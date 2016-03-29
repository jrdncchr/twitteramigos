<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

	public function index() {
//        $this->load->model("twitter_model");
//        $this->data['twitter_auth_url'] = $this->twitter_model->get_twitter_auth_url();
        $this->data['twitter_auth_url'] = "#";
		$this->_render('main');
	}

}
