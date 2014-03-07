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

	$('#ranking_initial_date_end_date').dataTable( {
		"bFilter": false,
		"bInfo": false,
		"sDom": 'T<"clear">lfrtip',
		"aLengthMenu": [[10, 25, 50,100,200,500,1000,-1], [10, 25, 50,100,200,500,1000, "All"]],		
		"oTableTools": {
			"sSwfPath": "<?php echo base_url('assets/grocery_crud/themes/datatables/extras/TableTools/media/swf/copy_csv_xls_pdf.swf');?>",
				"aButtons": [
					{
						"sExtends": "copy",
						"sButtonText": "<?php echo lang("Copy");?>",
						"mColumns": "visible"
					},
					{
						"sTitle": "<?php echo lang('ranking_incidents_by_date_1').$_POST['data_inicial'].lang('incidents_by_date_2').$_POST['data_final'];?>",
						"sExtends": "csv",
						"sButtonText": "CSV",
						"mColumns": "visible"
					},
					{
						"sTitle": "<?php echo lang('ranking_incidents_by_date_1').$_POST['data_inicial'].lang('incidents_by_date_2').$_POST['data_final'];?>",
						"sExtends": "xls",
						"sButtonText": "XLS",
						"mColumns": "visible"
					},
					{
						"sTitle": "<?php echo lang('ranking_incidents_by_date_1').$_POST['data_inicial'].lang('incidents_by_date_2').$_POST['data_final'];?>",
						"sExtends": "pdf",
						"sPdfOrientation": "portrait",
						"sButtonText": "PDF",
						"mColumns": "visible"
					},
					{
						"sExtends": "print",
						"sButtonText": "<?php echo lang("Print");?>",
						"mColumns": "visible"
					},
				]
},
        "iDisplayLength": 50,
        "aaSorting": [[ 4, "desc" ]],
	    "aoColumns": [
	    { "bVisible": false },
	    null,
	    null,
	    null,
	    null
	  ],        
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
	if(isset($_POST['data_inicial'])){
		$data_ini=strtotime($_POST['data_inicial']);
	}
	if(isset($_POST['data_final'])){
		$data_fi=strtotime($_POST['data_final']);
	}	
	if(isset($_POST['top'])){
		$top=$_POST['top'];
	} else {
		$top = 10;
	}	

?>

<!-- TITLE -->
<div style='height:30px;'></div>
	<div style="margin:10px;">
		<h2><?php echo $title; ?></h2>
	</div>

	<!-- FORM -->    
	<div style="width:50%; margin:20px auto;">
		<form method="post" action="informe_centre_ranking_di_df_1" class="form-horizontal" role="form">
			<table class="table table-bordered" cellspacing="10" cellpadding="5">
				<div class="form-group">
					<tr>
						<td><label for="data_inicial"><?php echo lang('select_initial_date');?></label></td>
						<td><input class="form-control" id="data_inicial" type="text" name="data_inicial" value="<?php if(isset($data_ini)){ echo date('d-m-Y',$data_ini); } else { echo date('d-m-Y'); } ?>"/></td>
					</tr>
				</div>		

				<div class="form-group">
					<tr>
						<td><label for="data_final"><?php echo lang('select_end_date');?></label></td>
						<td><input class="form-control" id="data_final" type="text" name="data_final" value="<?php if(isset($data_fi)){ echo date('d-m-Y',$data_fi); } else { echo date('d-m-Y'); } ?>"/></td>
					</tr>
				</div>

				<div class="form-group">
					<tr>
						<td><label for="top"><?php echo lang('write_max_results');?></label></td>
						<td><input class="form-control" id="top" type="text" name="top" value="<?php echo $top; ?>"/></td>
					</tr>
				</div>

				<tr><td colspan="2" style="text-align:center;"><input type="submit" value="Veure l'informe" class="btn btn-primary"/></td></tr>
			</table>
		</form>

<!-- DATATABLES -->

<?php

if($_POST){  
	$contador = count($_POST);	
	$i=0;
	foreach($faltes as $falta):

		// Si hi ha resultats entre les dates indicades
		if( ($falta['data'] >= $data_ini) && ($falta['data'] <= $data_fi)){
			// La primera iteració mostrem el títol i les capçaleres de la taula
			if($i==0){
				echo "<h4><center>".lang('ranking_incidents_by_date_1').$_POST['data_inicial'].lang('incidents_by_date_2').$_POST['data_final']."</center></h4>";

// mostrem la taula
	$posicio=1;
?>

<table class="table table-striped table-bordered table-hover table-condensed" id="ranking_initial_date_end_date">
 <thead style="background-color: #d9edf7;">
  <tr>
  	 <th>Data</th>
     <th>Posició</th>
     <th>Alumne</th>
     <th>Grup</th>
     <th>Total faltes No Justificades</th>     
  </tr>
 </thead>
 <tbody>

  <?php 
	$i++;
			} // if($i==0)
	// Si no hem arribat al max. elements a mostrar continuem mostrant		
	if($posicio <= $top){ ?>
		<tr align="center" class="{cycle values='tr0,tr1'}">
			<td><?php echo $falta['data'];?></td>
			<td><?php echo $posicio;$posicio++;?></td>
			<td><?php echo $falta['estudiant'];?></td>
			<td><?php echo $falta['grup'];?></td>     
			<td><?php echo $falta['total'];?></td>
		</tr>
   <?php 
	} // if($posicio <= $top)
} // Hi ha resultats
endforeach; 
if($i==0) { echo "No hi ha incidències per a aquest rang de dades."; }
} ?>
 </tbody>
</table>

</div>		