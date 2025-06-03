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
   public function impact_income()
   {
      $this->data['title'] = '';
      $this->data['description'] = '';
      $this->load->view('admin/impact_analysis/impact_income/impact_income', $this->data);
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
      switch ($file_name) {


         case 'Irrigated_CCA':
            $query = "SELECT id, impact_survery_id, region, district, component, sub_component, category, 
                  irrigated_area_before, irrigated_area_after 
                  FROM `impact_surveys` 
                  ORDER BY id ASC";
            break;

         case 'Cropping_Intensity_Raw':
            $query = "SELECT id, impact_survery_id, region, district, component, sub_component, category, 
                  `rabi_crop_area_acres_before`,
                  `zaid_rabi_crop_area_acres_before`,
                  `kharif_crop_area_acres_before`,
                  `zaid_kharif_crop_area_acres_before`,
                  `sugarcane_crop_area_acres_before`,
                  `area_intercropped_sugarcane_acres_before`,
                  `orchard_area_acres_before`,
                  `area_intercropped_orchards_acres_before`,
                  `total_cultivable_command_area_acres_before`,
                  `rabi_crop_area_acres_after`,
                  `zaid_rabi_crop_area_acres_before`,
                  `kharif_crop_area_acres_after`,
                  `zaid_kharif_crop_area_acres_after`,
                  `sugarcane_crop_area_acres_after`,
                  `area_intercropped_sugarcane_acres_after`,
                  `orchard_area_acres_after`,
                  `area_intercropped_orchards_acres_after`,
                  `total_cultivable_command_area_acres_after`  
                  FROM `impact_surveys` 
                  ORDER BY id ASC";
            break;

         case 'Cropping_Intensity_Compiled':
            $query = "SELECT * FROM `impact_crop_intensity` ORDER BY id ASC";
            break;

         case 'Crop_Yields':
            $query = "SELECT id, impact_survery_id, region, district, component, sub_component, category,
                     `wheat_yield_before`, `wheat_yield_after`, 
                     `maize_yield_before`, `maize_yield_after`, 
                     `sugarcane_yield_before`, `sugarcane_yield_after`, 
                     `vegetable_yield`, `orchard_yield`
                  FROM `impact_surveys` 
                  ORDER BY id ASC";
            break;


         case 'Cropping_Pattern':
            $query = "SELECT id, impact_survery_id, region, district, component, sub_component, category,
                     wheat_cp_before, wheat_cp_after,
                     maize_cp_before, maize_cp_after,
                     maize_hybrid_cp_before, maize_hybrid_cp_after,
                     sugarcane_cp_before, sugarcane_cp_after,
                     fodder_cp_before, fodder_cp_after,
                     vegetable_cp_before, vegetable_cp_after,
                     fruit_orchard_cp_before, fruit_orchard_cp_after
                  FROM `impact_surveys` 
                  ORDER BY id ASC";
            break;

         case 'Water_Losses_Raw':
            $query = "SELECT id, impact_survery_id, region, district, component, sub_component, category,
                     actual_discharge_before_head,
                     actual_discharge_before_tail,
                     actual_discharge_after_head,
                     actual_discharge_after_tail
                  FROM `impact_surveys` 
                  ORDER BY id ASC";
            break;

         case 'Water_Losses_Compiled':
            $query = "SELECT * FROM `impact_surveys_water_losses` ORDER BY id ASC";
            break;

         case 'Water_Productivity_Raw':
            $query = "SELECT id, impact_survery_id, region, district, component, sub_component, category,
                  design_discharge_head AS design_discharge_head,
                  wheat_irrigation_times AS wheat_irrigation_times,
                  wheat_irrigation_time_acre AS wheat_irrigation_time_acre,
                  wheat_yield_after AS wheat_yield_after,
                  maize_irrigation_times AS maize_irrigation_times,
                  maize_irrigation_time_acre AS maize_irrigation_time_acre,
                  maize_yield_after AS maize_yield_after
                  FROM `impact_surveys` 
                  ORDER BY id ASC";
            break;

         case 'Water_Productivity_Compiled':
            $query = "SELECT * FROM `impact_survery_water_productivity` ORDER BY id ASC";
            break;

         case 'Employment_Growth':
            $query = "SELECT id, impact_survery_id, region, district, component, sub_component, category,
                 income_improved_per, 
                 unskilled_labor_before,
                 unskilled_labor_after,
                 skilled_labor_before,
                 skilled_labor_after
                  FROM `impact_surveys` 
                  ORDER BY id ASC";
            break;
         case 'Income_Improvement':
            $query = "SELECT id, impact_survery_id, region, district, component, sub_component, category,
                 income_improved_per
                  FROM `impact_surveys` 
                  ORDER BY id ASC";
            break;
         case 'Env_And_Social':
            $query = "SELECT id, impact_survery_id, region, district, component, sub_component, category,
                 `watercourse_women_benefit`,
                  `clean_water_access`,
                  `kitchen_gardening`,
                  `drinking_water`,
                  `bathing_clean_water`,
                  `clothes_washing_water`,
                  `women_time_social_economic`,
                  `women_easy_irrigation`,
                  `road_accessible`,
                  `solid_waste_disposed`,
                  `soil_disposed`,
                  `construction_waste_disposed`,
                  `trees_cut`,
                  `trees_cut_count`,
                  `trees_planted`,
                  `trees_planted_count`,
                  `env_quality_monitored`,
                  `standing_water`,
                  `distress_damage`,
                  `pest_mgmt_knowledge`,
                  `pesticide_storage_handling`,
                  `surplus_pesticide_mgmt`,
                  `green_manure_soil_practice`,
                  `pesticide_impact_observed`,
                  `env_quality_monitoring`,
                  `pesticide_timeframe_understanding`
                  FROM `impact_surveys` 
                  ORDER BY id ASC";
            break;

         case 'Environmental':
            $query = "SELECT id, impact_survery_id, region, district, component, sub_component, category,
                  `solid_waste_disposed`,
                  `soil_disposed`,
                  `construction_waste_disposed`,
                  `env_quality_monitored`,
                  `standing_water`,
                  `distress_damage`
                  FROM `impact_surveys` 
                  ORDER BY id ASC";
            break;
         case 'Trees':
            $query = "SELECT id, impact_survery_id, region, district, component, sub_component, category,
                  `trees_cut`,
                  `trees_cut_count`,
                  `trees_planted`,
                  `trees_planted_count`
                  FROM `impact_surveys` 
                  ORDER BY id ASC";
            break;

         case 'IPM_Forming_Community':
            $query = "SELECT id, impact_survery_id, region, district, component, sub_component, category,
                  `pest_mgmt_knowledge`,
                  `pesticide_storage_handling`,
                  `surplus_pesticide_mgmt`,
                  `green_manure_soil_practice`,
                  `pesticide_impact_observed`,
                  `env_quality_monitoring`,
                  `pesticide_timeframe_understanding`
                  FROM `impact_surveys` 
                  ORDER BY id ASC";
            break;

         case 'Female_Beneficiaries':
            $query = "SELECT id, impact_survery_id, region, district, component, sub_component, category,
                  `watercourse_women_benefit`,
                  `clean_water_access`,
                  `kitchen_gardening`,
                  `drinking_water`,
                  `bathing_clean_water`,
                  `clothes_washing_water`,
                  `women_time_social_economic`,
                  `women_easy_irrigation`,
                  `road_accessible`
                  FROM `impact_surveys` 
                  ORDER BY id ASC";
            break;


         case 'Feild_Visits':
            $query = "SELECT id, impact_survery_id, `start`, `end`, `quarter`,  impact_quarter_id, 
            `field_monitor`, region, district, component, sub_component, category
            FROM `impact_surveys` 
            ORDER BY id ASC";
            break;

         case 'Private_Investment':
            $query = "SELECT id, impact_survery_id, region, district, component, sub_component, category,
            actual_cost,
            community_share,
            (actual_cost + community_share) as total_cost,
            ((community_share / NULLIF(actual_cost + community_share, 0)) * 100) AS community_share_percentage
            FROM `impact_surveys` 
            ORDER BY id ASC";
            break;
         default:
            echo "File Name Not Found";
            exit();
            break;
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
