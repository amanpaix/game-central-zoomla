<?php
defined('_JEXEC') or die('Restricted Access');
//exit(json_encode("http://asia-api.golden-race.net/api/?method=remote_auth&token=".$this->token."&idtype=EXTID&userid=".Utilities::getPlayerId()."&passwd=".Session::getSessionVariable('encPwd')));
$loginHashData = Utilities::parseXmlResponse("http://asia-api.golden-race.net/api/?method=remote_auth&token=".$this->token."&idtype=EXTID&userid=".Utilities::getPlayerId()."&passwd=".Session::getSessionVariable('encPwd'));

if($loginHashData->response->result != "success")
{
    Redirection::to(Redirection::ERROR_PAGE, Errors::TYPE_ERROR, $loginHashData->response->error_message);
}

$loginHash = $loginHashData->response->token;
?>


<div class="keronframe">
<iframe  src="http://asia.golden-race.net/web/themes/?loginHash=<?php echo $loginHash?>&sk=_base"   width="100%" height="1300px">

</iframe>
</div>

