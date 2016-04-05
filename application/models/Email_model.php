<?php

class Email_Model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function send_email_notification($follower, $followed_email) {

        if(!$this->is_localhost()) {
            $CI =& get_instance();
            $CI->load->model('settings_model');
            $email_notification = $this->settings_model->get_settings_by_category('email_notification');

            $headers = "From: " . $email_notification['name'] . "<" . $email_notification['from'] . ">"  . "\r\n";
            $headers .= "BCC: <" . $email_notification['cc'] . ">" . "\r\n";
            $headers .= "Content-type: text/html";

            $subject = "Twitter Amigos - " . $follower->name . " followed you on twitter!";
            $content = $email_notification['body'] . "\r\n\r\n";
            $content .= "<a href='" . base_url() . "twitter/follow_back/12345/6789/SecretKey'>Follow Back Link</a>";

            mail($followed_email, $subject, $content, $headers);
        }
    }


    public function is_localhost() {
        $whitelist = array('127.0.0.1', '::1');
        if( in_array( $_SERVER['REMOTE_ADDR'], $whitelist)) {
            return true;
        }
        return false;
    }

} 