<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_data extends CI_Controller {
	
	public function __construct(){
        
        parent::__construct();
		// $this->load->model('Data_m');

    	}

	public function index($value=''){
		echo "exit()";exit();
	}

	public function type_of_institute($value=''){
		$query = "SELECT
					    `levelofInstituteId`
					    , `levelofInstituteTitle`
					FROM
					    `levelofinstitute`;";
		$list = $this->db->query($query)->result();
		$data = "<option>Select</option>";
		foreach ($list as $item) {
			$data .= "<option value='".$item->levelofInstituteId."'>".$item->levelofInstituteTitle."</option>";
		}
		return $data;
	}

	public function districts($value=''){
		$query = "SELECT `districtId`, `districtTitle` FROM `district`";
		$list = $this->db->query($query)->result();
				$data = "<option>Select</option>";
				foreach ($list as $item) {
					$data .= "<option value='".$item->districtId."'>".$item->districtTitle."</option>";
				}
				return $data;
	}

	public function gender_of_school($value=''){
		$query = "SELECT `genderOfSchoolId`, `genderOfSchoolTitle` FROM `genderofschool`";
		$list = $this->db->query($query)->result();
			$data = "<option>Select</option>";
			foreach ($list as $item) {
				$data .= "<option value='".$item->genderOfSchoolId."'>".$item->genderOfSchoolTitle."</option>";
			}
			return $data;
	}

	public function level_of_institute($value=''){
		$query = "SELECT `levelofInstituteId`, `levelofInstituteTitle` FROM `levelofinstitute`";
		$list = $this->db->query($query)->result();
			$data = "<option>Select</option>";
			foreach ($list as $item) {
				$data .= "<option value='".$item->levelofInstituteId."'>".$item->levelofInstituteTitle."</option>";
			}
			return $data;

	}
}