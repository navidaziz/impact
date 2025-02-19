<?php if (!defined('BASEPATH')) exit('Direct access not allowed!');

class Admin_Controller extends MY_Controller
{

    public $controller_name = "";
    public $method_name = "";


    public function __construct()
    {

        parent::__construct();

        $this->load->helper("my_functions");
        $this->load->model("mr_m");
        $this->load->model("module_m");
        $this->data['controller_name'] = $this->controller_name = $this->router->fetch_class();
        $this->data['method_name'] = $this->method_name = $this->router->fetch_method();
        $this->data['menu_arr'] = $this->mr_m->roleMenu($this->session->userdata("role_id"));

        $this->load->model("system_global_setting_model");
        $system_global_setting_id = 1;
        $fields = $fields = array("*");
        $join_table = $join_table = array();
        $where = "system_global_setting_id = $system_global_setting_id";
        $this->data["system_global_settings"] = $this->system_global_setting_model->joinGet($fields, "system_global_settings", $join_table, $where, false, true);




        // var_dump($this->session);
        //exit();
        //login check
        $exception_uri = array(

            "admin/login",
            "cake",
            "login",
            "admin/login/validate_user",
            "admin/login/logout",
        );

        if (!in_array(uri_string(), $exception_uri)) {
            //check if the user is logged in or not
            if (!$this->session->userdata('userId') && empty($this->session->userdata('userId'))) {
                $is_ajax = '';
                if ($_SERVER['HTTP_X_REQUESTED_WITH']) {
                    $is_ajax = strtolower($_SERVER['HTTP_X_REQUESTED_WITH']);
                }
                if ($is_ajax) {
                    echo '<div class="alert alert-danger">';
                    echo 'Please log in again as your session has expired.';
                    echo '<span style="margin-left:20px;"></span><a href="' . site_url('login') . '"> Login again</a>';
                    echo '</div>';
                    exit();
                } else {
                    redirect("login");
                }
            }




            $query = "SELECT users.status as user_status FROM users WHERE user_id = " . $this->session->userdata('userId');
            $account = $this->db->query($query)->row();
            $query = "SELECT roles.status as role_status FROM roles WHERE role_id = " . $this->session->userdata('role_id');
            $role = $this->db->query($query)->row();
            if ($account->user_status != 1 or $role->role_status != 1) {
                $is_ajax = '';
                if ($_SERVER['HTTP_X_REQUESTED_WITH']) {
                    $is_ajax = strtolower($_SERVER['HTTP_X_REQUESTED_WITH']);
                }
                if ($is_ajax) {
                    echo '<div class="alert alert-danger">';
                    echo 'Your account is disabled. Please contact the administrator.';
                    echo '<span style="margin-left:20px;"></span><a href="' . site_url('login') . '"> Login again</a>';
                    echo '</div>';
                    exit();
                } else {
                    $this->session->set_flashdata('msg_error', 'Your account is disabled.');
                    redirect(site_url(ADMIN_DIR . "errors/account_disable"));
                }
            }

            //now we will check if the current module is assigned to the user or not
            //$this->data['current_action_id'] = $current_action_id = $this->module_m->actionIdFromName($this->controller_name, $this->method_name);
            $this->data['current_action_id'] = $current_action_id = $this->module_m->actionIdFromName($this->controller_name, NULL);
            $allowed_modules = $this->mr_m->rightsByRole($this->session->userdata("role_id"));

            //add role homepage to allowed modules
            $allowed_modules[] = $this->session->userdata("role_homepage_id");

            if (!in_array($current_action_id, $allowed_modules)) {
                $is_ajax = '';
                if ($_SERVER['HTTP_X_REQUESTED_WITH']) {
                    $is_ajax = strtolower($_SERVER['HTTP_X_REQUESTED_WITH']);
                }
                if ($is_ajax) {
                    echo '<div class="alert alert-danger">
                    <strong>Error!</strong> You are not allowed to access this module.
                    ' . $this->controller_name . ' - ' . $this->method_name . '
                </div>
                ';
                    exit();
                } else {
                    if (!$this->session->userdata('userId') && empty($this->session->userdata('userId'))) {
                        redirect("login");
                    } else {
                        $this->session->set_flashdata('msg_error', 'You are not allowed to access this module');
                        // redirect($_SERVER['HTTP_REFERER']);
                        // session_destroy();
                        //redirect($this->session->userdata("role_homepage_uri"));
                        $module = $this->controller_name . '-' . $this->method_name;
                        redirect(site_url(ADMIN_DIR . "errors/index?module=$module"));
                    }
                }
            }
        }
    }
}
