<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cinema_model extends CI_Model
{
    public function get_directory_filters()
    {
        return array(
            'states' => $this->db
                ->select('region')
                ->from('cinemas')
                ->where('status', 'active')
                ->where('region IS NOT NULL', NULL, FALSE)
                ->group_by('region')
                ->order_by('region', 'ASC')
                ->get()
                ->result_array(),
            'cinemas' => $this->db
                ->select('name, slug')
                ->from('cinemas')
                ->where('status', 'active')
                ->order_by('name', 'ASC')
                ->get()
                ->result_array(),
        );
    }

    public function get_directory_cinemas($filters = array())
    {
        $query = $this->db
            ->select("
                cinemas.id,
                cinemas.name,
                cinemas.slug,
                cinemas.city,
                cinemas.region,
                cinemas.country,
                cinemas.address_line,
                cinemas.postal_code,
                cinemas.latitude,
                cinemas.longitude,
                cinemas.monthly_reach,
                cinemas.description,
                COALESCE(screen_stats.hall_count, 0) AS hall_count,
                COALESCE(screen_stats.seat_count, 0) AS seat_count,
                rate_stats.starting_price,
                rate_stats.currency
            ", FALSE)
            ->from('cinemas')
            ->join('(
                SELECT cinema_id, COUNT(*) AS hall_count, COALESCE(SUM(seat_capacity), 0) AS seat_count
                FROM cinema_screens
                WHERE status = "active"
                GROUP BY cinema_id
            ) AS screen_stats', 'screen_stats.cinema_id = cinemas.id', 'left', FALSE)
            ->join('(
                SELECT cinema_id, MIN(monthly_price) AS starting_price, MIN(currency) AS currency
                FROM cinema_rate_cards
                WHERE status = "active"
                GROUP BY cinema_id
            ) AS rate_stats', 'rate_stats.cinema_id = cinemas.id', 'left', FALSE)
            ->where('cinemas.status', 'active');

        $keyword = isset($filters['keyword']) ? trim($filters['keyword']) : '';
        $state = isset($filters['state']) ? trim($filters['state']) : '';
        $cinema = isset($filters['cinema']) ? trim($filters['cinema']) : '';

        if ($keyword !== '') {
            $query
                ->group_start()
                ->like('cinemas.name', $keyword)
                ->or_like('cinemas.city', $keyword)
                ->or_like('cinemas.region', $keyword)
                ->or_like('cinemas.postal_code', $keyword)
                ->or_like('cinemas.address_line', $keyword)
                ->group_end();
        }

        if ($state !== '') {
            $query->where('cinemas.region', $state);
        }

        if ($cinema !== '') {
            $query
                ->group_start()
                ->where('cinemas.slug', $cinema)
                ->or_where('cinemas.name', $cinema)
                ->group_end();
        }

        $rows = $query
            ->order_by('cinemas.city', 'ASC')
            ->order_by('cinemas.name', 'ASC')
            ->get()
            ->result_array();

        foreach ($rows as &$row) {
            $row['state'] = $row['region'];
            $row['address'] = trim($row['address_line'] . ', ' . $row['city']);
            $row['weekly_reach'] = max(1000, (int) round(((int) $row['monthly_reach']) / 4.33));
            $row['weekly_reach_label'] = number_format($row['weekly_reach']) . ' / week reach';
            $row['seat_count_label'] = number_format((int) $row['seat_count']) . ' seats';
            $row['hall_count_label'] = (int) $row['hall_count'] . ' halls';
            $row['formats_label'] = 'Video + Image Ads';
            $row['availability_label'] = 'Unlimited';
            $row['location_label'] = $row['city'] . ', ' . $this->abbreviate_region($row['region']);
            $row['image_path'] = base_url('assets/Images/Home_banner.png');
            $row['starting_price_label'] = !empty($row['starting_price'])
                ? $row['currency'] . ' ' . number_format((float) $row['starting_price'], 0)
                : 'Custom';
        }

        return $rows;
    }

    public function get_booking_cinemas()
    {
        return $this->db
            ->select('name, slug, city, region')
            ->from('cinemas')
            ->where('status', 'active')
            ->order_by('city', 'ASC')
            ->order_by('name', 'ASC')
            ->get()
            ->result_array();
    }

    public function get_booking_cinema($identifier)
    {
        if (trim((string) $identifier) === '') {
            return NULL;
        }

        $row = $this->db
            ->select("
                cinemas.id,
                cinemas.name,
                cinemas.slug,
                cinemas.city,
                cinemas.region,
                cinemas.address_line,
                cinemas.postal_code,
                cinemas.latitude,
                cinemas.longitude,
                cinemas.monthly_reach,
                cinemas.description,
                COALESCE(screen_stats.hall_count, 0) AS hall_count,
                COALESCE(screen_stats.seat_count, 0) AS seat_count,
                rate_stats.starting_price,
                rate_stats.currency
            ", FALSE)
            ->from('cinemas')
            ->join('(
                SELECT cinema_id, COUNT(*) AS hall_count, COALESCE(SUM(seat_capacity), 0) AS seat_count
                FROM cinema_screens
                WHERE status = "active"
                GROUP BY cinema_id
            ) AS screen_stats', 'screen_stats.cinema_id = cinemas.id', 'left', FALSE)
            ->join('(
                SELECT cinema_id, MIN(monthly_price) AS starting_price, MIN(currency) AS currency
                FROM cinema_rate_cards
                WHERE status = "active"
                GROUP BY cinema_id
            ) AS rate_stats', 'rate_stats.cinema_id = cinemas.id', 'left', FALSE)
            ->group_start()
            ->where('cinemas.slug', $identifier)
            ->or_where('cinemas.name', $identifier)
            ->group_end()
            ->where('cinemas.status', 'active')
            ->limit(1)
            ->get()
            ->row_array();

        if (!$row) {
            return NULL;
        }

        $row['state'] = $row['region'];
        $row['weekly_reach'] = max(1000, (int) round(((int) $row['monthly_reach']) / 4.33));
        $row['weekly_reach_label'] = number_format($row['weekly_reach']) . ' / week reach';
        $row['hall_count_label'] = (int) $row['hall_count'] . ' screens available';
        $row['seat_count_label'] = number_format((int) $row['seat_count']) . ' seats';
        $row['location_label'] = $row['city'] . ', ' . $this->abbreviate_region($row['region']);
        $row['starting_price_label'] = !empty($row['starting_price'])
            ? $row['currency'] . number_format((float) $row['starting_price'], 0)
            : 'Custom';

        return $row;
    }

    public function get_cinemas_by_slugs($slugs)
    {
        $slugs = array_values(array_filter(array_map('trim', (array) $slugs)));

        if (empty($slugs)) {
            return array();
        }

        return $this->db
            ->select('name, slug, city, region')
            ->from('cinemas')
            ->where_in('slug', $slugs)
            ->where('status', 'active')
            ->order_by('city', 'ASC')
            ->order_by('name', 'ASC')
            ->get()
            ->result_array();
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

        return isset($map[$region]) ? $map[$region] : strtoupper(substr($region, 0, 2));
    }
}
