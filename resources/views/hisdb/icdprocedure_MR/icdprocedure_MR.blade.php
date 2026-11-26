<div class="ui segments" style="position: relative; padding: 10px">
    <form id="formIcdProcedure" class="ui form">
        <div class="ui grid">
            <input id="mrn_icdprocedure" name="mrn_icdprocedure" type="hidden">
            <input id="episno_icdprocedure" name="episno_icdprocedure" type="hidden">
            
            <div class="sixteen wide column">
                <div class="ui segments">
                    <div class="ui secondary segment">Final Diagnosis</div>
                    <div class="ui segment" id="jqGridFinalDiag_c">
                        <table id="jqGridFinalDiag" class="table table-striped"></table>
                        <div id="jqGridPagerFinalDiag"></div>
                    </div>
                </div>
            </div>
            
            <div class="sixteen wide column">
                <div class="ui segments">
                    <div class="ui secondary segment">External Cause</div>
                    <div class="ui segment" id="jqGridExternalCause_c">
                        <table id="jqGridExternalCause" class="table table-striped"></table>
                        <div id="jqGridPagerExternalCause"></div>
                    </div>
                </div>
            </div>
            
            <div class="sixteen wide column">
                <div class="ui segments">
                    <div class="ui secondary segment">Underlying Cause</div>
                    <div class="ui segment" id="jqGridUnderCause_c">
                        <table id="jqGridUnderCause" class="table table-striped"></table>
                        <div id="jqGridPagerUnderCause"></div>
                    </div>
                </div>
            </div>
            
            <div class="sixteen wide column">
                <div class="ui segments">
                    <div class="ui secondary segment">Procedure</div>
                    <div class="ui segment" id="jqGridProcedure_c">
                        <table id="jqGridProcedure" class="table table-striped"></table>
                        <div id="jqGridPagerProcedure"></div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>