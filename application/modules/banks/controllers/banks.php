<?php defined('BASEPATH') OR exit('No direct script access allowed');

include "application/third_party/skeleton/application/controllers/skeleton_main.php";


class banks extends skeleton_main {
	
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
		$this->load->add_package_path(APPPATH.'third_party/skeleton/application/');


		$current_language=$this->session->userdata("current_language");
		if ($current_language == "") {
			$current_language= $this->config->item('default_language');
		}

		$this->lang->load('banks', $current_language);

		$this->load->library('skeleton_auth');
	}
	
	
	public function index() {
		$this->bank();
	}

	//UPDATE AUTOMATIC FIELDS BEFORE INSERT
    function before_insert_object_callback($post_array, $primary_key) {
		//UPDATE LAST UPDATE FIELD
		$data= date('d/m/Y H:i:s', time());
		$post_array['bank_account_last_update'] = $data;
		$post_array['bank_account_entryDate'] = $data;
		
		$user_id=$this->session->userdata('user_id');
		$post_array['bank_account_creationUserId'] = $user_id;
		$post_array['bank_account_lastupdateUserId'] = $user_id;
		
		return $post_array;
    }

    //UPDATE AUTOMATIC FIELDS BEFORE INSERT
    function before_insert_user_preference_callback($post_array, $primary_key) {
		//UPDATE LAST UPDATE FIELD
		$data= date('d/m/Y H:i:s', time());
		$post_array['bank_account_last_update'] = $data;
		$post_array['bank_account_entryDate'] = $data;
		
		$user_id=$this->session->userdata('user_id');
		$post_array['userId'] = $user_id;
		$post_array['bank_account_creationUserId'] = $user_id;
		$post_array['bank_account_lastupdateUserId'] = $user_id;
		
		
		return $post_array;
    }

    //UPDATE AUTOMATIC FIELDS BEFORE UPDATE
    function before_update_object_callback($post_array, $primary_key) {
		//UPDATE LAST UPDATE FIELD
		$data= date('d/m/Y H:i:s', time());
		$post_array['bank_account_last_update'] = $data;
		
		$post_array['bank_account_lastupdateUserId'] = $this->session->userdata('user_id');
		return $post_array;
	}
    
    //UPDATE AUTOMATIC FIELDS BEFORE UPDATE
    // ONLY CALLED BY USERS NOT ADMINS!
    function before_update_user_preference_callback($post_array, $primary_key) {
		//UPDATE LAST UPDATE FIELD
		$data= date('d/m/Y H:i:s', time());
		$post_array['bank_account_last_update'] = $data;
		
		$user_id=$this->session->userdata('user_id');
		$post_array['userId'] = $user_id;
		$post_array['bank_account_lastupdateUserId'] = $user_id;
		return $post_array;
    }
	
	public function bank_account() {
		$table_name="bank_account";
        $this->grocery_crud->set_table($table_name);  
		
		//Establish subject:
        $this->grocery_crud->set_subject("Compte");

        //echo "user:".$this->session->userdata('user_id');;

		$this->grocery_crud->columns('bank_account_id','bank_account_owner_id','bank_account_type_id','bank_account_entity_code',
			'bank_account_office_code','bank_account_control_digit_code','bank_account_number','bank_account_entryDate',
			'bank_account_last_update','bank_account_creationUserId','bank_account_lastupdateUserId','bank_account_markedForDeletion',
			'bank_account_markedForDeletionDate');

		$this->grocery_crud->add_fields('bank_account_owner_id','bank_account_type_id','bank_account_entity_code',
			'bank_account_office_code','bank_account_control_digit_code','bank_account_number','bank_account_entryDate',
			'bank_account_creationUserId','bank_account_lastupdateUserId','bank_account_markedForDeletion',
			'bank_account_markedForDeletionDate');        

		$this->grocery_crud->edit_fields('bank_account_owner_id','bank_account_type_id','bank_account_entity_code',
			'bank_account_office_code','bank_account_control_digit_code','bank_account_number','bank_account_entryDate',
			'bank_account_last_update','bank_account_creationUserId','bank_account_lastupdateUserId','bank_account_markedForDeletion',
			'bank_account_markedForDeletionDate');
        
        $this->grocery_crud->display_as('bank_account_id',lang('bank_account_id'));
       	$this->grocery_crud->display_as('bank_account_owner_id',lang('bank_account_owner_id'));       
       	$this->grocery_crud->display_as('bank_account_type_id',lang('bank_account_type_id'));
       	$this->grocery_crud->display_as('bank_account_entity_code',lang('bank_account_entity_code'));
       	$this->grocery_crud->display_as('bank_account_office_code',lang('bank_account_office_code'));
       	$this->grocery_crud->display_as('bank_account_control_digit_code',lang('bank_account_control_digit_code'));
       	$this->grocery_crud->display_as('bank_account_number',lang('bank_account_number'));
       	$this->grocery_crud->display_as('bank_account_type_id',lang('bank_account_type_id'));
       	$this->grocery_crud->display_as('bank_account_entryDate',lang('bank_account_entryDate'));
       	$this->grocery_crud->display_as('bank_account_last_update',lang('bank_account_last_update'));
       	$this->grocery_crud->display_as('bank_account_creationUserId',lang('bank_account_creationUserId'));
       	$this->grocery_crud->display_as('bank_account_lastupdateUserId',lang('bank_account_lastupdateUserId'));
       	$this->grocery_crud->display_as('bank_account_markedForDeletion',lang('bank_account_markedForDeletion'));
       	$this->grocery_crud->display_as('bank_account_markedForDeletionDate',lang('bank_account_markedForDeletionDate'));
       	$this->grocery_crud->display_as('bank_account_num_persona',lang('bank_account_num_persona'));

  		$this->grocery_crud->unset_dropdowndetails("bank_account_type_id");

  		//Mandatory fields
        //$this->grocery_crud->required_fields('name','shortName','location','markedForDeletion');
        //$this->grocery_crud->required_fields('externalCode','name','shortName','location','markedForDeletion');
        
        //Express fields
        //$this->grocery_crud->express_fields('name','shortName');


   	    $this->grocery_crud->set_default_value($table_name,'bank_account_type_id',1);
   	    $this->grocery_crud->set_default_value($table_name,'bank_account_entity_code',2100);
   	    $this->grocery_crud->set_default_value($table_name,'bank_account_markedForDeletion','n');

   	    $complete_ccc=$this->input->post('bank_account_entity_code').$this->input->post('bank_account_office_code').$this->input->post('bank_account_control_digit_code').$this->input->post('bank_account_number');
   	    
   	    // IF ACCOUNT IS CCC
   	    if ($this->input->post('bank_account_type_id') == 1 ) {
   	    	$this->grocery_crud->set_rules('bank_account_number',lang('bank_account_number'),'callback_ccc_valido['. $complete_ccc . ']');	
   	    }
   	    
   	    $this->grocery_crud->set_relation('bank_account_owner_id','person','{person_sn1} {person_sn2},{person_givenName} ({person_official_id}) - {person_id} ');    
		$this->grocery_crud->set_relation('bank_account_type_id','bank_account_type','{bank_account_type_name}');
        $this->grocery_crud->set_relation('bank_account_entity_code','bank','{bank_code}-{bank_name}');
        //$this->grocery_crud->set_relation('bank_account_office_code','bank_office','{bank_office_code}-{bank_office_name}');


		//ENTRY DATE
		//DEFAULT VALUE=NOW. ONLY WHEN ADDING
		//EDITING: SHOW CURRENT VALUE READONLY
		$this->grocery_crud->callback_add_field('entryDate',array($this,'add_field_callback_entryDate'));
		$this->grocery_crud->callback_edit_field('entryDate',array($this,'edit_field_callback_entryDate'));
		
		//LAST UPDATE
		//DEFAULT VALUE=NOW. ONLY WHEN ADDING
		//EDITING: SHOW CURRENT VALUE READONLY
		$this->grocery_crud->callback_add_field('last_update',array($this,'add_callback_last_update'));
		$this->grocery_crud->callback_edit_field('last_update',array($this,'edit_callback_last_update'));
		
		$admin_group = ($this->config->item('admin_group') == "") ? 'intranet_admin' : $this->config->item('admin_group');
		
		//UPDATE AUTOMATIC FIELDS
		if ($this->skeleton_auth->in_group($admin_group)) {
			$this->grocery_crud->callback_before_insert(array($this,'before_insert_object_callback'));
			$this->grocery_crud->callback_before_update(array($this,'before_update_object_callback'));
		} else {
			//If not admin user, force UserId always to be the userid of actual user
			$this->grocery_crud->callback_before_insert(array($this,'before_insert_user_preference_callback'));
			$this->grocery_crud->callback_before_update(array($this,'before_update_user_preference_callback'));
		}
		
        //USER ID: show only active users and by default select current userid. IMPORTANT: Field is not editable, always forced to current userid by before_insert_object_callback
        $this->grocery_crud->set_relation('bank_account_creationUserId','users','{username}',array('active' => '1'));
        $this->grocery_crud->set_default_value($table_name,'bank_account_creationUserId',$this->session->userdata('user_id'));

        //LAST UPDATE USER ID: show only active users and by default select current userid. IMPORTANT: Field is not editable, always forced to current userid by before_update_object_callback
        $this->grocery_crud->set_relation('bank_account_lastupdateUserId','users','{username}',array('active' => '1'));
        $this->grocery_crud->set_default_value($table_name,'bank_account_lastupdateUserId',$this->session->userdata('user_id'));

        $this->grocery_crud->unset_dropdowndetails("bank_account_creationUserId","bank_account_lastupdateUserId");
        
        $output = $this->grocery_crud->render();
		
		$this->_load_html_header($this->_get_html_header_data(),$output); 
		$this->_load_body_header();
	
		$this->load->view('bank_account',$output); 
                
		$this->_load_body_footer();	 	
	}

	public function ccc_valido($field,$ccc)	{
    	//EJEMPLO de $ccc sería el 20770338793100254321
    	$valido = true;
    	
    	$this->form_validation->set_message(__FUNCTION__, lang("bank_account_incorrect") . "(".$ccc.")");

    	///////////////////////////////////////////////////
    	//    Dígito de control de la entidad y sucursal:
    	//Se multiplica cada dígito por su factor de peso
    	///////////////////////////////////////////////////
    	$suma = 0;
    	$suma += $ccc[0] * 4;
    	$suma += $ccc[1] * 8;
    	$suma += $ccc[2] * 5;
    	$suma += $ccc[3] * 10;
    	$suma += $ccc[4] * 9;
    	$suma += $ccc[5] * 7;
	    $suma += $ccc[6] * 3;
    	$suma += $ccc[7] * 6;

    	$division = floor($suma/11);
    	$resto    = $suma - ($division  * 11);
    	$primer_digito_control = 11 - $resto;
    	if($primer_digito_control == 11)
	        $primer_digito_control = 0;

	    if($primer_digito_control == 10)
        	$primer_digito_control = 1;

	    if($primer_digito_control != $ccc[8])
        	$valido = false;

    	///////////////////////////////////////////////////
    	//            Dígito de control de la cuenta:
    	///////////////////////////////////////////////////
    	$suma = 0;
    	$suma += $ccc[10] * 1;
    	$suma += $ccc[11] * 2;
    	$suma += $ccc[12] * 4;
    	$suma += $ccc[13] * 8;
    	$suma += $ccc[14] * 5;
    	$suma += $ccc[15] * 10;
    	$suma += $ccc[16] * 9;
	    $suma += $ccc[17] * 7;
	    $suma += $ccc[18] * 3;
    	$suma += $ccc[19] * 6;

    	$division = floor($suma/11);
    	$resto = $suma-($division  * 11);
    	$segundo_digito_control = 11- $resto;

	    if($segundo_digito_control == 11)
        	$segundo_digito_control = 0;
    	if($segundo_digito_control == 10)
        	$segundo_digito_control = 1;

    	if($segundo_digito_control != $ccc[9])
    	    $valido = false;

    	return $valido;
	}

	
	public function bank() {
		$table_name="bank";
        $this->grocery_crud->set_table($table_name);  
		
		//Establish subject:
        $this->grocery_crud->set_subject("banc");
        
        $output = $this->grocery_crud->render();
		
		$this->_load_html_header($this->_get_html_header_data(),$output); 
		$this->_load_body_header();
	
		$this->load->view('bank',$output); 
                
		$this->_load_body_footer();	 	
	}				
	
	public function bank_account_type() {
		$table_name="bank_account_type";
        $this->grocery_crud->set_table($table_name);  
		
		//Establish subject:
        $this->grocery_crud->set_subject("Tipus de Compte");
        
        $output = $this->grocery_crud->render();
		
		$this->_load_html_header($this->_get_html_header_data(),$output); 
		$this->_load_body_header();
	
		$this->load->view('bank_account_type',$output); 
                
		$this->_load_body_footer();	 	
	}
	
	public function bank_office() {
		$table_name="bank_office";
        $this->grocery_crud->set_table($table_name);  
		
		//Establish subject:
        $this->grocery_crud->set_subject("Oficina");
        
        $this->grocery_crud->set_relation('bank_office_bank_id','bank','{bank_code}-{bank_name}');
        
        
        $output = $this->grocery_crud->render();
		
		$this->_load_html_header($this->_get_html_header_data(),$output); 
		$this->_load_body_header();
	
		$this->load->view('bank_office',$output); 
                
		$this->_load_body_footer();	 	
	}				
	
}
