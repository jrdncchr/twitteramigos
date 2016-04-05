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

        if($result['new'] && $method == "") {
            $this->session->set_userdata('new_user', true);
        }

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
            if($this->user['user']->twitter_id != $id) {
                $twitter_access_token = $this->user['access_token'];
                $access_token = json_decode($twitter_access_token);

                try {
                    $connection = new \Abraham\TwitterOAuth\TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $access_token->oauth_token, $access_token->oauth_token_secret);
                    $follow = $connection->post("friendships/create", ["id" => $id, "follow" => true]);
                } catch(Exception $e) {
                    $this->data['error'] = $e->getMessage();
                    $this->_render('error');
                    return false;
                }

                if($follow->following == true) {
                    $follow_info = array('follower_id' => $this->user['user']->twitter_id, 'followed_id' => $id);
                    $this->load->model('follow_model');
                    $result = $this->follow_model->add_follow($follow_info);
                    if($result['success']) {
                        $this->load->model('user_model');
                        $followed = $this->user_model->get_user_where(array('twitter_id' => $id));

                        if($followed->email_notification) {
                            $this->load->model('email_model');
                            $this->email_model->send_email_notification($this->user['user'], $followed->email);
                        }

                        $_SESSION['notice'] = 'You are now following <a target="_blank" href="https://www.twitter.com/' . $followed->name . '">' . $followed->name . '</a>';
                        redirect("main");
                    }
                }
            } else {
                $_SESSION['notice'] = "You are now logged in, you can't follow your own account.";
                redirect("main");
            }

         }
        return true;
    }

    public function follow_back($follower_id, $followed_id, $follow_back_key) {
        $data = array(
            'follower_id' => $follower_id,
            'followed_id' => $followed_id,
            'follow_back_key' => $follow_back_key
        );
        $this->load->model('follow_model');
        $result = $this->follow_model->follow_back($data);

        if($result['success'] == true) {
            $this->load->model('user_model');

            $follower = $this->user_model->get_user_where(array('twitter_id' => $follower_id));

            $followed = $this->user_model->get_user_where(array('twitter_id' => $followed_id));
            $followed_secret = $this->user_model->get_user_secret($followed->id);

            try {
                $access_token = json_decode($followed_secret->twitter_access_token);
                $connection = new \Abraham\TwitterOAuth\TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $access_token->oauth_token, $access_token->oauth_token_secret);
                $twitter_user = $connection->get("users/show", ["user_id" => $access_token->user_id]);
            } catch(Exception $e) {
                $this->data['error']  = $e->getMessage();
                $this->_render('error');
                return false;
            }

            if(!isset($twitter_user->errors)) {
                $user = array(
                    'user' => $followed,
                    'twitter' => $twitter_user,
                    'access_token' => $followed_secret->twitter_access_token
                );
                $this->session->set_userdata('user', $user);
            }

            $_SESSION['notice'] = 'You followed back <a target="_blank" href="https://www.twitter.com/' . $follower->name . '">' . $follower->name . '</a>!';
        } else {
            $_SESSION['notice'] = "Incorrect follow back key.";
        }

        redirect('main');
        return true;
    }


}
