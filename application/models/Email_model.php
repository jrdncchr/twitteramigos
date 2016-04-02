<?php

class Email_Model extends CI_Model {

    private $cc = "cacherojordan@gmail.com";

    public function __construct() {
        $this->load->database();
    }

    public function send_email($follower, $followed = "danero.jrc@gmail.com") {

        $from = 'contact@twitteramigos.com';

        $headers = "From: Twitter Amigos<" . $from . ">"  . "\r\n";
        $headers .= "BCC: <" . $this->cc . ">" . "\r\n";
        $headers .= "Content-type: text/html";

        $subject = "Twitter Amigos - " .$follower->name . " followed you on twitter!";
        $content = "<a href='" . base_url() . "twitter/follow_back/12345/6789/SecretKey'>Follow Back Link</a>";


        mail($followed, $subject, $content, $headers);
    }

} 