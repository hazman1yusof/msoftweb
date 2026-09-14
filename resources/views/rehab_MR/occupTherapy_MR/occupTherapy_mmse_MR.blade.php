<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment" style="height: 50px;">
    THE MINI-MENTAL STATE EXAM
        <div class="ui small blue icon buttons" id="btn_grp_edit_mmse" style="position: absolute;
            padding: 0 0 0 0;
            right: 40px;
            top: 9px;
            z-index: 2;">
            <button class="ui button" id="new_mmse"><span class="fa fa-plus-square-o"></span>New</button>
            <button class="ui button" id="edit_mmse"><span class="fa fa-edit fa-lg"></span>Edit</button>
            <button class="ui button" id="save_mmse"><span class="fa fa-save fa-lg"></span>Save</button>
            <button class="ui button" id="cancel_mmse"><span class="fa fa-ban fa-lg"></span>Cancel</button>
            <!-- <button class="ui button" id="mmse_chart"><span class="fa fa-print fa-lg"></span>Print</button> -->
        </div>
    </div>

    <div class="ui segment">
        <div class="ui grid">
            <form id="formOccupTherapyMMSE" class="ui form sixteen wide column">
                <div class="sixteen wide column">
                    <div class="ui grid">
                        <div class='five wide column' style="padding: 3px 3px 3px 3px;">
                            <div class="ui segments">
                                <div class="ui segment">
                                    <div class="ui grid">
                                        <table id="datetime_tbl" class="ui celled table" style="width: 100%;">
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
                                                <label for="dateofexam" style="padding-right: 5px;">Date</label>
                                                <input id="dateofexam" name="dateofexam" type="date" class="form-control input-sm" value="<?php echo date("Y-m-d"); ?>" data-validation="required" data-validation-error-msg-required="Please enter information.">
                                            </div>
                                            <div class="field">
                                                <label for="examiner" style="padding-left: 15px; padding-right: 5px;">Examiner</label>
                                                <input id="examiner" name="examiner" type="text" class="form-control input-sm">
                                            </div>                                          
                                        </div>
                                    </div>
                                    <table class="ui table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Maximum</th>
                                                <th scope="col">Score</th>
                                                <th scope="col" colspan="2"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td scope="row"></td>
                                                <td></td>
                                                <td>
                                                    <b>Orientation</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td scope="row">5</td>
                                                <td>
                                                    <div class="ui form">
                                                        <div class="field">
                                                            <input type="number" name="orientation1">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    What is the (year) (season) (date) (day) (month?)
                                                </td>
                                            </tr>
                                            <tr>
                                                <td scope="row">5</td>
                                                <td>
                                                    <div class="ui form">
                                                        <div class="field">
                                                            <input type="number" name="orientation2">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    Where are we (state) (country) (town) (hospital) (floor)?
                                                </td>
                                            </tr>
                                            <tr>
                                                <td scope="row"></td>
                                                <td></td>
                                                <td>
                                                    <b>Registration</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td scope="row">3</td>
                                                <td>
                                                    <div class="ui form">
                                                        <div class="field">
                                                            <input type="number" name="registration">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    Name 3 objects. 1 second to say each. Then ask the patient all 3 after you have said them. Give 1 point for each correct answer. Then repeat them until he/she learns all 3. Count trials and record.<br>
                                                    <div class="ui form">
                                                        <div class="field">
                                                        Trials: &nbsp; <input type="number" name="registrationTrials" class="form-control input-sm">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td scope="row"></td>
                                                <td></td>
                                                <td>
                                                    <b>Attention and Calculation</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td scope="row">5</td>
                                                <td>
                                                    <div class="ui form">
                                                        <div class="field">
                                                            <input type="number" name="attnCalc">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    Serial 7's. 1 point for each correct answer. Stop after 5 answers. Alternatively spell "world" backward.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td scope="row"></td>
                                                <td></td>
                                                <td>
                                                    <b>Recall</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td scope="row">3</td>
                                                <td>
                                                    <div class="ui form">
                                                        <div class="field">
                                                            <input type="number" name="recall">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    Ask for the 3 objects repeated above. Give 1 point for each correct answer.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td scope="row"></td>
                                                <td></td>
                                                <td>
                                                    <b>Language</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td scope="row">2</td>
                                                <td>
                                                    <div class="ui form">
                                                        <div class="field">
                                                            <input type="number" name="language1">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    Name a pencil and watch.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>
                                                    <div class="ui form">
                                                        <div class="field">
                                                            <input type="number" name="language2">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    Repeat the following "No ifs, ands, or buts"
                                                </td>
                                            </tr>
                                            <tr>
                                                <td scope="row">3</td>
                                                <td>
                                                    <div class="ui form">
                                                        <div class="field">
                                                            <input type="number" name="language3">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    Follow a 3-stage command:<br>
                                                    "Take a paper in your hand, fold it in a half, and put it on the floor."
                                                </td>
                                            </tr>
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>
                                                    <div class="ui form">
                                                        <div class="field">
                                                            <input type="number" name="language4">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    Read and obey the following: CLOSE YOUR EYES
                                                </td>
                                            </tr>
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>
                                                    <div class="ui form">
                                                        <div class="field">
                                                            <input type="number" name="language5">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    Write a sentence.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>
                                                    <div class="ui form">
                                                        <div class="field">
                                                            <input type="number" name="language6">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    Copy the design shown.
                                                    <div class="image">
                                                        <img src="{{ asset('img/mmse.jpg') }}">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td scope="row"></td>
                                                <td>
                                                    <div class="ui form">
                                                        <div class="field">
                                                            <input type="number" name="tot_mmse" rdonly>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <b>Total Score</b><br>
                                                    ASSESS level of consciousness along a continuum (Alert, Drowsy, Stupor, Coma)
                                                    <div class="ui form">
                                                        <div class="field">
                                                            <input type="text" name="assess_lvl" class="form-control input-sm">
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
            </form>
        </div>
    </div>
</div>