<?php

class Api_Model extends CI_Model {

    private $secret_table = "secret";

    /* Get and set all constants */
    public function load_constants() {
        $this->load->database();
        $secrets = $this->db->get($this->secret_table);
        foreach($secrets->result() as $s) {
            define($s->name, $s->value);
        }
    }

} 