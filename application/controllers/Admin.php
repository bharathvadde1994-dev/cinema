<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller
{
    public function index()
    {
        redirect('admin/bookings');
    }

    public function bookings()
    {
        $this->render('admin/bookings', array(
            'title' => 'Admin Bookings',
            'active_nav' => 'admin',
            'metrics' => $this->demo->get_admin_metrics(),
            'bookings' => $this->demo->get_admin_bookings(),
        ));
    }

    public function booking($id)
    {
        $booking = $this->demo->get_admin_booking($id);

        if ($booking === NULL) {
            show_404();
        }

        $this->render('admin/booking_detail', array(
            'title' => 'Booking #' . $id,
            'active_nav' => 'admin',
            'booking' => $booking,
        ));
    }
}
