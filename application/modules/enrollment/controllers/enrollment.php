<?php defined('BASEPATH') OR exit('No direct script access allowed');

//include "skeleton_main.php";
include "application/third_party/skeleton/application/controllers/skeleton_main.php";

class enrollment extends skeleton_main {
	
    public $body_header_view ='include/ebre_escool_body_header.php' ;
    public $body_header_lang_file ='ebre_escool_body_header' ;

	function __construct()
    {
        parent::__construct();
        
        //$this->load->model('attendance_model');
        //$this->load->model('enrollment_model');
        //$this->load->library('ebre_escool_ldap');
        //$this->config->load('managment');        
        
        /* Set language */
        $current_language=$this->session->userdata("current_language");
        if ($current_language == "") {
            $current_language= $this->config->item('default_language');
        }
        
        // Load the language file
        $this->lang->load('enrollment',$current_language);
        $this->load->helper('language');

	}
	
	protected function _getvar($name){
		if (isset($_GET[$name])) return $_GET[$name];
		else if (isset($_POST[$name])) return $_POST[$name];
		else return false;
	}

	public function index() {
		$this->enrollment();
	}

/* ENROLLMENT */

	public function enrollment() {

		if (!$this->skeleton_auth->logged_in())
		{
			//redirect them to the login page
			redirect($this->skeleton_auth->login_page, 'refresh');
		}
		
		//CHECK IF USER IS READONLY --> unset add, edit & delete actions
		$readonly_group = $this->config->item('readonly_group');
		if ($this->skeleton_auth->in_group($readonly_group)) {
			$this->grocery_crud->unset_add();
			$this->grocery_crud->unset_edit();
			$this->grocery_crud->unset_delete();
		}

		/* Grocery Crud */
		$this->current_table="enrollment";
        $this->grocery_crud->set_table($this->current_table);
        $this->session->set_flashdata('table_name', $this->current_table);
        
        //ESTABLISH SUBJECT
        $this->grocery_crud->set_subject(lang('enrollment'));       

		//Mandatory fields
        

        //CALLBACKS        
        $this->grocery_crud->callback_add_field('enrollment_entryDate',array($this,'add_field_callback_entryDate'));
        $this->grocery_crud->callback_edit_field('enrollment_entryDate',array($this,'edit_field_callback_entryDate'));
        
        //Camps last update no editable i automàtic        
        $this->grocery_crud->callback_edit_field('enrollment_last_update',array($this,'edit_callback_last_update'));

        //Express fields
        

        //COMMON_COLUMNS               
        $this->set_common_columns_name();

        //SPECIFIC COLUMNS

        $this->grocery_crud->display_as('enrollment_periodid',lang('enrollment_periodid'));        
        $this->grocery_crud->display_as('enrollment_personid',lang('enrollment_personid'));

        $this->grocery_crud->display_as('enrollment_entryDate',lang('entryDate'));        
        $this->grocery_crud->display_as('enrollment_last_update',lang('last_update'));
        $this->grocery_crud->display_as('enrollment_creationUserId',lang('creationUserId'));
        $this->grocery_crud->display_as('enrollment_lastupdateUserId',lang('lastupdateUserId'));          
        $this->grocery_crud->display_as('enrollment_markedForDeletion',lang('markedForDeletion'));   
        $this->grocery_crud->display_as('enrollment_markedForDeletionDate',lang('markedForDeletionDate'));        		

        //UPDATE AUTOMATIC FIELDS
		$this->grocery_crud->callback_before_insert(array($this,'before_insert_object_callback'));
		$this->grocery_crud->callback_before_update(array($this,'before_update_object_callback'));
        
        $this->grocery_crud->unset_add_fields('enrollment_last_update');
   		
   		//USER ID: show only active users and by default select current userid. IMPORTANT: Field is not editable, always forced to current userid by before_insert_object_callback
        $this->grocery_crud->set_relation('enrollment_creationUserId','users','{username}',array('active' => '1'));
        $this->grocery_crud->set_default_value($this->current_table,'enrollment_creationUserId',$this->session->userdata('user_id'));

        //LAST UPDATE USER ID: show only active users and by default select current userid. IMPORTANT: Field is not editable, always forced to current userid by before_update_object_callback
        $this->grocery_crud->set_relation('enrollment_lastupdateUserId','users','{username}',array('active' => '1'));
        $this->grocery_crud->set_default_value($this->current_table,'enrollment_lastupdateUserId',$this->session->userdata('user_id'));
        
        $this->grocery_crud->unset_dropdowndetails("enrollment_creationUserId","study_submodules_lastupdateUserId");
   
        $this->set_theme($this->grocery_crud);
        $this->set_dialogforms($this->grocery_crud);
        
        //Default values:
//      $this->grocery_crud->set_default_value($this->current_table,'parentLocation',1);
        //markedForDeletion
        $this->grocery_crud->set_default_value($this->current_table,'enrollment_markedForDeletion','n');
                   
        $output = $this->grocery_crud->render();

       /*******************
	   /* HTML HEADER     *
	   /******************/
	   $this->_load_html_header($this->_get_html_header_data(),$output); 
	   
	   /*******************
	   /*      BODY       *
	   /******************/
	   $this->_load_body_header();
	   
		$default_values=$this->_get_default_values();
		$default_values["table_name"]=$this->current_table;
		$default_values["field_prefix"]="enrollment_";
		$this->load->view('defaultvalues_view.php',$default_values); 

        $this->load->view('enrollment/enrollment.php',$output);     
       
       /*******************
	   /*      FOOTER     *
	   *******************/
	   $this->_load_body_footer();	
	}

/* FI ENROLLMENT */

/* ENROLLMENT STUDIES */

	public function enrollment_studies() {

		if (!$this->skeleton_auth->logged_in())
		{
			//redirect them to the login page
			redirect($this->skeleton_auth->login_page, 'refresh');
		}
		
		//CHECK IF USER IS READONLY --> unset add, edit & delete actions
		$readonly_group = $this->config->item('readonly_group');
		if ($this->skeleton_auth->in_group($readonly_group)) {
			$this->grocery_crud->unset_add();
			$this->grocery_crud->unset_edit();
			$this->grocery_crud->unset_delete();
		}

		/* Grocery Crud */
		$this->current_table="enrollment_studies";
        $this->grocery_crud->set_table($this->current_table);
        $this->session->set_flashdata('table_name', $this->current_table);
        
        //ESTABLISH SUBJECT
        $this->grocery_crud->set_subject(lang('enrollment_studies'));       

		//Mandatory fields
        

        //CALLBACKS        
        $this->grocery_crud->callback_add_field('enrollment_studies_entryDate',array($this,'add_field_callback_entryDate'));
        $this->grocery_crud->callback_edit_field('enrollment_studies_entryDate',array($this,'edit_field_callback_entryDate'));
        
        //Camps last update no editable i automàtic        
        $this->grocery_crud->callback_edit_field('enrollment_studies_last_update',array($this,'edit_callback_last_update'));

        //Express fields
        

        //COMMON_COLUMNS               
        $this->set_common_columns_name();

        //SPECIFIC COLUMNS
        $this->grocery_crud->display_as('enrollment_studies_entryDate',lang('entryDate'));        
        $this->grocery_crud->display_as('enrollment_studies_last_update',lang('last_update'));
        $this->grocery_crud->display_as('enrollment_studies_creationUserId',lang('creationUserId'));
        $this->grocery_crud->display_as('enrollment_studies_lastupdateUserId',lang('lastupdateUserId'));          
        $this->grocery_crud->display_as('enrollment_studies_markedForDeletion',lang('markedForDeletion'));   
        $this->grocery_crud->display_as('enrollment_studies_markedForDeletionDate',lang('markedForDeletionDate'));        		

        $this->grocery_crud->display_as('enrollment_studies_periodid',lang('enrollment_studies_periodid'));          
        $this->grocery_crud->display_as('enrollment_studies_personid',lang('enrollment_studies_personid'));   
        $this->grocery_crud->display_as('enrollment_studies_study_id',lang('enrollment_studies_study_id'));        		

        //UPDATE AUTOMATIC FIELDS
		$this->grocery_crud->callback_before_insert(array($this,'before_insert_object_callback'));
		$this->grocery_crud->callback_before_update(array($this,'before_update_object_callback'));
        
        $this->grocery_crud->unset_add_fields('enrollment_studies_last_update');
   		
   		//USER ID: show only active users and by default select current userid. IMPORTANT: Field is not editable, always forced to current userid by before_insert_object_callback
        $this->grocery_crud->set_relation('enrollment_studies_creationUserId','users','{username}',array('active' => '1'));
        $this->grocery_crud->set_default_value($this->current_table,'enrollment_studies_creationUserId',$this->session->userdata('user_id'));

        //LAST UPDATE USER ID: show only active users and by default select current userid. IMPORTANT: Field is not editable, always forced to current userid by before_update_object_callback
        $this->grocery_crud->set_relation('enrollment_studies_lastupdateUserId','users','{username}',array('active' => '1'));
        $this->grocery_crud->set_default_value($this->current_table,'enrollment_studies_lastupdateUserId',$this->session->userdata('user_id'));
        
        $this->grocery_crud->unset_dropdowndetails("enrollment_studies_creationUserId","study_submodules_lastupdateUserId");
   
        $this->set_theme($this->grocery_crud);
        $this->set_dialogforms($this->grocery_crud);
        
        //Default values:
//      $this->grocery_crud->set_default_value($this->current_table,'parentLocation',1);
        //markedForDeletion
        $this->grocery_crud->set_default_value($this->current_table,'enrollment_studies_markedForDeletion','n');
                   
        $output = $this->grocery_crud->render();

       /*******************
	   /* HTML HEADER     *
	   /******************/
	   $this->_load_html_header($this->_get_html_header_data(),$output); 
	   
	   /*******************
	   /*      BODY       *
	   /******************/
	   $this->_load_body_header();
	   
		$default_values=$this->_get_default_values();
		$default_values["table_name"]=$this->current_table;
		$default_values["field_prefix"]="enrollment_studies_";
		$this->load->view('defaultvalues_view.php',$default_values); 

        $this->load->view('enrollment/enrollment_studies.php',$output);     
       
       /*******************
	   /*      FOOTER     *
	   *******************/
	   $this->_load_body_footer();	
	}

/* FI ENROLLMENT STUDIES */

/* ENROLLMENT CLASS GROUP */

	public function enrollment_class_group() {

		if (!$this->skeleton_auth->logged_in())
		{
			//redirect them to the login page
			redirect($this->skeleton_auth->login_page, 'refresh');
		}
		
		//CHECK IF USER IS READONLY --> unset add, edit & delete actions
		$readonly_group = $this->config->item('readonly_group');
		if ($this->skeleton_auth->in_group($readonly_group)) {
			$this->grocery_crud->unset_add();
			$this->grocery_crud->unset_edit();
			$this->grocery_crud->unset_delete();
		}

		/* Grocery Crud */
		$this->current_table="enrollment_class_group";
        $this->grocery_crud->set_table($this->current_table);
        $this->session->set_flashdata('table_name', $this->current_table);
        
        //ESTABLISH SUBJECT
        $this->grocery_crud->set_subject(lang('enrollment_class_group'));       

		//Mandatory fields
        

        //CALLBACKS        
        $this->grocery_crud->callback_add_field('enrollment_class_group_entryDate',array($this,'add_field_callback_entryDate'));
        $this->grocery_crud->callback_edit_field('enrollment_class_group_entryDate',array($this,'edit_field_callback_entryDate'));
        
        //Camps last update no editable i automàtic        
        $this->grocery_crud->callback_edit_field('enrollment_class_group_last_update',array($this,'edit_callback_last_update'));

        //Express fields
        

        //COMMON_COLUMNS               
        $this->set_common_columns_name();

        //SPECIFIC COLUMNS
        $this->grocery_crud->display_as('enrollment_class_group_entryDate',lang('entryDate'));        
        $this->grocery_crud->display_as('enrollment_class_group_last_update',lang('last_update'));
        $this->grocery_crud->display_as('enrollment_class_group_creationUserId',lang('creationUserId'));
        $this->grocery_crud->display_as('enrollment_class_group_lastupdateUserId',lang('lastupdateUserId'));          
        $this->grocery_crud->display_as('enrollment_class_group_markedForDeletion',lang('markedForDeletion'));   
        $this->grocery_crud->display_as('enrollment_class_group_markedForDeletionDate',lang('markedForDeletionDate'));        		

        $this->grocery_crud->display_as('enrollment_class_group_periodid',lang('enrollment_class_group_periodid'));
        $this->grocery_crud->display_as('enrollment_class_group_personid',lang('enrollment_class_group_personid'));          
        $this->grocery_crud->display_as('enrollment_class_group_study_id',lang('enrollment_class_group_study_id'));   
        $this->grocery_crud->display_as('enrollment_class_group_group_id',lang('enrollment_class_group_group_id'));        		

        //UPDATE AUTOMATIC FIELDS
		$this->grocery_crud->callback_before_insert(array($this,'before_insert_object_callback'));
		$this->grocery_crud->callback_before_update(array($this,'before_update_object_callback'));
        
        $this->grocery_crud->unset_add_fields('enrollment_class_group_last_update');
   		
   		//USER ID: show only active users and by default select current userid. IMPORTANT: Field is not editable, always forced to current userid by before_insert_object_callback
        $this->grocery_crud->set_relation('enrollment_class_group_creationUserId','users','{username}',array('active' => '1'));
        $this->grocery_crud->set_default_value($this->current_table,'enrollment_class_group_creationUserId',$this->session->userdata('user_id'));

        //LAST UPDATE USER ID: show only active users and by default select current userid. IMPORTANT: Field is not editable, always forced to current userid by before_update_object_callback
        $this->grocery_crud->set_relation('enrollment_class_group_lastupdateUserId','users','{username}',array('active' => '1'));
        $this->grocery_crud->set_default_value($this->current_table,'enrollment_class_group_lastupdateUserId',$this->session->userdata('user_id'));
        
        $this->grocery_crud->unset_dropdowndetails("enrollment_class_group_creationUserId","study_submodules_lastupdateUserId");
   
        $this->set_theme($this->grocery_crud);
        $this->set_dialogforms($this->grocery_crud);
        
        //Default values:
//      $this->grocery_crud->set_default_value($this->current_table,'parentLocation',1);
        //markedForDeletion
        $this->grocery_crud->set_default_value($this->current_table,'enrollment_class_group_markedForDeletion','n');
                   
        $output = $this->grocery_crud->render();

       /*******************
	   /* HTML HEADER     *
	   /******************/
	   $this->_load_html_header($this->_get_html_header_data(),$output); 
	   
	   /*******************
	   /*      BODY       *
	   /******************/
	   $this->_load_body_header();
	   
		$default_values=$this->_get_default_values();
		$default_values["table_name"]=$this->current_table;
		$default_values["field_prefix"]="enrollment_class_group_";
		$this->load->view('defaultvalues_view.php',$default_values); 

        $this->load->view('enrollment/enrollment_class_group.php',$output);     
       
       /*******************
	   /*      FOOTER     *
	   *******************/
	   $this->_load_body_footer();	
	}

/* FI ENROLLMENT CLASS GROUP */

/* ENROLLMENT MODULES */

	public function enrollment_modules() {

		if (!$this->skeleton_auth->logged_in())
		{
			//redirect them to the login page
			redirect($this->skeleton_auth->login_page, 'refresh');
		}
		
		//CHECK IF USER IS READONLY --> unset add, edit & delete actions
		$readonly_group = $this->config->item('readonly_group');
		if ($this->skeleton_auth->in_group($readonly_group)) {
			$this->grocery_crud->unset_add();
			$this->grocery_crud->unset_edit();
			$this->grocery_crud->unset_delete();
		}

		/* Grocery Crud */
		$this->current_table="enrollment_modules";
        $this->grocery_crud->set_table($this->current_table);
        $this->session->set_flashdata('table_name', $this->current_table);
        
        //ESTABLISH SUBJECT
        $this->grocery_crud->set_subject(lang('enrollment_modules'));       

		//Mandatory fields
        

        //CALLBACKS        
        $this->grocery_crud->callback_add_field('enrollment_modules_entryDate',array($this,'add_field_callback_entryDate'));
        $this->grocery_crud->callback_edit_field('enrollment_modules_entryDate',array($this,'edit_field_callback_entryDate'));
        
        //Camps last update no editable i automàtic        
        $this->grocery_crud->callback_edit_field('enrollment_modules_last_update',array($this,'edit_callback_last_update'));

        //Express fields
        

        //COMMON_COLUMNS               
        $this->set_common_columns_name();

        //SPECIFIC COLUMNS
        $this->grocery_crud->display_as('enrollment_modules_entryDate',lang('entryDate'));        
        $this->grocery_crud->display_as('enrollment_modules_last_update',lang('last_update'));
        $this->grocery_crud->display_as('enrollment_modules_creationUserId',lang('creationUserId'));
        $this->grocery_crud->display_as('enrollment_modules_lastupdateUserId',lang('lastupdateUserId'));          
        $this->grocery_crud->display_as('enrollment_modules_markedForDeletion',lang('markedForDeletion'));   
        $this->grocery_crud->display_as('enrollment_modules_markedForDeletionDate',lang('markedForDeletionDate'));        		

        $this->grocery_crud->display_as('enrollment_modules_periodid',lang('enrollment_modules_periodid'));
        $this->grocery_crud->display_as('enrollment_modules_personid',lang('enrollment_modules_personid'));
        $this->grocery_crud->display_as('enrollment_modules_study_id',lang('enrollment_modules_study_id'));          
        $this->grocery_crud->display_as('enrollment_modules_group_id',lang('enrollment_modules_group_id'));   
        $this->grocery_crud->display_as('enrollment_modules_moduleid',lang('enrollment_modules_moduleid'));        		

        //UPDATE AUTOMATIC FIELDS
		$this->grocery_crud->callback_before_insert(array($this,'before_insert_object_callback'));
		$this->grocery_crud->callback_before_update(array($this,'before_update_object_callback'));
        
        $this->grocery_crud->unset_add_fields('enrollment_modules_last_update');
   		
   		//USER ID: show only active users and by default select current userid. IMPORTANT: Field is not editable, always forced to current userid by before_insert_object_callback
        $this->grocery_crud->set_relation('enrollment_modules_creationUserId','users','{username}',array('active' => '1'));
        $this->grocery_crud->set_default_value($this->current_table,'enrollment_modules_creationUserId',$this->session->userdata('user_id'));

        //LAST UPDATE USER ID: show only active users and by default select current userid. IMPORTANT: Field is not editable, always forced to current userid by before_update_object_callback
        $this->grocery_crud->set_relation('enrollment_modules_lastupdateUserId','users','{username}',array('active' => '1'));
        $this->grocery_crud->set_default_value($this->current_table,'enrollment_modules_lastupdateUserId',$this->session->userdata('user_id'));
        
        $this->grocery_crud->unset_dropdowndetails("enrollment_modules_creationUserId","study_submodules_lastupdateUserId");
   
        $this->set_theme($this->grocery_crud);
        $this->set_dialogforms($this->grocery_crud);
        
        //Default values:
//      $this->grocery_crud->set_default_value($this->current_table,'parentLocation',1);
        //markedForDeletion
        $this->grocery_crud->set_default_value($this->current_table,'enrollment_modules_markedForDeletion','n');
                   
        $output = $this->grocery_crud->render();

       /*******************
	   /* HTML HEADER     *
	   /******************/
	   $this->_load_html_header($this->_get_html_header_data(),$output); 
	   
	   /*******************
	   /*      BODY       *
	   /******************/
	   $this->_load_body_header();
	   
		$default_values=$this->_get_default_values();
		$default_values["table_name"]=$this->current_table;
		$default_values["field_prefix"]="enrollment_modules_";
		$this->load->view('defaultvalues_view.php',$default_values); 

        $this->load->view('enrollment/enrollment_modules.php',$output);     
       
       /*******************
	   /*      FOOTER     *
	   *******************/
	   $this->_load_body_footer();	
	}

/* FI ENROLLMENT MODULES */

/* ENROLLMENT SUBMODULES */

	public function enrollment_submodules() {

		if (!$this->skeleton_auth->logged_in())
		{
			//redirect them to the login page
			redirect($this->skeleton_auth->login_page, 'refresh');
		}
		
		//CHECK IF USER IS READONLY --> unset add, edit & delete actions
		$readonly_group = $this->config->item('readonly_group');
		if ($this->skeleton_auth->in_group($readonly_group)) {
			$this->grocery_crud->unset_add();
			$this->grocery_crud->unset_edit();
			$this->grocery_crud->unset_delete();
		}

		/* Grocery Crud */
		$this->current_table="enrollment_submodules";
        $this->grocery_crud->set_table($this->current_table);
        $this->session->set_flashdata('table_name', $this->current_table);
        
        //ESTABLISH SUBJECT
        $this->grocery_crud->set_subject(lang('enrollment_submodules'));       

		//Mandatory fields
        

        //CALLBACKS        
        $this->grocery_crud->callback_add_field('enrollment_submodules_entryDate',array($this,'add_field_callback_entryDate'));
        $this->grocery_crud->callback_edit_field('enrollment_submodules_entryDate',array($this,'edit_field_callback_entryDate'));
        
        //Camps last update no editable i automàtic        
        $this->grocery_crud->callback_edit_field('enrollment_submodules_last_update',array($this,'edit_callback_last_update'));

        //Express fields
        

        //COMMON_COLUMNS               
        $this->set_common_columns_name();

        //SPECIFIC COLUMNS
        $this->grocery_crud->display_as('enrollment_submodules_entryDate',lang('entryDate'));        
        $this->grocery_crud->display_as('enrollment_submodules_last_update',lang('last_update'));
        $this->grocery_crud->display_as('enrollment_submodules_creationUserId',lang('creationUserId'));
        $this->grocery_crud->display_as('enrollment_submodules_lastupdateUserId',lang('lastupdateUserId'));          
        $this->grocery_crud->display_as('enrollment_submodules_markedForDeletion',lang('markedForDeletion'));   
        $this->grocery_crud->display_as('enrollment_submodules_markedForDeletionDate',lang('markedForDeletionDate'));        		

        $this->grocery_crud->display_as('enrollment_submodules_periodid',lang('enrollment_submodules_periodid'));
        $this->grocery_crud->display_as('enrollment_submodules_personid',lang('enrollment_submodules_personid'));
        $this->grocery_crud->display_as('enrollment_submodules_study_id',lang('enrollment_submodules_study_id'));          
        $this->grocery_crud->display_as('enrollment_submodules_group_id',lang('enrollment_submodules_group_id'));   
        $this->grocery_crud->display_as('enrollment_submodules_moduleid',lang('enrollment_submodules_moduleid'));        		
		$this->grocery_crud->display_as('enrollment_submodules_submoduleid',lang('enrollment_submodules_submoduleid'));        		

        //UPDATE AUTOMATIC FIELDS
		$this->grocery_crud->callback_before_insert(array($this,'before_insert_object_callback'));
		$this->grocery_crud->callback_before_update(array($this,'before_update_object_callback'));
        
        $this->grocery_crud->unset_add_fields('enrollment_submodules_last_update');
   		
   		//USER ID: show only active users and by default select current userid. IMPORTANT: Field is not editable, always forced to current userid by before_insert_object_callback
        $this->grocery_crud->set_relation('enrollment_submodules_creationUserId','users','{username}',array('active' => '1'));
        $this->grocery_crud->set_default_value($this->current_table,'enrollment_submodules_creationUserId',$this->session->userdata('user_id'));

        //LAST UPDATE USER ID: show only active users and by default select current userid. IMPORTANT: Field is not editable, always forced to current userid by before_update_object_callback
        $this->grocery_crud->set_relation('enrollment_submodules_lastupdateUserId','users','{username}',array('active' => '1'));
        $this->grocery_crud->set_default_value($this->current_table,'enrollment_submodules_lastupdateUserId',$this->session->userdata('user_id'));
        
        $this->grocery_crud->unset_dropdowndetails("enrollment_submodules_creationUserId","study_submodules_lastupdateUserId");
   
        $this->set_theme($this->grocery_crud);
        $this->set_dialogforms($this->grocery_crud);
        
        //Default values:
//      $this->grocery_crud->set_default_value($this->current_table,'parentLocation',1);
        //markedForDeletion
        $this->grocery_crud->set_default_value($this->current_table,'enrollment_submodules_markedForDeletion','n');
                   
        $output = $this->grocery_crud->render();

       /*******************
	   /* HTML HEADER     *
	   /******************/
	   $this->_load_html_header($this->_get_html_header_data(),$output); 
	   
	   /*******************
	   /*      BODY       *
	   /******************/
	   $this->_load_body_header();
	   
		$default_values=$this->_get_default_values();
		$default_values["table_name"]=$this->current_table;
		$default_values["field_prefix"]="enrollment_submodules_";
		$this->load->view('defaultvalues_view.php',$default_values); 

        $this->load->view('enrollment/enrollment_submodules.php',$output);     
       
       /*******************
	   /*      FOOTER     *
	   *******************/
	   $this->_load_body_footer();	
	}

/* FI ENROLLMENT SUBMODULES */

	private function set_header_data() {

		$header_data= $this->add_css_to_html_header_data(
			$this->_get_html_header_data(),
			base_url('assets/grocery_crud/css/jquery_plugins/chosen/chosen.css'));	
		$header_data= $this->add_css_to_html_header_data(
			$header_data,
			'http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css');	
		$header_data= $this->add_css_to_html_header_data(
			$header_data,
			base_url('assets/css/jquery-ui.css'));		
		$header_data= $this->add_css_to_html_header_data(
			$header_data,
			base_url('assets/grocery_crud/themes/datatables/extras/TableTools/media/css/TableTools.css'));	
		$header_data= $this->add_css_to_html_header_data(
			$header_data,
			base_url('assets/css/tooltipster.css'));			
		//JS
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			base_url("assets/grocery_crud/js/jquery_plugins/ui/jquery-ui-1.10.3.custom.min.js"));			
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			base_url("assets/grocery_crud/js/jquery_plugins/jquery.chosen.min.js"));			
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			"http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js");						
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			base_url("assets/grocery_crud/themes/datatables/extras/TableTools/media/js/TableTools.js"));
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			base_url("assets/grocery_crud/themes/datatables/extras/TableTools/media/js/ZeroClipboard.js"));				
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			base_url("assets/js/jquery.tooltipster.min.js"));		
			
		$this->_load_html_header($header_data); 
		
		$this->_load_body_header();		

	}

public function add_callback_last_update(){  
   
    return '<input type="text" class="datetime-input hasDatepicker" maxlength="19" name="'.$this->session->flashdata('table_name').'_last_update" id="field-last_update" readonly>';
}

public function add_field_callback_entryDate(){  
      $data= date('d/m/Y H:i:s', time());
      return '<input type="text" class="datetime-input hasDatepicker" maxlength="19" value="'.$data.'" name="'.$this->session->flashdata('table_name').'_entryDate" id="field-entryDate" readonly>';    
}

public function edit_field_callback_entryDate($value, $primary_key){  
    //$this->session->flashdata('table_name');
      return '<input type="text" class="datetime-input hasDatepicker" maxlength="19" value="'. date('d/m/Y H:i:s', strtotime($value)) .'" name="'.$this->session->flashdata('table_name').'_entryDate" id="field-entryDate" readonly>';    
    }
    
public function edit_callback_last_update($value, $primary_key){ 
    //$this->session->flashdata('table_name'); 
     return '<input type="text" class="datetime-input hasDatepicker" maxlength="19" value="'. date('d/m/Y H:i:s', time()) .'"  name="'.$this->session->flashdata('table_name').'_last_update" id="field-last_update" readonly>';
    }    

//UPDATE AUTOMATIC FIELDS BEFORE INSERT
function before_insert_object_callback($post_array, $primary_key) {
        //UPDATE LAST UPDATE FIELD
        $data= date('d/m/Y H:i:s', time());
        $post_array['entryDate'] = $data;
        
        $post_array['creationUserId'] = $this->session->userdata('user_id');
        return $post_array;
}

//UPDATE AUTOMATIC FIELDS BEFORE UPDATE
function before_update_object_callback($post_array, $primary_key) {
        //UPDATE LAST UPDATE FIELD
        $data= date('d/m/Y H:i:s', time());
        $post_array['last_update'] = $data;
        
        $post_array['lastupdateUserId'] = $this->session->userdata('user_id');
        return $post_array;
}

}
