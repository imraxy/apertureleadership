	<footer>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <div class="ex-copyrights">
                        <p class="mb-0 mt-0">© {{date('Y')}} @if(config('settings.copyright')) {{ config('settings.copyright') }} @else Copyrights reserved @endif</p>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="ex-footsocial">
                        <ul class="list-inline mb-0 mt-0 text-right">
                            <li class="list-inline-item">
                                <a href="{{ config('settings.facebook') ? : 'javascript:;' }}"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                            </li>
                            <li class="list-inline-item">
                                <a href="{{ config('settings.instagram') ? : 'javascript:;' }}"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                            </li>
                            <li class="list-inline-item">
                                <a href="{{ config('settings.youtube') ? : 'javascript:;' }}"><i class="fa fa-youtube-play" aria-hidden="true"></i></a>
                            </li>
                            <li class="list-inline-item">
                                <a href="{{ config('settings.twitter') ? : 'javascript:;' }}"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>