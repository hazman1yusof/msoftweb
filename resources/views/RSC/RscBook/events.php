<?php 
  include_once('../../../../header.php'); 
?>

<head>
</head>
<body>
<style>
  .num{
    width:20px;
  }

</style>

    
   
    <!-------------------------------- jqgrid  ------------------>
    <a href="event.php">Back</a>

      <div class='col-md-12' style="padding:0 0 15px 0">
            <table id="jqGrid" class="table table-striped"></table>
            <div id="jqGridPager"></div>
        </div>
      <!-------------------------------- End Search + table ------------------>
    
   <div id="dialogForm" title="Add Form" >
      <form class='form-horizontal' style='width:99%' id='formdata'>
            
        <div class>
           <label class="col-md-2 control-label" for="resourcecode">Resource</label>  
          <div class="col-md-4">
            <div class='input-group'>
            <input id="resourcecode" name="resourcecode" type="text" class="form-control input-sm" data-validation="required">
            <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
            </div>
            <span class="help-block"></span>
          </div>
       
        
          <label class="col-md-2 control-label" for="DeptReq">Department Request</label>  
          <div class="col-md-4">
            <div class='input-group'>
            <input id="DeptReq" name="DeptReq" type="text" class="form-control input-sm" data-validation="required">
            <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
            </div>
            <span class="help-block"></span>
          </div>
        </div>

         <div class="form-group">
            <label class="col-md-2 control-label" for="staffReq">Staff Request</label>  
            <div class="col-md-8">
            <input id="staffReq" name="staffReq" type="text" class="form-control input-sm" data-validation="required">
            </div>
          </div>
                   
                <div class="form-group">
                  <label class="col-md-2 control-label" for="startDate"> Start Date </label>  
                      <div class="col-md-4">
                      <input id="startDate" name="startDate" type="date" class="form-control input-sm">
                      </div>


                  <label class="col-md-2 control-label" for="startDate"> Start Time </label>  
                      <div class="col-md-4">
                      <input id="startDate" name="startDate" type="time" class="form-control input-sm">
                      </div>
                    </div>
        
                  <div class="form-group">
                  <label class="col-md-2 control-label" for="endDate"> End Date </label>  
                      <div class="col-md-4">
                       <input id="endDate" name="endDate" type="date" class="form-control input-sm">
                      </div>

                    <label class="col-md-2 control-label" for="startDate"> End Time </label>  
                      <div class="col-md-4">
                      <input id="startDate" name="startDate" type="time" class="form-control input-sm">
                      </div>
                    </div>

          <div class="form-group">
             <label class="col-md-2 control-label" for="attach">Attach Equipment</label>  
             <div class="col-md-4"> 
                <textarea wrap="hard" class="form-control col-md" rows="5" cols="50" id="attach" name="attach" type="text" maxlength="100">
                </textarea>
                    </div>

              <label class="col-md-2 control-label" for="remarks">Remarks</label> 
                <div class="col-md-4"> 
                <textarea wrap="hard" class="form-control col-md" rows="5" cols="50" id="remarks" name="remarks" type="text" maxlength="100">
                </textarea>
                </div>
          </div>            
      </form>
    </div>

  <?php 
    include_once('../../../../footer.php'); 
  ?>

 
  
  <!-- JS Implementing Plugins -->

  <!-- JS Customization -->

  <!-- JS Page Level -->
  <script src="event.js"></script>
  <script src="../../../../assets/js/utility.js"></script>
  <script src="../../../../assets/js/dialogHandler.js"></script>
<script>
    
</script>
</body>
</html>