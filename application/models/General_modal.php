<?php if (!defined('BASEPATH')) exit('Direct access not allowed!');

class General_modal extends MY_Model
{

  public function __construct()
  {

    parent::__construct();
    // $this->table = "roles";
    // $this->pk = "role_id";
    // $this->status = "status";
  }
  //-----------------------------------------------------------


  /**
   * function to get list of all roles and joining it with 
   * modules for homepage name
   */

  // other data functions for loading prerequisite data 

  public function school_types($id = 0, $list = true)
  {

    $query = "SELECT `typeId`, `typeTitle` FROM `school_type`;";
    if ($id != 0) {
      $query = "SELECT `typeId`, `typeTitle` FROM `school_type` where `typeId` = '" . $id . "';";
    }
    $result = $this->db->query($query)->result();
    if ($list == true) {
      return $result;
    }
    $data = "<option>Select</option>";
    foreach ($result as $item) {
      $data .= "<option value='" . $item->typeId . "'>" . $item->typeTitle . "</option>";
    }
    return $data;
  }

  public function districts($id = 0, $list = true)
  {
    $query = "SELECT `districtId`, `districtTitle` FROM `district`";
    if ($id != 0) {
      $query = "SELECT `districtId`, `districtTitle` FROM `district` where `districtId` = '" . $id . "';";
    }
    $result = $this->db->query($query)->result();
    if ($list == true) {
      return $result;
    }
    $data = '';
    if (count($result) > 0) {
      $data = "<option value='0'>Select District</option>";
      foreach ($result as $item) {
        $data .= "<option value='" . $item->districtId . "'>" . $item->districtTitle . "</option>";
      }
    } else {
      $data .= "<option>No Districts Found.<option>";
    }
    return $data;
  }

  public function gender_of_school($id = 0, $list = true)
  {
    $query = "SELECT `genderOfSchoolId`, `genderOfSchoolTitle` FROM `genderofschool`";
    if ($id != 0) {
      $query = "SELECT `genderOfSchoolId`, `genderOfSchoolTitle` FROM `genderofschool` where `genderOfSchoolId` = '" . $id . "';";
    }
    $result = $this->db->query($query)->result();
    if ($list == true) {
      return $result;
    }
    $data = "<option>Select</option>";
    foreach ($result as $item) {
      $data .= "<option value='" . $item->genderOfSchoolId . "'>" . $item->genderOfSchoolTitle . "</option>";
    }
    return $data;
  }

  public function level_of_institute($id = 0, $list = true)
  {
    $query = "SELECT `levelofInstituteId`, `levelofInstituteTitle` FROM `levelofinstitute`";
    if ($id != 0) {
      $query = "SELECT `levelofInstituteId`, `levelofInstituteTitle` FROM `levelofinstitute` WHERE `levelofInstituteId` = '" . $id . "';";
    }
    $result = $this->db->query($query)->result();
    if ($list == true) {
      return $result;
    }
    $data = "<option>Select</option>";
    foreach ($result as $item) {
      $data .= "<option value='" . $item->levelofInstituteId . " set_select('levelofInstituteId', '" . $item->levelofInstituteId . "', TRUE); ?> >" . $item->levelofInstituteTitle . "</option>";
    }
    return $data;
  }

  public function registration_type($id = 0, $list = true)
  {
    $query = "SELECT `regTypeId`, `regTypeTitle` FROM `reg_type`;";
    if ($id != 0) {
      $query = "SELECT `regTypeId`, `regTypeTitle` FROM  `reg_type` WHERE `regTypeId` = '" . $id . "';";
    }
    $result = $this->db->query($query)->result();
    if ($list == true) {
      return $result;
    }
    $data = "<option>Select</option>";
    foreach ($result as $item) {
      $data .= "<option value='" . $item->regTypeId . "'>" . $item->regTypeTitle . "</option>";
    }
    return $data;
  }

  public function tehsils($id = 0, $list = true)
  {
    $query = "SELECT `tehsilId`, `tehsilTitle`, `district_id` FROM `tehsils` ORDER BY tehsilTitle ASC;";
    if ($id != 0) {
      $query = "SELECT `tehsilId`, `tehsilTitle`, `district_id` FROM `tehsils` WHERE `district_id` = '" . $id . "' ORDER BY tehsilTitle ASC;";
    }
    $result = $this->db->query($query)->result();
    if ($list == true) {
      return $result;
    }
    $data = '';
    if (count($result) > 0) {
      $data = "<option value='0'>Select Tehsil</option>";
      foreach ($result as $item) {
        $data .= "<option value='" . $item->tehsilId . "'>" . $item->tehsilTitle . "</option>";
      }
    } else {
      $data .= "<option>No Tehsils Found.<option>";
    }
    return $data;
  }

  public function ucs($id = 0, $list = true)
  {
    $query = "SELECT `physicalBuildingId`, `physicalBuildingTitle` FROM `physical_building`;";
    if ($id != 0) {
      $query = "SELECT `ucId`, `ucTitle`, `tehsil_id` FROM `uc` WHERE `tehsil_id` = '" . $id . "' ORDER BY ucTitle ASC;";
    }
    $result = $this->db->query($query)->result();
    if ($list == true) {
      return $result;
    }
    $data = "<option value=''>Select</option>";
    foreach ($result as $item) {
      $data .= "<option value='" . $item->ucId . "'>" . $item->ucTitle . "</option>";
    }
    $data .= '<option value="0">Not In List</option>';
    return $data;
  }

  public function building($id = 0, $list = true)
  {
    $query = "SELECT `physicalBuildingId`, `physicalBuildingTitle` FROM `physical_building`;";
    if ($id != 0) {
      $query = "SELECT `physicalBuildingId`, `physicalBuildingTitle` FROM `physical_building` WHERE `physicalBuildingId` = '" . $id . "';";
    }
    $result = $this->db->query($query)->result();
    if ($list == true) {
      return $result;
    }
    $data = "<option>Select</option>";
    foreach ($result as $item) {
      $data .= "<option value='" . $item->physicalBuildingId . "'>" . $item->physicalBuildingTitle . "</option>";
    }
    return $data;
  }

  public function physical($id = 0, $list = true)
  {
    $query = "SELECT `physicalId`, `physicalTitle` FROM `physical_facilities_physical_meta`;";
    if ($id != 0) {
      $query = "SELECT `physicalId`, `physicalTitle` FROM `physical_facilities_physical_meta` WHERE `physicalId` = '" . $id . "';";
    }
    $result = $this->db->query($query)->result();
    if ($list == true) {
      return $result;
    }
    $data = "<option>Select</option>";
    foreach ($result as $item) {
      $data .= "<option value='" . $item->physicalId . "'>" . $item->physicalTitle . "</option>";
    }
    return $data;
  }

  public function academic($id = 0, $list = true)
  {
    $query = "SELECT `academicId`, `academicTitle` FROM `physical_facilities_academic_meta`;";
    if ($id != 0) {
      $query = "SELECT `academicId`, `academicTitle` FROM `physical_facilities_academic_meta` WHERE `academicId` = '" . $id . "';";
    }
    $result = $this->db->query($query)->result();
    if ($list == true) {
      return $result;
    }
    $data = "<option>Select</option>";
    foreach ($result as $item) {
      $data .= "<option value='" . $item->academicId . "'>" . $item->academicTitle . "</option>";
    }
    return $data;
  }

  public function book_type($id = 0, $list = true)
  {
    $query = "SELECT `bookTypeId`, `bookType` FROM `book_type`;";
    if ($id != 0) {
      $query = "SELECT `bookTypeId`, `bookType` FROM `book_type` WHERE `bookTypeId` = '" . $id . "';";
    }
    $result = $this->db->query($query)->result();
    if ($list == true) {
      return $result;
    }
    $data = "<option>Select</option>";
    foreach ($result as $item) {
      $data .= "<option value='" . $item->bookTypeId . "'>" . $item->bookType . "</option>";
    }
    return $data;
  }

  public function co_curricular($id = 0, $list = true)
  {
    $query = "SELECT `coCurricularId`, `coCurricularTitle` FROM `physical_facilities_co_curricular_meta`;";
    if ($id != 0) {
      $query = "SELECT `coCurricularId`, `coCurricularTitle` FROM `physical_facilities_co_curricular_meta` WHERE `coCurricularId` = '" . $id . "';";
    }
    $result = $this->db->query($query)->result();
    if ($list == true) {
      return $result;
    }
    $data = "<option>Select</option>";
    foreach ($result as $item) {
      $data .= "<option value='" . $item->coCurricularId . "'>" . $item->coCurricularTitle . "</option>";
    }
    return $data;
  }

  public function other($id = 0, $list = true)
  {
    $query = "SELECT `otherId`, `otherTitle` FROM `physical_facilities_others_meta`;";
    if ($id != 0) {
      $query = "SELECT `otherId`, `otherTitle` FROM `physical_facilities_others_meta` WHERE `otherId` = '" . $id . "';";
    }
    $result = $this->db->query($query)->result();
    if ($list == true) {
      return $result;
    }
    $data = "<option>Select</option>";
    foreach ($result as $item) {
      $data .= "<option value='" . $item->otherId . "'>" . $item->otherTitle . "</option>";
    }
    return $data;
  }

  public function location($id = 0, $list = true)
  {
    $query = "SELECT `locationId`, `locationTitle` FROM `location`;";
    if ($id != 0) {
      $query = "SELECT `locationId`, `locationTitle` FROM `location` WHERE `locationId` = '" . $id . "';";
    }
    $result = $this->db->query($query)->result();
    if ($list == true) {
      return $result;
    }
    $data = "<option>Select</option>";
    foreach ($result as $item) {
      $data .= "<option value='" . $item->locationId . "'>" . $item->locationTitle . "</option>";
    }
    return $data;
  }

  public function bise_list($id = 0, $list = true)
  {
    $query = "SELECT `biseId`, `biseName` FROM `bise`;";
    if ($id != 0) {
      $query = "SELECT `biseId`, `biseName` FROM `bise` WHERE `biseId` = '" . $id . "';";
    }
    $result = $this->db->query($query)->result();
    if ($list == true) {
      return $result;
    }
    $data = "<option>Select</option>";
    foreach ($result as $item) {
      $data .= "<option value='" . $item->biseId . "'>" . $item->biseName . "</option>";
    }
    return $data;
  }
}
