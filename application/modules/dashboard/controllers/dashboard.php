<?php defined('BASEPATH') OR exit('No direct script access allowed');


include "application/third_party/skeleton/application/controllers/skeleton_main.php";

class dashboard extends skeleton_main {
	
	public $body_header_view ='include/ebre_escool_body_header' ;

	public $body_header_lang_file ='ebre_escool_body_header' ;

	
	function __construct()
    {
        parent::__construct();
        
	}
	
	public function index() {		
		$this->_load_html_header($this->_get_html_header_data()); 
		$this->_load_body_header();
		
		$this->load->view('dashboard'); 
                
		$this->_load_body_footer();	 
	}		
}
