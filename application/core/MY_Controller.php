<?php

class MY_Controller extends CI_Controller {

    protected $user;
    protected $data;

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

        $this->data['site_title'] = $this->title;
        $this->data['site_description'] = $this->description;
        $this->data['site_keywords'] = $this->keywords;
        $this->data['site_author'] = $this->author;
    }

    public function _render($view)
    {
        $this->data['css'] = $this->css;
        $this->data['js'] = $this->js;
        $this->data['bower'] = $this->bower;

        $data['head'] = $this->load->view('templates/head', $this->data, true);
        $data['nav'] = $this->load->view('templates/nav', $this->data, true);
        $data['footer'] = $this->load->view('templates/footer', $this->data, true);
        $data['content'] = $this->load->view($view, $data, true);

        $this->load->view('templates/skeleton', $data);
    }

} 