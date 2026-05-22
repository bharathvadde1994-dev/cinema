<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking_model extends CI_Model
{
    public function create_booking_order($user, $cinema, $draft, $summary, $plan)
    {
        $item = array(
            'cinema_id' => (int) $cinema['id'],
            'cinema_slug' => $cinema['slug'],
            'cinema_name' => $cinema['name'],
            'location_label' => $cinema['location_label'],
            'weekly_reach_label' => $cinema['weekly_reach_label'],
            'hall_count_selected' => (int) $draft['hall_count_selected'],
            'duration_months' => (int) $draft['duration_months'],
            'spot_length' => (int) $draft['spot_length'],
            'start_month' => $draft['start_month'],
            'currency' => $summary['currency'],
            'estimated_total' => (float) $summary['base_rate'],
            'setup_fee_total' => (float) ($summary['processing_fee'] + $summary['custom_start_fee']),
            'discount_amount' => (float) $summary['coupon_discount'],
            'tax_amount' => (float) $summary['vat'],
            'grand_total' => (float) $summary['grand_total'],
        );

        return $this->create_booking_order_from_items(
            $user,
            array($item),
            $draft,
            $summary,
            $plan,
            $cinema['name'] . ' Cinema Campaign'
        );
    }

    public function create_multi_booking_order($user, $cart, $draft, $summary, $plan)
    {
        $items = array();

        foreach ((array) $cart['items'] as $slug => $item) {
            $items[] = array(
                'cinema_id' => (int) $item['cinema_id'],
                'cinema_slug' => $item['slug'],
                'cinema_name' => $item['name'],
                'location_label' => $item['location_label'],
                'weekly_reach_label' => $item['weekly_reach_label'],
                'hall_count_selected' => (int) $item['hall_count_selected'],
                'duration_months' => (int) $item['duration_months'],
                'spot_length' => (int) $item['spot_length'],
                'start_month' => $item['start_month'],
                'currency' => $item['currency'],
                'estimated_total' => (float) $item['estimated_total'],
                'setup_fee_total' => 0.00,
                'discount_amount' => 0.00,
                'tax_amount' => 0.00,
                'grand_total' => (float) $item['estimated_total'],
            );
        }

        return $this->create_booking_order_from_items(
            $user,
            $items,
            $draft,
            $summary,
            $plan,
            count($items) . ' Cinema Campaign'
        );
    }

    public function get_booking_by_reference($reference, $user_id)
    {
        $booking = $this->db
            ->select('bookings.*, companies.name AS company_name, companies.contact_email AS company_email')
            ->from('bookings')
            ->join('companies', 'companies.id = bookings.company_id', 'left')
            ->where('bookings.booking_reference', $reference)
            ->where('bookings.user_id', (int) $user_id)
            ->limit(1)
            ->get()
            ->row_array();

        if (!$booking) {
            return NULL;
        }

        $booking['items'] = $this->db
            ->select('
                booking_items.*,
                cinemas.slug AS cinema_slug,
                cinemas.name AS cinema_name,
                cinemas.city AS cinema_city,
                cinemas.region AS cinema_region,
                cinemas.country AS cinema_country
            ')
            ->from('booking_items')
            ->join('cinemas', 'cinemas.id = booking_items.cinema_id', 'left')
            ->where('booking_items.booking_id', (int) $booking['id'])
            ->order_by('booking_items.id', 'ASC')
            ->get()
            ->result_array();

        foreach ($booking['items'] as &$item) {
            $item['location_label'] = trim($item['cinema_city'] . ', ' . $this->abbreviate_region($item['cinema_region']));
            $item['city_label'] = $item['cinema_city'];
            $item['month_label'] = date('M', strtotime($booking['start_date']));
        }
        unset($item);

        if (!empty($booking['items'])) {
            $booking['cinema_name'] = $booking['items'][0]['cinema_name'];
            $booking['location_label'] = $booking['items'][0]['location_label'];
            $booking['city_label'] = $booking['items'][0]['city_label'];
            $booking['play_frequency'] = $booking['items'][0]['play_frequency'];
            $booking['booking_item_id'] = $booking['items'][0]['id'];
        } else {
            $booking['cinema_name'] = $booking['campaign_name'];
            $booking['location_label'] = '';
            $booking['city_label'] = '';
            $booking['play_frequency'] = '0 hall(s)';
            $booking['booking_item_id'] = NULL;
        }

        $payment = $this->db
            ->from('payments')
            ->where('booking_id', (int) $booking['id'])
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()
            ->row_array();

        $booking['payment'] = $payment;
        $booking['payment_payload'] = !empty($payment['provider_payload']) ? json_decode($payment['provider_payload'], TRUE) : array();
        $booking['notes_payload'] = !empty($booking['notes']) ? json_decode($booking['notes'], TRUE) : array();
        $booking['plan_label'] = $booking['payment_plan'] === 'monthly' ? 'Monthly' : 'One Time';

        return $booking;
    }

    public function get_booking_media_assets($booking_id)
    {
        return $this->db
            ->from('media_assets')
            ->where('booking_id', (int) $booking_id)
            ->order_by('id', 'DESC')
            ->get()
            ->result_array();
    }

    public function get_booking_item_assets_map($booking_id)
    {
        $rows = $this->db
            ->select('
                booking_media_assignments.booking_item_id,
                booking_media_assignments.assignment_scope,
                media_assets.*
            ')
            ->from('booking_media_assignments')
            ->join('media_assets', 'media_assets.id = booking_media_assignments.media_asset_id')
            ->where('booking_media_assignments.booking_id', (int) $booking_id)
            ->order_by('media_assets.id', 'DESC')
            ->get()
            ->result_array();

        $map = array();

        foreach ($rows as $row) {
            $item_id = (int) $row['booking_item_id'];
            if (!isset($map[$item_id])) {
                $map[$item_id] = array();
            }
            $map[$item_id][$row['asset_slot']] = $row;
        }

        return $map;
    }

    public function upsert_booking_item_asset($booking, $booking_item_id, $user, $slot, $file_type, $upload_data)
    {
        $existing_assignment = $this->db
            ->from('booking_media_assignments')
            ->join('media_assets', 'media_assets.id = booking_media_assignments.media_asset_id')
            ->where('booking_media_assignments.booking_id', (int) $booking['id'])
            ->where('booking_media_assignments.booking_item_id', (int) $booking_item_id)
            ->where('media_assets.asset_slot', $slot)
            ->limit(1)
            ->get()
            ->row_array();

        $asset_row = array(
            'booking_id' => (int) $booking['id'],
            'user_id' => (int) $user['id'],
            'company_id' => !empty($user['company_id']) ? (int) $user['company_id'] : NULL,
            'asset_slot' => $slot,
            'file_type' => $file_type,
            'original_name' => $upload_data['client_name'],
            'stored_name' => $upload_data['file_name'],
            'mime_type' => $upload_data['file_type'],
            'file_size_bytes' => isset($upload_data['file_size']) ? (int) round($upload_data['file_size'] * 1024) : 0,
            'storage_path' => $upload_data['storage_path'],
            'review_status' => 'pending',
        );

        if ($existing_assignment) {
            $this->db->where('id', (int) $existing_assignment['media_asset_id'])->update('media_assets', $asset_row);
            return (int) $existing_assignment['media_asset_id'];
        }

        $this->db->insert('media_assets', $asset_row);
        $asset_id = (int) $this->db->insert_id();

        $this->db->insert('booking_media_assignments', array(
            'booking_id' => (int) $booking['id'],
            'booking_item_id' => (int) $booking_item_id,
            'media_asset_id' => $asset_id,
            'assignment_scope' => 'single_cinema',
            'assigned_by_user_id' => (int) $user['id'],
        ));

        return $asset_id;
    }

    public function copy_booking_item_assets($booking_id, $source_item_id, $target_item_ids, $user_id)
    {
        $source_assets = $this->db
            ->select('booking_media_assignments.media_asset_id, media_assets.asset_slot')
            ->from('booking_media_assignments')
            ->join('media_assets', 'media_assets.id = booking_media_assignments.media_asset_id')
            ->where('booking_media_assignments.booking_id', (int) $booking_id)
            ->where('booking_media_assignments.booking_item_id', (int) $source_item_id)
            ->get()
            ->result_array();

        foreach ((array) $target_item_ids as $target_item_id) {
            foreach ($source_assets as $asset) {
                $existing = $this->db
                    ->from('booking_media_assignments')
                    ->join('media_assets', 'media_assets.id = booking_media_assignments.media_asset_id')
                    ->where('booking_media_assignments.booking_id', (int) $booking_id)
                    ->where('booking_media_assignments.booking_item_id', (int) $target_item_id)
                    ->where('media_assets.asset_slot', $asset['asset_slot'])
                    ->limit(1)
                    ->get()
                    ->row_array();

                if ($existing) {
                    $this->db->where('id', (int) $existing['id'])->update('booking_media_assignments', array(
                        'media_asset_id' => (int) $asset['media_asset_id'],
                        'assignment_scope' => 'all_selected_cinemas',
                        'assigned_by_user_id' => (int) $user_id,
                    ));
                } else {
                    $this->db->insert('booking_media_assignments', array(
                        'booking_id' => (int) $booking_id,
                        'booking_item_id' => (int) $target_item_id,
                        'media_asset_id' => (int) $asset['media_asset_id'],
                        'assignment_scope' => 'all_selected_cinemas',
                        'assigned_by_user_id' => (int) $user_id,
                    ));
                }
            }
        }
    }

    public function sync_booking_media_status($booking_id)
    {
        $item_slots = array();
        $rows = $this->db
            ->select('booking_item_id, media_assets.asset_slot')
            ->from('booking_media_assignments')
            ->join('media_assets', 'media_assets.id = booking_media_assignments.media_asset_id')
            ->where('booking_media_assignments.booking_id', (int) $booking_id)
            ->get()
            ->result_array();

        foreach ($rows as $row) {
            $item_id = (int) $row['booking_item_id'];
            if (!isset($item_slots[$item_id])) {
                $item_slots[$item_id] = array();
            }
            $item_slots[$item_id][$row['asset_slot']] = TRUE;
        }

        $items = $this->db
            ->select('id')
            ->from('booking_items')
            ->where('booking_id', (int) $booking_id)
            ->get()
            ->result_array();

        $status = 'not_uploaded';
        $all_complete = !empty($items);
        $any_assets = !empty($rows);

        foreach ($items as $item) {
            $slots = isset($item_slots[(int) $item['id']]) ? $item_slots[(int) $item['id']] : array();
            if (count(array_intersect(array_keys($slots), array('photo_video', 'logo', 'text_content'))) < 3) {
                $all_complete = FALSE;
            }
        }

        if ($all_complete && $any_assets) {
            $status = 'uploaded';
        } elseif ($any_assets) {
            $status = 'partial';
        }

        $this->db->where('id', (int) $booking_id)->update('bookings', array(
            'media_status' => $status,
        ));

        return $status;
    }

    protected function create_booking_order_from_items($user, $items, $draft, $summary, $plan, $campaign_name)
    {
        if (empty($items)) {
            return NULL;
        }

        $start_date = $this->resolve_start_date($items[0]['start_month']);
        $max_duration = 1;
        foreach ($items as $item) {
            $max_duration = max($max_duration, (int) $item['duration_months']);
        }
        $end_date = date('Y-m-d', strtotime($start_date . ' +' . max(1, $max_duration - 1) . ' months'));
        $reference = $this->generate_booking_reference();
        $notes = array(
            'billing_address' => isset($draft['billing_address']) ? $draft['billing_address'] : array(),
            'delivery_address' => isset($draft['delivery_address']) ? $draft['delivery_address'] : array(),
            'payment_details' => isset($draft['payment_details']) ? $draft['payment_details'] : array(),
            'coupon_code' => isset($draft['coupon_code']) ? $draft['coupon_code'] : '',
            'plan' => $plan,
            'summary' => $summary,
            'items' => $items,
        );

        $payment_status = $plan['type'] === 'monthly' ? 'pending' : 'paid';

        $this->db->trans_start();

        $this->db->insert('bookings', array(
            'booking_reference' => $reference,
            'user_id' => (int) $user['id'],
            'company_id' => !empty($user['company_id']) ? (int) $user['company_id'] : NULL,
            'campaign_name' => $campaign_name,
            'term_months' => $max_duration,
            'ad_length_seconds' => (int) $items[0]['spot_length'],
            'start_date' => $start_date,
            'end_date' => $end_date,
            'play_frequency' => array_sum(array_map(function ($item) {
                return (int) $item['hall_count_selected'];
            }, $items)) . ' hall(s)',
            'status' => 'awaiting_uploads',
            'payment_status' => $payment_status,
            'media_status' => 'not_uploaded',
            'subtotal_amount' => $summary['subtotal'],
            'setup_fee_total' => $summary['processing_fee'] + $summary['custom_start_fee'],
            'discount_amount' => $summary['coupon_discount'],
            'tax_amount' => $summary['vat'],
            'grand_total' => $summary['grand_total'],
            'currency' => $summary['currency'],
            'payment_plan' => $plan['type'],
            'initial_payment_amount' => $plan['amount_paid_now'],
            'recurring_payment_amount' => $plan['monthly_amount'],
            'remaining_balance_amount' => $plan['amount_left'],
            'notes' => json_encode($notes),
        ));

        $booking_id = (int) $this->db->insert_id();

        foreach ($items as $item) {
            $line_total = $item['estimated_total'];
            $monthly_price = round($line_total / max(1, (int) $item['duration_months']), 2);

            $this->db->insert('booking_items', array(
                'booking_id' => $booking_id,
                'cinema_id' => (int) $item['cinema_id'],
                'ad_length_seconds' => (int) $item['spot_length'],
                'term_months' => (int) $item['duration_months'],
                'play_frequency' => (int) $item['hall_count_selected'] . ' hall(s)',
                'monthly_price' => $monthly_price,
                'setup_fee' => 0.00,
                'line_total' => $line_total,
                'status' => 'awaiting_uploads',
            ));
        }

        $payment_payload = array(
            'simulated' => TRUE,
            'payment_plan' => $plan['type'],
            'plan_label' => $plan['label'],
            'amount_paid_now' => $plan['amount_paid_now'],
            'amount_left' => $plan['amount_left'],
            'monthly_amount' => $plan['monthly_amount'],
            'months' => $plan['months'],
            'summary' => $summary,
            'billing_address' => isset($draft['billing_address']) ? $draft['billing_address'] : array(),
            'delivery_address' => isset($draft['delivery_address']) ? $draft['delivery_address'] : array(),
            'payment_details' => isset($draft['payment_details']) ? $draft['payment_details'] : array(),
        );

        $this->db->insert('payments', array(
            'booking_id' => $booking_id,
            'provider' => 'simulated_gateway',
            'transaction_reference' => 'SIM-' . strtoupper(substr(md5($reference . microtime(TRUE)), 0, 12)),
            'amount' => $plan['amount_paid_now'],
            'currency' => $summary['currency'],
            'status' => $payment_status === 'paid' ? 'paid' : 'pending',
            'paid_at' => $payment_status === 'paid' ? date('Y-m-d H:i:s') : NULL,
            'provider_payload' => json_encode($payment_payload),
        ));

        $this->db->insert('booking_status_history', array(
            'booking_id' => $booking_id,
            'changed_by_user_id' => (int) $user['id'],
            'old_status' => 'draft',
            'new_status' => 'awaiting_uploads',
            'comment' => 'Booking created with simulated ' . $plan['label'] . ' payment.',
        ));

        $this->db->trans_complete();

        if (!$this->db->trans_status()) {
            return NULL;
        }

        return $this->get_booking_by_reference($reference, (int) $user['id']);
    }

    protected function resolve_start_date($month_label)
    {
        $month_map = array(
            'jan' => 1,
            'feb' => 2,
            'mar' => 3,
            'apr' => 4,
            'may' => 5,
            'jun' => 6,
            'jul' => 7,
            'aug' => 8,
            'sep' => 9,
            'oct' => 10,
            'nov' => 11,
            'dec' => 12,
        );

        $key = strtolower(substr(trim((string) $month_label), 0, 3));
        $current_year = (int) date('Y');
        $current_month = (int) date('n');
        $month = isset($month_map[$key]) ? $month_map[$key] : $current_month;
        $year = $month < $current_month ? $current_year + 1 : $current_year;

        return sprintf('%04d-%02d-01', $year, $month);
    }

    protected function generate_booking_reference()
    {
        do {
            $reference = 'KB-' . date('Ymd') . '-' . mt_rand(10000, 99999);
            $exists = $this->db
                ->select('id')
                ->from('bookings')
                ->where('booking_reference', $reference)
                ->limit(1)
                ->get()
                ->row_array();
        } while ($exists);

        return $reference;
    }

    protected function abbreviate_region($region)
    {
        $map = array(
            'Baden-Wurttemberg' => 'BW',
            'Bavaria' => 'BY',
            'Berlin' => 'BE',
            'Brandenburg' => 'BB',
            'Bremen' => 'HB',
            'Hamburg' => 'HH',
            'Hesse' => 'HE',
            'Lower Saxony' => 'NI',
            'Mecklenburg-Vorpommern' => 'MV',
            'North Rhine-Westphalia' => 'NW',
            'Rhineland-Palatinate' => 'RP',
            'Saarland' => 'SL',
            'Saxony' => 'SN',
            'Saxony-Anhalt' => 'ST',
            'Schleswig-Holstein' => 'SH',
            'Thuringia' => 'TH',
        );

        return isset($map[$region]) ? $map[$region] : strtoupper(substr((string) $region, 0, 2));
    }
}
