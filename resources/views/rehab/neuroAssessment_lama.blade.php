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
                            <label>Date</label>
                            <div class="field">
                                <input id="neuroAssessment_entereddate" name="entereddate" type="date">
                            </div>
                        </div>
                        
                        <div class="ui grid">
                            <div class='sixteen wide column'>
                                <div class="ui segments">
                                    <div class="ui secondary segment collapsed" data-toggle="collapse" data-target="#neuro_bodyChart">
                                        <i class="angle down icon large"></i>
                                        <i class="angle up icon large"></i>
                                        <h4 style="text-align: center; margin-top: 3px;">BODY CHART</h4>
                                    </div>
                                    <div class="ui segment collapse" id="neuro_bodyChart">
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
                                                        <input type="text" name="severity">
                                                    </div>
                                                    <div class="field">
                                                        <label>Irritability</label>
                                                        <input type="text" name="irritability">
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
                                                        <input type="text" name="palpation">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="ui segments">
                                    <div class="ui secondary segment collapsed" data-toggle="collapse" data-target="#neuro_sensation">
                                        <i class="angle down icon large"></i>
                                        <i class="angle up icon large"></i>
                                        <h4 style="text-align: center; margin-top: 3px;">SENSATION</h4>
                                    </div>
                                    <div class="ui segment collapse" id="neuro_sensation">
                                        <div class="ui grid">
                                            <div class="sixteen wide column">
                                                <table class="ui striped table">
                                                    <thead>
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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="ui segments">
                                    <div class="ui secondary segment collapsed" data-toggle="collapse" data-target="#neuro_rom">
                                        <i class="angle down icon large"></i>
                                        <i class="angle up icon large"></i>
                                        <h4 style="text-align: center; margin-top: 3px;">ROM</h4>
                                    </div>
                                    <div class="ui segment collapse" id="neuro_rom">
                                        <div class="ui grid">
                                            <div class="sixteen wide column">
                                                <div class="inline fields">
                                                    <label for="romAffectedSide">a) Affected Side</label>
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="romAffectedSide" value="R" id="affectedSideR">
                                                            <label for="affectedSideR">R</label>
                                                        </div>
                                                    </div>
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="romAffectedSide" value="L" id="affectedSideL">
                                                            <label for="affectedSideL">L</label>
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
                                                    </tbody>
                                                </table>
                                                
                                                <div class="inline fields">
                                                    <label>b) Sound Side</label>
                                                    <div class="field">
                                                        <input name="romSoundSide" type="text">
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
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="ui segments">
                                    <div class="ui secondary segment collapsed" data-toggle="collapse" data-target="#neuro_mas">
                                        <i class="angle down icon large"></i>
                                        <i class="angle up icon large"></i>
                                        <h4 style="text-align: center; margin-top: 3px;">MUSCLE TONE (MAS)</h4>
                                    </div>
                                    <div class="ui segment collapse" id="neuro_mas">
                                        <div class="ui grid">
                                            <div class="five wide column" style="margin: auto;">
                                                <div class="ui form">
                                                    <div class="field">
                                                        <div class="inline fields">
                                                            <label for="muscleUL">UL</label>
                                                            <div class="field">
                                                                <input type="text" name="muscleUL">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="field">
                                                        <div class="inline fields">
                                                            <label for="muscleLL">LL</label>
                                                            <div class="field">
                                                                <input type="text" name="muscleLL">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="ui segments">
                                    <div class="ui secondary segment collapsed" data-toggle="collapse" data-target="#neuro_tendon">
                                        <i class="angle down icon large"></i>
                                        <i class="angle up icon large"></i>
                                        <h4 style="text-align: center; margin-top: 3px;">DEEP TENDON REFLEX</h4>
                                    </div>
                                    <div class="ui segment collapse" id="neuro_tendon">
                                        <div class="ui grid">
                                            <div class="sixteen wide column">
                                                <table class="ui striped table">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th>RT</th>
                                                            <th>LT</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>BTR</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="btrRT">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="btrLT">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>TTR</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="ttrRT">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="ttrLT">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>KTR</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="ktrRT">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="ktrLT">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>ATR</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="atrRT">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="atrLT">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Babinsky</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="babinskyRT">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="babinskyLT">
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="ui segments">
                                    <div class="ui secondary segment collapsed" data-toggle="collapse" data-target="#neuro_musclePower">
                                        <i class="angle down icon large"></i>
                                        <i class="angle up icon large"></i>
                                        <h4 style="text-align: center; margin-top: 3px;">MUSCLE POWER</h4>
                                    </div>
                                    <div class="ui segment collapse" id="neuro_musclePower">
                                        <div class="ui grid">
                                            <div class="sixteen wide column">
                                                <div class="inline fields">
                                                    <label>a) Affected Side</label>
                                                    <div class="field">
                                                        <input name="affectedSide" type="text">
                                                    </div>
                                                </div>
                                                
                                                <table class="ui celled striped table">
                                                    <thead>
                                                        <tr>
                                                            <th>Joint</th>
                                                            <th>Movement</th>
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
                                                            <td>Knee</td>
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
                                                            <td></td>
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
                                                    </tbody>
                                                </table>
                                                
                                                <div class="inline fields">
                                                    <label>b) Sound Side</label>
                                                    <div class="field">
                                                        <input name="soundSide" type="text">
                                                    </div>
                                                </div>
                                                
                                                <table class="ui celled striped table">
                                                    <thead>
                                                        <tr>
                                                            <th>Joint</th>
                                                            <th>Movement</th>
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
                                                            <td>Knee</td>
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
                                                            <td></td>
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
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="ui segments">
                                    <div class="ui secondary segment collapsed" data-toggle="collapse" data-target="#neuro_coordinate">
                                        <i class="angle down icon large"></i>
                                        <i class="angle up icon large"></i>
                                        <h4 style="text-align: center; margin-top: 3px;">COORDINATION</h4>
                                    </div>
                                    <div class="ui segment collapse" id="neuro_coordinate">
                                        <div class="ui grid">
                                            <div class="ten wide column" style="margin: auto;">
                                                <div class="ui form">
                                                    <div class="field">
                                                        <div class="inline fields">
                                                            <label for="fingerTest">Finger Nose Test</label>
                                                            <div class="field">
                                                                <div class="ui radio checkbox">
                                                                    <input type="radio" id="fingerTest_poor" name="fingerTest" value="Poor">
                                                                    <label for="fingerTest_poor">Poor</label>
                                                                </div>
                                                            </div>
                                                            <div class="field">
                                                                <div class="ui radio checkbox">
                                                                    <input type="radio" id="fingerTest_fair" name="fingerTest" value="Fair">
                                                                    <label for="fingerTest_fair">Fair</label>
                                                                </div>
                                                            </div>
                                                            <div class="field">
                                                                <div class="ui radio checkbox">
                                                                    <input type="radio" id="fingerTest_good" name="fingerTest" value="Good">
                                                                    <label for="fingerTest_good">Good</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="field">
                                                        <div class="inline fields">
                                                            <label for="heelTest">Heel Shin Test</label>
                                                            <div class="field">
                                                                <div class="ui radio checkbox">
                                                                    <input type="radio" id="heelTest_poor" name="heelTest" value="Poor">
                                                                    <label for="heelTest_poor">Poor</label>
                                                                </div>
                                                            </div>
                                                            <div class="field">
                                                                <div class="ui radio checkbox">
                                                                    <input type="radio" id="heelTest_fair" name="heelTest" value="Fair">
                                                                    <label for="heelTest_fair">Fair</label>
                                                                </div>
                                                            </div>
                                                            <div class="field">
                                                                <div class="ui radio checkbox">
                                                                    <input type="radio" id="heelTest_good" name="heelTest" value="Good">
                                                                    <label for="heelTest_good">Good</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="ui segments">
                                    <div class="ui secondary segment collapsed" data-toggle="collapse" data-target="#neuro_funcActivity">
                                        <i class="angle down icon large"></i>
                                        <i class="angle up icon large"></i>
                                        <h4 style="text-align: center; margin-top: 3px;">FUNCTIONAL ACTIVITY</h4>
                                    </div>
                                    <div class="ui segment collapse" id="neuro_funcActivity">
                                        <div class="ui grid">
                                            <div class="sixteen wide column">
                                                <table class="ui celled striped table">
                                                    <thead>
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
                                                        <textarea rows="6" cols="50" id="neuroAssessment_impression" name="impression"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="ui segments">
                                    <div class="ui secondary segment collapsed" data-toggle="collapse" data-target="#neuro_summary">
                                        <i class="angle down icon large"></i>
                                        <i class="angle up icon large"></i>
                                        <h4 style="text-align: center; margin-top: 3px;">SUMMARY</h4>
                                    </div>
                                    <div class="ui segment collapse" id="neuro_summary">
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