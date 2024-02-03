<?php
//exit(json_encode($this));
defined('_JEXEC') or die('Restricted access');
$lastTicketPurchaseData = Session::getSessionVariable('LOTTERY_lastTicketPurchaseData');
if($lastTicketPurchaseData !== false) {
    if(Utilities::getPlayerToken() === false) {
        Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
    }

    if(Session::getSessionVariable('LOTTERY_lastpage') == Session::getSessionVariable('LOTTERY_pageToGo')) {
        Session::unsetSessionVariable('LOTTERY_lastTicketPurchaseData');
        Session::unsetSessionVariable('LOTTERY_lastpage');
    }
}
//exit(json_encode($this->recset));
?>
<script type="text/javascript">
    $('.left-header').html('SLOT Games');
</script>
<div class="row">
    <div class='col-xs-12 col-sm-12 col-md-12 pmsHeader'>
        <h3>
            <span>SLOT Games</span>
        </h3>
    </div>
</div>
<div class="row whiteBackground" style="display:none;">
    <div class='col-xs-12 col-sm-12 col-md-12' id="gameCatNav" style="line-height:20px;font-size:16px;">
    </div>
</div>
<div class='row whiteBackground'>
    <div class="col-md-12">
        <?php
        $gameCategory="";
        $gameCategoryArr=array();
        $gameCategoryCountArr=array();
        $gameCategoryLinkArr=array();
        $count=0;
        foreach ($this->recset as $games) {
        if($games->gameCategory != $gameCategory) {
        if($gameCategory!=""){
        ?>
        </ul><div class="clearBoth"></div><div class="pageSeprator"></div></div>

    <?php
    $gameCategoryArr[count($gameCategoryArr)]=$gameCategory;
    $gameCategoryCountArr[count($gameCategoryCountArr)]=$count;
    $gameCategoryLinkArr[count($gameCategoryLinkArr)]="<a style='color:white;' href='#' id='".count($gameCategoryLinkArr)."-list"."' onclick='showCatList(this.id)'>".$gameCategory."</a>";
    }
    $gameCategory=$games->gameCategory;
    $count=0;
    ?>

    <div class='instantGroupWrap' id='<?php echo $gameCategory;?>'>
        <h2 id='<?php echo $gameCategory."count";?>'><?php echo $gameCategory;?></h2><ul>
            <?php
            }
            $count++;
            ?>
            <li id='<?php echo $gameCategory."_".$count;?>'>
                <?php if($gameCategory=="Html-Based"){?>

                <a onclick="showImage('<?php echo addslashes($games->gameName);?>','<?php echo $games->gameImageLocations;?>','<?php echo addslashes($games->gameDescription);?>','<?php echo Utilities::getFormattedAmount($games->gamePrice);?>', '<?php echo $gameCategory."_".$count;?>', '<?php echo $games->gameNumber;?>', '<?php echo $games->windowHeight;?>', '<?php echo $games->windowWidth;?>', '<?php echo $games->thirdPartyURL; ?>');">
                    <?php }else {?>
                <a data-toggle="modal" data-target="#globalModal" onclick="showImage('<?php echo addslashes($games->gameName);?>','<?php echo $games->gameImageLocations;?>','<?php echo addslashes($games->gameDescription);?>','<?php echo Utilities::getFormattedAmount($games->gamePrice);?>', '<?php echo $gameCategory."_".$count;?>', '<?php echo $games->gameNumber;?>', '<?php echo $games->windowHeight;?>', '<?php echo $games->windowWidth;?>', '<?php echo $games->thirdPartyURL; ?>');">
                    <?php }?>
                    <div class='gameDiv'>
                        <div class="gameDivInner">
                            <div class='imgWrap' >

                                <img src="<?php echo $games->gameImageLocations;?>" alt="<?php echo $games->gameName;?>" />

                            </div>
                            <div class='row'>
                                <div class="div col-xs-8">
                                    <?php echo $games->gameName;?>
                                </div>
                                <!--                            <div class="col-xs-4"><span class="currencySymbol">--><?php //echo Constants::MYCURRENCYSYMBOL;?><!--</span>--><?php //echo Utilities::getFormattedAmount($games->gamePrice);?><!--</div>-->
                            </div>
                        </div>
                    </div>
                </a>
                <div class="groupbuttonWrap">
                    <div class="row groupbutton">
                        <div class='col-xs-6 text-left'>
                            <a target="_blank" onclick='windowOpenMethod("<?php echo $this->igepath.ServerUrl::SLOT_GAMEPLAY.'?gameNumber='.$games->gameNumber.'&gameMode=try&domainName=gourav&merchantKey=1&secureKey=12345678&currencyCode=INR&lang=ENG&gameType=flash' ?>","","<?php echo $games->windowWidth;?>","<?php echo $games->windowHeight;?>")'>Try it for Free</a>
                        </div>
                        <div class='col-xs-6 text-right'>
                            <a target="_blank" onclick='windowOpenMethod("<?php echo $this->igepath.ServerUrl::SLOT_GAMEPLAY.'?gameNumber='.$games->gameNumber.'&gameMode=try&domainName=gourav&merchantKey=1&secureKey=12345678&currencyCode=INR&lang=ENG&gameType=flash' ?>","","<?php echo $games->windowWidth;?>","<?php echo $games->windowHeight;?>")'>Buy Now</a>
                        </div>
                    </div>
                </div>
            </li>

            <?php
            if(count($this->recset) == array_sum($gameCategoryCountArr)+$count){
            ?>
        </ul><div class="clearBoth"></div></div>
<?php
$gameCategoryArr[count($gameCategoryArr)]=$gameCategory;
$gameCategoryCountArr[count($gameCategoryCountArr)]=$count;
$gameCategoryLinkArr[count($gameCategoryLinkArr)]="<a style='color:white;' href='#' id='".count($gameCategoryLinkArr)."-list"."' onclick='showCatList(this.id)'>".$gameCategory."</a>";
}
}
?>
</div>
</div>
<form id="confirmationForm" method="post" action="<?php echo JRoute::_('index.php?task=ige.prepareConfirmation'); ?>">
    <input type="hidden" name="gameName" id ="gameName" value="Instant Win Games"/>
    <input type="hidden" name="pageToGo" id ="pageToGo" value="<?php echo Session::getSessionVariable('LOTTERY_lastpage'); ?>"/>
</form>
<script type='text/javascript'>
    <?php
    $deviceType = Configuration::getDeviceType();
    $appTypeAndClientType = Configuration::getAppAndClientType($deviceType);
    ?>
    var clientType = "<?php echo $appTypeAndClientType['CLIENTTYPE']; ?>";
    var appType = "<?php echo $appTypeAndClientType['APPTYPE']; ?>";
    var userAgentIge = "<?php echo Configuration::getDevice(); ?>";
    var deviceType = "<?php echo $deviceType; ?>";

    function listener(event)
    {
        pmslog(event.data);
        if(event.data=='updateBal') {
            startAjax("/component/Betting/?task=account.getPlayerBalance", '', getBalance, "nottoshow");
        }
        if(event.data=='updateParent') {
            location.reload();
        }
    }

    if (window.addEventListener){
        window.addEventListener("message", listener, false)
    }
    else {
        window.attachEvent("onmessage", listener)
    }

    $( window ).load(function() {
        var playerId = '<?php echo Utilities::getPlayerId(); ?>';
        if(playerId.trim().length!=0)
            startAjax("/component/Betting/?task=account.getPlayerBalance", '', getBalance, "nottoshow");
    });

    var gameIdArray = [];
    $(document).ready(function(e) {
        var maxHeight=0;
        $('.instantGroupWrap .gameDiv').each(function(){
            if($(this).height()>maxHeight)
                maxHeight=$(this).height();
        });
        pmslog("max"+maxHeight);
        $('.instantGroupWrap .gameDiv .gameDivInner').css('height',maxHeight);
        $('.instantGroupWrap').each(function() {
            gameIdArray.push(this.id);
        });
        var selectGameMenu = "";
        jQuery.each(gameIdArray, function(i,val){
            selectGameMenu += "<li data-val="+val+"><a href='#id"+val+"'>"+val+"</a></li>";
            //$("#sp-left ul.nav.menu").html(selectGameMenu);
            $("#sp-left ul.nav.menu").html("<li data-val='All' class='current active'><a href='#idAll'>All Categories</a></li>"+selectGameMenu);

            $("#sp-left ul.nav.menu li").click(function(){
                gameValChanger($(this).attr('data-val'));
            });
            gameValChanger(window.location.hash.substr(3));
        });



        function gameValChanger(gameOnClickVal){
            if(gameOnClickVal == 'All' || gameOnClickVal == '') {
                $('.instantGroupWrap').slideDown(700).removeClass("singleOpen");
                $(".instant-win-games ul.nav.menu li").removeClass('current active');
                $(".instant-win-games ul.nav.menu li:first-child").addClass('current active');
            }
            else {
                $('#'+gameOnClickVal).slideDown(700);
                $('.instantGroupWrap').each(function(){
                    if($(this).attr('id')==gameOnClickVal) {
                        $(this).addClass("singleOpen");
                    }
                    else {
                        $(this).slideUp(600);
                    }
                });
                $("#sp-left ul.nav.menu li").removeClass('current active');
                $("#sp-left ul.nav.menu li[data-val="+gameOnClickVal+"]").addClass('current active');
            }
        }

        var gameOnChangeVal = '';
        $("#gameCatNav select").change(function(){
            gameOnChangeVal = $(this).val();
            if(gameOnChangeVal == 'All') {
                $('.instantGroupWrap').slideDown(700).removeClass("singleOpen");
            }
            else {
                $('#'+gameOnChangeVal).slideDown(700);
                $('.instantGroupWrap').each(function(){
                    if($(this).attr('id')==gameOnChangeVal) {
                        $(this).addClass("singleOpen");
                    }
                    else {
                        $(this).slideUp(600);
                    }
                });
            }
        });

        lastTicketPurchaseData = '<?php echo $lastTicketPurchaseData?>';
        if(lastTicketPurchaseData.length != 0) {
            var tmpArr=lastTicketPurchaseData.split('~#~');
            playForCash(tmpArr[0],tmpArr[1],tmpArr[2],tmpArr[3]);
        }
    });

    function playForCash(gameNumber,height,width,Price, thirdPartyURL)
    {
        hideMsg();
        //return false;
        var playerId = '<?php echo Utilities::getPlayerId(); ?>';
        var merchantSessionId = '<?php echo Utilities::getPlayerToken(); ?>';
        var igePath='<?php echo $this->igepath ?>';
        var domainName='<?php echo $this->serviceData['domainName']; ?>';
        var merchantKey='<?php echo $this->serviceData['merchantKey']; ?>';
        var secureKey='<?php echo $this->serviceData['secureCode']; ?>';
        var currencyCode='<?php echo $this->currency; ?>';
        var lang='<?php echo $this->serviceData['lang']; ?>';

//        /*if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|BB/i.test(navigator.userAgent)){
//            igePath += "<?php //echo ServerUrl::IGE_GAMEPLAY_MOBILE_BUY; ?>//";
//		}
//		else {*/
        igePath += "<?php echo ServerUrl::SLOT_GAMEPLAY; ?>";
        //}

        if(playerId.length===0 || merchantSessionId.length ===0) {
            $('#confirmationForm').append('<input type="hidden" name="requestData" id="requestData" value="'+gameNumber+'~#~'+height+'~#~'+width+'~#~'+Price+'">');
            document.getElementById("confirmationForm").submit();
        }
        else {
            var curBal = '<?php echo Utilities::getPlayerLoginResponse()->walletBean->cashBalance;?>';
            if( getOriginalAmount(Price) >  getOriginalAmount(curBal)) {
                error_display("You have insufficient balance, Please top-up your wallet and try again.");
                return false;
            }

            if(thirdPartyURL)
            {
                //windowOpenMethod(thirdPartyURL,'gamewindow'+gameNumber+'try',width,height, '');
                window.open(thirdPartyURL,'gamewindow'+gameNumber+'try','location=0,toolbar=0,menubar=0,status=0,height='+height+',width='+width);
            }
            else
            {
                windowOpenMethod(igePath+'?gameNumber='+gameNumber+'&gameMode=Buy&domainName='+domainName+'&merchantKey='+merchantKey+'&secureKey='+secureKey+'&currencyCode='+currencyCode+'&lang='+lang+'&gameType=flash&playerId='+playerId+'&merchantSessionId='+merchantSessionId+"&clientType="+clientType+"&deviceType="+deviceType+"&appType="+appType+"&userAgentIge="+userAgentIge,'gamewindow'+gameNumber+'Buy',width,height, '');
            }


        }
    }

    function playForFree(gameNumber,height,width, thirdPartyURL)
    {
//	return false;
        var playerId = '<?php echo Utilities::getPlayerId(); ?>';
        var merchantSessionId = '<?php echo Utilities::getPlayerToken(); ?>';
        var igePath='<?php echo $this->igepath ?>';
        var domainName='<?php echo $this->serviceData['domainName']; ?>';
        var merchantKey='<?php echo $this->serviceData['merchantKey']; ?>';
        var secureKey='<?php echo $this->serviceData['secureCode']; ?>';
        var currencyCode='<?php echo $this->currency; ?>';
        var lang='<?php echo $this->serviceData['lang']; ?>';
        var curBal = '<?php echo Utilities::getPlayerLoginResponse()->walletBean->cashBalance;?>';

//        /*if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|BB/i.test(navigator.userAgent)){
//            igePath +="<?php //echo ServerUrl::IGE_GAMEPLAY_MOBILE_TRY; ?>//";
//		}
//		else {*/
        igePath +="<?php echo ServerUrl::SLOT_GAMEPLAY; ?>";
        //}


        if(thirdPartyURL)
        {
            if((thirdPartyURL.indexOf("play.staging.mrslotty.com")===-1))
            {
                if(playerId) {
                    window.open(thirdPartyURL + '&playerId=' + playerId + '&merchantSessionId=' + merchantSessionId + '&gameMode=Try&playerBal=' + curBal, 'gamewindow' + gameNumber + 'try', 'location=0,toolbar=0,menubar=0,status=0,height=' + height + ',width=' + width);
                }
                else {
                    //window.open(thirdPartyURL,'gamewindow'+gameNumber+'Try','location=0,toolbar=0,menubar=0,status=0,height='+height+',width='+width);
                    window.open(thirdPartyURL+'&gameMode=Try','gamewindow'+gameNumber+'try','location=0,toolbar=0,menubar=0,status=0,height='+height+',width='+width);
                }
            }
            else {
                window.open(thirdPartyURL,'gamewindow'+gameNumber+'try','location=0,toolbar=0,menubar=0,status=0,height='+height+',width='+width);
            }
            //windowOpenMethod(thirdPartyURL,'gamewindow'+gameNumber+'try',width,height, '');
            //window.open(thirdPartyURL,'gamewindow'+gameNumber+'try','location=0,toolbar=0,menubar=0,status=0,height='+height+',width='+width);
            //windowOpenMethod(thirdPartyURL?gameMode=try&playerId='+playerId+'&merchantSessionId='+merchantSessionId+','gamewindow'+gameNumber+'try',width,height, '');
        }
        else
        {
            if(playerId.length==0 || merchantSessionId.length==0) {
                windowOpenMethod(igePath+'?gameNumber='+gameNumber+'&gameMode=try&domainName='+domainName+'&merchantKey='+merchantKey+'&secureKey='+secureKey+'&currencyCode='+currencyCode+'&lang='+lang+'&gameType=flash'+"&clientType="+clientType+"&deviceType="+deviceType+"&appType="+appType+"&userAgentIge="+userAgentIge,'gamewindow'+gameNumber+'try',width,height, '');
            }
            else {
                windowOpenMethod(igePath+'?gameNumber='+gameNumber+'&gameMode=try&domainName='+domainName+'&merchantKey='+merchantKey+'&secureKey='+secureKey+'&currencyCode='+currencyCode+'&lang='+lang+'&gameType=flash&playerId='+playerId+'&merchantSessionId='+merchantSessionId+"&clientType="+clientType+"&deviceType="+deviceType+"&appType="+appType+"&userAgentIge="+userAgentIge,'gamewindow'+gameNumber+'try',width,height, '');
            }
        }


    }

    function showImage(gameName,imagePath,howToPlay,Price,clkGameId,gameNumber,height,width, thirdPartyURL)
    {
        var path="http://ice.winlot.in/html-page";

        if((thirdPartyURL.indexOf("ala.winBetting.com")!==-1))
        {
            $('#globalModal').modal('hide');
            window.location.href=path +'?gameNumber=' + gameNumber + '&gameMode=try&height='+height+'&width='+width;
        }
        else
        {
            $("#globalModal .modal-title").html("");
            $("#globalModal .modal-body").html("");
            $("#globalModal .modal-footer").html("");

            if(/BlackBerry|BB/i.test(navigator.userAgent)){
                $("#globalModal .modal-title").html("Instant Win Games");
                $("#globalModal .modal-body").html("<p>Coming soon... To enjoy these games, please visit our website on PC</p>");
                $("#globalModal .modal-footer").html();
            }
            /*else if( (/ Mac OS/i.test(navigator.userAgent) == true) && (/ Mobile/i.test(navigator.userAgent) == false)) {
                $("#globalModal .modal-title").html("Instant Win Games");
                $("#globalModal .modal-body").html("<p>This Feature Is Not Supported On This Platform. For More Information, Please Contact Your Support Team. </p>");
                $("#globalModal .modal-footer").html();
            }*/
            else {
                $("#globalModal .modal-title").html(gameName);
                var innerHtmlStr="<div class='row'><div class='col-xs-4'><img src='"+imagePath+"' alt=''  style='display:block; margin: auto;width:100%;' / ></div><div class='col-xs-8' style='padding-bottom: 20px; font-size: 15px; padding-top:8px;'>"+howToPlay+"</div></div>";
                innerHtmlStr = innerHtmlStr+ "<div class='row'><div class='col-sm-8 col-sm-push-4 modelbtnBox'> <a href='#' data-dismiss='modal' onclick='playForCash(\""+gameNumber+"\",\""+height+"\",\""+width+"\",\""+Price+"\", \""+thirdPartyURL+"\");'><div class='btnOuterWrap'><div class='priceBox'><span class='currencySymbol'><?php //echo Constants::MYCURRENCYSYMBOL;?></span>"+Price+"</div> <div class='btnTitle'>Play For <span>Cash!!</span></div></div></a><div class='tryBtnWrap'><i>No thanks!! I want to </i><a href='#' data-dismiss='modal' onclick='playForFree(\""+gameNumber+"\",\""+height+"\",\""+width+"\",\""+thirdPartyURL+"\");' style='text-decoration:none;'><i>try it for free!!</i></a></div></div></div>";

                $("#globalModal .modal-body").html(innerHtmlStr);
                $("#globalModal .modal-footer").html();
            }
        }
    }
    $(document).ready(function(e) {
        adjustSideBar();
    });

function backToLobby()
{
  	
}
</script>
