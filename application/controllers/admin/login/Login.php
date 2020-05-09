<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("admin/login/login_model", "login_model");
        $this->lang->load("admin/login");
    }

    public function index() {
        if ($this->session->has_userdata('loggedin') == true) {
            redirect(base_url() . "users", "refresh");
        }
        $data['error'] = ($this->session->flashdata("error")) ? $this->session->flashdata("error") : '';
        $data['success'] = ($this->session->flashdata("success")) ? $this->session->flashdata("success") : '';
        $this->load->view('admin/login/index', $data);
    }

    public function login_submited() {
        if ($this->login_validation()) {
            $this->load->helper('security');
            $input_data = $this->security->xss_clean($this->input->post());
            $r = $this->login_model->checkLoginCredential($input_data);

            if ($r) {
                if ((int) $r['is_blocked'] == 1) {
                    $this->session->set_flashdata("error", $this->lang->line("account_blocked"));
                    redirect("login");
                } else {
                    if ((int) $r['role_id'] == 1) {
                        $this->session->set_userdata([
                            "id" => $r['id'],
                            "name" => $r['name'],
                            "role_id" => $r['role_id'],
                            "email" => $r['email'],
                            "loggedin" => TRUE,
                        ]);
                        redirect("users");
                    } elseif ((int) $r['role_id'] == 2) {
                        $this->session->set_userdata([
                            "id" => $r['id'],
                            "name" => $r['name'],
                            "role_id" => $r['role_id'],
                            "email" => $r['email'],
                            "loggedin" => TRUE,
                        ]);
                        redirect("subadmin/dashboard");
                    }
                }
            } else {
                $this->session->set_flashdata("error", $this->lang->line("credential_invalid"));
                redirect("login");
            }
        }
        $this->load->view('admin/login/index');
    }

    private function login_validation($pass = true) {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        if ($pass) {
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
        }
        return $this->form_validation->run();
    }

    public function forgotPassword() {
        $data['error'] = ($this->session->flashdata("error")) ? $this->session->flashdata("error") : '';
        $data['success'] = ($this->session->flashdata("success")) ? $this->session->flashdata("success") : '';
        $this->load->view('admin/login/forgot_password', $data);
    }

    public function forgotPasswordSubmited() {
        if ($this->login_validation(false)) {
            $email = $this->input->post();
            $response = $this->login_model->checkLoginCredential($email);
            if ($response) {
                $r = $this->send_temp_link(trim($email['email']));
                if ($r) {
                    $this->session->set_flashdata("success", "Email Reset link has been sent to your email id");
                } else {
                    $this->session->set_flashdata("error", "Email not sent , please try again..");
                }
            } else {
                $this->session->set_flashdata("error", $this->lang->line("email_not_found"));
            }
        }
        redirect("forgot-password");
    }

    private function send_temp_link($email) {
        $em = base64_encode($email);
        $this->load->library("email_setting");
        $subject = "Forgot Password"; // language file is not working
        $message = "Please click this link " . base_url() . "updated-password/$em";

        $response = $this->email_setting->send_email($email, $message, $subject);
        if ($response) {
            $this->login_model->updateEmailInfo($email);
            return true;
        } else {
            return false;
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect("login");
    }

}
