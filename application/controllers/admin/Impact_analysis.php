<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Impact_analysis extends CI_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->load->helper('project_helper');
   }

   public function index()
   {
      $this->data['title'] = 'Impact Analysis Dashboard';
      $this->data['description'] = 'KP-IAIP Project Impact Analysis Dashboard';
      $this->load->view('admin/impact_analysis/index', $this->data);
   }

   public function quarterly_field_visits()
   {
      $this->data['title'] = 'Quarterly Achievement of the Field Visit conducted by the M&EC Team so far';
      $this->data['description'] = 'Monitoring and Impact Data collected of Sub Components';
      $this->load->view('admin/impact_analysis/surveys/quarterly_field_visits', $this->data);
   }

   public function get_quarterly_field_visits_district_wise()
   {
      $this->data['impact_quarter_id'] = (int) $this->input->post('impact_quarter_id');
      $this->data['region'] = $this->input->post('region');
      $this->data['title'] = '';
      $this->data['description'] = '';
      $this->load->view('admin/impact_analysis/surveys/quarterly_field_visits_district_wise', $this->data);
   }




   public function get_quarterly_sub_component_wise()
   {
      $this->data['impact_quarter_id'] = (int) $this->input->post('impact_quarter_id');
      $this->data['region'] = $this->input->post('region');
      $this->data['title'] = '';
      $this->data['description'] = '';
      $this->load->view('admin/impact_analysis/surveys/quarterly_sub_component_wise', $this->data);
   }

   public function get_quarterly_component_wise()
   {
      $this->data['impact_quarter_id'] = (int) $this->input->post('impact_quarter_id');
      $this->data['region'] = $this->input->post('region');
      $this->data['title'] = '';
      $this->data['description'] = '';
      $this->load->view('admin/impact_analysis/surveys/quarterly_component_wise', $this->data);
   }

   public function get_quarterly_categories_wise()
   {
      $this->data['impact_quarter_id'] = (int) $this->input->post('impact_quarter_id');
      $this->data['region'] = $this->input->post('region');
      $this->data['title'] = '';
      $this->data['description'] = '';
      $this->load->view('admin/impact_analysis/surveys/quarterly_categories_wise', $this->data);
   }

   public function issues_and_damages_schemes()
   {
      echo "we are here";
   }

   public function irrigated_cca()
   {
      $this->data['title'] = '';
      $this->data['description'] = '';
      $this->load->view('admin/impact_analysis/irrigated_cca/components_outcome', $this->data);
   }

   public function crop_yield()
   {
      $this->data['title'] = '';
      $this->data['description'] = '';
      $this->load->view('admin/impact_analysis/crop_yield/crop_yield', $this->data);
   }

   public function cropping_pattern()
   {
      $this->data['title'] = '';
      $this->data['description'] = '';
      $this->load->view('admin/impact_analysis/cropping_pattern/cropping_pattern', $this->data);
   }
   public function water_losses()
   {
      $this->data['title'] = '';
      $this->data['description'] = '';
      $this->load->view('admin/impact_analysis/water_losses/water_losses', $this->data);
   }
   public function water_productivity()
   {
      $this->data['title'] = '';
      $this->data['description'] = '';
      $this->load->view('admin/impact_analysis/water_productivity/water_productivity', $this->data);
   }
   public function cropping_intensity()
   {
      $this->data['title'] = '';
      $this->data['description'] = '';
      $this->load->view('admin/impact_analysis/cropping_intensity/cropping_intensity', $this->data);
   }

   public function beneficiaries()
   {
      $this->data['title'] = '';
      $this->data['description'] = '';
      $this->load->view('admin/impact_analysis/beneficiaries/beneficiaries', $this->data);
   }

   public function engagment_benefits()
   {
      $this->data['title'] = '';
      $this->data['description'] = '';
      $this->load->view('admin/impact_analysis/engagment_benefits/engagment_benefits', $this->data);
   }

   public function env_and_social_management()
   {
      $this->data['title'] = '';
      $this->data['description'] = '';
      $this->load->view('admin/impact_analysis/env_and_social_management/env_and_social_management', $this->data);
   }

   public function private_investment()
   {
      $this->data['title'] = '';
      $this->data['description'] = '';
      $this->load->view('admin/impact_analysis/private_investment/private_investment', $this->data);
   }

   public function export_data($file_name)
   {
      if ($file_name == 'Irrigated_CCA') {
         $query = "SELECT id, impact_survery_id, region, district, component, sub_component, category, 
         irrigated_area_before, irrigated_area_after FROM `impact_surveys` ORDER BY id ASC ";
      } else {
         echo "File Name Not Found";
      }
      $result = $this->db->query($query)->result_array();
      $filename = $file_name . '_' . time() . '.csv';
      header('Content-Type: text/csv');
      header('Content-Disposition: attachment;filename=' . $filename);
      $output = fopen('php://output', 'w');
      if (!empty($result)) {
         // Get headers from the first row
         fputcsv($output, array_keys($result[0]));
         foreach ($result as $row) {
            fputcsv($output, $row);
         }
      }
      fclose($output);
   }
}
