<?php defined('BASEPATH') OR exit('No direct script access allowed');


include "application/third_party/skeleton/application/controllers/skeleton_main.php";

class skeleton extends skeleton_main {
	
	function __construct()
    {
		parent::__construct();
	}
	
	public function index() {
		if (!$this->skeleton_auth->logged_in())
		{
			//redirect them to the login page
			redirect($this->skeleton_auth->login_page, 'refresh');
		}
		redirect('dashboard','refresh');
		
	}
}
