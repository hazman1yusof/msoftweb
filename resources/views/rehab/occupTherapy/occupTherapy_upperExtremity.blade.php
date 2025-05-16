<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment" style="height: 50px;">
        UPPER EXTREMITY ASSESSMENT FORM
        <div class="ui small blue icon buttons" id="btn_grp_edit_upperExtremity" style="position: absolute;
            padding: 0 0 0 0;
            right: 40px;
            top: 9px;
            z-index: 2;">
            <button class="ui button" id="new_upperExtremity"><span class="fa fa-plus-square-o"></span>New</button>
            <button class="ui button" id="edit_upperExtremity"><span class="fa fa-edit fa-lg"></span>Edit</button>
            <button class="ui button" id="save_upperExtremity"><span class="fa fa-save fa-lg"></span>Save</button>
            <button class="ui button" id="cancel_upperExtremity"><span class="fa fa-ban fa-lg"></span>Cancel</button>
            <!-- <button class="ui button" id="upperExtremity_chart"><span class="fa fa-print fa-lg"></span>Print</button> -->
        </div>
    </div>
    <div class="ui segment">
        <div class="ui grid">
            <form id="formOccupTherapyUpperExtremity" class="floated ui form sixteen wide column">
            <input id="idno_upperExtremity" name="idno_upperExtremity" type="hidden">

                <div class="sixteen wide column">
                    <div class="ui grid">
                        <div class='five wide column' style="padding: 3px 3px 3px 3px;">
                            <div class="ui segments">
                                <div class="ui segment">
                                    <div class="ui grid">
                                        <table id="datetimeUpperExtremity_tbl" class="ui celled table" style="width: 100%;">
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

                                        <div class="ui grid">
                                            <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                <label>Date</label>
                                                <input id="dateAssess" name="dateAssess" type="date" class="form-control input-sm" value="<?php echo date("Y-m-d"); ?>" data-validation="required" data-validation-error-msg-required="Please enter information.">
                                            </div>

                                            <div class="field eight wide column" style="margin:0px; padding: 3px 20px 14px 14px;">
                                                <label>Occupational Therapist</label>
                                                <input type="text" id="occupTherapist" name="occupTherapist">
                                            </div>
                                        </div>

                                        <div class="ui grid">
                                            <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                <label>Right/Left Dominant</label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="handDominant" value="R" class="score">Right
                                                    </label>
                                                    <label class="radio-inline" style="padding-right: 5px;">
                                                        <input type="radio" name="handDominant" value="L" class="score">Left
                                                    </label>                                            
                                            </div>

                                            <div class="field eight wide column" style="margin:0px; padding: 3px 20px 50px 14px;">
                                                <label>Diagnosis</label>
                                                <textarea id="diagnosis" name="diagnosis" row="4"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                
                                <div class="ui segments">
                                    <div class="ui secondary segment collapsed" data-toggle="collapse" data-target="#rof">
                                        <i class="angle down icon large"></i>
                                        <i class="angle up icon large"></i>
                                        <h4 style="text-align:center;margin-top:3px">RANGE OF MOTION</h4>
                                    </div>
                                    <div class="ui segment collapse" id="rof">
                                        <div class="ui form">
                                            <div class="sixteen wide column">
                                                <div class="ui segments">
                                                    <div class="ui segment" id="jqGrid_rof_c" style="padding: 5px 5px 5px 5px;">
                                                        <table id="jqGrid_rof" class="table table-striped"></table>
                                                        <div id="jqGridPager_rof"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="ui segments">
                                    <div class="ui secondary segment collapsed" data-toggle="collapse" data-target="#hand">
                                        <i class="angle down icon large"></i>
                                        <i class="angle up icon large"></i>
                                        <h4 style="text-align:center;margin-top:3px">HAND</h4>
                                    </div>
                                </div>
                                <div class="ui segment collapse" id="hand">
                                    <div class="ui form">
                                        <div class="sixteen wide column">
                                            <div class="ui segments">
                                                <!-- <div class="ui segment" id="jqGrid_hand_c" style="padding: 14px 5px;">
                                                    <table id="jqGrid_hand" class="table table-striped"></table>
                                                    <div id="jqGridPager_hand"></div>
                                                </div> -->
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