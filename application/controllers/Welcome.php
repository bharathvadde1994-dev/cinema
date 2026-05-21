<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller
{
    public function index()
    {
        $home = $this->demo->get_home_data();

        $this->render('home', array(
            'title' => 'Cinema Advertising Network',
            'active_nav' => 'home',
            'stats' => $home['stats'],
            'steps' => $home['steps'],
            'cities' => $home['cities'],
            'featured_cinemas' => $this->demo->get_cinemas(),
            'booking_options' => $this->demo->get_booking_options(),
        ));
    }
}
