<?php 
    include_once('../../../../header.php'); 
?>
<body>

    <div class="container"> 
  <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#doctor">Doctor</a></li>
    <li><a data-toggle="tab" href="#resource">Resource</a></li>
    <li><a data-toggle="tab" href="#ot">Operation Theater</a></li>
  </ul>


  <div class="tab-content">
    <div id="Doctor" class="tab-pane fade in active">
      <h3>Doctor</h3>
      <table id="d_doctor" ></table>

      <div class="form-group">
          <label class="col-md-2 control-label" for="resourcecode">Doctor Code</label>    
          <div class="col-md-4">
                      <div class='input-group'>
            <input id="resourcecode" name="resourcecode" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit>
            </div>
          </div>
                
                  <label class="col-md-2 control-label" for="description">Description</label>  
                      <div class="col-md-4">
                      <input id="description" name="description" type="text" class="form-control input-sm" data-validation="required">
                      </div>
        </div>
                
               <div class="form-group">  
          <div class="col-md-4">
                      <div class='input-group'>
            <input id="Status" name="Status" type="hidden" class="form-control input-sm" data-validation="required">
            </div>
          </div>
                </div>
                
                <div class="form-group">
                      <label class="col-md-2 control-label" for="comment">Comment</label>  
                      <div class="col-md-6">
                      <input id="comment" name="comment" type="text" class="form-control input-sm" data-validation="required">
                      </div>
        </div>
        
        <div class="form-group">
          <label class="col-md-2 control-label" for="recstatus">Record Status</label>  
          <div class="col-md-4">
          <label class="radio-inline"><input type="radio" name="recstatus" value='A' checked>Active</label>
          <label class="radio-inline"><input type="radio" name="recstatus" value='D'>Deactive</label>       
                </div>
        </div>




    </div>
    <div id="resource" class="tab-pane fade">
      <h3>Resource</h3>

    </div>

    <div id="ot" class="tab-pane fade">
      <h3>Operation Theater</h3>

    </div>

  </div>
</div>
   
             <?php 
        include_once('../../../../footer.php'); 
    ?>
    
    <!-- JS Implementing Plugins -->

    <!-- JS Customization -->

    <!-- JS Page Level -->
    <script src="test.js"></script>
    <script src="../../../../assets/js/utility.js"></script>
    <script src="../../../../assets/js/dialogHandler.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

<script>
        
</script>
</body>
</html>

