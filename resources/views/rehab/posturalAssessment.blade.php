<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment" style="height: 50px;">
        <div class="ui small blue icon buttons" id="btn_grp_edit_posturalAssessment" style="position: absolute;
            padding: 0 0 0 0;
            right: 40px;
            top: 9px;
            z-index: 2;">
            <button class="ui button" id="new_posturalAssessment"><span class="fa fa-plus-square-o"></span>New</button>
            <button class="ui button" id="edit_posturalAssessment"><span class="fa fa-edit fa-lg"></span>Edit</button>
            <button class="ui button" id="save_posturalAssessment"><span class="fa fa-save fa-lg"></span>Save</button>
            <button class="ui button" id="cancel_posturalAssessment"><span class="fa fa-ban fa-lg"></span>Cancel</button>
            <button class="ui button" id="posturalAssessment_chart"><span class="fa fa-print fa-lg"></span>Print</button>
        </div>
    </div>
    <div class="ui segment">
        <div class="ui grid">
            <form id="formPosturalAssessment" class="floated ui form sixteen wide column">
                <input id="idno_posturalAssessment" name="idno_posturalAssessment" type="hidden">
                <div class="ui grid">
                    <div class='four wide column'>
                        <div class="ui segments">
                            <div class="ui segment">
                                <div class="ui grid">
                                    <table id="tbl_posturalAssessment_date" class="ui celled table" style="width: 100%;">
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
                        <div class="inline fields">
                            <label>Date</label>
                            <div class="field">
                                <input id="posturalAssessment_entereddate" name="entereddate" type="date">
                            </div>
                        </div>
                        
                        <div class="ui grid">
                            <div class='sixteen wide column'>
                                @include('rehab.posturalAssessmt_div')
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>