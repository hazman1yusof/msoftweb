<div class="ui segments" style="position: relative;">
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
    
    <div class="ui segment" style="padding: 10px 10px 30px 30px;">
        <form id="formPhysiotherapy" class="ui form">
            <div class="ui grid">
                <!-- <input type="hidden" name="curr_user" id="curr_user_physio" value="{{ Auth::user()->username }}"> -->
                <input id="mrn_physio" name="mrn_physio" type="hidden">
                <input id="episno_physio" name="episno_physio" type="hidden">
                <input id="age_physio" name="episno_physio" type="hidden">
            </div>
        </form>
        
        <div id="physioTabs" class="ui segment">
            <div class="ui top attached tabular menu">
                <a class="item active" data-tab="sixMinWalking" id="navtab_sixMinWalking">6-Minute Walking<br>Test</a>
                <a class="item" data-tab="bergBalanceTest" id="navtab_bergBalanceTest">Berg Balance Positions<br>and Tests</a>
                <a class="item" data-tab="posturalAssessment" id="navtab_posturalAssessment">Postural<br>Assessment</a>
                <a class="item" data-tab="oswestryQuest" id="navtab_oswestryQuest">Oswestry Low Back<br>Disability Questionnaire</a>
                <a class="item" data-tab="neuroAssessment" id="navtab_neuroAssessment">Neurological Physiotherapy<br>Assessment</a>
                <a class="item" data-tab="motorScale" id="navtab_motorScale">Motor Assessment<br>Scale</a>
                <a class="item" data-tab="spinalCord" id="navtab_spinalCord">Spinal Cord<br>Injury</a>
            </div>
            
            <div class="ui bottom attached tab raised segment active" data-tab="sixMinWalking">
                @include('rehab.sixMinWalking')
            </div>
            
            <div class="ui bottom attached tab raised segment" data-tab="bergBalanceTest">
                @include('rehab.bergBalanceTest')
            </div>
            
            <div class="ui bottom attached tab raised segment" data-tab="posturalAssessment">
                @include('rehab.posturalAssessment')
            </div>
            
            <div class="ui bottom attached tab raised segment" data-tab="oswestryQuest">
                @include('rehab.oswestryQuest')
            </div>
            
            <div class="ui bottom attached tab raised segment" data-tab="neuroAssessment">
                @include('rehab.neuroAssessment')
            </div>
            
            <div class="ui bottom attached tab raised segment" data-tab="motorScale">
                @include('rehab.motorScale')
            </div>
            
            <div class="ui bottom attached tab raised segment" data-tab="spinalCord">
                @include('rehab.spinalCord')
            </div>
        </div>
    </div>
</div>