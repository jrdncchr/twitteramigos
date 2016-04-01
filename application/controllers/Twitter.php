<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Twitter extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function callback($controller = "main", $method = "", $value = "") {
        $oauth_token = $this->session->userdata('twitter_oauth_token');
        $oauth = new \Abraham\TwitterOAuth\TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $oauth_token,  $_GET['oauth_token']);

        $access_token = $oauth->oauth("oauth/access_token",
            array("oauth_verifier" => $_GET['oauth_verifier']));

        $twitter_access_token = (string) json_encode($access_token);

        if(!$twitter_access_token) {
            $this->data['error']  = "Twitter access token not found.";
            $this->_render('error');
            return false;
        }

        try {
            $access_token = json_decode($twitter_access_token);
            $connection = new \Abraham\TwitterOAuth\TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $access_token->oauth_token, $access_token->oauth_token_secret);
            $twitter_user = $connection->get("users/show", ["user_id" => $access_token->user_id]);
        } catch(Exception $e) {
            $this->data['error']  = $e->getMessage();
            $this->_render('error');
            return false;
        }

        $user_info = array(
            'twitter_access_token' => (string) json_encode($access_token),
            'name' => $twitter_user->screen_name,
            'twitter_id' => $twitter_user->id
        );

        $this->load->model('user_model');
        $result = $this->user_model->add_user($user_info);
        if(!$result['success']) {
            $this->data['error']  = $result['message'];
            $this->_render('error');
            return false;
        }

        $user = array(
            'user' => $result['user'],
            'twitter' => $twitter_user,
            'access_token' => $user_info['twitter_access_token']
        );

        $this->session->set_userdata('user', $user);

        $redirect = $value != "" ? $controller . "/" . $method . "/" . $value :
            ($method != "" ? $controller . "/" . $method : $controller);
        redirect($redirect);
        return true;
    }

    public function follow($id) {
        if(null == $this->user) {
            $this->load->model('twitter_model');
            $auth_url = $this->twitter_model->get_twitter_auth_url("twitter/follow/$id");
            redirect($auth_url);
        } else {
            $twitter_access_token = $this->user['access_token'];
            $access_token = json_decode($twitter_access_token);
            try {
                $connection = new \Abraham\TwitterOAuth\TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $access_token->oauth_token, $access_token->oauth_token_secret);
                $follow = $connection->post("friendships/create", ["screen_name" => $id, "follow" => true]);
            } catch(Exception $e) {
                $this->data['error']  = $e->getMessage();
                $this->_render('error');
                return false;
            }

            if($follow->following == true) {
                $_SESSION['notice'] = 'You are now following <a target="_blank" href="https://www.twitter.com/' . $follow->screen_name . '">' . $follow->screen_name . '</a>';
                redirect("main");
            }
         }
        return true;
    }


}
