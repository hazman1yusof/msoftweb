<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment">
        OPERATION RECORD
        <div class="ui small blue icon buttons" id="btn_grp_edit_otmgmt_div" style="position: absolute;
                    padding: 0 0 0 0;
                    right: 40px;
                    top: 9px;
                    z-index: 2;">
            <button class="ui button" id="new_otmgmt_div"><span class="fa fa-plus-square-o"></span>New</button>
            <button class="ui button" id="edit_otmgmt_div"><span class="fa fa-edit fa-lg"></span>Edit</button>
            <button class="ui button" id="save_otmgmt_div"><span class="fa fa-save fa-lg"></span>Save</button>
            <button class="ui button" id="cancel_otmgmt_div"><span class="fa fa-ban fa-lg"></span>Cancel</button>
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
                                    <input id="admdate" name="admdate" type="date" rdonly onkeydown="return false"/>
                                </div>

                                <div class="field five wide column">
                                    <label>Adm Time</label>
                                    <input id="admtime" name="admtime" type="time" rdonly onkeydown="return false"/>
                                </div>

                                <div class="field six wide column">
                                    <label>Ward</label>
                                    <input id="ward" name="ward" type="text" rdonly>
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
                                    <input id="hoursutilized" name="hoursutilized" type="text" rdonly>
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
                                <div class="sixteen wide column">
                                    <div class="ui segments">
                                        <div class="ui segment">

                                            <div class="ui grid">
                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                    <label>Surgeon</label>
                                                    <input type="text" id="surgeon" name="surgeon">
                                                </div>

                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 14px 14px;">
                                                    <label>Anaest</label>
                                                    <input type="text" id="anaest" name="anaest">
                                                </div>
                                            </div>

                                            <div class="ui grid">
                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                    <label>Scrub Nurse</label>
                                                    <input type="text" id="scrubnurse" name="scrubnurse">
                                                </div>

                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 14px 14px;">
                                                    <label>Consultant</label>
                                                    <input type="text" id="consultant" name="consultant">
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>

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

                                            <div class="ui grid" style="display: none;">
                                                <div class="field five wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                    <label>Serial No.</label>
                                                    <input id="serialno" name="serialno" type="number">
                                                </div>

                                                <div class="field five wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                    <label>Status</label>
                                                    <select name="oper_status" id="oper_status" class="form-control input-sm">
                                                        <option value=""></option>
                                                        @foreach($otstatus as $obj)
                                                            <option value="{{$obj->code}}">{{$obj->description}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="ui grid">
                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                    <label>Nature of Operation</label>
                                                    <textarea id="natureoper" name="natureoper" rows="4"></textarea>
                                                </div>

                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 14px 14px;">
                                                    <label>Diagnosis</label>
                                                    <textarea id="diagnosis" name="diagnosis" rows="4"></textarea>
                                                </div>

                                            </div>

                                            <div class="ui grid">
                                                <div class="field sixteen wide column" style="margin:0px; padding: 3px 14px 14px 14px;">
                                                    <label>Findings</label>
                                                    <textarea id="finding" name="finding" rows="4"></textarea>
                                                </div>
                                            </div>

                                            <div class="ui grid">
                                                <div class="field eight wide column" style="display: none;">
                                                    <label>Procedure</label>
                                                    <textarea id="procedure" name="procedure" rows="4"></textarea>
                                                </div>

                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                    <label>Plan</label>
                                                    <textarea id="plan" name="plan" rows="4"></textarea>
                                                </div>

                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 14px 14px;">
                                                    <label>Specimen</label>
                                                    <textarea id="specimen" name="specimen" rows="4"></textarea>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>

                                <div class="six wide column">
                                    <div class="ui segments">
                                        <div class="ui segment" style="height: 480px">
                                            <div class="ui grid">

                                                <div class="sixteen wide column" style="display: none;">
                                                    <div class="ui segments">
                                                        <div class="ui segment">

                                                            <div class="ui grid">
                                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                                    <label>Elective/Emergency</label>
                                                                    <select name="electiveemgc" id="electiveemgc" class="form-control input-sm">
                                                                        <option value=""></option>
                                                                        <option value="ELECTIVE">ELECTIVE</option>
                                                                        <option value="EMERGENCY">EMERGENCY</option>
                                                                    </select>
                                                                </div>

                                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 14px 14px;">
                                                                    <label>Classification</label>
                                                                    <select name="classification" id="classification" class="form-control input-sm">
                                                                        <option value=""></option>
                                                                        <option value="MAJOR">MAJOR</option>
                                                                        <option value="MINOR">MINOR</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="ui grid">
                                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                                    <label>Type of Anaesthesia</label>
                                                                    <select name="anaesthtype" id="anaesthtype" class="form-control input-sm">
                                                                        <option value=""></option>
                                                                        <option value="GA">GA</option>
                                                                        <option value="LA">LA</option>
                                                                        <option value="SPINAL">SPINAL</option>
                                                                        <option value="EPIDURAL">EPIDURAL</option>
                                                                        <option value="CSE">CSE</option>
                                                                        <option value="LAN SEDATION">LAN SEDATION</option>
                                                                        <option value="REGIONAL">REGIONAL</option>
                                                                    </select>
                                                                </div>

                                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 14px 14px;">
                                                                    <label>OT No</label>
                                                                    <select name="otno" id="otno" class="form-control input-sm">
                                                                        <option value=""></option>
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                        <option value="3">3</option>
                                                                        <option value="4">4</option>
                                                                        <option value="5">5</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="sixteen wide column">
                                                    <div class="ui segments">
                                                        <div class="ui segment">
                                                            <div class="ui grid">
                                                                <div class="inline field">
                                                                    <label style="margin:0px; padding: 3px 3px 3px 3px;">Emergency</label>
                                                                    <div class="field">
                                                                        <label>
                                                                            <input type="checkbox" id="e_yes" name="e_yes" value="1">
                                                                            Yes 
                                                                        </label>
                                                                        <label>
                                                                            <input type="checkbox" id="e_no" name="e_no" value="0">
                                                                            No 
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="ui grid">
                                                                <div class="field">
                                                                <label style="margin:0px; padding: 10px 3px 10px 3px;">Material sent to pathologist.</label>
                                                                    <label>
                                                                        <input type="checkbox" id="general" name="general" value="1">
                                                                        General 
                                                                    </label>
                                                                    <label>
                                                                        <input type="checkbox" id="local" name="local" value="1">
                                                                        Local 
                                                                    </label>
                                                                    <label>
                                                                        <input type="checkbox" id="spinal" name="spinal" value="1">
                                                                        Spinal 
                                                                    </label>
                                                                    <label>
                                                                        <input type="checkbox" id="other" name="other" value="1">
                                                                        Other 
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- <div class="sixteen wide column">
                                                    <div class="ui segments">
                                                        <div class="ui segment">

                                                            <div class="ui grid">
                                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                                    <label>First Scrub</label>
                                                                    <input type="text" id="firstscrubnrs" name="firstscrubnrs">
                                                                </div>

                                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 14px 14px;">
                                                                    <label>Second Scrub</label>
                                                                    <input type="text" id="secondscrubnrs" name="secondscrubnrs">
                                                                </div>
                                                            </div>

                                                            <div class="ui grid">
                                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                                    <label>G.A Asst</label>
                                                                    <input type="text" id="gaassistant" name="gaassistant">
                                                                </div>

                                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 14px 14px;">
                                                                    <label>Circulator</label>
                                                                    <input type="text" id="circulator" name="circulator">
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div> -->

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