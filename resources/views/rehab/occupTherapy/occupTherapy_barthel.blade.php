<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment" style="height: 50px;">
        MODIFIED BARTHEL INDEX (SHAH VERSION): SELF CARE ASSESSMENT
        <div class="ui small blue icon buttons" id="btn_grp_edit_barthel" style="position: absolute;
            padding: 0 0 0 0;
            right: 40px;
            top: 9px;
            z-index: 2;">
            <button class="ui button" id="new_barthel"><span class="fa fa-plus-square-o"></span>New</button>
            <button class="ui button" id="edit_barthel"><span class="fa fa-edit fa-lg"></span>Edit</button>
            <button class="ui button" id="save_barthel"><span class="fa fa-save fa-lg"></span>Save</button>
            <button class="ui button" id="cancel_barthel"><span class="fa fa-ban fa-lg"></span>Cancel</button>
            <!-- <button class="ui button" id="barthel_chart"><span class="fa fa-print fa-lg"></span>Print</button> -->
        </div>
    </div>
    <div class="ui segment">
        <div class="ui grid">
            <form id="formOccupTherapyBarthel" class="floated ui form sixteen wide column">
            <input id="idno_barthel" name="idno_barthel" type="hidden">

                <div class="sixteen wide column">
                    <div class="ui grid">
                        <div class='five wide column' style="padding: 3px 3px 3px 3px;">
                            <div class="ui segments">
                                <div class="ui segment">
                                    <div class="ui grid">
                                        <table id="datetimeBarthel_tbl" class="ui celled table" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th class="scope">idno</th>
                                                    <th class="scope">mrn</th>
                                                    <th class="scope">episno</th>
                                                    <th class="scope">Date</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class='eleven wide column' style="padding: 3px 3px 3px 3px;">
                            <div class="ui segment">
                                <div class='ui grid' style="padding: 5px 3px 3px 2px;">
                                    <div class="sixteen wide column" style="padding: 10px 0px 0px 3px;">
                                        <div class="inline fields">
                                            <div class="field">
                                                <label for="dateAssessment" style="padding-right: 5px;">Date</label>
                                                <input id="dateAssessment" name="dateAssessment" type="date" class="form-control input-sm" value="<?php echo date("Y-m-d"); ?>" data-validation="required" data-validation-error-msg-required="Please enter information.">
                                            </div>
                                            <!-- <div class="field">
                                                <label for="timeAssessment" style="padding-left: 15px; padding-right: 5px;">Time</label>
                                                <input id="timeAssessment" name="timeAssessment" type="time" class="form-control input-sm">
                                            </div> -->
                                        </div>
                                    </div>

                                    <table class="table table-bordered small">
                                        <thead>
                                            <tr>
                                                <th scope="col" style="background-color:#dddddd">INDEX ITEM</th>
                                                <th scope="col" style="background-color:#dddddd">SCORE</th>
                                                <th scope="col" colspan="2" style="background-color:#dddddd">DESCRIPTION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- CHAIR/BED TRANSFERS -->
                                            <tr>
                                                <td rowspan="6" class="align-middle"><b>CHAIR/BED TRANSFERS</b></td>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="chairBedTrf" value="0" class="score">0
                                                    </label>
                                                </td>
                                                <td>
                                                    Unable to participate in a transfer. Two attendants are required to transfer the patient with or without a mechanical device.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="chairBedTrf" value="3" class="score">3
                                                    </label>
                                                </td>
                                                <td>
                                                    Able to participate but maximum assistance of one other person is require in all <u>aspects</u> of the transfer.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="chairBedTrf" value="8" class="score">8
                                                    </label>
                                                </td>
                                                <td>
                                                    The transfer requires the assistance of one other person. Assistance may be required <u>in any</u> aspect of the transfer.                                                
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="chairBedTrf" value="12" class="score">12
                                                    </label>
                                                </td>
                                                <td>
                                                    The presence of another person is required either as a confidence measure, or to provide supervision for safety.                                                
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="chairBedTrf" value="15" class="score">15
                                                    </label>
                                                </td>
                                                <td>
                                                    The patient can safely approach the bed walking or in a wheelchair, lock brakes, lift footrests, or position walking aid, move safely to bed, lie down, come to a sitting position on the side of the bed, change the position of the wheelchair, transfer back into it safely and/or grasp aid and stand. The patient must be independent in all phases of this activity.                                                
                                                </td>
                                            </tr><tr></tr>

                                            <!-- AMBULATION -->
                                            <tr>
                                                <td rowspan="6" class="align-middle"><b>AMBULATION</b></td>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="ambulation" value="0" class="score">0
                                                    </label>
                                                </td>
                                                <td>
                                                    Dependent in ambulation.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="ambulation" value="3" class="score">3
                                                    </label>
                                                </td>
                                                <td>
                                                    Constant presence of one or more assistant is required during ambulation.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="ambulation" value="8" class="score">8
                                                    </label>
                                                </td>
                                                <td>
                                                    Assistance is required with reaching aids and/or their manipulation. One person is required to offer assistance.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="ambulation" value="12" class="score">12
                                                    </label>
                                                </td>
                                                <td>
                                                    The patient is independent in ambulation but unable to walk 50 metres without help, or supervision is needed for confidence or safety in hazardous situations.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="ambulation" value="15" class="score">15
                                                    </label>
                                                </td>
                                                <td>
                                                    The patient must be able to wear braces if required, lock and unlock these braces assume standing position, sit down, and place the necessary aids into position for use. The patient must be able to crutches, canes, or a walkarette, and walk 50 metres without help or supervision.
                                                </td>
                                            </tr><tr></tr>
                                           
                                            <!-- AMBULATION/WHEELCHAIR -->
                                            <tr>
                                                <td rowspan="6" class="align-middle"><b>AMBULATION/WHEELCHAIR<br><br><i>*(If unable to walk)<br><br>
                                                Only use thís item if the patient is rated "0" for Ambulation, and then only if the patient has been trained in wheelchair management.</i>
                                                </b></td>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="ambulationWheelchair" value="0" class="score">0
                                                    </label>
                                                </td>
                                                <td>
                                                    Dependent in wheelchair ambulation.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="ambulationWheelchair" value="1" class="score">1
                                                    </label>
                                                </td>
                                                <td>
                                                    Patient can propel self short distances on flat surface, but assistance is required for all other steps of wheelchair management.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="ambulationWheelchair" value="3" class="score">3
                                                    </label>
                                                </td>
                                                <td>
                                                    Presence of one person is necessary and constant assistance is required to manipulate chair to table, bed, etc.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="ambulationWheelchair" value="4" class="score">4
                                                    </label>
                                                </td>
                                                <td>
                                                    The patient can propel self for a reasonable duration over regularly encountered terrain. Minimal assistance may still be required in "tight corners" or to negotiate a kerb 100mm high.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="ambulationWheelchair" value="5" class="score">5
                                                    </label>
                                                </td>
                                                <td>
                                                    To propel wheclchair independently, the patient must be able to go around comers, turn around, manoeuvre the chair to a table, bed, toilet, etc. The patient must be able to push a chair at least 50 metres and negotiate a kerb.
                                                </td>
                                            </tr><tr></tr>

                                            <!-- STAIR CLIMBING -->
                                            <tr>
                                                <td rowspan="6" class="align-middle"><b>STAIR CLIMBING</b></td>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="stairClimbing" value="0" class="score">0
                                                    </label>
                                                </td>
                                                <td>
                                                    The patient is unable to climb stairs.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="stairClimbing" value="2" class="score">2
                                                    </label>
                                                </td>
                                                <td>
                                                    Assistance is required in all aspects of chair climbing, including assistance with walking aids.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="stairClimbing" value="5" class="score">5
                                                    </label>
                                                </td>
                                                <td>
                                                    The patient is able to ascend/descend but is unable to carry walking aids and needs supervision and assistance.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="stairClimbing" value="8" class="score">8
                                                    </label>
                                                </td>
                                                <td>
                                                    Generally no assistance is required. At times supervision is required for safety due to morning stiffness, shortness of breath, etc.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="stairClimbing" value="10" class="score">10
                                                    </label>
                                                </td>
                                                <td>
                                                    The patient is able to go up and down a flight of stairs safely without help or supervision. The patient is able to use hand rails, cane or crutches when needed and is able to carry these devices as he/she ascends or descends.
                                                </td>
                                            </tr><tr></tr>

                                            <!-- TOILET TRANSFERS -->
                                            <tr>
                                                <td rowspan="6" class="align-middle"><b>TOILET TRANSFERS</b></td>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="toiletTrf" value="0" class="score">0
                                                    </label>
                                                </td>
                                                <td>
                                                    Fully dependent in toileting.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="toiletTrf" value="2" class="score">2
                                                    </label>
                                                </td>
                                                <td>
                                                    Assistance required in all aspects of toileting.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="toiletTrf" value="5" class="score">5
                                                    </label>
                                                </td>
                                                <td>
                                                    Assistance may be required with management of clothing, transferring, or washing hands.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="toiletTrf" value="8" class="score">8
                                                    </label>
                                                </td>
                                                <td>
                                                    Supervision may be required for safety with normal toilet. A commode may be used at night but assistance is required for emptying and cleaning.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="toiletTrf" value="10" class="score">10
                                                    </label>
                                                </td>
                                                <td>
                                                    The patient is able to get on/off the toilet, fasten clothing and use toilet paper without help. If necessary, the patient may use a bed pan or commode or urinal at night, but must be able to empty it and clean it.
                                                </td>
                                            </tr><tr></tr>

                                            <!-- BOWEL CONTROL -->
                                            <tr>
                                                <td rowspan="6" class="align-middle"><b>BOWEL CONTROL</b></td>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="bowelControl" value="0" class="score">0
                                                    </label>
                                                </td>
                                                <td>
                                                    The patient is bowel incontinent.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="bowelControl" value="2" class="score">2
                                                    </label>
                                                </td>
                                                <td>
                                                    The patient needs help to assume appropriate position, and with bowel movement facilitatory techniques.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="bowelControl" value="5" class="score">5
                                                    </label>
                                                </td>
                                                <td>
                                                    The patient can assume appropriate position, but cannot use facilitatory techniques or clean self without assistance and has frequent accidents. Assistance is required with incontinence aids such as pad, etc.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="bowelControl" value="8" class="score">8
                                                    </label>
                                                </td>
                                                <td>
                                                    The patient may require supervision with the use of suppository or enema and has occasional accidents.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="bowelControl" value="10" class="score">10
                                                    </label>
                                                </td>
                                                <td>
                                                    The patient can control bowels and has no accidents, can use suppository, or take an enema when necessary.
                                                </td>
                                            </tr><tr></tr>

                                            <!-- BLADDER CONTROL -->
                                            <tr>
                                                <td rowspan="6" class="align-middle"><b>BLADDER CONTROL</b></td>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="bladderControl" value="0" class="score">0
                                                    </label>
                                                </td>
                                                <td>
                                                    The patient is dependent in bladder management, is incontinent, or has indwelling catheter.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="bladderControl" value="2" class="score">2
                                                    </label>
                                                </td>
                                                <td>
                                                    The patient is incontinent but is able to assist with the application of an intermal or external device.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="bladderControl" value="5" class="score">5
                                                    </label>
                                                </td>
                                                <td>
                                                    The patient is generally dry by day, but not at night and needs some assistance with the devices.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="bladderControl" value="8" class="score">8
                                                    </label>
                                                </td>
                                                <td>
                                                    The patient is generally dry by day and night, but may have an occasional accident or need minimal assistance with internal or external devices.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="bladderControl" value="10" class="score">10
                                                    </label>
                                                </td>
                                                <td>
                                                    The patient is able to control bladder day and night, and/or is independent with internal or external devices.
                                                </td>
                                            </tr><tr></tr>

                                            <!-- BATHING -->
                                            <tr>
                                                <td rowspan="6" class="align-middle"><b>BATHING</b></td>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="bathing" value="0" class="score">0
                                                    </label>
                                                </td>
                                                <td>
                                                    Total dependence in bathing self.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="bathing" value="1" class="score">1
                                                    </label>
                                                </td>
                                                <td>
                                                    Assistance is required in all aspects of bathing, but patient is able to make some contribution.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="bathing" value="3" class="score">3
                                                    </label>
                                                </td>
                                                <td>
                                                    Assistance is required with either transfer to shower/bath or with washing or drying; including inability to complete a task because of condition or disease, etc.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="bathing" value="4" class="score">4
                                                    </label>
                                                </td>
                                                <td>
                                                    Supervision is required for safety in adjusting the water temperature, or in the transfer.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="bathing" value="5" class="score">5
                                                    </label>
                                                </td>
                                                <td>
                                                    The patient may use a bathtub, a shower, or take a complete sponge bath. The patient must be able to do all the steps of whichever method is employed without another person being present.
                                                </td>
                                            </tr><tr></tr>

                                            <!-- DRESSING -->
                                            <tr>
                                                <td rowspan="6" class="align-middle"><b>DRESSING</b></td>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="dressing" value="0" class="score">0
                                                    </label>
                                                </td>
                                                <td>
                                                    The patient is dependent in all aspects of dressing and is unable to participate in the activity.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="dressing" value="2" class="score">2
                                                    </label>
                                                </td>
                                                <td>
                                                    The patient is able to participate to some degree, but is dependent in all aspects of dressing.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="dressing" value="5" class="score">5
                                                    </label>
                                                </td>
                                                <td>
                                                    Assistance is needed in putting on, and/or removing any clothing.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="dressing" value="8" class="score">8
                                                    </label>
                                                </td>
                                                <td>
                                                    Only minimal assistance is required with fastening clothing such as buttons, zips, bra, shoes, etc.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="dressing" value="10" class="score">10
                                                    </label>
                                                </td>
                                                <td>
                                                    The patient is able to put on, remove, corset, braces, as prescribed.
                                                </td>
                                            </tr><tr></tr>

                                            <!-- PERSONAL HYGIENE -->
                                            <tr>
                                                <td rowspan="6" class="align-middle"><b>PERSONAL HYGIENE<br><br><i>(Grooming)</i></b></td>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="personalHygiene" value="0" class="score">0
                                                    </label>
                                                </td>
                                                <td>
                                                    The patient is unable to attend to personal hygiene and is dependent in all aspects.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="personalHygiene" value="1" class="score">1
                                                    </label>
                                                </td>
                                                <td>
                                                    Assistance is required in all steps of personal hygiene, but patient able to make some contribution.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="personalHygiene" value="3" class="score">3
                                                    </label>
                                                </td>
                                                <td>
                                                    Some assistance is required in one or more steps of personal hygiene.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="personalHygiene" value="4" class="score">4
                                                    </label>
                                                </td>
                                                <td>
                                                    Patient is able to conduct his/her own personal bygiene but requires minimal assistance before and/or after the operation.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="personalHygiene" value="5" class="score">5
                                                    </label>
                                                </td>
                                                <td>
                                                    The patient can wash his/her hands and face, comb hair, clean teeth and shave. A male patient may use any kind of razor but must insert the blade, or plug in the razor without help, as well as retrieve it from the drawer or cabinet. A female patient must apply her own make-up, if used, but need not braid or style her hair.
                                                </td>
                                            </tr><tr></tr>

                                            <!-- FEEDING -->
                                            <tr>
                                                <td rowspan="6" class="align-middle"><b>FEEDING</b></td>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="feeding" value="0" class="score">0
                                                    </label>
                                                </td>
                                                <td>
                                                    Dependent in all aspects and needs to be fed, nasogastric needs to be administered.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="feeding" value="2" class="score">2
                                                    </label>
                                                </td>
                                                <td>
                                                    Can manipulate an eating device, usually a spoon, but someone must provide active assistance during the meal.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="feeding" value="5" class="score">5
                                                    </label>
                                                </td>
                                                <td>
                                                    Able to feed self with supervision. Assistance is required with associated tasks such as putting milk/sugar into tea, salt, pepper, spreading butter, turning a plate or other "set up" activities.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="feeding" value="8" class="score">8
                                                    </label>
                                                </td>
                                                <td>
                                                    Independence in feeding with prepared tray, except may need meat cut, milk carton opened or jar lid etc. The presence of another person is not required.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="feeding" value="10" class="score">10
                                                    </label>
                                                </td>
                                                <td>
                                                    The patient can feed self trom a tray or table when someone puts the food within reach. The patient must put on an assistive device if needed, cut food, and if desired use salt and pepper, spread butter, etc.
                                                </td>
                                            </tr><tr></tr>

                                            <tr>
                                                <td class="align-middle"><b>TOTAL SCORE</b></td>
                                                <td colspan="2">
                                                    <div class="ui form">
                                                        <div class="field">
                                                            <input type="number" name="tot_score" id="tot_score" rdonly>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="align-middle"><b>INTERPRETATION</b></td>
                                                <td colspan="2">
                                                    <div class="ui form">
                                                        <div class="field">
                                                            <input type="text" name="interpretation" id="interpretation" rdonly>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="align-middle"><b>PREDICTION</b></td>
                                                <td colspan="2">
                                                    <div class="ui form">
                                                        <div class="field">
                                                            <textarea name="prediction" id="prediction" rows="4" cols="50" readonly=""></textarea>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                           
                                        </tbody>
                                    </table>

                                    <table class="table table-bordered small">
                                        <thead>
                                            <tr>
                                                <th scope="col" style="background-color:#dddddd">SCORE</th>
                                                <th scope="col" style="background-color:#dddddd">INTERPRETATION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>00-20</td>
                                                <td>Total Dependence</td>
                                            </tr>
                                            <tr>
                                                <td>21-60</td>
                                                <td>Severe Dependence</td>
                                            </tr>
                                            <tr>
                                                <td>61-90</td>
                                                <td>Moderate Dependence</td>
                                            </tr>
                                            <tr>
                                                <td>91-99</td>
                                                <td>Slight Dependence</td>
                                            </tr>
                                            <tr>
                                                <td>100</td>
                                                <td>Independence</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <table class="table table-bordered small">
                                        <thead>
                                            <tr>
                                                <th scope="col" style="background-color:#dddddd">SCORE</th>
                                                <th scope="col" style="background-color:#dddddd">PREDICTION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Less Than 40</td>
                                                <td>Unlikely to go home<br>- Dependent in Mobility<br>- Dependent in Self Care</td>
                                            </tr>
                                            <tr>
                                                <td>60</td>
                                                <td>Pivotal score where patients move from dependency to assisted independence.</td>
                                            </tr>
                                            <tr>
                                                <td>60-80</td>
                                                <td>If living alone will probably need a number of community services to cope.</td>
                                            </tr>
                                            <tr>
                                                <td>More Than 85</td>
                                                <td>Likely to be discharged to community living<br>- Independent in transfers and able to walk or use wheelchair independently.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>