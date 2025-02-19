<?php if(!defined('BASEPATH')) exit('Direct access not allowed!');

class session_m extends MY_Model{
    
    public function __construct(){
        
        parent::__construct();
        $this->table = "session_year";
        $this->pk = "session_id";
        $this->status = "status";
    }

    //-----------------------------------------------------------
     public function get_sessions()
     {
     	$query=$this->db->get($this->table);
     	return $query->result();
     }
     /////////////////////////////////

     public function get_current_session()
     {  $this->db->select('*');
        $this->db->from('session_setting');
        $this->db->join('session_year',"session_setting.session_id=session_year.sessionYearId");
     	$this->db->where('session_setting.setting_name','current');
     	$query=$this->db->get();
     	
     	return $query->row();
     }
     //////////////////////////
     public function get_next_session()
     {
     	 $this->db->select('*');
        $this->db->from('session_setting');
        $this->db->join('session_year',"session_setting.session_id=session_year.sessionYearId");
     	$this->db->where('session_setting.setting_name','next');
     	$query=$this->db->get();
     	
     	return $query->row();
     }
}