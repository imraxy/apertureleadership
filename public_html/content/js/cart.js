	/**
	 * Snackbar / Toast
	 * Snackbars are often used as a tooltips/popups to show a message at the bottom of the screen.
	 */

	function snackbarFunction() {
		var x = document.getElementById("snackbar");
		x.className = "_show";
		setTimeout(function(){ x.className = x.className.replace("_show", ""); }, 3000);
	}

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	function perform_add_to_cart(cart_id, userid) {
		
		var api_url = $("#root").attr('data-root');
		
		var ajax_data = {cart_id: cart_id};
			
		$.ajax({
			type : 'GET',
			dataType: 'json',
			cache: false,
			url: api_url + '/cart/add_item',
			data: 'cart_id='+cart_id,
			headers: {
				userid: userid,
				'Content-Type':'application/json'
			},
            success : function(resp)
            {
                //console.log(resp);
                if(resp.error){
                    snackbarFunction();
                    $("#snackbar").html(resp.error);
					if(resp.unauthenticated) {
						location.href = api_url+'/login';
					}	
                    return false;
                }
                snackbarFunction();
                $("#snackbar").html(resp.snackbar);   
            }   
        });
	}

	$(document).on('click', '.addtocartbtn', function () {  

		var $this = $(this);
		
		var cart_id = $this.data('val');
		
		var userid = $(".userid").val();
		
		perform_add_to_cart(cart_id, userid);
		
	});