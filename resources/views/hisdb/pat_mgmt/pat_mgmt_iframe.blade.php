@if($phase == 'bootstrap')
<div id="mdl_patient_info" data-keyboard="false" class="modal fade ba" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true" style="display: none; z-index: 1000; padding-left: 0px !important;">
    <iframe style="display:block; border:none; height:100vh; width:100vw;" id="mdl_patient_iframe"></iframe>
</div>
@else
<div id="mdl_patient_info" class="ui modal fullscreen" style="
    z-index: 1000;     
    width: 97vw !important;
    height: 97vh !important; 
    margin: 0px !important;
">
    <iframe style="display:block; border:none; height:98vh; width:98vw;" id="mdl_patient_iframe"></iframe>
</div>

<div id="mdl_episode_info" class="ui modal fullscreen" style="
    z-index: 1000;     
    width: 97vw !important;
    height: 97vh !important; 
    margin: 0px !important;
">
    <iframe style="display:block; border:none; height:98vh; width:98vw;" id="mdl_episode_iframe"></iframe>
</div>
@endif