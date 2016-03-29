<?php

class Twitter_Model extends CI_Model {

    public function twitter_verify_access_key($user) {
        $result['has_access_key'] = false;
        if($user->twitter_access_token) {
            $result['has_access_key'] = true;
            $access_token = json_decode($user->twitter_access_token);
            $connection = new \Abraham\TwitterOAuth\TwitterOAuth(TWITTER_KEY, TWITTER_SECRET_KEY, $access_token->oauth_token, $access_token->oauth_token_secret);
            $content = $connection->get("users/show", ["user_id" => $access_token->user_id]);
            $result['user_info'] = $content;
        }
        $result['auth_url'] = $this->get_twitter_auth_url();
        return $result;
    }

    public function get_twitter_auth_url() {
        $oauth_token = $this->generate_twitter_oauth_token();
        return "https://api.twitter.com/oauth/authorize?oauth_token=$oauth_token";
    }

    public function generate_twitter_oauth_token() {
        function buildBaseString($baseURI, $params){
            $r = array();
            ksort($params);
            foreach($params as $key=>$value){
                $r[] = "$key=" . rawurlencode($value);
            }
            return "POST&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
        }

        function getCompositeKey($consumerSecret, $requestToken){
            return rawurlencode($consumerSecret) . '&' . rawurlencode($requestToken);
        }

        function buildAuthorizationHeader($oauth){
            $r = 'Authorization: OAuth ';
            $values = array();
            foreach($oauth as $key=>$value)
                $values[] = "$key=\"" . rawurlencode($value) . "\""; //encode key=value string

            $r .= implode(', ', $values);
            return $r;
        }

        function sendRequest($oauth, $baseURI){
            $header = array( buildAuthorizationHeader($oauth), 'Expect:');

            $options = array(CURLOPT_HTTPHEADER => $header,
                CURLOPT_HEADER => false,
                CURLOPT_URL => $baseURI,
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false);

            $ch = curl_init();
            curl_setopt_array($ch, $options);
            $response = curl_exec($ch);
            curl_close($ch);

            return $response;
        }

        $baseURI = 'https://api.twitter.com/oauth/request_token';

        $nonce = time();
        $timestamp = time();
        $oauth = array('oauth_callback' => 'http://localhost/twitteramigos/twitter/callback',
            'oauth_consumer_key' => TWITTER_CONSUMER_KEY,
            'oauth_nonce' => $nonce,
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => $timestamp,
            'oauth_version' => '1.0');
        $consumerSecret = TWITTER_CONSUMER_SECRET;
        $baseString = buildBaseString($baseURI, $oauth);
        $compositeKey = getCompositeKey($consumerSecret, null);
        $oauth_signature = base64_encode(hash_hmac('sha1', $baseString, $compositeKey, true));
        $oauth['oauth_signature'] = $oauth_signature;

        $response = sendRequest($oauth, $baseURI);

        $responseArray = array();
        $parts = explode('&', $response);
        foreach($parts as $p){
            $p = explode('=', $p);
            $responseArray[$p[0]] = $p[1];
        }

        $oauth_token = $responseArray['oauth_token'];

        $this->session->set_userdata('twitter_oauth_token', $oauth_token);
        return $oauth_token;
    }

    public function get_user_info($access_token) {

    }

} 