<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment">
        MANAGEMENT
        <div class="ui small blue icon buttons" id="btn_grp_edit_otmgmt_div" style="position: absolute;
                    padding: 0 0 0 0;
                    right: 40px;
                    top: 9px;
                    z-index: 2;">
            <button class="ui button" id="new_otmgmt_div"><span class="fa fa-plus-square-o"></span> New</button>
            <button class="ui button" id="edit_otmgmt_div"><span class="fa fa-edit fa-lg"></span> Edit</button>
            <button class="ui button" id="save_otmgmt_div"><span class="fa fa-save fa-lg"></span> Save</button>
            <button class="ui button" id="cancel_otmgmt_div"><span class="fa fa-ban fa-lg"></span> Cancel</button>
        </div>
    </div>
    
    <div class="ui segment" style="padding: 10px 10px 30px 30px;">
        <form id="form_otmgmt_div" class="ui form">
            <div class="ui grid">
                <input id="mrn_otmgmt_div" name="mrn_otmgmt_div" type="hidden">
                <input id="episno_otmgmt_div" name="episno_otmgmt_div" type="hidden">

                <div class="eight wide column">
                    <div class="ui segments">
                        <div class="ui secondary segment">PATIENT PARTICULARS</div>
                        <div class="ui segment" style="height: 160px">

                            <div class="ui grid">
                                <div class="field five wide column">
                                    <label>Adm Date</label>
                                    <input id="admdate" name="admdate" type="date">
                                </div>

                                <div class="field five wide column">
                                    <label>Adm Time</label>
                                    <input id="admtime" name="admtime" type="time">
                                </div>

                                <div class="field six wide column">
                                    <label>Ward</label>
                                    <input id="ward" name="ward" type="text">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="eight wide column">
                    <div class="ui segments">
                        <div class="ui secondary segment">OPERATION</div>
                        <div class="ui segment" style="height: 160px">

                            <div class="ui grid">
                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                    <label>Oper Date</label>
                                    <input id="operdate" name="operdate" type="date">
                                </div>

                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 14px 14px;">
                                    <label>Time Started</label>
                                    <input id="timestarted" name="timestarted" type="time">
                                </div>
                            </div>

                            <div class="ui grid">
                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                    <label>Time Ended</label>
                                    <input id="timeended" name="timeended" type="time">
                                </div>

                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 14px 14px;">
                                    <label>Hours Utilized</label>
                                    <input id="hoursutilized" name="hoursutilized" type="text">
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                
                <div class="sixteen wide column">
                    <div class="ui segments">
                        <div class="ui secondary segment">OPERATION INFO</div>
                        <div class="ui segment">
                            <div class="ui grid">
                            
                                <div class="ten wide column">
                                    <div class="ui segments">
                                        <div class="ui segment" style="height: 480px">
                                        
                                            <!-- <div class="ui grid">
                                                <div class="row" style="padding-left: 30px;">
                                                    <div class="field">
                                                        <label>Serial No.</label>
                                                        <input id="serialno" name="serialno" type="text">
                                                    </div>
                                                </div>
                                            </div> -->

                                            <div class="ui grid">
                                                <div class="field five wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                    <label>Serial No.</label>
                                                    <input id="serialno" name="serialno" type="number">
                                                </div>

                                                <div class="field five wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                    <label>Status</label>
                                                    <input id="recstatus" name="recstatus" type="text">
                                                </div>
                                            </div>

                                            <div class="ui grid">
                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                    <label>Nature of Operation</label>
                                                    <textarea id="natureoper" name="natureoper" type="text" rows="4" ></textarea>
                                                </div>

                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 14px 14px;">
                                                    <label>Specimen</label>
                                                    <textarea id="specimen" name="specimen" type="text" rows="4" ></textarea>
                                                </div>
                                            </div>

                                            <div class="ui grid">
                                                <div class="field sixteen wide column" style="margin:0px; padding: 3px 14px 14px 14px;">
                                                    <label>Remarks</label>
                                                    <textarea id="remarks" name="remarks" type="text" rows="4" ></textarea>
                                                </div>
                                            </div>

                                            <div class="ui grid">
                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                    <label>Procedure</label>
                                                    <textarea id="procedure" name="procedure" type="text" rows="4" ></textarea>
                                                </div>

                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 14px 14px;">
                                                    <label>Diagnosis</label>
                                                    <textarea id="diagnosis" name="diagnosis" type="text" rows="4" ></textarea>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>

                                <div class="six wide column">
                                    <div class="ui segments">
                                        <div class="ui segment" style="height: 480px">
                                            <div class="ui grid">

                                                <div class="sixteen wide column">
                                                    <div class="ui segments">
                                                        <div class="ui segment">

                                                            <div class="ui grid">
                                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                                    <label>Elective/Emergency</label>
                                                                    <input id="electiveemgc" name="electiveemgc" type="text">
                                                                </div>

                                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 14px 14px;">
                                                                    <label>Classification</label>
                                                                    <input id="classification" name="classification" type="text">
                                                                </div>
                                                            </div>

                                                            <div class="ui grid">
                                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                                    <label>Type of Anaesthesia</label>
                                                                    <input id="anaesthtype" name="anaesthtype" type="text">
                                                                </div>

                                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 14px 14px;">
                                                                    <label>OT No</label>
                                                                    <input id="otno" name="otno" type="text">
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="sixteen wide column">
                                                    <div class="ui segments">
                                                        <div class="ui segment">

                                                            <div class="ui grid">
                                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                                    <label>First Scrub</label>
                                                                    <div class="ui action input">
                                                                        <input id="firstscrubnrs" name="firstscrubnrs" type="text">
                                                                        <a class="ui icon blue button"><i class="fa fa-ellipsis-h"></i></a>
                                                                    </div>
                                                                </div>

                                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 14px 14px;">
                                                                    <label>Second Scrub</label>
                                                                    <div class="ui action input">
                                                                        <input id="secondscrubnrs" name="secondscrubnrs" type="text">
                                                                        <a class="ui icon blue button"><i class="fa fa-ellipsis-h"></i></a>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="ui grid">
                                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                                    <label>G.A Asst</label>
                                                                    <div class="ui action input">
                                                                        <input id="gaassistant" name="gaassistant" type="text">
                                                                        <a class="ui icon blue button"><i class="fa fa-ellipsis-h"></i></a>
                                                                    </div>
                                                                </div>

                                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 14px 14px;">
                                                                    <label>Circulator</label>
                                                                    <div class="ui action input">
                                                                        <input id="circulator" name="circulator" type="text">
                                                                        <a class="ui icon blue button"><i class="fa fa-ellipsis-h"></i></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>