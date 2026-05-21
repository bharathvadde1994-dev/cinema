<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    public $demo;

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url');
        $this->load->model('DemoData_model', 'demo');
    }

    protected function render($view, $data = array())
    {
        $defaults = array(
            'title' => 'KinoBlick',
            'active_nav' => '',
        );

        $data = array_merge($defaults, $data);

        $this->load->view('layout/header', $data);
        $this->load->view($view, $data);
        $this->load->view('layout/footer', $data);
    }
}
