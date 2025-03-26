<div class='col-md-12' style="padding-left: 0px; padding-right: 0px;">
    <div class="panel panel-info">
        <div class="panel-heading text-center" style="height: 40px;">
            <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                id="btn_grp_edit_thrombo"
                style="position: absolute;
                        padding: 0 0 0 0;
                        right: 40px;
                        top: 5px;">
                <button type="button" class="btn btn-default" id="new_thrombo">
                    <span class="fa fa-plus-square-o"></span> New 
                </button>
                <button type="button" class="btn btn-default" id="edit_thrombo">
                    <span class="fa fa-edit fa-lg"></span> Edit 
                </button>
                <button type="button" class="btn btn-default" data-oper='add' id="save_thrombo">
                    <span class="fa fa-save fa-lg"></span> Save 
                </button>
                <button type="button" class="btn btn-default" id="cancel_thrombo">
                    <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel 
                </button>
                <!-- <button type="button" class="btn btn-default" id="thrombo_chart">
                    <span class="fa fa-print fa-lg"></span> Chart 
                </button> -->
            </div>
        </div>
        
        <!-- <button class="btn btn-default btn-sm" type="button" id="thrombo_chart" style="float: right; margin: 10px 40px 10px 0px;">Chart</button> -->
        
        <div class="panel-body" style="padding-right: 0px;">                
            <form class='form-horizontal' style='width: 99%;' id='formThrombo'>
            <input id="idno_thrombo" name="idno_thrombo" type="hidden">
                
                <div class="col-md-2" style="padding: 0 0 0 0;">
                    <div class="panel panel-info">
                        <div class="panel-body">
                            <table id="datetimethrombo_tbl" class="ui celled table" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th class="scope">idno</th>
                                        <th class="scope">mrn</th>
                                        <th class="scope">episno</th>
                                        <th class="scope">Date</th>
                                        <th class="scope">Time</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>

                <div class='col-md-10' style="padding-right: 0px;">
                    <div class="panel panel-info">
                        <div class="panel-heading text-center">THROMBOPHLEBITIS FORM</div>
                        <div class="panel-body" style="padding: 15px 0px;">
    
                            <div class="form-inline col-md-12" style="padding-bottom: 15px;">

                                <label class="control-label" for="practiceDate" style="padding-right: 5px;">Date</label>
                                <input id="practiceDate" name="practiceDate" type="date" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter information." value="<?php echo date("Y-m-d"); ?>">

                                <!-- <button class="btn btn-default btn-sm" type="button" id="doctornote_bpgraph" style="float: right; margin-right: 20px;">Chart</button> -->
                            </div>
                            
                            <div class='col-md-12'>
                                <div class="panel panel-info">
                                    <div class="panel-heading text-center">CATHERER INSERTION</div>
                                    <div class="panel-body">
                                    </div>
                                </div>
                            </div>
                        
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>