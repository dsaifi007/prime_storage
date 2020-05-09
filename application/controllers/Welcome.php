<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function index() {
        redirect("login");
        //$this->load->view('welcome_message');
    }
    public function timeZone() {
        echo date("Y-m-d H:i:s");
    }
}
