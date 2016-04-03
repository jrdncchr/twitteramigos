<?php

class Page extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function terms_and_conditions() {
        $this->_render('static/terms_and_conditions');
    }

    public function privacy_policy() {
        $this->_render('static/privacy_policy');
    }


} 