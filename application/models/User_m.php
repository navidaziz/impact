<?php if (!defined('BASEPATH')) exit('Direct access not allowed!');

class User_m extends MY_Model
{

    public function __construct()
    {

        parent::__construct();
        $this->table = "users";
        $this->pk = "userId";
        $this->status = "status";
    }
    //------------------------------------------------------



    /**
     * get users with inner join role and dept
     */
    public function usersDeptRole()
    {

        $this->db->select("users.*, roles.role_title, departments.department_title");
        $this->db->from($this->table);
        $this->db->join("roles", "users.role_id = roles.role_id");
        $this->db->join("departments", "users.ngo_id = departments.ngo_id");
        $query = $this->db->get();
        var_dump($query->result());
    }
    //---------------------------------------------------------



    /**
     * login a user
     */
    public function login()
    {
    }
    //--------------------------------------------------------




    /**
     * check if user is logged in or not
     */
    public function loggedIn()
    {

        if ($this->session->userdata("logged_in") === TRUE) {
            return true;
        }
        return false;
    }
    //---------------------------------------------------------



    /**
     * log out the user
     */
    public function logOut()
    {

        $this->session->sess_destroy();
    }


    // my functions ...
    public function get_user_list($limit, $offset)
    {
        $user_id = $this->session->userdata('userId');
        $result = $this->db->where('userId', $user_id)->get('users')->result()[0];
        $district_id = $result->district_id;
        $role_id = $result->role_id;

        $this->db->select('userId, userTitle, user_title, userPassword, userEmail, userStatus, gender, cnic, contactNumber, district_id, users.createdDate, users.createdBy, users.lastUpdatedBy, address, district.districtTitle as districtTitle');
        $this->db->from('users');
        $this->db->join('district', 'district.districtId = users.district_id', 'left');
        $this->db->order_by('userId', 'DESC');
        $this->db->limit($limit, $offset);
        if ($role_id == 16) :
            $this->db->where('district_id', $district_id);
            $this->db->where('userId >=', '109');
        endif;
        $query = $this->db->get();
        return $query->result();
    }
}
