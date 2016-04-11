<?php

class Admin extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $admin = $this->session->userdata("admin");
        if(null != $admin) {
            $this->data['admin'] = $admin;
        } else {
            redirect("main");
        }

        $this->load->model("admin_model");
        $this->css[] = "admin.css";
    }


    public function action() {
        $result = array('success' => false);

        $action = $this->input->post("action");
        switch($action) {

            case 'user_list' :
                $this->load->model('user_model');
                $public_only = $this->input->post('public_only');
                $list = $this->user_model->get_users($public_only);
                $result = array('data' => $list);
                break;

            case 'user_add' :
                $this->load->model('user_model');
                $result = $this->user_model->add_user_for_admin($this->input->post('user'));
                break;

            case 'admin_update' :
                $result = $this->admin_model->update_admin($this->data['admin']->id, $this->input->post("admin"));
                break;

            case 'settings_update' :
                $this->load->model('settings_model');
                $result = $this->settings_model->update_settings($this->input->post('category'), $this->input->post('settings'));
                break;

            case 'ads_update' :
                $this->load->model('settings_model');
                $result = $this->settings_model->update_settings($this->input->post('category'), $this->input->post('settings'));
                break;

            case 'subscribe' :
                $this->load->model('subscription_model');
                $result = $this->subscription_model->add($this->input->post('subscription'));
                break;

            default:
                $result['message'] = "Action not found.";
        }

        echo json_encode($result);
    }

    public function index() {
        $this->users();
    }

    public function settings() {
        $this->_renderA("admin/settings");
    }

    public function ads() {
        $this->load->model('settings_model');
        $this->data['ads'] = $this->settings_model->get_settings_by_category('ads');
        $this->_renderA("admin/ads");
    }

    public function users() {
        $this->_renderA("admin/users");
    }

    public function email() {
        $this->load->model('settings_model');
        $this->data['email_notification'] = $this->settings_model->get_settings_by_category('email_notification');
        $this->data['email_contact'] = $this->settings_model->get_settings_by_category('email_contact');
        $this->data['paypal'] = $this->settings_model->get_settings_by_category('paypal');
        $this->_renderA("admin/email");
    }

    public function logout() {
        $this->session->unset_userdata('admin');
        redirect('main');
    }

    public function get_twitter_id() {
        $this->load->model('user_model');
        $this->user_model->get_twitter_id($this->input->post('name'));
    }

    public function add_admin() {
        $admin = array(
            'email' => 'cacherojordan@gmail.com',
            'password' => 'admin',
            'name' => 'Jordan Cachero'
        );
        $result = $this->admin_model->add_admin($admin);
        var_dump($result);
    }

} 