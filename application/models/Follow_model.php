<?php

class Follow_Model extends CI_Model {

    protected $follows_table = 'follows';
    protected $add_rules = [
        'required'  => [['follower_id']],
        'email'     => [['email']],
        'equals'    => [],
        'lengthMin' => [],
        'lengthMax' => []
    ];

    function __construct() {
        $this->load->database();
    }


    public function get_follows($twitter_id = 0, $id = 0) {
        if($id > 0) {
            $this->db->where('id', $id);
        }
        if($twitter_id > 0) {
            $this->db->where('follower_id', $twitter_id);
        }
        $result = $this->db->get($this->follows_table);
        if($result->num_rows() > 0) {
            if($id > 0) {
                return $result->row();
            } else {
                return $result->result();
            }
        }
        return null;
    }

    public function add_follow(array $follow) {

        /* Validate using the rules */
        $v = new Valitron\Validator($follow);
        $v->rules($this->add_rules);
        if(!$v->validate()) {
            return array('success' => false, 'message' => "Please validate your inputs.", 'errors' => $v->errors());
        }

        /* Check if follow already exists */
        $result = $this->db->get_where($this->follows_table,
            array('follower_id' => $follow['follower_id'], 'followed_id' => $follow['followed_id']));
        if($result->num_rows() > 0) {
            return array('success' => true, 'message' => "Already following.");
        }

        $this->load->library('general_functions');
        $follow['follow_back_key'] = $this->general_functions->generate_random_str(50);

        $this->db->trans_start();
        if($this->db->insert($this->follows_table, $follow)) {
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                return array('success' => true);
            }
        }

        return array('success' => false, 'message' => "Something went wrong.");
    }

    public function update_follow($follow) {
        $result['success'] = false;
        if($this->db->update($this->follows_table, $follow)) {
            $result['success'] = true;
        }
        return $result;
    }

} 