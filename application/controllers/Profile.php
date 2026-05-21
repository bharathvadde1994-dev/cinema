<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller
{
    public function index()
    {
        $this->render('profile', array(
            'title' => 'My Profile',
            'active_nav' => 'profile',
            'bookings' => $this->demo->get_user_bookings(),
        ));
    }
}
