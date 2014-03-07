<script>

$(function() {

	//toggle `popup` / `inline` mode
    $.fn.editable.defaults.mode = 'popup';     
    
    //make username editable
    $('.obs_').editable();

	//Datatable
	//TODO
	//Obtenir les dades corresponents al dropdown escollit (alumne, hora i incidència) i insertar-los a la BD

	//***********************
	//* TEACHERS DROPDOWN  **
	//***********************

	//Jquery select plugin: http://ivaynberg.github.io/select2/
	$("#teachers").select2(); 

	$('#teachers').on("change", function(e) {	
		selectedValue = $("#teachers").select2("val");
		var pathArray = window.location.pathname.split( '/' );
		var secondLevelLocation = pathArray[1];
		var baseURL = window.location.protocol + "//" + window.location.host + "/" + secondLevelLocation + "/index.php/attendance/check_attendance";
		//alert(baseURL + "/" + selectedValue);
		window.location.href = baseURL + "/" + selectedValue;

	}); 

	//***********************
	//* Datepicker         **
	//***********************
	$('.input-append.date').datepicker({
    	format: "dd/mm/yyyy",
    	weekStart: 1,
    	todayBtn: true,
    	language: "ca",
    	daysOfWeekDisabled: "0,6",
    	autoclose: true,
    	todayHighlight: true
    });


	//***************************
	//* CHECK ATTENDANCE TABLE **
	//***************************

     $('#groups_by_teacher_an_date').dataTable( {
                "oLanguage": {
                        "sProcessing":   "Processant...",
                        "sLengthMenu":   "Mostra _MENU_ registres",
                        "sZeroRecords":  "No s'han trobat registres.",
                        "sInfo":         "Mostrant de _START_ a _END_ de _TOTAL_ registres",
                        "sInfoEmpty":    "Mostrant de 0 a 0 de 0 registres",
                        "sInfoFiltered": "(filtrat de _MAX_ total registres)",
                        "sInfoPostFix":  "",
                        "sSearch":       "Filtrar:",
                        "sUrl":          "",
                        "oPaginate": {
                                "sFirst":    "Primer",
                                "sPrevious": "Anterior",
                                "sNext":     "Següent",
                                "sLast":     "Últim"
                        }
            },
                "bPaginate": false,
                "bFilter": false,
        "bInfo": false,
        });





	$("select").change(function(){
		var fila = null;
		var columna = null;
		var hora = null;
		var alumne = null;
		var observacions = null;	
		var incidencia = $("option:selected", this).val();
		var id = $(this).attr('id');

		//obtenir la fila i columna a partir de l'identificador
	 	text = id.split("-");
	 	fila = text[1];
	 	columna = text[0];
	 	//hora = $(".hora_"+columna).text();
	 	hora= get_hour(columna);
	 	//alumne = $("#nom_"+fila).text();
	 	alumne = get_student(fila);
	 	insert_value(alumne,hora,incidencia);
	 	//read_value(alumne,hora);
	});

    //$('#groups_by_teacher_an_date1').dataTable();
    //console.log("HEY YOU1");
	
	//Datepicker
	var data;
	$.datepicker.regional['ca'] = {
					onSelect: function(date) {
			            data = date;
			        },
	                closeText: 'Tancar',
	                prevText: '&#x3c;Ant',
	                nextText: 'Seg&#x3e;',
	                currentText: 'Avui',
	                monthNames: ['Gener','Febrer','Mar&ccedil;','Abril','Maig','Juny',
	                'Juliol','Agost','Setembre','Octubre','Novembre','Desembre'],
	                monthNamesShort: ['Gen','Feb','Mar','Abr','Mai','Jun',
	                'Jul','Ago','Set','Oct','Nov','Des'],
	                dayNames: ['Diumenge','Dilluns','Dimarts','Dimecres','Dijous','Divendres','Dissabte'],
	                dayNamesShort: ['Dug','Dln','Dmt','Dmc','Djs','Dvn','Dsb'],
	                dayNamesMin: ['Dg','Dl','Dt','Dc','Dj','Dv','Ds'],
	                weekHeader: 'Sm',
	                dateFormat: 'dd/mm/yy',
	                firstDay: 1,
	                isRTL: false,
	                showMonthAfterYear: false,
	                yearSuffix: ''};

	data = $( "#datepicker" ).datepicker($.datepicker.regional['ca']);
	//alert("la data es: "+data.val());
});

</script>

<?php 

	/* Urls per a fer el Insertar, Editar, Llegir i Esborrar */
	$url_insert = ('http://localhost/ebre-escool/index.php/attendance/insert/prova_incidencies');
	$url_read = ('http://localhost/ebre-escool/index.php/attendance/read/prova_incidencies');
	$url_update = ('http://localhost/ebre-escool/index.php/attendance/update/prova_incidencies');
	$url_delete = ('http://localhost/ebre-escool/index.php/attendance/delete/prova_incidencies');
?>

<script type="text/javascript">

function get_student(fila){
	alumne = $("#nom_"+fila).text();
	return alumne;
}

function get_hour(columna){
	hora = $(".hora_"+columna).text();
	return hora;
}

function insert_value(alumne,hora,incidencia){
	jQuery.ajax({
		url:'<?php echo $url_insert;?>',
		data: {
				alumne: alumne,
				incidencia: incidencia,
				hora: hora
			},
			type: 'post',
			dataType: 'json'
		}).done(
			function (data) 
			{
				alert("S'ha insertat " + data + " fila.");
			}
		).fail(
			function() 
			{
				alert( "No s'ha pogut obtenir cap valor" );
			});
}	

function read_value(alumne,hora){
	jQuery.ajax({
		url:'<?php echo $url_read;?>',
		data: {
				alumne: alumne,
				hora: hora
			},
			type: 'post',
			dataType: 'json'
		}).done(
			function (data) 
			{
				alert("S'ha llegit " + data + ".");
			}
		).fail(
			function() 
			{
				alert( "No s'ha pogut obtenir cap valor" );
			});
}
</script>



<div class="container">

 <center>
  <select id="teachers" style="width: 400px">
  		   <option></option>
   <?php foreach( (array) $teachers as $teacher_id => $teacher_name): ?>
		   <?php if( $teacher_id == $default_teacher): ?>
            <option value="<?php echo $teacher_id; ?>" selected="selected"><?php echo $teacher_name; ?></option>
           <?php else: ?> 
            <option value="<?php echo $teacher_id; ?>" ><?php echo $teacher_name; ?></option>
           <?php endif; ?> 
   <?php endforeach; ?>	
  </select> 
  <br/><br/>
      <div class="input-append date">
    	<input type="text" class="span2" value="<?php echo $check_attendance_date;?>"/><span class="add-on"><i class="icon-calendar"></i></span>
      </div>
 </center>

 <div id ="check_attendance_table" style="visibility: visible">
 
 <table class="table table-striped table-bordered table-hover table-condensed" id="groups_by_teacher_an_date">
 <thead style="background-color: #d9edf7;">
  <tr>
    <td colspan="4" style="text-align: center;"> <h4><?php echo $check_attendance_table_title?> | Dia: <?php echo $check_attendance_date?></h4></td>
  </tr>
  <tr>
     <th><?php echo lang("time_slot");?></th>
     <th><?php echo lang("ClassroomGroup");?></th>
     <th>TODO Mòdul Profesional</th>
     <th><?php echo lang("attendances_actions");?></th>
  </tr>
 </thead>
 <tbody>
  <!-- Iteration that shows teacher groups for selected day-->
  <?php foreach ($all_time_slots as $key => $time_slot) : ?>
   
   <tr align="center" class="{cycle values='tr0,tr1'}" id="tr_<?php echo $key;?>">
     <td><?php echo $time_slot->time_interval;?></td>
     <td>
		<?php if ($time_slot->time_slot_lective == 1): ?>
			<li class="tt-event btn-warning" style="margin-left: auto;margin-right: auto;position:relative; width: 90%; height:90%;">
           		<a href="<?php echo $time_slot->group_url;?> "><?php echo $time_slot->group_name;?></a><br />
            	20.2<br />
        	</li>
		<?php else: ?>
			<li class="tt-event btn-inverse" style="margin-left: auto;margin-right: auto;position:relative; width: 90%; height: auto;">
           		DESCANS<br/>
           		&nbsp;<br />
        	</li>
		<?php endif; ?>
     </td>

     <td>
     	<?php if ($time_slot->time_slot_lective == 1): ?>
			<li class="tt-event btn-default" style="margin-left: auto;margin-right: auto;position:relative; width: 90%; height:90%;">
           		<?php echo $time_slot->group_code;?><br />
            	20.2<br />
        	</li>
		<?php else: ?>
			<li class="tt-event btn-inverse" style="margin-left: auto;margin-right: auto;position:relative; width: 90%; height: auto;">
           		DESCANS<br/>
           		&nbsp;<br />
        	</li>
		<?php endif; ?>

	 </td>
	 <td>
	 	<button type="button" class="btn btn-primary">
  			<i class="icon-bell icon-white"></i> Passar llista
		</button>
	 </td>
   </tr>
  <?php endforeach; ?>
 </tbody>
</table>

</div>


 <div class="timetable" data-days="5" data-hours="15" style="visibility: hidden">
            <ul class="tt-events">
				<li class="tt-event btn-inverse" data-id="10" data-day="0" data-start="4" data-duration="1">
                    DESCANS
                </li>
                <li class="tt-event btn-inverse" data-id="10" data-day="0" data-start="7" data-duration="1">
                    MIGDIA
                </li>
				<li class="tt-event btn-inverse" data-id="10" data-day="0" data-start="11" data-duration="1">
                    DESCANS
                </li>
                <li class="tt-event btn-success" data-id="10" data-day="0" data-start="12" data-duration="1">
                    2 DAM M9<br />
                    20.2<br />
                </li>
                <li class="tt-event btn-warning" data-id="10" data-day="0" data-start="13" data-duration="2">
                    2 ASIX M16<br />
                    20.4<br />
                </li>
                <li class="tt-event btn-inverse" data-id="10" data-day="1" data-start="4" data-duration="1">
                    DESCANS
                </li>
                <li class="tt-event btn-inverse" data-id="10" data-day="1" data-start="7" data-duration="1">
                    MIGDIA
                </li>
                <li class="tt-event btn-inverse" data-id="10" data-day="1" data-start="11" data-duration="1">
                    DESCANS
                </li>
                <li class="tt-event btn-danger" data-id="10" data-day="1" data-start="13" data-duration="2">
                    2 DAM M8<br />
                    20.2<br />
                </li>
                </li>
                <li class="tt-event btn-inverse" data-id="10" data-day="2" data-start="4" data-duration="1">
                    DESCANS
                </li>
                <li class="tt-event btn-inverse" data-id="10" data-day="2" data-start="7" data-duration="1">
                    MIGDIA
                </li>
                <li class="tt-event btn-info" data-id="10" data-day="2" data-start="9" data-duration="2">
                    2 ASIX M7<br />
                    20.4<br />
                </li>
                <li class="tt-event btn-inverse" data-id="10" data-day="2" data-start="11" data-duration="1">
                    DESCANS
                </li>
                <li class="tt-event btn-success" data-id="10" data-day="2" data-start="12" data-duration="2">
                    2 DAM M9<br />
                    20.2<br />
                </li>
                </li>
                <li class="tt-event btn-inverse" data-id="10" data-day="3" data-start="4" data-duration="1">
                    DESCANS
                </li>
                <li class="tt-event btn-inverse" data-id="10" data-day="3" data-start="7" data-duration="1">
                    MIGDIA
                </li>
                <li class="tt-event btn-inverse" data-id="10" data-day="3" data-start="11" data-duration="1">
                    DESCANS
                </li>
                <li class="tt-event btn-info" data-id="10" data-day="3" data-start="12" data-duration="2">
                    2 ASIX M7<br />
                    20.4<br />
                </li>
                <li class="tt-event btn-primary	" data-id="10" data-day="3" data-start="14" data-duration="1">
                    2 ASIX M11<br />
                    20.4<br />
                </li>
                </li>
                <li class="tt-event btn-inverse" data-id="10" data-day="4" data-start="4" data-duration="1">
                    DESCANS
                </li>
                <li class="tt-event btn-inverse" data-id="10" data-day="4" data-start="7" data-duration="1">
                    MIGDIA
                </li>
                <li class="tt-event btn-primary" data-id="10" data-day="4" data-start="8" data-duration="1">
                    2 ASIX M11<br />
                    20.4<br />
                </li>
                <li class="tt-event btn-danger" data-id="10" data-day="4" data-start="9" data-duration="2">
                    2 DAM M8<br />
                    20.2<br />
                </li>
                <li class="tt-event btn-inverse" data-id="10" data-day="4" data-start="11" data-duration="1">
                    DESCANS
                </li>
                <li class="tt-event btn-primary" data-id="10" data-day="4" data-start="12" data-duration="2">
                    2 ASIX M11<br />
                    20.4<br />
                </li>

            </ul>
            <div class="tt-times">
				<div class="tt-time" data-time="0">
                    08<span class="hidden-phone">:00</span></div>
                <div class="tt-time" data-time="0">
                    09<span class="hidden-phone">:00</span></div>
                <div class="tt-time" data-time="1">
                    10<span class="hidden-phone">:00</span></div>
                <div class="tt-time" data-time="2">
                    11</div>
                <div class="tt-time" data-time="3">
                    11:30</div>
                <div class="tt-time" data-time="4">
                    12:30</div>
                <div class="tt-time" data-time="5">
                    13:30</div>
                <div class="tt-time" data-time="6">
                    14:30</div>
                <div class="tt-time" data-time="7">
                    15:30</div>
                <div class="tt-time" data-time="8">
                    16:30</div>
                <div class="tt-time" data-time="9">
                    17:30</div>
                <div class="tt-time" data-time="10">
                    18:30</div>
                <div class="tt-time" data-time="11">
                    19<span class="hidden-phone">:00</span></div>
                <div class="tt-time" data-time="12">
                    20<span class="hidden-phone">:00</span></div>    
                <div class="tt-time" data-time="13">
                    21<span class="hidden-phone">:00</span></div>    
            </div>
            <div class="tt-days">
                <div class="tt-day" data-day="0">
                    Dl.</div>
                <div class="tt-day" data-day="1">
                    Dt.</div>
                <div class="tt-day" data-day="2">
                    Dc.</div>
                <div class="tt-day" data-day="3">
                    Dj.</div>
                <div class="tt-day" data-day="4">
                    Dv.</div>
            </div>
        </div>
</div>