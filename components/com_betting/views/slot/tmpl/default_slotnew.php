<?php
defined('_JEXEC') or die('Restricted access');
//exit(json_encode($this->recset));

$allGameCategory = array();

foreach ($this->recset as $item )
{
    if( !(in_array($item->gameCategory, $allGameCategory) ) )
    {
        array_push($allGameCategory, $item->gameCategory);
    }
}
?>
<style>
    iframe{
        overflow:hidden;
    }
</style>
<section class="bannerTop">
    <div class="colLeft">
        <!--<div class="flexslider flexslider1">
            <ul class="slides">
                <li>
                    <img src="images/banner1.jpg" alt="">
                </li>
                <li>
                    <img src="images/banner2.jpg" alt="">
                </li>
                <li>
                    <img src="images/banner1.jpg" alt="">
                </li>
                <li>
                    <img src="images/banner2.jpg" alt="">
                </li>
            </ul>
        </div>-->

        <div class="owl-carousel gameSlider2 owl-theme">

            <div>
                <img src="images/banner11.jpg" alt="">
            </div>

            <div>
                <img src="images/banner12.jpg" alt="">
            </div>
        </div>
    </div>

    <div class="colRight">
        <div class="colRightRow">
            <div class="bannerThumb bannerThumb1" style="background-image:url(images/banner_thumb11.jpg);">
            </div>
            <div class="bannerThumb bannerThumb2" style="background-image:url(images/banner_thumb12.jpg);">
            </div>
        </div>
        <div class="colRightRow">
            <div class="bannerThumb bannerThumb3" style="background-image:url(images/banner_thumb13.jpg);">
            </div>
            <div class="bannerThumb bannerThumb4" style="background-image:url(images/banner_thumb14.jpg);">
            </div>
        </div>
    </div>
</section>
<!-- banner code end-->

<!-- Game Play Start -->

<div class="bodyWrapper gameDetailsWrap" style="display: none;" id="gamePlayDiv">
    <section class="gameDetailsBgWrap">
        <div class="gameDetailsBgInner">
            <div class="gamePlayArea">
                <div class="gamePlayArea-top clearfix">
                    <div class="fl">

                    </div>
                    <div class="fr">

                    </div>
                </div>

                <iframe scrolling="no" id="playScreen" src="about:blank" width="450" height="680" style="background: black;">
                </iframe>

            </div>
            <!---->
            <!--            <div class="gamePlayArea-bottom">-->
            <!--                <button type="button" class="playBtn">Play for Real Money</button>-->
            <!--            </div>-->
        </div>
        <span class="quickLink homeLink">
			<a href="javascript:void(0);" onclick="toggleFullScreen();" >Home</a>
		</span>
    </section>

    <main class="gameDetailsInnerWrap">
        <div class="gameDesc">
            <h1></h1>
            <p></p>
        </div>
    </main>
</div>

<!-- Game Play End -->


<!-- header start here-->
<header class="gameNav">
    <ul>

        <li class="active  allGame" data-filter="*">
            <a>
                <span>All</span>
            </a>
        </li>

        <?php
        foreach ( $allGameCategory as $value ) {
            ?>

            <li class="instantGame" data-filter=".<?php echo $value ?>">
                <a>
                    <span>
                        <?php
                        if( $value == "-HTML" )
                        {
                            $value = "HTML";
                            echo $value;
                        }
                        else
                        {
                            echo $value;
                        }
                        ?>
                    </span>
                </a>
            </li>

            <?php
        }
        ?>

    </ul>
</header>
<!-- header end here-->

<section class="gameListingWrap">
    <div class="gameListing grid">

        <?php
        foreach ($this->recset as $item) {
            $gameCategory=$item->gameCategory;
            $count =0;
            ?>
            <div class="cell grid-item <?php echo $item->gameCategory ?>">
                <div class="cellInnerWrap">
                    <a href="#" onclick="playGame('<?php echo addslashes($item->gameName);?>','<?php echo $item->gameImageLocations;?>','<?php echo addslashes($item->gameDescription);?>','', '<?php echo $gameCategory."_".$count;?>', '<?php echo $item->gameNumber;?>', '<?php echo $item->windowHeight;?>', '<?php echo $item->windowWidth;?>', '<?php echo $item->isHTML5;?>',this,'<?php echo $item->isOLD; ?>','<?php echo $item->background; ?>','<?php echo $item->isOldForNew; ?>');">
                        <figure>
                            <img src="<?php echo $item->gameImageLocations ?>" alt="<?php echo $item->gameName ?>">
                            <figcaption>
                                <div class="gameName"><?php echo $item->gameName ?></div>
                                <!--<div class="gamePrice"><span>$</span> <?php echo $item->gamePrice ?></div>-->

                            </figcaption>
                        </figure>
                    </a>

                </div>
            </div>

            <?php
        }
        ?>

    </div>
</section>


</div>


<script type="application/javascript">

    var globalHeight = "";
    var globalWidth = "";
    var screenRatio = "";
    var gameRatio = "";
    var innerHeight = "";
    var gameH = "";
    var gameW = "";
    var isGameOld = true;


    function retioCalculatorFull(screenRatio,gameRatio, height, width) {

        if( screenRatio >= gameRatio  )
        {

            innerHeight = $(window).innerHeight();

            height1 = innerHeight - 80;
            var change = ((height1 ) / (parseInt(height) + 80) ) * 100 ;

            height = height1;
            width = ( parseInt(width) ) * (change / 100);
        }
        else
        {
            width1 = $(window).innerWidth() - 80;
            var change1 = ((width1 ) / (parseInt(width)) ) * 100 ;

            width = width1;
            height = (parseInt(height) + 80) * (change1 / 100);
        }

        return [height,width];

    }


    function retioCalculator(screenRatio,gameRatio, height, width) {

        if( screenRatio >= gameRatio  )
        {
//            if( $(window).innerHeight() > 702 )
//                innerHeight = 702;
//            else
            innerHeight = $(window).innerHeight();

            height1 = innerHeight - 80;
            var change = ((height1 ) / (parseInt(height)) ) * 100 ;

            height = height1;
            width = ( parseInt(width) ) * (change / 100);
        }
        else
        {
            width1 = $(window).innerWidth() - 80;
            var change1 = ((width1 ) / (parseInt(width)) ) * 100 ;

            width = width1;
            height = (parseInt(height)) * (change1 / 100);
        }

        return [height,width];

    }

    function retioCalculatorFullHTML(screenRatio,gameRatio, height, width) {

        if( screenRatio >= gameRatio  )
        {

            innerHeight = $(window).innerHeight();

            height1 = innerHeight - 80;
            var change = ((height1 ) / (parseInt(height)) ) * 100 ;

            height = height1;
            width = ( parseInt(width) + 40 ) * (change / 100);
        }
        else
        {
            width1 = $(window).innerWidth() - 80;
            var change1 = ((width1 ) / (parseInt(width)) ) * 100 ;

            width = width1;
            height = (parseInt(height)) * (change1 / 100);
        }

        return [height,width];

    }


    function retioCalculatorHTML(screenRatio,gameRatio, height, width) {

        if( screenRatio >= gameRatio  )
        {
//            if( $(window).innerHeight() > 702 )
//                innerHeight = 702;
//            else
            innerHeight = $(window).innerHeight();

            height1 = innerHeight - 80;
            var change = ((height1 ) / (parseInt(height)) ) * 100 ;

            height = height1;
            width = ( parseInt(width) ) * (change / 100);
        }
        else
        {
            width1 = $(window).innerWidth() - 80;
            var change1 = ((width1 ) / (parseInt(width)) ) * 100 ;

            width = width1;
            height = (parseInt(height)) * (change1 / 100);
        }

        return [height,width];

    }

    <?php
    $deviceType = Configuration::getDeviceType();
    $appTypeAndClientType = Configuration::getAppAndClientType($deviceType);
    ?>

    var clientType = "<?php echo $appTypeAndClientType['CLIENTTYPE']; ?>";
    var appType = "<?php echo $appTypeAndClientType['APPTYPE']; ?>";
    var userAgentIge = encodeURI("<?php echo Configuration::getDevice(); ?>");
    var deviceType = "<?php echo $deviceType; ?>";

    function playGame(gameName,imagePath,howToPlay,Price,clkGameId,gameNumber,height,width,isHTML5,e,isOLD,background,isOldForNew) {
        if( isOLD == "false" && $('body').hasClass('pre-login') )
        {
            $("#home_login").modal('show');
            return false;
        }
        $(".bannerTop").hide();
        $("#playScreen").hide();
        $(".gamePlayArea").hide();
        $('#playScreen').attr('src','about:blank');

        var playerId = '<?php echo Utilities::getPlayerId(); ?>';
        var merchantSessionId = '<?php echo Utilities::getPlayerToken(); ?>';
        var domainName='<?php echo $this->serviceData['domainName']; ?>';
        var merchantKey='<?php echo $this->serviceData['merchantKey']; ?>';
        var secureKey='<?php echo $this->serviceData['secureCode']; ?>';
        var currencyCode='<?php echo Configuration::getCurrencyDetails()['curCode']; ?>';
        var lang='<?php echo $this->serviceData['lang']; ?>';
        var path = "<?php echo Configuration::DOMAIN ?>/slot-page";
        var igePath = "<?php echo $this->igepathhtml5; ?>";
        isGameOld = isOLD == 'true' ? true : false;

        gameH = height;
        gameW = width;

        $(".gameDetailsBgInner").css('background-image','url(' + background + ')');

        if( isOLD )
        {
            path = path+'?gameNumber='+gameNumber+'&gameMode=try&domainName='+domainName+'&merchantKey='+merchantKey+'&secureKey='+secureKey+'&currencyCode='+currencyCode+'&lang='+lang+'&gameType=scratch&playerId='+playerId+'&merchantSessionId='+merchantSessionId+"&clientType="+clientType+"&deviceType="+deviceType+"&appType="+appType+"&userAgentIge="+userAgentIge+"&path="+igePath+"&height="+height+"&width="+width+"&isOLD="+isOLD+"&isOldForNew="+isOldForNew;
        }
        else
        {
            path = path+'?gameNumber='+gameNumber+'&gameMode=try&domainName='+domainName+'&merchantKey='+merchantKey+'&secureKey='+secureKey+'&currencyCode='+currencyCode+'&lang='+lang+'&gameType=flash&playerId='+playerId+'&merchantSessionId='+merchantSessionId+"&clientType="+clientType+"&deviceType="+deviceType+"&appType="+appType+"&userAgentIge="+userAgentIge+"&path="+igePath+"&height="+height+"&width="+width+"&isOLD="+isOLD+"&isOldForNew="+isOldForNew;
        }

        if( isOLD == 'false' )
        {
            $('#playScreen').attr('height','600px');
            $('#playScreen').css('height','');
            $('#playScreen').attr('width','800px');
            $('#playScreen').css('width','');

            globalHeight = 600;
            globalWidth = 800;

            htmlGameResizer();
        }
        else
        {
            $('#playScreen').attr('height','600px');
            $('#playScreen').css('height','600px');
            $('#playScreen').attr('width','800px');
            $('#playScreen').css('width','600px');

            gameRatio = width / (parseInt(height)) ;
            screenRatio = ( $(window).innerWidth() - 80) / ($(window).innerHeight() - 80) ;


//            if( $(window).innerHeight() >= 702 )
//            {
//
////                screenRatio = ( window.innerWidth - 80 ) / 622 ;
//                screenRatio = ( parseInt($("#sp-main-body").width()) - 50 ) / ( 622 );
//
//                var newData = retioCalculator(screenRatio,gameRatio, height, width);
//
//                $("#playScreen").css("height",newData[0]);
//                $("#playScreen").css("width",newData[1]);
//
//                globalHeight = parseInt(height)+80;
//                globalWidth = width;
//
//            }
//            else
//            {

            var newData = retioCalculator(screenRatio,gameRatio, height, width);

            $("#playScreen").css("height",newData[0]);
            $("#playScreen").css("width",newData[1]);

            globalHeight = newData[0];
            globalWidth = newData[1];
//            }

        }

        $('#playScreen').attr('src',path);

        location.hash = '#playScreen';

        $("#gamePlayDiv").show();
        $("#playScreen").show();
        $(".gamePlayArea").show();


        var gap = $('.gameDetailsBgInner').offset().top;
        $('html,body').animate({ scrollTop: gap }, 'slow');

    }

    function toggleFullScreen() {
        var element = document.getElementById('gamePlayDiv');
//        $("#gamePlayDiv").css('height',"100%");
//        $("#gamePlayDiv .gameDetailsBgInner").css('height',"100%");
//        $("#gamePlayDiv").css('width',"100%");

        if ((document.fullScreenElement && document.fullScreenElement !== null) ||
            (!document.mozFullScreen && !document.webkitIsFullScreen)) {
            $("#gamePlayDiv").addClass('fullScreeOn');
            if (element.requestFullScreen) {
                element.requestFullScreen();
            } else if (element.mozRequestFullScreen) {
                element.mozRequestFullScreen();
            } else if (element.webkitRequestFullScreen) {
                element.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
            }

            if( isGameOld == false )
            {
                $('#playScreen').attr('height','600px');
                $('#playScreen').css('height','');
                $('#playScreen').attr('width','800px');
                $('#playScreen').css('width','');

                globalHeight = 600;
                globalWidth = 800;

                htmlGameResizer();
            }
            else
            {
                gameRatio = gameW / (parseInt(gameH)) ;
                screenRatio = ( $(window).innerWidth() - 80) / ($(window).innerHeight() - 80) ;


//                if( $(window).innerHeight() >= 702 )
//                {
//
//                    //                screenRatio = ( window.innerWidth - 80 ) / 622 ;
//                    screenRatio = ( $(window).innerWidth() - 80 ) / ( $(window).innerHeight() - 80 );
//
//                    var newData = retioCalculatorFull(screenRatio,gameRatio, gameH, gameW);
//
//                    $("#playScreen").css("height",newData[0]);
//                    $("#playScreen").css("width",newData[1]);
//
//                    globalHeight = parseInt(gameH)+80;
//                    globalWidth = gameW;
//
//                }
//                else
//                {

                screenRatio = ( $(window).innerWidth() - 80) / ($(window).innerHeight() - 80) ;
                var newData = retioCalculator(screenRatio,gameRatio, gameH, gameW);

                $("#playScreen").css("height",newData[0]);
                $("#playScreen").css("width",newData[1]);

//                    globalHeight = parseInt(gameH)+80;
//                    globalWidth = gameW;
//                }
            }
        }
        else
        {
            $("#gamePlayDiv").removeClass('fullScreeOn');
            if (document.cancelFullScreen) {
                document.cancelFullScreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitCancelFullScreen) {
                document.webkitCancelFullScreen();
            }


            if( isGameOld == false )
            {
                $('#playScreen').attr('height','600px');
                $('#playScreen').css('height','');
                $('#playScreen').attr('width','800px');
                $('#playScreen').css('width','');


                $("#playScreen").css("height",globalHeight);
                $("#playScreen").css("width",globalWidth);

            }
            else
            {

                gameRatio = gameW / (parseInt(gameH)) ;
                screenRatio = ( $(window).innerWidth() - 80) / ($(window).innerHeight() - 80) ;


//                if( $(window).innerHeight() >= 702 )
//                {
//
//                    //                screenRatio = ( window.innerWidth - 80 ) / 622 ;
//                    screenRatio = ( $(window).innerWidth() - 80 ) / ( $(window).innerHeight() - 80 );
//
//                    var newData = retioCalculator(screenRatio,gameRatio, gameH, gameW);
//
//                    $("#playScreen").css("height",newData[0]);
//                    $("#playScreen").css("width",newData[1]);
//
//                    globalHeight = parseInt(gameH)+80;
//                    globalWidth = gameW;
//
//                }
//                else
//                {

                var newData = retioCalculator(screenRatio,gameRatio, gameH, gameW);

                $("#playScreen").css("height",globalHeight);
                $("#playScreen").css("width",globalWidth);

//                    globalHeight = parseInt(gameH)+80;
//                    globalWidth = gameW;
//                }
            }
        }

    }

    $(document).on("keypress", function(e){

        var key = e.which ? e.which : e.keyCode;
        var key1 = String.fromCharCode(key);

        if( key == 27 && $("#gamePlayDiv").hasClass('fullScreeOn') )
        {
            toggleFullScreen();
        }
    });


    function htmlGameResizer()
    {
        gameRatio = gameW / (parseInt(gameH)) ;
        screenRatio = ( $(window).innerWidth() - 80) / ($(window).innerHeight() - 80) ;


//        if( $(window).innerHeight() >= 702 )
//        {
//
//            screenRatio = ( $(window).innerWidth() - 80 ) / ( $(window).innerHeight() - 80 );
//
//            var newData = retioCalculatorFullHTML(screenRatio,gameRatio, gameH, gameW);
//
//            $("#playScreen").css("height",newData[0]);
//            $("#playScreen").css("width",newData[1]);
//
//            globalHeight = parseInt(gameH)+80;
//            globalWidth = gameW;
//
//        }
//        else
//        {

        var newData = retioCalculatorFullHTML(screenRatio,gameRatio, gameH, gameW);

        $("#playScreen").css("height",newData[0]);
        $("#playScreen").css("width",newData[1]);

        globalHeight = newData[0];
        globalWidth = newData[1];
//        }
    }




function backToLobby()
{

        $('#playScreen').attr('src','about:blank');

        $("#gamePlayDiv").hide();
        $("#playScreen").hide();
        $(".gamePlayArea").hide();

        $(".bannerTop").show();
}


window.addEventListener("message", function(event) {
            console.log("Hello from " + event.data);
            if( event.data == "backToLobby" )
		backToLobby();
        });

</script>
