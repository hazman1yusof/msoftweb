<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment" style="height: 50px;">
        <div class="ui small blue icon buttons" id="btn_grp_edit_followupReqfor" style="position: absolute;
            padding: 0 0 0 0;
            right: 40px;
            top: 9px;
            z-index: 2;">
            <button class="ui button" id="new_followupReqfor"><span class="fa fa-plus-square-o"></span>New</button>
            <button class="ui button" id="edit_followupReqfor"><span class="fa fa-edit fa-lg"></span>Edit</button>
            <button class="ui button" id="save_followupReqfor"><span class="fa fa-save fa-lg"></span>Save</button>
            <button class="ui button" id="cancel_followupReqfor"><span class="fa fa-ban fa-lg"></span>Cancel</button>
            <!-- <button class="ui button" id="followupReqfor_chart"><span class="fa fa-print fa-lg"></span>Print</button> -->
        </div>
    </div>
    <div class="ui segment">
        <div class="ui grid">
            <form id="formfollowupReqfor" class="floated ui form sixteen wide column">
                <input id="doctorcodefup" name="doctorcodefup" type="hidden" >
                <input id="end_timefup" name="end_timefup" type="hidden" >
                <div class='ui grid'>
                    <div class="ten wide column" style="padding-top:0px; margin: auto;">
                        <div class="field">
                            <label>Doctor</label>
                            <div class="ui action input">
                                <input id="docnamefup" name="docnamefup" type="text" data-validation="required">
                                <a class="ui icon blue button"><i class="fa fa-ellipsis-h"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="ten wide column" style="padding-top:0px; margin: auto;">
                        <div class="two fields">
                            <div class="field">
                                <label>Date</label>
                                <input type="date" name="datefup" id="datefup" data-validation="required" min='{{\Carbon\Carbon::now("Asia/Kuala_Lumpur")->toDateString()}}'>
                            </div>
                            <div class="field">
                                <label>Time</label>
                                <div class="ui action input">
                                    <input id="timefup" name="timefup" type="text" data-validation="required">
                                    <a class="ui icon blue button"><i class="fa fa-ellipsis-h"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ten wide column" style="padding-top:0px; margin: auto;">
                        <div class="field">
                            <label>Remarks</label>
                            <textarea rows="5" id="remarkfup" name="remarkfup" data-validation="required" ></textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>