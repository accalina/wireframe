<?php

require APPPATH . 'libraries/REST_Controller.php';

class Api extends REST_Controller
{
    // Initialization ----------------------------------------------------------------------------------
    function __construct(){
        parent::__construct();
        $this->load->library('session');
        $this->db = $this->load->database('pos',true);
    }

    // API Banner ----------------------------------------------------------------------------------
    function index_get(){
        header('Content-Type: application/json');
        $banner = ["text" => "welcome to Wolf API"];
        echo json_encode($banner);
    }

    function allUser_get(){
        header('Content-Type: application/json');
        $result = $this->db->query("SELECT * FROM login")->result_array();
        echo json_encode(["data"=>$result]);
    }

    function renameUser_post(){
        $no     = $this->input->post('no');
        $name   = $this->input->post('name');

        $result = $this->db->query("UPDATE `login` SET `username`=? WHERE `no`=?",[$name,$no]);
        if ($result) {
            echo "rename successfully";
        }else{
            echo "rename failed";
        }
    }

    function deleteUser_post(){
        $username = $this->input->post('username');
        $result = $this->db->query('DELETE FROM `login` WHERE username=?',[$username]);
        if ($result) {
            echo "User ".$username." has been deleted!";
        }else{
            echo "User ".$username." failed to be deleted!";
        }
    }

    // Login Endpoint ----------------------------------------------------------------------------------
    function login_post(){    
        $username = $this->input->post('username');
        $password = hash('sha512',$this->input->post('password'));

        $result = $this->db->query('SELECT * FROM login WHERE (username = ?) and (password = ?)',[$username,$password])->result_array();
        if(count($result) != 0){
            foreach ($result as $key => $row) {
                $this->session->username    = $row['username'];
                $this->session->token       = $row['token'];
                $this->session->role        = $row['role'];
            }
            echo "Login Success";
        }else{
            echo "Login Failed";
        }
    }

    function login_get(){
        header('Content-Type: application/json');
        $banner = [
            "username"  => $this->session->username,
            "token"     => $this->session->token,
            "role"      => $this->session->role
        ];
        echo json_encode(["profile" => $banner]);
    }

    // Register Endpoint ----------------------------------------------------------------------------------
    function register_post(){
        $username = $this->post('username');
        $password = $this->post('password');
        $password = hash('sha512',$password);

        $db = $this->load->database('pos',true);
        $result = $db->query('INSERT INTO `login`(`username`, `password`, `token`, `role`,`edit`) VALUES (?,?,?,?,?)',[$username,$password,'','user','false']);

        if ($result) {
            $message = "USER: $username Created Successfully";
            $this->set_response($message, REST_Controller::HTTP_CREATED);
        }else{
            $message = "USER: $username Failed to be created!";
            $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    // Logout Endpoint ----------------------------------------------------------------------------------
    function logout_get(){
        echo  "User " .$this->session->username. " Has Logged Out";
        $this->session->sess_destroy();
    }

    // Token Endpoint ----------------------------------------------------------------------------------
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

    // User Role ----------------------------------------------------------------------------------
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
