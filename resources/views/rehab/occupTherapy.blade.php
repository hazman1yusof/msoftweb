<div class="ui segments" style="position: relative;">    
    <div class="ui segment" style="padding: 10px 10px 30px 30px;">
        <form id="formOccupTherapy" class="ui form">
            <div class="ui grid">
                <input id="mrn_occupTherapy" name="mrn_occupTherapy" type="hidden">
                <input id="episno_occupTherapy" name="episno_occupTherapy" type="hidden">
            </div>
        </form>
        
        <div id="occupTherapy" class="ui segment">
            <div class="ui top attached tabular menu">
                <a class="item active" data-tab="cognitive" id="navtab_cognitive">Cognitive</a>
                <a class="item" data-tab="physical" id="navtab_physical">Physical</a>
                <a class="item" data-tab="adl" id="navtab_adl">Activity Daily Living</a>
            </div>
            
            <div class="ui bottom attached tab raised segment active" data-tab="cognitive">
                @include('rehab.occupTherapy.occupTherapy_cognitive')
            </div>
            
            <div class="ui bottom attached tab raised segment" data-tab="physical">
                @include('rehab.occupTherapy.occupTherapy_upperExtremity')
            </div>

            <div class="ui bottom attached tab raised segment" data-tab="adl">
                @include('rehab.occupTherapy.occupTherapy_barthel')
            </div>
        </div>
    </div>
</div>