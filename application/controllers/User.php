<?php

class User extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("user_model");
    }

    public function action() {
        $result = array('success' => false);

        $action = $this->input->post("action");
        switch($action) {

            case 'list' :
                $user_id = $this->user ? $this->user['user']->id : 0;
                $public_only = $this->input->post('public_only');
                $list = $this->user_model->get_users($public_only, $user_id);
                $result = array('data' => $list);
                break;

            case 'update' :
                $result = $this->user_model->update_user($this->user['user']->id, $this->input->post("user"));
                break;

            default:
                $result['message'] = "Action not found.";
        }

        echo json_encode($result);
    }

} 