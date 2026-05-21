<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cinemas extends MY_Controller
{
    public function index()
    {
        $this->render('cinemas', array(
            'title' => 'Available Cinemas',
            'active_nav' => 'cinemas',
            'cinemas' => $this->demo->get_cinemas(),
        ));
    }
}
