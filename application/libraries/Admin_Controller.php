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

        $exception_uri = [
            "admin/login",
            "cake",
            "login",
            "admin/login/validate_user",
            "admin/login/logout",
        ];

        if (!in_array(uri_string(), $exception_uri)) {
            // Check if the user is logged in
            if (empty($this->session->userdata('userId'))) {
                $this->handleAjaxOrRedirect("Please log in again as your session has expired.", "login");
            }

            // Check if user and role statuses are active
            $userId = $this->session->userdata('userId');
            $roleId = $this->session->userdata('role_id');

            $userRow = $this->db->select('status')->get_where(
                'users',
                ['user_id' => $userId]
            )->row();
            $userStatus = isset($userRow->status) ? $userRow->status : null;

            $roleRow = $this->db->select('status')->get_where(
                'roles',
                ['role_id' => $roleId]
            )->row();
            $roleStatus = isset($roleRow->status) ? $roleRow->status : null;



            if ($userStatus != 1 || $roleStatus != 1) {
                $this->handleAjaxOrRedirect("Your account is disabled. Please contact the administrator.", site_url(ADMIN_DIR . "errors/account_disable"));
            }

            // Check if the current module is assigned to the user
            $current_action_id = $this->module_m->actionIdFromName($this->controller_name, null);
            $allowed_modules = $this->mr_m->rightsByRole($roleId);
            $allowed_modules[] = $this->session->userdata("role_homepage_id");

            if (!in_array($current_action_id, $allowed_modules)) {
                $module = $this->controller_name . '-' . $this->method_name;
                $this->handleAjaxOrRedirect(
                    "You are not allowed to access this module.",
                    site_url(ADMIN_DIR . "errors/index?module=$module")
                );
            }
        }
    }

    function handleAjaxOrRedirect($message, $redirect_url)
    {
        $is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        if ($is_ajax) {
            echo '<div class="alert alert-danger">';
            echo $message;
            echo '<span style="margin-left:20px;"></span><a href="' . site_url('login') . '"> Login again</a>';
            echo '</div>';
            exit();
        } else {
            $this->session->set_flashdata('msg_error', $message);
            redirect($redirect_url);
        }
    }
}
