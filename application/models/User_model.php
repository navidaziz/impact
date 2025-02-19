<?php if (!defined('BASEPATH')) exit('Direct access not allowed!');

class User_model extends MY_Model
{

    public function __construct()
    {

        parent::__construct();
        $this->table = "users";
        $this->pk = "user_id";
        $this->status = "status";
        $this->order = "order";
    }

    public function validate_form_data($record_id = NULL)
    {
        $validation_config = array(

            array(
                "field"  =>  "role_id",
                "label"  =>  "Role",
                "rules"  =>  "required"
            ),


            array(
                "field"  =>  "user_title",
                "label"  =>  "User Title",
                "rules"  =>  "required"
            ),

            array(
                "field"  =>  "user_email",
                "label"  =>  "User Email",
                "rules"  =>  "required"
            ),

            array(
                "field"  =>  "user_mobile_number",
                "label"  =>  "User Mobile Number",
                "rules"  =>  "required"
            ),

            array(
                "field"  =>  "user_password",
                "label"  =>  "User Password",
                "rules"  =>  "required"
            ),



        );
        if ($record_id) {


            $query = "SELECT user_name as old_user_name FROM `users` WHERE `users`.`user_id`='" . $record_id . "'";
            $query_result = $this->db->query($query);
            $user = $query_result->result()[0];
            if ($user->old_user_name != $this->input->post('user_name')) {
                $validation_config[] = array(
                    "field"  =>  "user_name",
                    "label"  =>  "User Name",
                    "rules"  =>  "required|is_unique[users.user_name]"
                );
            }
        } else {

            $validation_config[] = array(
                "field"  =>  "user_name",
                "label"  =>  "User Name",
                "rules"  =>  "required|is_unique[users.user_name]"
            );
        }



        //set and run the validation
        $this->form_validation->set_rules($validation_config);
        $this->form_validation->set_message('is_unique', '%s is already in use try with different user name');
        return $this->form_validation->run();
    }

    public function save_data($image_field = NULL)
    {
        $inputs = array();

        $inputs["role_id"]  =  $this->input->post("role_id");

        $inputs["district_id"]  =  $this->input->post("district_id");

        $inputs["user_title"]  =  $this->input->post("user_title");

        $inputs["user_email"]  =  $this->input->post("user_email");

        $inputs["user_mobile_number"]  =  $this->input->post("user_mobile_number");

        $inputs["user_name"]  =  $this->input->post("user_name");

        $inputs["user_password"]  =  trim($this->input->post("user_password"));




        if ($_FILES["user_image"]["size"] > 0) {
            $inputs["user_image"]  =  $this->router->fetch_class() . "/" . $this->input->post("user_image");
        }

        return $this->user_model->save($inputs);
    }

    public function update_data($user_id, $image_field = NULL)
    {
        $inputs = array();

        $inputs["role_id"]  =  $this->input->post("role_id");
        $inputs["district_id"]  =  $this->input->post("district_id");

        $inputs["user_title"]  =  $this->input->post("user_title");

        $inputs["user_email"]  =  $this->input->post("user_email");

        $inputs["user_mobile_number"]  =  $this->input->post("user_mobile_number");

        $inputs["user_name"]  =  $this->input->post("user_name");

        $inputs["user_password"]  =  $this->input->post("user_password");



        if ($_FILES["user_image"]["size"] > 0) {
            //remove previous file....
            $users = $this->get_user($user_id);
            $file_path = $users[0]->user_image;
            $this->delete_file($file_path);
            $inputs["user_image"]  =  $this->router->fetch_class() . "/" . $this->input->post("user_image");
        }

        return $this->user_model->save($inputs, $user_id);
    }

    //----------------------------------------------------------------
    public function get_user_list($where_condition = NULL, $pagination = TRUE, $public = FALSE)
    {

        $data = (object) array();
        $fields = array(
            "users.*", "roles.role_title"
        );
        $join_table = array(
            "roles" => "roles.ROLE_ID = users.ROLE_ID",
        );
        if (!is_null($where_condition)) {
            $where = $where_condition;
        } else {
            $where = "";
        }

        if ($pagination) {
            //configure the pagination
            $this->load->library("pagination");

            if ($public) {
                $config['per_page'] = 10;
                $config['uri_segment'] = 3;
                $this->user_model->uri_segment = $this->uri->segment(3);
                $config["base_url"]  = base_url($this->uri->segment(1) . "/" . $this->uri->segment(2));
            } else {
                $this->user_model->uri_segment = $this->uri->segment(4);
                $config["base_url"]  = base_url(ADMIN_DIR . $this->uri->segment(2) . "/" . $this->uri->segment(3));
            }
            $config["total_rows"] = $this->user_model->joinGet($fields, "users", $join_table, $where, true);
            $this->pagination->initialize($config);
            $data->pagination = $this->pagination->create_links();
            $data->users = $this->user_model->joinGet($fields, "users", $join_table, $where);
            return $data;
        } else {
            return $this->user_model->joinGet($fields, "users", $join_table, $where, FALSE, TRUE);
        }
    }

    public function get_user($user_id)
    {

        $fields = array(
            "users.*", "roles.role_title"
        );
        $join_table = array(
            "roles" => "roles.ROLE_ID = users.ROLE_ID",
        );
        $where = "users.user_id = $user_id";

        return $this->user_model->joinGet($fields, "users", $join_table, $where, FALSE, TRUE);
    }


    //send confirm mail
    public function sendEmail($receiver, $subject, $message)
    {


        //config email settings https://kp-whm-03.kpdata.gov.pk ssl//103.240.220.37
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'ssl://smtp.gmail.com';
        $config['smtp_port'] = '465';
        $config['smtp_user'] = "KPIAIPkpk@gmail.com";
        $config['smtp_pass'] = '@KPIAIP1234';  //sender's password health@email.com
        $config['mailtype'] = 'html';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = 'TRUE';
        $config['newline'] = "\r\n";

        $this->load->library('email', $config);
        $this->email->initialize($config);
        //send email
        $this->email->from('KPIAIPkpk@gmail.com', 'KPIAIP Account Recovery');
        //  $this->email->from($from);
        $this->email->to($receiver);
        $this->email->subject($subject);
        $this->email->message($message);


        //$this->email->send();

        //echo $this->email->print_debugger();


        //die;
        if ($this->email->send()) {

            //$inputs["verification_code"]  =  $verification_code;


            //$this->user_model->save($inputs, $user_id);


            return true;
        } else {

            return false;
        }
    }



    //activate account
    function verifyEmail($key)
    {
        $data = array('verified' => 1);
        $this->db->where('md5(users.user_email)', $key);
        if ($this->db->update('users', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function get_user_by_email($user_email)
    {

        $fields = array("*");
        $join_table = array();
        $where = "users.userEmail = $user_email";
        /*		
			$query="SELECT * FROM `users` WHERE `users`.`user_email`='".$user_email."'";
				$query_result = $this->db->query($query);
			return	$user = $query_result->row();*/


        $this->db->select("*");
        $this->db->from($this->table);
        $this->db->where(array(
            "userEmail" => $user_email
        ));
        $this->db->limit(1);
        $action_query = $this->db->get();
        $act_obj = $action_query->row();
        if ($act_obj) {
            return $act_obj;
        } else {
            return false;
        }


        //	return $this->user_model->Get($fields, "users", $join_table, $where, FALSE, TRUE);

    }
}
