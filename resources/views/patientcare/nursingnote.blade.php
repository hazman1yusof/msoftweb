<div class="ui segments" style="position: relative;">
    <div class="ui segment" style="padding: 10px 10px 30px 30px;">
        <form id="formNursNote" class="ui form">
            <div class="ui grid">
                <input id="mrn_nursNote" name="mrn_nursNote" type="hidden">
                <input id="episno_nursNote" name="episno_nursNote" type="hidden">
                <input id="age_nursNote" name="age_nursNote" type="hidden">
                <input id="ptname_nursNote" name="ptname_nursNote" type="hidden">
                <input id="preg_nursNote" name="preg_nursNote" type="hidden">
                <input id="ic_nursNote" name="ic_nursNote" type="hidden">
                <input id="doctorname_nursNote" name="doctorname_nursNote" type="hidden">
                <input type="hidden" id="ordcomtt_phar" value="{{$ordcomtt_phar ?? ''}}">
            </div>
        </form>

        <div id="nursNote" class="ui segment">
            <div class="ui top attached tabular menu">
                <a class="item active" data-tab="progress" id="navtab_progress">Progress Note</a>
                <a class="item" data-tab="drug" id="navtab_drug">Drug Administration</a>
                <a class="item" data-tab="pivc" id="navtab_pivc">PIVC</a>
                <a class="item" data-tab="thrombo" id="navtab_thrombo">Thrombophlebitis</a>
            </div>

            <div class="ui bottom attached tab raised segment" data-tab="progress">
                @include('patientcare.nursingnote_progressnote_ED')
            </div>
            
            <div class="ui bottom attached tab raised segment" data-tab="drug">
                <form class="floated ui form sixteen wide column" id="formDrug">
                    <div class="ui segment">
                        <div class="ui grid">
                            <div class="five wide column" style="padding: 3px 3px 3px 3px;">
                                <div class="ui segments">
                                    <div class="ui secondary segment">PRESCRIPTION</div>
                                    <div class="ui segment">
                                        <div class="ui grid">
                                            <table id="tbl_prescription" class="ui celled table tbl_prescription" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th class="scope">auditno</th>
                                                        <th class="scope">mrn</th>
                                                        <th class="scope">episno</th>
                                                        <th class="scope">Charge Code</th>
                                                        <th class="scope">Item</th>
                                                        <th class="scope">Quantity</th>
                                                        <th class="scope">doscode</th>
                                                        <th class="scope">doscode_desc</th>
                                                        <th class="scope">frequency</th>
                                                        <th class="scope">frequency_desc</th>
                                                        <th class="scope">ftxtdosage</th>
                                                        <th class="scope">addinstruction</th>
                                                        <th class="scope">addinstruction_desc</th>
                                                        <th class="scope">drugindicator</th>
                                                        <th class="scope">drugindicator_desc</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                            <div style="position: absolute;
                                                    padding: 0 0 0 0;
                                                    right: 0px;
                                                    bottom: 0px;
                                                    z-index: 1000;">
                                                <button class="ui icon tertiary button tbl_prescription_refresh" id="tbl_prescription_refresh" style="float: right;">
                                                    <i class="sync alternate icon"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="eleven wide column" style="padding: 3px 3px 3px 3px;" >
                                <div class="ui segment">
                                    <input id="trx_auditno" name="trx_auditno" type="hidden">
                                    <input id="trx_chgcode" name="trx_chgcode" type="hidden">
                                    <input id="trx_quantity" name="trx_quantity" type="hidden">

                                    <div class="ui grid">
                                        <div class="sixteen wide column" style="padding-top: 15px;">
                                            <div class="ui disabled input">
                                                <input id="doc_name" name="doc_name" type="text">
                                            </div>
                                        </div>

                                        <div class='sixteen wide column' style="padding: 3px 3px 3px 3px;" >
                                            <div class="fields">
                                                <div class="two wide field" style="padding: 3px 3px 3px 20px;" >
                                                    <label class="oe_phar_label">Dose</label>
                                                </div>
                                                <div class="nine wide field">
                                                    <div class="ui disabled input oe_phar_div">
                                                        <input id="dosage_nursNote" name="dosage" type="text">
                                                        <a class="ui icon blue button"><i class="fa fa-ellipsis-h"></i></a>
                                                    </div>
                                                    <input type="hidden" id="dosage_nursNote_code">
                                                </div>
                                            </div>
                                        </div>

                                        <div class='sixteen wide column' style="padding: 3px 3px 3px 3px;" >
                                            <div class="fields">
                                                <div class="two wide field" style="padding: 3px 3px 3px 20px;"  >
                                                    <label class="oe_phar_label">Frequency</label>
                                                </div>
                                                <div class="nine wide field">
                                                    <div class="ui disabled input oe_phar_div">
                                                        <input id="frequency_nursNote" name="frequency" type="text">
                                                        <a class="ui icon blue button"><i class="fa fa-ellipsis-h"></i></a>
                                                    </div>
                                                    <input type="hidden" id="frequency_nursNote_code">
                                                </div>
                                            </div>
                                        </div>

                                        <div class='sixteen wide column' style="padding: 3px 3px 3px 3px;" >
                                            <div class="fields">
                                                <div class="two wide field" style="padding: 3px 3px 3px 20px;" >
                                                    <label class="oe_phar_label">Instruction</label>
                                                </div>
                                                <div class="nine wide field">
                                                    <div class="ui disabled input oe_phar_div">
                                                        <input id="instruction_nursNote" name="instruction" type="text">
                                                        <a class="ui icon blue button"><i class="fa fa-ellipsis-h"></i></a>
                                                    </div>
                                                    <input type="hidden" id="instruction_nursNote_code">
                                                </div>
                                            </div>
                                        </div>

                                        <div class='sixteen wide column' style="padding: 3px 3px 3px 3px;" >
                                            <div class="fields">
                                                <div class="two wide field" style="padding: 3px 3px 3px 20px;" >
                                                    <label class="oe_phar_label">Indicator</label>
                                                </div>
                                                <div class="nine wide field">
                                                    <div class="ui disabled input oe_phar_div">
                                                        <input id="drugindicator_nursNote" name="drugindicator" type="text">
                                                        <a class="ui icon blue button"><i class="fa fa-ellipsis-h"></i></a>
                                                    </div>
                                                    <input type="hidden" id="drugindicator_nursNote_code">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- <div class="ui segment"> -->
                                        <div class="sixteen wide column" id="patMedic" style="padding: 3px 3px 3px 3px;">
                                            <div class="ui segment" id="jqGridPatMedic_c">
                                                <div class="fields" style="padding-top: 10px;">
                                                    <label>Total Quantity: </label>
                                                    <div class="ui disabled input" style="padding-left: 10px;">
                                                        <input id="tot_qty" name="tot_qty" type="text">
                                                    </div>
                                                </div>
                                                <table id="jqGridPatMedic" class="table table-striped"></table>
                                                <div id="jqGridPagerPatMedic"></div>
                                            </div>
                                        </div>
                                    <!-- </div> -->

                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="ui bottom attached tab raised segment active" data-tab="pivc">
                @include('patientcare.nursingnote_pivc_ED')
            </div>

            <div class="ui bottom attached tab raised segment" data-tab="thrombo">
                @include('patientcare.nursingnote_thrombo_ED')
            </div>
        </div>
    </div>
</div>
