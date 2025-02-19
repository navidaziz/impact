<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Home extends Admin_Controller
{

    /**
     * constructor method
     */
    public function __construct()
    {

        parent::__construct();
        $this->lang->load("system", 'english');
        //$this->output->enable_profiler(TRUE);
    }
    //---------------------------------------------------------------


    /**
     * Default action to be called
     */
    public function index()
    {

        $this->data["title"] = 'Home';
        $this->data["description"] = 'Application Dashboard';
        $this->data["view"] = ADMIN_DIR . "home/home_index";
        $this->load->view(ADMIN_DIR . "layout", $this->data);
    }
}
