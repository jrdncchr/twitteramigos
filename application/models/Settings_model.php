<?php

class Settings_Model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function get_settings_by_key($k) {
        $result = $this->db->get_where('settings', array('k' => $k));
        return $result->row();
    }

    public function get_settings_by_category($category) {
        $result = $result = $this->db->get_where('settings', array('category' => $category));
        return $this->convert($result->result());
    }

    public function update_settings($category, $settings) {
        $result['success'] = false;
        $this->db->trans_start();
        $this->db->where('category', $category);
        foreach($settings as $k => $v) {
            $this->db->where('k', $k);
            $this->db->update('settings', array('v' => $v));
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            $result['success'] = true;
        }
        return $result;
    }

    public function convert(array $array) {
        $result = array();
        foreach ($array as $obj) {
            $result[$obj->k] = $obj->v;
        }
        return $result;
    }

} 