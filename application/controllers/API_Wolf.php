<?php

require APPPATH . 'libraries/REST_Controller.php';

class API_Wolf extends REST_Controller
{
    function __construct(){
        parent::__construct();
    }

    function index_get(){
        header('Content-Type: application/json');
        $banner = ["text" => "welcome to Wolf API"];
        echo json_encode($banner);
    }
    
    function login_post(){
        $username = $this->post('username');
        $password = $this->post('password');
        echo $username . " - " . $password;
        $this->set_response($message, REST_Controller::HTTP_CREATED);
    }

    function register_post(){
        $username = $this->post('username');
        $password = $this->post('password');
        $password = hash('sha512',$password);

        $db = $this->load->database('pos',true);
        $result = $db->query('INSERT INTO `login`(`username`, `password`, `token`, `role`) VALUES (?,?,?,?)',[$username,$password,'','user']);

        if ($result) {
            $message = "USER: $username Created Successfully";
            $this->set_response($message, REST_Controller::HTTP_CREATED);
        }else{
            $message = "USER: $username Failed to be created!";
            $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
        }
    }


    function genToken_post(){
        $username = $this->post('username');
        $token = explode(".",microtime(true))[0];

        $db = $this->load->database('pos',true);
        $result = $db->query('UPDATE `login` SET `token`=? WHERE `username`=?',[$token,$username]);

        if ($result) {
            $message = "Token [ $token ] has been generated for user: $username ";
            $this->set_response($message, REST_Controller::HTTP_CREATED);
        }else{
            $message = "Token generation has failed on user: $username";
            $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    function revokeToken_post(){
        $username = $this->post('username');

        $db = $this->load->database('pos',true);
        $result = $db->query('UPDATE `login` SET `token`="" WHERE `username`=?',[$username]);

        if ($result) {
            $message = "Token has been revoked for user: $username ";
            $this->set_response($message, REST_Controller::HTTP_CREATED);
        }else{
            $message = "Token revoked has failed on user: $username";
            $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    function userEscalate_post(){
        $username = $this->post('username');

        $db = $this->load->database('pos',true);
        $result = $db->query('UPDATE `login` SET `role`="admin" WHERE `username`=?',[$username]);

        if ($result) {
            $message = "User: $username has granted administrative previlage";
            $this->set_response($message, REST_Controller::HTTP_CREATED);
        }else{
            $message = "Granted administrative previlage failed on user: $username";
            $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    function userDescalate_post(){
        $username = $this->post('username');

        $db = $this->load->database('pos',true);
        $result = $db->query('UPDATE `login` SET `role`="user" WHERE `username`=?',[$username]);

        if ($result) {
            $message = "User: $username has revoked administrative previlage";
            $this->set_response($message, REST_Controller::HTTP_CREATED);
        }else{
            $message = "Revoked administrative previlage failed on user: $username";
            $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}
