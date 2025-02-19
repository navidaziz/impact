<?php
defined('BASEPATH') or exit('No direct script access allowed');
// Admin_Controller
// MY_Controller
class Errors extends MY_Controller
{

    public function __construct()
    {

        parent::__construct();
        $this->load->model('user_m');
        // $this->output->enable_profiler(TRUE);
    }
    public function index()
    {
        $this->data['title'] = 'Dashboard';
        $this->data['description'] = 'info about All Modules';
        //$this->data['view'] = 'errors/html/module_not_allowed';
        //$this->load->view('layout', $this->data);
        $this->load->view('errors/html/module_not_allowed', $this->data);
    }

    public function account_disable()
    {
        $this->data['title'] = 'Account Disabled';
        $this->data['description'] = 'Your account is disabled. Please contact the administrator.';
        $this->load->view('errors/html/account_disabled', $this->data);
    }
}
