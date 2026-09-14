<div class="ui segments" style="position: relative; padding: 10px">
    <form id="formRehabMain" class="ui form">
        <div class="ui grid">
            <input id="mrn_rehabMain" name="mrn_rehabMain" type="hidden">
            <input id="episno_rehabMain" name="episno_rehabMain" type="hidden">
            <input id="age_rehabMain" name="age_rehabMain" type="hidden">
        </div>
    </form>
    
    <div id="rehabMain_tab" class="ui segment">
        <div class="ui top attached tabular menu">
            <a class="item active" data-tab="rehabilitation" id="navtab_rehabilitation">PERKESO AX NOTES</a>
            <a class="item" data-tab="neurorobotic" id="navtab_neurorobotic">NEUROROBOTIC</a>
            <a class="item" data-tab="physiotherapy" id="navtab_physiotherapy">PHYSIOTHERAPY</a>
            <a class="item" data-tab="occupTherapy" id="navtab_occupTherapy">OCCUPATIONAL THERAPY</a>
            <a class="item" data-tab="speechTherapy" id="navtab_speechTherapy">SPEECH THERAPY</a>
            <a class="item" data-tab="psychotherapy" id="navtab_psychotherapy">PSYCHOTHERAPY</a>
            <a class="item" data-tab="dietitian" id="navtab_dietitian">DIETITIAN</a>
        </div>
        
        <div class="ui bottom attached tab raised segment active" data-tab="rehabilitation">
            @include('patientcare.physiotherapy_MR.physioterapy_MR')
        </div>
        
        <div class="ui bottom attached tab raised segment" data-tab="neurorobotic">
            @include('rehab_MR.neurorobotic_MR.neurorobotic_MR')
        </div>
        
        <div class="ui bottom attached tab raised segment" data-tab="physiotherapy">
            @include('rehab_MR.physio_MR.physio_MR')
        </div>
        
        <div class="ui bottom attached tab raised segment" data-tab="occupTherapy">
            @include('rehab_MR.occupTherapy_MR.occupTherapy_MR')
        </div>
        
        <div class="ui bottom attached tab raised segment" data-tab="speechTherapy">
            @include('rehab_MR.speechTherapy_MR.speechTherapy_MR')
        </div>
        
        <div class="ui bottom attached tab raised segment" data-tab="psychotherapy">
            @include('rehab_MR.psychotherapy_MR.psychotherapy_MR')
        </div>
        
        <div class="ui bottom attached tab raised segment" data-tab="dietitian">
            @include('rehab_MR.dietitian_MR.dietitian_MR')
        </div>
    </div>
</div>