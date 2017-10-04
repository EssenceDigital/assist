<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Arrow Archaeology | @yield('page-title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Arrow provides a full range of archaeological, palaeontological, and cultural resource management services." />
    <meta name="author" content="Codename Digital" />

    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="{{ url('css/site/bootstrap.min.css') }}" /><!-- Bootstrap -->
    <link rel="stylesheet" type="text/css" href="{{ url('css/site/icons.css') }}" /><!-- Font Awesome -->
    <link rel="stylesheet" type="text/css" href="{{ url('css/site/flaticon.css') }}" /><!-- Flat Icons -->
    <link rel="stylesheet" type="text/css" href="{{ url('css/site/style.css') }}" /><!-- Style -->
    <link rel="stylesheet" type="text/css" href="{{ url('css/site/responsive.css') }}" /><!-- Responsive -->
    
    <!-- Color Scheme -->
    <link rel="stylesheet" type="text/css" href="css/site/colors/color.css" title="color" /><!-- Color -->

    <link rel="apple-touch-icon" sizes="57x57" href="{{ url('images/favicons/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ url('images/favicons/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ url('images/favicons/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ url('images/favicons/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ url('images/favicons/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ url('images/favicons/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ url('images/favicons/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ url('images/favicons/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ url('images/favicons/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ url('images/favicons/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ url('images/favicons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ url('images/favicons/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ url('images/favicons/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ url('images/favicons/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ url('images/favicons/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">

    @yield('styles')

</head>
<body itemscope>
    <div class="loader-wrapper">
        <div class="loader">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>
    </div>
    <div class="web-lyut">
        <header class="style2">
            <div class="container">
                <div class="lg-mn-sec">
                    <div class="logo">
                        <h1><a href="/" title=""><img src="images/nav-logo.png" alt="" /></a></h1>
                    </div>
                    <nav>
                        <ul>
                            <li><a href="/" title="">Home</a></li>
                            <li><a href="/profile" title="">Profile</a></li>
                            <li><a href="/services" title="">Services</a></li>
                            <li><a href="/projects" title="">Projects</a></li>
                            <li><a href="/team" title="">Team</a></li>
                            <li><a href="#contact" title="">Contact</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header><!-- Header Style 2 -->

        <div class="rspn-hdr">

            <div class="lg-mn">
                <div class="logo">
                    <h1><a href="index.html" title=""><img src="images/mobile-logo.png" alt="" /></a></h1>
                </div>
                <span class="rspn-mnu-btn"><i class="fa fa-list-ul"></i></span>
            </div>
            <div class="rsnp-mnu">
                <span class="rspn-mnu-cls"><i class="fa fa-times"></i></span>
                <ul>
                    <li><a href="/" title="">Home</a></li>
                    <li><a href="/profile" title="">Profile</a></li>
                    <li><a href="/services" title="">Services</a></li>
                    <li><a href="/projects" title="">Projects</a></li>
                    <li><a href="/team" title="">Team</a></li>
                    <li><a id="contact-link" href="#contact" title="">Contact</a></li>
                </ul>
            </div><!-- Responsive Menu -->
        </div><!-- Responsive Header -->

        @yield('content')


        <footer>
            <div class="tbg notbg" id="contact">
                <div class="stl-bg2" style="background: url(images/footer-bg.png) no-repeat scroll center / cover;"></div>
                <div class="container">
                    <div class="row">
                        <div class="col-md-7 col-centered">
                            <div class="ftr-dta overlape">
                                <div class="row mrg">
                                    <div class="col-md-12">
                                        <div id="black-background" class="kpt-cnt">
                                            <div class="ftr-lgo-tl">
                                                <div class="logo"><h1><a href="#" title=""><img src="images/wt-logo.png" alt="" /></a></h1></div>
                                                <div class="wdg-tl">
                                                    <h4>Contact<br /> Arrow</h4>
                                                </div>
                                            </div>
                                            <div class="kpt-cnt-tabs">                        
                                                <div class="tab-content">
                                                    <div class="tab-pane fade in active" id="ft-tb1">
                                                        <ul class="ft-cnt-inf"> 
                                                            <li>
                                                                <i class="flaticon-home-icon-silhouette"></i>
                                                                <strong>Address:</strong>
                                                                2315 20 Street, Coaldale AB, T1M 1G5
                                                                <span>01.</span>
                                                            </li>

                                                            <li>
                                                                <i class="flaticon-phone-book"></i>
                                                                <strong>Call Us:</strong>
                                                                <a href="tel:+14033452812">(403) 345-2812</a>
                                                                <span>02.</span>
                                                            </li>

                                                            <li>
                                                                <i class="flaticon-black-back-closed-envelope-shape"></i>
                                                                <strong>Email:</strong>
                                                                <a href="mailto:nmirau@shaw.ca?Subject=Arrow%20Enquiry" target="_top">nmirau@shaw.ca</a>
                                                                <span>03.</span>
                                                            </li>                                                            
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer><!-- Footer -->
        <div class="btmbar">
            <div class="container">
                <p>Copyright &copy; 2017 - <a href="#" title=""> Arrow Archaeology</a> All Rights Reserved</p>
                <ul class="btm-lnks">
                    <li><a href="/" title="">Home</a></li>
                    <li><a href="/profile" title="">Profile</a></li>
                    <li><a href="/services" title="">Services</a></li>
                    <li><a href="/projects" title="">Projects</a></li>
                    <li><a href="/team" title="">Team</a></li>
                    <li><a href="#contact" title="">Contact</a></li>
                </ul>
            </div>
        </div><!-- Bottom Bar -->

    </div><!-- Web layout -->

    <!-- Scripts -->
    <script src="js/site/jquery.min.js" type="text/javascript"></script><!-- jQuery -->
    <script src="js/site/bootstrap.min.js" type="text/javascript"></script><!-- Bootstrap -->
    <script src="js/site/owl.carousel.min.js" type="text/javascript"></script><!-- Owl Carousel --> 
    <script src="js/site/isotope.min.js" type="text/javascript"></script><!-- Isotope -->
    <script src="js/site/isotope-int.js" type="text/javascript"></script><!-- Isotope -->    
    <script src="js/site/waypoints.min.js" type="text/javascript"></script><!-- WayPoints --> 
    <script src="js/site/counterup.min.js" type="text/javascript"></script><!-- Counter Up -->
    <script src="js/site/jquery.circliful.min.js" type="text/javascript"></script><!-- Progressbar -->
    <script src="js/site/swiper.jquery.min.js" type="text/javascript"></script><!-- Swiper Slider -->
    <script src="js/site/perfect-scrollbar.js" type="text/javascript"></script><!-- Scroll Bar -->
    <script src="js/site/perfect-scrollbar.jquery.js" type="text/javascript"></script><!-- Scroll Bar -->
    <script src="js/site/parallax.js" type="text/javascript"></script><!-- Parallax -->
    <script src="js/site/script.js" type="text/javascript"></script><!-- Custom -->

    @yield('scripts')

</html>