<?php

class Email_Model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function send_mass_email($recipient, $title, $message) {
        if($recipient == "All") {
            $result = $this->db->get_where('user', array('email_notification' => 1));
            $list = $result->result();
        } else if($recipient == "Premium") {
            $this->db->where(array('user.email_notification' => 1, 'subscription.service' => 'PREMIUM'));
            $this->db->from('subscription');
            $this->db->join('user', 'subscription.user_id = user.id', 'left');
            $result = $this->db->get();
            $list = $result->result();
        } else if($recipient == "Subscribers") {
            $this->db->where(array('user.email_notification' => 1));
            $this->db->from('subscription');
            $this->db->join('user', 'subscription.user_id = user.id', 'left');
            $this->db->group_by('subscription.user_id');
            $result = $this->db->get();
            $list = $result->result();
        }

        foreach($list as $to) {
            $this->send_email($to->email, $title, $message);
        }
        return array('success' => true);
    }

    public function send_email($to, $title, $message) {
        if(!$this->is_localhost()) {
            $CI =& get_instance();
            $CI->load->model('settings_model');
            $email_contact = $this->settings_model->get_settings_by_category('email_contact');
            $headers = "From: Twitter Amigos" . "<" . $email_contact['to'] . ">"  . "\r\n";
            if(mail($to, $title, $message, $headers)) {
                return true;
            }
            return false;
        }
        return true;
    }

    public function send_email_notification($follower, $followed, $follow_back_link) {

        if(!$this->is_localhost()) {
            $CI =& get_instance();
            $CI->load->model('settings_model');
            $email_notification = $this->settings_model->get_settings_by_category('email_notification');

            $headers = "From: " . $email_notification['name'] . "<" . $email_notification['from'] . ">"  . "\r\n";
            $headers .= "BCC: <" . $email_notification['cc'] . ">" . "\r\n";
            $headers .= "Content-type: text/html";

            $subject = "Twitter Amigos - " . $follower->name . " followed you on twitter!";
            $content = $email_notification['body'] . "\r\n\r\n";
            $content .= "<br/><br />
                        <a href='" . base_url() . "twitter/follow_back/".$follower->twitter_id."/".$followed->twitter_id."/".$follow_back_link ."'>Follow Back Link</a>";

            mail($followed->email, $subject, $content, $headers);
        }
    }

    public function send_contact_message($input) {
        if(!$this->is_localhost()) {
            $CI =& get_instance();
            $CI->load->model('settings_model');
            $email_contact = $this->settings_model->get_settings_by_category('email_contact');

            $headers = "From: " . $input['name'] . "<" . $input['email'] . ">"  . "\r\n";
            $headers .= "BCC: <" . $email_contact['cc'] . ">" . "\r\n";

            $subject = "Twitter Amigos - New message from " . $input['name'];
            $content = $input['content'];

            if(mail($email_contact['to'], $subject, $content, $headers)) {
                return true;
            }
            return false;
        }
        return true;
    }


    public function is_localhost() {
        $whitelist = array('127.0.0.1', '::1');
        if( in_array( $_SERVER['REMOTE_ADDR'], $whitelist)) {
            return true;
        }
        return false;
    }

} 