<?php

class User_Model extends CI_Model {

    protected $user_table = 'user';
    protected $user_secret_table = 'user_secret';
    protected $add_rules = [
        'required'  => [['email'], ['name'], ['password']],
        'email'     => [['email']],
        'equals'    => [['password', 'confirm_password']],
        'lengthMin' => [['name', 5], ['password', 5]],
        'lengthMax' => [['name', 50]]
    ];

    function __construct() {
        $this->load->database();
    }

    public function login(array $auth) {
        $result = array('success' => false,  'message' => 'Incorrect Email or Password.');
        if(isset($auth['email']) && isset($auth['password'])) {
            $user_get = $this->db->get($this->user_table, array('email' => $auth['email']));
            if($user_get->num_rows() > 0) {
                $user = $user_get->row();
                $user_secret_get = $this->db->get($this->user_secret_table, array('user_id' => $user->user_id));
                $user_secret = $user_secret_get->row();
                if (hash_equals($user_secret->password, crypt($auth['password'], $user_secret->password))) {
                    $result = array('success' => true);
                    $_SESSION['user'] = $user;
                    $this->session->set_userdata('user', $user);
                }
            }
        }
        return $result;
    }

    public function add_user(array $user_info) {

        /* Validate using the rules */
        $v = new Valitron\Validator($user_info);
        $v->rules($this->add_rules);
        if(!$v->validate()) {
            return array('success' => false, 'message' => "Please validate your inputs.", 'errors' => $v->errors());
        }

        /* Check if unique identifier already exists */
        $result = $this->db->get_where($this->user_table, array('email' => $user_info['email']));
        if($result->num_rows() > 0) {
            return array('success' => false, 'message' => "Email already exists.", 'field' => 'email');
        }

        /* Add salt and crypt the password */
        $this->load->library('general_functions');
        $salt = $this->general_functions->generate_random_str(20);
        $password = crypt($user_info['password'], $salt);

        /* Remove password keys in user info, it will be stored in user secret table */
        unset($user_info['password']);
        unset($user_info['confirm_password']);

        /* Insert user info */
        $this->db->trans_start();
        if($this->db->insert($this->user_table, $user_info)) {

            $user_secret = array(
                'password' => $password,
                'user_id' => $this->db->insert_id(),
                'email_confirmation' => $this->general_functions->generate_random_str(100)
            );

            /* Insert user secret */
            if($this->db->insert($this->user_secret_table, $user_secret)) {
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                } else {
                    $this->db->trans_commit();
                    /* TO DO : Send Email Confirmation */
                    return array('success' => true);
                }
            }
        }

        return array('success' => false, 'message' => "Something went wrong.");
    }


} 