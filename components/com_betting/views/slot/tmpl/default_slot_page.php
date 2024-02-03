<?php
defined('_JEXEC') or die('Restricted Access');

//exit(json_encode($_REQUEST));
$gameNumb=$_REQUEST['gameNumber'];
$gameMode=$_REQUEST['gameMode'];
$domain=$_REQUEST['domainName'];
$merchantKey=$_REQUEST['merchantKey'];
$secureKey=$_REQUEST['secureKey'];
$currencyCode=$_REQUEST['currencyCode'];
$lang=$_REQUEST['lang'];
$gameType=$_REQUEST['gameType'];
$merchantSessionId = $_REQUEST['merchantSessionId'] == "" ? "" : $_REQUEST['merchantSessionId'];
$playerId= $_REQUEST['playerId'] == "" ? "" : $_REQUEST['playerId'];
$clientType=$_REQUEST['clientType'];
$deviceType=$_REQUEST['deviceType'];
$appType=$_REQUEST['appType'];
$userAgentIge=$_REQUEST['userAgentIge'];
$isOLD=$_REQUEST['isOLD'];
$isOldForNew=$_REQUEST['isOldForNew'];

$deviceType = "TAB";
$currencyCode = "USD";

$height=$_REQUEST['height'];
$width=$_REQUEST['width'];

//$newpath=$_REQUEST['path'];
$newpath = Redirection::ICE_DOMAIN."/slot/index.php";

$igePath=$newpath."?gameNumber=".$gameNumb."&gameMode=".$gameMode."&domainName=".$domain."&merchantKey=".$merchantKey."&secureKey=".$secureKey."&currencyCode=".$currencyCode."&lang=".$lang."&gameType=".$gameType."&playerId=".$playerId."&merchantSessionId=".$merchantSessionId."&clientType=".$clientType."&deviceType=".$deviceType."&appType=".$appType."&userAgentIge=".$userAgentIge;


$Slotpath.="http://ice.winlot.in/html5/index.html"."?gameNum=".$gameNumb;

$imgpath="images/html-games-images/".$gameNumb.".jpg";

if( isset($isOLD) && $isOLD == 'true' )
{
    ?>
    <script>
        var get_params = '<?php echo trim($igePath) ?>'.split("?")[1].split("&");
        var form_elem_html ='';
        for(var i=0; i<get_params.length; i++) {
            var tmp = get_params[i].split("=");
            form_elem_html += "<input type='hidden' name='"+tmp[0]+"' value='"+tmp[1]+"' />";
        }

        form_elem_html = '<form action="http://ala-new.winBetting.com/SGEOLD/loadGame.action" method="post" name="hidden_form" id="hidden_form">'+form_elem_html+'</form>';
        document.write(form_elem_html);
        document.getElementById('hidden_form').submit();
    </script>
    <?php
}
else
{
    $playerLoginResponse = Utilities::getPlayerLoginResponse();
   // exit(json_encode($playerLoginResponse->walletBean->cashBalance));
    //$cashBalance = explode(".", $playerLoginResponse->walletBean->cashBalance)[0];
    $cashBalance = $playerLoginResponse->walletBean->cashBalance;
    //$cashBalance = $cashBalance == "" ? "" : $cashBalance;

    $igePath=$newpath."?gameNumber=".$gameNumb."&gameMode=Buy&domainName=tbg.lottoBetting.com&merchantKey=".$merchantKey."&secureKey=".$secureKey."&currency=".$currencyCode."&lang=".$lang."&gameType=".$gameType."&playerId=".$playerId."&merchantSessionId=".$merchantSessionId."&clientType=".$clientType."&deviceType=".$deviceType."&appType=".$appType."&userAgentIge=".$userAgentIge."&playerBal=".$cashBalance;

    //exit(json_encode($igePath));
    ?>
    <style>

        .keronframe {
            width: 100%;
            margin: 0;
            padding: 0;
            height: 100%;
            position: fixed;
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
            text-align: center;
            padding: 20px 0 40px 0;
        }
        .linkWrap {
            box-shadow: rgb(164, 226, 113) 0px 1px 0px 0px inset;
            background: #89c403;
            background: -moz-linear-gradient(top, #89c403 1%, #77a809 100%);
            background: -webkit-linear-gradient(top, #89c403 1%,#77a809 100%);
            background: linear-gradient(to bottom, #89c403 1%,#77a809 100%);
            border-radius: 3px;
            border: 1px solid rgb(116, 184, 7);
            display: block;
            position: absolute;
            cursor: pointer;
            color: rgb(255, 255, 255);
            font-family: Arial;
            font-size: 15px;
            font-weight: bold;
            padding: 4px;
            width: 180px;
            left: 50%;
            margin-left: -90px;
            top: auto;
            bottom: 4px;
            text-decoration: none;
            text-shadow: rgb(82, 128, 9) 0px 1px 0px;
        }



    </style>

    <script>

        <?php
        $isOldForNew = ( $isOldForNew == "true" ? true : false );

        if( $isOldForNew )
        {
            ?>
            window.location.href = "http://ala.winBetting.com/slot-html5/index.html?gameNum=<?php echo $gameNumb ?>";
            <?php
        }
        else
        {
            ?>
            window.location.href='<?php echo $igePath?>';
            <?php
        }

        ?>


        $(document).ready(function () {

            var screenHeight = screen.height;
            var screenWidth = screen.width;

            var windowHeight = $(window).height();
            var windowWidth = $(window).width();

            var heightMargin = 100;

            <?php
            if($newpath == null)
            {
            ?>
            var aspectRatio = 1.33;
            <?php
            }
            else
            {
            ?>
            var aspectRatio = 0.66;
            <?php
            }
            ?>

//        windowHeight = windowHeight - heightMargin;
//        windowWidth = aspectRatio * windowHeight;
//
//        $('iframe').height(windowHeight);
//        $('iframe').width(windowWidth);

        });

function backToLobby()
{

        $('#playScreen').attr('src','about:blank');

        $("#gamePlayDiv").hide();
        $("#playScreen").hide();
        $(".gamePlayArea").hide();

        $(".bannerTop").show();
}


    </script>
    <?php
}
?>





