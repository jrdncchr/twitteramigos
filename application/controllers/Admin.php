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

            default:
                $result['message'] = "Action not found.";
        }

        echo json_encode($result);
    }

    public function index() {
        $this->dashboard();
    }

    public function dashboard() {
        $this->_renderA("admin/dashboard");
    }

    public function settings() {
        $this->_renderA("admin/settings");
    }

    public function users() {
        $this->_renderA("admin/users");
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