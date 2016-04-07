<?php

class Subscription_Model extends CI_Model {

    protected $table = 'subscription';

    function __construct() {
        $this->load->database();
    }


    public function get($user_id = 0, $id = 0) {
        if($id > 0) {
            $this->db->where('id', $id);
        }
        if($user_id > 0) {
            $this->db->where('user_id', $user_id);
        }
        $result = $this->db->get($this->table);
        if($result->num_rows() > 0) {
            if($id > 0) {
                return $result->row();
            } else {
                return $result->result();
            }
        }
        return null;
    }

    public function get_where(array $where) {
        $result = $this->db->get_where($this->table, $where);
        return $result->row();
    }


    public function add(array $info) {
        $this->db->trans_start();

        $result = $this->db->get_where($this->table, array('user_id' => $info['user_id'], 'service' => $info['service']));
        if($result->num_rows() > 0) {
            if($info['service'] == "PREMIUM") {
                $info['expiration_date'] = date("Y-m-d H:i:s", strtotime("+1 month"));;
            }
            $existing_subscription = $result->row();
            $this->db->where('id', $existing_subscription->id);
            $this->db->update($this->table, $info);
            $id = $existing_subscription->id;
        } else {
            $this->db->insert($this->table, $info);
            $id = $this->db->insert_id();
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            return array('success' => true, 'id' => $id);
        }
        return array('success' => false, 'message' => "Something went wrong.");
    }

    public function update($id, $update) {
        $result['success'] = false;
        $this->db->where('id', $id);
        if($this->db->update($this->table, $update)) {
            $result['success'] = true;
        }
        return $result;
    }



} 