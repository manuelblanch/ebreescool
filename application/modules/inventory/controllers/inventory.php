<?php defined('BASEPATH') OR exit('No direct script access allowed');

include "application/third_party/skeleton/application/controllers/skeleton_main.php";


class inventory extends skeleton_main {

	public $body_header_view ='include/ebre_escool_body_header.php' ;

	public $body_header_lang_file ='ebre_escool_body_header' ;



function __construct()	{
		parent::__construct();

		//GROCERY CRUD
		$this->load->add_package_path(APPPATH.'third_party/grocery-crud/application/');
        $this->load->library('grocery_CRUD');
        $this->load->add_package_path(APPPATH.'third_party/image-crud/application/');
		$this->load->library('image_CRUD');  

		/* Set language */
		$current_language=$this->session->userdata("current_language");
		if ($current_language == "") {
			$current_language= $this->config->item('default_language');
		}
		

		$this->lang->load('inventory', $current_language);

		
        //LANGUAGE HELPER:
        $this->load->helper('language');

	}	

public function index()	{

	if (!$this->skeleton_auth->logged_in())	{
		//redirect them to the login page
		redirect($this->skeleton_auth->login_page, 'refresh');
	}

	$output = array();
	
	$this->_load_html_header($this->_get_html_header_data(),$output); 
	$this->_load_body_header();
	
	
	$this->load->view('inventory',$output); 
                
	$this->_load_body_footer();

	}
	
}
 
/* End of file inventory.php */
/* Location: ./application/modules/inventory/controllers/inventory.php */