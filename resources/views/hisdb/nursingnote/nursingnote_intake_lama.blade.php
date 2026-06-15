<div class='col-md-12' style="padding-left: 0px; padding-right: 0px;">
    <div class="panel panel-info">
        <div class="panel-heading text-center" style="position: sticky; top: 0px; z-index: 3; height: 40px;">
            <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                id="btn_grp_edit_intake"
                style="position: absolute;
                        padding: 0 0 0 0;
                        right: 40px;
                        top: 5px;">
                <button type="button" class="btn btn-default" id="new_intake">
                    <span class="fa fa-plus-square-o"></span> New 
                </button>
                <button type="button" class="btn btn-default" id="edit_intake">
                    <span class="fa fa-edit fa-lg"></span> Edit 
                </button>
                <button type="button" class="btn btn-default" data-oper='add' id="save_intake">
                    <span class="fa fa-save fa-lg"></span> Save 
                </button>
                <button type="button" class="btn btn-default" id="cancel_intake">
                    <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel 
                </button>
            </div>
        </div>
        
        <div class="panel-body" style="padding: 15px 0 15px 3px;">
            <form class='form-horizontal' style='width: 99%;' id='formIntake'>
                <input id="idno_intake" name="idno_intake" type="hidden">
                
                <div class="col-md-2" style="padding: 0 0 0 0;">
                    <div class="panel panel-info">
                        <div class="panel-body">
                            <table id="tbl_intake_date" class="ui celled table" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th class="scope">idno</th>
                                        <th class="scope">mrn</th>
                                        <th class="scope">episno</th>
                                        <th class="scope">Date</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class='col-md-10' style="padding-right: 0px;">
                    <div class="col-md-5" style="padding-top: 20px; text-align: left; color: red;">
                        <p id="p_error_intake"></p>
                    </div>
                    <div class="form-inline col-md-12" style="padding-bottom: 15px;">
                        <label class="control-label" for="recorddate" style="padding-right: 5px;">Date</label>
                        <input id="recorddate_intake" name="recorddate" type="date" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter information." value="<?php echo date("Y-m-d"); ?>">
                        
                        <button class="btn btn-default btn-sm" type="button" id="doctornote_iograph" style="float: right; margin-right: 20px;">Preview</button>
                    </div>
                    <ul class="nav nav-tabs" id="jqGridNursNote_intake_tabs">
                        <li class="active"><a data-toggle="tab" id="navtab_first" href="#tab-first" aria-expanded="true" data-shift='first'>First Shift</a></li>
                        <li><a data-toggle="tab" id="navtab_second" href="#tab-second" data-shift='second'>Second Shift</a></li>
                        <li><a data-toggle="tab" id="navtab_third" href="#tab-third" data-shift='third'>Third Shift</a></li>
                    </ul>
                    <div class="tab-content" style="padding: 10px 5px;">
                        <div id="tab-first" class="active in tab-pane fade">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">Oral (IN)</th>
                                        <th scope="col">Intra-Vena (IN)</th>
                                        <th scope="col">Others (IN)</th>
                                        <th scope="col">Urine (OUT)</th>
                                        <th scope="col">Vomit (OUT)</th>
                                        <th scope="col">Aspirate (OUT)</th>
                                        <th scope="col">Others (OUT)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>07:00</td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="oraltype1" name="oraltype1" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="oralamt1" name="oralamt1" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="intratype1" name="intratype1" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="intraamt1" name="intraamt1" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="othertype1" name="othertype1" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="otheramt1" name="otheramt1" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="urineamt1" name="urineamt1" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="vomitamt1" name="vomitamt1" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="aspamt1" name="aspamt1" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="otherout1" name="otherout1" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>08:00</td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="oraltype2" name="oraltype2" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="oralamt2" name="oralamt2" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="intratype2" name="intratype2" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="intraamt2" name="intraamt2" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="othertype2" name="othertype2" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="otheramt2" name="otheramt2" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="urineamt2" name="urineamt2" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="vomitamt2" name="vomitamt2" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="aspamt2" name="aspamt2" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="otherout2" name="otherout2" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>09:00</td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="oraltype3" name="oraltype3" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="oralamt3" name="oralamt3" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="intratype3" name="intratype3" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="intraamt3" name="intraamt3" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="othertype3" name="othertype3" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="otheramt3" name="otheramt3" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="urineamt3" name="urineamt3" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="vomitamt3" name="vomitamt3" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="aspamt3" name="aspamt3" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="otherout3" name="otherout3" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>10:00</td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="oraltype4" name="oraltype4" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="oralamt4" name="oralamt4" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="intratype4" name="intratype4" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="intraamt4" name="intraamt4" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="othertype4" name="othertype4" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="otheramt4" name="otheramt4" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="urineamt4" name="urineamt4" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="vomitamt4" name="vomitamt4" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="aspamt4" name="aspamt4" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="otherout4" name="otherout4" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>11:00</td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="oraltype5" name="oraltype5" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="oralamt5" name="oralamt5" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="intratype5" name="intratype5" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="intraamt5" name="intraamt5" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="othertype5" name="othertype5" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="otheramt5" name="otheramt5" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="urineamt5" name="urineamt5" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="vomitamt5" name="vomitamt5" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="aspamt5" name="aspamt5" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="otherout5" name="otherout5" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>12:00</td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="oraltype6" name="oraltype6" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="oralamt6" name="oralamt6" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="intratype6" name="intratype6" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="intraamt6" name="intraamt6" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="othertype6" name="othertype6" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="otheramt6" name="otheramt6" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="urineamt6" name="urineamt6" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="vomitamt6" name="vomitamt6" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="aspamt6" name="aspamt6" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="otherout6" name="otherout6" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>13:00</td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="oraltype7" name="oraltype7" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="oralamt7" name="oralamt7" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="intratype7" name="intratype7" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="intraamt7" name="intraamt7" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="othertype7" name="othertype7" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="otheramt7" name="otheramt7" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="urineamt7" name="urineamt7" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="vomitamt7" name="vomitamt7" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="aspamt7" name="aspamt7" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="otherout7" name="otherout7" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>14:00</td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="oraltype8" name="oraltype8" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="oralamt8" name="oralamt8" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="intratype8" name="intratype8" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="intraamt8" name="intraamt8" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="othertype8" name="othertype8" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="otheramt8" name="otheramt8" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="urineamt8" name="urineamt8" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="vomitamt8" name="vomitamt8" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="aspamt8" name="aspamt8" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="otherout8" name="otherout8" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div id="tab-second" class="tab-pane fade">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">Oral (IN)</th>
                                        <th scope="col">Intra-Vena (IN)</th>
                                        <th scope="col">Others (IN)</th>
                                        <th scope="col">Urine (OUT)</th>
                                        <th scope="col">Vomit (OUT)</th>
                                        <th scope="col">Aspirate (OUT)</th>
                                        <th scope="col">Others (OUT)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>15:00</td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="oraltype9" name="oraltype9" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="oralamt9" name="oralamt9" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="intratype9" name="intratype9" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="intraamt9" name="intraamt9" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="othertype9" name="othertype9" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="otheramt9" name="otheramt9" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="urineamt9" name="urineamt9" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="vomitamt9" name="vomitamt9" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="aspamt9" name="aspamt9" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="otherout9" name="otherout9" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>16:00</td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="oraltype10" name="oraltype10" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="oralamt10" name="oralamt10" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="intratype10" name="intratype10" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="intraamt10" name="intraamt10" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="othertype10" name="othertype10" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="otheramt10" name="otheramt10" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="urineamt10" name="urineamt10" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="vomitamt10" name="vomitamt10" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="aspamt10" name="aspamt10" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="otherout10" name="otherout10" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>17:00</td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="oraltype11" name="oraltype11" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="oralamt11" name="oralamt11" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="intratype11" name="intratype11" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="intraamt11" name="intraamt11" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="othertype11" name="othertype11" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="otheramt11" name="otheramt11" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="urineamt11" name="urineamt11" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="vomitamt11" name="vomitamt11" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="aspamt11" name="aspamt11" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="otherout11" name="otherout11" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>18:00</td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="oraltype12" name="oraltype12" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="oralamt12" name="oralamt12" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="intratype12" name="intratype12" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="intraamt12" name="intraamt12" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="othertype12" name="othertype12" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="otheramt12" name="otheramt12" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="urineamt12" name="urineamt12" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="vomitamt12" name="vomitamt12" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="aspamt12" name="aspamt12" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="otherout12" name="otherout12" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>19:00</td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="oraltype13" name="oraltype13" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="oralamt13" name="oralamt13" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="intratype13" name="intratype13" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="intraamt13" name="intraamt13" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="othertype13" name="othertype13" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="otheramt13" name="otheramt13" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="urineamt13" name="urineamt13" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="vomitamt13" name="vomitamt13" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="aspamt13" name="aspamt13" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="otherout13" name="otherout13" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>20:00</td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="oraltype14" name="oraltype14" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="oralamt14" name="oralamt14" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="intratype14" name="intratype14" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="intraamt14" name="intraamt14" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="othertype14" name="othertype14" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="otheramt14" name="otheramt14" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="urineamt14" name="urineamt14" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="vomitamt14" name="vomitamt14" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="aspamt14" name="aspamt14" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="otherout14" name="otherout14" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>21:00</td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="oraltype15" name="oraltype15" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="oralamt15" name="oralamt15" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="intratype15" name="intratype15" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="intraamt15" name="intraamt15" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="othertype15" name="othertype15" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="otheramt15" name="otheramt15" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="urineamt15" name="urineamt15" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="vomitamt15" name="vomitamt15" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="aspamt15" name="aspamt15" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="otherout15" name="otherout15" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>22:00</td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="oraltype16" name="oraltype16" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="oralamt16" name="oralamt16" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="intratype16" name="intratype16" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="intraamt16" name="intraamt16" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="othertype16" name="othertype16" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="otheramt16" name="otheramt16" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="urineamt16" name="urineamt16" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="vomitamt16" name="vomitamt16" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="aspamt16" name="aspamt16" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="otherout16" name="otherout16" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div id="tab-third" class="tab-pane fade">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">Oral (IN)</th>
                                        <th scope="col">Intra-Vena (IN)</th>
                                        <th scope="col">Others (IN)</th>
                                        <th scope="col">Urine (OUT)</th>
                                        <th scope="col">Vomit (OUT)</th>
                                        <th scope="col">Aspirate (OUT)</th>
                                        <th scope="col">Others (OUT)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>23:00</td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="oraltype17" name="oraltype17" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="oralamt17" name="oralamt17" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="intratype17" name="intratype17" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="intraamt17" name="intraamt17" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="othertype17" name="othertype17" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="otheramt17" name="otheramt17" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="urineamt17" name="urineamt17" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="vomitamt17" name="vomitamt17" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="aspamt17" name="aspamt17" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="otherout17" name="otherout17" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>24:00</td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="oraltype18" name="oraltype18" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="oralamt18" name="oralamt18" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="intratype18" name="intratype18" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="intraamt18" name="intraamt18" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="othertype18" name="othertype18" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="otheramt18" name="otheramt18" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="urineamt18" name="urineamt18" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="vomitamt18" name="vomitamt18" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="aspamt18" name="aspamt18" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="otherout18" name="otherout18" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>01:00</td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="oraltype19" name="oraltype19" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="oralamt19" name="oralamt19" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="intratype19" name="intratype19" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="intraamt19" name="intraamt19" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="othertype19" name="othertype19" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="otheramt19" name="otheramt19" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="urineamt19" name="urineamt19" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="vomitamt19" name="vomitamt19" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="aspamt19" name="aspamt19" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="otherout19" name="otherout19" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>02:00</td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="oraltype20" name="oraltype20" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="oralamt20" name="oralamt20" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="intratype20" name="intratype20" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="intraamt20" name="intraamt20" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="othertype20" name="othertype20" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="otheramt20" name="otheramt20" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="urineamt20" name="urineamt20" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="vomitamt20" name="vomitamt20" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="aspamt20" name="aspamt20" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="otherout20" name="otherout20" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>03:00</td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="oraltype21" name="oraltype21" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="oralamt21" name="oralamt21" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="intratype21" name="intratype21" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="intraamt21" name="intraamt21" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="othertype21" name="othertype21" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="otheramt21" name="otheramt21" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="urineamt21" name="urineamt21" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="vomitamt21" name="vomitamt21" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="aspamt21" name="aspamt21" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="otherout21" name="otherout21" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>04:00</td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="oraltype22" name="oraltype22" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="oralamt22" name="oralamt22" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="intratype22" name="intratype22" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="intraamt22" name="intraamt22" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="othertype22" name="othertype22" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="otheramt22" name="otheramt22" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="urineamt22" name="urineamt22" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="vomitamt22" name="vomitamt22" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="aspamt22" name="aspamt22" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="otherout22" name="otherout22" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>05:00</td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="oraltype23" name="oraltype23" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="oralamt23" name="oralamt23" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="intratype23" name="intratype23" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="intraamt23" name="intraamt23" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="othertype23" name="othertype23" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="otheramt23" name="otheramt23" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="urineamt23" name="urineamt23" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="vomitamt23" name="vomitamt23" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="aspamt23" name="aspamt23" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="otherout23" name="otherout23" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>06:00</td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="oraltype24" name="oraltype24" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="oralamt24" name="oralamt24" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="intratype24" name="intratype24" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="intraamt24" name="intraamt24" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                <textarea id="othertype24" name="othertype24" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                <input id="otheramt24" name="otheramt24" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="urineamt24" name="urineamt24" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="vomitamt24" name="vomitamt24" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="aspamt24" name="aspamt24" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input id="otherout24" name="otherout24" type="text" class="form-control input-sm">
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>