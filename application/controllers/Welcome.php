<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller
{
    public function index()
    {
        $this->redirect_admin_to_portal();

        $home = $this->demo->get_home_data();

        $this->render('home', array(
            'title' => 'KinoBlick',
            'active_nav' => 'home',
            'hero' => $home['hero'],
            'stats' => $home['stats'],
            'steps' => $home['steps'],
            'cities' => $home['cities'],
            'states' => $home['states'],
            'cinema_names' => $home['cinema_names'],
            'business_types' => $home['business_types'],
            'default_business_type' => $home['default_business_type'],
            'showcase' => $home['showcase'],
            'benefits' => $home['benefits'],
        ));
    }
}
