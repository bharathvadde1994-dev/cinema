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

        $this->render('cinemas', array(
            'title' => 'Cinemas Directory',
            'active_nav' => 'cinemas',
            'cinemas' => $cinemas,
            'filters' => $filters,
            'states' => $filter_options['states'],
            'cinema_options' => $filter_options['cinemas'],
            'directory_view' => in_array($view, array('list', 'map'), TRUE) ? $view : 'list',
            'focus_slug' => $focus,
        ));
    }
}
