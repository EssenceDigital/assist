@extends('layouts.site')

@section('page-title', 'Heritage Resource Consultants | Historical Impact Assessments | Alberta - Saskatchewan - BC')

@section('styles')

    <!-- REVOLUTION STYLE SHEETS -->
    <link rel="stylesheet" type="text/css" href="css/site/revolution/settings.css" />
    <link rel="stylesheet" type="text/css" href="css/site/revolution/navigation.css" />
    <link rel="stylesheet" type="text/css" href="css/site/revolution/pe-icon-7-stroke/css/pe-icon-7-stroke.css">

@endsection

@section('content')

        <section>
            <div class="tbg notbg" id="home">
                <div class="row">
                    <div class="col-md-12">
                        <div class="creative-slider">
                            <div id="rev_slider_4_1_wrapper" class="rev_slider_wrapper fullwidthbanner-container" data-alias="classicslider1">
                                <div id="rev_slider_4_1" class="rev_slider fullwidthabanner" style="display:none;">
                                    <ul> 
                                        <li data-index="rs-1" data-transition="random" data-slotamount="default" data-hideafterloop="0"  data-easein="default" data-easeout="default" data-masterspeed="default" data-rotate="0"  data-saveperformance="off" data-param1="" data-param2="" data-param3="" data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9="" data-param10="" data-title="">
                                            <!-- MAIN IMAGE -->
                                            <img src="images/home-landing.jpg" alt="" data-bgposition="center center"  data-bgfit="cover" data-bgrepeat="no-repeat" data-bgparallax="10" data-kenburns="on" data-duration="10000" data-ease="Linear.easeNone" data-scalestart="100" data-scaleend="120" class="rev-slidebg" data-no-retina />

                                            <!-- LAYER NR. 1 -->
                                            <span class="tp-caption sl1-ly1 tp-resizeme rs-parallaxlevel-3" 
                                                  id="slide1-layer-1" 
                                                  data-x="center" data-hoffset="" 
                                                  data-y="center" data-voffset="-100" 
                                                  data-width="['auto','auto','auto','auto']"
                                                  data-height="['auto','auto','auto','auto']"
                                                  data-transform_idle="o:1;"
                                                  data-transform_in="y:[100%];z:0;rZ:-35deg;sX:1;sY:1;skX:0;skY:0;s:2000;e:Power4.easeInOut;" 
                                                  data-transform_out="s:1000;e:Power2.easeInOut;s:1000;e:Power2.easeInOut;" 
                                                  data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"                                       
                                                  data-start="1000" 
                                                  data-splitin="chars" 
                                                  data-splitout="none"                                      
                                                  data-responsive_offset="on" 
                                                  data-elementdelay="0.05" 
                                                  style="font-size:26px;letter-spacing:0.3px;">Heritage
                                            </span>         

                                            <!-- LAYER NR. 2 -->
                                            <h4 class="tp-caption sl1-ly2 tp-resizeme rs-parallaxlevel-3" 
                                                id="slide1-layer2" 
                                                data-x="center" data-hoffset="" 
                                                data-y="center" data-voffset="-40" 
                                                data-width="['auto','auto','auto','auto']"
                                                data-height="['auto','auto','auto','auto']"
                                                data-transform_idle="o:1;"
                                                data-transform_in="y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;s:2000;e:Power4.easeInOut;" 
                                                data-transform_out="s:1000;e:Power3.easeInOut;s:1000;e:Power3.easeInOut;"
                                                data-start="2000" 
                                                data-splitin="none" 
                                                data-splitout="none" 
                                                data-responsive_offset="on" 
                                                data-elementdelay="0.05"                                    
                                                style="font-size:48px;letter-spacing:.3px;">Resource Consultants
                                            </h4>

                                        </li>                                     
                                    </ul>
                                </div>
                            </div>
                        </div><!-- Creative Slider  -->
                    </div>
                </div>      
            </div>
        </section><!-- Slider Sec -->

        <section style="margin-bottom: 105px;">
            <div class="tbg nobg">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="ab-arch">
                                <span class="sd-tl">About Us</span>
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="abu-arch-tl">
                                            <h4>About Arrow Archaeology</h4>
                                            <p>Arrow provides a full range of archaeological, palaeontological, and cultural resource management services. We are capable of undertaking projects of any size, type, and duration in western and northern Canada. Our services include, but are not limited to:</p>
                                            <p>
                                                <ol>
                                                    <li>Historical Resource Overviews</li>
                                                    <li>Heritage Resources Impact Assessments</li>
                                                    <li>Mitigation Planning and Execution</li>
                                                    <li>Construction Monitoring</li>
                                                    <li>Mapping</li>
                                                    <li>Archival and Related Research</li>
                                                    <li>Client Training and Traditional</li>
                                                    <li>Land Use Studies</li>
                                                </ol>
                                            </p>
                                            <div class="ct-in"><span>Call Us At:</span> <a href="tel:+14033452812">(403) 345-2812</a></div>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="abu-gal">
                                            <ul>
                                                <li><img src="images/home-1.jpg" alt="" /></li>
                                                <li class="active"><img src="images/home-2.jpg" alt="" /></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- About Architecture -->
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="tbg ls-g"></div>
        </section>

@endsection

@section('scripts')

    <!-- REVOLUTION JS FILES -->
    <script type="text/javascript" src="js/site/revolution/jquery.themepunch.tools.min.js"></script>
    <script type="text/javascript" src="js/site/revolution/jquery.themepunch.revolution.min.js"></script>
    <!-- SLIDER REVOLUTION 5.0 EXTENSIONS -->   
    <script type="text/javascript" src="js/site/revolution/revolution.extension.actions.min.js"></script>
    <script type="text/javascript" src="js/site/revolution/revolution.extension.carousel.min.js"></script>
    <script type="text/javascript" src="js/site/revolution/revolution.extension.kenburn.min.js"></script>
    <script type="text/javascript" src="js/site/revolution/revolution.extension.layeranimation.min.js"></script>
    <script type="text/javascript" src="js/site/revolution/revolution.extension.migration.min.js"></script>
    <script type="text/javascript" src="js/site/revolution/revolution.extension.navigation.min.js"></script>
    <script type="text/javascript" src="js/site/revolution/revolution.extension.parallax.min.js"></script>
    <script type="text/javascript" src="js/site/revolution/revolution.extension.slideanims.min.js"></script>
    <script type="text/javascript" src="js/site/revolution/revolution.extension.video.min.js"></script>
    <script type="text/javascript" src="js/site/revolution/revolution.initialize.js"></script>

    <script>
        $('#contact-link').click(function(){
            $('.rsnp-mnu').removeClass('active');
        });
    </script>

@endsection