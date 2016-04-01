<?php

class User_Model extends CI_Model {

    protected $user_table = 'user';
    protected $user_secret_table = 'user_secret';
    protected $add_rules = [
        'required'  => [['name'], ['twitter_id']],
        'email'     => [],
        'equals'    => [],
        'lengthMin' => [],
        'lengthMax' => []
    ];

    function __construct() {
        $this->load->database();
    }

    public function get_user($id) {
        $result = $this->db->get_where($this->user_table, array('id' => $id));
        if($result->num_rows() > 0) {
            return $result->row();
        }
        return null;
    }

    public function add_user(array $user_info) {

        /* Validate using the rules */
        $v = new Valitron\Validator($user_info);
        $v->rules($this->add_rules);
        if(!$v->validate()) {
            return array('success' => false, 'message' => "Please validate your inputs.", 'errors' => $v->errors());
        }

        /* Check if twitter user doest not exist yet */
        $result = $this->db->get_where($this->user_table, array('twitter_id' => $user_info['twitter_id']));
        if($result->num_rows() == 0) {

            /* Insert user info */
            $this->db->trans_start();
            $twitter_access_token = $user_info['twitter_access_token'];
            unset($user_info['twitter_access_token']);
            if($this->db->insert($this->user_table, $user_info)) {
                $user_secret = array(
                    'user_id' => $this->db->insert_id(),
                    'twitter_access_token' => $twitter_access_token
                );

                /* Insert user secret */
                if($this->db->insert($this->user_secret_table, $user_secret)) {
                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                    } else {
                        $this->db->trans_commit();
                        return array('success' => true);
                    }
                }
            }
        } else {
            return array('success' => true, 'user' => $result->row());
        }

        return array('success' => true);
    }

    public function add_user_for_admin($user) {
        $result = $this->db->get_where($this->user_table, array('twitter_id' => $user['twitter_id']));
        if($result->num_rows() == 0) {
            if($this->db->insert($this->user_table, $user)) {
                return array('success' => true);
            }
        } else {
            return array('success' => false, 'message' => 'User already exist!');
        }
        return array('success' => false);
    }

    public function update_user($id, array $update) {
        $this->db->trans_start();
        $this->db->where("id", $id);
        if($this->db->update($this->user_table, $update)) {
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                $session_user = $this->session->userdata('user');
                $user = $this->get_user($id);
                $session_user['user'] = $user;
                $this->session->set_userdata('user', $session_user);
                return array('success' => true);
            }
        }
        return array('success' => false, 'message' => "Something went wrong.");
    }

    public function get_users($public_only = true, $user_id = 0) {
        if($user_id > 0) {
            $this->db->where('id !=', $user_id);
        }
        if($public_only) {
            $this->db->where('show_profile', 1);
        }
        $result = $this->db->get($this->user_table);
        return $result->result();
    }

    public function get_twitter_id($screen_name) {
        $url = 'https://tweeterid.com/ajax.php';
        $fields = array(
            'input' => urlencode($screen_name)
        );

        $fields_string = "";
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');

        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

        $result = curl_exec($ch);

        curl_close($ch);
        return $result[0];
    }

} 