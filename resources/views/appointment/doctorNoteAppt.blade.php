<div class="ui column">
    <div class="ui segments" style="position: relative;">
        <div class="ui secondary segment bluecloudsegment">
            <div class="ui form">
                <div class="six wide column">
                    <div class="inline fields" style="margin-bottom: 0px;">
                        <label style="color: rgba(0,0,0,.6);">DOCTOR NOTES</label>
                        <div class="field">
                            <div class="ui radio checkbox checked pastcurr_docNoteAppt">
                                <input type="radio" name="toggle_type_docNoteAppt" checked="" tabindex="0" class="hidden" id="current_doctorNoteAppt" value="current" checked>
                                <label for="current_doctorNoteAppt">Current</label>
                            </div>
                        </div>
                        
                        <div class="field">
                            <div class="ui radio checkbox pastcurr_docNoteAppt">
                                <input type="radio" name="toggle_type_docNoteAppt" tabindex="0" class="hidden" id="past_doctorNoteAppt" value="past">
                                <label for="past_doctorNoteAppt">Past History</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ui small blue icon buttons" id="btn_grp_edit_doctorNoteAppt" style="position: absolute;
                        padding: 0 0 0 0;
                        right: 40px;
                        top: 9px;
                        z-index: 2;">
                <button class="ui button" id="new_doctorNoteAppt"><span class="fa fa-plus-square-o"></span>New</button>
                <!-- <button class="ui button" id="edit_doctorNoteAppt"><span class="fa fa-edit fa-lg"></span>Edit</button> -->
                <button class="ui button" id="save_doctorNoteAppt"><span class="fa fa-save fa-lg"></span>Save</button>
                <button class="ui button" id="cancel_doctorNoteAppt"><span class="fa fa-ban fa-lg"></span>Cancel</button>
            </div>
        </div>
        <div class="ui segment">
            <div class="three wide column" style="position: absolute;
                        left: 10px;
                        top: 30px;">
                <table id="docNoteAppt_date_tbl" class="ui celled table" style="min-width: 310px;">
                    <thead>
                        <tr>
                            <th class="scope">mrn</th>
                            <th class="scope">episno</th>
                            <th class="scope">Date</th>
                            <th class="scope">adduser</th>
                            <th class="scope">datetaken</th>
                            <th class="scope">timetaken</th>
                            <th class="scope">Doctor</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="ui grid">
                <form id="formDoctorNoteAppt" class="right floated ui form twelve wide column">
                    <input id="mrn_doctorNoteAppt" name="mrn_doctorNoteAppt" type="hidden">
                    <input id="episno_doctorNoteAppt" name="episno_doctorNoteAppt" type="hidden">
                    <input id="age_doctorNoteAppt" name="age_doctorNoteAppt" type="hidden">
                    <!-- <input id="recorddate" name="recorddate" type="hidden"> -->
                    <input id="recorddate_doctorNoteAppt" name="recorddate_doctorNoteAppt" type="hidden">
                    <input id="mrn_doctorNoteAppt_past" name="mrn_doctorNoteAppt_past" type="hidden">
                    <input id="episno_doctorNoteAppt_past" name="episno_doctorNoteAppt_past" type="hidden">
                    <input id="ptname_doctorNoteAppt" name="ptname_doctorNoteAppt" type="hidden">
                    <input id="preg_doctorNoteAppt" name="preg_doctorNoteAppt" type="hidden">
                    <input id="ic_doctorNoteAppt" name="ic_doctorNoteAppt" type="hidden">
                    <input id="doctorname_doctorNoteAppt" name="doctorname_doctorNoteAppt" type="hidden">
                    
                    <div class='ui grid'>
                        <div class="seven wide column">
                            <div class="inline fields">
                                <div class="field">
                                    <label>Date</label>
                                    <input id="datetaken_doctorNoteAppt" name="datetaken" type="text" rdonly>
                                </div>
                                <div class="field">
                                    <label>Time</label>
                                    <input id="timetaken_doctorNoteAppt" name="timetaken" type="time">
                                </div>
                            </div>
                        </div>
                        
                        <div class="sixteen wide column">
                            <div class="ui segments">
                                <div class="ui secondary segment">CLIENT'S PROGRESS NOTES</div>
                                <div class="ui segment">
                                    <textarea id="progressnote_doctorNoteAppt" name="progressnote" type="text" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="sixteen wide column" style="display: none;" id="addNotesAppt">
                            <div class="ui segments">
                                <div class="ui secondary segment">Additional Notes</div>
                                <div class="ui segment" id="jqGridAddNotesAppt_c">
                                    <table id="jqGridAddNotesAppt" class="table table-striped"></table>
                                    <div id="jqGridPagerAddNotesAppt"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>