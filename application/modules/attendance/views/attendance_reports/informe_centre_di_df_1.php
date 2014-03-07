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

	$('#initial_date_end_date').dataTable( {
		"bFilter": false,
		"bInfo": false,
		"sDom": 'T<"clear">lfrtip',
		"aLengthMenu": [[10, 25, 50,100,200,500,1000,-1], [10, 25, 50,100,200,500,1000, "All"]],
	
	// El primer element de la taula l'utilitzem per a ordenar la taula, però no es visualitza.
	// Si no ho fem així la taula no s'ordena correctament
    "aoColumns": [
    { "bVisible": false },
    null,
    null,
    null,
    null,
    null,
    null
  ],

		"oTableTools": {
			"sSwfPath": "<?php echo base_url('assets/grocery_crud/themes/datatables/extras/TableTools/media/swf/copy_csv_xls_pdf.swf');?>",
				"aButtons": [
					{
						"sExtends": "copy",
						"sButtonText": "<?php echo lang("Copy");?>",
						"mColumns": "visible"
					},
					{
						"sTitle": "<?php echo lang('incidents_by_date_1').$_POST['data_inicial'].lang('incidents_by_date_2').$_POST['data_final'];?>",
						"sExtends": "csv",
						"sButtonText": "CSV",
						"mColumns": "visible"
					},
					{
						"sTitle": "<?php echo lang('incidents_by_date_1').$_POST['data_inicial'].lang('incidents_by_date_2').$_POST['data_final'];?>",
						"sExtends": "xls",
						"sButtonText": "XLS",
						"mColumns": "visible"
					},
					{
						"sTitle": "<?php echo lang('incidents_by_date_1').$_POST['data_inicial'].lang('incidents_by_date_2').$_POST['data_final'];?>",
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
        "aaSorting": [[ 0, "asc" ],[ 2, "asc" ],[ 5, "asc" ],[ 6, "asc" ],[ 7, "asc" ]],
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

	// Guardem la data amb format UNIX per a poder fer les comparacions
	if(isset($_POST['data_inicial'])){
		$data_ini = strtotime($_POST['data_inicial']);
	}
	if(isset($_POST['data_final'])){
		$data_fi = strtotime($_POST['data_final']);
	}	
?>

<!-- TITLE -->
<div style='height:30px;'></div>
	<div style="margin:10px;">
		<h2><?php echo $title; ?></h2>
	</div> 
	<!-- FORM -->    
	<div style="width:60%; margin:20px auto;">
		<form method="post" action="informe_centre_di_df_1" class="form-horizontal" role="form">
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
						<td valign="top"><label for="incident"><?php echo lang('select_type_of_incident');?></label></td>
						<td>
							<input type="checkbox" name="F" value="1" <?php if(isset($_POST['F'])){ ?>checked <?php } ?> > F</input><br />
							<input type="checkbox" name="FJ" value="2" <?php if(isset($_POST['FJ'])){ ?>checked <?php } ?> > FJ</input><br />
							<input type="checkbox" name="R" value="3" <?php if(isset($_POST['R'])){ ?>checked <?php } ?> > R</input><br />
							<input type="checkbox" name="RJ" value="4" <?php if(isset($_POST['RJ'])){ ?>checked <?php } ?> > RJ</input><br />
							<input type="checkbox" name="E" value="5" <?php if(isset($_POST['E'])){ ?>checked <?php } ?> > E</input>
						</td>
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
	foreach($incidencia as $falta):

		// Si hi ha incidències entre les dates indicades
		if( ($falta['dia'] >= $data_ini) && ($falta['dia'] <= $data_fi) && array_key_exists($falta['incidencia'], $_POST)){
			// La primera iteració mostrem el títol i les capçaleres de la taula
			if($i==0){
				echo "<h4><center>".$title."</center></h4>";

// mostrem la taula
?>

<table class="table table-striped table-bordered table-hover table-condensed" id="initial_date_end_date">
 <thead style="background-color: #d9edf7;">
  <tr>
     <th>datetime</th>   	
     <th>Dia</th> 
     <th>Grup</th>
     <th>Alumne</th>
     <th>Incidència</th>
     <th>Crèdit</th>
     <th>Professor</th>
  </tr>
 </thead>
 <tbody>

  <?php 
	$i++;
			} // if($i==0)

   ?>
   <tr align="center" class="{cycle values='tr0,tr1'}">
     <td><?php echo $falta['dia'];?></td>   	
     <td><?php echo date("d-m-Y",$falta['dia']);?></td>
	 <td><?php echo $falta['grup'];?></td>     
     <td><?php echo $falta['estudiant'];?></td>
     <td><?php echo $falta['incidencia'];?></td>
     <td><?php echo $falta['credit'];?></td>
     <td><?php echo $falta['professor'];?></td>
   </tr>
  <?php 
  	// mirem si hem arribat al últim element  
  	if($i==$contador){
  ?>
 </tbody>
</table>
<?php $i++; 
	} // Ultim element
} // Hi ha incidències
endforeach;
if($i==0) { echo "No hi ha incidències per a aquest rang de dades."; }
} ?>

</div>	