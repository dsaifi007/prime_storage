<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_controller extends MY_Controller {

    protected $data = [];

    public function __construct() {
        parent::__construct();
        $this->user_not_loggedin();
        $this->load->model("admin/users/payment_model", "payment_model");
    }

    public function index() {    
        $this->data['view'] = "admin/users/payment_listing";
        $this->data['items'] = $this->payment_model->getAllPaymentList();
        $this->displayview($this->data);
    }
}

?>