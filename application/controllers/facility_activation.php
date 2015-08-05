<?php
/*
 * @author Karsan
 */
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Facility_activation extends MY_Controller 
{
	function __construct() 
	{
		// echo "I WORK";exit;
		// echo md5(123456);exit;
		parent::__construct();
		$this -> load -> helper(array('form', 'url'));
		$this -> load -> library(array('hcmp_functions', 'form_validation'));
	}

	public function index(){
		$this->facility_dash();
	}
	public function facility_dash(){
		$identifier = $this -> session -> userdata('user_indicator');
		$user_type_id = $this -> session -> userdata('user_type_id');
		$district = $this -> session -> userdata('district_id');
		// echo $identifier;exit;
		$county = $this -> session -> userdata('county_id');

		// $data['facilities'] = isset($district) ? Facilities::get_facility_details($district) : Facilities::get_facilities_per_county($county);

		if ($identifier == "district") {
			$data['facilities'] = Facilities::get_facility_details($district);
			$data['identifier'] = $identifier;
		}elseif ($identifier == "county") {
			$data['facilities'] = Facilities::get_facility_details(NULL,$county);
			$data['identifier'] = $identifier;
			$data['district_info'] = Districts::get_districts($county);

			// echo "<pre>";print_r($districts);echo "</pre>";exit;

		}
		$permissions='district_permissions';
		// $data['facilities']=Facilities::get_facility_details($district);
		// $data['facilities']=Facilities::get_facilities_per_county($county);
		// echo "<pre>";print_r($data['facilities']);echo "</pre>";exit;
		$data['title'] = "Facility Management";
		$data['banner_text'] = "Facility Management";
		$template = 'shared_files/template/template';
		// $data['sidebar'] = "shared_files/report_templates/side_bar_sub_county_v";
		$data['active_panel'] = 'system_usage';
		// $data['report_view'] = "shared_files/Facility_activation_v";
		$data['content_view'] = "shared_files/facility_activation_v";
		$this -> load -> view($template, $data);

	}

	public function change_status($facility_code = NULL,$status = NULL){
		$facility_code = $_POST['facility_code'];
		$status = $_POST['status'];

		$update_user = Doctrine_Manager::getInstance()->getCurrentConnection();
			$update_user->execute("UPDATE `facilities` SET using_hcmp = '$status' WHERE `facility_code`= '$facility_code'");
			echo $update_user." success";
	}

	public function add_facility(){
		// echo"<pre>"; var_dump($this->input->post()); echo "</pre>"; exit;
		
		$facility_code = $this->input->post('facility_code');
		$facility_name = $this->input->post('facility_name');
		$district = $this->input->post('district');
		$owner = $this->input->post('owner');
		$facility_type = $this->input->post('facility_type');
		$facility_level = $this->input->post('facility_level');
		$contact_name = $this->input->post('contact_name');
		$contact_phone = $this->input->post('contact_phone');
		$activation_status = $this->input->post('activation_status');
		
		// echo $facility_code;exit;
		/*
		$facility_code = 1234;
		$facility_name = "asdfasdf";
		$district = 5;
		$owner = "asdfasdfasdfasdf";
		$facility_type = "weqwerqwer";
		$facility_level = "1";
		$contact_name = "asdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdf";
		$contact_phone = "788787878787";
		*/

		$facility_details = array();
		$facility_details_ = array(
			'facility_code' => $facility_code, 
			'facility_name' => $facility_name, 
			'district' => $district, 
			'owner' => $owner, 
			'type' => $facility_type, 
			'level' => $facility_level, 
			'contactperson' => $contact_name, 
			'cellphone' => $contact_phone,
			'drawing_rights'=> 0,
			'rtk_enabled'=> 0,
			'cd4_enabled'=> 0,
			'drawing_rights_balance'=> 0,
			'using_hcmp'=> $activation_status,
			'date_of_activation'=> date('Y m D'),
			'targetted'=> 0
			);

		array_push($facility_details, $facility_details_);

		$result = $this->db->insert_batch('facilities',$facility_details);

		echo "This: ".$result;exit;
		// $savefacility = new Facilities();

		// $savefacility -> facility_code = $facility_code;
		// $savefacility -> facility_name = $facility_name;
		// $savefacility -> district = $district;
		// $savefacility -> owner = $owner;
		// $savefacility -> facility_type = $facility_type;
		// $savefacility -> facility_level = $facility_level;
		// $savefacility -> contact_name = $contact_name;
		// $savefacility -> contact_phone = $contact_phone;
		// $savefacility -> save(); 

	}
}

?>