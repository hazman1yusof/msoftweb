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
        </div>
        
        <div class="ui bottom attached tab raised segment active" data-tab="rehabilitation">
            @include('patientcare.physiotherapy.physioterapy')
        </div>
        
        <div class="ui bottom attached tab raised segment" data-tab="neurorobotic">
            @include('rehab.neurorobotic.neurorobotic')
        </div>
        
        <div class="ui bottom attached tab raised segment" data-tab="physiotherapy">
            @include('rehab.physio.physio')
        </div>
        
        <div class="ui bottom attached tab raised segment" data-tab="occupTherapy">
            @include('rehab.occupTherapy.occupTherapy')
        </div>
    </div>
</div>