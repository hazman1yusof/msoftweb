<?php 
  include_once('../../../../header.php'); 
?>
<style>
</style>
<body>


   <div class='col-md-12'>
      <div class='panel panel-info'>
        <div class="panel-body">
          <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tab-doctor" form='#f_tab-doctor'>Doctor</a></li>
            <li><a data-toggle="tab" href="#tab-resource" form='#f_tab-resource'>Resource</a></li>
            <li><a data-toggle="tab" href="#tab-ot" form='#f_tab-ot'>Operation Theater</a></li>
          </ul>

 
<!-------------------------------- Search + table ---------------------->
  <div class='row'>
    <form id="searchForm" class="formclass" style='width:99%'>
      <fieldset>
        <div class="ScolClass">
            <div name='Scol'>Search By : </div>
        </div>
        <div class="StextClass">
          <input name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase">
        </div>
       </fieldset> 
    </form>
      <div class='col-md-12' style="padding:0 0 15px 0">
          
        </div>
    </div>
  <!-------------------------------- End Search + table ------------------>
    
              <div class="tab-content">
                <div id="tab-doctor" class="tab-pane active form-horizontal">
                  <form id='f_tab-doctor'>
                   <table id="jqGrid" class="table table-striped"></table>
                     <div id="jqGridPager"></div>
                  </br>
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
                        </form>
                </div>

                <div id="tab-resource" class="tab-pane">
                  <form id='f_tab-resource'>
                  </br>
                  <table id="jqGrid2" class="table table-striped"></table>
                   <div id="jqGridPager2"></div>
                    <div class="form-group">
              <label class="col-md-2 control-label" for="resourcecode">Resource Code</label>    
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
                  </form>
                </div>

                <div id="tab-ot" class="tab-pane form-horizontal">
                  <form id='f_tab-ot'>
                      <table id="g_ot"></table>
                    <div id="pg_ot"></div>
                  </br>
                 <div class="form-group">
              <label class="col-md-2 control-label" for="resourcecode">Operation Theater Code</label>    
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
              </form>
            </div>
            </div>
        </div>
      </div>
    </div>
</div>
  <?php 
    include_once('../../../../footer.php'); 
  ?>

<script src="testdoc.js"></script>
  <script src="../../../../assets/js/utility.js"></script>
  <script src="../../../../assets/js/dialogHandler.js"></script>

<script>
    
</script>
</body>
</html>