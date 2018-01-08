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
            <li class="active"><a data-toggle="tab" href="#tab-doctor" form='#f_tab-doctor' grid='#jqGrid'>Doctor</a></li>
            <li><a data-toggle="tab" href="#tab-resource" form='#f_tab-resource' grid='#g_resource'>Resource</a></li>
            <li><a data-toggle="tab" href="#tab-ot" form='#f_tab-ot' grid='#g_ot'>Operation Theater</a></li>
          </ul>
    
              <div class="tab-content">
                  <div id='tab-doctor' class='tab-pane fade in active'>
                    <div id='jqGrid_c'>
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
                              <table id="jqGrid" class="table table-striped"></table>
                              <div id="jqGridPager"></div>
                          </div>
                      </div>
                      <table id="jqGrid" class="table table-striped"></table>
                      <div id="jqGridPager"></div>
                    </div>
                  </div>

                <div id="tab-resource" class="tab-pane fade">
                  <div id='g_resource_c'>
                    <div class='row'>
                    <form id="searchForm2" class="formclass" style='width:99%'>
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
                            <table id="g_resource" class="table table-striped"></table>
                            <div id="jqGridPager2"></div>
                        </div>
                    </div>
                    <table id="g_resource"></table>
                    <div id="jqGridPager2"></div>
                  </div>
                </div>

                <div id="tab-ot" class="tab-pane fade">
                  <div id='g_ot_c'>
                      <div class='row'>
                    <form id="searchForm3" class="formclass" style='width:99%'>
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
                            <table id="g_ot" class="table table-striped"></table>
                            <div id="jqGridPager3"></div>
                        </div>
                    </div>
                    <table id="g_ot" class="table table-striped"></table>
                    <div id="jqGridPager3"></div>
                  </div>
              </div>
      </div>
    </div>
  </div>

  <div id="dialogForm" title="Add Form" >
      <form class='form-horizontal' style='width:99%' id='formdata'>
        <div class="form-group">
          <label class="col-md-2 control-label" for="resourcecode">Code</label>    
          <div class="col-md-4">
                      <div class='input-group'>
            <input id="resourcecode" name="resourcecode" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit>
            </div>
          </div>
     

          <label class="col-md-2 control-label" for="type">Type</label>  
          <div class="col-md-4">
            <div class='input-group'>
            <input id="type" name="type" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit>
            <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
            </div>
            <span class="help-block"></span>
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
                     <div class="col-md-4">
                     <input id="comment" name="comment" type="text" class="form-control input-sm" data-validation="required">
                     </div>
                     <label class="col-md-2 control-label" for="description">Description</label>  
                      <div class="col-md-4">
                      <input id="description" name="description" type="text" class="form-control input-sm" data-validation="required">
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








  <?php 
    include_once('../../../../footer.php'); 
  ?>

<script src="test2.js"></script>
  <script src="../../../../assets/js/utility.js"></script>
  <script src="../../../../assets/js/dialogHandler.js"></script>

<script>
    
</script>
</body>
</html>