<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
require_once JPATH_COMPONENT . '/controller.php';

class BettingControllerAccount extends BettingController {

    function display($cachable = false, $urlparams = false) {}

    public static function playerProfile() {
        if (Session::sessionValidate()) {
            $response = Utilities::getPlayerProfile();
        } else {
            Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }

    public function updatePlayerProfile() {
        if (Session::sessionValidate()) {
            $request = JFactory::getApplication()->input;
            $firstName = $request->getString('fname', '');
            $lastName = $request->getString('lname', '');
            $mobileNo = $request->getString('mobile', 0);
            $gender = $request->getString('gender', '');
            $dob = $request->getString('dob', '');
            $address = $request->getString('address', '');
            $stateCode = $request->getString('state', '');
            $city = $request->getString('city', '');
            $pinCode = $request->getString('pincode', '');
            $email = $request->getString('email', '');
            $from = $request->getString('from', '');
            $countryCode = $request->getString('country', '');
            $isAjax = $request->getString('isAjax', '');
            Validations::$isAjax = ($isAjax == 'true') ? true : false;

            $requestData = array(
                "firstName" => $firstName,
                "lastName" => $lastName,
                "gender" => $gender,
                "dob" => $dob,
                "addressLine1" => $address,
                "stateCode" => $stateCode,
                "city" => $city,
                "pinCode" => $pinCode,
                "emailId" => $email,
                //"countryCode" => $countryCode
            );
            if (!empty($mobileNo) && (strlen($mobileNo) >= Constants::MOBILE_MIN_LENGTH) && (strlen($mobileNo) <= Constants::MOBILE_MAX_LENGTH)) {
                $requestData['mobileNo'] = $mobileNo;
            }
            $redirectTo = Redirection::MYACC_PROFILE;
            $playerInfo = Utilities::getPlayerLoginResponse();
            $playerStatus = false;
            if ($playerInfo->emailVerified == "Y" && $playerInfo->phoneVerified == "Y") {
                $playerStatus = "FULL";
            }
            exit(json_encode($requestData));
           $response =  Utilities::updatePlayerProfile($requestData, $redirectTo, $playerStatus,$isAjax);
           if( Validations::$isAjax == true)
                Redirection::ajaxSendDataToView($response);
            Redirection::to($redirectTo, Errors::TYPE_SUCCESS, Validations::getRespMsg());
        }else {
            Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }

    function changePassword() {
        if (Session::sessionValidate()) {
            $request = JFactory::getApplication()->input;
            $isAjax = $request->getString('isAjax', '');
            $currentPassword = $request->get('currentPassword', '', 'RAW');
            $newPassword = $request->get('newPassword', '', 'RAW');
             Validations::$isAjax = ($isAjax == 'true') ? true : false;
            $response = ServerCommunication::sendCall(ServerUrl::CHANGE_PASSWORD, array(
                        "oldPassword" => $currentPassword,
                        "newPassword" => $newPassword
            ));
            if (Validations::getErrorCode() != 0)
                 Redirection::ajaxSendDataToView($response);
                //Redirection::to($redirectTo, Errors::TYPE_ERROR, Validations::getRespMsg());
            $msg = Validations::getRespMsg();
            Utilities::playerLogout(array(
                "isManual" => false
            ));
            $redirectTo = Redirection::PASSWORD_CHANGED;
            Session::setSessionVariable('passwordChanged', true);
             if (Validations::$isAjax) {
                $response->path = $redirectTo;
                Redirection::ajaxSendDataToView($response);
            }
             Redirection::to($redirectTo);
            //Redirection::to(Redirection::PASSWORD_CHANGED);
        }
        else {
            Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }

    function getBalance() {
        $request = JFactory::getApplication()->input;
        $walletType = $request->getString('walletType', '');
        $response = ServerCommunication::sendCall(ServerUrl::GET_BALANCE, array(
                    "walletType" => $walletType
        ));
        exit($response);
    }

    function uploadPlayerAvatarOld() {
        if (Session::sessionValidate()) {
            $request = JFactory::getApplication()->input;
            $selectedAvatar = $request->getString('selected_avatar', '');
            $userAvatar = JRequest::getVar('user_avatar', null, 'files');
            jimport('joomla.filesystem.file');
            $fileName = JFile::makeSafe($userAvatar['name']);
            if (!empty($fileName)) {
                $fileTmpName = $userAvatar['tmp_name'];
                $fileSize = $userAvatar['size'];
                $fileType = $userAvatar['type'];
//                exit(json_encode($fileType));
                if( count(explode(".", $fileName)) > 2  ){
                    Redirection::ajaxSendDataToView(true, 1, 'Invalid File Extension.');
                }
                if( preg_match("/php/i", $fileType) ){
                    Redirection::ajaxSendDataToView(true, 1, 'Invalid File Type.');
                }
                $playerInfo = Utilities::getPlayerLoginResponse();
                $selectedAvatar = $playerInfo->playerId . '_image.jpg';
                move_uploaded_file($fileTmpName, Constants::AVATAR_PATH_ABS_PLAYER . $selectedAvatar);
                $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                header('Content-Type: image');
                list($width, $height) = getimagesize(Constants::AVATAR_PATH_ABS_PLAYER . $selectedAvatar);
                $ratio = $width / $height;
                $newwidth = 200;
                $newheight = $newwidth / $ratio;
                $thumb = imagecreatetruecolor($newwidth, $newheight);
                if ($extension == "jpg") {
                    $source = imagecreatefromjpeg(Constants::AVATAR_PATH_ABS_PLAYER . $selectedAvatar);
                    imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                    imagejpeg($thumb, Constants::AVATAR_PATH_ABS_PLAYER . $selectedAvatar, 100);
                } else if ($extension == "png") {
                    $source = imagecreatefrompng(Constants::AVATAR_PATH_ABS_PLAYER . $selectedAvatar);
                    imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                    imagepng($thumb, Constants::AVATAR_PATH_ABS_PLAYER . $selectedAvatar, 0);
                } else if ($extension == "gif") {
                    $source = imagecreatefromgif(Constants::AVATAR_PATH_ABS_PLAYER . $selectedAvatar);
                    imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                    imagegif($thumb, Constants::AVATAR_PATH_ABS_PLAYER . $selectedAvatar);
                }
                $response = ServerCommunication::serverUploadImage(ServerUrl::IMAGE_UPLOAD, array(
                            "fileContentEncoded" => base64_encode(file_get_contents(Constants::AVATAR_PATH_ABS_PLAYER . $selectedAvatar)),
                            "docType" => "avatar",
                            "fileName" => $selectedAvatar,
                            "domainName" => Session::getSessionVariable('imgUploadDomain')
                                ), true);
                if (Validations::getErrorCode() != 0) {
                    Redirection::ajaxSendDataToView($response);
                }
                unlink(Constants::AVATAR_PATH_ABS_PLAYER . $selectedAvatar);
            }
            $response = ServerCommunication::sendCall(ServerUrl::EDIT_AVATAR, array("imageName" => $selectedAvatar));
            if (Validations::getErrorCode() == 0) {
                Utilities::updatePlayerLoginResponse(array("avatarPath" => "/playerImages/" . $selectedAvatar));
            } else {
                Redirection::ajaxSendDataToView($response);
            }
            $response->avatarPath = Utilities::getPlayerLoginResponse()->commonContentPath . Utilities::getPlayerLoginResponse()->avatarPath . "?v=" . microtime();
            Redirection::ajaxSendDataToView($response);
        } else {
            Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_SESSION_EXPIRE, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }

    public function uploadPlayerAvatar()
    {
        if (Session::sessionValidate()) {
            $request = JFactory::getApplication()->input;
            $selectedAvatar = $request->getString('selected_avatar', '');
            $userAvatar = JRequest::getVar('user_avatar', null, 'files');
            jimport('joomla.filesystem.file');
            $fileName = JFile::makeSafe($userAvatar['name']);
            if (!empty($fileName)) {
                $fileTmpName = $userAvatar['tmp_name'];
                $fileSize = $userAvatar['size'];
                $fileType = $userAvatar['type'];
//                if( count(explode(".", $fileName)) > 2  ){
//                    Redirection::ajaxSendDataToView(true, 1, 'Invalid File Extension.');
//                }
                if( preg_match("/php/i", $fileType) || preg_match("/php/i", $fileName) ){
                    Redirection::ajaxSendDataToView(true, 1, 'Invalid File Type.');
                }
                $playerInfo = Utilities::getPlayerLoginResponse();
                $selectedAvatar = $playerInfo->playerId . '_image.jpg';
                $vatr = move_uploaded_file($fileTmpName, Constants::AVATAR_PATH_ABS_PLAYER . $selectedAvatar);
                $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                header('Content-Type: image');
                list($width, $height) = getimagesize(Constants::AVATAR_PATH_ABS_PLAYER . $selectedAvatar);
                $ratio = $width / $height;
                $newwidth = 200;
                $newheight = $newwidth / $ratio;
                $thumb = imagecreatetruecolor($newwidth, $newheight);
                if ($extension == "jpg") {
                    $source = imagecreatefromjpeg(Constants::AVATAR_PATH_ABS_PLAYER . $selectedAvatar);
                    imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                    imagejpeg($thumb, Constants::AVATAR_PATH_ABS_PLAYER . $selectedAvatar, 100);
                } else if ($extension == "png") {
                    $source = imagecreatefrompng(Constants::AVATAR_PATH_ABS_PLAYER . $selectedAvatar);
                    imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                    imagepng($thumb, Constants::AVATAR_PATH_ABS_PLAYER . $selectedAvatar, 0);
                } else if ($extension == "gif") {
                    $source = imagecreatefromgif(Constants::AVATAR_PATH_ABS_PLAYER . $selectedAvatar);
                    imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                    imagegif($thumb, Constants::AVATAR_PATH_ABS_PLAYER . $selectedAvatar);
                }
                $filesize = filesize(Constants::AVATAR_PATH_ABS_PLAYER . $selectedAvatar);
                $cfile = new CURLFile(Constants::AVATAR_PATH_ABS_PLAYER . $selectedAvatar, $fileType);
                curl_file_create($selectedAvatar);
                $data = array(
                    "playerId" => Utilities::getPlayerId(),
                    "playerToken" => Utilities::getPlayerToken(),
                    "domainName" => Configuration::DOMAIN_NAME,
                    "isDefaultAvatar" => "Y",
//                    "file" => $cfile,
                    "documents[0].image" => $cfile,
                    "documents[0].docType" => "avatar",
                    "imageFile" => $cfile
                );
                $response = ServerCommunication::serverUploadImageNew(ServerUrl::UPLOAD_AVATAR, $data, true, $filesize);
                //  exit($response);

                unlink(Constants::AVATAR_PATH_ABS_PLAYER . $selectedAvatar);
                $common_path = preg_replace('/http:\/\/10.179.0.150:8080/',Configuration::AVATAR_DOMAIN,$response->docList[0]->avatarPath,1);
                $response->avatarPath = $common_path;
                if (Validations::getErrorCode() == 0) {
                    Utilities::updatePlayerLoginResponse(array("avatarPath" => $response->avatarPath));
                } else {
                    Redirection::ajaxSendDataToView($response);
                }

//                $common_path = $playerInfo->commonContentPath;
//                $common_path = substr($common_path,strpos($common_path,"WeaverDoc"));
//                $common_path = Configuration::CONTENT_SERVER_DOMAIN ."/" . $common_path;
//                $common_path = preg_replace('/www.sabanzuri.com/','api.sabanzuri.com',$common_path,1);
//                $response->avatarPath = Configuration::AVATAR_DOMAIN . '/' . Utilities::getPlayerLoginResponse()->avatarPath . "?v=" . microtime();




                Redirection::ajaxSendDataToView($response);
            } else if ($selectedAvatar) {
                $filesize = filesize(Constants::AVATAR_PATH_ABS_COMMON . $selectedAvatar);
                $cfile = new CURLFile(Constants::AVATAR_PATH_ABS_COMMON . $selectedAvatar, 'image/png');
                curl_file_create($selectedAvatar);
                $data = array('ImageFileName'=>"akshay","playerId" => Utilities::getPlayerId(), "playerToken" => Utilities::getPlayerToken(), "domainName" => Configuration::DOMAIN_NAME, "isDefaultAvatar" => "N", "file" => $cfile);
                $response = ServerCommunication::serverUploadImageNew(ServerUrl::UPLOAD_AVATAR, $data, true, $filesize);
                if (Validations::getErrorCode() == 0) {
                    $selectedAvatarNew = Utilities::getPlayerId() . '_image.png';
                    Utilities::updatePlayerLoginResponse(array("avatarPath" => "/playerImages/" . $selectedAvatarNew));
                } else {
                    Redirection::ajaxSendDataToView($response);
                }
                 $initial_path = Utilities::getPlayerLoginResponse()->commonContentPath;
                $initial_path = preg_replace('/www.sabanzuri.com/','api.sabanzuri.com',$initial_path,1);
                $response->avatarPath = $initial_path . Utilities::getPlayerLoginResponse()->avatarPath . "?v=" . microtime();
                $response->avatarPath = Utilities::getPlayerLoginResponse()->commonContentPath . Utilities::getPlayerLoginResponse()->avatarPath . "?v=" . microtime();
                Redirection::ajaxSendDataToView($response);
            } else {
                Redirection::ajaxSendDataToView(true, 1, 'Invalid request.');
            }
        } else {
            Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_SESSION_EXPIRE, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }

    function sendVerificationCode() {
        if (Session::sessionValidate()) {
            $request = JFactory::getApplication()->input;
            $verificationField = $request->getString('verificationField', '');
            $requestArr = array(
                "verificationField" => $verificationField
            );
            if ($verificationField == "MOBILE") {
                $requestArr['mobileNo'] = $request->getString('mobileNo', '');
            }
            $response = ServerCommunication::sendCall(ServerUrl::SEND_VERIFICATION_CODE, $requestArr, true);
            Redirection::ajaxSendDataToView($response);
        } else {
            Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_SESSION_EXPIRE, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }

    function verifyPlayer() {
        $request = JFactory::getApplication()->input;
        $verificationType = $request->getString('verificationType', '');
        if ($verificationType != "LOGIN") {
            if (!Session::sessionValidate()) {
                Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_SESSION_EXPIRE, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
            }
        }
        $verificationCode = $request->getString('verificationCode', '');
        $requestBean = array(
            "verificationCode" => $verificationCode,
            "verificationType" => $verificationType
        );
        if ($verificationType == "LOGIN") {
            $requestBean['userName'] = $request->getString('verifyUserName', '');
        } else
            $requestBean['verificationField'] = $request->getString('verificationField', '');
        $response = ServerCommunication::sendCall(ServerUrl::VERIFY_PLAYER, $requestBean, true);
        if ($verificationType != "LOGIN") {
            if (Validations::getErrorCode() == 0) {
                Utilities::updatePlayerLoginResponse(array(
                    "phoneVerified" => "Y"
                ));
            }
        } else {
            if (Validations::getErrorCode() == 0) {
                Session::unsetSessionVariable('verificationPending');
                Session::unsetSessionVariable('verificationPendingUserName');
                $response->path = Redirection::LOGIN;
            }
        }
        $response->verificationField = $requestBean['verificationField'];
        Redirection::ajaxSendDataToView($response);
    }

    public function playerInbox() {
        $request = JFactory::getApplication()->input;
        $isAjax = $request->getString('isAjax', '');
        Validations::$isAjax = ($isAjax == 'true') ? true : false;
        if (Session::sessionValidate()) {
            $offset = $request->getString('offset', '');
            $limit = $request->getString('limit', '');
            $responseArr = Utilities::playerInbox($offset, $limit);
//            $tmpContent = array();
//            foreach ($responseArr['content'] as $content) {
//                $tmpContent[$content['id']] = json_decode($content['params'])->content;
//            }
//            $content = $responseArr['content'];
            unset($responseArr['response']->errorCode);
            $tmpArr = array(
                'messages' => $responseArr['response'],
//                'content' => $tmpContent,
                'errorCode' => Validations::getErrorCode(),
            );
            if (Validations::getErrorCode() != 0) {
                $tmpArr['respMsg'] = Validations::getRespMsg();
            }
            Redirection::ajaxSendDataToView($tmpArr);
        } else {
            Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_SESSION_EXPIRE, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }

    function inboxActivity() {
        $request = JFactory::getApplication()->input;
        $activity = $request->getString('activity', '');
        $msgId = $request->getString('msgId', '');
        $isAjax = $request->getString('isAjax', '');
        Validations::$isAjax = ($isAjax == 'true') ? true : false;
        if (Session::sessionValidate()) {
            $offset = '';
            $limit = '';
            if (strtoupper($activity) == "DELETE") {
                $unreadCount = $request->getInt('unreadCount', 0);
                $offset = $request->getInt('offset', '');
                $limit = $request->getInt('limit', '');
                $msgId = explode("AND", $msgId);
                $tmpArr = array();
                foreach ($msgId as $msg) {
                    array_push($tmpArr, (int) $msg);
                }
                $msgId = $tmpArr;
                $activity = "DELETED";
            }
            $requestArr = array(
                "activity" => $activity
            );
            if (strtoupper($activity) == "READ") {
                $requestArr['inboxId'] = $msgId;
            } else {
                $requestArr['inboxIdList'] = $msgId;
                $requestArr['offset'] = $offset;
                $requestArr['limit'] = $limit;
            }
            $response = ServerCommunication::sendCall(ServerUrl::INBOX_ACTIVITY, $requestArr, Validations::$isAjax,true,
                array('merchantCode' => Configuration::MERCHANT_CODE,'merchantPwd'=> Configuration::MERCHANT_PWD));
            if (Validations::getErrorCode() == 0) {
                if( isset($response->unreadMsgCount) ){
                    Utilities::updatePlayerLoginResponse(array(
                        "unreadMsgCount" => $response->unreadMsgCount
                    ));
                }

                if (strtoupper($activity) != "READ") {
                    if (Validations::getErrorCode() != 0)
                        Redirection::ajaxSendDataToView($response);
                    Utilities::updatePlayerLoginResponse(array(
                        "unreadMsgCount" => $response->unreadMsgCount
                    ));
                    if (count($response->responseData->data->plrInboxList) == 0) {
                        Redirection::ajaxSendDataToView(true, 1, Errors::NO_MESSAGES_IN_INBOX);
                    }
                    $ids = array();
                    foreach ($response->responseData->data->plrInboxList as $msg) {
                        array_push($ids, $msg->content_id);
                    }
                    if (count($ids) == 0) {
                        Redirection::ajaxSendDataToView(true, 501, Errors::NO_MESSAGES_IN_INBOX);
                    }
                    $responseArr = array();
                    $responseArr['response'] = $response->responseData;
                   // $responseArr['content'] = Utilities::getMessageContent("'" . implode("','", $ids) . "'");
//                    if (count($responseArr['content']) == 0) {
//                        Redirection::ajaxSendDataToView(true, 501, Errors::NO_MESSAGES_IN_INBOX);
//                    }
                    $tmpContent = array();
                    foreach ($response->responseData->data as $content) {
                        $tmpContent[$content->inboxId] = $content->content_id;
                    }
                    $content = $responseArr['content'];
                    unset($responseArr['response']->errorCode);
                    $tmpArr = array(
                        'messages' => $responseArr['response'],
                        'content' => $tmpContent,
                        'errorCode' => Validations::getErrorCode(),
                        'unreadMsgCount' => Utilities::getPlayerLoginResponse()->unreadMsgCount
                    );
                    if (Validations::getErrorCode() != 0) {
                        $tmpArr['respMsg'] = Validations::getRespMsg();
                    }
                    Redirection::ajaxSendDataToView($tmpArr);
                }
            }

            $formatedRes = new stdClass();

            $formatedRes->errorCode = $response->responseCode;
            $formatedRes->unreadMsgCount = 0;
            $formatedRes->respMsg = $response->responseMessage;
            $formatedRes->messages = new stdClass();
            $formatedRes->messages->plrInboxList = $response->responseData->data;

            Redirection::ajaxSendDataToView($formatedRes);
        } else {
            Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_SESSION_EXPIRE, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }


    function updatePracticeBalance() {
        $request = JFactory::getApplication()->input;
        $isAjax = $request->getString('isAjax', '');
        $isAjax = ($isAjax == 'true') ? true : false;
        if (Session::sessionValidate()) {
            $refTxnNo = $request->getString('refTxnNo', '123124');
            $response = Utilities::getPlayerPracticeBalance($refTxnNo, $isAjax);
            $response->refill = true;
            if ($isAjax)
                Redirection::ajaxSendDataToView($response);
            return $response;
        }
        else {
            if ($isAjax)
                Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_SESSION_EXPIRE, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
            Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }

    function getPlayerBalance() {
        $request = JFactory::getApplication()->input;
        $isAjax = $request->getString('isAjax', '');
        $isAjax = ($isAjax == 'true') ? true : false;
        if (Session::sessionValidate()) {
            $refill = $request->getString('refill', '');
            $refill = ($refill == 'true') ? true : false;
            $response = Utilities::getPlayerBalance($refill, $isAjax);
            $response->refill = false;
            if ($isAjax)
                Redirection::ajaxSendDataToView($response);
            return $response;
        }
        else {
            if ($isAjax)
                Redirection::ajaxExit(Redirection::LOGIN, Constants::AJAX_FLAG_SESSION_EXPIRE, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
            Redirection::to(Redirection::LOGIN, Errors::TYPE_ERROR, Errors::SESSION_EXPIRED);
        }
    }

    function getPostLoginData() {
        Utilities::getPostLoginData(true, (Session::sessionValidate() ? true : false), true);
    }

    function fetchCityList() {
        $request = JFactory::getApplication()->input;
        $stateId = $request->getString('stateId', '');
        $isAjax = $request->getString('isAjax', '');
        Validations::$isAjax = ($isAjax == 'true') ? true : false;
        $response = ServerCommunication::sendCall(ServerUrl::FETCH_CITY_LIST, array(
                    "stateCode" => $stateId,
                    "countryCode" => Constants::COUNTRY_CODE
                        ), Validations::$isAjax);
        Redirection::ajaxSendDataToView($response);
    }

    function sendAppLink() {
        $request = JFactory::getApplication()->input;
        $isAjax = $request->getString('isAjax', '');
        Validations::$isAjax = ($isAjax == 'true') ? true : false;
        $mobileNo = $request->getInt("mobileNo", 0);
        if ($mobileNo !== 0) {
            $response = ServerCommunication::sendCall(ServerUrl::SEND_APP_LINK, array(
                        "mobileNo" => $mobileNo
                            ), Validations::$isAjax);

            Redirection::ajaxSendDataToView($response);
        }
    }

    public function contact()
    {
        $request = JFactory::getApplication()->input;
        $isAjax = $request->getString('isAjax', '');
        Validations::$isAjax = ($isAjax == 'true') ? true : false;
        $fname = $request->getString('fname', '');
        $lname = $request->getString('lname', '');
        $email = $request->getString('email', '');
        $subject = $request->getString('subject', '');
        $message = $request->getString('message', '');
        $deviceType = Configuration::getDeviceType();
        $os = Configuration::getOS();

        $success = true;
        $error = array();
        if (empty($fname)) {
            $success = false;
            $error['fname'] = Errors::FIRST_NAME_EMPTY;
        }
        if (empty($lname)) {
            $success = false;
            $error['lname'] = Errors::LAST_NAME_EMPTY;
        }
        if (empty($email)) {
            $success = false;
            $error['email'] = Errors::EMPTY_EMAIL;
        }
         if (empty($subject)) {
            $success = false;
            $error['subject'] = Errors::EMPTY_SUBJECT;
        }
        if (empty($message)) {
            $success = false;
            $error['message'] = Errors::EMPTY_MESSAGE;
        }

        if ($success == true) {

            $requestArr = array();
            $requestArr['firstName'] = $fname;
            $requestArr['lastName'] = $lname;
            $requestArr['emailId'] = $email;
            $requestArr['subject'] = $subject;
            $requestArr['message'] = $message;
            $requestArr['domainName'] = Configuration::DOMAIN_NAME;
            //$request = json_encode($requestArr);
            $response = ServerCommunication::sendCall(ServerUrl::CONTACT_US, $requestArr);
            // $response = json_decode('{"msg":"Data Successfully Saved"}');
            if($response->errorCode == 0){
                $response->msg = JText::_('YOUR_MESSAGE_WAS_SENT_SUCCESSFULLY');
            }else{
                if ($deviceType != Configuration::DEVICE_PC) {
                    if (($os == Configuration::OS_ANDROID) || ($os == Configuration::OS_IOS)) {
                        //Redirection::to(Redirection::CONTACT_US_MOBILE, Errors::TYPE_ERROR, $response->errorMsg);
                        $redirecto = Redirection::CONTACT_US_MOBILE;
                        $redirecto = $redirecto . '?msg=' . $response->errorMsg;
                        Redirection::to($redirecto, Errors::TYPE_ERROR, $response->errorMsg);
                }
                } else {
                    Redirection::to(Redirection::CONTACT_US, Errors::TYPE_ERROR, $response->errorMsg);
                }
                exit;
            }
            if (Validations::$isAjax == true) {
                Redirection::ajaxSendDataToView($response);
            } else {
                if ($deviceType != Configuration::DEVICE_PC) {
                    if (($os == Configuration::OS_ANDROID) || ($os == Configuration::OS_IOS)) {
                     $redirecto = Redirection::CONTACT_US_MOBILE;
                        $redirecto = $redirecto . '?msg=' . $response->msg;
                        Redirection::to($redirecto);
               }
                } else {
                    Redirection::to(Redirection::CONTACT_US, Errors::TYPE_SUCCESS, $response->msg);
                }
            }

        } else {
            //Error Occured
            if (Validations::$isAjax == true) {
                $response = new stdClass();
                $response->error = true;
                $response->errors = $error;
                Redirection::ajaxSendDataToView($response);
            } else {
                echo json_encode($error);
                Redirection::to(Redirection::CONTACT_US, Errors::TYPE_ERROR, $error[array_keys($error)[0]]);
            }
        }
    }

}
