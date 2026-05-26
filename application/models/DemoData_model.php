<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DemoData_model extends CI_Model
{
    public function get_home_data()
    {
        $business_types = $this->get_business_types();
        $cinemas = $this->get_cinemas();

        return array(
            'hero' => array(
                'title' => 'We Create Your Cinema Ad. You Choose the Screen.',
                'copy' => 'No creative? Provide your business details, and we\'ll create a professional cinema ready ad at no extra cost, included with your booking.',
            ),
            'stats' => array(
                array('icon' => 'trend', 'value' => '1,000+', 'label' => 'Campaigns Booked', 'detail' => 'Successful ad placements'),
                array('icon' => 'screen', 'value' => '50+', 'label' => 'Cinemas', 'detail' => 'Partner locations in DE'),
                array('icon' => 'target', 'value' => '1M+', 'label' => 'Weekly Reach', 'detail' => 'Engaged moviegoers'),
                array('icon' => 'award', 'value' => '20+', 'label' => 'Years Experience', 'detail' => 'Industry expertise'),
            ),
            'promotions' => array(
                array(
                    'icon' => 'screen',
                    'title' => '2 Cinemas - Munich',
                    'price' => 'EUR 592',
                    'period' => 'per month',
                    'features' => array(
                        'Includes 2 cinemas',
                        'Fixed premium hall configuration',
                        '10-second spot length',
                        '3-month campaign duration',
                        'Cinema-ready video production included',
                    ),
                    'note' => 'Prices shown per month excluding VAT.',
                ),
                array(
                    'icon' => 'clapper',
                    'title' => '4 Cinemas - Berlin Region',
                    'price' => 'EUR 1,184',
                    'period' => 'per month',
                    'features' => array(
                        'Includes 4 cinemas',
                        'Fixed premium hall configuration',
                        '10-second spot length',
                        '3-month campaign duration',
                        'Cinema-ready video production included',
                    ),
                    'note' => 'Prices shown per month excluding VAT.',
                ),
                array(
                    'icon' => 'play',
                    'title' => '3 Cinemas - Hamburg Metro',
                    'price' => 'EUR 888',
                    'period' => 'per month',
                    'features' => array(
                        'Includes 3 cinemas',
                        'Fixed premium hall configuration',
                        '10-second spot length',
                        '3-month campaign duration',
                        'Cinema-ready video production included',
                    ),
                    'note' => 'Prices shown per month excluding VAT.',
                ),
                array(
                    'icon' => 'camera',
                    'title' => '5 Cinemas - Nationwide',
                    'price' => 'EUR 1,480',
                    'period' => 'per month',
                    'features' => array(
                        'Includes 5 cinemas',
                        'Fixed premium hall configuration',
                        '10-second spot length',
                        '3-month campaign duration',
                        'Cinema-ready video production included',
                    ),
                    'note' => 'Prices shown per month excluding VAT.',
                ),
            ),
            'steps' => array(
                array(
                    'number' => '1',
                    'icon' => 'location',
                    'title' => 'Select Location & Cinema',
                    'description' => 'Filter our network by state and city to find the perfect cinema screens for your target audience.',
                ),
                array(
                    'number' => '2',
                    'icon' => 'calendar',
                    'title' => 'Choose Halls & Duration',
                    'description' => 'Pick specific screening halls, preferred movie genres, and how long you want your campaign to run.',
                ),
                array(
                    'number' => '3',
                    'icon' => 'screen',
                    'title' => 'Book & Go Live',
                    'description' => 'Upload your video, finalize the booking securely online, and watch your ad hit the big screen.',
                ),
            ),
            'cities' => $this->get_locations(),
            'states' => $this->get_states(),
            'cinema_names' => array_values(array_map(function ($cinema) {
                return $cinema['name'];
            }, $cinemas)),
            'business_types' => $business_types,
            'default_business_type' => $business_types[0]['slug'],
            'showcase' => array(
                'title' => 'Now Showing in CinemaX',
                'copy' => 'Your ads will be shown before these blockbusters.',
                'cta' => 'View full schedule',
                'cards' => array(
                    array('title' => 'TWILIGHT', 'variant' => 'ember'),
                    array('title' => 'NOIR', 'variant' => 'shadow'),
                    array('title' => 'Feature Presentation', 'variant' => 'featured', 'image' => 'assets/Images/Home_banner.png'),
                    array('title' => 'AURY', 'variant' => 'aury'),
                    array('title' => 'RED ROOM', 'variant' => 'scarlet'),
                ),
            ),
            'benefits' => array(
                array(
                    'icon' => 'target',
                    'title' => 'Hyperlocal targeting',
                    'description' => 'Reach audiences exactly where your business operates. Target specific postcodes and neighborhoods.',
                ),
                array(
                    'icon' => 'check',
                    'title' => 'Transparent pricing',
                    'description' => 'No hidden fees or agency markups. See the exact cost per screen and duration instantly.',
                ),
                array(
                    'icon' => 'flash',
                    'title' => 'Instant booking',
                    'description' => 'Skip the back-and-forth emails. Select your slots, upload content, and secure placement online.',
                ),
                array(
                    'icon' => 'diamond',
                    'title' => 'Premium screens',
                    'description' => 'Showcase your brand in stunning 4K and immersive surround sound on the biggest local screens.',
                ),
            ),
        );
    }

    public function get_cinemas()
    {
        return array(
            array(
                'id' => 101,
                'name' => 'Astor Grand Cinema',
                'city' => 'Berlin',
                'state' => 'Berlin',
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
                'state' => 'Hamburg',
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
                'state' => 'Bavaria',
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
                'state' => 'North Rhine-Westphalia',
                'address' => 'Hohenzollernring 22, Cologne',
                'screens' => 7,
                'monthly_reach' => '31,200',
                'formats' => '15s, 30s, 45s, 60s',
                'availability' => 'Available from July 2026',
                'lat' => 50.9391,
                'lng' => 6.9447,
            ),
            array(
                'id' => 105,
                'name' => 'Skyline Film Lounge',
                'city' => 'Frankfurt',
                'state' => 'Hesse',
                'address' => 'Zeil 121, Frankfurt',
                'screens' => 4,
                'monthly_reach' => '18,700',
                'formats' => '15s, 30s, 45s',
                'availability' => 'Available from June 2026',
                'lat' => 50.1155,
                'lng' => 8.6842,
            ),
            array(
                'id' => 106,
                'name' => 'Neckar Center Screens',
                'city' => 'Stuttgart',
                'state' => 'Baden-Wurttemberg',
                'address' => 'Konigstrasse 19, Stuttgart',
                'screens' => 5,
                'monthly_reach' => '21,300',
                'formats' => '15s, 30s, 60s',
                'availability' => 'Available from September 2026',
                'lat' => 48.7776,
                'lng' => 9.1800,
            ),
        );
    }

    public function get_states()
    {
        $states = array();

        foreach ($this->get_cinemas() as $cinema) {
            $states[$cinema['state']] = $cinema['state'];
        }

        ksort($states);

        return array_values($states);
    }

    public function get_locations()
    {
        $cities = array();

        foreach ($this->get_cinemas() as $cinema) {
            $cities[$cinema['city']] = $cinema['city'];
        }

        ksort($cities);

        return array_values($cities);
    }

    public function filter_cinemas($filters = array())
    {
        $location = isset($filters['location']) ? strtolower(trim($filters['location'])) : '';
        $cinema_name = isset($filters['cinema']) ? strtolower(trim($filters['cinema'])) : '';
        $state = isset($filters['state']) ? strtolower(trim($filters['state'])) : '';

        return array_values(array_filter($this->get_cinemas(), function ($cinema) use ($location, $cinema_name, $state) {
            if ($location !== '' && strpos(strtolower($cinema['city']), $location) === FALSE) {
                return FALSE;
            }

            if ($cinema_name !== '' && strtolower($cinema['name']) !== $cinema_name) {
                return FALSE;
            }

            if ($state !== '' && strtolower($cinema['state']) !== $state) {
                return FALSE;
            }

            return TRUE;
        }));
    }

    public function get_business_types()
    {
        return array(
            array(
                'slug' => 'cafe',
                'label' => 'Cafe',
                'title' => 'Cafe & Coffee Shops',
                'description' => 'Promote your specialty brews, cozy atmosphere, and local coffee culture to cinema audiences.',
                'preview_title' => 'Sample Ad Preview',
                'preview_copy' => 'See how your cafe ad will look on the big screen',
                'cta' => 'Get Started with Cafe',
                'requirements' => array(
                    array('icon' => 'photo', 'title' => 'Photos or Videos', 'description' => 'You can provide 2-3 photos/images of your cafe or use our existing templates.'),
                    array('icon' => 'text', 'title' => 'Texts', 'description' => 'Provide keywords so we can create a professional voiceover. Specify preferred voice (male/female).'),
                    array('icon' => 'clock', 'title' => 'Length of the Spot', 'description' => 'This spot is 25 seconds long.'),
                    array('icon' => 'palette', 'title' => 'Logo', 'description' => 'Upload logo as vector file (.svg, .eps, .ai) or high-res PNG.'),
                ),
            ),
            array(
                'slug' => 'hairdresser',
                'label' => 'Hairdresser',
                'title' => 'Hair Salons & Barbershops',
                'description' => 'Highlight transformations, premium styling, and your signature salon experience before every screening.',
                'preview_title' => 'Salon Promo Preview',
                'preview_copy' => 'Position your salon as the local style destination',
                'cta' => 'Get Started with Hairdresser',
                'requirements' => array(
                    array('icon' => 'photo', 'title' => 'Before/After Visuals', 'description' => 'Share haircut, styling, or color transformation photos for your spot.'),
                    array('icon' => 'text', 'title' => 'Offer Details', 'description' => 'Provide your services, promo details, and ideal target audience.'),
                    array('icon' => 'clock', 'title' => 'Length of the Spot', 'description' => 'Recommended running time is 20-25 seconds.'),
                    array('icon' => 'palette', 'title' => 'Brand Assets', 'description' => 'Upload your logo, salon colors, and booking URL if available.'),
                ),
            ),
            array(
                'slug' => 'real-estate',
                'label' => 'Real Estate',
                'title' => 'Real Estate & Property',
                'description' => 'Showcase flagship listings, neighborhood access, and investment potential with premium cinematic presentation.',
                'preview_title' => 'Property Showcase Preview',
                'preview_copy' => 'Present your listings with a premium, polished brand feel',
                'cta' => 'Get Started with Real Estate',
                'requirements' => array(
                    array('icon' => 'photo', 'title' => 'Property Images', 'description' => 'Send high-resolution photos or walkthrough clips of your featured properties.'),
                    array('icon' => 'text', 'title' => 'Key Selling Points', 'description' => 'Tell us the location, size, amenities, and call-to-action.'),
                    array('icon' => 'clock', 'title' => 'Length of the Spot', 'description' => 'Recommended running time is 25-30 seconds.'),
                    array('icon' => 'palette', 'title' => 'Agent Branding', 'description' => 'Include agency logo, headshot, and contact details for the closing frame.'),
                ),
            ),
            array(
                'slug' => 'pizzeria',
                'label' => 'Pizzeria',
                'title' => 'Pizzerias & Takeaway',
                'description' => 'Drive evening orders and family visits with mouth-watering food visuals built for big screens.',
                'preview_title' => 'Food Spot Preview',
                'preview_copy' => 'Turn cravings into walk-ins and delivery orders',
                'cta' => 'Get Started with Pizzeria',
                'requirements' => array(
                    array('icon' => 'photo', 'title' => 'Menu Visuals', 'description' => 'Provide your best pizza, combo, or restaurant atmosphere images.'),
                    array('icon' => 'text', 'title' => 'Promotional Message', 'description' => 'Share your core message, delivery area, and limited-time offer.'),
                    array('icon' => 'clock', 'title' => 'Length of the Spot', 'description' => 'Recommended running time is 20 seconds.'),
                    array('icon' => 'palette', 'title' => 'Logo & Contact', 'description' => 'Upload branding plus website, phone number, or QR code destination.'),
                ),
            ),
            array(
                'slug' => 'restaurants',
                'label' => 'Restaurants',
                'title' => 'Restaurants & Dining',
                'description' => 'Promote your signature dishes, ambience, and seasonal menus to local evening audiences.',
                'preview_title' => 'Dining Experience Preview',
                'preview_copy' => 'Bring your dining atmosphere to the cinema screen',
                'cta' => 'Get Started with Restaurants',
                'requirements' => array(
                    array('icon' => 'photo', 'title' => 'Food & Venue Photos', 'description' => 'Send 3-5 hero shots of dishes, drinks, and the restaurant interior.'),
                    array('icon' => 'text', 'title' => 'Reservation Hook', 'description' => 'Provide your cuisine focus, booking call-to-action, and any event offers.'),
                    array('icon' => 'clock', 'title' => 'Length of the Spot', 'description' => 'Recommended running time is 25 seconds.'),
                    array('icon' => 'palette', 'title' => 'Identity Assets', 'description' => 'Share your logo, fonts if available, and reservation link.'),
                ),
            ),
            array(
                'slug' => 'insurance',
                'label' => 'Insurance',
                'title' => 'Insurance & Financial Services',
                'description' => 'Build trust with clear messaging, local familiarity, and reassuring brand presentation on premium screens.',
                'preview_title' => 'Trust Campaign Preview',
                'preview_copy' => 'Communicate clarity, trust, and local credibility',
                'cta' => 'Get Started with Insurance',
                'requirements' => array(
                    array('icon' => 'photo', 'title' => 'Office or Team Photos', 'description' => 'Provide clean brand visuals, advisor images, or office photography.'),
                    array('icon' => 'text', 'title' => 'Core Services', 'description' => 'List your cover types, target clients, and preferred message tone.'),
                    array('icon' => 'clock', 'title' => 'Length of the Spot', 'description' => 'Recommended running time is 20-25 seconds.'),
                    array('icon' => 'palette', 'title' => 'Compliance Branding', 'description' => 'Upload your logo and any required disclaimer or regulatory copy.'),
                ),
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
