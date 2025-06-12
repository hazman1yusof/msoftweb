<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment" style="height: 50px;">
        <div class="ui small blue icon buttons" id="btn_grp_edit_bergBalanceTest" style="position: absolute;
            padding: 0 0 0 0;
            right: 40px;
            top: 9px;
            z-index: 2;">
            <button class="ui button" id="new_bergBalanceTest"><span class="fa fa-plus-square-o"></span>New</button>
            <button class="ui button" id="edit_bergBalanceTest"><span class="fa fa-edit fa-lg"></span>Edit</button>
            <button class="ui button" id="save_bergBalanceTest"><span class="fa fa-save fa-lg"></span>Save</button>
            <button class="ui button" id="cancel_bergBalanceTest"><span class="fa fa-ban fa-lg"></span>Cancel</button>
            <!-- <button class="ui button" id="bergBalanceTest_chart"><span class="fa fa-print fa-lg"></span>Print</button> -->
        </div>
    </div>
    <div class="ui segment">
        <div class="ui grid">
            <form id="formBergBalanceTest" class="floated ui form sixteen wide column">
                <input id="idno_bergBalanceTest" name="idno_bergBalanceTest" type="hidden">
                <div class="ui grid">
                    <div class='four wide column'>
                        <div class="ui segments">
                            <div class="ui segment">
                                <div class="ui grid">
                                    <table id="tbl_bergBalanceTest_date" class="ui celled table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th class="scope">idno</th>
                                                <th class="scope">mrn</th>
                                                <th class="scope">episno</th>
                                                <th class="scope">Date</th>
                                                <th class="scope">Entered By</th>
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
                                <input id="bergBalanceTest_entereddate" name="entereddate" type="date">
                            </div>
                        </div>
                        
                        <div class="ui grid">
                            <div class='eight wide column' style="padding-right: 0px;">
                                <table class="ui striped table">
                                    <tbody>
                                        <tr height="300px">
                                            <th scope="row">1</th>
                                            <td>
                                                SITTING TO STANDING<br>
                                                <u>INSTRUCTIONS:</u> Please stand up. Try not to use your hands for support.
                                                
                                                <div class="inline fields" style="padding-top: 5px;">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="sitToStand" value="4" id="sitToStand4" class="calc_bergBalanceTest">
                                                            <label for="sitToStand4">(4) Able to stand without using hands and stabilize independently.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="sitToStand" value="3" id="sitToStand3" class="calc_bergBalanceTest">
                                                            <label for="sitToStand3">(3) Able to stand independently using hands.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="sitToStand" value="2" id="sitToStand2" class="calc_bergBalanceTest">
                                                            <label for="sitToStand2">(2) Able to stand using hands after several tries.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="sitToStand" value="1" id="sitToStand1" class="calc_bergBalanceTest">
                                                            <label for="sitToStand1">(1) Needs minimal aid to stand or to stabilize.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="sitToStand" value="0" id="sitToStand0" class="calc_bergBalanceTest">
                                                            <label for="sitToStand0">(0) Needs moderate or maximal assist to stand.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr height="340px">
                                            <th scope="row">2</th>
                                            <td>
                                                STANDING UNSUPPORTED<br>
                                                <u>INSTRUCTIONS:</u> Please stand for two minutes without holding.
                                                
                                                <div class="inline fields" style="padding-top: 5px;">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="standUnsupported" value="4" id="standUnsupported4" class="calc_bergBalanceTest">
                                                            <label for="standUnsupported4">(4) Able to stand safely two minutes.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="standUnsupported" value="3" id="standUnsupported3" class="calc_bergBalanceTest">
                                                            <label for="standUnsupported3">(3) Able to stand 2 minutes with supervision.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="standUnsupported" value="2" id="standUnsupported2" class="calc_bergBalanceTest">
                                                            <label for="standUnsupported2">(2) Able to stand 30 seconds unsupported.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="standUnsupported" value="1" id="standUnsupported1" class="calc_bergBalanceTest">
                                                            <label for="standUnsupported1">(1) Needs several tries to stand 30 seconds unsupported.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="standUnsupported" value="0" id="standUnsupported0" class="calc_bergBalanceTest">
                                                            <label for="standUnsupported0">(0) Unable to stand 30 seconds unassisted.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr height="290px">
                                            <th scope="row">3</th>
                                            <td>
                                                SITTING WITH BACK UNSUPPORTED BUT FEET SUPPORTED ON FLOOR OR ON A STOOL<br>
                                                <u>INSTRUCTIONS:</u> Please sit with arms folded for 2 minutes.
                                                
                                                <div class="inline fields" style="padding-top: 5px;">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="sitBackUnsupported" value="4" id="sitBackUnsupported4" class="calc_bergBalanceTest">
                                                            <label for="sitBackUnsupported4">(4) Able to sit safely and securely for 2 minutes.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="sitBackUnsupported" value="3" id="sitBackUnsupported3" class="calc_bergBalanceTest">
                                                            <label for="sitBackUnsupported3">(3) Able to sit 2 minutes under supervision.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="sitBackUnsupported" value="2" id="sitBackUnsupported2" class="calc_bergBalanceTest">
                                                            <label for="sitBackUnsupported2">(2) Able to sit 30 seconds.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="sitBackUnsupported" value="1" id="sitBackUnsupported1" class="calc_bergBalanceTest">
                                                            <label for="sitBackUnsupported1">(1) Able to sit 10 seconds.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="sitBackUnsupported" value="0" id="sitBackUnsupported0" class="calc_bergBalanceTest">
                                                            <label for="sitBackUnsupported0">(0) Unable to sit without support 10 seconds.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr height="270px">
                                            <th scope="row">4</th>
                                            <td>
                                                STANDING TO SITTING<br>
                                                <u>INSTRUCTIONS:</u> Please sit down.
                                                
                                                <div class="inline fields" style="padding-top: 5px;">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="standToSit" value="4" id="standToSit4" class="calc_bergBalanceTest">
                                                            <label for="standToSit4">(4) Sits safely with minimal use of hands.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="standToSit" value="3" id="standToSit3" class="calc_bergBalanceTest">
                                                            <label for="standToSit3">(3) Controls descent by using hands.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="standToSit" value="2" id="standToSit2" class="calc_bergBalanceTest">
                                                            <label for="standToSit2">(2) Uses back of legs against chair to control descent.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="standToSit" value="1" id="standToSit1" class="calc_bergBalanceTest">
                                                            <label for="standToSit1">(1) Sits independently but has uncontrolled descent.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="standToSit" value="0" id="standToSit0" class="calc_bergBalanceTest">
                                                            <label for="standToSit0">(0) Needs assistance to sit.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr height="330px">
                                            <th scope="row">5</th>
                                            <td>
                                                TRANSFERS<br>
                                                <u>INSTRUCTIONS:</u> Arrange chair(s) for a pivot transfer. Ask subject to transfer one way toward a seat without armrests. You may use two chairs (one with and one without armrests) or a bed and a chair.
                                                
                                                <div class="inline fields" style="padding-top: 5px;">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="transfer" value="4" id="transfer4" class="calc_bergBalanceTest">
                                                            <label for="transfer4">(4) Able to transfer safely with minor use of hands.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="transfer" value="3" id="transfer3" class="calc_bergBalanceTest">
                                                            <label for="transfer3">(3) Able to transfer safely definite need of hands.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="transfer" value="2" id="transfer2" class="calc_bergBalanceTest">
                                                            <label for="transfer2">(2) Able to transfer with verbal cueing and/or supervision.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="transfer" value="1" id="transfer1" class="calc_bergBalanceTest">
                                                            <label for="transfer1">(1) Needs one person to assist.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="transfer" value="0" id="transfer0" class="calc_bergBalanceTest">
                                                            <label for="transfer0">(0) Needs two people to assist or supervise to be safe.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr height="290px">
                                            <th scope="row">6</th>
                                            <td>
                                                STANDING UNSUPPORTED WITH EYES CLOSED<br>
                                                <u>INSTRUCTIONS:</u> Please close your eyes and stand still for 10 seconds.
                                                
                                                <div class="inline fields" style="padding-top: 5px;">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="standEyesClosed" value="4" id="standEyesClosed4" class="calc_bergBalanceTest">
                                                            <label for="standEyesClosed4">(4) Able to stand 10 seconds safely.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="standEyesClosed" value="3" id="standEyesClosed3" class="calc_bergBalanceTest">
                                                            <label for="standEyesClosed3">(3) Able to stand 10 seconds with supervision.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="standEyesClosed" value="2" id="standEyesClosed2" class="calc_bergBalanceTest">
                                                            <label for="standEyesClosed2">(2) Able to stand 3 seconds.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="standEyesClosed" value="1" id="standEyesClosed1" class="calc_bergBalanceTest">
                                                            <label for="standEyesClosed1">(1) Unable to keep eyes closed 3 seconds but stays steady.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="standEyesClosed" value="0" id="standEyesClosed0" class="calc_bergBalanceTest">
                                                            <label for="standEyesClosed0">(0) Needs help to keep from falling.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr height="340px">
                                            <th scope="row">7</th>
                                            <td>
                                                STANDING UNSUPPORTED WITH FEET TOGETHER<br>
                                                <u>INSTRUCTIONS:</u> Place your feet close together and stand without holding.
                                                
                                                <div class="inline fields" style="padding-top: 5px;">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="standFeetTogether" value="4" id="standFeetTogether4" class="calc_bergBalanceTest">
                                                            <label for="standFeetTogether4">(4) Able to place feet together independently and stand 1 minute safely.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="standFeetTogether" value="3" id="standFeetTogether3" class="calc_bergBalanceTest">
                                                            <label for="standFeetTogether3">(3) Able to place feet together independently and stand for 1 minute with supervision.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="standFeetTogether" value="2" id="standFeetTogether2" class="calc_bergBalanceTest">
                                                            <label for="standFeetTogether2">(2) Able to place feet together independently but unable to hold for 30 seconds.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="standFeetTogether" value="1" id="standFeetTogether1" class="calc_bergBalanceTest">
                                                            <label for="standFeetTogether1">(1) Needs help to attain position but able to stand 15 seconds feet together.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="standFeetTogether" value="0" id="standFeetTogether0" class="calc_bergBalanceTest">
                                                            <label for="standFeetTogether0">(0) Needs help to attain position and unable to hold for 15 seconds.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class='eight wide column' style="padding-left: 0px;">
                                <table class="ui striped table">
                                    <tbody>
                                        <tr height="300px">
                                            <th scope="row">8</th>
                                            <td>
                                                REACHING FORWARD WITH OUTSTRETCHED ARM WHILE STANDING<br>
                                                <u>INSTRUCTIONS:</u> Lift arm to 90°. Stretch out your fingers and reach forward as far as you can.
                                                
                                                <div class="inline fields" style="padding-top: 5px;">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="reachForward" value="4" id="reachForward4" class="calc_bergBalanceTest">
                                                            <label for="reachForward4">(4) Can reach forward confidently > 25 cm.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="reachForward" value="3" id="reachForward3" class="calc_bergBalanceTest">
                                                            <label for="reachForward3">(3) Can reach forward > 12 cm.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="reachForward" value="2" id="reachForward2" class="calc_bergBalanceTest">
                                                            <label for="reachForward2">(2) Can reach forward > 5cm.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="reachForward" value="1" id="reachForward1" class="calc_bergBalanceTest">
                                                            <label for="reachForward1">(1) Reaches forward but needs supervision.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="reachForward" value="0" id="reachForward0" class="calc_bergBalanceTest">
                                                            <label for="reachForward0">(0) Loses balance while trying/requires external support.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr height="340px">
                                            <th scope="row">9</th>
                                            <td>
                                                PICK UP OBJECT FROM THE FLOOR FROM A STANDING POSITION<br>
                                                <u>INSTRUCTIONS:</u> Pick up the shoe/slipper which is placed in front of your feet.
                                                
                                                <div class="inline fields" style="padding-top: 5px;">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="pickUpObject" value="4" id="pickUpObject4" class="calc_bergBalanceTest">
                                                            <label for="pickUpObject4">(4) Able to pick up slipper safely and easily.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="pickUpObject" value="3" id="pickUpObject3" class="calc_bergBalanceTest">
                                                            <label for="pickUpObject3">(3) Able to pick up slipper but needs supervision.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="pickUpObject" value="2" id="pickUpObject2" class="calc_bergBalanceTest">
                                                            <label for="pickUpObject2">(2) Unable to pick up but reaches 2-5 cm from slipper and keeps balance independently.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="pickUpObject" value="1" id="pickUpObject1" class="calc_bergBalanceTest">
                                                            <label for="pickUpObject1">(1) Unable to pick up and needs supervision while trying.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="pickUpObject" value="0" id="pickUpObject0" class="calc_bergBalanceTest">
                                                            <label for="pickUpObject0">(0) Unable to try/needs assist to keep from losing balance or falling.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr height="290px">
                                            <th scope="row">10</th>
                                            <td>
                                                TURNING TO LOOK BEHIND OVER LEFT AND RIGHT SHOULDERS WHILE STANDING<br>
                                                <u>INSTRUCTIONS:</u> Turn to look directly behind you over toward left shoulder. Repeat to the right.
                                                
                                                <div class="inline fields" style="padding-top: 5px;">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="turnToLookBehind" value="4" id="turnToLookBehind4" class="calc_bergBalanceTest">
                                                            <label for="turnToLookBehind4">(4) Look behind from both sides & weight shifts well.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="turnToLookBehind" value="3" id="turnToLookBehind3" class="calc_bergBalanceTest">
                                                            <label for="turnToLookBehind3">(3) Looks behind 1 side, other side shows less shift.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="turnToLookBehind" value="2" id="turnToLookBehind2" class="calc_bergBalanceTest">
                                                            <label for="turnToLookBehind2">(2) Turns sideways only, but maintains balance.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="turnToLookBehind" value="1" id="turnToLookBehind1" class="calc_bergBalanceTest">
                                                            <label for="turnToLookBehind1">(1) Needs supervision when turning.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="turnToLookBehind" value="0" id="turnToLookBehind0" class="calc_bergBalanceTest">
                                                            <label for="turnToLookBehind0">(0) Needs assist to keep from losing balance/falling.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr height="270px">
                                            <th scope="row">11</th>
                                            <td>
                                                TURN 360°<br>
                                                <u>INSTRUCTIONS:</u> Turn completely around in a full circle, pause, then turn a full circle in the other direction.
                                                
                                                <div class="inline fields" style="padding-top: 5px;">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="turn360" value="4" id="turn360_4" class="calc_bergBalanceTest">
                                                            <label for="turn360_4">(4) Able to turn 360° safely in 4 seconds or less.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="turn360" value="3" id="turn360_3" class="calc_bergBalanceTest">
                                                            <label for="turn360_3">(3) Able to turn 360° safely one side in 4 sec or less.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="turn360" value="2" id="turn360_2" class="calc_bergBalanceTest">
                                                            <label for="turn360_2">(2) Able to turn 360° safely but slowly.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="turn360" value="1" id="turn360_1" class="calc_bergBalanceTest">
                                                            <label for="turn360_1">(1) Needs close supervision or verbal cueing.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="turn360" value="0" id="turn360_0" class="calc_bergBalanceTest">
                                                            <label for="turn360_0">(0) Needs assistance while turning.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr height="330px">
                                            <th scope="row">12</th>
                                            <td>
                                                PLACING ALTERNATE FOOT ON STEP OR STOOL WHILE STANDING UNSUPPORTED<br>
                                                <u>INSTRUCTIONS:</u> Place each foot alternately on the step stool. Continue until each foot has touched the step stool four times.
                                                
                                                <div class="inline fields" style="padding-top: 5px;">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="placeFootOnStep" value="4" id="placeFootOnStep4" class="calc_bergBalanceTest">
                                                            <label for="placeFootOnStep4">(4) Able to stand alone & safely do 8 steps in 20 sec.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="placeFootOnStep" value="3" id="placeFootOnStep3" class="calc_bergBalanceTest">
                                                            <label for="placeFootOnStep3">(3) Able to stand alone & do 8 steps > 20 sec.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="placeFootOnStep" value="2" id="placeFootOnStep2" class="calc_bergBalanceTest">
                                                            <label for="placeFootOnStep2">(2) Able to do 4 steps without aid, with supervision.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="placeFootOnStep" value="1" id="placeFootOnStep1" class="calc_bergBalanceTest">
                                                            <label for="placeFootOnStep1">(1) Able to do > 2 steps, need minimal assist.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="placeFootOnStep" value="0" id="placeFootOnStep0" class="calc_bergBalanceTest">
                                                            <label for="placeFootOnStep0">(0) Needs assistance to keep from falling/unable to try.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr height="290px">
                                            <th scope="row">13</th>
                                            <td>
                                                STANDING UNSUPPORTED ONE FOOT IN FRONT<br>
                                                <u>INSTRUCTIONS:</u> Place one foot directly in front of the other or place foot somewhat in front of the other.
                                                
                                                <div class="inline fields" style="padding-top: 5px;">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="oneFootInFront" value="4" id="oneFootInFront4" class="calc_bergBalanceTest">
                                                            <label for="oneFootInFront4">(4) Able to place foot tandem alone and hold 30 sec.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="oneFootInFront" value="3" id="oneFootInFront3" class="calc_bergBalanceTest">
                                                            <label for="oneFootInFront3">(3) Able to place foot ahead of other & hold 30 sec.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="oneFootInFront" value="2" id="oneFootInFront2" class="calc_bergBalanceTest">
                                                            <label for="oneFootInFront2">(2) Able to take small step alone and hold 30 sec.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="oneFootInFront" value="1" id="oneFootInFront1" class="calc_bergBalanceTest">
                                                            <label for="oneFootInFront1">(1) Needs help to step but can hold 15 sec.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="oneFootInFront" value="0" id="oneFootInFront0" class="calc_bergBalanceTest">
                                                            <label for="oneFootInFront0">(0) Loses balance while stepping or standing.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr height="340px">
                                            <th scope="row">14</th>
                                            <td>
                                                STANDING ON ONE LEG<br>
                                                <u>INSTRUCTIONS:</u> Stand on one leg as long as you can without holding.
                                                
                                                <div class="inline fields" style="padding-top: 5px;">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="standOneLeg" value="4" id="standOneLeg4" class="calc_bergBalanceTest">
                                                            <label for="standOneLeg4">(4) Able to lift leg alone and hold > 10 sec.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="standOneLeg" value="3" id="standOneLeg3" class="calc_bergBalanceTest">
                                                            <label for="standOneLeg3">(3) Able to lift leg alone and hold 5-10 sec.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="standOneLeg" value="2" id="standOneLeg2" class="calc_bergBalanceTest">
                                                            <label for="standOneLeg2">(2) Able to lift leg alone and hold = or > 3 sec.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="standOneLeg" value="1" id="standOneLeg1" class="calc_bergBalanceTest">
                                                            <label for="standOneLeg1">(1) Tried to lift leg, unable to hold 3 sec. But remains standing alone.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="standOneLeg" value="0" id="standOneLeg0" class="calc_bergBalanceTest">
                                                            <label for="standOneLeg0">(0) Unable to try or needs assist to prevent fall.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="inline fields" style="padding-top: 15px;">
                            <label>TOTAL SCORE</label>
                            <div class="field">
                                <input id="bergBalanceTest_totalScore" name="totalScore" type="text" rdonly>
                            </div>
                            <label>(56 MAXIMUM)</label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>