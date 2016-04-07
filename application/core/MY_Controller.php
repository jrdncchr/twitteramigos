<?php

class MY_Controller extends CI_Controller {

    protected $user;
    protected $data;

    protected $paypal_url;
    protected $paypal_business;

    protected $css;
    protected $js;
    protected $bower;

    protected $title = "Twitter Amigos";
    protected $description = "Follow Back";
    protected $keywords = "Twitter Follow Back";
    protected $author = "Danero";

    public $request_method;

    public function __construct($logged = false) {
        parent::__construct();
        $this->request_method = $_SERVER['REQUEST_METHOD'];
        $this->load->helper(array('url'));
        $this->load->library('session');

        $this->_loadConstants();

        $user = $this->session->userdata("user");
        if(null != $user) {
            $this->user = $user;
        }

        if($this->is_localhost()) {
            $this->paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
            $this->paypal_business = "jrdn-sb-business@gmail.com";
        } else {
            $this->paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
            $this->paypal_business = "jrdn-sb-business@gmail.com";
        }

        $this->data['site_title'] = $this->title;
        $this->data['site_description'] = $this->description;
        $this->data['site_keywords'] = $this->keywords;
        $this->data['site_author'] = $this->author;
        $this->data['user'] = $user;
    }

    public function _render($view) {
        $this->data['css'] = $this->css;
        $this->data['js'] = $this->js;
        $this->data['bower'] = $this->bower;

        $data['head'] = $this->load->view('templates/head', $this->data, true);
        $data['nav'] = $this->load->view('templates/nav', $this->data, true);
        $data['footer'] = $this->load->view('templates/footer', $this->data, true);
        $data['content'] = $this->load->view($view, $data, true);

        $this->load->view('templates/skeleton', $data);
    }

    public function _renderA($view) {
        $this->data['css'] = $this->css;
        $this->data['js'] = $this->js;
        $this->data['bower'] = $this->bower;

        $data['head'] = $this->load->view('templates/head', $this->data, true);
        $data['nav'] = $this->load->view('templates/nav', $this->data, true);
        $data['sidenav'] = $this->load->view('templates/admin/sidenav', $this->data, true);
        $data['footer'] = $this->load->view('templates/footer', $this->data, true);
        $data['content'] = $this->load->view($view, $data, true);

        $this->load->view('templates/admin/skeleton', $data);
    }

    public function _loadConstants() {
        $this->session->unset_userdata('constants_loaded');
        $constants_loaded = $this->session->userdata('constants_loaded');
        if(!$constants_loaded) {
            $this->load->model("api_model");
            $this->api_model->load_constants();
            $this->session->set_userdata('constants_loaded', true);
        }
    }

    public function is_localhost() {
        $whitelist = array('127.0.0.1', '::1');
        if( in_array( $_SERVER['REMOTE_ADDR'], $whitelist)) {
            return true;
        }
        return false;
    }

} 