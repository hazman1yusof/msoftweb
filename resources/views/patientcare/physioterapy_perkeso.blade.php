<div class="ui grid">
    <div class="sixteen wide column">
        <div class="ui grid">
            <input id="phy_type" name="type" value="perkeso" type="hidden">
            
            <div class="sixteen wide column">
                <button class="btn btn-default btn-sm" type="button" id="perkeso_chart" style="float: right; margin-right: 40px; margin-bottom: 10px;">Print</button>
                
                <table class="ui celled table">
                    <tbody>
                        <tr>
                            <td><b>Diagnosis</b></td>
                            <td>
                                <textarea rows="4" cols="50" name="diagnosis"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>BACKGROUND INFORMATION:</b></td>
                            <!-- <td></td> -->
                        </tr>
                        <tr>
                            <td>Current source of income:<br><i>gaji/family/jabatan kebajikan/bantuan baitulmal/saving.</i></td>
                            <td>
                                <textarea rows="4" cols="50" name="incomeSource"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>Total Dependents:</td>
                            <td>
                                <input type="text" class="form-control" name="totDependents">
                            </td>
                        </tr>
                        <tr>
                            <td>Educational level:</td>
                            <td>
                                <input type="text" class="form-control" name="eduLevel">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>MEDICAL & REHABILITATION INFORMATION</b></td>
                            <!-- <td></td> -->
                        </tr>
                        <tr>
                            <td>Date of TCA<i>(date/type of tca/location)</i>:</td>
                            <td>
                                <!-- <input type="date" class="form-control" name="dateTCA">
                                <input type="text" class="form-control" name="typeTCA" style="margin-top: 10px;"> -->
                                <textarea rows="4" cols="50" name="TCA"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>Date of MC (if any):</td>
                            <td>
                                <!-- <input type="date" class="form-control" name="dateMC"> -->
                                <textarea rows="4" cols="50" name="MC"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>EMPLOYMENT HISTORY</b></td>
                            <!-- <td></td> -->
                        </tr>
                        <tr>
                            <td>Current employment Status:</td>
                            <td>
                                <input type="text" class="form-control" name="employmentStat">
                            </td>
                        </tr>
                        <tr>
                            <td>Current work information:</td>
                            <td>
                                <input type="text" class="form-control" name="workInfo">
                            </td>
                        </tr>
                        <tr>
                            <td>Employment History:</td>
                            <td>
                                <textarea rows="4" cols="50" name="employmentHist"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>Community mobility:<br><i>How does OB mobilize in the community</i></td>
                            <td>
                                <textarea rows="4" cols="50" name="communityMobility"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>RTW PLANNING</b></td>
                            <!-- <td></td> -->
                        </tr>
                        <tr>
                            <td>View & Perception About work:<br><i>Yes/no/undecided</i></td>
                            <td>
                                <input type="text" class="form-control" name="workView">
                            </td>
                        </tr>
                        <tr>
                            <td>Working Industry:<br>(same job same employer/same job different employer/Different Job different employer/etc)</td>
                            <td>
                                <input type="text" class="form-control" name="workIndustry">
                            </td>
                        </tr>
                        <tr>
                            <td><b>OB'S MOTIVATION LEVEL DURING IA:</b></td>
                            <td>
                                <textarea rows="4" cols="50" name="OBmotivation"></textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="sixteen wide column">
                <div class="ui segment">
                    <div class="inline fields">
                        <label>SUBJECTIVE AX</label>
                        <div class="field"><textarea rows="4" cols="50" name="subjective"></textarea></div>
                    </div>
                </div>
            </div>
            
            <div class="sixteen wide column">
                <div class="ui segments">
                    <div class="ui secondary segment">PATIENT COMPLAINT</div>
                    <div class="ui segment">
                        <div class="ui grid">
                            <div class="sixteen wide column">
                                <table class="ui celled table">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="inline fields">
                                                    <label>Initial Date</label>
                                                    <div class="field">
                                                        <input name="initialDate" type="date">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="inline fields">
                                                    <label>Progress Date</label>
                                                    <div class="field">
                                                        <input name="progressDate" type="date">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="inline fields">
                                                    <label>Final Date</label>
                                                    <div class="field">
                                                        <input name="finalDate" type="date">
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <textarea rows="4" cols="50" name="initialComplaint"></textarea>
                                            </td>
                                            <td>
                                                <textarea rows="4" cols="50" name="progressComplaint"></textarea>
                                            </td>
                                            <td>
                                                <textarea rows="4" cols="50" name="finalComplaint"></textarea>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="sixteen wide column">
                <div class="ui segment">
                    <div class="inline fields">
                        <label>PATIENT EXPECTATION</label>
                        <div class="field"><textarea rows="4" cols="50" name="patExpectation"></textarea></div>
                    </div>
                    
                    <div class="inline fields">
                        <label>FAMILY EXPECTATION</label>
                        <div class="field"><textarea rows="4" cols="50" name="familyExpectation"></textarea></div>
                    </div>
                </div>
            </div>
            
            <div class="sixteen wide column">
                <div class="ui segment">
                    <div class="inline fields">
                        <label>OBJECTIVE AX</label>
                        <div class="field"><textarea rows="4" cols="50" name="objective"></textarea></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="sixteen wide column">
        <div class="ui segments">
            <div class="ui secondary segment">BODY CHART</div>
            <div class="ui segment">
                <div class="ui grid">
                    <div class="ten wide column">
                        <div class="ui two cards">
                            <a class="ui card bodydia_perkeso" data-type='BB_PERKESO'>
                                <div class="image">
                                    <img src="{{ asset('patientcare/img/bodydiagperkeso4.png') }}">
                                </div>
                            </a>
                            <a class="ui card bodydia_perkeso" data-type='BF_PERKESO'>
                                <div class="image">
                                    <img src="{{ asset('patientcare/img/bodydiagperkeso1.png') }}">
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
                                            <input type="radio" id="painDeep" name="painLocation" value="Deep">
                                            <label for="painDeep">Deep</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox">
                                            <input type="radio" id="painSuperficial" name="painLocation" value="Superficial">
                                            <label for="painSuperficial">Superficial</label>
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
                            <textarea rows="6" cols="50" id="perkeso_impressionBC" name="impressionBC"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="sixteen wide column">
        <div class="ui segments">
            <div class="ui secondary segment">SENSATION</div>
            <div class="ui segment">
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
                        
                        <div class="inline fields">
                            <label>Impression</label>
                            <div class="field">
                                <textarea rows="6" cols="50" id="perkeso_impressionSens" name="impressionSens"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
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
                                    <input type="radio" name="romAffectedSide" value="R" id="perkeso_romAffectedSideR">
                                    <label for="perkeso_romAffectedSideR">R</label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="ui radio checkbox">
                                    <input type="radio" name="romAffectedSide" value="L" id="perkeso_romAffectedSideL">
                                    <label for="perkeso_romAffectedSideL">L</label>
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
                                    <input type="radio" name="romSoundSide" value="R" id="perkeso_romSoundSideR">
                                    <label for="perkeso_romSoundSideR">R</label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="ui radio checkbox">
                                    <input type="radio" name="romSoundSide" value="L" id="perkeso_romSoundSideL">
                                    <label for="perkeso_romSoundSideL">L</label>
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
                                <textarea rows="6" cols="50" id="perkeso_impressionROM" name="impressionROM"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="sixteen wide column">
        <div class="ui segments">
            <div class="ui secondary segment">MUSCLE TONE (MAS)</div>
            <div class="ui segment">
                <div class="ui grid">
                    <div class="twelve wide column" style="margin: auto;">
                        <div class="ui form">
                            <div class="field">
                                <div class="inline fields">
                                    <label for="muscleUL">UL</label>
                                    <div class="field">
                                        <!-- <input type="text" name="muscleUL"> -->
                                        <textarea rows="4" cols="50" name="muscleUL"></textarea>
                                    </div>
                                <!-- </div>
                            </div>
                            <div class="field">
                                <div class="inline fields"> -->
                                    <label for="muscleLL">LL</label>
                                    <div class="field">
                                        <!-- <input type="text" name="muscleLL"> -->
                                        <textarea rows="4" cols="50" name="muscleLL"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="sixteen wide column">
                        <div class="inline fields">
                            <label>Impression</label>
                            <div class="field">
                                <textarea rows="4" cols="50" id="perkeso_impressionMAS" name="impressionMAS"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="sixteen wide column">
        <div class="ui segments">
            <div class="ui secondary segment">DEEP TENDON REFLEX</div>
            <div class="ui segment">
                <div class="ui grid">
                    <div class="sixteen wide column">
                        <table class="ui striped table">
                            <thead>
                                <tr>
                                    <th width="30%"></th>
                                    <th>RT</th>
                                    <th>LT</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>BTR</td>
                                    <td>
                                        <input type="checkbox" name="btrRT" value="1">
                                    </td>
                                    <td>
                                        <input type="checkbox" name="btrLT" value="1">
                                    </td>
                                </tr>
                                <tr>
                                    <td>TTR</td>
                                    <td>
                                        <input type="checkbox" name="ttrRT" value="1">
                                    </td>
                                    <td>
                                        <input type="checkbox" name="ttrLT" value="1">
                                    </td>
                                </tr>
                                <tr>
                                    <td>KTR</td>
                                    <td>
                                        <input type="checkbox" name="ktrRT" value="1">
                                    </td>
                                    <td>
                                        <input type="checkbox" name="ktrLT" value="1">
                                    </td>
                                </tr>
                                <tr>
                                    <td>ATR</td>
                                    <td>
                                        <input type="checkbox" name="atrRT" value="1">
                                    </td>
                                    <td>
                                        <input type="checkbox" name="atrLT" value="1">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Babinsky</td>
                                    <td>
                                        <input type="checkbox" name="babinskyRT" value="1">
                                    </td>
                                    <td>
                                        <input type="checkbox" name="babinskyLT" value="1">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <div class="sixteen wide column">
                            <div class="inline fields">
                                <label>Impression</label>
                                <div class="field">
                                    <textarea rows="6" cols="50" id="perkeso_impressionDTR" name="impressionDTR"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="sixteen wide column">
        <div class="ui segments">
            <div class="ui secondary segment">MUSCLE POWER</div>
            <div class="ui segment">
                <div class="ui grid">
                    <div class="sixteen wide column">
                        <div class="inline fields">
                            <label for="affectedSide">a) Affected Side</label>
                            <div class="field">
                                <div class="ui radio checkbox">
                                    <input type="radio" name="affectedSide" value="R" id="perkeso_affectedSideR">
                                    <label for="perkeso_affectedSideR">R</label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="ui radio checkbox">
                                    <input type="radio" name="affectedSide" value="L" id="perkeso_affectedSideL">
                                    <label for="perkeso_affectedSideL">L</label>
                                </div>
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
                            <label for="soundSide">b) Sound Side</label>
                            <div class="field">
                                <div class="ui radio checkbox">
                                    <input type="radio" name="soundSide" value="R" id="perkeso_soundSideR">
                                    <label for="perkeso_soundSideR">R</label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="ui radio checkbox">
                                    <input type="radio" name="soundSide" value="L" id="perkeso_soundSideL">
                                    <label for="perkeso_soundSideL">L</label>
                                </div>
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
                        
                        <div class="sixteen wide column">
                            <div class="inline fields">
                                <label>Impression</label>
                                <div class="field">
                                    <textarea rows="6" cols="50" id="perkeso_impressionSMP" name="impressionSMP"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="sixteen wide column">
        <div class="ui segments">
            <div class="ui secondary segment">COORDINATION</div>
            <div class="ui segment">
                <div class="ui grid">
                    <div class="sixteen wide column">
                        <table class="ui celled striped table">
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">R</th>
                                    <th scope="col">L</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Finger Nose Test</td>
                                    <td>
                                        <div class="inline fields">
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" id="perkeso_fingerTestRPoor" name="fingerTestR" value="Poor">
                                                    <label for="perkeso_fingerTestRPoor">Poor</label>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" id="perkeso_fingerTestRFair" name="fingerTestR" value="Fair">
                                                    <label for="perkeso_fingerTestRFair">Fair</label>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" id="perkeso_fingerTestRGood" name="fingerTestR" value="Good">
                                                    <label for="perkeso_fingerTestRGood">Good</label>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="inline fields">
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" id="perkeso_fingerTestLPoor" name="fingerTestL" value="Poor">
                                                    <label for="perkeso_fingerTestLPoor">Poor</label>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" id="perkeso_fingerTestLFair" name="fingerTestL" value="Fair">
                                                    <label for="perkeso_fingerTestLFair">Fair</label>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" id="perkeso_fingerTestLGood" name="fingerTestL" value="Good">
                                                    <label for="perkeso_fingerTestLGood">Good</label>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Heel Shin Test</td>
                                    <td>
                                        <div class="inline fields">
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" id="perkeso_heelTestRPoor" name="heelTestR" value="Poor">
                                                    <label for="perkeso_heelTestRPoor">Poor</label>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" id="perkeso_heelTestRFair" name="heelTestR" value="Fair">
                                                    <label for="perkeso_heelTestRFair">Fair</label>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" id="perkeso_heelTestRGood" name="heelTestR" value="Good">
                                                    <label for="perkeso_heelTestRGood">Good</label>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="inline fields">
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" id="perkeso_heelTestLPoor" name="heelTestL" value="Poor">
                                                    <label for="perkeso_heelTestLPoor">Poor</label>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" id="perkeso_heelTestLFair" name="heelTestL" value="Fair">
                                                    <label for="perkeso_heelTestLFair">Fair</label>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" id="perkeso_heelTestLGood" name="heelTestL" value="Good">
                                                    <label for="perkeso_heelTestLGood">Good</label>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <div class="inline fields">
                            <label>Impression</label>
                            <div class="field">
                                <textarea rows="6" cols="50" id="perkeso_impressionCoord" name="impressionCoord"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="sixteen wide column">
        <div class="ui segments">
            <div class="ui secondary segment">FUNCTIONAL ACTIVITY</div>
            <div class="ui segment">
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
                                <textarea rows="6" cols="50" id="perkeso_impressionFA" name="impressionFA"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="sixteen wide column">
        <div class="ui segments">
            <div class="ui secondary segment">SPECIAL TEST</div>
            <div class="ui segment">
                <div class="ui grid">
                    <div class="sixteen wide column">
                        <table class="ui celled striped table">
                            <thead>
                                <tr>
                                    <th scope="col">TEST</th>
                                    <th scope="col">INITIAL</th>
                                    <th scope="col">PROGRESS</th>
                                    <th scope="col">FINAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Barthel Index</td>
                                    <td>
                                        <input type="text" class="form-control" name="barthelIndexInit">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="barthelIndexProg">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="barthelIndexFin">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Berg Balance</td>
                                    <td>
                                        <input type="text" class="form-control" name="bergBalanceInit">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="bergBalanceProg">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="bergBalanceFin">
                                    </td>
                                </tr>
                                <tr>
                                    <td>6 Min Walk Test</td>
                                    <td>
                                        <input type="text" class="form-control" name="sixMinWalkInit">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="sixMinWalkProg">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="sixMinWalkFin">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <div class="inline fields">
                            <label>Impression</label>
                            <div class="field">
                                <textarea rows="6" cols="50" id="perkeso_impressionST" name="impressionST"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="sixteen wide column">
        <div class="ui segments">
            <div class="ui secondary segment">TREATMENT</div>
            <div class="ui segment">
                <div class="ui grid">
                    <div class="sixteen wide column">
                        <table class="ui celled striped table">
                            <thead>
                                <tr>
                                    <!-- <th scope="col">NO</th> -->
                                    <th scope="col">FINDING</th>
                                    <th scope="col">INTERVENTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <textarea rows="4" cols="50" name="finding"></textarea>
                                    </td>
                                    <td>
                                        <textarea rows="4" cols="50" name="intervention"></textarea>
                                    </td>
                                </tr>
                                <!-- <tr>
                                    <th scope="row">1</th>
                                    <td>
                                        <input type="text" class="form-control" name="finding1">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="intervention1">
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">2</th>
                                    <td>
                                        <input type="text" class="form-control" name="finding2">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="intervention2">
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">3</th>
                                    <td>
                                        <input type="text" class="form-control" name="finding3">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="intervention3">
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">4</th>
                                    <td>
                                        <input type="text" class="form-control" name="finding4">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="intervention4">
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">5</th>
                                    <td>
                                        <input type="text" class="form-control" name="finding5">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="intervention5">
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">6</th>
                                    <td>
                                        <input type="text" class="form-control" name="finding6">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="intervention6">
                                    </td>
                                </tr> -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="sixteen wide column">
        <div class="ui segments">
            <div class="ui secondary segment">SUMMARY OF CASE PROGRESS</div>
            <div class="ui segment">
                <div class="ui grid">
                    <div class="sixteen wide column">
                        <p>i. Rehabilitation plans/goals.</p>
                        <table class="ui celled striped table">
                            <thead>
                                <tr>
                                    <th scope="col">INITIAL</th>
                                    <th scope="col">PROGRESS</th>
                                    <th scope="col">FINAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <textarea rows="4" cols="50" name="rehabPlansInit"></textarea>
                                    </td>
                                    <td>
                                        <textarea rows="4" cols="50" name="rehabPlansProg"></textarea>
                                    </td>
                                    <td>
                                        <textarea rows="4" cols="50" name="rehabPlansFin"></textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <p>ii. Limitations.</p>
                        <table class="ui celled striped table">
                            <thead>
                                <tr>
                                    <th scope="col">INITIAL</th>
                                    <th scope="col">PROGRESS</th>
                                    <th scope="col">FINAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <textarea rows="4" cols="50" name="limitInit"></textarea>
                                    </td>
                                    <td>
                                        <textarea rows="4" cols="50" name="limitProg"></textarea>
                                    </td>
                                    <td>
                                        <textarea rows="4" cols="50" name="limitFin"></textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <p>iii. Improvements.</p>
                        <table class="ui celled striped table">
                            <thead>
                                <tr>
                                    <th scope="col">INITIAL</th>
                                    <th scope="col">PROGRESS</th>
                                    <th scope="col">FINAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <textarea rows="4" cols="50" name="improvementInit"></textarea>
                                    </td>
                                    <td>
                                        <textarea rows="4" cols="50" name="improvementProg"></textarea>
                                    </td>
                                    <td>
                                        <textarea rows="4" cols="50" name="improvementFin"></textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <p>iv. Recommendations.</p>
                        <table class="ui celled striped table">
                            <thead>
                                <tr>
                                    <th scope="col">INITIAL</th>
                                    <th scope="col">PROGRESS</th>
                                    <th scope="col">FINAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <textarea rows="4" cols="50" name="recommendInit"></textarea>
                                    </td>
                                    <td>
                                        <textarea rows="4" cols="50" name="recommendProg"></textarea>
                                    </td>
                                    <td>
                                        <textarea rows="4" cols="50" name="recommendFin"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="inline fields">
                                            <label>Therapist name</label>
                                            <div class="field">
                                                <input name="therapistNameInit1" type="text">
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="inline fields">
                                            <label>Therapist name</label>
                                            <div class="field">
                                                <input name="therapistNameProg1" type="text">
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="inline fields">
                                            <label>Therapist name</label>
                                            <div class="field">
                                                <input name="therapistNameFin1" type="text">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="inline fields">
                                            <label>Therapist name</label>
                                            <div class="field">
                                                <input name="therapistNameInit2" type="text">
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="inline fields">
                                            <label>Therapist name</label>
                                            <div class="field">
                                                <input name="therapistNameProg2" type="text">
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="inline fields">
                                            <label>Therapist name</label>
                                            <div class="field">
                                                <input name="therapistNameFin2" type="text">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="sixteen wide column">
        <div class="ui segment">
            <div class="field"><textarea rows="6" cols="50" name="summaryInitRmk"></textarea></div>
            
            <div class="inline fields">
                <label>SUMMARY INITIAL & PROGRESS ASSESSMENT</label>
                <div class="field" style="padding-left: 50px;"><input name="summaryInitial" type="text"></div>
            </div>
        </div>
    </div>
    
    <div class="sixteen wide column">
        <div class="ui segment">
            <div class="field"><textarea rows="6" cols="50" name="summaryFinalRmk"></textarea></div>
            
            <div class="inline fields">
                <label>SUMMARY FINAL ASSESSMENT</label>
                <div class="field" style="padding-left: 50px;"><input name="summaryFinal" type="text"></div>
            </div>
        </div>
    </div>
</div>