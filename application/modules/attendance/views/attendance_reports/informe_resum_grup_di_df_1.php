<!-- Date Picker -->
<script>
$(function() {
$( "#data_inicial" ).datepicker({ dateFormat: 'dd-mm-yy' });
$( "#data_final" ).datepicker({ dateFormat: 'dd-mm-yy' });
});
</script>
<!-- Data Table -->
<script>
$(document).ready( function () {

	$('#groups_fault_reports').dataTable( {
		"bFilter": false,
		"bInfo": false,
		"sDom": 'T<"clear">lfrtip',
		"aLengthMenu": [[10, 25, 50,100,200,500,1000,-1], [10, 25, 50,100,200,500,1000, "All"]],		
		"oTableTools": {
			"sSwfPath": "<?php echo base_url('assets/grocery_crud/themes/datatables/extras/TableTools/media/swf/copy_csv_xls_pdf.swf');?>",
				"aButtons": [
					{
						"sExtends": "copy",
						"sButtonText": "<?php echo lang("Copy");?>"
					},
					{
						"sTitle": "<?php echo lang('group_incidents_summary').$selected_group.lang('group_incidents_summary_between').$_POST['data_inici'].lang('incidents_by_date_2').$_POST['data_fi'];?>",
						"sExtends": "csv",
						"sButtonText": "CSV"
					},
					{
						"sTitle": "<?php echo lang('group_incidents_summary').$selected_group.lang('group_incidents_summary_between').$_POST['data_inici'].lang('incidents_by_date_2').$_POST['data_fi'];?>",
						"sExtends": "xls",
						"sButtonText": "XLS"
					},
					{
						"sTitle": "<?php echo lang('group_incidents_summary').$selected_group.lang('group_incidents_summary_between').$_POST['data_inici'].lang('incidents_by_date_2').$_POST['data_fi'];?>",
						"sExtends": "pdf",
						"sPdfOrientation": "portrait",
						"sButtonText": "PDF"
					},
					{
						"sExtends": "print",
						"sButtonText": "<?php echo lang("Print");?>"
					},
				]
},
        "iDisplayLength": 50,
        "aaSorting": [[ 5, "asc" ],[ 6, "asc" ],[ 7, "asc" ]],
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
	    }
		
	});
} );

</script>

<?php 
	//echo "Hi ha ".$count_alumnes." alumnes<br />";
	//echo "El grup sel·leccionat és ".$selected_group."<br />";

$alumne = array();
$contador = 0;
if($_POST){

	$ini = strtotime($_POST['data_inici']);
	//echo $_POST['data_inici']."-".$ini."<br />";
	$fi = strtotime($_POST['data_fi']);
	//echo $_POST['data_fi']."-".$fi."<br />";
} else {

	$ini = strtotime(date("d-m-Y"));
	$fi = strtotime(date("d-m-Y"));
}


	foreach($all_students_in_group as $student){
		$suma = 0;
		$alumne[$contador]['fullName'] = $student->givenName." ".$student->sn;
		$aleatori = rand(0,3);
		if($aleatori!=0){
			$fecha_rand=rand($ini,$fi);
			$alumne[$contador]['F']=$aleatori." ".date($fecha_rand);
		} else {
			$alumne[$contador]['F']=$aleatori;
		}
		$aleatori = rand(0,3);
		if($aleatori!=0){
			$fecha_rand=rand($ini,$fi);
			$alumne[$contador]['FJ']=$aleatori." ".date($fecha_rand);
		} else {
			$alumne[$contador]['FJ']=$aleatori;
		}
		$aleatori = rand(0,3);
		if($aleatori!=0){
			$fecha_rand=rand($ini,$fi);
			$alumne[$contador]['R']=$aleatori." ".date($fecha_rand);
		} else {
			$alumne[$contador]['R']=$aleatori;
		}
		$aleatori = rand(0,3);
		if($aleatori!=0){
			$fecha_rand=rand($ini,$fi);
			$alumne[$contador]['RJ']=$aleatori." ".date($fecha_rand);
		} else {
			$alumne[$contador]['RJ']=$aleatori;
		}
		$aleatori = rand(0,3);
		if($aleatori!=0){
			$fecha_rand=rand($ini,$fi);
			$alumne[$contador]['E']=$aleatori." ".date($fecha_rand);
		} else {
			$alumne[$contador]['E']=$aleatori;
		}
		/*							
		$alumne[$contador]['FJ']=rand(0,3);
		$alumne[$contador]['R']=rand(0,3);
		$alumne[$contador]['RJ']=rand(0,3);
		$alumne[$contador]['E']=rand(0,3);
		*/
		$suma = $alumne[$contador]['F']+$alumne[$contador]['FJ']+$alumne[$contador]['R']+$alumne[$contador]['RJ']+$alumne[$contador]['E'];		
		$alumne[$contador]['Total']=$suma;										
		$contador++;
	}
	//echo "<pre>";
	//print_r($alumne);
	//echo "</pre>";

?>

<!-- TITLE -->
<div style='height:30px;'></div>
	<div style="margin:10px;">
		<h2><?php echo lang('reports_group_reports_incidents_by_date'); ?></h2>
	</div>


	<!-- FORM -->    
	<div style="width:50%; margin:0px auto;">
		<form method="post" action="#" class="form-horizontal" role="form">
			<table class="table table-bordered" cellspacing="10" cellpadding="5">


				<div class="form-group">
					<tr>
						<td><label for="grup">Selecciona el grup:</label></td>
						<td><select class="chosen-select" data-place_holder="TODO" style="width:200px;" id="grup" name="grup" data-size="5" data-live-search="true">
							<?php foreach ($grups as $key => $value) { ?>
								<option value="<?php echo $key ?>" ><?php echo $value ?></option>
							<?php } ?>
							</select>	
						</td>
					</tr>
				</div>

				<div class="form-group">
					<tr>
						<td><label for="data_inicial">Write the initial Date:</label></td>
						<td><input class="form-control" id="data_inicial" type="text" name="data_inici" value="<?php echo date("d-m-Y")?>"/></td>
					</tr>
				</div>		

				<div class="form-group">
					<tr>
						<td><label for="data_final">Write the end Date:</label></td>
						<td><input class="form-control" id="data_final" type="text" name="data_fi" value="<?php echo date("d-m-Y")?>"/></td>
					</tr>
				</div>

				<tr><td colspan="2" style="text-align:center;"><input type="submit" value="Veure l'informe" class="btn btn-primary"/></td></tr>
			</table>
		</form>

<!-- Proves datatables -->
<?php if($_POST){ ?>
<table class="table table-striped table-bordered table-hover table-condensed" id="groups_fault_reports">
 <thead style="background-color: #d9edf7;">
  <tr>
     <th>Alumne</th>   	
     <th>F</th> 
     <th>FJ</th>
     <th>R</th>
     <th>RJ</th>
     <th>E</th>
     <th>Total</th>
  </tr>
 </thead>
 <tbody>
  <!-- Iteration that shows teacher groups for selected day-->
<?php 
	for($i=0; $i<$count_alumnes; $i++){
?>

   <tr align="center" class="{cycle values='tr0,tr1'}">
     <td><?php echo $alumne[$i]['fullName'];?></td>   	
     <td><?php if($alumne[$i]['F']==0){ echo $alumne[$i]['F']; } else { $faltes = explode(" ",$alumne[$i]['F']); echo $faltes[0]; }?></td>
	 <td><?php if($alumne[$i]['FJ']==0){ echo $alumne[$i]['FJ']; } else { $faltes = explode(" ",$alumne[$i]['FJ']); echo $faltes[0]; }?></td>
     <td><?php if($alumne[$i]['R']==0){ echo $alumne[$i]['R']; } else { $faltes = explode(" ",$alumne[$i]['R']); echo $faltes[0]; }?></td>
     <td><?php if($alumne[$i]['RJ']==0){ echo $alumne[$i]['RJ']; } else { $faltes = explode(" ",$alumne[$i]['RJ']); echo $faltes[0]; }?></td>
     <td><?php if($alumne[$i]['E']==0){ echo $alumne[$i]['E']; } else { $faltes = explode(" ",$alumne[$i]['E']); echo $faltes[0]; }?></td>
     <td><?php echo $alumne[$i]['Total'];?></td>
   </tr>
<?php
	}
?>
 </tbody>
</table>
<?php } ?>
<!-- Fi proves datatable -->

	</div>		