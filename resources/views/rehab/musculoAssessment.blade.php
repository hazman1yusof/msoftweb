<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment" style="height: 50px;">
        <div class="ui small blue icon buttons" id="btn_grp_edit_musculoAssessment" style="position: absolute;
            padding: 0 0 0 0;
            right: 40px;
            top: 9px;
            z-index: 2;">
            <button class="ui button" id="new_musculoAssessment"><span class="fa fa-plus-square-o"></span>New</button>
            <button class="ui button" id="edit_musculoAssessment"><span class="fa fa-edit fa-lg"></span>Edit</button>
            <button class="ui button" id="save_musculoAssessment"><span class="fa fa-save fa-lg"></span>Save</button>
            <button class="ui button" id="cancel_musculoAssessment"><span class="fa fa-ban fa-lg"></span>Cancel</button>
            <button class="ui button" id="musculoAssessment_chart"><span class="fa fa-print fa-lg"></span>Print</button>
        </div>
    </div>
    <div class="ui segment">
        <div class="ui grid">
            <form id="formMusculoAssessment" class="floated ui form sixteen wide column">
                <input id="idno_musculoAssessment" name="idno_musculoAssessment" type="hidden">
                <input id="idno_affectedside" name="idno_affectedside" type="hidden">
                <input id="idno_soundside" name="idno_soundside" type="hidden">
                <input id="idno_musclepwr" name="idno_musclepwr" type="hidden">
                <input id="musculoAssessment_type" name="type" value="musculoskeletal" type="hidden">
                <div class="ui grid">
                    <div class='four wide column'>
                        <div class="ui segments">
                            <div class="ui segment">
                                <div class="ui grid">
                                    <table id="tbl_musculoAssessment_date" class="ui celled table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th class="scope">ma_idno</th>
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
                        <div class="sixteen wide column">
                            <!-- <div class="inline fields">
                                <label>Date</label>
                                <div class="field">
                                    <input id="musculoAssessment_entereddate" name="entereddate" type="date">
                                </div>
                            </div> -->
                            
                            <table class="ui table">
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="inline fields">
                                                <label>DATE:</label>
                                                <div class="field">
                                                    <input type="date" class="form-control" id="musculoAssessment_entereddate" name="entereddate">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>SUBJECTIVE ASSESSMENT:</label>
                                            <div class="inline fields">
                                                <!-- <label>SUBJECTIVE ASSESSMENT:</label> -->
                                                <div class="field">
                                                    <textarea rows="5" cols="60" id="musculoAssessment_subjectiveAssessmt" name="subjectiveAssessmt"></textarea>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>OBJECTIVE ASSESSMENT:</label>
                                            <div class="inline fields">
                                                <!-- <label>OBJECTIVE ASSESSMENT:</label> -->
                                                <div class="field">
                                                    <textarea rows="5" cols="60" id="musculoAssessment_objectiveAssessmt" name="objectiveAssessmt"></textarea>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>BODY CHART:</label>
                                            <div class="ui card" style="margin: 14px 150px;">
                                                <a class="ui card bodydia_musculoskeletal" data-type='DIAG_MUSCULOSKELETAL'>
                                                    <div class="image">
                                                        <img src="{{ asset('patientcare/img/bodydiagmusculoskeletal.png') }}">
                                                    </div>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 5px 50px;">
                                            <table class="ui table">
                                                <thead>
                                                    <tr>
                                                        <th colspan="2">BODY CHART</th>
                                                        <!-- <th></th> -->
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Pain Score</td>
                                                        <td>
                                                            <div class="ui right labeled input">
                                                                <input type="number" name="painscore">
                                                                <div class="ui basic label">/10</div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Type of Pain</td>
                                                        <td><input type="text" class="form-control" name="painType"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Severity</td>
                                                        <td><input type="text" class="form-control" name="severity"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Irritability</td>
                                                        <td><input type="text" class="form-control" name="irritability"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Location of Pain</td>
                                                        <td><input type="text" class="form-control" name="painLocation"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Deep</td>
                                                        <td><input type="text" class="form-control" name="deep"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Superficial</td>
                                                        <td><input type="text" class="form-control" name="superficial"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Subluxation</td>
                                                        <td>
                                                            <div class="ui left labeled input">
                                                                <div class="ui basic label">Comment: </div>
                                                                <input type="text" name="subluxation" size="50">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Palpation</td>
                                                        <td><input type="text" class="form-control" name="palpation"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            
                                            <div class="inline fields">
                                                <label>Impression</label>
                                                <div class="field">
                                                    <textarea rows="6" cols="50" id="musculoAssessment_impressionBC" name="impressionBC"></textarea>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 5px 50px;">
                                            <table class="ui table">
                                                <thead>
                                                    <tr>
                                                        <th colspan="4">SENSATION</th>
                                                        <!-- <th></th>
                                                        <th></th>
                                                        <th></th> -->
                                                    </tr>
                                                    <tr>
                                                        <th>Sensitivity</th>
                                                        <th>R</th>
                                                        <th>L</th>
                                                        <th>(Specification)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Superficial</td>
                                                        <td>
                                                            <input type="checkbox" name="superficialR" value="1">
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" name="superficialL" value="1">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="superficialSpec">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Deep</td>
                                                        <td>
                                                            <input type="checkbox" name="deepR" value="1">
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" name="deepL" value="1">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="deepSpec">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Numbness</td>
                                                        <td>
                                                            <input type="checkbox" name="numbnessR" value="1">
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" name="numbnessL" value="1">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="numbnessSpec">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Paresthesia</td>
                                                        <td>
                                                            <input type="checkbox" name="paresthesiaR" value="1">
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" name="paresthesiaL" value="1">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="paresthesiaSpec">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Other</td>
                                                        <td>
                                                            <input type="checkbox" name="otherR" value="1">
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" name="otherL" value="1">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="otherSpec">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            
                                            <div class="inline fields">
                                                <label>Impression</label>
                                                <div class="field">
                                                    <textarea rows="6" cols="50" id="musculoAssessment_impressionSens" name="impressionSens"></textarea>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 5px 50px;">
                                            <table class="ui table">
                                                <thead>
                                                    <tr>
                                                        <th colspan="8">ROM</th>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="8">
                                                            <div class="inline fields">
                                                                <label for="romAffectedSide">a) Affected Side</label>
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="romAffectedSide" value="R" id="musculoAssessment_romAffectedSideR">
                                                                        <label for="musculoAssessment_romAffectedSideR">R</label>
                                                                    </div>
                                                                </div>
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="romAffectedSide" value="L" id="musculoAssessment_romAffectedSideL">
                                                                        <label for="musculoAssessment_romAffectedSideL">L</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
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
                                                        <td></td>
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
                                            
                                            <table class="ui table">
                                                <thead>
                                                    <tr>
                                                        <td colspan="8">
                                                            <div class="inline fields">
                                                                <label for="romSoundSide">b) Sound Side</label>
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="romSoundSide" value="R" id="musculoAssessment_romSoundSideR">
                                                                        <label for="musculoAssessment_romSoundSideR">R</label>
                                                                    </div>
                                                                </div>
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="romSoundSide" value="L" id="musculoAssessment_romSoundSideL">
                                                                        <label for="musculoAssessment_romSoundSideL">L</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
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
                                                        <td></td>
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
                                            
                                            <div class="inline fields" style="display: none;">
                                                <label>Impression</label>
                                                <div class="field">
                                                    <textarea rows="6" cols="50" id="musculoAssessment_impressionROM" name="impressionROM"></textarea>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 5px 50px;">
                                            <table class="ui table">
                                                <thead>
                                                    <tr>
                                                        <th colspan="5">MUSCLE POWER</th>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="5">
                                                            <div class="inline fields">
                                                                <label for="affectedSide">a) Affected Side</label>
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="affectedSide" value="R" id="musculoAssessment_affectedSideR">
                                                                        <label for="musculoAssessment_affectedSideR">R</label>
                                                                    </div>
                                                                </div>
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="affectedSide" value="L" id="musculoAssessment_affectedSideL">
                                                                        <label for="musculoAssessment_affectedSideL">L</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>JOINT</th>
                                                        <th>MOVEMENT</th>
                                                        <th>INITIAL</th>
                                                        <th>PROGRESS</th>
                                                        <th>FINAL</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Shoulder</td>
                                                        <td>Flexion</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aShlderFlxInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aShlderFlxProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aShlderFlxFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Extension</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aShlderExtInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aShlderExtProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aShlderExtFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Abduction</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aShlderAbdInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aShlderAbdProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aShlderAbdFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Adduction</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aShlderAddInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aShlderAddProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aShlderAddFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Int Rot</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aShlderIntRotInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aShlderIntRotProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aShlderIntRotFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Ext Rot</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aShlderExtRotInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aShlderExtRotProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aShlderExtRotFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Elbow</td>
                                                        <td>Flexion</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aElbowFlxInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aElbowFlxProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aElbowFlxFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Extension</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aElbowExtInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aElbowExtProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aElbowExtFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Pronation</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aElbowProInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aElbowProProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aElbowProFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Supination</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aElbowSupInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aElbowSupProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aElbowSupFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Wrist</td>
                                                        <td>Flexion</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aWristFlxInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aWristFlxProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aWristFlxFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Extension</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aWristExtInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aWristExtProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aWristExtFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Radial Deviation</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aWristRadInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aWristRadProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aWristRadFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Ulnar Deviation</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aWristUlnarInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aWristUlnarProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aWristUlnarFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Hip</td>
                                                        <td>Flexion</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aHipFlxInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aHipFlxProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aHipFlxFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Extension</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aHipExtInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aHipExtProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aHipExtFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Abduction</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aHipAbdInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aHipAbdProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aHipAbdFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Adduction</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aHipAddInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aHipAddProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aHipAddFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Int Rot</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aHipIntRotInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aHipIntRotProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aHipIntRotFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Ext Rot</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aHipExtRotInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aHipExtRotProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aHipExtRotFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Knee</td>
                                                        <td>Flexion</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aKneeFlxInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aKneeFlxProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aKneeFlxFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Extension</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aKneeExtInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aKneeExtProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aKneeExtFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Ankle</td>
                                                        <td>Dorsiflexion</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aAnkleDorsInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aAnkleDorsProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aAnkleDorsFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Plantar flexion</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aAnklePtarInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aAnklePtarProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aAnklePtarFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Eversion</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aAnkleEverInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aAnkleEverProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aAnkleEverFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Inversion</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aAnkleInverInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aAnkleInverProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="aAnkleInverFin">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            
                                            <div class="inline fields">
                                                <label>Impression</label>
                                                <div class="field">
                                                    <textarea rows="6" cols="50" id="musculoAssessment_impressionAMP" name="impressionAMP"></textarea>
                                                </div>
                                            </div>
                                            
                                            <table class="ui table">
                                                <thead>
                                                    <tr>
                                                        <td colspan="5">
                                                            <div class="inline fields">
                                                                <label for="soundSide">b) Sound Side</label>
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="soundSide" value="R" id="musculoAssessment_soundSideR">
                                                                        <label for="musculoAssessment_soundSideR">R</label>
                                                                    </div>
                                                                </div>
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="soundSide" value="L" id="musculoAssessment_soundSideL">
                                                                        <label for="musculoAssessment_soundSideL">L</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>JOINT</th>
                                                        <th>MOVEMENT</th>
                                                        <th>INITIAL</th>
                                                        <th>PROGRESS</th>
                                                        <th>FINAL</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Shoulder</td>
                                                        <td>Flexion</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sShlderFlxInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sShlderFlxProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sShlderFlxFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Extension</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sShlderExtInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sShlderExtProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sShlderExtFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Abduction</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sShlderAbdInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sShlderAbdProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sShlderAbdFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Adduction</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sShlderAddInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sShlderAddProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sShlderAddFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Int Rot</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sShlderIntRotInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sShlderIntRotProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sShlderIntRotFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Ext Rot</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sShlderExtRotInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sShlderExtRotProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sShlderExtRotFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Elbow</td>
                                                        <td>Flexion</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sElbowFlxInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sElbowFlxProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sElbowFlxFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Extension</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sElbowExtInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sElbowExtProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sElbowExtFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Pronation</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sElbowProInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sElbowProProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sElbowProFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Supination</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sElbowSupInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sElbowSupProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sElbowSupFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Wrist</td>
                                                        <td>Flexion</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sWristFlxInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sWristFlxProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sWristFlxFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Extension</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sWristExtInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sWristExtProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sWristExtFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Radial Deviation</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sWristRadInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sWristRadProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sWristRadFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Ulnar Deviation</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sWristUlnarInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sWristUlnarProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sWristUlnarFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Hip</td>
                                                        <td>Flexion</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sHipFlxInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sHipFlxProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sHipFlxFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Extension</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sHipExtInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sHipExtProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sHipExtFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Abduction</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sHipAbdInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sHipAbdProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sHipAbdFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Adduction</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sHipAddInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sHipAddProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sHipAddFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Int Rot</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sHipIntRotInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sHipIntRotProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sHipIntRotFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Ext Rot</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sHipExtRotInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sHipExtRotProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sHipExtRotFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Knee</td>
                                                        <td>Flexion</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sKneeFlxInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sKneeFlxProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sKneeFlxFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Extension</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sKneeExtInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sKneeExtProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sKneeExtFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Ankle</td>
                                                        <td>Dorsiflexion</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sAnkleDorsInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sAnkleDorsProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sAnkleDorsFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Plantar flexion</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sAnklePtarInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sAnklePtarProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sAnklePtarFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Eversion</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sAnkleEverInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sAnkleEverProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sAnkleEverFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Inversion</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sAnkleInverInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sAnkleInverProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sAnkleInverFin">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            
                                            <div class="inline fields">
                                                <label>Impression</label>
                                                <div class="field">
                                                    <textarea rows="6" cols="50" id="musculoAssessment_impressionSMP" name="impressionSMP"></textarea>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 5px 50px;">
                                            <table class="ui table">
                                                <thead>
                                                    <tr>
                                                        <th colspan="5">FUNCTIONAL ACTIVITY</th>
                                                    </tr>
                                                    <tr>
                                                        <th scope="col">NO</th>
                                                        <th scope="col">ACTIVITY</th>
                                                        <th scope="col">INITIAL</th>
                                                        <th scope="col">PROGRESS</th>
                                                        <th scope="col">FINAL</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th scope="row">1</th>
                                                        <td>Transfer</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="transferInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="transferProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="transferFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">2</th>
                                                        <td>Supto Side Ly.</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="suptoSideInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="suptoSideProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="suptoSideFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">3</th>
                                                        <td>Side Ly. To Sitt</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sideToSitInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sideToSitProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sideToSitFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">4</th>
                                                        <td>Sitt</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sittInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sittProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sittFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">5</th>
                                                        <td>Sitt To Std</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sitToStdInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sitToStdProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sitToStdFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">6</th>
                                                        <td>Std</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="stdInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="stdProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="stdFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">7</th>
                                                        <td>Shifting Ability</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="shiftInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="shiftProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="shiftFin">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">8</th>
                                                        <td>Ambulation</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="ambulationInit">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="ambulationProg">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="ambulationFin">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            
                                            <div class="inline fields">
                                                <label>Impression</label>
                                                <div class="field">
                                                    <textarea rows="6" cols="50" id="musculoAssessment_impressionFA" name="impressionFA"></textarea>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>INTERVENTION:</label>
                                            <div class="inline fields">
                                                <!-- <label>INTERVENTION:</label> -->
                                                <div class="field">
                                                    <textarea rows="5" cols="60" id="musculoAssessment_intervention" name="intervention"></textarea>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>HOME EDUCATION:</label>
                                            <div class="inline fields">
                                                <!-- <label>HOME EDUCATION:</label> -->
                                                <div class="field">
                                                    <textarea rows="5" cols="60" id="musculoAssessment_homeEducation" name="homeEducation"></textarea>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>EVALUATION:</label>
                                            <div class="inline fields">
                                                <!-- <label>EVALUATION:</label> -->
                                                <div class="field">
                                                    <textarea rows="5" cols="60" id="musculoAssessment_evaluation" name="evaluation"></textarea>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>REVIEW:</label>
                                            <div class="inline fields">
                                                <!-- <label>REVIEW:</label> -->
                                                <div class="field">
                                                    <textarea rows="5" cols="60" id="musculoAssessment_review" name="review"></textarea>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            <table class="ui table" style="margin-top: 30px;">
                                <tbody>
                                    <tr>
                                        <td>
                                            <label>ADDITIONAL NOTES:</label>
                                            <div class="inline fields">
                                                <!-- <label>ADDITIONAL NOTES:</label> -->
                                                <div class="field">
                                                    <textarea rows="5" cols="60" id="musculoAssessment_additionalNotes" name="additionalNotes"></textarea>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>