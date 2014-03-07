<!-- Data Table -->
<script>
$(document).ready( function () {

	$('#month_summary').dataTable( {
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
						"sTitle": "<?php echo lang('reports_group_reports_monthly_report').'-'.$_POST['mes'].'-'.$_POST['any'];?>",
						"sExtends": "csv",
						"sButtonText": "CSV"
					},
					{
						"sTitle": "<?php echo lang('reports_group_reports_monthly_report');?>",
						"sExtends": "xls",
						"sButtonText": "XLS"
					},
					{
						"sTitle": "<?php echo lang('reports_group_reports_monthly_report').'-'.$mes[$_POST['mes']].'-'.$_POST['any'];?>",
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
/* Dades Alumne */
/*
echo "<pre>";
print_r($all_students_in_group);
echo "</pre>";
*/
$alumne =array();
$contador = 0;

foreach($all_students_in_group as $student){
$alumne[$contador]['codi']= $student->uidnumber;
$alumne[$contador]['nom']= $student->givenName." ".$student->sn;
$month = rand(1,12);
$year = rand(2008,2013);
$faults = rand(0,3);
$alumne[$contador]['mes']= $month;
$alumne[$contador]['any']= $year;
$alumne[$contador]['faltes']= $faults;
$contador++;
}
/*
echo "<pre>";
print_r($alumne);
echo "</pre>";
*/
?>

<!-- TITLE -->
<div style='height:30px;'></div>
	<div style="margin:10px;">
		<h2><?php echo lang('reports_group_reports_monthly_report'); ?></h2>
	</div>    
	<!-- FORM -->    
	<div style="width:50%; margin:20px auto;">
		<form method="post" action="informe_resum_grup_faltes_mes_1" class="form-horizontal" role="form">
			<table class="table table-bordered" cellspacing="10" cellpadding="5">
				<div class="form-group">
					<tr>
						<td><label for="data_informe">Select Group:</label></td>
						<td>
							<select class="chosen-select" data-place_holder="TODO" style="width:400px;" id="grup" name="grup" data-size="5" data-live-search="true">
							<?php foreach ($grups as $key => $value) { ?>
								<option value="<?php echo $key ?>" <?php if(isset($_POST['grup']) && $key==$_POST['grup']){ ?> selected <?php }?> > <?php echo $value ?></option>
							<?php } ?>
							</select>
						</td>
					</tr>
				</div>		
				<div class="form-group">
					<tr>
						<td><laber for="hora_informe">Select Month:</label></td>
						<td>
							<select class="chosen-select" data-place_holder="TODO" style="width:400px;" id="mes" name="mes" data-size="5" data-live-search="true">
							<?php foreach ($mes as $key => $value) { ?>
								<option value="<?php echo $key ?>" <?php if(isset($_POST['mes']) && $key==$_POST['mes']){ ?> selected <?php }?> > <?php echo $value ?></option>
							<?php } ?>
							</select>		
						</td>
					</tr>	
				</div>
				<div class="form-group">
					<tr>
						<td valign="top"><label for="incident">Select Year:</label></td>
						<td>
							<select class="chosen-select" data-place_holder="TODO" style="width:400px;" id="any" name="any" data-size="5" data-live-search="true">
							<?php foreach ($any as $key => $value) { ?>
								<option value="<?php echo $key; ?>" <?php if(isset($_POST['any']) && $value==$_POST['any']){ ?> selected <?php }?> > <?php echo $value ?></option>
							
							<?php } ?>
							</select>		
						</td>
					</tr>	
				</div>
				<tr><td colspan="2" style="text-align:center;"><input type="submit" value="Veure l'informe" class="btn btn-primary"/></td></tr>
			</table>
		</form>	

<!-- Proves datatables -->

<?php 

if($_POST){
	$contador = count($_POST);	
	echo "<h4><center>".lang('reports_group_reports_monthly_report')."</center></h4>";
?>

<table class="table table-striped table-bordered table-hover table-condensed" id="month_summary">
 <thead style="background-color: #d9edf7;">
  <tr>
     <th>Codi</th>
     <th>Alumne</th>
     <th>Faltes Injustificades</th>
  </tr>
 </thead>
 <tbody>
  <!-- Iteration that shows teacher groups for selected day-->
   <?php for($i=0; $i<count($alumne);$i++){ ?>
   <?php if($alumne[$i]['mes']==$_POST['mes'] && $alumne[$i]['any']==$_POST['any'] && $alumne[$i]['faltes']>0){ ?>
   <tr align="center" class="{cycle values='tr0,tr1'}">
	 <td><?php echo $alumne[$i]['codi'];?></td>     
     <td><?php echo $alumne[$i]['nom'];?></td>
     <td><?php echo $alumne[$i]['faltes'];?></td>
   </tr>
   <?php } ?>
   <?php } ?>

 </tbody>
</table>
<?php 
} ?>

<!-- Fi proves datatable -->		