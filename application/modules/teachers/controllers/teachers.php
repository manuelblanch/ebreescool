<?php defined('BASEPATH') OR exit('No direct script access allowed');

include "application/third_party/skeleton/application/controllers/skeleton_main.php";


class teachers extends skeleton_main {
	
	public $body_header_view ='include/ebre_escool_body_header.php' ;

  public $body_header_lang_file ='ebre_escool_body_header' ;
	
	function __construct()
    {
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
		    $this->lang->load('teachers', $current_language);	       

        //LANGUAGE HELPER:
        $this->load->helper('language');
	}

	public function teacher() {
		
        $table_name="teacher";
        $this->session->set_flashdata('table_name', $table_name.'_');    
        $this->grocery_crud->set_table($table_name);  
        
	    //Establish subject:
        $this->grocery_crud->set_subject(lang('teacher'));

        //RELATIONS
        $this->grocery_crud->set_relation('teacher_person_id','person','{person_sn1} {person_sn2},{person_givenName} ({person_official_id}) - {person_id} '); 
        
        //$this->grocery_crud->unset_dropdowndetails("person_official_id_type");
        
        //$this->grocery_crud->columns('person_id','person_sn1','person_sn2','person_givenName','person_official_id','person_homePostalAddress','person_locality_id','person_email','person_telephoneNumber','person_mobile','person_gender','person_bank_account_id');

        /*$this->grocery_crud->add_fields('person_official_id_type','person_official_id','person_sn1','person_sn2','person_givenName','person_email','person_homePostalAddress','person_gender',
        	'person_locality_id','person_telephoneNumber','person_mobile','person_date_of_birth','person_bank_account_id','person_notes','person_entryDate','person_creationUserId',
        	'person_markedForDeletion','person_markedForDeletionDate');

        $this->grocery_crud->edit_fields('person_official_id_type','person_official_id','person_sn1','person_sn2','person_givenName','person_email','person_homePostalAddress','person_gender',
        	'person_locality_id','person_telephoneNumber','person_mobile','person_date_of_birth','person_bank_account_id','person_notes','person_entryDate','person_last_update','person_creationUserId',
        	'person_lastupdateUserId','person_markedForDeletion','person_markedForDeletionDate');

        $this->grocery_crud->unset_dropdowndetails("person_official_id_type");
        */

        //COLUMN NAMES
        $this->grocery_crud->display_as('teacher_person_id',lang('teacher_person_id'));          
        $this->grocery_crud->display_as('teacher_code',lang('teacher_code'));  
        $this->grocery_crud->display_as('teacher_entryDate',lang('entryDate'));        
        $this->grocery_crud->display_as('teacher_last_update',lang('last_update'));
        $this->grocery_crud->display_as('teacher_creationUserId',lang('creationUserId'));
        $this->grocery_crud->display_as('teacher_lastupdateUserId',lang('lastupdateUserId'));          
        $this->grocery_crud->display_as('teacher_markedForDeletion',lang('markedForDeletion'));   
        $this->grocery_crud->display_as('teacher_markedForDeletionDate',lang('markedForDeletionDate'));              

        //$this->grocery_crud->display_as('person_id',lang('person_id'));
       	//$this->grocery_crud->display_as('person_givenName',lang('person_givenName'));       
       	//$this->grocery_crud->display_as('person_sn1',lang('person_sn1'));       
       	
        //DEFAULT VALUES

        $this->grocery_crud->set_default_value($table_name,'teacher_markedForDeletion','n');
        //$this->grocery_crud->set_default_value($table_name,'teacher_creationUserId','TODO');
        //$this->grocery_crud->set_default_value($table_name,'person_markedForDeletion','n');

        //CALLBACKS
//-->
    //Camps last update no editable i automàtic  
        $this->grocery_crud->callback_add_field($table_name.'_entryDate',array($this,'add_field_callback_entryDate')); 
        //$this->grocery_crud->callback_add_field($table_name.'_last_update',array($this,'add_field_callback_last_update'));     
        $this->grocery_crud->callback_edit_field($table_name.'_entryDate',array($this,'edit_field_callback_entryDate'));
        $this->grocery_crud->callback_edit_field($table_name.'_last_update',array($this,'edit_callback_last_update'));
        $this->grocery_crud->callback_before_update(array($this,'before_update_last_update'));
//<--        
        //$this->grocery_crud->callback_add_field('teacher_entryDate',array($this,'add_field_callback_entryDate'));
        //$this->grocery_crud->callback_edit_field('teacher_entryDate',array($this,'edit_field_callback_entryDate'));

       	//$this->grocery_crud->set_rules('person_official_id',lang('person_official_id'),'callback_valida_nif_cif_nie['.$this->input->post('person_official_id_type').']');
        //$this->grocery_crud->set_rules('person_email',lang('person_email'),'valid_email');
       	
        //USER ID: show only active users and by default select current userid. IMPORTANT: Field is not editable, always forced to current userid by before_insert_object_callback
        $this->grocery_crud->set_relation('teacher_creationUserId','users','{username}',array('active' => '1'));
        $this->grocery_crud->set_default_value($table_name,'teacher_creationUserId',$this->session->userdata('user_id'));

        //LAST UPDATE USER ID: show only active users and by default select current userid. IMPORTANT: Field is not editable, always forced to current userid by before_update_object_callback
        $this->grocery_crud->set_relation('teacher_lastupdateUserId','users','{username}',array('active' => '1'));
        $this->grocery_crud->set_default_value($table_name,'teacher_lastupdateUserId',$this->session->userdata('user_id'));
        
        $this->grocery_crud->unset_dropdowndetails("teacher_creationUserId","teacher_lastupdateUserId");

        $output = $this->grocery_crud->render();
		
		    $this->_load_html_header($this->_get_html_header_data(),$output); 
		    $this->_load_body_header();
        	
            $default_values=$this->_get_default_values();
            $default_values["table_name"]=$table_name;
            $default_values["field_prefix"]="teacher_";
            $this->load->view('defaultvalues_view.php',$default_values); 

		    $this->load->view('teachers',$output); 
                
		    $this->_load_body_footer();	 
	}

//-->


  public function edit_field_callback_entryDate($value, $primary_key){
    //return '<input type="text" class="datetime-input hasDatepicker" maxlength="19" value="'. date('d/m/Y H:i:s', strtotime($value)) .'" name="person_entryDate" id="field-entryDate" readonly>';    
    return '<input type="text" class="datetime-input hasDatepicker" maxlength="19" value="'. date('d/m/Y H:i:s', strtotime($value)) .'" name="'.$this->session->flashdata('table_name').'entryDate" id="field-entryDate" readonly>';    
  }

  public function edit_callback_last_update($value, $primary_key){

    $data = date('d/m/Y H:i:s', time());
    //return '<input type="text" class="datetime-input hasDatepicker" maxlength="19" value="'. $data .'"  name="person_last_update" id="field-last_update" readonly>';
    return '<input type="text" class="datetime-input hasDatepicker" maxlength="19" value="'. $data .'"  name="'.$this->session->flashdata('table_name').'last_update" id="field-last_update" readonly>';

  }

  public function before_update_last_update($post_array, $primary_key) {
    $data= date('d/m/Y H:i:s', time());
    //$post_array['person_last_update'] = $data;
    $post_array[$this->session->flashdata('table_name').'last_update'] = $data;
    //$post_array['lastupdateUserId'] = $this->session->userdata('user_id');
    return $post_array;
}  

public function add_field_callback_entryDate(){  

    $data= date('d/m/Y H:i:s', time());
    //return '<input type="text" class="datetime-input hasDatepicker" maxlength="19" value="'.$data.'" name="person_entryDate" id="field-entryDate" readonly>';    
    return '<input type="text" class="datetime-input hasDatepicker" maxlength="19" value="'.$data.'" name="'.$this->session->flashdata('table_name').'entryDate" id="field-entryDate" readonly>';    
}

//<--    


	protected function _unique_field_name($field_name)
    {
    	return 's'.substr(md5($field_name),0,8); //This s is because is better for a string to begin with a letter and not with a number
    }

	
	public function index() {
		$this->teacher();
	}

}
