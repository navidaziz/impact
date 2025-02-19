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
}
