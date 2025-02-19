<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login extends Admin_Controller
{
    public function index()
    {
        redirect('admin/login');
    }
}
