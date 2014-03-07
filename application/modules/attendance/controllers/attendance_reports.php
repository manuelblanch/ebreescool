<?php defined('BASEPATH') OR exit('No direct script access allowed');

include "application/third_party/skeleton/application/controllers/skeleton_main.php";


class attendance_reports extends skeleton_main {


    public $body_header_view ='include/ebre_escool_body_header.php' ;

    public $body_header_lang_file ='ebre_escool_body_header' ;

	
	function __construct()
    {
        parent::__construct();
        
        $this->load->model('attendance_model');
        $this->load->library('ebre_escool_ldap');
        $this->config->load('managment');

        // Load FPDF        
        $this->load->add_package_path(APPPATH.'third_party/fpdf-codeigniter/application/');
        $this->load->library('pdf'); // Load library
		$this->pdf->fontpath = 'font/'; // Specify font folder

        /* Set language */
        $current_language=$this->session->userdata("current_language");
        if ($current_language == "") {
            $current_language= $this->config->item('default_language');
        }
        

        $this->lang->load('ebre_escool_ldap', $current_language);
        $this->lang->load('attendance',$current_language);        

        
        //LANGUAGE HELPER:
        $this->load->helper('language');
        
	}

    /* ASSISTÈNCIA - INFORMES DE CENTRE */

    function informe_centre_d_h_1() { // Incidències del centre del dia d hora h

        $this->load_datatables_data();

        $data = array();
        $data['title']=lang('reports_educational_center_reports_incidents_by_day_and_hour');
        $data['post'] = $_POST;

        $falta ='';

        // Mirar als elements del $_POST si hi ha algun tipus de falta sel·leccionat
        foreach ($_POST as $key=>$val){
            if($key!='data' and $key!='hora'){
                $falta .= $key." ";
            }
        }

        $teacher_groups_current_day=array();
        
        // Guardem la data i hora sel·leccionades
        $group = new stdClass;
        if(isset($_POST['data'])){
            $group->data=$_POST['data'];
        } else {
            $group->data='';
        }
        if(isset($_POST['hora'])){
            $group->hora=$_POST['hora'];            
        } else {
            $group->hora='';
        }
        
        // Guardem les faltes
        $group->faltes=$falta;

        /* La informació de grup és de l'estil:
        
            [data] => 26-11-2013 
            [hora] => 8:00-9:00 
            [faltes] => F R E  
        
        */

        // Incidències simulades, mentre no estigui llesta la base de dades
        $data['incidencia'] = array(
            array(
            'grup' => '1AF',
            'dia'  => '26-11-2013',
            'hora' => '8:00-9:00',
            'estudiant' => 'Patricia Favà Marti',
            'incidencia' => 'FJ',
            'credit' => 'M1',
            'professor' => 'Ferran Sabaté Borras'
            ),
            array(
            'grup' => '1APD',
            'dia'  => '28-11-2012',
            'hora' => '8:00-9:00',
            'estudiant' => 'Ignacio Bel Rodriguez',
            'incidencia' => 'F',
            'credit' => 'M4',
            'professor' => 'Ricard Gonzàlez Castelló'
            ),  
            array(
            'grup' => '2ASIX',
            'dia'  => '27-11-2013',
            'hora' => '8:00-9:00',
            'estudiant' => 'Oscar Adán Valls',
            'incidencia' => 'R',
            'credit' => 'M6',
            'professor' => 'David Caminero Baubí'
            ),
            array(
            'grup' => '1APD',
            'dia'  => '28-11-2013',
            'hora' => '8:00-9:00',
            'estudiant' => 'Ignacio Bel Rodriguez',
            'incidencia' => 'F',
            'credit' => 'M4',
            'professor' => 'Ricard Gonzàlez Castelló'
            )

        );
        
        // Hores de classe
        $data['hores'] = array( 1 => '8:00-9:00', 2 => '9:00-10:00', 3 => '10:00-11:00', 4 => '11:30-12:30', 
                                 5 => '12:30-13:30', 6 => '13:30-14:30', 7 => '15:30-16:30', 8 => '16:30-17:30',
                                 9 => '17:30-18:30', 10 => '19:00-20:00', 11 => '20:00-21:00', 12 => '21:00-22:00');

        //$this->load_header();  
        $this->load->view('attendance_reports/informe_centre_d_h_1.php',$data);     
        $this->load_footer();
    }

    function informe_centre_di_df_1() { // Incidències del centre entre una data inicial i una data final

        $this->load_datatables_data();

        $data= array();
        $data['title']=lang('reports_educational_center_reports_incidents_by_date');
        $data['post'] = $_POST;

        $falta ='';

        // Mirar als elements del $_POST si hi ha algun tipus de falta sel·leccionat
        foreach ($_POST as $key=>$val){
            if($key!='data_inicial' and $key!='data_final'){
                $falta .= $key." ";
            }
        }

        $teacher_groups_current_day=array();

        // Guardem la data inicial i data final sel·leccionades        
        $group = new stdClass;
        if(isset($_POST['data_inicial'])){
            $group->data_ini=$_POST['data_inicial'];
        } else {
            $group->data_ini='';
        }
        if(isset($_POST['data_final'])){
            $group->data_fi=$_POST['data_final'];
        } else {
            $group->data_fi='';
        }
        
        // Guardem les faltes
        $group->faltes=$falta;
        
        /* La informació de grup és de l'estil:
        
            [data_ini] => 02-12-2013
            [data_fi] => 17-12-2013
            [faltes] => FJ RJ 
        
        */

        // Incidències simulades, mentre no estigui llesta la base de dades
        $data['incidencia'] = array(
            array(
            'grup' => '1AF',
            'dia'  => strtotime('26-11-2013'),
            'hora' => '8:00-9:00',
            'estudiant' => 'Patricia Favà Marti',
            'incidencia' => 'FJ',
            'credit' => 'M1',
            'professor' => 'Ferran Sabaté Borras'
            ),
            array(
            'grup' => '1APD',
            'dia'  => strtotime('28-11-2012'),
            'hora' => '8:00-9:00',
            'estudiant' => 'Ignacio Bel Rodriguez',
            'incidencia' => 'F',
            'credit' => 'M4',
            'professor' => 'Ricard Gonzàlez Castelló'
            ),  
            array(
            'grup' => '2ASIX',
            'dia'  => strtotime('27-11-2013'),
            'hora' => '8:00-9:00',
            'estudiant' => 'Oscar Adán Valls',
            'incidencia' => 'R',
            'credit' => 'M6',
            'professor' => 'David Caminero Baubí'
            ),
            array(
            'grup' => '1APD',
            'dia'  => strtotime('28-11-2013'),
            'hora' => '8:00-9:00',
            'estudiant' => 'Ramón Bel Rodriguez',
            'incidencia' => 'F',
            'credit' => 'M4',
            'professor' => 'Ricard Gonzàlez Castelló'
            )

        );

        //$this->load_header();   
        $this->load->view('attendance_reports/informe_centre_di_df_1.php',$data);     
        $this->load_footer();
    }

    function informe_centre_ranking_di_df_1() { // Rànquing incidències del centre entre una data inicial i una data final

        $this->load_datatables_data();

        $data= array();
        $data['title']=lang('reports_educational_center_reports_incidents_by_date_ranking');
        $data['post'] = $_POST;

        $top = '';

        // $top = nº incidències a mostrar
        if(isset($_POST['top']))
        {
            $top = $_POST['top'];
        }

        // Incidències simulades, mentre no estigui llesta la base de dades
        $data['faltes'] = array(
            array(
            'data' => strtotime('10-11-2013'),
            'estudiant'  => 'Ramón Rodriguez Murillo',
            'grup' => '1GAD',
            'total' => 82,
            ),
            array(
            'data' => strtotime('10-11-2013'),      
            'estudiant'  => 'Cristina Lizana Roche',
            'grup' => '1LDC',
            'total' => 80,
            ),
            array(
            'data' => strtotime('11-11-2013'),      
            'estudiant'  => 'Cristina Oleinic',
            'grup' => '1DIE',
            'total' => 79,
            ),  
            array(
            'data' => strtotime('8-10-2013'),       
            'estudiant'  => 'Monika Aleknaite',
            'grup' => '1DIE',
            'total' => 74,
            ),
            array(
            'data' => strtotime('20-10-2013'),      
            'estudiant'  => 'Saboora Kabir',
            'grup' => '1FAR',
            'total' => 73,
            ),
            array(
            'data' => strtotime('01-12-2013'),      
            'estudiant'  => 'Aycha Nafaa Rubio',
            'grup' => '1GAD',
            'total' => 67,
            ),
            array(
            'data' => strtotime('10-11-2013'),      
            'estudiant'  => 'Sira Sowe',
            'grup' => '2DIE',
            'total' => 65,
            ),
            array(
            'data' => strtotime('08-10-2012'),      
            'estudiant'  => 'Nerea Pellicer Montesó',
            'grup' => '1LDC',
            'total' => 63,
            ),
            array(
            'data' => strtotime('5-11-2013'),       
            'estudiant'  => 'Aura Peris Aldea',
            'grup' => '1FAR',
            'total' => 63,
            ),
            array(
            'data' => strtotime('30-11-2013'),      
            'estudiant'  => 'Venecia Sotillo Diaz',
            'grup' => '1AF',
            'total' => 63
            )
        );  
        
        $group = new stdClass;
        if(isset($_POST['data_inicial'])){
            $group->data_ini=$_POST['data_inicial'];
        } else {
            $group->data_ini='';
        }
        if(isset($_POST['data_final'])){
            $group->data_fi=$_POST['data_final'];
        } else {
            $group->data_fi='';
        }
        
        $group->top=$top;

        /* La informació de grup és de l'estil:
        
            [data_ini] => 05-12-2013
            [data_fi] => 05-12-2013
            [top] => 10
        
        */

        //$this->load_header();   
        $this->load->view('attendance_reports/informe_centre_ranking_di_df_1.php',$data);     
        $this->load_footer();    
    }

    function Llistat_grup_tutor() { // Tutors de Grup

        $this->load_datatables_data();

        if (!$this->skeleton_auth->logged_in())
        {
            //redirect them to the login page
            redirect($this->skeleton_auth->login_page, 'refresh');
        }
        
        $default_group_code = $this->config->item('default_group_code');
        $group_code=$default_group_code;

        $organization = $this->config->item('organization','skeleton_auth');

        $header_data['header_title']=lang("all_teachers") . ". " . $organization;

        //Load CSS & JS
        //$this->set_header_data();

        $all_groups = $this->attendance_model->get_all_classroom_groups();
        $data['group_code']=$group_code;
        $data['all_groups']=$all_groups->result();
        
        if (isset($group_code)) {
            $data['selected_group']= urldecode($group_code);
        }   else {
            $data['selected_group']=$default_group_code;
        }
        
       // $students_base_dn= $this->config->item('students_base_dn','skeleton_auth');
       // $default_group_dn=$students_base_dn;
        if ($data['selected_group']!="ALL_GROUPS")
            $default_group_dn=$this->ebre_escool_ldap->getGroupDNByGroupCode($data['selected_group']);
        
        if ($data['selected_group']=="ALL_GROUPS")
            $data['selected_group_names']= array (lang("all_teachers"),"");
        else
            $data['selected_group_names']= $this->attendance_model->getGroupNamesByGroupCode($data['selected_group']);
        
       // $data['all_students_in_group']= $this->ebre_escool_ldap->getAllGroupStudentsInfo($default_group_dn);
        

        $data['all_teachers']= $this->ebre_escool_ldap->getAllTeachers("ou=Profes,ou=All,dc=iesebre,dc=com");
        $data['all_conserges']= $this->ebre_escool_ldap->getAllTeachers("ou=Consergeria,ou=Personal,ou=All,dc=iesebre,dc=com");
        $data['all_secretaria']= $this->ebre_escool_ldap->getAllTeachers("ou=Secretaria,ou=Personal,ou=All,dc=iesebre,dc=com");
        //Total de professors
        $data['count_teachers'] = count($data['all_teachers']);
        $data['count_conserges'] = count($data['all_conserges']);
        $data['count_secretaria'] = count($data['all_secretaria']);                
        $data['empleat']= $this->ebre_escool_ldap->getEmailAndPhotoData("ou=Profes,ou=All,dc=iesebre,dc=com");
/**/
        //$this->load_header();  
        //$this->load->view('attendance_reports/Llistat_grup_tutor.php',$data);     
        $this->load->view('attendance_reports/Llistat_grup_tutor.php',$data);     
        $this->load_footer();     
    }     

    function mailing_list_report() {

        $this->load_header();    
        $this->load->view('attendance_reports/mailing_list_report.php');     
        $this->load_footer();      
    }  

    /* ASSISTÈNCIA - INFORMES DE GRUP */

    function class_list_report() {

        $data['grups'] = array( "1AF" => "1AF - *1r Adm.Finan (S) - CF",
                                "1APD" => "1APD - *1r Atenc. Persones Dep.M) - CF",
                                "1ASIX-DAM" => "1ASIX-DAM - *1r Inform. superior (S)L - CF",
                                "1DIE" => "1DIE - 1r Diet - CF",
                                "1EE" => "1EE - *1r Efic. Energ.(S) L - CF",
                                "1EIN" => "1EIN - *1r Educaci - CF",
                                "1ES" => "1ES - *1r Emerg. Sanit.(M)L - CF",
                                "1FAR" => "1FAR - *1r Farm - CF",
                                "1GAD" => "1GAD - *Gesti - CF",
                                "1IEA" => "1IEA - *1r Ins.Elec. Autom(M)L - CF",
                                "1IME" => "1IME - *1r Ins. Mant. Elec.(M) - CF",
                                "1INS A" => "1INS A - *1r Int.Soc.(S)L - CF",
                                "1INS B" => "1INS B - 1r Int. Soc.(S)L - CF",
                                "1LDC" => "1LDC - *1r Lab. Diagnosi C (S). - CF",
                                "1MEC" => "1MEC - *1r Mecanitzaci - CF",
                                "1PM" => "1PM - *1r Prod. Mecanitza(S)L. - CF",
                                "1PRO" => "1PRO - *1r D. A. Projec. C (S) L - CF",
                                "1PRP" => "1PRP - 1r Prev. Riscos Prof.(S) - CF",
                                "1SEA" => "1SEA - i automa (S) - CF",
                                "1SMX A" => "1SMX A - *1r Inform Mitj - CF",
                                "1SMX B" => "1SMX B - *1r Inform. mitj - CF",
                                "1STI" => "1STI - 1r Sis. Teleco. Infor (S) - CF",
                                "2AF" => "2AF - 2n Ad. Finan (S) - CF",
                                "2APD" => "2APD - 2n Atenc. Persones Dep.M) - CF",
                                "2ASIX" => "2ASIX - 2n Adm Sist Inf xarxa(S)L - CF",
                                "2DAM" => "2DAM - 2n Desenv Aplic Mult (S)L - CF",
                                "2DIE" => "2DIE - 2n Diet - CF",
                                "2EE" => "2EE - 2 Efic.Energ.(S) L - CF",
                                "2EIN" => "2EIN - 2n Educaci - CF",
                                "2ES" => "2ES - 2n Emerg. Sanit.(M) - CF",
                                "2FAR" => "2FAR - 2n Farm - CF",
                                "2GAD" => "2GAD - 2n Gest. Adm. (M)L - CF",
                                "2IEA" => "2IEA - *2n Ins.Elec,Autom(M)L - CF",
                                "2IME" => "2IME - 2n Ins. Mant. Elec.(M) - CF",
                                "2INS A" => "2INS A - 2n Integraci - CF",
                                "2INS B" => "2INS B - 2n Integraci - CF",
                                "2LDC" => "2LDC - 2n Lab. Diagnosi C(S) - CF",
                                "2MEC" => "2MEC - 2n Mecanitzaci - CF",
                                "2PM" => "2PM - *2n Prod. Mecanitza.(S) L - CF",
                                "2PRO" => "2PRO - 2n D. A. Projec. C (S) - CF",
                                "2PRP" => "2PRP - 2n Prev. Riscos Prof.(S) - CF",
                                "2SEA" => "2SEA - *2n Sist. Electri i automa (S) - CF",
                                "2SIC" => "2SIC - 2n Soldadura i caldereria (M)  - CF",
                                "2SMX" => "2SMX - 2n Inform. Mitj - CF",
                                "2STI" => "2STI - 2n Sis. teleco. Infor (S) - CF",
                                "CAIA" => "CAIA - *Cures Auxiliar Inf(M) - CF",
                                "CAIB" => "CAIB - *Cures Auxiliar Inf(M) - CF",
                                "CAIC" => "CAIC - Cures Auxiliar Inf(M) - CF",
                                "CAM" => "CAM - *Curs Acc - CF",
                                "CAS A" => "CAS A - *Curs Acc - CF",
                                "CAS B" => "CAS B - *Curs Acc - CF",
                                "CAS C" => "CAS C - Curs Acc - CF",
                                "COM" => "COM - *Comer - CF",
                                "GCM" => "GCM - Ges. Comer. Mar.(S) - CF",
                                "SE" => "SE - Secretariat (S) - CF"
            );

/**/
        $this->load_datatables_data();

        if (!$this->skeleton_auth->logged_in())
        {
            //redirect them to the login page
            redirect($this->skeleton_auth->login_page, 'refresh');
        }
        
        $default_group_code = $this->config->item('default_group_code');
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
        if ($_POST) {
            $data['selected_group']= urldecode($_POST['grup']);
            if ($_POST['foto']){
                $data['photo'] = true;
            }
        }   else {
            $data['selected_group']=$default_group_code;
        }
       // echo $data['selected_group'];
       // $students_base_dn= $this->config->item('students_base_dn','skeleton_auth');
       // $default_group_dn=$students_base_dn;
        if ($data['selected_group']!="ALL_GROUPS")
            $default_group_dn=$this->ebre_escool_ldap->getGroupDNByGroupCode($data['selected_group']);
        
        if ($data['selected_group']=="ALL_GROUPS")
            $data['selected_group_names']= array (lang("all_tstudents"),"");
        else
            $data['selected_group_names']= $this->attendance_model->getGroupNamesByGroupCode($data['selected_group']);
        
       $data['all_students_in_group']= $this->ebre_escool_ldap->getAllGroupStudentsInfo($default_group_dn);
        //print_r($data['all_students_in_group']);       
        //$data['all_students']= $this->ebre_escool_ldap->getAllGroupStudentsInfo("ou=Alumnes,ou=All,dc=iesebre,dc=com");
        //Total de professors
        $data['count_alumnes'] = count($data['all_students_in_group']);
        //$data['empleat']= $this->ebre_escool_ldap->getEmailAndPhotoData("ou=Profes,ou=All,dc=iesebre,dc=com");

/**/


//        $this->load_header();  
        if(!$_POST){
            $this->load->view('attendance_reports/class_list_report.php', $data); 
        } else {
            $this->load->view('attendance_reports/class_list_report_pdf.php', $data); 
        } 
        $this->load_footer();      
    }

    function class_sheet_report() {

        $data['grups'] = array( "1AF" => "1AF - *1r Adm.Finan (S) - CF",
                                "1APD" => "1APD - *1r Atenc. Persones Dep.M) - CF",
                                "1ASIX-DAM" => "1ASIX-DAM - *1r Inform. superior (S)L - CF",
                                "1DIE" => "1DIE - 1r Diet - CF",
                                "1EE" => "1EE - *1r Efic. Energ.(S) L - CF",
                                "1EIN" => "1EIN - *1r Educaci - CF",
                                "1ES" => "1ES - *1r Emerg. Sanit.(M)L - CF",
                                "1FAR" => "1FAR - *1r Farm - CF",
                                "1GAD" => "1GAD - *Gesti - CF",
                                "1IEA" => "1IEA - *1r Ins.Elec. Autom(M)L - CF",
                                "1IME" => "1IME - *1r Ins. Mant. Elec.(M) - CF",
                                "1INS A" => "1INS A - *1r Int.Soc.(S)L - CF",
                                "1INS B" => "1INS B - 1r Int. Soc.(S)L - CF",
                                "1LDC" => "1LDC - *1r Lab. Diagnosi C (S). - CF",
                                "1MEC" => "1MEC - *1r Mecanitzaci - CF",
                                "1PM" => "1PM - *1r Prod. Mecanitza(S)L. - CF",
                                "1PRO" => "1PRO - *1r D. A. Projec. C (S) L - CF",
                                "1PRP" => "1PRP - 1r Prev. Riscos Prof.(S) - CF",
                                "1SEA" => "1SEA - i automa (S) - CF",
                                "1SMX A" => "1SMX A - *1r Inform Mitj - CF",
                                "1SMX B" => "1SMX B - *1r Inform. mitj - CF",
                                "1STI" => "1STI - 1r Sis. Teleco. Infor (S) - CF",
                                "2AF" => "2AF - 2n Ad. Finan (S) - CF",
                                "2APD" => "2APD - 2n Atenc. Persones Dep.M) - CF",
                                "2ASIX" => "2ASIX - 2n Adm Sist Inf xarxa(S)L - CF",
                                "2DAM" => "2DAM - 2n Desenv Aplic Mult (S)L - CF",
                                "2DIE" => "2DIE - 2n Diet - CF",
                                "2EE" => "2EE - 2 Efic.Energ.(S) L - CF",
                                "2EIN" => "2EIN - 2n Educaci - CF",
                                "2ES" => "2ES - 2n Emerg. Sanit.(M) - CF",
                                "2FAR" => "2FAR - 2n Farm - CF",
                                "2GAD" => "2GAD - 2n Gest. Adm. (M)L - CF",
                                "2IEA" => "2IEA - *2n Ins.Elec,Autom(M)L - CF",
                                "2IME" => "2IME - 2n Ins. Mant. Elec.(M) - CF",
                                "2INS A" => "2INS A - 2n Integraci - CF",
                                "2INS B" => "2INS B - 2n Integraci - CF",
                                "2LDC" => "2LDC - 2n Lab. Diagnosi C(S) - CF",
                                "2MEC" => "2MEC - 2n Mecanitzaci - CF",
                                "2PM" => "2PM - *2n Prod. Mecanitza.(S) L - CF",
                                "2PRO" => "2PRO - 2n D. A. Projec. C (S) - CF",
                                "2PRP" => "2PRP - 2n Prev. Riscos Prof.(S) - CF",
                                "2SEA" => "2SEA - *2n Sist. Electri i automa (S) - CF",
                                "2SIC" => "2SIC - 2n Soldadura i caldereria (M)  - CF",
                                "2SMX" => "2SMX - 2n Inform. Mitj - CF",
                                "2STI" => "2STI - 2n Sis. teleco. Infor (S) - CF",
                                "CAIA" => "CAIA - *Cures Auxiliar Inf(M) - CF",
                                "CAIB" => "CAIB - *Cures Auxiliar Inf(M) - CF",
                                "CAIC" => "CAIC - Cures Auxiliar Inf(M) - CF",
                                "CAM" => "CAM - *Curs Acc - CF",
                                "CAS A" => "CAS A - *Curs Acc - CF",
                                "CAS B" => "CAS B - *Curs Acc - CF",
                                "CAS C" => "CAS C - Curs Acc - CF",
                                "COM" => "COM - *Comer - CF",
                                "GCM" => "GCM - Ges. Comer. Mar.(S) - CF",
                                "SE" => "SE - Secretariat (S) - CF"
            );

/**/
        $this->load_datatables_data();

        if (!$this->skeleton_auth->logged_in())
        {
            //redirect them to the login page
            redirect($this->skeleton_auth->login_page, 'refresh');
        }
        
        $default_group_code = $this->config->item('default_group_code');
        $group_code=$default_group_code;

        $organization = $this->config->item('organization','skeleton_auth');

        $header_data['header_title']=lang("all_students") . ". " . $organization;

        //Load CSS & JS
        //$this->set_header_data();
        $all_groups = $this->attendance_model->get_all_classroom_groups();

        $data['group_code']=$group_code;

        $data['all_groups']=$all_groups->result();

        if ($_POST) {
            $data['selected_group']= urldecode($_POST['grup']);
        }   else {
            $data['selected_group']=$default_group_code;
        }

        

       // echo $data['selected_group'];
       // $students_base_dn= $this->config->item('students_base_dn','skeleton_auth');
       // $default_group_dn=$students_base_dn;
        if ($data['selected_group']!="ALL_GROUPS")
            $default_group_dn=$this->ebre_escool_ldap->getGroupDNByGroupCode($data['selected_group']);
        
        if ($data['selected_group']=="ALL_GROUPS")
            $data['selected_group_names']= array (lang("all_tstudents"),"");
        else
            $data['selected_group_names']= $this->attendance_model->getGroupNamesByGroupCode($data['selected_group']);
        
       $data['all_students_in_group']= $this->ebre_escool_ldap->getAllGroupStudentsInfo($default_group_dn);
       
        //$data['all_students']= $this->ebre_escool_ldap->getAllGroupStudentsInfo("ou=Alumnes,ou=All,dc=iesebre,dc=com");
        //Total de professors
        $data['count_alumnes'] = count($data['all_students_in_group']);
        //$data['empleat']= $this->ebre_escool_ldap->getEmailAndPhotoData("ou=Profes,ou=All,dc=iesebre,dc=com");

/**/


        //$this->load_header();
        if(!$_POST){
            $this->load->view('attendance_reports/class_sheet_report.php', $data); 
        } else {
            $this->load->view('attendance_reports/class_sheet_report_pdf.php', $data); 
        }
        $this->load_footer();       
    }

    function informe_resum_grup_di_df_1() {

        $data['grups'] = array( "1AF" => "1AF",
                                "1APD" => "1APD",
                                "1ASIX-DAM" => "1ASIX-DAM",
                                "1DIE" => "1DIE",
                                "1EE" => "1EE",
                                "1EIN" => "1EIN",
                                "1ES" => "1ES",
                                "1FAR" => "1FAR",
                                "1GAD" => "1GAD",
                                "1IEA" => "1IEA",
                                "1IME" => "1IME",
                                "1INS A" => "1INS A",
                                "1INS B" => "1INS B",
                                "1LDC" => "1LDC",
                                "1MEC" => "1MEC",
                                "1PM" => "1PM",
                                "1PRO" => "1PRO",
                                "1PRP" => "1PRP",
                                "1SEA" => "1SEA",
                                "1SMX A" => "1SMX A",
                                "1SMX B" => "1SMX B",
                                "1STI" => "1STI",
                                "2AF" => "2AF",
                                "2APD" => "2APD",
                                "2ASIX" => "2ASIX",
                                "2DAM" => "2DAM",
                                "2DIE" => "2DIE",
                                "2EE" => "2EE",
                                "2EIN" => "2EIN",
                                "2ES" => "2ES",
                                "2FAR" => "2FAR",
                                "2GAD" => "2GAD",
                                "2IEA" => "2IEA",
                                "2IME" => "2IME",
                                "2INS A" => "2INS A",
                                "2INS B" => "2INS B",
                                "2LDC" => "2LDC",
                                "2MEC" => "2MEC",
                                "2PM" => "2PM",
                                "2PRO" => "2PRO",
                                "2PRP" => "2PRP",
                                "2SEA" => "2SEA",
                                "2SIC" => "2SIC",
                                "2SMX" => "2SMX",
                                "2STI" => "2STI",
                                "CAIA" => "CAIA",
                                "CAIB" => "CAIB",
                                "CAIC" => "CAICF",
                                "CAM" => "CAM",
                                "CAS A" => "CAS A",
                                "CAS B" => "CAS B",
                                "CAS C" => "CAS C",
                                "COM" => "COM",
                                "GCM" => "GCM",
                                "SE" => "SE"
            );

        $this->load_datatables_data();

        if (!$this->skeleton_auth->logged_in())
        {
            //redirect them to the login page
            redirect($this->skeleton_auth->login_page, 'refresh');
        }
        
        $default_group_code = $this->config->item('default_group_code');
        $group_code=$default_group_code;

        $organization = $this->config->item('organization','skeleton_auth');

        $all_groups = $this->attendance_model->get_all_classroom_groups();

        $data['group_code']=$group_code;
        $data['all_groups']=$all_groups->result();
        if ($_POST) {
            $data['selected_group']= urldecode($_POST['grup']);
        } else {
            $data['selected_group']=$default_group_code;
        }

        if ($data['selected_group']!="ALL_GROUPS")
            $default_group_dn=$this->ebre_escool_ldap->getGroupDNByGroupCode($data['selected_group']);

        if ($data['selected_group']=="ALL_GROUPS")
            $data['selected_group_names']= array (lang("all_tstudents"),"");
        else
            $data['selected_group_names']= $this->attendance_model->getGroupNamesByGroupCode($data['selected_group']);
        
        $data['all_students_in_group']= $this->ebre_escool_ldap->getAllGroupStudentsInfo($default_group_dn);
        $data['count_alumnes'] = count($data['all_students_in_group']);


//        $this->load_header(); 
        $this->load->view('attendance_reports/informe_resum_grup_di_df_1.php',$data);     
        $this->load_footer();    
    }

    function informe_resum_grup_faltes_mes_1() {

        $data['grups'] = array( "1AF" => "1AF - *1r Adm.Finan (S) - CF",
                                "1APD" => "1APD - *1r Atenc. Persones Dep.M) - CF",
                                "1ASIX-DAM" => "1ASIX-DAM - *1r Inform. superior (S)L - CF",
                                "1DIE" => "1DIE - 1r Diet - CF",
                                "1EE" => "1EE - *1r Efic. Energ.(S) L - CF",
                                "1EIN" => "1EIN - *1r Educaci - CF",
                                "1ES" => "1ES - *1r Emerg. Sanit.(M)L - CF",
                                "1FAR" => "1FAR - *1r Farm - CF",
                                "1GAD" => "1GAD - *Gesti - CF",
                                "1IEA" => "1IEA - *1r Ins.Elec. Autom(M)L - CF",
                                "1IME" => "1IME - *1r Ins. Mant. Elec.(M) - CF",
                                "1INS A" => "1INS A - *1r Int.Soc.(S)L - CF",
                                "1INS B" => "1INS B - 1r Int. Soc.(S)L - CF",
                                "1LDC" => "1LDC - *1r Lab. Diagnosi C (S). - CF",
                                "1MEC" => "1MEC - *1r Mecanitzaci - CF",
                                "1PM" => "1PM - *1r Prod. Mecanitza(S)L. - CF",
                                "1PRO" => "1PRO - *1r D. A. Projec. C (S) L - CF",
                                "1PRP" => "1PRP - 1r Prev. Riscos Prof.(S) - CF",
                                "1SEA" => "1SEA - i automa (S) - CF",
                                "1SMX A" => "1SMX A - *1r Inform Mitj - CF",
                                "1SMX B" => "1SMX B - *1r Inform. mitj - CF",
                                "1STI" => "1STI - 1r Sis. Teleco. Infor (S) - CF",
                                "2AF" => "2AF - 2n Ad. Finan (S) - CF",
                                "2APD" => "2APD - 2n Atenc. Persones Dep.M) - CF",
                                "2ASIX" => "2ASIX - 2n Adm Sist Inf xarxa(S)L - CF",
                                "2DAM" => "2DAM - 2n Desenv Aplic Mult (S)L - CF",
                                "2DIE" => "2DIE - 2n Diet - CF",
                                "2EE" => "2EE - 2 Efic.Energ.(S) L - CF",
                                "2EIN" => "2EIN - 2n Educaci - CF",
                                "2ES" => "2ES - 2n Emerg. Sanit.(M) - CF",
                                "2FAR" => "2FAR - 2n Farm - CF",
                                "2GAD" => "2GAD - 2n Gest. Adm. (M)L - CF",
                                "2IEA" => "2IEA - *2n Ins.Elec,Autom(M)L - CF",
                                "2IME" => "2IME - 2n Ins. Mant. Elec.(M) - CF",
                                "2INS A" => "2INS A - 2n Integraci - CF",
                                "2INS B" => "2INS B - 2n Integraci - CF",
                                "2LDC" => "2LDC - 2n Lab. Diagnosi C(S) - CF",
                                "2MEC" => "2MEC - 2n Mecanitzaci - CF",
                                "2PM" => "2PM - *2n Prod. Mecanitza.(S) L - CF",
                                "2PRO" => "2PRO - 2n D. A. Projec. C (S) - CF",
                                "2PRP" => "2PRP - 2n Prev. Riscos Prof.(S) - CF",
                                "2SEA" => "2SEA - *2n Sist. Electri i automa (S) - CF",
                                "2SIC" => "2SIC - 2n Soldadura i caldereria (M)  - CF",
                                "2SMX" => "2SMX - 2n Inform. Mitj - CF",
                                "2STI" => "2STI - 2n Sis. teleco. Infor (S) - CF",
                                "CAIA" => "CAIA - *Cures Auxiliar Inf(M) - CF",
                                "CAIB" => "CAIB - *Cures Auxiliar Inf(M) - CF",
                                "CAIC" => "CAIC - Cures Auxiliar Inf(M) - CF",
                                "CAM" => "CAM - *Curs Acc - CF",
                                "CAS A" => "CAS A - *Curs Acc - CF",
                                "CAS B" => "CAS B - *Curs Acc - CF",
                                "CAS C" => "CAS C - Curs Acc - CF",
                                "COM" => "COM - *Comer - CF",
                                "GCM" => "GCM - Ges. Comer. Mar.(S) - CF",
                                "SE" => "SE - Secretariat (S) - CF"
            );

            $data['mes'] = array( "1" => "Gener",
                                  "2" => "Febrer",
                                  "3" => "Març",
                                  "4" => "Abril",
                                  "5" => "Maig",
                                  "6" => "Juny",
                                  "7" => "Juliol",
                                  "8" => "Agost",
                                  "9" => "Setembre",
                                  "10" => "Octubre",
                                  "11" => "Novembre",
                                  "12" => "Desembre"
            );

            $data['any'] = array( "2013" => "2013",
                                  "2012" => "2012",
                                  "2011" => "2011",
                                  "2010" => "2010",
                                  "2009" => "2009",
                                  "2008" => "2008"
            );

        $this->load_datatables_data();

        if (!$this->skeleton_auth->logged_in())
        {
            //redirect them to the login page
            redirect($this->skeleton_auth->login_page, 'refresh');
        }
        
        $default_group_code = $this->config->item('default_group_code');
        $group_code=$default_group_code;

        $organization = $this->config->item('organization','skeleton_auth');

        $all_groups = $this->attendance_model->get_all_classroom_groups();

        $data['group_code']=$group_code;
        $data['all_groups']=$all_groups->result();
        if ($_POST) {
            $data['selected_group']= urldecode($_POST['grup']);
        } else {
            $data['selected_group']=$default_group_code;
        }

        if ($data['selected_group']!="ALL_GROUPS")
            $default_group_dn=$this->ebre_escool_ldap->getGroupDNByGroupCode($data['selected_group']);

        if ($data['selected_group']=="ALL_GROUPS")
            $data['selected_group_names']= array (lang("all_tstudents"),"");
        else
            $data['selected_group_names']= $this->attendance_model->getGroupNamesByGroupCode($data['selected_group']);
        
        $data['all_students_in_group']= $this->ebre_escool_ldap->getAllGroupStudentsInfo($default_group_dn);
        $data['count_alumnes'] = count($data['all_students_in_group']);


//        $this->load_header(); 
        $this->load->view('attendance_reports/informe_resum_grup_faltes_mes_1.php',$data);     
        $this->load_footer();    
    }

    function load_header() {

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

        $this->_load_html_header($header_data); 
        $this->_load_body_header();
    }

    function load_footer() {

        $this->_load_body_footer();    

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

    public function informeGuifi() {

        $this->load_header();   
        $this->load->view('attendance_reports/informe_guifi.php');     
        $this->load_footer();

    }

 }   
