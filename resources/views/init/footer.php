<!--------------------------------Dialog Handler ------------------>
<div id="dialog" title="title">
  <div class="panel panel-default">
    <div class="panel-heading">
      <form id="checkForm" class="form-inline">
        <div class="form-group">
          <b>Search: </b><div id="Dcol" name='Dcol'></div>
        </div>
        <div class="form-group" style="width:70%">
          <input id="Dtext" name='Dtext' type="search" style="width:100%" placeholder="Search here ..." class="form-control text-uppercase" autocomplete="off">
        </div>
      </form>
    </div>
    <div class="panel-body">
      <div id="gridDialog_c" class='col-xs-12' align="center">
        <table id="gridDialog" class="table table-striped"></table>
        <div id="gridDialogPager"></div>
      </div>
    </div>
  </div>
</div>
<!--------------------------------End dialog handler------------------>