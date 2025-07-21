<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment" style="height: 50px;">
        <div class="ui small blue icon buttons" id="btn_grp_edit_sixMinWalking" style="position: absolute;
            padding: 0 0 0 0;
            right: 40px;
            top: 9px;
            z-index: 2;">
            <button class="ui button" id="new_sixMinWalking"><span class="fa fa-plus-square-o"></span>New</button>
            <button class="ui button" id="edit_sixMinWalking"><span class="fa fa-edit fa-lg"></span>Edit</button>
            <button class="ui button" id="save_sixMinWalking"><span class="fa fa-save fa-lg"></span>Save</button>
            <button class="ui button" id="cancel_sixMinWalking"><span class="fa fa-ban fa-lg"></span>Cancel</button>
            <!-- <button class="ui button" id="sixMinWalking_chart"><span class="fa fa-print fa-lg"></span>Print</button> -->
        </div>
    </div>
    <div class="ui segment">
        <div class="ui grid">
            <form id="formSixMinWalking" class="floated ui form sixteen wide column">
                <input id="idno_sixMinWalking" name="idno_sixMinWalking" type="hidden">
                <div class="ui grid">
                    <div class='four wide column'>
                        <div class="ui segments">
                            <div class="ui segment">
                                <div class="ui grid">
                                    <table id="tbl_sixMinWalking_date" class="ui celled table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th class="scope">idno</th>
                                                <th class="scope">mrn</th>
                                                <th class="scope">episno</th>
                                                <th class="scope">Date</th>
                                                <th class="scope">dt</th>
                                                <th class="scope">Entered By</th>
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
                                    <input id="sixMinWalking_entereddate" name="entereddate" type="date">
                                </div>
                            </div> -->
                            
                            <table class="ui table">
                                <tbody>
                                    <tr>
                                        <td>The following elements should be present on the 6MWT worksheet and report:</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="inline fields">
                                                <label>Lap counter:</label>
                                                <div class="field" style="padding-right: 25px;">
                                                    <input type="text" class="form-control" name="lapCounter" style="width: 300px;">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr style="display: none;">
                                        <td>
                                            <div class="inline fields">
                                                <label>Patient name:</label>
                                                <div class="field">
                                                    <input type="text" class="form-control" id="sixMinWalking_patName" name="patName" style="width: 350px;" rdonly>
                                                </div>
                                                
                                                <!-- <label style="margin-left: 40px;">Patient ID#</label>
                                                <div class="field">
                                                    <input type="text" class="form-control" name="patID">
                                                </div> -->
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="inline fields">
                                                <!-- <label>Walk #</label>
                                                <div class="field">
                                                    <input type="text" class="form-control" name="walk">
                                                </div>
                                                
                                                <label style="margin-left: 25px;">Tech ID:</label>
                                                <div class="field">
                                                    <input type="text" class="form-control" name="techID">
                                                </div> -->
                                                
                                                <label>Date:</label>
                                                <div class="field">
                                                    <input type="date" class="form-control" id="sixMinWalking_entereddate" name="entereddate">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="inline fields">
                                                <!-- <label>Gender:</label>
                                                <div class="field">
                                                    <div class="ui radio checkbox">
                                                        <input type="radio" id="genderM" name="gender" value="M">
                                                        <label for="genderM">M</label>
                                                    </div>
                                                </div>
                                                <div class="field">
                                                    <div class="ui radio checkbox">
                                                        <input type="radio" id="genderF" name="gender" value="F">
                                                        <label for="genderF">F</label>
                                                    </div>
                                                </div>
                                                
                                                <label>Age:</label>
                                                <div class="field">
                                                    <input type="number" class="form-control" id="sixMinWalking_age" name="age" style="width: 90px;" rdonly>
                                                </div>
                                                
                                                <label>Race:</label>
                                                <div class="field">
                                                    <input type="text" class="form-control" id="sixMinWalking_race" name="race" style="width: 140px;" rdonly>
                                                </div> -->
                                                
                                                <label>Height:</label>
                                                <div class="field">
                                                    <div class="ui right labeled input">
                                                        <input type="number" class="form-control" name="heightCM" style="height: 38px;">
                                                        <div class="ui basic label">cm</div>
                                                    </div>
                                                </div>
                                                <div class="field" style="display: none;">
                                                    <input type="number" class="form-control" name="heightFT" style="width: 90px; margin-right: 0px;"> ft
                                                    <input type="number" class="form-control" name="heightIN" style="width: 90px;"> in,
                                                    <input type="number" class="form-control" name="heightMETERS" style="width: 90px;"> meters
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="inline fields">
                                                <label>Weight:</label>
                                                <div class="field">
                                                    <!-- <input type="number" class="form-control" name="weightLBS" style="width: 90px; margin-right: 0px;"> lbs,
                                                    <input type="number" class="form-control" name="weightKG" style="width: 90px;"> kg -->
                                                    <div class="ui right labeled input">
                                                        <input type="number" class="form-control" name="weightKG" style="height: 38px;">
                                                        <div class="ui basic label">kg</div>
                                                    </div>
                                                </div>
                                                
                                                <label style="margin-left: 40px;">Blood pressure:</label>
                                                <div class="field">
                                                    <!-- <input type="number" class="form-control" name="bpsys1" style="width: 90px; margin-right: 0px;"> /
                                                    <input type="number" class="form-control" name="bpdias2" style="width: 90px;"> -->
                                                    <div class="ui right labeled input">
                                                        <input type="number" class="form-control" name="bpsys1" style="height: 38px;">
                                                        <input type="number" class="form-control" name="bpdias2" style="height: 38px;">
                                                        <div class="ui basic label">mmHg</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr style="display: none;">
                                        <td>
                                            <div class="inline fields">
                                                <label>Medications taken before the test (dose and time):</label>
                                                <div class="field">
                                                    <input type="text" class="form-control" name="medsDose">
                                                    <input type="time" class="form-control" name="medsTime">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr style="display: none;">
                                        <td>
                                            <div class="inline fields">
                                                <label>Supplemental oxygen during the test:</label>
                                                <div class="field">
                                                    <div class="ui radio checkbox">
                                                        <input type="radio" id="suppOxygenNo" name="suppOxygen" value="0">
                                                        <label for="suppOxygenNo">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="inline fields" style="margin-left: 228px;">
                                                <div class="field">
                                                    <div class="ui radio checkbox">
                                                        <input type="radio" id="suppOxygenYes" name="suppOxygen" value="1">
                                                        <label for="suppOxygenYes">Yes,</label>
                                                    </div>
                                                </div>
                                                
                                                <div class="field">flow
                                                    <input type="text" class="form-control" name="oxygenFlow"> L/min, type
                                                    <input type="text" class="form-control" name="oxygenType">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 5px 50px;">
                                            <table class="ui table">
                                                <tbody>
                                                    <tr>
                                                        <td></td>
                                                        <td>Baseline</td>
                                                        <td>End of Test</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Time</td>
                                                        <td><input type="time" class="form-control" name="baselineTime" style="width: 185px;"></td>
                                                        <td><input type="time" class="form-control" name="endTestTime" style="width: 185px;"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Heart Rate</td>
                                                        <td><input type="number" class="form-control" name="baselineHR" style="width: 185px;"></td>
                                                        <td><input type="number" class="form-control" name="endTestHR" style="width: 185px;"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Borg Scale</td>
                                                        <td><input type="number" class="form-control" name="baselineBorgScale" style="width: 185px;"></td>
                                                        <td><input type="number" class="form-control" name="endTestBorgScale" style="width: 185px;"></td>
                                                    </tr>
                                                    <tr style="display: none;">
                                                        <td>Dyspnea</td>
                                                        <td><input type="text" class="form-control" name="baselineDyspnea" style="width: 185px;"></td>
                                                        <td>
                                                            <div class="inline fields">
                                                                <div class="field">
                                                                    <div class="ui right labeled input">
                                                                        <input type="text" class="form-control" name="endTestDyspnea" style="height: 38px;">
                                                                        <div class="ui basic label">(Borg Scale)</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr style="display: none;">
                                                        <td>Fatigue</td>
                                                        <td><input type="text" class="form-control" name="baselineFatigue" style="width: 185px;"></td>
                                                        <td>
                                                            <div class="inline fields">
                                                                <div class="field">
                                                                    <div class="ui right labeled input">
                                                                        <input type="text" class="form-control" name="endTestFatigue" style="height: 38px;">
                                                                        <div class="ui basic label">(Borg Scale)</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>SpO2</td>
                                                        <td>
                                                            <div class="inline fields">
                                                                <div class="field">
                                                                    <div class="ui right labeled input">
                                                                        <input type="number" class="form-control" name="baselineSpO2" style="height: 38px;">
                                                                        <div class="ui basic label">%</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="inline fields">
                                                                <div class="field">
                                                                    <div class="ui right labeled input">
                                                                        <input type="number" class="form-control" name="endTestSpO2" style="height: 38px;">
                                                                        <div class="ui basic label">%</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="inline fields">
                                                <label>Stopped or paused before 6 minutes?:</label>
                                                <div class="field">
                                                    <div class="ui radio checkbox">
                                                        <input type="radio" id="stopPausedNo" name="stopPaused" value="0">
                                                        <label for="stopPausedNo">No</label>
                                                    </div>
                                                </div>
                                                <div class="field">
                                                    <div class="ui radio checkbox">
                                                        <input type="radio" id="stopPausedYes" name="stopPaused" value="1">
                                                        <label for="stopPausedYes">Yes,</label>
                                                    </div>
                                                </div>
                                                
                                                <div class="field">reason: 
                                                    <input type="text" class="form-control" name="reason">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="inline fields">
                                                <label>Other symptoms at end of exercise:</label>
                                                <div class="field">
                                                    <div class="ui radio checkbox">
                                                        <input type="radio" id="othSymptomsAngina" name="othSymptoms" value="angina">
                                                        <label for="othSymptomsAngina">angina</label>
                                                    </div>
                                                </div>
                                                <div class="field">
                                                    <div class="ui radio checkbox">
                                                        <input type="radio" id="othSymptomsDizziness" name="othSymptoms" value="dizziness">
                                                        <label for="othSymptomsDizziness">dizziness</label>
                                                    </div>
                                                </div>
                                                <div class="field">
                                                    <div class="ui radio checkbox">
                                                        <input type="radio" id="othSymptomsHip" name="othSymptoms" value="hipLegCalfPain">
                                                        <label for="othSymptomsHip">hip, leg, or calf pain</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr style="display: none;">
                                        <td>
                                            <div class="inline fields">
                                                <label>Number of laps:</label>
                                                <div class="field">
                                                    <input type="number" class="form-control" name="lapsNo" style="width: 130px;"> (X60 meters) + final partial lap:
                                                    <input type="number" class="form-control" name="partialLaps" style="width: 130px;"> meters =
                                                    <input type="number" class="form-control" name="lapsTot" style="width: 130px;">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="inline fields">
                                                <label>Total distance walked in 6 minutes:</label>
                                                <div class="field">
                                                    <div class="ui right labeled input">
                                                        <input type="number" class="form-control" name="totDistance" style="height: 38px;">
                                                        <div class="ui basic label">meters</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr style="display: none;">
                                        <td>
                                            <div class="inline fields">
                                                <label>Predicted distance:</label>
                                                <div class="field">
                                                    <div class="ui right labeled input">
                                                        <input type="number" class="form-control" name="predictDistance" style="height: 38px;">
                                                        <div class="ui basic label">meters</div>
                                                    </div>
                                                </div>
                                                
                                                <label style="margin-left: 50px;">Percent predicted:</label>
                                                <div class="field">
                                                    <div class="ui right labeled input">
                                                        <input type="number" class="form-control" name="percentPredicted" style="height: 38px;">
                                                        <div class="ui basic label">%</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>Tech comments:</label>
                                            <div class="inline fields">
                                                <!-- <label>Tech comments:</label> -->
                                                <div class="field">
                                                    Interpretation (including comparison with a preintervention 6MWD):
                                                    <textarea rows="6" id="sixMinWalking_comments" name="comments"></textarea>
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