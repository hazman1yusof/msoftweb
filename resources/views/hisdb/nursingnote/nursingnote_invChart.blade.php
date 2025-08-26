
<div class='col-md-12' style="padding-left: 0px; padding-right: 0px;">
    <div class="panel panel-info">
        <div class="panel-heading text-center" style="height: 40px;">
            <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                id="btn_grp_edit_invChart"
                style="position: absolute;
                        padding: 0 0 0 0;
                        right: 40px;
                        top: 5px;">
                <!-- <button type="button" class="btn btn-default" id="new_invChart">
                    <span class="fa fa-plus-square-o"></span> New 
                </button>
                <button type="button" class="btn btn-default" id="edit_invChart">
                    <span class="fa fa-edit fa-lg"></span> Edit 
                </button>
                <button type="button" class="btn btn-default" data-oper='add' id="save_invChart">
                    <span class="fa fa-save fa-lg"></span> Save 
                </button>
                <button type="button" class="btn btn-default" id="cancel_invChart">
                    <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel 
                </button> -->
                <button type="button" class="btn btn-default" id="invChart_chart">
                    <span class="fa fa-print fa-lg"></span> Chart 
                </button>
            </div>
        </div>
        
        <!-- <button class="btn btn-default btn-sm" type="button" id="invChart_chart" style="float: right; margin: 10px 40px 10px 0px;">Chart</button> -->
        
        <div class="panel-body" style="padding-right: 0px;">
            <form class='form-horizontal' style='width: 99%;' id='formInvHeader'>
                <div class='col-md-12'>
                    <div class="panel panel-info">
                        <div class="panel-body">
                            <div class="form-group">
                                <div class='col-md-6' style="padding-top: 20px;">
                                    <label class="col-md-5 control-label" for="reg_date">Date of Admission</label>
                                    <div class="col-md-5">
                                        <input id="reg_date" name="reg_date" type="date" class="form-control input-sm" readonly>
                                    </div>
                                </div>
                                
                                <div class='col-md-6'>
                                    <div class="ui action input">
                                        <button type="button" id='invChart_click' class='ui icon button'><i class="paperclip icon"></i>Upload File</button>
                                        <input type="file" name="file" id="invChrt_file" accept="audio/*,image/*,video/*,application/pdf" style="display: none;">
                                    </div>
                                    <div class="ui segment" id="invChart_allAttach" style="display: none;"></div>
                                    
                                    <table class="ui celled table" id="invChart_file" style="width: 150%">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>compcode</th>
                                                <th>mrn</th>
                                                <th>episno</th>
                                                <th style="width: 80%;">File</th>
                                                <th style="width: 20%;">Open</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class='col-md-12'>
                    <div class="panel panel-info">
                        <div class="panel-body">
                            <form class='form-horizontal' style='width: 99%;' id='formInvChart'>
                                
                                <ul class="nav nav-tabs" id="jqGridNursNote_inv_tabs">
                                    <li class="active"><a data-toggle="tab" id="navtab_FBC" href="#tab-FBC" aria-expanded="true" data-type='FBC'>FBC</a></li>
                                    <li><a data-toggle="tab" id="navtab_Coag" href="#tab-Coag" data-type='Coag'>Coag</a></li>
                                    <li><a data-toggle="tab" id="navtab_RP" href="#tab-RP" data-type='RP'>RP</a></li>
                                    <li><a data-toggle="tab" id="navtab_LFT" href="#tab-LFT" data-type='LFT'>LFT</a></li>
                                    <li><a data-toggle="tab" id="navtab_Elect" href="#tab-Elect" data-type='Elect'>Elect</a></li>
                                    <li><a data-toggle="tab" id="navtab_ABGVBG" href="#tab-ABGVBG" data-type='ABGVBG'>ABG/VBG</a></li>
                                    <li><a data-toggle="tab" id="navtab_UFEME" href="#tab-UFEME" data-type='UFEME'>UFEME</a></li>
                                    <li><a data-toggle="tab" id="navtab_CE" href="#tab-CE" data-type='CE'>CE</a></li>
                                    <li><a data-toggle="tab" id="navtab_CS" href="#tab-CS" data-type='CS'>C&S</a></li>
                                </ul>
                                <div class="tab-content" style="padding: 10px 5px;">
                                    <div id="tab-FBC" class="active in tab-pane fade">
                                        <div class="col-md-4" style="padding: 0 0 0 0;">
                                            <div class="panel panel-info">
                                                <div class="panel-body">
                                                    <table id="tbl_invcat_FBC" class="ui celled table" style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th class="scope">idno</th>
                                                                <th class="scope">inv_code</th>
                                                                <th class="scope"> </th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class='col-md-8'>
                                            <div class="panel panel-info" id="jqGridInvChartFBC_c">
                                                <div class="panel-body">
                                                    <div class='col-md-12' style="padding: 0 0 15px 0;">
                                                        <input id="inv_codeFBC" name="inv_codeFBC" type="hidden">
                                                        <input id="inv_catFBC" name="inv_catFBC" type="hidden">
                                                        <table id="jqGridInvChart_FBC" class="table table-striped"></table>
                                                        <div id="jqGridPagerInvChart_FBC"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="tab-Coag" class="tab-pane fade">
                                        <div class="col-md-4" style="padding: 0 0 0 0;">
                                            <div class="panel panel-info">
                                                <div class="panel-body">
                                                    <table id="tbl_invcat_Coag" class="ui celled table" style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th class="scope">idno</th>
                                                                <th class="scope">inv_code</th>
                                                                <th class="scope"> </th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class='col-md-8'>
                                            <div class="panel panel-info" id="jqGridInvChartCoag_c">
                                                <div class="panel-body">
                                                    <div class='col-md-12' style="padding: 0 0 15px 0;">
                                                        <input id="inv_codeCoag" name="inv_codeCoag" type="hidden">
                                                        <input id="inv_catCoag" name="inv_catCoag" type="hidden">
                                                        <table id="jqGridInvChart_Coag" class="table table-striped"></table>
                                                        <div id="jqGridPagerInvChart_Coag"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="tab-RP" class="tab-pane fade">
                                        <div class="col-md-4" style="padding: 0 0 0 0;">
                                            <div class="panel panel-info">
                                                <div class="panel-body">
                                                    <table id="tbl_invcat_RP" class="ui celled table" style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th class="scope">idno</th>
                                                                <th class="scope">inv_code</th>
                                                                <th class="scope"> </th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class='col-md-8'>
                                            <div class="panel panel-info" id="jqGridInvChartRP_c">
                                                <div class="panel-body">
                                                    <div class='col-md-12' style="padding: 0 0 15px 0;">
                                                        <input id="inv_codeRP" name="inv_codeRP" type="hidden">
                                                        <input id="inv_catRP" name="inv_catRP" type="hidden">
                                                        <table id="jqGridInvChart_RP" class="table table-striped"></table>
                                                        <div id="jqGridPagerInvChart_RP"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="tab-LFT" class="tab-pane fade">
                                        <div class="col-md-4" style="padding: 0 0 0 0;">
                                            <div class="panel panel-info">
                                                <div class="panel-body">
                                                    <table id="tbl_invcat_LFT" class="ui celled table" style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th class="scope">idno</th>
                                                                <th class="scope">inv_code</th>
                                                                <th class="scope"> </th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class='col-md-8'>
                                            <div class="panel panel-info" id="jqGridInvChartLFT_c">
                                                <div class="panel-body">
                                                    <div class='col-md-12' style="padding: 0 0 15px 0;">
                                                        <input id="inv_codeLFT" name="inv_codeLFT" type="hidden">
                                                        <input id="inv_catLFT" name="inv_catLFT" type="hidden">
                                                        <table id="jqGridInvChart_LFT" class="table table-striped"></table>
                                                        <div id="jqGridPagerInvChart_LFT"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="tab-Elect" class="tab-pane fade">
                                        <div class="col-md-4" style="padding: 0 0 0 0;">
                                            <div class="panel panel-info">
                                                <div class="panel-body">
                                                    <table id="tbl_invcat_Elect" class="ui celled table" style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th class="scope">idno</th>
                                                                <th class="scope">inv_code</th>
                                                                <th class="scope"> </th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class='col-md-8'>
                                            <div class="panel panel-info" id="jqGridInvChartElect_c">
                                                <div class="panel-body">
                                                    <div class='col-md-12' style="padding: 0 0 15px 0;">
                                                        <input id="inv_codeElect" name="inv_codeElect" type="hidden">
                                                        <input id="inv_catElect" name="inv_catElect" type="hidden">
                                                        <table id="jqGridInvChart_Elect" class="table table-striped"></table>
                                                        <div id="jqGridPagerInvChart_Elect"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="tab-ABGVBG" class="tab-pane fade">
                                        <div class="col-md-4" style="padding: 0 0 0 0;">
                                            <div class="panel panel-info">
                                                <div class="panel-body">
                                                    <table id="tbl_invcat_ABGVBG" class="ui celled table" style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th class="scope">idno</th>
                                                                <th class="scope">inv_code</th>
                                                                <th class="scope"> </th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class='col-md-8'>
                                            <div class="panel panel-info" id="jqGridInvChartABGVBG_c">
                                                <div class="panel-body">
                                                    <div class='col-md-12' style="padding: 0 0 15px 0;">
                                                        <input id="inv_codeABGVBG" name="inv_codeABGVBG" type="hidden">
                                                        <input id="inv_catABGVBG" name="inv_catABGVBG" type="hidden">
                                                        <table id="jqGridInvChart_ABGVBG" class="table table-striped"></table>
                                                        <div id="jqGridPagerInvChart_ABGVBG"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="tab-UFEME" class="tab-pane fade">
                                        <div class="col-md-4" style="padding: 0 0 0 0;">
                                            <div class="panel panel-info">
                                                <div class="panel-body">
                                                    <table id="tbl_invcat_UFEME" class="ui celled table" style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th class="scope">idno</th>
                                                                <th class="scope">inv_code</th>
                                                                <th class="scope"> </th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class='col-md-8'>
                                            <div class="panel panel-info" id="jqGridInvChartUFEME_c">
                                                <div class="panel-body">
                                                    <div class='col-md-12' style="padding: 0 0 15px 0;">
                                                        <input id="inv_codeUFEME" name="inv_codeUFEME" type="hidden">
                                                        <input id="inv_catUFEME" name="inv_catUFEME" type="hidden">
                                                        <table id="jqGridInvChart_UFEME" class="table table-striped"></table>
                                                        <div id="jqGridPagerInvChart_UFEME"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="tab-CE" class="tab-pane fade">
                                        <div class="col-md-4" style="padding: 0 0 0 0;">
                                            <div class="panel panel-info">
                                                <div class="panel-body">
                                                    <table id="tbl_invcat_CE" class="ui celled table" style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th class="scope">idno</th>
                                                                <th class="scope">inv_code</th>
                                                                <th class="scope"> </th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class='col-md-8'>
                                            <div class="panel panel-info" id="jqGridInvChartCE_c">
                                                <div class="panel-body">
                                                    <div class='col-md-12' style="padding: 0 0 15px 0;">
                                                        <input id="inv_codeCE" name="inv_codeCE" type="hidden">
                                                        <input id="inv_catCE" name="inv_catCE" type="hidden">
                                                        <table id="jqGridInvChart_CE" class="table table-striped"></table>
                                                        <div id="jqGridPagerInvChart_CE"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="tab-CS" class="tab-pane fade">
                                        <div class="col-md-4" style="padding: 0 0 0 0;">
                                            <div class="panel panel-info">
                                                <div class="panel-body">
                                                    <table id="tbl_invcat_CS" class="ui celled table" style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th class="scope">idno</th>
                                                                <th class="scope">inv_code</th>
                                                                <th class="scope"> </th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class='col-md-8'>
                                            <div class="panel panel-info" id="jqGridInvChartCS_c">
                                                <div class="panel-body">
                                                    <div class='col-md-12' style="padding: 0 0 15px 0;">
                                                        <input id="inv_codeCS" name="inv_codeCS" type="hidden">
                                                        <input id="inv_catCS" name="inv_catCS" type="hidden">
                                                        <table id="jqGridInvChart_CS" class="table table-striped"></table>
                                                        <div id="jqGridPagerInvChart_CS"></div>
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
            </form>
        </div>
    </div>
</div>