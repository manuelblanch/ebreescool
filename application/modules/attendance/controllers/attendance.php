<?php defined('BASEPATH') OR exit('No direct script access allowed');

include "application/third_party/skeleton/application/controllers/skeleton_main.php";

class attendance extends skeleton_main {

	public $body_header_view ='include/ebre_escool_body_header.php' ;

	public $body_header_lang_file ='ebre_escool_body_header' ;
	
	function __construct()
    {
        parent::__construct();
        
        $this->load->model('attendance_model');
        $this->load->library('ebre_escool_ldap');

        //GROCERY CRUD
		$this->load->add_package_path(APPPATH.'third_party/grocery-crud/application/');
        $this->load->library('grocery_CRUD');
        $this->load->add_package_path(APPPATH.'third_party/image-crud/application/');
		$this->load->library('image_CRUD');  

		/* Set language */
		$current_language=$this->session->userdata("current_language");
		if ($current_language == "") {
			$current_language= $this->config->item('default_language','skeleton_auth');
		}
		$this->grocery_crud->set_language($current_language);
    	$this->lang->load('skeleton', $current_language);	       
    	
    	$this->lang->load('attendance', $current_language);	
    	
		$this->lang->load('managment', $current_language);        
        
	}

	public function time_slots () {

		$table_name="time_slot";
        $this->grocery_crud->set_table($table_name);  
		
		//Establish subject:
        $this->grocery_crud->set_subject(lang("time_slot"));

        //RELATIONS
        //$this->grocery_crud->set_relation('person_official_id_type','person_official_id_type','{person_official_id_type_shortname} - {person_official_id_type_id}',null,null,"persons");
        

        $this->grocery_crud->display_as('time_slot_id',lang('time_slot_id'));
       	$this->grocery_crud->display_as('time_slot_start_time',lang('time_slot_start_time'));       
       	$this->grocery_crud->display_as('time_slot_end_time',lang('time_slot_end_time'));       
       	$this->grocery_crud->display_as('time_slot_entryDate',lang('time_slot_entryDate'));
       	$this->grocery_crud->display_as('time_slot_last_update',lang('time_slot_last_update'));
       	$this->grocery_crud->display_as('time_slot_creationUserId',lang('time_slot_creationUserId'));
       	$this->grocery_crud->display_as('time_slot_lastupdateUserId',lang('time_slot_lastupdateUserId'));
       	$this->grocery_crud->display_as('time_slot_markedForDeletion',lang('time_slot_markedForDeletion'));
       	$this->grocery_crud->display_as('time_slot_markedForDeletionDate',lang('time_slot_markedForDeletionDate'));

        //$this->grocery_crud->set_default_value($table_name,'person_creationUserId','TODO');
        $this->grocery_crud->set_default_value($table_name,'person_markedForDeletion','n');


        $output = $this->grocery_crud->render();
		
		$this->_load_html_header($this->_get_html_header_data(),$output); 
		$this->_load_body_header();
	
		$this->load->view('time_slots',$output); 
                
		$this->_load_body_footer();	 	
	}

/* proves ajax, json */

	public function prova () {

		//CSS
		$header_data= $this->add_css_to_html_header_data(
			$this->_get_html_header_data(),
			"http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css");
			
		$this->_load_html_header($header_data); 
		$this->_load_body_header();
        $this->load->view('attendance/prova.php');    
		$this->_load_body_footer();	
	}

	public function read($table=null){

		$this->db->select('alumne, incidencia, data, hora');
		$this->db->where('alumne', $_POST['alumne']); 
		$this->db->where('hora', $_POST['hora']);
		$query = $this->db->get($table);
		$resultat = array();
		$resultat[] = "Alumne  - Incidencia - Data - Hora";

		foreach ($query->result() as $row)
		{
		    $resultat[] = $row->alumne ." - ".$row->incidencia." - ".$row->data." - ".$row->hora;
		}
		print_r(json_encode($resultat));
	}	

	public function insert($table=null){

		//echo $table;
		$this->db->insert($table, $_POST); 
		$rows = $this->db->affected_rows();
		print_r(json_encode($this->db->affected_rows()));
		//$this->db->insert($table, $data); 
		//print_r(json_encode($data));
	}		

	public function update(){

		$data = array(
           'cycle_shortname' => 'cic mod 1',
           'cycle_name' => 'cicle modificat 1',
           'cycle_entryDate' => date("Y-m-d H:i:s")
        );

		$this->db->where('cycle_id', '6');
		$this->db->update('cycle', $data); 
		print_r(json_encode($data));
	}	

	public function delete(){
		$data = array(
			'Esborrat' => 'id 8'		
		);
		$this->db->where('cycle_id', '8');
		$this->db->delete('cycle'); 
		print_r(json_encode($data));
	}	

/* fi proves ajax json */	

	
	public function mentoring_groups ( $class_room_group_id = null ) {

		if (!$this->skeleton_auth->logged_in())
		{
			//redirect them to the login page
			redirect($this->skeleton_auth->login_page, 'refresh');
		}
		
		$header_data= $this->add_css_to_html_header_data(
			$this->_get_html_header_data(),
			"http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css");
		$header_data= $this->add_css_to_html_header_data(
			$header_data,
			"http://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/jqueryui-editable/css/jqueryui-editable.css");
		$header_data= $this->add_css_to_html_header_data(
            $header_data,
            base_url('assets/css/datepicker.css'));  
		$header_data= $this->add_css_to_html_header_data(
			$header_data,
			"http://cdn.jsdelivr.net/select2/3.4.5/select2.css");
		$header_data= $this->add_css_to_html_header_data(
			$header_data,
            base_url('assets/css/tribal-timetable.css')); 
		$header_data= $this->add_css_to_html_header_data(
			$header_data,
            "http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css"); 

		//JS
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			"http://code.jquery.com/jquery-1.9.1.js");
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			"http://code.jquery.com/ui/1.10.3/jquery-ui.js");	
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			"http://cdn.jsdelivr.net/select2/3.4.5/select2.js");
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			"http://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/jqueryui-editable/js/jqueryui-editable.min.js");
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			base_url('assets/js/bootstrap-datepicker.js'));
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			base_url('assets/js/bootstrap-datepicker.ca.js'));
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			base_url('assets/js/bootstrap-datepicker.es.js'));
		$header_data= $this->add_javascript_to_html_header_data(
            $header_data,
            base_url('assets/js/bootstrap-tooltip.js'));
        $header_data= $this->add_javascript_to_html_header_data(
            $header_data,
            base_url('assets/js/bootstrap-collapse.js'));                
        $header_data= $this->add_javascript_to_html_header_data(
            $header_data,
            base_url('assets/js/tribal.js'));
        $header_data= $this->add_javascript_to_html_header_data(
            $header_data,
            base_url('assets/js/tribal-shared.js'));        
        $header_data= $this->add_javascript_to_html_header_data(
            $header_data,
            base_url('assets/js/tribal-timetable.js'));
        $header_data= $this->add_javascript_to_html_header_data(
            $header_data,
            "http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js");
	
		$this->_load_html_header($header_data); 
		
		$this->_load_body_header();

		//Check if user is manager -> Show all groups

		// IF USER IS NOT MANAGER -> IS MENTOR? -> SHOW GROUPS user is mentor

		$data = array();
		$data['default_classroom_group_id'] = 2;

		$data['check_attendance_date'] = date('d/m/Y');

		if ( $class_room_group_id != null ) {
			$data['default_classroom_group_id'] = $class_room_group_id;			
		}

		
		//$data['classroom_groups'] = array ( 1 => "Grup1" , 2 => "Grup 2", 3 => "Grup 3");
		$data['classroom_groups'] = $this->attendance_model->get_all_groups();

		$this->load->view('mentoring_groups',$data);	

		$this->_load_body_footer();		

	}

	public function mentoring_attendance_by_student () {
		$header_data= $this->add_css_to_html_header_data(
			$this->_get_html_header_data(),
			"http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css");
		//JS
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			"http://code.jquery.com/jquery-1.9.1.js");
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			"http://code.jquery.com/ui/1.10.3/jquery-ui.js");	
			
		$this->_load_html_header($header_data); 
		
		$this->_load_body_header();
		
		echo "TODO";	

		$this->_load_body_footer();		
	}
	
	public function pdf_exemple() {
		$this->load->add_package_path(APPPATH.'third_party/fpdf-codeigniter/application/');
		#$this->load->library('fpdf');
		$this->load->library('fpdf');
		$pdf=new FPDF();
		
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',16);
		$pdf->Image('http://fpdf.org/logo.gif');
		$pdf->Cell(0,10,utf8_decode('¡Hola, Món!'),1,2,'L');
		$pdf->Output();
	}




	public function classroom_groups() {
		//Cargar la llibreria fpdf
		$this->load->add_package_path(APPPATH.'third_party/fpdf-codeigniter/application/');
		#$this->load->library('fpdf');
		$this->load->library('pdf');
		$pdf=new PDF();




		if (!$this->skeleton_auth->logged_in())
		{
			//redirect them to the login page
			redirect($this->skeleton_auth->login_page, 'refresh');
		}

		$header_data= $this->add_css_to_html_header_data(
			$this->_get_html_header_data(),
			"http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css");
		//JS
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			"http://code.jquery.com/jquery-1.9.1.js");
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			"http://code.jquery.com/ui/1.10.3/jquery-ui.js");	
			

		$this->current_table="classroom_group";
        $this->grocery_crud->set_table($this->current_table);
        $this->session->set_flashdata('table_name', $this->current_table);


        //ESTABLISH SUBJECT        
        $this->grocery_crud->set_subject(lang('ClassroomGroup'));

        //COMMON_COLUMNS               
        $this->set_common_columns_name();

		//Mandatory fields
        $this->grocery_crud->required_fields('classroom_group_code','classroom_group_name','classroom_group_shortName','classroom_group_markedForDeletion');
        
        //express fields
        $this->grocery_crud->express_fields('name','shortName','code');

        //Camps last update no editable i automàtic        
        //$this->grocery_crud->callback_add_field('classroom_group_last_update',array($this,'add_callback_last_update'));
      
        //CALLBACKS        
        $this->grocery_crud->callback_add_field('classroom_group_entryDate',array($this,'add_field_callback_entryDate'));
        $this->grocery_crud->callback_edit_field('classroom_group_entryDate',array($this,'edit_field_callback_entryDate'));
        
        //Camps last update no editable i automàtic        
        $this->grocery_crud->callback_edit_field('classroom_group_last_update',array($this,'edit_callback_last_update'));
        
        //UPDATE AUTOMATIC FIELDS
		$this->grocery_crud->callback_before_insert(array($this,'before_insert_object_callback'));
		$this->grocery_crud->callback_before_update(array($this,'before_update_object_callback'));
        
   		$this->grocery_crud->unset_add_fields('classroom_group_last_update');
        
   		//USER ID: show only active users and by default select current userid. IMPORTANT: Field is not editable, always forced to current userid by before_insert_object_callback
        $this->grocery_crud->set_relation('classroom_group_creationUserId','users','{username}',array('active' => '1'));
        $this->grocery_crud->set_default_value($this->current_table,'classroom_group_creationUserId',$this->session->userdata('user_id'));

        //LAST UPDATE USER ID: show only active users and by default select current userid. IMPORTANT: Field is not editable, always forced to current userid by before_update_object_callback
        $this->grocery_crud->set_relation('classroom_group_lastupdateUserId','users','{username}',array('active' => '1'));
        $this->grocery_crud->set_default_value($this->current_table,'classroom_group_lastupdateUserId',$this->session->userdata('user_id'));
        
        $this->grocery_crud->unset_dropdowndetails("classroom_group_creationUserId","classroom_group_lastupdateUserId","classroom_group_parentLocation");
        
        $this->set_theme($this->grocery_crud);
        $this->set_dialogforms($this->grocery_crud);
        
        //Default values:
        $this->grocery_crud->set_default_value($this->current_table,'classroom_group_parentLocation',1);
        
        //markedForDeletion
        $this->grocery_crud->set_default_value($this->current_table,'classroom_group_markedForDeletion','n');

        $this->grocery_crud->display_as('classroom_group_id',lang('idGroup'));
        $this->grocery_crud->display_as('classroom_group_code',lang('GroupCode'));
		$this->grocery_crud->display_as('classroom_group_shortName',lang('GroupShortName'));
		$this->grocery_crud->display_as('classroom_group_name',lang('GroupName'));
		$this->grocery_crud->display_as('classroom_group_description',lang('GroupDescription'));
		$this->grocery_crud->display_as('classroom_group_educationalLevelId',lang('EducationalLevelId'));
		$this->grocery_crud->display_as('classroom_group_mentorId',lang('MentorId'));
        $this->grocery_crud->display_as('classroom_group_entryDate',lang('entryDate'));        
        $this->grocery_crud->display_as('classroom_group_last_update',lang('last_update'));
        $this->grocery_crud->display_as('classroom_group_creationUserId',lang('creationUserId'));
        $this->grocery_crud->display_as('classroom_group_lastupdateUserId',lang('lastupdateUserId'));          
        $this->grocery_crud->display_as('classroom_group_markedForDeletion',lang('markedForDeletion'));   
        $this->grocery_crud->display_as('classroom_group_markedForDeletionDate',lang('markedForDeletionDate')); 		

		/* show only specified columns */
		$this->grocery_crud->columns('classroom_group_id','classroom_group_code','classroom_group_shortName','classroom_group_name','classroom_group_description','classroom_group_mentorId','classroom_group_entryDate','classroom_group_last_update','classroom_group_creationUserId','classroom_group_lastupdateUserId');

		$output = $this->grocery_crud->render();
                        
        $this->_load_html_header($header_data,$output); 
	    $this->_load_body_header();
			
		$default_values=$this->_get_default_values();
		$default_values["table_name"]=$this->current_table;
		$default_values["field_prefix"]="classroom_group_";
		$this->load->view('defaultvalues_view.php',$default_values); 

        $this->load->view('attendance/classroom_groups_view.php',$output);     


		$this->_load_body_footer();

	}

	public function check_attendance($teacher_code = null,$group_code = null) {

		if (!$this->skeleton_auth->logged_in())
		{
			//redirect them to the login page
			redirect($this->skeleton_auth->login_page, 'refresh');
		}
		
		$header_data= $this->add_css_to_html_header_data(
			$this->_get_html_header_data(),
			"http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css");
		$header_data= $this->add_css_to_html_header_data(
			$header_data,
			"http://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/jqueryui-editable/css/jqueryui-editable.css");
		$header_data= $this->add_css_to_html_header_data(
            $header_data,
            base_url('assets/css/datepicker.css'));  
		$header_data= $this->add_css_to_html_header_data(
			$header_data,
			"http://cdn.jsdelivr.net/select2/3.4.5/select2.css");
		$header_data= $this->add_css_to_html_header_data(
			$header_data,
            base_url('assets/css/tribal-timetable.css')); 
		$header_data= $this->add_css_to_html_header_data(
			$header_data,
            "http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css"); 

		//JS
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			"http://code.jquery.com/jquery-1.9.1.js");
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			"http://code.jquery.com/ui/1.10.3/jquery-ui.js");	
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			"http://cdn.jsdelivr.net/select2/3.4.5/select2.js");
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			"http://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/jqueryui-editable/js/jqueryui-editable.min.js");
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			base_url('assets/js/bootstrap-datepicker.js'));
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			base_url('assets/js/bootstrap-datepicker.ca.js'));
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			base_url('assets/js/bootstrap-datepicker.es.js'));
		$header_data= $this->add_javascript_to_html_header_data(
            $header_data,
            base_url('assets/js/bootstrap-tooltip.js'));
        $header_data= $this->add_javascript_to_html_header_data(
            $header_data,
            base_url('assets/js/bootstrap-collapse.js'));                
        $header_data= $this->add_javascript_to_html_header_data(
            $header_data,
            base_url('assets/js/tribal.js'));
        $header_data= $this->add_javascript_to_html_header_data(
            $header_data,
            base_url('assets/js/tribal-shared.js'));        
        $header_data= $this->add_javascript_to_html_header_data(
            $header_data,
            base_url('assets/js/tribal-timetable.js'));
        $header_data= $this->add_javascript_to_html_header_data(
            $header_data,
            "http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js");
        
		$this->_load_html_header($header_data); 
		
		/*******************
		/*      BODY     *
		/******************/
		$this->_load_body_header();
		
		//TODO: select current user (sessions user as default teacher)
	    if ($teacher_code == null) {
	    	$teacher_code = 43;
	    }
		
		//Load teachers from Model
		$teachers_array = $this->attendance_model->get_all_teachers_ids_and_names();

		$data['teachers'] = $teachers_array;

		//TODO: select current user (sessions user as default teacher)
	    $data['default_teacher'] = $teacher_code;

	    $data['check_attendance_date'] = date('d/m/Y');

	    //Obtain Time Slots
	    $time_slots_array = $this->attendance_model->getAllTimeSlots()->result_array();

	    $data['time_slots_array'] = $time_slots_array;

	    //print_r($time_slots_array);

	    $teacher_groups_current_day=array();
	    
	    foreach ($time_slots_array as $time_slot)	{
   			$time_slot_data = new stdClass;
			$time_slot_data->time_interval= $time_slot['time_slot_start_time'] . " - " . $time_slot['time_slot_end_time'];
			$time_slot_data->time_slot_lective = $time_slot['time_slot_lective'];

			//Obtain lesson for this teacher date and time slot

			//$time_slots_array = $this->attendance_model->getLesson($teacher_code,$time_slot['time_slot_id'])->result_array();

			$time_slot_data->group_code="M 8 TODO";
			$time_slot_data->group_url=base_url("index.php?/attendance/check_attendance/2DAMTODO");
			$time_slot_data->group_name="2DAM TODO";

   			$all_time_slots[$time_slot['time_slot_id']] = $time_slot_data;
		}
		
		$data['all_time_slots']=$all_time_slots;
		
		//Obtain all teacher groups for selected date
		

		if(isset($group_code)){
			$data['$group_code'] = $group_code;	
		}	

		$data['check_attendance_day']="TODO";
		$data['check_attendance_table_title']=lang('check_attendance_table_title');
		$data['choose_date_string']=lang('choose_date_string');
		$data['today']=date('d-m-Y');

		$teacher_groups_current_day=array();
		$hores = array(
				"15:30",
				"16:30",
				"17:30",
				"19:00",
				"20:00",
				"21:00"
			);

		$data['hores']=$hores;		
		
		/*
		$group = new stdClass;
		$group->time_interval="16:30 - 17:30";
		//$group->group_url=base_url("attendance/select_student/codi_dia=1&codi_hora=1&codi_grup=1SEA&codi_ass=M%201&time_interval=8:00%20-%209:00&optativa=0");
		//$group->group_name="i automa (S)";
		$group->group_code="M 7";
		$group->group_url=base_url("index.php?/attendance/check_attendance/2ASIX");
		$group->group_name="2ASIX";
		
		$teacher_groups_current_day['key1']= $group;
		
		$time_slot = new stdClass;
		$group1->time_interval="15:30 - 16:30";
		//$group1->group_url=base_url("attendance/select_student/codi_dia=1&codi_hora=1&codi_grup=1SEA&codi_ass=M%201&time_interval=8:00%20-%209:00&optativa=0");
		//$group1->group_name="GRUP MPROVA";
		$group1->group_code="M 8";
		$group1->group_url=base_url("index.php?/attendance/check_attendance/2DAM");
		$group1->group_name="2DAM";

		$group2 = new stdClass;
		$group2->time_interval="11:00 - 12:00";
		//$group2->group_url=base_url("attendance/select_student/codi_dia=1&codi_hora=1&codi_grup=1SEA&codi_ass=M%201&time_interval=8:00%20-%209:00&optativa=0");
		//$group2->group_name="GRUP M9";
		$group2->group_code="M 9";
		$group2->group_url=base_url("index.php?/attendance/check_attendance/1AF");
		$group2->group_name="1AF";		
		
		$teacher_groups_current_day['key2']=$group;
		$teacher_groups_current_day['key3']=$group1;
		$teacher_groups_current_day['key4']=$group;
		$teacher_groups_current_day['key4']=$group2;
		*/
		
		
		
		/* Llista alumnes grup */

        $default_group_code = $group_code;
        $group_code=$default_group_code;

        $organization = $this->config->item('organization','skeleton_auth');

        $header_data['header_title']=lang("all_students") . ". " . $organization;

        //Load CSS & JS
        //$this->set_header_data();
        $all_groups = $this->attendance_model->get_all_classroom_groups();

        $data['group_code']=$group_code;

        $data['all_groups']=$all_groups->result();

        $data['all_groups']=$all_groups->result();
        $data['photo'] = false;
        if ($group_code) {
            $data['selected_group']= urldecode($group_code);
                $data['photo'] = true;
        }   else {
            $data['selected_group']=$default_group_code;
        }
       // echo $data['selected_group'];
       // $students_base_dn= $this->config->item('students_base_dn','skeleton_auth');
       // $default_group_dn=$students_base_dn;
        /*
        if ($data['selected_group']!="ALL_GROUPS")
            $default_group_dn=$this->ebre_escool_ldap->getGroupDNByGroupCode($data['selected_group']);
        
        if ($data['selected_group']=="ALL_GROUPS")
            $data['selected_group_names']= array (lang("all_tstudents"),"");
        else
            $data['selected_group_names']= $this->attendance_model->getGroupNamesByGroupCode($data['selected_group']);
        */
        //$data['all_students_in_group']= $this->ebre_escool_ldap->getAllGroupStudentsInfo($default_group_dn);
        
        //print_r($data['all_students_in_group']);       
        //$data['all_students']= $this->ebre_escool_ldap->getAllGroupStudentsInfo("ou=Alumnes,ou=All,dc=iesebre,dc=com");
        //Total de professors
       
        //$data['count_alumnes'] = count($data['all_students_in_group']);


		/* fi llista alumnes grup */
		$this->load->view('attendance/check_attendance',$data);










		 
		/*******************
		/*      FOOTER     *
		*******************/
		$this->_load_body_footer();		
	}


	
	public function index() {
		$this->check_attendance();
	}

    public function load_datatables_data() {

        //CSS
        $header_data= $this->add_css_to_html_header_data(
            $this->_get_html_header_data(),
            'http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css');
        $header_data= $this->add_css_to_html_header_data(
            $header_data,
            base_url('assets/css/jquery-ui.css'));  
        $header_data= $this->add_css_to_html_header_data(
            $header_data,
            base_url('assets/grocery_crud/themes/datatables/extras/TableTools/media/css/TableTools.css'));  
        //JS
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
            base_url("assets/grocery_crud/js/jquery_plugins/ui/jquery-ui-1.10.3.custom.min.js"));   
        
        $this->_load_html_header($header_data);
        //$this->_load_html_header($header_data); 
        
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
