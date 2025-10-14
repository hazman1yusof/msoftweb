<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment">
        ENDOSCOPY NOTES
        <div class="ui small blue icon buttons" id="btn_grp_edit_endoscopyNotes" style="position: absolute; 
                    padding: 0 0 0 0; 
                    right: 40px; 
                    top: 9px; 
                    z-index: 2;">
            <!-- <button class="ui button" id="new_endoscopyNotes"><span class="fa fa-plus-square-o"></span>New</button>
            <button class="ui button" id="edit_endoscopyNotes"><span class="fa fa-edit fa-lg"></span>Edit</button>
            <button class="ui button" id="save_endoscopyNotes"><span class="fa fa-save fa-lg"></span>Save</button>
            <button class="ui button" id="cancel_endoscopyNotes"><span class="fa fa-ban fa-lg"></span>Cancel</button> -->
        </div>
    </div>
    
    <div class="ui segment" style="padding: 10px 10px 30px 30px;">
        <form id="formEndoscopyNotes" class="ui form">
            <div class="ui grid">
                <input id="mrn_endoscopyNotes" name="mrn_endoscopyNotes" type="hidden">
                <input id="episno_endoscopyNotes" name="episno_endoscopyNotes" type="hidden">
                <input id="age_endoscopyNotes" name="age_endoscopyNotes" type="hidden">
            </div>
        </form>
        
        <div id="endoscopyNotes" class="ui segment">
            <div class="ui top attached tabular menu">
                <a class="item active" data-tab="endoscopyStomach" id="navtab_endoscopyStomach">Stomach</a>
                <a class="item" data-tab="endoscopyIntestine" id="navtab_endoscopyIntestine">Intestine</a>
            </div>
            
            <div class="ui bottom attached tab raised segment active" data-tab="endoscopyStomach">
                @include('hisdb.endoscopyNotes.endoscopyStomach')
            </div>
            
            <div class="ui bottom attached tab raised segment" data-tab="endoscopyIntestine">
                @include('hisdb.endoscopyNotes.endoscopyIntestine')
            </div>
        </div>
    </div>
</div>