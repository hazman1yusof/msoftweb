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
                        <div class='three wide column' style="padding: 3px 3px 3px 3px;">
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

                        <div class='thirteen wide column' style="padding: 3px 3px 3px 3px;">
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

                                

                                <!-- <div class="ui segments">
                                    <div class="ui secondary segment collapsed" data-toggle="collapse" data-target="#rof">
                                        <i class="angle down icon large"></i>
                                        <i class="angle up icon large"></i>
                                        <h4 style="text-align:center;margin-top:3px">RANGE OF MOTION</h4>
                                    </div>
                                    <div class="ui segment collapse" id="rof" style="overflow: scroll hidden;">
                                        <div id="jqGrid_rof_c" style="padding: 5px 5px 5px 5px; width: 2000px;">
                                            <table id="jqGrid_rof" class="table table-striped"></table>
                                            <div id="jqGridPager_rof"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="ui segments">
                                    <div class="ui secondary segment collapsed" data-toggle="collapse" data-target="#hand">
                                        <i class="angle down icon large"></i>
                                        <i class="angle up icon large"></i>
                                        <h4 style="text-align:center;margin-top:3px">HAND</h4>
                                    </div>
                                    <div class="ui segment collapse" id="hand" style="overflow: scroll hidden;">
                                        <div id="jqGrid_hand_c" style="padding: 5px 5px 5px 5px; width: 3200px;">
                                            <table id="jqGrid_hand" class="table table-striped"></table>
                                            <div id="jqGridPager_hand"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="ui segments">
                                    <div class="ui secondary segment collapsed" data-toggle="collapse" data-target="#muscle">
                                        <i class="angle down icon large"></i>
                                        <i class="angle up icon large"></i>
                                        <h4 style="text-align:center;margin-top:3px">MUSCLE STRENGTH</h4>
                                    </div>
                                    
                                </div>

                                <div class="ui segments">
                                    <div class="ui secondary segment collapsed" data-toggle="collapse" data-target="#sensation">
                                        <i class="angle down icon large"></i>
                                        <i class="angle up icon large"></i>
                                        <h4 style="text-align:center;margin-top:3px">SENSATION</h4>
                                    </div>
                                    
                                </div>

                                <div class="ui segments">
                                    <div class="ui secondary segment collapsed" data-toggle="collapse" data-target="#prehensive">
                                        <i class="angle down icon large"></i>
                                        <i class="angle up icon large"></i>
                                        <h4 style="text-align:center;margin-top:3px">PREHENSIVE PATTERN</h4>
                                    </div>
                                    
                                </div>

                                <div class="ui segments">
                                    <div class="ui secondary segment collapsed" data-toggle="collapse" data-target="#skin">
                                        <i class="angle down icon large"></i>
                                        <i class="angle up icon large"></i>
                                        <h4 style="text-align:center;margin-top:3px">SKIN CONDITION/SCARRING</h4>
                                    </div>
                                    
                                </div>

                                <div class="ui segments">
                                    <div class="ui secondary segment collapsed" data-toggle="collapse" data-target="#edema">
                                        <i class="angle down icon large"></i>
                                        <i class="angle up icon large"></i>
                                        <h4 style="text-align:center;margin-top:3px">EDEMA</h4>
                                    </div>
                                    
                                </div>

                                <div class="ui segments">
                                    <div class="ui secondary segment collapsed" data-toggle="collapse" data-target="#functional">
                                        <i class="angle down icon large"></i>
                                        <i class="angle up icon large"></i>
                                        <h4 style="text-align:center;margin-top:3px">FUNCTIONAL ACTIVITIES</h4>
                                    </div>
                                    
                                </div> -->

                            </div>
                            <div id="upExt" class="ui bottom attached tab raised segment active">
                                 <div class="ui top attached tabular menu">
                                    <a class="item active" data-tab="rof"><h5>Range of Motion</h5></a>
                                    <a class="item" data-tab="hand"><h5>Hand</h5></a>
                                    <a class="item" data-tab="muscle"><h5>Muscle<br>Strength</h5></a>
                                    <a class="item" data-tab="sensation"><h5>Sensation</h5></a>
                                    <a class="item" data-tab="prehensive"><h5>Prehensive Pattern</h5></a>
                                    <a class="item" data-tab="skin"><h5>Skin Condition/<br>Scarring</h5></a>
                                    <a class="item" data-tab="edema"><h5>Edema</h5></a>
                                    <a class="item" data-tab="functional"><h5>Functional<br>Activities</h5></a>

                                </div>

                                <div class="ui bottom attached tab raised segment active" data-tab="rof">
                                    <div id="jqGrid_rof_c" style="padding: 3px 3px 3px 3px;">
                                        <table id="jqGrid_rof" class="table table-striped"></table>
                                        <div id="jqGridPager_rof"></div>
                                    </div>
                                </div>

                                <div class="ui bottom attached tab raised segment" data-tab="hand">
                                    <div id="jqGrid_hand_c" style="padding: 3px 3px 3px 3px;">
                                        <table id="jqGrid_hand" class="table table-striped"></table>
                                        <div id="jqGridPager_hand"></div>
                                    </div>
                                </div>

                                <div class="ui bottom attached tab raised segment" data-tab="muscle"></div>

                                <div class="ui bottom attached tab raised segment" data-tab="sensation"></div>

                                <div class="ui bottom attached tab raised segment" data-tab="prehensive"></div>

                                <div class="ui bottom attached tab raised segment" data-tab="skin"></div>

                                <div class="ui bottom attached tab raised segment" data-tab="edema"></div>

                                <div class="ui bottom attached tab raised segment" data-tab="functional"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>