<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment" style="height: 50px;">
        NOTES
        <div class="ui small blue icon buttons" id="btn_grp_edit_notes" style="position: absolute;
            padding: 0 0 0 0;
            right: 40px;
            top: 9px;
            z-index: 2;">
            <button class="ui button" id="new_notes"><span class="fa fa-plus-square-o"></span>New</button>
            <button class="ui button" id="edit_notes"><span class="fa fa-edit fa-lg"></span>Edit</button>
            <button class="ui button" id="save_notes"><span class="fa fa-save fa-lg"></span>Save</button>
            <button class="ui button" id="cancel_notes"><span class="fa fa-ban fa-lg"></span>Cancel</button>
            <button class="ui button" id="notes_chart"><span class="fa fa-print fa-lg"></span>Print</button>
        </div>
    </div>
    <div class="ui segment">
        <div class="ui grid">
            <form id="formOccupTherapyNotes" class="floated ui form sixteen wide column">
            <input id="idno_notes" name="idno_notes" type="hidden">

                <div class="sixteen wide column">
                    <div class="ui grid">
                        <div class='five wide column' style="padding: 3px 3px 3px 3px;">
                            <div class="ui segments">
                                <div class="ui segment">
                                    <div class="ui grid">
                                        <table id="datetimeNotes_tbl" class="ui celled table" style="width: 100%;">
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
                        </div>

                        <div class='eleven wide column' style="padding: 3px 3px 3px 3px;">
                            <div class="ui segment">
                                <div class='ui grid' style="padding: 5px 3px 3px 2px;">
                                    <div class="sixteen wide column" style="padding: 10px 0px 0px 3px;">
                                        <div class="inline fields">
                                            <div class="field">
                                                <label for="dateNotes" style="padding-left: 15px;">Date: </label>
                                                <input id="dateNotes" name="dateNotes" type="date" class="form-control input-sm" value="<?php echo date("Y-m-d"); ?>" data-validation="required" data-validation-error-msg-required="Please enter information.">
                                            </div>                                         
                                        </div>
                                    </div>

                                    <div class="sixteen wide column">
                                        <div class="ui form">
                                            <div class="field" style="margin:0px; padding: 3px 3px 3px 3px;">
                                                <label>Notes:</label>
                                                <textarea id="notes" name="notes" rows="10" cols="50"></textarea>
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
</div>