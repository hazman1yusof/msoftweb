<div class="row">
	<div class="panel-body form-horizontal" style="padding: 5px 5px;">
        <input type="hidden" id="mrn_epno_docinfo" name="mrn_epno_docinfo">
        <input type="hidden" id="episno_epno_docinfo" name="episno_epno_docinfo">

        <form class="row myrow" style="margin-bottom:20px" id="form_epno_vitstate" autocomplete="off">
          <div class="row mytitle">
            <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." id="btn_grp_edit_nok_pat">
                <button type="button" class="btn btn-default" id="add_epno_vitstate">
                    <span class="fa fa-plus-square-o fa-lg"></span> Add
                </button>
                <button type="button" class="btn btn-default" id="edit_epno_vitstate">
                    <span class="fa fa-edit fa-lg"></span> Edit
                </button>
                <button type="button" class="btn btn-default" id="save_epno_vitstate">
                    <span class="fa fa-save fa-lg"></span> Save
                </button>
                <button type="button" class="btn btn-default" id="cancel_epno_vitstate" >
                    <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
                </button>
            </div>
          </div>
          <div class="row">
              <div class="col-md-12" style="padding:0px">
                  <div class="col-md-12" style="padding:2px 5px">
                    <label>History of Presenting Complaint</label>
                    <textarea id="clinicnote" name="clinicnote" type="text" class="form-control input-sm" style="height:38px;min-height:40px;overflow-y:hidden;" readonly=""></textarea>
                  </div>
                  <div class="col-md-3" style="padding:1px 5px">
                    <label>Height</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="height">  
                        <span class="input-group-addon" >CM</span>
                    </div>
                  </div>
                  <div class="col-md-2" style="padding:1px 5px">
                    <label style="display: block;">BP</label>
                    <input type="text" class="form-control" name="bp_sys1" style="width: 49%;display: inline-block;">  
                    <input type="text" class="form-control" name="bp_dias2" style="width: 49%;display: inline-block;">  
                  </div>
                  <div class="col-md-3" style="padding:1px 5px">
                    <label>Temperature</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="temperature">  
                        <span class="input-group-addon" >&#8451;</span>
                    </div>
                  </div>
                  <div class="col-md-2" style="padding:1px 5px">
                    <label>BMI</label>
                    <input type="text" class="form-control" name="bmi">  
                  </div>
                  <div class="col-md-2" style="padding:1px 5px">
                    <label>Vision (L)</label>
                    <input type="text" class="form-control" name="visionl">  
                  </div>


                  <div class="col-md-3" style="padding:1px 5px">
                    <label>Weight</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="weight">  
                        <span class="input-group-addon" >KG</span>
                    </div> 
                  </div>
                  <div class="col-md-2" style="padding:1px 5px">
                    <label>Pulse Rate</label>
                    <input type="text" class="form-control" name="pulse">  
                  </div>
                  <div class="col-md-3" style="padding:1px 5px">
                    <label>Respiration</label>
                    <input type="text" class="form-control" name="respiration">  
                  </div>
                  <div class="col-md-2" style="padding:1px 5px">
                    <label>Color Blind</label>
                    <select name="colorblind" class="form-control">
                      <option value="Normal">Normal</option>
                      <option value="Abnormal">Abnormal</option>
                    </select>
                  </div>
                  <div class="col-md-2" style="padding:1px 5px">
                    <label>Vision (R)</label>
                    <input type="text" class="form-control" name="visionr">  
                  </div>
              </div>
          </div>
          <div class="addt_data">
              <div>
                <label>Add Date</label>
                <input type="text" name="adddate">  
              </div>
              <div>
                <label>Add User</label>
                <input type="text" name="adduser">  
              </div>
              <div>
                <label>Computer ID</label>
                <input type="text" name="computerid">  
              </div>
          </div>
        </form>

        <div class="row">
          <div class="col-md-3" style="padding:0px">
            <div class="panel panel-default">
              <div class="panel-heading" style="font-weight: bold;"><span id="admdoctor_text"></span></div>
              <div class="panel-body" style="padding: 0px 15px 5px 15px;">
                <table id="addnotes_epno" style="width:100%">
                    <thead>
                        <tr>
                            <td>Date</td>
                            <td>Time</td>
                            <td>Add User</td>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
              </div>
            </div>
          </div>

          <div class="col-md-9" style="padding:0px 5px 0px 10px">
            <div class="panel panel-default">
              <div class="panel-heading" style="font-weight: bold;">
                Additional Notes
                  <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." id="btn_grp_epno_addnotes" style="margin-top: -7px;">
                    <button type="button" class="btn btn-default" id="add_epno_addnotes">
                        <span class="fa fa-plus-square-o fa-lg"></span> Add
                    </button>
                    <button type="button" class="btn btn-default" id="save_epno_addnotes">
                        <span class="fa fa-save fa-lg"></span> Save
                    </button>
                    <button type="button" class="btn btn-default" id="cancel_epno_addnotes" >
                        <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
                    </button>
                </div>
            </div>
              <form id="form_epno_addnotes" autocomplete="off">
              <div class="panel-body" style="padding-bottom: 0px;">
                <textarea rows="3"class="form-control" id="additionalnote_epno_addnotes" name="additionalnote" required></textarea>
                <div class="addt_data">
                  <div>
                    <label>Add Date</label>
                    <input type="text" name="adddate">  
                  </div>
                  <div>
                    <label>Add User</label>
                    <input type="text" name="adduser">  
                  </div>
                  <div>
                    <label>Computer ID</label>
                    <input type="text" name="computerid">  
                  </div>
                </div>
              </div>
              </form>
            </div>
          </div>
        </div>

        <form class="row myrow" id="form_epno_diagnose" autocomplete="off">
          <div class="row mytitle">
            <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." id="btn_grp_edit_nok_pat">
                <button type="button" class="btn btn-default" id="add_epno_diagnose">
                    <span class="fa fa-plus-square-o fa-lg"></span> Add
                </button>
                <button type="button" class="btn btn-default" id="edit_epno_diagnose">
                    <span class="fa fa-edit fa-lg"></span> Edit
                </button>
                <button type="button" class="btn btn-default" id="save_epno_diagnose">
                    <span class="fa fa-save fa-lg"></span> Save
                </button>
                <button type="button" class="btn btn-default" id="cancel_epno_diagnose" >
                    <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
                </button>
            </div>
          </div>
          <div class="row">
              <div class="col-md-12" style="padding:2px">
                <label>Provisional Diagnosis</label>
                <input type="text" class="form-control" name="diagprov">  
              </div>
              <div class="col-md-12" style="padding:2px">
                <label>Final Diagnosis</label>
                <input type="text" class="form-control" name="diagfinal">  
              </div>
              <div class="col-md-12" style="padding:2px">
                <label>Procedure</label>
                <textarea rows="3" class="form-control" name="procedure"></textarea>
              </div>
          </div>
            <div class="addt_data">
              <div>
                <label>Add Date</label>
                <input type="text" name="adddate">  
              </div>
              <div>
                <label>Add User</label>
                <input type="text" name="adduser">  
              </div>
              <div>
                <label>Computer ID</label>
                <input type="text" name="computerid">  
              </div>
            </div>
        </form>
    </div>
</div>