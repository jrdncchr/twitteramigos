<?php

class Admin_Model extends CI_Model {

    protected $admin_table = 'admin';
    protected $add_rules = [
        'required'  => [['name'], ['email'], ['password']],
        'email'     => [['email']],
        'equals'    => [],
        'lengthMin' => [],
        'lengthMax' => []
    ];

    function __construct() {
        $this->load->database();
    }

    public function login(array $auth) {
        $result = array('success' => false,  'message' => 'Incorrect Email or Password.');
        if(isset($auth['email']) && isset($auth['password'])) {
            $sql = $this->db->get($this->admin_table, array('email' => $auth['email']));
            if($sql->num_rows() > 0) {
                $admin = $sql->row();
                if (hash_equals($admin->password, crypt($auth['password'], $admin->password))) {
                    $result = array('success' => true);
                    $this->session->set_userdata('admin', $admin);
                }
            }
        }
        return $result;
    }

    public function add_admin(array $admin) {

        /* Validate using the rules */
        $v = new Valitron\Validator($admin);
        $v->rules($this->add_rules);
        if(!$v->validate()) {
            return array('success' => false, 'message' => "Please validate your inputs.", 'errors' => $v->errors());
        }

        /* Check if unique identifier already exists */
        $result = $this->db->get_where($this->admin_table, array('email' => $admin['email']));
        if($result->num_rows() > 0) {
            return array('success' => false, 'message' => "Email already exists.", 'field' => 'email');
        }

        /* Add salt and crypt the password */
        $this->load->library('general_functions');
        $salt = $this->general_functions->generate_random_str(20);
        $admin['password'] = crypt($admin['password'], $salt);


        /* Insert admin info */
        $this->db->trans_start();
        if($this->db->insert($this->admin_table, $admin)) {
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                return array('success' => true);
            }
        }

        return array('success' => false, 'message' => "Something went wrong.");
    }

    public function get_admin($id) {
        $result = $this->db->get_where($this->admin_table, array('id' => $id));
        if($result->num_rows() > 0) {
            return $result->row();
        }
        return null;
    }


    public function update_admin($id, $admin) {
        $result['success'] = false;
        if(isset($admin['password'])) {
            $logged_admin = $this->session->userdata('admin');
            if (hash_equals($logged_admin->password, crypt($admin['password'], $logged_admin->password))) {
                if($admin['new_password'] == $admin['confirm_password']) {
                    /* Add salt and crypt the new password */
                    $this->load->library('general_functions');
                    $salt = $this->general_functions->generate_random_str(20);
                    $admin['password'] = crypt($admin['new_password'], $salt);
                    unset($admin['new_password'], $admin['confirm_password']);
                } else {
                    return array('success' => false, 'message' => 'Password did not match.');
                }
            } else {
                return array('success' => false, 'message' => 'Incorrect Password');
            }
        }
        $this->db->where('id', $id);
        if($this->db->update($this->admin_table, $admin)) {
            $admin = $this->get_admin($id);
            $this->session->set_userdata('admin', $admin);
            $result['success'] = true;
        }
        return $result;
    }

} 