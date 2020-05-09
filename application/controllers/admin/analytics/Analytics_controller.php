<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Analytics_controller extends MY_Controller {

    protected $data = [];

    public function __construct() {
        parent::__construct();
        $this->user_not_loggedin();
        $this->load->model("admin/analytics/analytics_model", "analytics_model");
    }

    public function index() {
        $this->data['success'] = ($this->session->flashdata("success")) ? $this->session->flashdata("success") : '';
        $this->data['view'] = "admin/analytics/analytics_listing";
        $this->data['items'] = $this->analytics_model->getAllanalyticsList();    
        $this->displayview($this->data);
    }  
}

?>