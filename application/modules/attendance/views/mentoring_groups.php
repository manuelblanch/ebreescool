<script>

$(function() {

    //*****************************
    //* CLASSROOMGROUP DROPDOWN  **
    //*****************************

    //Jquery select plugin: http://ivaynberg.github.io/select2/
    $("#classroom_groups").select2(); 

    $('#classroom_groups').on("change", function(e) {   
        selectedValue = $("#classroom_groups").select2("val");
        var pathArray = window.location.pathname.split( '/' );
        var secondLevelLocation = pathArray[1];
        var baseURL = window.location.protocol + "//" + window.location.host + "/" + secondLevelLocation + "/index.php/attendance/mentoring_groups";
        //alert(baseURL + "/" + selectedValue);
        window.location.href = baseURL + "/" + selectedValue;

    });

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

</script>

<div class="container">

 <center>
  <select id="classroom_groups" style="width: 400px">
  		   <option></option>
   <?php foreach( (array) $classroom_groups as $classroom_group_id => $classroom_group): ?>
		   <?php if( $classroom_group_id == $default_classroom_group_id): ?>
            <option value="<?php echo $classroom_group_id; ?>" selected="selected"><?php echo $classroom_group; ?></option>
           <?php else: ?> 
            <option value="<?php echo $classroom_group_id; ?>" ><?php echo $classroom_group; ?></option>
           <?php endif; ?> 
   <?php endforeach; ?>	
  </select> 
  <br/><br/>
      <div class="input-append date">
        <input type="text" class="span2" value="<?php echo $check_attendance_date;?>"/><span class="add-on"><i class="icon-calendar"></i></span>
      </div>
 </center>

</div>
