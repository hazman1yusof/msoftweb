<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment" style="height: 50px;">
        <div class="ui small blue icon buttons" id="btn_grp_edit_physioNotes" style="position: absolute;
            padding: 0 0 0 0;
            right: 40px;
            top: 9px;
            z-index: 2;">
            <button class="ui button" id="new_physioNotes"><span class="fa fa-plus-square-o"></span>New</button>
            <button class="ui button" id="edit_physioNotes"><span class="fa fa-edit fa-lg"></span>Edit</button>
            <button class="ui button" id="save_physioNotes"><span class="fa fa-save fa-lg"></span>Save</button>
            <button class="ui button" id="cancel_physioNotes"><span class="fa fa-ban fa-lg"></span>Cancel</button>
            <button class="ui button" id="physioNotes_chart"><span class="fa fa-print fa-lg"></span>Print</button>
        </div>
    </div>
    <div class="ui segment">
        <div class="ui grid">
            <form id="formPhysioNotes" class="floated ui form sixteen wide column">
                <input id="idno_physioNotes" name="idno_physioNotes" type="hidden">
                <div class="ui grid">
                    <div class='four wide column'>
                        <div class="ui segments">
                            <div class="ui segment">
                                <div class="ui grid">
                                    <table id="tbl_physioNotes_date" class="ui celled table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th class="scope">idno</th>
                                                <th class="scope">mrn</th>
                                                <th class="scope">episno</th>
                                                <th class="scope">Date</th>
                                                <th class="scope">dt</th>
                                                <th class="scope">Entered By</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class='twelve wide column'>
                        <div class="sixteen wide column">
                            <div class="inline fields">
                                <label>Date</label>
                                <div class="field">
                                    <input id="physioNotes_entereddate" name="entereddate" type="date" data-validation="required" data-validation-error-msg-required="Please enter information.">
                                </div>
                            </div>
                            
                            <div class="sixteen wide column">
                                <div class="ui form">
                                    <div class="field">
                                        <label>Notes</label>
                                        <textarea rows="10" cols="50" id="physioNotes_notes" name="notes"></textarea>
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