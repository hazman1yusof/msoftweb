<?php 
  include_once('../../../../header.php'); 
?>

<head>

  <link rel="stylesheet" href="css/calendar.css">

</head>
<body>
<style>
  .num{
    width:20px;
  }
  .glyphicon {
    font-size: 15px;
}

</style>
   
  <!-------------------------------- Search + table ---------------------->
  <div class='row'>
    <div class='col-md-12' style="padding:0 0 15px 0;">
      <div class="form-group"> 
        <div class="col-md-2">
          <label class="control-label" for="resourcecode">Resource</label>  
          <div class='input-group'>
          <input id="resourcecode" name="resourcecode" type="text" class="form-control input-sm"/>
          <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
          </div>
          <span class="help-block"></span>
        </div>
        </div>
    </div>
    

  <div class="page-header">
<div class="container">
  <div class="pull-right form-inline">
      <div class="btn-group">
        <button class="btn btn-primary" data-calendar-nav="prev"><< Prev</button>
        <button class="btn" data-calendar-nav="today">Today</button>
        <button class="btn btn-primary" data-calendar-nav="next">Next >></button>
      </div>
      <div class="btn-group">
        <button class="btn" data-calendar-view="year">Year</button>
        <button class="btn" data-calendar-view="month">Month</button>
        <button class="btn" data-calendar-view="week">Week</button>
        <button class="btn" data-calendar-view="day">Day</button>
      </div>
    </div>

    <h3></h3>

  </div>

 
  </div>  

  <div class="span12">
      <div id="calendar"></div>
  </div> 
</div>  



      <div class="container">
         <div class="button">
            <button type="navButtonAdd" class="ui-pg-button"><span class="glyphicon glyphicon-plus"><a class="button" data-popup-open="popup-1" href="#">Add</a></span>
            <button type="navButtonAdd" class="ui-pg-button"><span class="glyphicon glyphicon-edit"></span>
            <button type="navButtonAdd" class="ui-pg-button"><span class="glyphicon glyphicon-trash"></span>
            <button type="navButtonAdd" class="ui-pg-button"><span class="glyphicon glyphicon-info-sign"></span>
            <button type="navButtonAdd" class="ui-pg-button"><span class="glyphicon glyphicon-refresh"></span>
          </div>
       </div>

       
 
      <div class="popup" data-popup="popup-1">
          <div class="popup-inner">
          
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
       
        
          <label class="col-md-2 control-label" for="departmentReq">Department Request</label>  
          <div class="col-md-4">
            <div class='input-group'>
            <input id="departmentReq" name="departmentReq" type="text" class="form-control input-sm" data-validation="required">
            <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
            </div>
            <span class="help-block"></span>
          </div>
        </div>

         <div class="form-group">
            <label class="col-md-2 control-label" for="adduser">Staff Request</label>  
            <div class="col-md-8">
            <input id="adduser" name="adduser" type="text" class="form-control input-sm" data-validation="required">
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



              <a data-popup-close="popup-1" href="#">Close</a></p>
              <a class="popup-close" data-popup-close="popup-1" href="#">x</a>
          </div>
      </div>
     
  <!-------------------------------- jqgrid 
      <div class='col-md-12' style="padding:0 0 15px 0">
            <table id="jqGrid" class="table table-striped"></table>
            <div id="jqGridPager"></div>
        </div>
 ------------------>

   
 
         <div class="sideleft" style="width:29%; margin:1% auto; overflow:auto">
            <div id="datepicker" style="margin:2% auto; width:98%;"></div>
            <table id="gridsess"></table>
            <div id="pagersess"></div>
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
       
        
          <label class="col-md-2 control-label" for="departmentReq">Department Request</label>  
          <div class="col-md-4">
            <div class='input-group'>
            <input id="departmentReq" name="departmentReq" type="text" class="form-control input-sm" data-validation="required">
            <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
            </div>
            <span class="help-block"></span>
          </div>
        </div>

         <div class="form-group">
            <label class="col-md-2 control-label" for="adduser">Staff Request</label>  
            <div class="col-md-8">
            <input id="adduser" name="adduser" type="text" class="form-control input-sm" data-validation="required">
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

  
  <!-- JS Implementing Plugins -->

  <!-- JS Customization -->

  <!-- JS Page Level -->
  <script src="event.js"></script>
  <script src="../../../../assets/js/utility.js"></script>
  <script src="../../../../assets/js/dialogHandler.js"></script>

  <script type="text/javascript" src="components/underscore/underscore-min.js"></script>
  <script type="text/javascript" src="js/calendar.js"></script>
  <script type="text/javascript" src="js/app.js"></script>

  <script type="text/javascript" src="data.js"></script>

<script>
    
</script>
</body>
</html>