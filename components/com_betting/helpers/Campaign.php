<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
require_once JPATH_BETTING_COMPONENT . '/helpers/Includes.php';

class Campaign {

    public static function prepareCampaignTracking() {
        if (Session::sessionValidate()) {
            Redirection::to(JUri::base());
        }
        $request = JFactory::getApplication()->input;
//        $isAjax = $request->getString('isAjax', '');
//        Validations::$isAjax = ($isAjax == 'true') ? true : false;
        $gsp = $request->getString('gsp', '');
        if ($gsp) {
            $request = JFactory::getApplication()->input;
            $param = $request->getString('param', '');
            $idVCommString = $request->getString('tid', '');
            //exit(json_encode($idVCommString));
            if ($idVCommString !== "") {
                Session::setSessionVariable('idVCommString', $idVCommString);
            }
            if ($param == "" || empty($param) || is_null($param)) {
                //exit('Encrypted String not received');
            } else {
                $params = array();
                foreach ($_POST as $k => $v) {
                    if ($k != "param" && $k != "userName" && $k != "mobile" && $k != "reg_password" && $k != "email")
                        $params[$k] = $v;
                }
                if (count($params) == 0)
                    $params = new stdClass();
                $requestArr = array(
                    'encString' => $param,
                    'requestIp' => Configuration::getClientIP(),
                    'params' => $params
                );
                $response = ServerCommunication::sendCall(ServerUrl::PREPARE_CAMPAIGN_TREKKING, $requestArr);
                if (Validations::getErrorCode() != 0) {
                    if (Validations::getErrorCode() == 608)
                        Redirection::to(Redirection::CAMPAIGN_EXPIRED_LINK);
                    else
                        Redirection::to(JUri::base());
                }
                if (Validations::getErrorCode() == 0) {
                    Session::setSessionVariable('reEncString', $response->reEncString);
                    $userName = $request->getString('userName', '');
                    $password = $request->get('reg_password', '', 'RAW');
                    $mobileNo = $request->getString('mobile', 0);
                    $emailId = $request->get('email', '', 'RAW');
                    $landingPage = $_SERVER['REQUEST_URI'];
                    $registrationType = $request->getString('registrationType', '');
                    $stateCode = $request->getString('state', '');
                    $submitUrl = $request->getString('submiturl', null);
                    $refercode = $request->getString('refercode', '');
                    Utilities::doGSPregistration($userName, $password, $mobileNo, $emailId, $landingPage, $registrationType, $stateCode, $submitUrl, $refercode);
                }
            }
        } else {
            $request = JFactory::getApplication()->input;
            $data = $request->getString('data', '');
            //$landing_page = JFactory::getApplication()->getMenu()->getActive();
            //$data = $landing_page->params['encrypted_string'];
            $idVCommString = $request->getString('tid', '');
            if ($idVCommString !== "") {
                Session::setSessionVariable('idVCommString', $idVCommString);
            }
            Session::unsetSessionVariable("reEncString");
            if ($data == "" || empty($data) || is_null($data)) {
//            exit('Encrypted String not received');
            } else {
                $params = array();
                foreach ($_GET as $k => $v) {
                    if ($k != "data")
                        $params[$k] = $v;
                }
                if (count($params) == 0)
                    $params = new stdClass();
                $requestArr = array(
                    'encString' => $data,
                    'requestIp' => Configuration::getClientIP(),
                    'params' => $params
                );
                $response = ServerCommunication::sendCall(ServerUrl::PREPARE_CAMPAIGN_TREKKING, $requestArr);
                if (Validations::getErrorCode() != 0) {
                    if (Validations::getErrorCode() == 608)
                        Redirection::to(Redirection::CAMPAIGN_EXPIRED_LINK);
                    else
                        Redirection::to(JUri::base());
                }
                if (Validations::getErrorCode() == 0) {
                    Session::setSessionVariable('reEncString', $response->reEncString);
                }
            }
        }
    }

}

?>
