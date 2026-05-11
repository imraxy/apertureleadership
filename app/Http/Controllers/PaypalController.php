<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Session;
use App\User;
use App\Models\Cart;

/** All Paypal Details class **/
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;


class PaypalController extends Controller
{
    private $_api_context;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        /** setup PayPal api context **/
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'],$paypal_conf['secret']));
        $this->_api_context->setConfig($paypal_conf['settings']);
    }
    /**
     * Show the application paywith paypalpage.
     *
     * @return \Illuminate\Http\Response
     */
    public function payWithPaypal()
    {
        return view('paywithpaypal');
    }
    /**
     * Store a details of payment with paypal.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postPaymentWithpaypal(Request $request, $folder_id)
    {

        $folder_id = base64_decode($folder_id);

        $to_folder_id = trim($folder_id, "&".auth::user()->user_name);

        $check_folder = Cart::where('id', $to_folder_id)->where('user_id', Auth::id())->first();

        if(!$check_folder) {

            return redirect(route('account.folders'))->with('error', 'Unknown error occurred.');

        }

        $item_name = $check_folder->sessionimage->title;

        $item_description = $check_folder->sessionimage->description;
        
        // Via a request instance...
        $request->session()->put('to_folder_id', $check_folder->id); //User folder cart id
        $request->session()->put('session_image_id', $check_folder->session_image_id); //User folder session_image_id

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item_1 = new Item();
        $item_1->setName($item_name) /** item name **/
                ->setCurrency('USD')
                ->setQuantity(1)
                ->setPrice(10); /** unit price **/ //$request->get('amount')

        $item_list = new ItemList();
        $item_list->setItems(array($item_1));

        $amount = new Amount();
        $amount->setCurrency('USD')
                ->setTotal(10);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
                ->setItemList($item_list)
                ->setDescription($item_description);

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::route('payment.status')) /** Specify return URL **/
                ->setCancelUrl(URL::route('payment.status'));

        $payment = new Payment();
        $payment->setIntent('Sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirect_urls)
                ->setTransactions(array($transaction));
            
            /** dd($payment->create($this->_api_context));exit; **/

        try {

            $payment->create($this->_api_context);

            // Get PayPal redirect URL and redirect the customer
            $approvalUrl = $payment->getApprovalLink();

        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            
            // Forget multiple keys...
            $request->session()->forget(['to_folder_id', 'session_image_id']);

            if (\Config::get('app.debug')) {
                
                return redirect(route('account.folders'))->with('error', 'Connection timeout.');

            } else {

                return redirect(route('account.folders'))->with('error', 'Some error occur, sorry for inconvenient.');
            
			}
        }

        foreach($payment->getLinks() as $link) {
            
            if($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }

        /** add payment ID to session **/
        $request->session()->put('paypal_payment_id', $payment->getId());

        if(isset($redirect_url)) {
            /** redirect to paypal **/
            //return Redirect::away($redirect_url);
            return redirect($redirect_url);
        }

        return redirect(route('account.folders'))->with('error', 'Unknown error occurred.');

    }

    public function getPaymentStatus(Request $request)
    {
        /** Get the payment ID before session clear **/
        $payment_id = Session::get('paypal_payment_id');
        // Get payment object by passing paymentId
        $paymentId = $request->paymentId;
        $payerId = $request->PayerID;
        $paypal_token = $request->token;
        
        if (empty($payerId) || empty($paypal_token)) {

            // Forget multiple keys...
            $request->session()->forget(['to_folder_id', 'session_image_id', 'paypal_payment_id']);

            return redirect(route('account.folders'))->with('error', 'Payment failed.');
        }

        $payment = Payment::get($payment_id, $this->_api_context);

        /** PaymentExecution object includes information necessary **/
        /** to execute a PayPal account payment. **/
        /** The payer_id is added to the request query parameters **/
        /** when the user is redirected from paypal back to your site **/
        $execution = new PaymentExecution();

        $execution->setPayerId($payerId);
        
        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);
        
       
        if ($result->getState() == 'approved') { 
            
            
            $paymentArray['transaction_id'] = $payment_id;
            $paymentArray['amount'] = 1;
            $paymentArray['payment_status'] = 'approved'; 
            $paymentArray['user_id'] = Auth::id();
            $paymentArray['session_image_id'] = $request->session()->get('session_image_id');
            $paymentArray['cart_id'] = $request->session()->get('to_folder_id');
            
            \App\Models\Payment::create($paymentArray);

            // Forget multiple keys...
            $request->session()->forget(['to_folder_id', 'session_image_id', 'paypal_payment_id']);

            /** it's all right **/
            /** Here Write your database logic like that insert record or value in database if you want **/
            return redirect(route('account.folders'))->with('success', 'Payment success.');
        }

        // Forget multiple keys...
        $request->session()->forget(['to_folder_id', 'session_image_id', 'paypal_payment_id']);

        return redirect(route('account.folders'))->with('error', 'Payment failed.');
    }
}
