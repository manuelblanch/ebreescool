
<!-- TITLE -->
<div style='height:30px;'></div>
	<div style="margin:10px;">
		<h2><?php echo lang('reports_educational_center_reports_student_emails'); ?></h2>
	</div>	


	<!-- FORM -->    
	<div style="width:40%; margin:0px auto;">
		<form method="post" action="#" class="form-horizontal" role="form">
			<table class="table table-bordered" cellspacing="10" cellpadding="5">
				<div class="form-group">
					<tr>
						<td><label for="data_inicial">Select an option:</label></td>
						<td>
							<input type="radio" name="opcio" checked="checked" value="P"/> Personal accounts<br />
							<input type="radio" name="opcio" value="C"> Center accounts<br />
							<input type="radio" name="opcio" value="A"> Booth accounts 
						</td>
					</tr>	
				</div>

				<tr><td colspan="2" style="text-align:center;"><input type="submit" value="Veure l'informe" class="btn btn-primary"/></td></tr>
			</table>
		</form>
	</div>	