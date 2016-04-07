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

        $this->load->model('settings_model');
        $this->data['ads'] = $this->settings_model->get_settings_by_category('ads');

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

    public function contact() {
        $this->_render('contact');
    }

    public function subscribe_premium() {
        if($this->user == null) {
            $this->load->model('twitter_model');
            $auth_url = $this->twitter_model->get_twitter_auth_url("main/subscribe_premium");
            redirect($auth_url);
        } else {
            $this->_render('subscribe_premium');
        }
    }

    public function premium_success() {

        $this->load->model('subscription_model');
        $subscription = array(
            'user_id' => $this->user['user']->id,
            'service' => 'PREMIUM'
        );
        $subscription_result = $this->subscription_model->add($subscription);
        if($subscription_result['success'] == true) {
            $subscription_id = $subscription_result['id'];

            $this->load->model('payment_model');
            $payment = array(
                'amount' => $_GET['amt'],
                'subscription_id' => $subscription_id,
                'paid_by' => isset($this->user) ? $this->user['user']->id : NULL
            );
            $payment_result = $this->payment_model->add($payment);
            if($payment_result['success'] == true) {
                $this->session->set_flashdata('notice', 'Payment Successful! You are now subscribed as premium!');
                redirect("main/subscribe_premium");
            }
        }
        $this->session->set_flashdata('notice', 'Something went wrong in the processing, please contact us at contact@twitteramigos.com');
        redirect("main");
    }

    public function top_success() {
        $twitter_id = $_GET['item_number'];

        $this->load->model('user_model');
        $u = $this->user_model->get_user_where(array('twitter_id' => $twitter_id));

        $this->load->model('subscription_model');
        $subscription = array(
            'user_id' => $u->id,
            'service' => 'TOP'
        );
        $subscription_result = $this->subscription_model->add($subscription);
        if($subscription_result['success'] == true) {
            $subscription_id = $subscription_result['id'];

            $this->load->model('payment_model');
            $payment = array(
                'amount' => $_GET['amt'],
                'subscription_id' => $subscription_id,
                'paid_by' => isset($this->user) ? $this->user['user']->id : NULL
            );
            $payment_result = $this->payment_model->add($payment);
            if($payment_result['success'] == true) {
                $_SESSION['notice'] = 'Payment Successful! ' . $u->name . ' is now back in the top.';
                redirect("main");
            }
        }
        $_SESSION['notice'] = 'Something went wrong in the processing, please contact us at contact@twitteramigos.com';
        redirect("main");
    }

    public function contact_send_email() {
        $this->load->model('email_model');
        $result = $this->email_model->send_contact_message($this->input->post());
        if($result['success']) {
            $this->_render('contact_success');
        } else {
            $this->session->set_flashdata('error', 'Email not sent, please send email directly to <b>contact@twitteramigos.com</b>');
            redirect("main/contact");
        }
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

    public function send_email() {
        $this->load->model('email_model');
        $this->email_model->send_email($this->user['user']);
    }

}

