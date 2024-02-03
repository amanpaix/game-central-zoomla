<?php defined( '_JEXEC' ) or die;
//error_reporting(E_ALL);
include_once JPATH_THEMES.'/'.$this->template.'/logic.php';
require_once JPATH_BETTING_COMPONENT.'/helpers/Campaign.php';
//Campaign::prepareCampaignTracking();

?>

<!doctype html>

<html lang="<?php echo $this->language; ?>" >




<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <link rel="apple-touch-icon-precomposed" href="<?php echo $tpath; ?>/images/apple-touch-icon-57x57-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $tpath; ?>/images/apple-touch-icon-72x72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $tpath; ?>/images/apple-touch-icon-114x114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $tpath; ?>/images/apple-touch-icon-144x144-precomposed.png">

    <jdoc:include type="head" />

    <?php
    JHtml::_('jquery.framework');
    //JHtml::_('bootstrap.framework');
    Html::addJs(JUri::base()."templates/shaper_helix3/js/bootstrap.min.js");
    Html::addCss(JUri::base()."templates/shaper_helix3/css/font-awesome.min.css");
    Html::addCss(JUri::base()."templates/shaper_helix3/css/customstyle.css?v=".Constants::CSS_VER);
    JHtml::script(JUri::base()."templates/shaper_helix3/js/custom/common.js?v=".Constants::JS_VER);
    ?>

    <!-- <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-28561360-7', 'auto');
      ga('send', 'pageview');

    </script> -->
<!--    <meta property="fb:app_id" content="179383045799673" />
    <meta property="og:url"                content="<?php //echo "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>" />
    <meta property="og:type"               content="website" />
    <meta property="og:title"              content="Refer A Friend and Get Rs. 1,000 Bonus Free, at KhelPlayRummy.com" />
    <meta property="og:description"        content="Refer your friend and get Rs. 1,000 bonus Free. Get Rs.1,000 bonus on friends first deposit. A referrer is eligible for Rs. 1,250/- cash bonus when his Referee makes a first deposit." />
    <meta property="og:image"              content="https://webportal-kpr.s3.amazonaws.com/images/promotions/common/refer-a-friend/promotion-FB-526x275.jpg" />-->
<!--	<link rel="stylesheet" href="http://Betting-demo-new.lottoBetting.com/templates/landing_page/1000-welcome-bonus/style.css?v=10.6" type="text/css" />-->
<!--	<script src="http://Betting-demo-new.lottoBetting.com/templates/landing_page/1000-welcome-bonus/rxvalidation.js?v=10.6" type="text/javascript"></script>-->
</head>

<body class="<?php echo (($menu->getActive() == $menu->getDefault()) ? ('front') : ('site')).' '.$active->alias.' '.$pageclass; ?> <?php if(Session::sessionValidate()){ echo "post-login";}else {echo "pre-login";}?>">
<jdoc:include type="modules" name="body" />
<jdoc:include type="component" />
<jdoc:include type="modules" name="footer" />

<script>
    var myDeviceType = '<?php echo Configuration::getDeviceType() ?>';
    var myOsType = '<?php echo Configuration::getOS() ?>';
    var myId = '<?php echo Utilities::getPlayerId(); ?>';
    var myCurrency = '<?php echo Constants::MYCURRENCYSYMBOL; ?>';
    var webSocketDomain = '<?php echo Configuration::WEB_SOCKET_DOMAIN ?>';
</script>

<script>

    var reEncStr = '<?php echo Session::getSessionVariable('reEncString'); ?>';
</script>



</body>
</html>

