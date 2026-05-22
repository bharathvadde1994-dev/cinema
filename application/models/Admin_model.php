<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model
{
    public function get_metrics()
    {
        $booking_count = (int) $this->db->count_all('bookings');

        $pending_payments = (int) $this->db
            ->from('bookings')
            ->where('payment_status', 'pending')
            ->count_all_results();

        $pending_uploads = (int) $this->db
            ->from('bookings')
            ->group_start()
            ->where('media_status', 'not_uploaded')
            ->or_where('media_status', 'partial')
            ->group_end()
            ->count_all_results();

        $monthly_plans = (int) $this->db
            ->from('bookings')
            ->where('payment_plan', 'monthly')
            ->count_all_results();

        return array(
            array('label' => 'Total Bookings', 'value' => number_format($booking_count)),
            array('label' => 'Pending Payments', 'value' => number_format($pending_payments)),
            array('label' => 'Pending Uploads', 'value' => number_format($pending_uploads)),
            array('label' => 'Monthly Plans', 'value' => number_format($monthly_plans)),
        );
    }

    public function get_bookings($filters = array())
    {
        $query = $this->db
            ->select('bookings.*, users.first_name, users.last_name, companies.name AS company_name')
            ->from('bookings')
            ->join('users', 'users.id = bookings.user_id', 'left')
            ->join('companies', 'companies.id = bookings.company_id', 'left');

        $keyword = isset($filters['q']) ? trim((string) $filters['q']) : '';
        $status_filter = isset($filters['filter']) ? trim((string) $filters['filter']) : '';

        if ($keyword !== '') {
            $query
                ->group_start()
                ->like('bookings.booking_reference', $keyword)
                ->or_like('bookings.campaign_name', $keyword)
                ->or_like('companies.name', $keyword)
                ->or_like('users.first_name', $keyword)
                ->or_like('users.last_name', $keyword)
                ->group_end();
        }

        if ($status_filter !== '') {
            switch ($status_filter) {
                case 'pending_payment':
                    $query->where('bookings.payment_status', 'pending');
                    break;
                case 'paid':
                    $query->where('bookings.payment_status', 'paid');
                    break;
                case 'missing_assets':
                    $query
                        ->group_start()
                        ->where('bookings.media_status', 'not_uploaded')
                        ->or_where('bookings.media_status', 'partial')
                        ->group_end();
                    break;
                case 'monthly':
                    $query->where('bookings.payment_plan', 'monthly');
                    break;
                case 'one_time':
                    $query->where('bookings.payment_plan', 'one_time');
                    break;
            }
        }

        $rows = $query
            ->order_by('bookings.created_at', 'DESC')
            ->get()
            ->result_array();

        foreach ($rows as &$row) {
            $row = $this->hydrate_booking_summary($row);
        }
        unset($row);

        return $rows;
    }

    public function get_booking($booking_id)
    {
        $booking = $this->db
            ->select('bookings.*, users.first_name, users.last_name, users.email, companies.name AS company_name')
            ->from('bookings')
            ->join('users', 'users.id = bookings.user_id', 'left')
            ->join('companies', 'companies.id = bookings.company_id', 'left')
            ->where('bookings.id', (int) $booking_id)
            ->limit(1)
            ->get()
            ->row_array();

        if (!$booking) {
            return NULL;
        }

        $booking = $this->hydrate_booking_summary($booking);
        $booking['notes_payload'] = !empty($booking['notes']) ? json_decode($booking['notes'], TRUE) : array();
        $booking['latest_payment'] = $this->get_latest_payment((int) $booking['id']);

        $booking['items'] = $this->db
            ->select("
                booking_items.*,
                cinemas.name AS cinema_name,
                cinemas.slug AS cinema_slug,
                cinemas.city,
                cinemas.region,
                cinemas.monthly_reach,
                COALESCE(screen_stats.hall_count, 0) AS hall_count
            ", FALSE)
            ->from('booking_items')
            ->join('cinemas', 'cinemas.id = booking_items.cinema_id', 'left')
            ->join('(
                SELECT cinema_id, COUNT(*) AS hall_count
                FROM cinema_screens
                WHERE status = \'active\'
                GROUP BY cinema_id
            ) AS screen_stats', 'screen_stats.cinema_id = booking_items.cinema_id', 'left', FALSE)
            ->where('booking_items.booking_id', (int) $booking['id'])
            ->order_by('booking_items.id', 'ASC')
            ->get()
            ->result_array();

        foreach ($booking['items'] as $index => &$item) {
            $item['selected_halls'] = $this->extract_hall_count($item['play_frequency']);
            $item['weekly_reach'] = max(1000, (int) round(((int) $item['monthly_reach']) / 4.33));
            $item['weekly_reach_label'] = number_format($item['weekly_reach']);
            $item['location_label'] = trim($item['city'] . ', ' . $item['region']);
            $item['selected_months_label'] = $this->build_month_range_label(
                !empty($booking['start_date']) ? $booking['start_date'] : date('Y-m-01'),
                (int) $item['term_months']
            );
            $item['sort_number'] = $index + 1;
        }
        unset($item);

        $booking['assets'] = $this->db
            ->select('media_assets.*, booking_media_assignments.booking_item_id')
            ->from('media_assets')
            ->join('booking_media_assignments', 'booking_media_assignments.media_asset_id = media_assets.id', 'left')
            ->where('media_assets.booking_id', (int) $booking['id'])
            ->order_by('media_assets.uploaded_at', 'DESC')
            ->get()
            ->result_array();

        return $booking;
    }

    public function delete_booking($booking_id, $admin_user_id, $reason)
    {
        $booking = $this->get_booking($booking_id);

        if (!$booking) {
            return FALSE;
        }

        $snapshot = array(
            'booking_reference' => $booking['booking_reference'],
            'client_name' => $booking['client_name'],
            'booking_type' => $booking['booking_type_label'],
            'grand_total' => $booking['grand_total'],
            'currency' => $booking['currency'],
        );

        $this->db->trans_start();

        if ($this->db->table_exists('admin_booking_deletions')) {
            $this->db->insert('admin_booking_deletions', array(
                'booking_reference' => $booking['booking_reference'],
                'deleted_booking_id' => (int) $booking['id'],
                'admin_user_id' => (int) $admin_user_id,
                'delete_reason' => $reason,
                'snapshot_json' => json_encode($snapshot),
            ));
        }

        $this->db->where('id', (int) $booking_id)->delete('bookings');
        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function mark_assets_reviewed($booking_id, $admin_user_id)
    {
        $this->db->trans_start();

        $this->db
            ->where('booking_id', (int) $booking_id)
            ->update('media_assets', array(
                'review_status' => 'approved',
                'review_notes' => 'Marked as reviewed by admin.',
            ));

        $this->db
            ->where('id', (int) $booking_id)
            ->update('bookings', array(
                'media_status' => 'approved',
                'status' => 'approved',
            ));

        $this->db->insert('booking_status_history', array(
            'booking_id' => (int) $booking_id,
            'changed_by_user_id' => (int) $admin_user_id,
            'old_status' => 'under_review',
            'new_status' => 'approved',
            'comment' => 'Assets marked as reviewed by admin.',
        ));

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function request_asset_reupload($booking_id, $admin_user_id)
    {
        $this->db->trans_start();

        $this->db
            ->where('booking_id', (int) $booking_id)
            ->update('media_assets', array(
                'review_status' => 'replace_requested',
                'review_notes' => 'Admin requested a re-upload.',
            ));

        $this->db
            ->where('id', (int) $booking_id)
            ->update('bookings', array(
                'media_status' => 'review_required',
                'status' => 'under_review',
            ));

        $this->db->insert('booking_status_history', array(
            'booking_id' => (int) $booking_id,
            'changed_by_user_id' => (int) $admin_user_id,
            'old_status' => 'approved',
            'new_status' => 'under_review',
            'comment' => 'Admin requested replacement assets.',
        ));

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    protected function hydrate_booking_summary($booking)
    {
        $booking['items_count'] = (int) $this->db
            ->from('booking_items')
            ->where('booking_id', (int) $booking['id'])
            ->count_all_results();

        $booking['asset_count'] = (int) $this->db
            ->from('media_assets')
            ->where('booking_id', (int) $booking['id'])
            ->count_all_results();

        $latest_payment = $this->get_latest_payment((int) $booking['id']);
        $notes_payload = !empty($booking['notes']) ? json_decode($booking['notes'], TRUE) : array();

        $booking['client_name'] = !empty($booking['company_name'])
            ? $booking['company_name']
            : trim($booking['first_name'] . ' ' . $booking['last_name']);
        $booking['client_code'] = 'CL-' . str_pad((string) (!empty($booking['company_id']) ? $booking['company_id'] : $booking['user_id']), 4, '0', STR_PAD_LEFT);
        $booking['booking_date_label'] = !empty($booking['created_at']) ? date('M d, Y', strtotime($booking['created_at'])) : '';
        $booking['payment_status_label'] = $this->labelize($booking['payment_status']);
        $booking['assets_uploaded_label'] = $booking['media_status'] === 'not_uploaded' ? 'No' : 'Yes';
        $booking['payment_method_label'] = $this->resolve_payment_method_label($latest_payment, $notes_payload);
        $booking['subscription_type_label'] = $booking['payment_plan'] === 'monthly' ? 'Monthly' : 'One-Time';
        $booking['booking_type_label'] = (int) $booking['items_count'] > 1 ? 'Multi-Cinema' : 'Single-Cinema';
        $booking['status_badge'] = $this->resolve_status_badge($booking['payment_status'], $booking['media_status'], $booking['status']);

        return $booking;
    }

    protected function get_latest_payment($booking_id)
    {
        return $this->db
            ->from('payments')
            ->where('booking_id', (int) $booking_id)
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()
            ->row_array();
    }

    protected function resolve_payment_method_label($payment, $notes_payload)
    {
        if (!empty($notes_payload['payment_details']['provider'])) {
            return $notes_payload['payment_details']['provider'];
        }

        if (!empty($notes_payload['payment_method'])) {
            return $this->labelize(str_replace('_', ' ', $notes_payload['payment_method']));
        }

        if (!empty($payment['provider'])) {
            if ($payment['provider'] === 'simulated_gateway') {
                return !empty($notes_payload['plan']['type']) && $notes_payload['plan']['type'] === 'monthly'
                    ? 'Bank Transfer'
                    : 'Visa';
            }

            return $this->labelize(str_replace('_', ' ', $payment['provider']));
        }

        return 'Invoice';
    }

    protected function resolve_status_badge($payment_status, $media_status, $booking_status)
    {
        if ($payment_status === 'pending') {
            return 'Pending';
        }

        if ($media_status === 'not_uploaded') {
            return 'No';
        }

        if ($booking_status === 'live' || $booking_status === 'approved') {
            return 'Active';
        }

        return 'Paid';
    }

    protected function labelize($value)
    {
        return ucwords(str_replace('_', ' ', strtolower((string) $value)));
    }

    protected function extract_hall_count($play_frequency)
    {
        if (preg_match('/(\d+)/', (string) $play_frequency, $matches)) {
            return (int) $matches[1];
        }

        return 0;
    }

    protected function build_month_range_label($start_date, $months)
    {
        $months = max(1, (int) $months);
        $labels = array();
        $base = strtotime($start_date);

        for ($i = 0; $i < $months; $i++) {
            $labels[] = date('F', strtotime('+' . $i . ' month', $base));
        }

        return implode(', ', array_unique($labels));
    }
}
