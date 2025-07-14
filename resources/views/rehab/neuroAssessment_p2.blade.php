<div class="sixteen wide column">
    <div class="ui segments">
        <div class="ui secondary segment">MUSCLE TONE (MAS)</div>
        <div class="ui segment">
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
                
                <div class="sixteen wide column">
                    <div class="inline fields">
                        <label>Impression</label>
                        <div class="field">
                            <textarea rows="6" cols="50" id="neuroAssessment_impressionMAS" name="impressionMAS"></textarea>
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
                                <textarea rows="6" cols="50" id="neuroAssessment_impressionDTR" name="impressionDTR"></textarea>
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
                                <input type="radio" name="affectedSide" value="R" id="affectedSideR">
                                <label for="affectedSideR">R</label>
                            </div>
                        </div>
                        <div class="field">
                            <div class="ui radio checkbox">
                                <input type="radio" name="affectedSide" value="L" id="affectedSideL">
                                <label for="affectedSideL">L</label>
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
                        <label for="soundSide">b) Sound Side</label>
                        <div class="field">
                            <div class="ui radio checkbox">
                                <input type="radio" name="soundSide" value="R" id="soundSideR">
                                <label for="soundSideR">R</label>
                            </div>
                        </div>
                        <div class="field">
                            <div class="ui radio checkbox">
                                <input type="radio" name="soundSide" value="L" id="soundSideL">
                                <label for="soundSideL">L</label>
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
                    
                    <div class="sixteen wide column">
                        <div class="inline fields">
                            <label>Impression</label>
                            <div class="field">
                                <textarea rows="6" cols="50" id="neuroAssessment_impressionSMP" name="impressionSMP"></textarea>
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
                                                <input type="radio" id="fingerTestR_poor" name="fingerTestR" value="Poor">
                                                <label for="fingerTestR_poor">Poor</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" id="fingerTestR_fair" name="fingerTestR" value="Fair">
                                                <label for="fingerTestR_fair">Fair</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" id="fingerTestR_good" name="fingerTestR" value="Good">
                                                <label for="fingerTestR_good">Good</label>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="inline fields">
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" id="fingerTestL_poor" name="fingerTestL" value="Poor">
                                                <label for="fingerTestL_poor">Poor</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" id="fingerTestL_fair" name="fingerTestL" value="Fair">
                                                <label for="fingerTestL_fair">Fair</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" id="fingerTestL_good" name="fingerTestL" value="Good">
                                                <label for="fingerTestL_good">Good</label>
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
                                                <input type="radio" id="heelTestR_poor" name="heelTestR" value="Poor">
                                                <label for="heelTestR_poor">Poor</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" id="heelTestR_fair" name="heelTestR" value="Fair">
                                                <label for="heelTestR_fair">Fair</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" id="heelTestR_good" name="heelTestR" value="Good">
                                                <label for="heelTestR_good">Good</label>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="inline fields">
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" id="heelTestL_poor" name="heelTestL" value="Poor">
                                                <label for="heelTestL_poor">Poor</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" id="heelTestL_fair" name="heelTestL" value="Fair">
                                                <label for="heelTestL_fair">Fair</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" id="heelTestL_good" name="heelTestL" value="Good">
                                                <label for="heelTestL_good">Good</label>
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
                            <textarea rows="6" cols="50" id="neuroAssessment_impressionCoord" name="impressionCoord"></textarea>
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
                            <textarea rows="6" cols="50" id="neuroAssessment_impressionFA" name="impressionFA"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>