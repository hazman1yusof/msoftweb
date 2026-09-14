<!-- <div class="ui segments" style="position: relative;">     -->
    <!-- <div class="ui segment" style="position: relative;"> -->
        <form id="formOccupTherapy" class="ui form">
            <div class="ui grid">
                <!-- <input id="mrn_occupTherapy" name="mrn_occupTherapy" type="hidden">
                <input id="episno_occupTherapy" name="episno_occupTherapy" type="hidden"> -->
            </div>
        </form>
        
        <div id="occupTherapy" class="ui segment">
            <div class="ui top attached tabular menu">
                <a class="item active" data-tab="notes" id="navtab_notes">Notes</a>
                <a class="item" data-tab="cognitive" id="navtab_cognitive">Cognitive</a>
                <a class="item" data-tab="physical" id="navtab_physical">Physical</a>
                <a class="item" data-tab="adl" id="navtab_adl">Activity Daily Living</a>
            </div>

            <div class="ui bottom attached tab raised segment active" data-tab="notes">
                @include('rehab_MR.occupTherapy_MR.occupTherapy_notes_MR')
            </div>
            
            <!-- <div class="ui bottom attached tab raised segment" data-tab="cognitive"> -->
                @include('rehab_MR.occupTherapy_MR.occupTherapy_cognitive_MR')
            <!-- </div> -->
            
            <div class="ui bottom attached tab raised segment" data-tab="physical">
                @include('rehab_MR.occupTherapy_MR.occupTherapy_upperExtremity_MR')
            </div>

            <div class="ui bottom attached tab raised segment" data-tab="adl">
                @include('rehab_MR.occupTherapy_MR.occupTherapy_barthel_MR')
            </div>
        </div>
    <!-- </div> -->
<!-- </div> -->