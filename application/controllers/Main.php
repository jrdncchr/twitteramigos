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
        } else {
            $new_user = $this->session->userdata('new_user');
            if(null != $new_user) {
                $this->data['new_user'] = true;
                $this->session->unset_userdata('new_user');
            }
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

    public function send_email() {
        $this->load->model('email_model');
        $this->email_model->send_email($this->user['user']);
    }

    public function delete_sessions() {
        $files = glob(APPPATH . 'session/*');
        if(!is_dir(APPPATH . 'session')) {
            exit('Wrong Path');
        } else {
            foreach($files as $file) {
                if(is_file($file))
                    unlink($file);
            }
            exit('Sessions deleted!');
        }
    }

}

