<?php
require_once JPATH_BETTING_COMPONENT . '/helpers/Includes.php';

class Api {

    public static function gmailCallbackResponse()
    {
        require_once JPATH_BETTING_COMPONENT . '/library/google-client/vendor/autoload.php';

        $request = JFactory::getApplication()->input;
        $auth_code = $request->getString('code', '');
        if ($auth_code == "") {
            Redirection::to(Redirection::REFER_A_FRIEND);
        }

        $client = new Google_Client();
        $client->setApplicationName('Sabanzuri');
        $client->setScopes("https://www.googleapis.com/auth/contacts.other.readonly");
        $client->setAuthConfig(JPATH_BETTING_COMPONENT . '/library/google-client/credentials.json');
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        $accessToken = $client->fetchAccessTokenWithAuthCode($auth_code);
        $client->setAccessToken($accessToken);
        $service = new Google_Service_PeopleService($client);
        $optParams = array(
            'readMask' => 'names,emailAddresses'
        );
        //$results = $service->people_connections->listPeopleConnections('otherContacts', $optParams);
        $results = $service->otherContacts->listOtherContacts($optParams);

        $return = array();
        if (!empty($results['otherContacts'])) {
            foreach ($results['otherContacts'] as $contact) {
                if (isset($contact['names'][0]['displayName']) && $contact['names'][0]['displayName'] != "" && isset($contact['emailAddresses'][0]['value']) && $contact['emailAddresses'][0]['value'] != "") {
                    $return[] = array(
                        'name' => $contact['names'][0]['displayName'],
                        'email' => $contact['emailAddresses'][0]['value'],
                    );
                    // array_push($return,$contact['names'][0]['displayName'],$contact['emailAddresses'][0]['value']);
                }
            }
        }
        $google_contacts = $return;
        return $google_contacts;
    }


    public static function gmailApiCallResponse() {
        $request = JFactory::getApplication()->input;
        $auth_code = $request->getString('code', '');
        if ($auth_code == "") {
            Redirection::to(Redirection::REFER_A_FRIEND);
        }
        $fields = array(
            'code' => urlencode($auth_code),
            'client_id' => urlencode(Constants::GOOGLE_CLIENT_ID),
            'client_secret' => urlencode(Constants::GOOGLE_CLIENT_SECRET),
            'redirect_uri' => urlencode(Configuration::GOOGLE_CALLBACK),
            'grant_type' => urlencode('authorization_code')
        );
        $post = '';
        foreach ($fields as $key => $value) {
            $post .= $key . '=' . $value . '&';
        }
        $post = rtrim($post, '&');
        $result = self::curl('https://accounts.google.com/o/oauth2/token', $post);
        $response = json_decode($result);
        $accesstoken = $response->access_token;
        $url = 'https://www.google.com/m8/feeds/contacts/default/full?max-results=' . Constants::GOOGLE_MAX_RESULTS . '&alt=json&v=3.0&oauth_token=' . $accesstoken;
        $xmlresponse = self::curl($url);
        $contacts = json_decode($xmlresponse, true);
//        exit(json_encode($contacts));
        $return = array();
        if (!empty($contacts['feed']['entry'])) {
            foreach ($contacts['feed']['entry'] as $contact) {
                if (isset($contact['title']['$t']) && $contact['title']['$t'] != "" && isset($contact['gd$email'][0]['address']) && $contact['gd$email'][0]['address'] != "") {
                    $return[] = array(
                        'name' => $contact['title']['$t'],
                        'email' => $contact['gd$email'][0]['address'],
                    );
                }
            }
        }
        $google_contacts = $return;
        return $google_contacts;
    }

    public static function curl($url, $post = "") {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        if ($post != "") {
            curl_setopt($curl, CURLOPT_POST, 5);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        }
        curl_setopt($curl, CURLOPT_USERAGENT, Configuration::getDevice());
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        $contents = curl_exec($curl);
        curl_close($curl);
        return $contents;
    }

    public static function sendIdListToBetting($referType, $contacts, $referThrough) {
        $response = ServerCommunication::sendCall("", array(
                    "referType" => '',
                    "referThrough" => $referThrough,
                    "referalList" => array()
        ));
    }

    public function socialCallToBetting($referThrough, $status, $postId = "") {
        $response = ServerCommunication::sendCall("", array(
                    "postId" => $postId,
                    "referThrough" => $referThrough,
                    "postStatus" => $status
        ));
    }

    public function inviteFriendSocial($referThrough) {
        $response = ServerCommunication::sendCall(ServerUrl::REFER_A_FRIEND, array(
                    "referThrough" => $referThrough,
                    "referType" => "socialRefer",
                    "inviteMode" => "EMAIL"
        ));
        return json_decode($response);
    }

    public static function outlookApiCallResponse($code) {
        $auth_code = $code;
        $fields = array(
            'code' => urlencode($auth_code),
            'client_id' => urlencode(Constants::OUTLOOK_CLIENT_ID),
            'client_secret' => urlencode(Constants::OUTLOOK_CLIENT_SECRET),
            'redirect_uri' => urlencode(Redirection::OUTLOOK_CALLBACK),
            'grant_type' => urlencode('authorization_code')
        );
        $post = '';
        foreach ($fields as $key => $value) {
            $post .= $key . '=' . $value . '&';
        }
        $post = rtrim($post, '&');
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://login.live.com/oauth20_token.srf');
        curl_setopt($curl, CURLOPT_POST, 5);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($result);
        if (isset($response->access_token)) {
            $_SESSION['access_token'] = $response->access_token;
            $accesstoken = $_SESSION['access_token'];
        }
        if (isset($code)) {
            $accesstoken = $_SESSION['access_token'];
        }
        $url = 'https://apis.live.net/v5.0/me/contacts?access_token=' . $accesstoken;
        $response = file_get_contents($url);
        $response = json_decode($response, true);
        $data = $response['data'];
        $data = json_decode(json_encode($data));
        $contacts = array();
        foreach ($data as $item) {
            if (self::searchArray($item->emails->preferred, $contacts)) {
                array_push($contacts, [
                    "name" => $item->name,
                    "email" => $item->emails->preferred
                ]);
            }
            if (self::searchArray($item->emails->account, $contacts)) {
                array_push($contacts, [
                    "name" => $item->name,
                    "email" => $item->emails->account
                ]);
            }
            if (self::searchArray($item->emails->business, $contacts)) {
                array_push($contacts, [
                    "name" => $item->name,
                    "email" => $item->emails->business
                ]);
            }
            if (self::searchArray($item->emails->personal, $contacts)) {
                array_push($contacts, [
                    "name" => $item->name,
                    "email" => $item->emails->personal
                ]);
            }
            if (self::searchArray($item->emails->other, $contacts)) {
                array_push($contacts, [
                    "name" => $item->name,
                    "email" => $item->emails->other
                ]);
            }
        }
        return $contacts;
    }

    public function yahooApiCallResponse($code) {
        require_once(JPATH_BETTING_COMPONENT . '/library/Yahoo/globals.php');
        require_once(JPATH_BETTING_COMPONENT . '/library/Yahoo/oauth_helper.php');
        $request_token = $_SESSION['request_token'];
        $request_token_secret = $_SESSION['request_token_secret'];
        $oauth_verifier = $code;
        $retarr = get_access_token_yahoo(OAUTH_CONSUMER_KEY, OAUTH_CONSUMER_SECRET, $request_token, $request_token_secret, $oauth_verifier, false, true, true);
        if (!empty($retarr)) {
            list($info, $headers, $body, $body_parsed) = $retarr;
            if (!empty($body)) {
                $guid = $body_parsed['xoauth_yahoo_guid'];
                $access_token = rfc3986_decode($body_parsed['oauth_token']);
                $access_token_secret = $body_parsed['oauth_token_secret'];
                $retarrs = callcontact_yahoo(OAUTH_CONSUMER_KEY, OAUTH_CONSUMER_SECRET, $guid, $access_token, $access_token_secret, false, true);
                $contacts = array();
                foreach ($retarrs as $item) {
                    if (count($item['email']) != 0 && count($item['name']) != 0) {
                        array_push($contacts, [
                            "name" => $item['name'],
                            "email" => $item['email']
                        ]);
                    }
                }
                return $contacts;
            }
        }
        return false;
    }

    public static function searchArray($email, $contacts) {
        if (!$email == "" && count($email) != 0) {
            foreach ($contacts as $value) {
                if ($value['email'] == $email) {
                    return false;
                }
            }
        } else
            return false;

        return true;
    }

}
