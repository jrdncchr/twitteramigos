<?php

class Payment_Model extends CI_Model {

    protected $table = 'payment';

    function __construct() {
        $this->load->database();
    }


    public function get($paid_by = 0, $id = 0) {
        if($id > 0) {
            $this->db->where('id', $id);
        }
        if($paid_by > 0) {
            $this->db->where('paid_by', $paid_by);
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

    public function add(array $info) {
        $this->db->trans_start();
        if($this->db->insert($this->table, $info)) {
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                return array('success' => true, 'insert_id' => $this->db->insert_id());
            }
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