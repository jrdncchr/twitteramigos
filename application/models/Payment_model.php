<?php

class Follow_Model extends CI_Model {

    protected $following_table = 'following';
    protected $followed_back_table = 'followed_back';

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
        $result = $this->db->get($this->following_table);
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

        /* Check if follow already exists */
        $result = $this->db->get_where($this->following_table,
            array('follower_id' => $follow['follower_id'], 'followed_id' => $follow['followed_id']));
        if($result->num_rows() > 0) {
            return array('success' => true, 'message' => "Already following.");
        }

        $this->load->library('general_functions');
        $follow['follow_back_key'] = $this->general_functions->generate_random_str(50);

        $this->db->trans_start();
        if($this->db->insert($this->following_table, $follow)) {
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                return array('success' => true, 'follow_back_key' => $follow['follow_back_key']);
            }
        }

        return array('success' => false, 'message' => "Something went wrong.");
    }

    public function update_follow($follow) {
        $result['success'] = false;
        if($this->db->update($this->following_table, $follow)) {
            $result['success'] = true;
        }
        return $result;
    }

    public function follow_back($data) {
        $this->db->trans_start();
        $result = $this->db->get_where($this->following_table, $data);
        if($result->num_rows() > 0) {
            $follow = $result->row();

            $followed_back = array(
                'followed_back_id' => $data['followed_id'],
                'followed_id' => $data['follower_id'],
                'following_id' => $follow->id
            );
            if($this->db->insert($this->followed_back_table,$followed_back)) {
                $following = array(
                    'follower_id' => $data['followed_id'],
                    'followed_id' => $data['follower_id']
                );
                $this->db->insert($this->following_table, $following);

            }
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            return array('success' => true);
        }
        return array('success' => false);
    }

} 