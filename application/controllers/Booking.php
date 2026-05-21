<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking extends MY_Controller
{
    public function index()
    {
        $options = $this->demo->get_booking_options();

        $this->render('booking', array(
            'title' => 'Build Your Booking',
            'active_nav' => 'booking',
            'cinemas' => $this->demo->get_cinemas(),
            'booking_options' => $options,
        ));
    }
}
