<?php defined( '_JEXEC' ) or die; 

include_once JPATH_THEMES.'/'.$this->template.'/logic.php';
require_once JPATH_BETTING_COMPONENT.'/helpers/Includes.php';


//Campaign::prepareCampaignTracking();

?><!doctype html>
<html lang="<?php echo $this->language; ?>">

<head>
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" /> 
<param name="wmode" value="transparent"></param>
 <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />



  <jdoc:include type="head" />
  <?php 
	JHtml::_('jquery.framework');
//	JHtml::_('bootstrap.framework');

?>
  
  <link rel="apple-touch-icon-precomposed" href="<?php echo $tpath; ?>/images/apple-touch-icon-57x57-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $tpath; ?>/images/apple-touch-icon-72x72-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $tpath; ?>/images/apple-touch-icon-114x114-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $tpath; ?>/images/apple-touch-icon-144x144-precomposed.png">

<!-- <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-28561360-7', 'auto');
  ga('send', 'pageview');

</script> -->

    <meta name="theme-color" content="#400c00">
    <meta name="msapplication-navbutton-color" content="green" />
    <meta name="apple-mobile-web-app-status-bar-style" content="green">
    <link rel="canonical" href="<?php echo "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>" />

    <link href="/templates/shaper_helix3/css/template.css" rel="stylesheet" type="text/css" />
    <link href="/templates/shaper_helix3/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
     <link href="/templates/shaper_helix3/css/customstyle.css" rel="stylesheet" />
     <link href="/templates/shaper_helix3/css/font-awesome.min.css" rel="stylesheet" />
   
    <script src="/templates/shaper_helix3/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="/templates/shaper_helix3/js/jquery.sticky.js" type="text/javascript"></script>
    <script src="/templates/shaper_helix3/js/main.js" type="text/javascript"></script>
    <script src="/templates/shaper_helix3/js/frontend-edit.js" type="text/javascript"></script>
    <script src="/media/system/js/core.js" type="text/javascript"></script>
    
    
    
    <script src="/templates/shaper_helix3/js/jquery.validate.min.js?v=1.1" type="text/javascript"></script>
    <script src="/templates/shaper_helix3/js/jquery.validate2.additional-methods.min.js?v=1.1" type="text/javascript"></script>
    <script src="/templates/shaper_helix3/js/MD5.min.js?v=1.1" type="text/javascript"></script>
   
    <script src="/templates/shaper_helix3/js/custom/common.js?v=<?php echo Constants::JS_VER ?>" type="text/javascript"></script>
    <link rel="stylesheet" href="/templates/shaper_helix3/css/customstyle.css?v=<?php echo Constants::CSS_VER ?>" />
    <script src="/templates/blank/js/mobile-pages.js?v=<?php echo Constants::JS_VER ?>" type="text/javascript"></script>

</head>

<script>
    $(function(){
        if ($(window).width() > 1024){

            $(window).scroll(function(){
                ($(window).scrollTop()> 8)? $('body').addClass('fixed') : $('body').removeClass('fixed');
                if(!!window.chrome){
                    $('.leftPart').css('top', $(window).scrollTop() + 'px');
                }
            })

        }
    });
</script>




<script>
    var myDeviceType = '<?php echo Configuration::getDeviceType() ?>';
    var myOsType = '<?php echo Configuration::getOS() ?>';
    var myId = '<?php echo Utilities::getPlayerId(); ?>';
    var myCurrency = '<?php echo Constants::MYCURRENCYSYMBOL; ?>';
    var webSocketDomain = '<?php echo Configuration::WEB_SOCKET_DOMAIN ?>';
    var domain = '<?php echo Configuration::DOMAIN ?>';
</script>
<body class="<?php echo (($menu->getActive() == $menu->getDefault()) ? ('front') : ('site')).' '.$active->alias.' '.$pageclass; ?> <?php if(Session::sessionValidate()){ echo "post-login";}else {echo "pre-login";}?> ">
  <!--
 <noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-WX29X3" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','GTM-WX29X3');</script>
  -->
  <jdoc:include type="modules" name="frontend_header" />
  <jdoc:include type="modules" name="debug" />
  <jdoc:include type="component" />
  <jdoc:include type="modules" name="frontend_footer" />
  <jdoc:include type="modules" name="header" />



</body>

</html>
