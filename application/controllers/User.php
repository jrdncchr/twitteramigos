<?php

class User extends MY_Controller {

    public function index() {

    }

    public function sign_up() {
        if($this->request_method == "POST") {

            $user_info = $this->input->post();
            $this->load->model('user_model');
            $result = $this->user_model->add_user($user_info);
            echo json_encode($result);

        } else {
            $this->load->model("twitter_model");
            $this->data['twitter_auth_url'] = $this->twitter_model->get_twitter_auth_url();
            $this->_render('user/sign_up');

        }
    }

    public function login() {
        $this->_render('user/login');
    }

} 