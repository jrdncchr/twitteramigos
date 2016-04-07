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
                if(null == $this->user) {
                    if($this->input->post('type') == "premium") {
                        $list = $this->user_model->get_users_list(null, "premium");
                    } else {
                        $list = $this->user_model->get_users_list();
                    }
                } else {
                    $list = $this->user_model->get_users_list($this->user['user']->twitter_id, $this->input->post('type'));
                }
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