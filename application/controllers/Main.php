<?php



class Main extends CI_Controller
{
    // Initialization
    function __construct(){
        parent::__construct();
        $this->load->library('session');
        $this->db = $this->load->database('pos',true);
    }
    // ---------------------------------------------------------

    // Templating
    function index(){
        redirect('login');
    }

    function login(){
        $this->load->view('wireframe/login');
    }

    function register(){
        $this->load->view('wireframe/register');
    }

    function home(){
        $this->login_check();
        $this->load->view('wireframe/admin/dashboard');
    }


    // ---------------------------------------------------------
    
    // Methods
    function login_check(){
       if (!isset($this->session->username)) {
            redirect('login');
        }
    }
}
