<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Content_controller extends MY_Controller {

    protected $data = [];

    public function __construct() {
        parent::__construct();
        $this->user_not_loggedin();
        $this->load->model("admin/users/content_model", "content_model");
    }

    public function aboutUs() {
        $this->data['error'] = ($this->session->flashdata("error")) ? $this->session->flashdata("error") : '';
        $this->data['success'] = ($this->session->flashdata("success")) ? $this->session->flashdata("success") : '';
        $this->data['view'] = "admin/users/about_us";
        $this->data['active_class1'] = "active-item";
        $this->data['items'] = $this->content_model->getContent_model();
        $data = $this->input->post();
        if (isset($data['save'])) {
            $this->content_model->getContent_model($data);
            $this->session->set_flashdata("success", "About us Successfully Updated");
            redirect("about");
        }
        $this->displayview($this->data);
    }

    public function termsCondtn() {
        $this->data['error'] = ($this->session->flashdata("error")) ? $this->session->flashdata("error") : '';
        $this->data['success'] = ($this->session->flashdata("success")) ? $this->session->flashdata("success") : '';
        $this->data['view'] = "admin/users/terms_condition";
        $this->data['active_class2'] = "active-item";
        $this->data['items'] = $this->content_model->getterms_model();
        $data = $this->input->post();
        if (isset($data['save'])) {
            $this->content_model->getterms_model($data);
            $this->session->set_flashdata("success", "Terms and conditions Successfully Updated");
            redirect("term-condition-list");
        }
        $this->displayview($this->data);
    }

    public function contactUs() {
        $this->data['error'] = ($this->session->flashdata("error")) ? $this->session->flashdata("error") : '';
        $this->data['success'] = ($this->session->flashdata("success")) ? $this->session->flashdata("success") : '';
        $this->data['view'] = "admin/users/contact_us";
        $this->data['active_class3'] = "active-item";
        $this->data['items'] = $this->content_model->getcontact_model();
        $data = $this->input->post();
        
        if (isset($data['save'])) {
            
            $this->content_model->getcontact_model($data);
            $this->session->set_flashdata("success", "Contact Us Successfully Updated");
            redirect("contact-us");
        }
        $this->displayview($this->data);
    }

}

?>