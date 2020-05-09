<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

class Sitelink_api extends REST_Controller {

    private $user_data = [];
    private $response_send = [];

    public function __construct() {
        try {
            parent::__construct();

            $this->headers = apache_request_headers();
            $this->load->library('sitelink');
            $this->load->model("api/sitelink_model", "sitelink_model");
            //content_type($this->headers);
            change_languge($this->headers);
        } catch (Exception $exc) {
            $this->response_send = ["message" => $exc->getMessage(), "status" => $this->config->item("status_false")];
            $this->response($this->response_send);
        }
    }

    function index_post() {
        try {
            check_acces_token($this->headers);
            $data = $this->post();
            $data['result_key'] = (isset($data['result_key']) && trim($data['result_key'])) ? $data['result_key'] : 'payload';
            if (check_form_array_keys_existance($data, ["latitude", "longitude", "index", "result_key"]) && check_user_input_values($data)) {

                $result = $this->sitelink_model->getStorageLocationBasedOnLatLong($data);

                if ($result) {
                    //echo $this->lang->line('success');die;
                    $this->response_send = display_api_res($result, $this->config->item("status_true"), $this->lang->line('success'), $this->headers, $data['result_key']);
                } else {
                    $this->response_send = display_api_res(array(), $this->config->item("status_false"), $this->lang->line('no_data_found'), $this->headers, $data['result_key']);
                }
            } else {
                $this->response_send = display_api_res(array(), $this->config->item("status_false"), $this->lang->line('all_field_required'), $this->headers, $data['result_key']);
            }
        } catch (Exception $exc) {
            $this->response_send = ["message" => $exc->getMessage(), "status" => $this->config->item("status_false")];
        }
        $this->response($this->response_send);
    }

    public function insertStorage_get() {
        try {
            $data = $this->sitelink->get_storage_locations();
            if (count($data) > 0) {
                $input_data = array();
                foreach ($data as $key => $value) {
                    $result = $this->sitelink_model->checkStorage($value['SiteID']);
                    if ($result != true) {
                        $input_data[] = [
                            "site_id" => $value['SiteID'],
                            "location_code" => $value['sLocationCode'],
                            "email" => (!is_array($value['sEmailAddress'])) ? $value['sEmailAddress'] : json_encode($value['sEmailAddress']),
                            "site_name" => $value['sSiteName'],
                            "site_address1" => $value['sSiteAddr1'],
                            //"site_address2" => $value['sSiteAddr2'],
                            "city" => $value['sSiteCity'],
                            "postal_code" => $value['sSitePostalCode'],
                            "country" => $value['sSiteCountry'],
                            "latitude" => $value['dcLatitude'],
                            "longitude" => $value['dcLongitude'],
                            "created_at" => $this->config->item("date_time")
                        ];
                    }
                }
                //dd($input_data);
                $this->sitelink_model->insertStorage($input_data);

                //$this->response_send = display_api_res($data, $this->config->item("status_true"), null);
                //dd($this->response_send);
            } else {
                $this->response_send = display_api_res(array(), $this->config->item("status_false"), $this->lang->line("no_data_found"));
            }
        } catch (Exception $exc) {
            $this->response_send = display_api_res(array(), $this->config->item("status_false"), $exc->getMessage());
        }
        $this->response($this->response_send);
    }

    public function get_units_by_location_code_post() {
        try {
            check_acces_token($this->headers);
            $location_code = $this->input->post();
            $location_code['result_key'] = (isset($location_code['result_key']) && trim($location_code['result_key'])) ? $location_code['result_key'] : 'payload';

            if ($location_code) {
                $result = $this->sitelink->get_units_by_location_code($location_code['location_code']);
                $this->response_send = display_api_res($result, $this->config->item("status_true"), $this->lang->line("success"), $this->headers, $location_code['result_key']);
            } else {
                $this->response_send = display_api_res(array(), $this->config->item("status_false"), $this->lang->line('all_field_required'), $this->headers, $data['result_key']);
            }
        } catch (Exception $exc) {
            $this->response_send = display_api_res(array(), $this->config->item("status_false"), $exc->getMessage());
        }
        $this->response($this->response_send);
    }

    function storageSerachByCity_post() {
        try {
            $city = $this->input->post();
            $city['result_key'] = (isset($city['result_key']) && trim($city['result_key'])) ? $city['result_key'] : "payload";
            if (check_form_array_keys_existance($city, ["city", "result_key"]) && check_user_input_values($city)) {

                $result = $this->sitelink_model->get_search_by_city($city['city']);
                if ($result) {
                    $this->response_send = display_api_res($result, $this->config->item("status_true"), $this->lang->line("success"), $this->headers, $city['result_key']);
                } else {
                    $this->response_send = display_api_res(array(), $this->config->item("status_false"), $this->lang->line('no_data_found'), $this->headers, $city['result_key']);
                }
            } else {
                $this->response_send = display_api_res(array(), $this->config->item("status_false"), $this->lang->line('all_field_required'), $this->headers, $data['result_key']);
            }
        } catch (Exception $exc) {
            $this->response_send = display_api_res(array(), $this->config->item("status_false"), $exc->getMessage());
        }
        $this->response($this->response_send);
    }

    // Add tenant Detail 
    public function addTenant_post() {
        try {
            check_acces_token(@$this->headers);
            $input_data = $this->input->post();
            $input_data['result_key'] = ((isset($input_data['result_key'])) && trim($input_data['result_key'])) ? $input_data['result_key'] : "payload";
            //dd($input_data);
            if (check_form_array_keys_existance($input_data, ["location_code", "location_name", "location_address", "unit_id", "date"]) && check_user_input_values($input_data)) {

                if ($this->addTenantValidation() == true) {
                    $formatted_date = date('Y-m-d', strtotime($input_data['date'])) . 'T' . date('H:i:s', strtotime($input_data['date']));
                    $move_in_cost_retrieve_return = $this->sitelink->move_in_cost_retrieve($input_data['location_code'], $input_data['unit_id'], $formatted_date);

                    if (count($move_in_cost_retrieve_return) > 0 && $move_in_cost_retrieve_return) {
                        $data = array();
                        $data['price'] = 0;
                        foreach ($move_in_cost_retrieve_return as $move_in_fee) {
                            $data['price'] = $data['price'] + $move_in_fee['dcTotal'];
                            $data['start_date_array'][] = strtotime($move_in_fee['StartDate']);
                            $data['end_date_array'][] = strtotime($move_in_fee['EndDate']);
                        }
                        $start_date1 = date('Y-m-d', min($data['start_date_array'])) . ' ' . date('H:i:s', min($data['start_date_array']));
                        $end_date1 = date('Y-m-d', min($data['end_date_array'])) . ' ' . date('H:i:s', min($data['end_date_array']));

                        $start_date = date('Y-m-d', min($data['start_date_array'])) . 'T' . date('H:i:s', min($data['start_date_array']));
                        $end_date = date('Y-m-d', max($data['end_date_array'])) . 'T' . date('H:i:s', max($data['end_date_array']));

                        $data['site_information'] = $this->sitelink->get_site_information($input_data['location_code']);

                        $data['unit_information'] = $this->sitelink->get_units_information_by_unit_id($input_data['location_code'], $input_data['unit_id']);

                        $tenant_data = array();
                        $tenant_data['lo_code'] = $input_data['location_code'];
                        $tenant_data['first_name'] = $input_data['full_name'];
                        $tenant_data['last_name'] = $input_data['full_name'];
                        $tenant_data['company'] = '';
                        $tenant_data['address_1'] = $input_data['address'];
                        $tenant_data['address_2'] = '';
                        $tenant_data['city'] = $input_data['city'];
                        $tenant_data['country'] = $input_data['country'];
                        $tenant_data['region'] = ''; //$tenant_data['state'];
                        $tenant_data['postal'] = $input_data['zip'];
                        $tenant_data['phone'] = $input_data['phone'];
                        $tenant_data['fax'] = '';
                        $tenant_data['email'] = $input_data['email'];
                        $tenant_data['password'] = rand();
                        $tenant_return = $this->sitelink->add_tenant($tenant_data);
                        if ($tenant_return['rt']['Ret_Code'] > 0) {
                            $response_data = array();
                            $response_data['lo_code'] = $input_data['location_code'];
                            $response_data['site_id'] = $data['site_information']['SiteID'];
                            $response_data['tenant_id'] = $tenant_return['data']['TenantID'];
                            $response_data['access_code'] = $tenant_return['data']['sAccessCode'];
                            $response_data['unit_id'] = $input_data['unit_id'];
                            $response_data['sitelink_formated_start_date'] = $start_date;
                            $response_data['sitelink_formated_end_date'] = $end_date;
                            $response_data['start_date'] = substr($start_date, 0, 10); ///$start_date1;
                            $response_data['end_date'] = substr($end_date, 0, 10); //$end_date1;
                            $response_data['address'] = $input_data["address"];
                            $response_data['zip'] = $input_data["zip"];
                            $response_data['size'] = $data['unit_information']['dcWidth'] . '*' . $data['unit_information']['dcLength'];
                            $response_data['user_id'] = $input_data["user_id"];
                            $response_data['price'] = $data['price'];
                            $response_data['created_date'] = $this->config->item("date_time");
                            $response_data['storage_id'] = $input_data["storage_id"];
                            $response_data['zip'] = $input_data['zip'];
                            $id = $this->sitelink_model->addTanentInsert($response_data);
                            $response_data['tenantid'] = $id;
                            $this->response_send = display_api_res(array_slice($response_data, 9), $this->config->item("status_true"), $this->lang->line("info_added"), $this->headers, $input_data['result_key']);
                        } else {
                            $this->response_send = display_api_res((object) array(), $this->config->item("status_false"), $tenant_return['rt']['Ret_Msg'], $this->headers, $input_data['result_key']);
                        }
                    } else {
                        $this->response_send = display_api_res((object) array(), $this->config->item("status_false"), $this->lang->line("cost_no_retrive"), $this->headers, $input_data['result_key']);
                    }
                } else {
                    $error = $this->form_validation->error_array();
                    $error_key = array_keys($error);
                    $this->response_send = display_api_res(array(), $this->config->item("status_false"), $error[$error_key[0]], $this->headers, $input_data['result_key']);
                }
            } else {
                $this->response_send = display_api_res(array(), $this->config->item("status_false"), $this->lang->line('all_field_required'), $this->headers, $input_data['result_key']);
            }
        } catch (Exception $exc) {
            $this->response_send = display_api_res(array(), $this->config->item("status_false"), $exc->getMessage());
        }
        $this->response($this->response_send);
    }

    private function addTenantValidation() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('full_name', 'First Name', 'trim|required');
        //$this->form_validation->set_rules('company', 'Company', 'trim');
        $this->form_validation->set_rules('address', 'Address', 'trim|required');
        $this->form_validation->set_rules('unit_id', 'Unit Id', 'trim|numeric');
        $this->form_validation->set_rules('city', 'City', 'trim|required');
        $this->form_validation->set_rules('state', 'State', 'trim|required');
        $this->form_validation->set_rules('zip', 'Zip', 'trim|required');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        //$this->form_validation->set_rules('password', 'Password', 'trim|required|alpha_numeric|min_length[6]|max_length[10]');
        return $this->form_validation->run();
    }

    # get all cities

    public function GetAllCity_get() {
        try {
            check_acces_token(@$this->headers);
            $input_data = $this->get();
            $input_data['result_key'] = ((isset($input_data['result_key'])) && trim($input_data['result_key'])) ? $input_data['result_key'] : "payload";
            $data = $this->sitelink->get_storage_locations();
            if (count($data) > 0) {
                $cities = array();
                foreach ($data as $key => $value) {
                    $cities[] = ["city" => $value['sSiteCity']];
                }
                $this->response_send = display_api_res($cities, $this->config->item("status_true"), $this->lang->line('success'), $this->headers, $input_data['result_key']);
            } else {
                $this->response_send = display_api_res(array(), $this->config->item("status_false"), $this->lang->line('no_data_found'), $this->headers, $input_data['result_key']);
            }
        } catch (Exception $exc) {
            $this->response_send = display_api_res(array(), $this->config->item("status_false"), $exc->getMessage());
        }
        $this->response($this->response_send);
    }

    public function payPaymentToSiteLink_post() {
        try {
            //check_acces_token(@$this->headers);
            $input_data = $this->input->post();

            $input_data['result_key'] = ((isset($input_data['result_key'])) && trim($input_data['result_key'])) ? $input_data['result_key'] : "payload";
            if (check_form_array_keys_existance($input_data, ["tenantid", "card_name", "card_number", "card_cvv", "card_month", "card_year", "card_type"]) && check_user_input_values($input_data)) {

                if ($this->paymentValidation()) {
                    $response = $this->sitelink_model->getTanentData($input_data['tenantid']);

                    if (!empty($response)) {
                        $this->load->library('data');
                        $data['card_months'] = array_merge(array('' => 'Select Month'), $this->data->get_months());
                        $data['card_years'] = array_merge(array('' => 'Select Year'), $this->data->get_years());
                        //echo date('Y-m-t', strtotime('1 ' . $data['card_months'][$input_data['card_month']] . ' ' . $data['card_years'][$input_data['card_year']]));die;
                        $expdata1 = $input_data['card_year'] . '-' . $input_data['card_month'] . '-' . date("d");
                        $expdata = date_format(date_create($expdata1), "Y-m-d");
                        $move_in_data = array();
                        $move_in_data['lo_code'] = $response['lo_code'];
                        $move_in_data['tenant_id'] = $response['tenant_id'];
                        $move_in_data['acc_id'] = $response['access_code'];
                        $move_in_data['unit_id'] = $response['unit_id'];
                        $move_in_data['start_date'] = date_format(date_create($response['sitelink_formated_start_date']), "Y-m-d");
                        $move_in_data['end_date'] = date_format(date_create($response['sitelink_formated_end_date']), "Y-m-d");
                        $move_in_data['amount'] = $response['price'];
                        $move_in_data['card_name'] = $input_data['card_name'];
                        $move_in_data['card_address'] = (isset($input_data['card_address'])) ? $input_data['card_address'] : '';
                        $move_in_data['card_postal'] = (isset($input_data['card_postal'])) ? $input_data['card_postal'] : '';
                        $move_in_data['card_number'] = $input_data['card_number'];
                        $move_in_data['card_cvv'] = $input_data['card_cvv'];
                        $move_in_data['expiration_date'] = $expdata; //date('Y-m-t', strtotime('1 ' . $data['card_months'][$input_data['card_month']] . ' ' . $data['card_years'][$input_data['card_year']]));
                        //$move_in_data['expiration_date'] = date('Y-m-t', strtotime('1 ' . $data['card_months'][$input_data['card_month']] . ' ' . $data['card_years'][$input_data['card_year']]));
                        $move_in_data['card_type'] = $input_data['card_type'];

                        $move_in_return = $this->sitelink->move_in($move_in_data);
                        if ($move_in_return['rt']['Ret_Code'] > 0) {
                            // update status of payment by the user to sitelink
                            // send notification 
                            $this->load->library("pushnotification");
                            $msg = ['data' => [
                                    'title' => "Booking",
                                    'body' => "Your booking has been confirmed",
                                    'sound' => 'default'
                            ]];
                            $this->pushnotification->sendPushNotificationToFCMSever(array($response['device_token']), $msg, $msg['data']);

                            $this->sitelink_model->update_payment_status($input_data['tenantid']);
                            $this->response_send = display_api_res((object) $move_in_return['rt'], $this->config->item("status_true"), $this->lang->line('payment_success'), $this->headers, $input_data['result_key']);
                        } else {
                            $this->response_send = display_api_res((object) array(), $this->config->item("status_false"), $move_in_return['rt']['Ret_Msg'], $this->headers, $input_data['result_key']);
                        }
                    } else {
                        $this->response_send = display_api_res((object) array(), $this->config->item("status_false"), $this->lang->line('no_data_found'), $this->headers, $input_data['result_key']);
                    }
                } else {
                    $error = $this->form_validation->error_array();
                    $error_key = array_keys($error);
                    $this->response_send = display_api_res((object) array(), $this->config->item("status_false"), $error[$error_key[0]], $this->headers, $input_data['result_key']);
                }
            } else {
                $this->response_send = display_api_res((object) array(), $this->config->item("status_false"), $this->lang->line('all_field_required'), $this->headers, $input_data['result_key']);
            }
        } catch (Exception $exc) {
            $this->response_send = display_api_res((object) array(), $this->config->item("status_false"), $exc->getMessage());
        }
        $this->response($this->response_send);
    }

    private function paymentValidation() {
        $this->load->helper('security');
        $this->load->library('form_validation');

        /* $this->form_validation->set_rules('location_code', 'Location Code', 'xss_clean|trim|required');
          $this->form_validation->set_rules('tenant_id', 'Tenant Id', 'xss_clean|trim|required|numeric');
          $this->form_validation->set_rules('access_code', 'Access Code', 'xss_clean|trim|required|numeric');
          $this->form_validation->set_rules('unit_id', 'Unit Id', 'xss_clean|trim|required|numeric');
          $this->form_validation->set_rules('start_date', 'Start Date', 'xss_clean|trim|required');
          $this->form_validation->set_rules('end_date', 'End Date', 'xss_clean|trim|required');
          $this->form_validation->set_rules('amount', 'Amount', 'xss_clean|trim|required'); */
        $this->form_validation->set_rules('tenantid', 'Tenant Id', 'xss_clean|trim|required|numeric');
        $this->form_validation->set_rules('card_name', 'Name On Card', 'xss_clean|trim|required');
        $this->form_validation->set_rules('card_address', 'Street Address', 'xss_clean|trim|required');
        $this->form_validation->set_rules('card_postal', 'Postal/Zip', 'xss_clean|trim|required');
        $this->form_validation->set_rules('card_type', 'Card Type', 'xss_clean|trim|required');
        $this->form_validation->set_rules('card_number', 'Card Number', 'xss_clean|trim|required|numeric');
        $this->form_validation->set_rules('card_cvv', 'CVV Number', 'xss_clean|trim|required|numeric');
        $this->form_validation->set_rules('card_month', 'Expire Month', 'xss_clean|trim|required');
        $this->form_validation->set_rules('card_year', 'Expire Year', 'xss_clean|trim|required');
        //$this->form_validation->set_rules('password', 'Password', 'trim|required|alpha_numeric|min_length[6]|max_length[10]');
        return $this->form_validation->run();
    }

    public function storeStorageRating_post() {
        try {
            check_acces_token(@$this->headers);
            $input_data = $this->input->post();
            $input_data['result_key'] = ((isset($input_data['result_key'])) && trim($input_data['result_key'])) ? $input_data['result_key'] : "payload";
            $input_data['review'] = (isset($input_data['review']) && trim($input_data['review'])) ? $input_data['review'] : " ";
            if (check_form_array_keys_existance($input_data, ["storage_id", "user_id", "reservation_id", "rating"]) && check_user_input_values($input_data)) {
                if ($this->ratingValidation()) {
                    $this->sitelink_model->add_storage_rating($input_data);
                    $this->response_send = display_api_res((object) array(), $this->config->item("status_true"), $this->lang->line('success'), $this->headers, $input_data['result_key']);
                } else {
                    $error = $this->form_validation->error_array();
                    $error_key = array_keys($error);
                    $this->response_send = display_api_res((object) array(), $this->config->item("status_false"), $error[$error_key[0]], $this->headers, $input_data['result_key']);
                }
            } else {
                $this->response_send = display_api_res((object) array(), $this->config->item("status_false"), $this->lang->line('all_field_required'), $this->headers, $input_data['result_key']);
            }
        } catch (Exception $exc) {
            $this->response_send = display_api_res((object) array(), $this->config->item("status_false"), $exc->getMessage());
        }
        $this->response($this->response_send);
    }

    private function ratingValidation() {
        $this->load->helper('security');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('storage_id', 'Storage Id', 'xss_clean|trim|required|numeric');
        $this->form_validation->set_rules('rating', 'Rating', 'xss_clean|trim|required|numeric');
        return $this->form_validation->run();
    }

    public function reservationHistoryList_post() {
        try {
            check_acces_token(@$this->headers);
            $input_data = $this->input->post();
            $input_data['result_key'] = ((isset($input_data['result_key'])) && trim($input_data['result_key'])) ? $input_data['result_key'] : "payload";
            if ((int) $input_data['user_id']) {
                $result = $this->sitelink_model->get_user_reservation_history($input_data['user_id']);
                if ($result) {
                    //dd($result);
                    foreach ($result as $k => $value) {
                        if (($value['end_date'] > date("Y-m-d")) && $value['status'] == "payment_done") {
                            $result[$k]['status'] = $this->lang->line("on_going");
                        } elseif (($value['end_date'] < date("Y-m-d")) && $value['status'] == "payment_done") {
                            $result[$k]['status'] = $this->lang->line("booking_completed");
                        } elseif ($value['status'] == "payment_not_done") {
                            $result[$k]['status'] = $this->lang->line("payment_not_done");
                        } else {
                            $result[$k]['status'] = $this->lang->line("payment_tentative");
                        }
                    }
                    $this->response_send = display_api_res($result, $this->config->item("status_true"), $this->lang->line('success'), $this->headers, $input_data['result_key']);
                } else {
                    $this->response_send = display_api_res(array(), $this->config->item("status_false"), $this->lang->line('no_data_found'), $this->headers, $input_data['result_key']);
                }
            } else {
                $this->response_send = display_api_res(array(), $this->config->item("status_false"), $this->lang->line('user_id_not_found'), $this->headers, $input_data['result_key']);
            }
        } catch (Exception $exc) {
            $this->response_send = display_api_res(array(), $this->config->item("status_false"), $exc->getMessage());
        }
        $this->response($this->response_send);
    }

    public function getReservationDetail_post() {
        try {
            check_acces_token(@$this->headers);
            $input_data = $this->input->post();
            $input_data['result_key'] = ((isset($input_data['result_key'])) && trim($input_data['result_key'])) ? $input_data['result_key'] : "payload";
            if ((int) $input_data['id']) {
                $result = $this->sitelink_model->get_reservation_detail($input_data['id']);
                if ($result) {
                    if (($result['end_date'] > date("Y-m-d")) && $result['status'] == "payment_done") {
                        $result['status'] = $this->lang->line("on_going");
                    } elseif (($result['end_date'] < date("Y-m-d")) && $result['status'] == "payment_done") {
                        $result['status'] = $this->lang->line("booking_completed");
                    } elseif ($result['status'] == "payment_not_done") {
                        $result['status'] = $this->lang->line("payment_not_done");
                    } else {
                        $result['status'] = $this->lang->line("payment_tentative");
                    }
                    $this->response_send = display_api_res($result, $this->config->item("status_true"), $this->lang->line('success'), $this->headers, $input_data['result_key']);
                } else {
                    $this->response_send = display_api_res((object) array(), $this->config->item("status_false"), $this->lang->line('no_data_found'), $this->headers, $input_data['result_key']);
                }
            } else {
                $this->response_send = display_api_res((object) array(), $this->config->item("status_false"), $this->lang->line('user_id_not_found'), $this->headers, $input_data['result_key']);
            }
        } catch (Exception $exc) {
            $this->response_send = display_api_res((object) array(), $this->config->item("status_false"), $exc->getMessage());
        }
        $this->response($this->response_send);
    }

// For find_tenantList
    public function cancelReservation_post() {
        try {
            // check_acces_token(@$this->headers);
            $tanent_table_id = $this->post();
            $tanent_table_id['result_key'] = ((isset($tanent_table_id['result_key'])) && trim($tanent_table_id['result_key'])) ? $tanent_table_id['result_key'] : "payload";
            $data = $this->sitelink_model->getTanentData($tanent_table_id['tenantid']);

            if ($data) {
                $this->load->library('sitelink');
                if (isset($data)) {
                    $cancel_data = array();
                    //$cancel_data['waiting_id'] = $data['WaitingID'];
                    $cancel_data['tanent_id'] = $data['tenant_id'];
                    $cancel_data['lo_code'] = $data['lo_code'];
                    $cancel_data['unit_id'] = $data['unit_id'];
                    $cancel_data['expiry_date'] = $data['sitelink_formated_end_date'];
                    $cancel_data['price'] = $data['price'];
                    $response = $this->sitelink->get_waiting_id_by_tanent_id($data['lo_code'], $data['tenant_id']);
                    if (isset($response['WaitingID']) && @$response['WaitingID']) {
                        $cancel_data['waiting_id'] = $response['WaitingID'];
                        $cancel_return = $this->sitelink->CancelReservation($cancel_data);
                        if ($cancel_return['rt']['Ret_Code'] > 0) {
                            $this->response_send = display_api_res((object) array(), $this->config->item("status_true"), $this->lang->line('success'), $this->headers, $tanent_table_id['result_key']);
                        } else {
                            $this->response_send = display_api_res($cancel_return['rt']['Ret_Msg'], $this->config->item("status_false"), $this->lang->line('error'), $this->headers, $tanent_table_id['result_key']);
                        }
                    } else {
                        $this->response_send = display_api_res((object) array(), $this->config->item("status_false"), $this->lang->line('cancelled_msg'), $this->headers, $tanent_table_id['result_key']);
                    }
                }
            } else {
                $this->response_send = display_api_res((object) array(), $this->config->item("status_false"), $this->lang->line('no_data_found'), $this->headers, $tanent_table_id['result_key']);
            }
        } catch (Exception $exc) {
            $this->response_send = display_api_res((object) array(), $this->config->item("status_false"), $exc->getMessage());
        }
        $this->response($this->response_send);
    }

    // for find_tenantList
    public function find_tenantList_by_location_code() {

        $this->load->library('sitelink');
        $data['sites'] = $this->sitelink->find_tenantList_with_location_code();
        dd($data['sites']);
    }

    public function notification_test_post() {
        $data = $this->post();
        $this->load->library("pushnotification");
        $msg = ['data' => [
                'title' => "Booking",
                'body' => "Your booking has been confirmed",
                'sound' => 'default'
        ]];
        $response = $this->pushnotification->sendPushNotificationToFCMSever([$data['device_token']], $msg, $msg['data']);
        echo $response;
        die;
    }

}

?>