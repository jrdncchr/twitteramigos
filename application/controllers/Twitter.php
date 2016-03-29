<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Twitter extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        /**
         * Method for creating a base string from an array and base URI.
         * @param string $baseURI the URI of the request to twitter
         * @param array $params the OAuth associative array
         * @return string the encoded base string
         **/
        function buildBaseString($baseURI, $params){

            $r = array(); //temporary array
            ksort($params); //sort params alphabetically by keys
            foreach($params as $key=>$value){
                $r[] = "$key=" . rawurlencode($value); //create key=value strings
            }//end foreach

            return "POST&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r)); //return complete base string
        }//end buildBaseString()

        /**
         * Method for creating the composite key.
         * @param string $consumerSecret the consumer secret authorized by Twitter
         * @param string $requestToken the request token from Twitter
         * @return string the composite key.
         **/
        function getCompositeKey($consumerSecret, $requestToken){
            return rawurlencode($consumerSecret) . '&' . rawurlencode($requestToken);
        }//end getCompositeKey()

        /**
         * Method for building the OAuth header.
         * @param array $oauth the oauth array.
         * @return string the authorization header.
         **/
        function buildAuthorizationHeader($oauth){
            $r = 'Authorization: OAuth '; //header prefix

            $values = array(); //temporary key=value array
            foreach($oauth as $key=>$value)
                $values[] = "$key=\"" . rawurlencode($value) . "\""; //encode key=value string

            $r .= implode(', ', $values); //reassemble
            return $r; //return full authorization header
        }//end buildAuthorizationHeader()

        /**
         * Method for sending a request to Twitter.
         * @param array $oauth the oauth array
         * @param string $baseURI the request URI
         * @return string the response from Twitter
         **/
        function sendRequest($oauth, $baseURI){
            $header = array( buildAuthorizationHeader($oauth), 'Expect:'); //create header array and add 'Expect:'

            $options = array(CURLOPT_HTTPHEADER => $header, //use our authorization and expect header
                CURLOPT_HEADER => false, //don't retrieve the header back from Twitter
                CURLOPT_URL => $baseURI, //the URI we're sending the request to
                CURLOPT_POST => true, //this is going to be a POST - required
                CURLOPT_RETURNTRANSFER => true, //return content as a string, don't echo out directly
                CURLOPT_SSL_VERIFYPEER => false); //don't verify SSL certificate, just do it

            $ch = curl_init(); //get a channel
            curl_setopt_array($ch, $options); //set options
            $response = curl_exec($ch); //make the call
            curl_close($ch); //hang up

            return $response;
        }//end sendRequest()


        //get request token
        $baseURI = 'https://api.twitter.com/oauth/request_token';

        $nonce = time();
        $timestamp = time();
        $oauth = array('oauth_callback' => 'http://localhost/jellyfish/twitter/success',
            'oauth_consumer_key' => 'OnbE46go1s3E00K5j72tu3aco',
            'oauth_nonce' => $nonce,
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => $timestamp,
            'oauth_version' => '1.0');


        $consumerSecret = '2edJqSUoZuSYxXrZQ58tC3q2spOD3pU54pMIgtttiRKBQRY7Zc'; //put your actual consumer secret here, it will look something like 'MCD8BKwGdgPHvAuvgvz4EQpqDAtx89grbuNMRd7Eh98'

        $baseString = buildBaseString($baseURI, $oauth); //build the base string

        $compositeKey = getCompositeKey($consumerSecret, null); //first request, no request token yet
        $oauth_signature = base64_encode(hash_hmac('sha1', $baseString, $compositeKey, true)); //sign the base string

        $oauth['oauth_signature'] = $oauth_signature; //add the signature to our oauth array

        $response = sendRequest($oauth, $baseURI); //make the call

        //parse response into as$testsociative array
        $responseArray = array();
        $parts = explode('&', $response);
        foreach($parts as $p){
            $p = explode('=', $p);
            $responseArray[$p[0]] = $p[1];
        }//end foreach

        //get oauth_token from response
        $oauth_token = $responseArray['oauth_token'];

        $this->session->set_userdata('oauth_token', $oauth_token);

        //redirect for authorization
        header("Location: https://api.twitter.com/oauth/authorize?oauth_token=$oauth_token");

    }

	public function success() {
        $oauth_token = $this->session->userdata('oauth_token');
        $oauth = new \Abraham\TwitterOAuth\TwitterOAuth('OnbE46go1s3E00K5j72tu3aco', '2edJqSUoZuSYxXrZQ58tC3q2spOD3pU54pMIgtttiRKBQRY7Zc', $oauth_token,  $_GET['oauth_token']);

        $access_token = $oauth->oauth("oauth/access_token",
            array("oauth_verifier" => $_GET['oauth_verifier']));

        $connection = new \Abraham\TwitterOAuth\TwitterOAuth('OnbE46go1s3E00K5j72tu3aco', '2edJqSUoZuSYxXrZQ58tC3q2spOD3pU54pMIgtttiRKBQRY7Zc', $access_token['oauth_token'], $access_token['oauth_secret']);
        $content = $connection->get("account/verify_credentials");
        var_dump($content);

    }

}
