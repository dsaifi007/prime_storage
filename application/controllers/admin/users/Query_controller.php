<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Query_controller extends MY_Controller {

    protected $data = [];

    public function __construct() {
        parent::__construct();
        $this->user_not_loggedin();
        $this->load->model("admin/users/user_model", "user_model");
    }
    public function userQueries() {
        $this->data['view'] = "admin/users/user_queries";
        $this->data['items'] = $this->user_model->user_queries();
        $this->displayview($this->data);
    }

}

?>