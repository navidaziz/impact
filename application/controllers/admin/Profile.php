<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends Admin_Controller
{

    /**
     * constructor method
     */
    public function __construct()
    {

        parent::__construct();
        $this->load->model("admin/user_model");
        $this->lang->load("users", 'english');
        $this->lang->load("system", 'english');
        //$this->output->enable_profiler(TRUE);
    }




    public function update_profile()
    {
        $this->data['title'] = 'Update Profile';

        $user_id = (int) $this->session->userdata('userId');
        $this->data["user"] = $this->user_model->get($user_id);


        $validation_config = array(
            array(
                "field"  =>  "user_email",
                "label"  =>  "User Email",
                "rules"  =>  "required"
            ),


            array(
                "field"  =>  "user_password",
                "label"  =>  "User Password",
                "rules"  =>  "required"
            ),

            array(
                "field"  =>  "user_mobile_number",
                "label"  =>  "Mobile Number",
                "rules"  =>  "required"
            ),


        );


        //set and run the validation
        $this->form_validation->set_rules($validation_config);
        if ($this->form_validation->run() === TRUE) {


            $config = array(
                "upload_path" => "./assets/uploads/" . $this->router->fetch_class() . "/",
                "allowed_types" => "jpg|jpeg|bmp|png|gif",
                "max_size" => 10000,
                "max_width" => 0,
                "max_height" => 0,
                "remove_spaces" => true,
                "encrypt_name" => true
            );
            if (!$this->upload_file("user_image", $config)) {
                //var_dump($this->data["upload_error"]);
            } else {
                //var_dump($this->data["upload_data"]);
                $user_image = $this->data["upload_data"]["file_name"];
            }


            $inputs = array();



            $inputs["user_email"]  =  $this->input->post("user_email");

            $inputs["user_password"]  =  $this->input->post("user_password");

            $inputs["user_mobile_number"]  =  $this->input->post("user_mobile_number");



            if ($_FILES["user_image"]["size"] > 0) {
                $inputs["user_image"]  =  $this->router->fetch_class() . "/" . $user_image;
            }


            if ($this->user_model->save($inputs, $user_id)) {

                $this->session->set_flashdata("msg_success", $this->lang->line("update_msg_success"));
                redirect(ADMIN_DIR . "profile/update_profile");
            } else {

                $this->session->set_flashdata("msg_error", $this->lang->line("msg_error"));
                redirect(ADMIN_DIR . "profile/update_profile");
            }
        }

        $this->data["title"] = 'Update Account';
        $this->data["view"] = ADMIN_DIR . "profile/update_profile";
        $this->load->view(ADMIN_DIR . "layout", $this->data);
    }
}
