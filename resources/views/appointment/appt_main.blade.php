<!-- <div class="ui segments" style="position: relative;"> -->
    <div class="ui secondary segment bluecloudsegment" style="display: none;">
        CLINICAL
        <div class="ui small blue icon buttons" id="btn_grp_edit_apptMain" style="position: absolute; 
                    padding: 0 0 0 0; 
                    right: 40px; 
                    top: 9px; 
                    z-index: 2;">
            <!-- <button class="ui button" id="new_apptMain"><span class="fa fa-plus-square-o"></span>New</button>
            <button class="ui button" id="edit_apptMain"><span class="fa fa-edit fa-lg"></span>Edit</button>
            <button class="ui button" id="save_apptMain"><span class="fa fa-save fa-lg"></span>Save</button>
            <button class="ui button" id="cancel_apptMain"><span class="fa fa-ban fa-lg"></span>Cancel</button> -->
        </div>
    </div>
    
    <!-- <div class="ui segment"> -->
        <form id="formApptMain" class="ui form">
            <div class="ui grid">
                <!-- <input type="hidden" name="curr_user" id="curr_user_apptMain" value="{{ Auth::user()->username }}"> -->
                <input id="mrn_apptMain" name="mrn_apptMain" type="hidden">
                <input id="episno_apptMain" name="episno_apptMain" type="hidden">
                <input id="age_apptMain" name="age_apptMain" type="hidden">
            </div>
        </form>
        
        <div id="apptMainTabs" class="ui segment">
            <div class="ui top attached tabular menu">
                <a class="item apptMainItem active" data-tab="docImaging" id="navtab_docImaging">Document Imaging</a>
                <a class="item apptMainItem" data-tab="triageInfo" id="navtab_triageInfo">Triage Information</a>
                <a class="item apptMainItem" data-tab="doctorNote" id="navtab_doctorNote">Doctor Note</a>
                <a class="item apptMainItem" data-tab="reqFor" id="navtab_reqFor">Request For</a>
                <a class="item apptMainItem" data-tab="admHandover" id="navtab_admHandover">Admission Handover</a>
                <a class="item apptMainItem" data-tab="dietNotes" id="navtab_dietNotes">Dietetic Care Notes</a>
            </div>
            
            <div class="ui bottom attached tab raised segment active" data-tab="docImaging">
                @include('patientcare.userfile_div')
            </div>
            
            <div class="ui bottom attached tab raised segment" data-tab="triageInfo">
                @include('appointment.nursingAppt')
            </div>
            
            <div class="ui bottom attached tab raised segment" data-tab="doctorNote">
                @include('patientcare.doctornote_div')
            </div>
            
            <div class="ui bottom attached tab raised segment" data-tab="reqFor">
                @include('patientcare.requestfor')
            </div>
            
            <div class="ui bottom attached tab raised segment" data-tab="admHandover">
                @include('appointment.admhandoverAppt')
            </div>
            
            <div class="ui bottom attached tab raised segment" data-tab="dietNotes" style="height: 2510px;">
                @include('patientcare.dieteticCareNotes')
            </div>
        </div>
    <!-- </div> -->
<!-- </div> -->