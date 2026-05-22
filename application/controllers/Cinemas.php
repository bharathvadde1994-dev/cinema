<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cinemas extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Cinema_model', 'cinema');
    }

    public function index()
    {
        $this->config->load('google_maps', TRUE);
        $keyword = trim((string) $this->input->get('keyword', TRUE));
        $location = trim((string) $this->input->get('location', TRUE));
        $state = trim((string) $this->input->get('state', TRUE));
        $cinema = trim((string) $this->input->get('cinema', TRUE));
        $view = trim((string) $this->input->get('view', TRUE));
        $focus = trim((string) $this->input->get('focus', TRUE));

        $filters = array(
            'keyword' => $keyword !== '' ? $keyword : $location,
            'location' => $location,
            'cinema' => $cinema,
            'state' => $state,
        );

        $cinemas = $this->cinema->get_directory_cinemas($filters);
        $filter_options = $this->cinema->get_directory_filters();
        $google_maps_api_key = trim((string) getenv('GOOGLE_MAPS_API_KEY'));
        $google_maps_map_id = trim((string) getenv('GOOGLE_MAPS_MAP_ID'));

        if ($google_maps_api_key === '') {
            $google_maps_api_key = trim((string) $this->config->item('google_maps_api_key', 'google_maps'));
        }

        if ($google_maps_map_id === '') {
            $google_maps_map_id = trim((string) $this->config->item('google_maps_map_id', 'google_maps'));
        }

        $this->render('cinemas', array(
            'title' => 'Cinemas Directory',
            'active_nav' => 'cinemas',
            'cinemas' => $cinemas,
            'filters' => $filters,
            'states' => $filter_options['states'],
            'cinema_options' => $filter_options['cinemas'],
            'directory_view' => in_array($view, array('list', 'map'), TRUE) ? $view : 'list',
            'focus_slug' => $focus,
            'google_maps_api_key' => $google_maps_api_key,
            'google_maps_map_id' => $google_maps_map_id,
        ));
    }
}
