<!-- <div class="ui segments" style="position: relative;"> -->
    <div class="ui secondary segment bluecloudsegment" style="display: none;">
        PHYSIOTHERAPY
        <div class="ui small blue icon buttons" id="btn_grp_edit_physio" style="position: absolute; 
                    padding: 0 0 0 0; 
                    right: 40px; 
                    top: 9px; 
                    z-index: 2;">
            <!-- <button class="ui button" id="new_physio"><span class="fa fa-plus-square-o"></span>New</button>
            <button class="ui button" id="edit_physio"><span class="fa fa-edit fa-lg"></span>Edit</button>
            <button class="ui button" id="save_physio"><span class="fa fa-save fa-lg"></span>Save</button>
            <button class="ui button" id="cancel_physio"><span class="fa fa-ban fa-lg"></span>Cancel</button> -->
        </div>
    </div>
    
    <!-- <div class="ui segment"> -->
        <form id="formPhysiotherapy" class="ui form">
            <div class="ui grid">
                <!-- <input type="hidden" name="curr_user" id="curr_user_physio" value="{{ Auth::user()->username }}"> -->
                <!-- <input id="mrn_physio" name="mrn_physio" type="hidden">
                <input id="episno_physio" name="episno_physio" type="hidden">
                <input id="age_physio" name="age_physio" type="hidden"> -->
            </div>
        </form>
        
        <div id="physioTabs" class="ui segment">
            <div class="ui top attached tabular menu">
                <a class="item active" data-tab="sixMinWalking" id="navtab_sixMinWalking">6-Minute<br>Walking<br>Test</a>
                <a class="item" data-tab="bergBalanceTest" id="navtab_bergBalanceTest">Berg Balance<br>Positions<br>and Tests</a>
                <a class="item" data-tab="musculoAssessment" id="navtab_musculoAssessment">Musculoskeletal<br>Assessment</a>
                <a class="item" data-tab="posturalAssessment" id="navtab_posturalAssessment">Postural<br>Assessment</a>
                <a class="item" data-tab="oswestryQuest" id="navtab_oswestryQuest">Oswestry<br>Low Back<br>Disability<br>Questionnaire</a>
                <a class="item" data-tab="cardiorespAssessment" id="navtab_cardiorespAssessment">Cardiorespiratory<br>Assessment</a>
                <a class="item" data-tab="neuroAssessment" id="navtab_neuroAssessment">Neurological<br>Physiotherapy<br>Assessment</a>
                <a class="item" data-tab="motorScale" id="navtab_motorScale">Motor<br>Assessment<br>Scale</a>
                <a class="item" data-tab="spinalCord" id="navtab_spinalCord">Spinal<br>Cord<br>Injury</a>
                <a class="item" data-tab="physioNotes" id="navtab_physioNotes">Notes</a>
            </div>
            
            <div class="ui bottom attached tab raised segment active" data-tab="sixMinWalking">
                @include('rehab_MR.physio_MR.sixMinWalking_MR')
            </div>
            
            <div class="ui bottom attached tab raised segment" data-tab="bergBalanceTest">
                @include('rehab_MR.physio_MR.bergBalanceTest_MR')
            </div>
            
            <div class="ui bottom attached tab raised segment" data-tab="musculoAssessment">
                @include('rehab_MR.physio_MR.musculoAssessment_MR')
            </div>
            
            <div class="ui bottom attached tab raised segment" data-tab="posturalAssessment">
                @include('rehab_MR.physio_MR.posturalAssessment_MR')
            </div>
            
            <div class="ui bottom attached tab raised segment" data-tab="oswestryQuest">
                @include('rehab_MR.physio_MR.oswestryQuest_MR')
            </div>
            
            <div class="ui bottom attached tab raised segment" data-tab="cardiorespAssessment">
                @include('rehab_MR.physio_MR.cardiorespAssessment_MR')
            </div>
            
            <div class="ui bottom attached tab raised segment" data-tab="neuroAssessment">
                @include('rehab_MR.physio_MR.neuroAssessment_MR')
            </div>
            
            <div class="ui bottom attached tab raised segment" data-tab="motorScale">
                @include('rehab_MR.physio_MR.motorScale_MR')
            </div>
            
            <div class="ui bottom attached tab raised segment" data-tab="spinalCord">
                @include('rehab_MR.physio_MR.spinalCord_MR')
            </div>
            
            <div class="ui bottom attached tab raised segment" data-tab="physioNotes">
                @include('rehab_MR.physio_MR.physioNotes_MR')
            </div>
        </div>
    <!-- </div> -->
<!-- </div> -->