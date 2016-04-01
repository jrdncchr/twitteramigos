<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

	public function index() {
        if(null == $this->user) {
            $this->load->model("twitter_model");
            $this->data['twitter_auth_url'] = $this->twitter_model->get_twitter_auth_url();
        }
		$this->_render('main');
	}

    public function logout() {
        $this->session->unset_userdata('user');
        redirect("main");
    }

    public function admin() {
        $admin = $this->session->userdata('admin');
        if(null == $admin) {
            $this->_render("admin/login");
        } else {
            redirect('admin');
        }
    }

    public function admin_login() {
        $auth = $this->input->post();
        $this->load->model("admin_model");
        $result = $this->admin_model->login($auth);
        if($result['success'] == true) {
            redirect('admin');
        } else {
            $_SESSION['error'] = $result['message'];
            $this->admin();
        }
    }

}

