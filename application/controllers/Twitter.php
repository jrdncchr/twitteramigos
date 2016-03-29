<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Twitter extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function callback() {
        $oauth_token = $this->session->userdata('twitter_oauth_token');
        var_dump($oauth_token);
        $oauth = new \Abraham\TwitterOAuth\TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $oauth_token,  $_GET['oauth_token']);

        $access_token = $oauth->oauth("oauth/access_token",
            array("oauth_verifier" => $_GET['oauth_verifier']));

        $twitter_access_token = (string) json_encode($access_token);

        if($twitter_access_token) {
            $result['has_access_key'] = true;
            $access_token = json_decode($twitter_access_token);
            $connection = new \Abraham\TwitterOAuth\TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $access_token->oauth_token, $access_token->oauth_token_secret);
            $content = $connection->get("users/show", ["user_id" => $access_token->user_id]);
            var_dump($content);exit;
        }

//        $this->load->model('user_model');
//        $update = array('twitter_access_token' => (string) json_encode($access_token));
//
//        if($this->user_model->updateInfo($update, $this->user->id)) {
//            $_SESSION['twitter_access_token'] = (string) json_encode($access_token);
//            $this->session->set_userdata('twitter_access_token', (string) json_encode($access_token));
////            redirect(base_url() . "main");
//        }

    }

}
