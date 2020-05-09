<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Offer_controller extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("admin/offer_featured/offer_model", "offer_model");
    }

    public function index() {
        $this->data['view'] = "admin/offer_featured/index";
        $this->data['items'] = $this->offer_model->offerList();
        $this->displayview($this->data);
    }

    public function update_feature_status() {
        $input_data = $this->input->post();
        $this->offer_model->update_feature_status_modal($input_data);
        $input['message'] = (@$input_data['status'] == 0) ? "Feature Successfully unblocked" : "Feature Successfully blocked";
        echo json_encode($input);
        exit();
    }

}

?>
