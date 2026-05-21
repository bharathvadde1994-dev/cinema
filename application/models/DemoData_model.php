<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DemoData_model extends CI_Model
{
    public function get_home_data()
    {
        return array(
            'stats' => array(
                array('value' => '48', 'label' => 'cinemas under contract'),
                array('value' => '126', 'label' => 'screens available to sell'),
                array('value' => '3-6', 'label' => 'month booking windows'),
                array('value' => '24h', 'label' => 'upload review turnaround'),
            ),
            'steps' => array(
                'Choose cinemas from the map or catalogue.',
                'Set ad length, booking duration, and start month.',
                'Pay online and upload files for one or many booked cinemas.',
            ),
            'cities' => array('Berlin', 'Hamburg', 'Munich', 'Cologne', 'Frankfurt', 'Stuttgart'),
        );
    }

    public function get_cinemas()
    {
        return array(
            array(
                'id' => 101,
                'name' => 'Astor Grand Cinema',
                'city' => 'Berlin',
                'address' => 'Potsdamer Platz 7, Berlin',
                'screens' => 8,
                'monthly_reach' => '42,000',
                'formats' => '15s, 30s, 45s',
                'availability' => 'Available from July 2026',
                'lat' => 52.5096,
                'lng' => 13.3759,
            ),
            array(
                'id' => 102,
                'name' => 'Harbor Lights Multiplex',
                'city' => 'Hamburg',
                'address' => 'Kehrwieder 4, Hamburg',
                'screens' => 6,
                'monthly_reach' => '28,500',
                'formats' => '20s, 30s, 60s',
                'availability' => 'Available from June 2026',
                'lat' => 53.5450,
                'lng' => 9.9867,
            ),
            array(
                'id' => 103,
                'name' => 'Bavaria Plaza Screens',
                'city' => 'Munich',
                'address' => 'Leopoldstrasse 54, Munich',
                'screens' => 5,
                'monthly_reach' => '24,400',
                'formats' => '15s, 30s',
                'availability' => 'Available from August 2026',
                'lat' => 48.1590,
                'lng' => 11.5864,
            ),
            array(
                'id' => 104,
                'name' => 'Rhein Forum Cinema',
                'city' => 'Cologne',
                'address' => 'Hohenzollernring 22, Cologne',
                'screens' => 7,
                'monthly_reach' => '31,200',
                'formats' => '15s, 30s, 45s, 60s',
                'availability' => 'Available from July 2026',
                'lat' => 50.9391,
                'lng' => 6.9447,
            ),
        );
    }

    public function get_booking_options()
    {
        return array(
            'durations' => array('3 months', '6 months'),
            'ad_lengths' => array('15 seconds', '30 seconds', '45 seconds', '60 seconds'),
            'packages' => array(
                array(
                    'name' => 'Starter Circuit',
                    'description' => '3 cinemas, 30-second spot, weekday emphasis',
                    'price' => 'EUR 4,800 / month',
                ),
                array(
                    'name' => 'Regional Boost',
                    'description' => '6 cinemas, mixed screen sizes, monthly reporting',
                    'price' => 'EUR 9,500 / month',
                ),
                array(
                    'name' => 'Flagship Launch',
                    'description' => '10+ cinemas, prime placements, campaign coordination',
                    'price' => 'Custom quote',
                ),
            ),
            'draft' => array(
                'cinemas' => array('Astor Grand Cinema', 'Harbor Lights Multiplex', 'Rhein Forum Cinema'),
                'term' => '6 months',
                'ad_length' => '30 seconds',
                'loop' => 'Before each main feature',
                'setup_fee' => 'EUR 350',
                'media_review' => 'Included',
                'monthly_price' => 'EUR 8,400',
                'total' => 'EUR 50,750',
            ),
        );
    }

    public function get_user_bookings()
    {
        return array(
            array(
                'reference' => 'KB-24061',
                'campaign' => 'Nova Mobility Summer Push',
                'cinemas' => '3 cinemas',
                'status' => 'Awaiting uploads',
                'term' => '6 months',
                'start_date' => '2026-07-01',
                'amount' => 'EUR 50,750',
            ),
            array(
                'reference' => 'KB-24012',
                'campaign' => 'Fresh Market Autumn Promo',
                'cinemas' => '2 cinemas',
                'status' => 'Live',
                'term' => '3 months',
                'start_date' => '2026-05-15',
                'amount' => 'EUR 14,400',
            ),
        );
    }

    public function get_admin_metrics()
    {
        return array(
            array('label' => 'Active bookings', 'value' => '27'),
            array('label' => 'Pending uploads', 'value' => '9'),
            array('label' => 'Payments to review', 'value' => '4'),
            array('label' => 'This month revenue', 'value' => 'EUR 186k'),
        );
    }

    public function get_admin_bookings()
    {
        return array(
            array(
                'id' => 24061,
                'client' => 'Nova Mobility',
                'campaign' => 'Summer Push',
                'cinemas' => 'Berlin, Hamburg, Cologne',
                'status' => 'Awaiting uploads',
                'payment' => 'Paid',
                'term' => '6 months',
                'value' => 'EUR 50,750',
            ),
            array(
                'id' => 24031,
                'client' => 'Horizon Bank',
                'campaign' => 'Mortgage Awareness',
                'cinemas' => 'Munich, Cologne',
                'status' => 'In review',
                'payment' => 'Pending',
                'term' => '3 months',
                'value' => 'EUR 18,900',
            ),
            array(
                'id' => 24012,
                'client' => 'Fresh Market',
                'campaign' => 'Autumn Promo',
                'cinemas' => 'Berlin, Munich',
                'status' => 'Live',
                'payment' => 'Paid',
                'term' => '3 months',
                'value' => 'EUR 14,400',
            ),
        );
    }

    public function get_admin_booking($id)
    {
        foreach ($this->get_admin_bookings() as $booking) {
            if ((int) $booking['id'] === (int) $id) {
                return array(
                    'summary' => $booking,
                    'uploads' => array(
                        array('file' => 'nova-30s-master.mp4', 'scope' => 'Applied to all selected cinemas', 'status' => 'Approved'),
                        array('file' => 'nova-screen-poster.jpg', 'scope' => 'Applied to Berlin + Cologne', 'status' => 'Waiting replacement'),
                    ),
                    'timeline' => array(
                        'Booking created and payment intent generated.',
                        'Card payment confirmed and invoice issued.',
                        'Media upload requested from advertiser.',
                        'Operations review pending for one static artwork.',
                    ),
                );
            }
        }

        return NULL;
    }
}
