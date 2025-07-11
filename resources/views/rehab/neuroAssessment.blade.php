<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment" style="height: 50px;">
        <div class="ui small blue icon buttons" id="btn_grp_edit_neuroAssessment" style="position: absolute;
            padding: 0 0 0 0;
            right: 40px;
            top: 9px;
            z-index: 2;">
            <button class="ui button" id="new_neuroAssessment"><span class="fa fa-plus-square-o"></span>New</button>
            <button class="ui button" id="edit_neuroAssessment"><span class="fa fa-edit fa-lg"></span>Edit</button>
            <button class="ui button" id="save_neuroAssessment"><span class="fa fa-save fa-lg"></span>Save</button>
            <button class="ui button" id="cancel_neuroAssessment"><span class="fa fa-ban fa-lg"></span>Cancel</button>
            <!-- <button class="ui button" id="neuroAssessment_chart"><span class="fa fa-print fa-lg"></span>Print</button> -->
        </div>
    </div>
    <div class="ui segment">
        <div class="ui grid">
            <form id="formNeuroAssessment" class="floated ui form sixteen wide column">
                <input id="idno_neuroAssessment" name="idno_neuroAssessment" type="hidden">
                <input id="idno_romaffectedside" name="idno_romaffectedside" type="hidden">
                <input id="idno_romsoundside" name="idno_romsoundside" type="hidden">
                <input id="idno_musclepower" name="idno_musclepower" type="hidden">
                <input id="neuroAssessment_type" name="type" value="neuro" type="hidden">
                <div class="ui grid">
                    <div class='four wide column'>
                        <div class="ui segments">
                            <div class="ui segment">
                                <div class="ui grid">
                                    <table id="tbl_neuroAssessment_date" class="ui celled table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th class="scope">n_idno</th>
                                                <th class="scope">mrn</th>
                                                <th class="scope">episno</th>
                                                <th class="scope">Date</th>
                                                <th class="scope">dt</th>
                                                <th class="scope">Entered By</th>
                                                <th class="scope">a_idno</th>
                                                <th class="scope">s_idno</th>
                                                <th class="scope">m_idno</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class='twelve wide column'>
                        <div class="inline fields">
                            <label>DATE</label>
                            <div class="field" style="padding-left: 50px;">
                                <input id="neuroAssessment_entereddate" name="entereddate" type="date">
                            </div>
                        </div>
                        
                        <div class="inline fields">
                            <label>OBJECTIVE AX</label>
                            <div class="field">
                                <textarea rows="6" cols="50" id="neuroAssessment_objective" name="objective"></textarea>
                            </div>
                        </div>
                        
                        <div class="ui grid">
                            <div class="sixteen wide column">
                                <div class="ui segments">
                                    <div class="ui secondary segment">BODY CHART</div>
                                    <div class="ui segment">
                                        <div class="ui grid">
                                            <div class="ten wide column">
                                                <div class="ui two cards">
                                                    <a class="ui card bodydia_neuro" data-type='BB_NEURO'>
                                                        <div class="image">
                                                            <img src="{{ asset('patientcare/img/bodydiagneuro4.png') }}">
                                                        </div>
                                                    </a>
                                                    <a class="ui card bodydia_neuro" data-type='BF_NEURO'>
                                                        <div class="image">
                                                            <img src="{{ asset('patientcare/img/bodydiagneuro1.png') }}">
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                            
                                            <div class="four wide column">
                                                <div class="ui form">
                                                    <div class="field">
                                                        <label>Pain Score</label>
                                                        <div class="ui right labeled input">
                                                            <input type="number" name="painscore">
                                                            <div class="ui basic label">/10</div>
                                                        </div>
                                                    </div>
                                                    <div class="field">
                                                        <label>Type of Pain</label>
                                                        <input type="text" name="painType">
                                                    </div>
                                                    <div class="field">
                                                        <label>Severity</label>
                                                        <input type="text" name="severityBC">
                                                    </div>
                                                    <div class="field">
                                                        <label>Irritability</label>
                                                        <input type="text" name="irritabilityBC">
                                                    </div>
                                                    <div class="field">
                                                        <label>Location of Pain</label>
                                                        <div class="inline fields">
                                                            <!-- <label for="painLocation">Location of Pain</label> -->
                                                            <div class="field">
                                                                <div class="ui radio checkbox">
                                                                    <input type="radio" id="pain_deep" name="painLocation" value="Deep">
                                                                    <label for="pain_deep">Deep</label>
                                                                </div>
                                                            </div>
                                                            <div class="field">
                                                                <div class="ui radio checkbox">
                                                                    <input type="radio" id="pain_superficial" name="painLocation" value="Superficial">
                                                                    <label for="pain_superficial">Superficial</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="field">
                                                        <label>Subluxation</label>
                                                        <div class="ui left labeled input">
                                                            <div class="ui basic label">Comment: </div>
                                                            <input type="text" name="subluxation">
                                                        </div>
                                                    </div>
                                                    <div class="field">
                                                        <label>Palpation</label>
                                                        <input type="text" name="palpationBC">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="inline fields">
                                                <label>Impression</label>
                                                <div class="field">
                                                    <textarea rows="6" cols="50" id="neuroAssessment_impressionBC" name="impressionBC"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            @include('rehab.neuroAssessment_p1')
                            
                            <div class="sixteen wide column">
                                <div class="ui segments">
                                    <div class="ui secondary segment">ROM</div>
                                    <div class="ui segment">
                                        <div class="ui grid">
                                            <div class="sixteen wide column">
                                                <div class="inline fields">
                                                    <label for="romAffectedSide">a) Affected Side</label>
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="romAffectedSide" value="R" id="romAffectedSideR">
                                                            <label for="romAffectedSideR">R</label>
                                                        </div>
                                                    </div>
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="romAffectedSide" value="L" id="romAffectedSideL">
                                                            <label for="romAffectedSideL">L</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <table class="ui celled striped table">
                                                    <thead>
                                                        <tr>
                                                            <th>Joint</th>
                                                            <th>Movement</th>
                                                            <th colspan="2">INITIAL</th>
                                                            <th colspan="2">PROGRESS</th>
                                                            <th colspan="2">FINAL</th>
                                                        </tr>
                                                        <tr>
                                                            <th></th>
                                                            <th></th>
                                                            <th>P</th>
                                                            <th>A</th>
                                                            <th>P</th>
                                                            <th>A</th>
                                                            <th>P</th>
                                                            <th>A</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Shoulder</td>
                                                            <td>Flexion</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aShlderFlxInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aShlderFlxInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aShlderFlxProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aShlderFlxProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aShlderFlxFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aShlderFlxFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Extension</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aShlderExtInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aShlderExtInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aShlderExtProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aShlderExtProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aShlderExtFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aShlderExtFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Abduction</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aShlderAbdInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aShlderAbdInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aShlderAbdProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aShlderAbdProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aShlderAbdFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aShlderAbdFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Adduction</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aShlderAddInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aShlderAddInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aShlderAddProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aShlderAddProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aShlderAddFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aShlderAddFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Int Rot</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aShlderIntRotInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aShlderIntRotInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aShlderIntRotProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aShlderIntRotProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aShlderIntRotFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aShlderIntRotFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Ext Rot</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aShlderExtRotInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aShlderExtRotInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aShlderExtRotProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aShlderExtRotProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aShlderExtRotFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aShlderExtRotFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Elbow</td>
                                                            <td>Flexion</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aElbowFlxInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aElbowFlxInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aElbowFlxProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aElbowFlxProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aElbowFlxFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aElbowFlxFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Extension</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aElbowExtInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aElbowExtInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aElbowExtProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aElbowExtProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aElbowExtFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aElbowExtFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Pronation</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aElbowProInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aElbowProInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aElbowProProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aElbowProProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aElbowProFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aElbowProFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Supination</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aElbowSupInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aElbowSupInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aElbowSupProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aElbowSupProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aElbowSupFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aElbowSupFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Wrist</td>
                                                            <td>Flexion</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aWristFlxInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aWristFlxInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aWristFlxProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aWristFlxProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aWristFlxFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aWristFlxFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Extension</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aWristExtInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aWristExtInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aWristExtProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aWristExtProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aWristExtFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aWristExtFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Radial Deviation</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aWristRadInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aWristRadInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aWristRadProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aWristRadProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aWristRadFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aWristRadFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Ulnar Deviation</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aWristUlnarInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aWristUlnarInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aWristUlnarProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aWristUlnarProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aWristUlnarFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aWristUlnarFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Hip</td>
                                                            <td>Flexion</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aHipFlxInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aHipFlxInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aHipFlxProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aHipFlxProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aHipFlxFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aHipFlxFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Extension</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aHipExtInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aHipExtInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aHipExtProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aHipExtProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aHipExtFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aHipExtFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Abduction</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aHipAbdInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aHipAbdInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aHipAbdProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aHipAbdProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aHipAbdFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aHipAbdFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Adduction</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aHipAddInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aHipAddInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aHipAddProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aHipAddProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aHipAddFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aHipAddFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Int Rot</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aHipIntRotInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aHipIntRotInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aHipIntRotProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aHipIntRotProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aHipIntRotFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aHipIntRotFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Ext Rot</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aHipExtRotInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aHipExtRotInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aHipExtRotProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aHipExtRotProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aHipExtRotFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aHipExtRotFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Knee</td>
                                                            <td>Extension</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aKneeExtInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aKneeExtInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aKneeExtProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aKneeExtProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aKneeExtFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aKneeExtFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Flexion</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aKneeFlxInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aKneeFlxInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aKneeFlxProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aKneeFlxProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aKneeFlxFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aKneeFlxFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Ankle</td>
                                                            <td>Dorsiflexion</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aAnkleDorsInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aAnkleDorsInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aAnkleDorsProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aAnkleDorsProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aAnkleDorsFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aAnkleDorsFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Plantar flexion</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aAnklePtarInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aAnklePtarInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aAnklePtarProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aAnklePtarProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aAnklePtarFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aAnklePtarFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Eversion</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aAnkleEverInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aAnkleEverInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aAnkleEverProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aAnkleEverProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aAnkleEverFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aAnkleEverFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Inversion</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aAnkleInverInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aAnkleInverInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aAnkleInverProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aAnkleInverProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aAnkleInverFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="aAnkleInverFinA">
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                
                                                <div class="inline fields">
                                                    <label for="romSoundSide">b) Sound Side</label>
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="romSoundSide" value="R" id="romSoundSideR">
                                                            <label for="romSoundSideR">R</label>
                                                        </div>
                                                    </div>
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="romSoundSide" value="L" id="romSoundSideL">
                                                            <label for="romSoundSideL">L</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <table class="ui celled striped table">
                                                    <thead>
                                                        <tr>
                                                            <th>Joint</th>
                                                            <th>Movement</th>
                                                            <th colspan="2">INITIAL</th>
                                                            <th colspan="2">PROGRESS</th>
                                                            <th colspan="2">FINAL</th>
                                                        </tr>
                                                        <tr>
                                                            <th></th>
                                                            <th></th>
                                                            <th>P</th>
                                                            <th>A</th>
                                                            <th>P</th>
                                                            <th>A</th>
                                                            <th>P</th>
                                                            <th>A</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Shoulder</td>
                                                            <td>Flexion</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sShlderFlxInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sShlderFlxInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sShlderFlxProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sShlderFlxProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sShlderFlxFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sShlderFlxFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Extension</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sShlderExtInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sShlderExtInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sShlderExtProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sShlderExtProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sShlderExtFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sShlderExtFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Abduction</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sShlderAbdInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sShlderAbdInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sShlderAbdProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sShlderAbdProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sShlderAbdFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sShlderAbdFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Adduction</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sShlderAddInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sShlderAddInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sShlderAddProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sShlderAddProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sShlderAddFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sShlderAddFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Int Rot</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sShlderIntRotInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sShlderIntRotInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sShlderIntRotProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sShlderIntRotProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sShlderIntRotFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sShlderIntRotFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Ext Rot</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sShlderExtRotInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sShlderExtRotInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sShlderExtRotProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sShlderExtRotProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sShlderExtRotFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sShlderExtRotFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Elbow</td>
                                                            <td>Flexion</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sElbowFlxInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sElbowFlxInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sElbowFlxProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sElbowFlxProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sElbowFlxFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sElbowFlxFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Extension</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sElbowExtInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sElbowExtInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sElbowExtProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sElbowExtProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sElbowExtFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sElbowExtFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Pronation</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sElbowProInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sElbowProInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sElbowProProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sElbowProProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sElbowProFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sElbowProFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Supination</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sElbowSupInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sElbowSupInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sElbowSupProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sElbowSupProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sElbowSupFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sElbowSupFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Wrist</td>
                                                            <td>Flexion</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sWristFlxInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sWristFlxInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sWristFlxProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sWristFlxProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sWristFlxFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sWristFlxFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Extension</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sWristExtInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sWristExtInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sWristExtProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sWristExtProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sWristExtFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sWristExtFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Radial Deviation</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sWristRadInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sWristRadInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sWristRadProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sWristRadProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sWristRadFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sWristRadFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Ulnar Deviation</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sWristUlnarInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sWristUlnarInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sWristUlnarProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sWristUlnarProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sWristUlnarFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sWristUlnarFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Hip</td>
                                                            <td>Flexion</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sHipFlxInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sHipFlxInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sHipFlxProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sHipFlxProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sHipFlxFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sHipFlxFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Extension</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sHipExtInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sHipExtInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sHipExtProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sHipExtProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sHipExtFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sHipExtFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Abduction</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sHipAbdInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sHipAbdInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sHipAbdProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sHipAbdProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sHipAbdFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sHipAbdFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Adduction</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sHipAddInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sHipAddInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sHipAddProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sHipAddProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sHipAddFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sHipAddFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Int Rot</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sHipIntRotInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sHipIntRotInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sHipIntRotProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sHipIntRotProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sHipIntRotFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sHipIntRotFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Ext Rot</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sHipExtRotInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sHipExtRotInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sHipExtRotProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sHipExtRotProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sHipExtRotFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sHipExtRotFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Knee</td>
                                                            <td>Extension</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sKneeExtInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sKneeExtInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sKneeExtProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sKneeExtProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sKneeExtFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sKneeExtFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Flexion</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sKneeFlxInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sKneeFlxInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sKneeFlxProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sKneeFlxProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sKneeFlxFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sKneeFlxFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Ankle</td>
                                                            <td>Dorsiflexion</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sAnkleDorsInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sAnkleDorsInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sAnkleDorsProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sAnkleDorsProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sAnkleDorsFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sAnkleDorsFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Plantar flexion</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sAnklePtarInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sAnklePtarInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sAnklePtarProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sAnklePtarProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sAnklePtarFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sAnklePtarFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Eversion</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sAnkleEverInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sAnkleEverInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sAnkleEverProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sAnkleEverProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sAnkleEverFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sAnkleEverFinA">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>Inversion</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sAnkleInverInitP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sAnkleInverInitA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sAnkleInverProgP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sAnkleInverProgA">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sAnkleInverFinP">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="sAnkleInverFinA">
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                
                                                <div class="inline fields">
                                                    <label>Impression</label>
                                                    <div class="field">
                                                        <textarea rows="6" cols="50" id="neuroAssessment_impressionROM" name="impressionROM"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            @include('rehab.neuroAssessment_p2')
                            
                            <div class="sixteen wide column">
                                <div class="ui segments">
                                    <div class="ui secondary segment">SUMMARY</div>
                                    <div class="ui segment">
                                        <div class="ui form">
                                            <div class="field"><textarea rows="6" cols="50" id="neuroAssessment_summary" name="summary"></textarea></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>