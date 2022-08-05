
<div class="panel panel-default" style="position: relative;" id="jqGridPaediatric_c">
	
	<div class="panel-heading clearfix collapsed position" id="toggle_paediatric" style="position: sticky;top: 0px;z-index: 3;">
		<b>NAME: <span id="name_show_paediatric"></span></b><br>
		MRN: <span id="mrn_show_paediatric"></span>
		SEX: <span id="sex_show_paediatric"></span>
		DOB: <span id="dob_show_paediatric"></span>
		AGE: <span id="age_show_paediatric"></span>
		RACE: <span id="race_show_paediatric"></span>
		RELIGION: <span id="religion_show_paediatric"></span><br>
		OCCUPATION: <span id="occupation_show_paediatric"></span>
		CITIZENSHIP: <span id="citizenship_show_paediatric"></span>
		AREA: <span id="area_show_paediatric"></span>

		<i class="arrow fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#jqGridPaediatric_panel"></i>
		<i class="arrow fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#jqGridPaediatric_panel"></i>
		<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 310px; top: 25px;">
			<h5>Paediatric</h5>
		</div>	

		<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
			id="btn_grp_edit_paediatric"
			style="position: absolute;
					padding: 0 0 0 0;
					right: 40px;
					top: 25px;" 

		>
		<button type="button" class="btn btn-default" id="new_paediatric">
			<span class="fa fa-plus-square-o"></span> New
		</button>
		<button type="button" class="btn btn-default" id="edit_paediatric">
			<span class="fa fa-edit fa-lg"></span> Edit
		</button>
		<button type="button" class="btn btn-default" data-oper='add' id="save_paediatric">
			<span class="fa fa-save fa-lg"></span> Save
		</button>
		<button type="button" class="btn btn-default" id="cancel_paediatric">
			<span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
		</button>
	</div>			
	</div>
	<div id="jqGridPaediatric_panel" class="panel-collapse collapse">
		<div class="panel-body">
			<div class='col-md-12' style="padding:0 0 15px 0">

				<form class='form-horizontal' style='width:99%' id='formPaediatric'>

                    <div class='col-md-12'>
                        <div class='col-md-6'>
                            <div class="panel panel-info">
                                <div class="panel-heading text-center">PARTICULARS OF MOTHER</div>
                                <div class="panel-body" style="height:387px">

                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label class="control-label" for="mothersname" style="padding-bottom:5px">Name</label>
                                            <input name="mothersname" type="text" class="form-control input-sm">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-2">
                                            <label class="control-label" for="mothersage" style="padding-bottom:5px">Age</label>
                                            <input name="mothersage" type="number" class="form-control input-sm">
                                        </div>

                                        <div class="col-md-2">
                                        </div>

                                        <div class="col-md-8">
                                            <label class="control-label" for="mothersnricpassport" style="padding-bottom:5px">NRIC / Passport No.</label>
                                            <input name="mothersnricpassport" type="text" class="form-control input-sm">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label class="control-label" for="address" style="padding-bottom:5px">Address</label>
                                            <textarea id="address" name="address" type="text" class="form-control input-sm"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <label class="control-label" for="telhome" style="padding-bottom:5px">Tel (Home)</label>
                                            <input name="telhome" type="number" class="form-control input-sm">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="control-label" for="telhp" style="padding-bottom:5px">H/P</label>
                                            <input name="telhp" type="number" class="form-control input-sm">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-4">
                                            <label class="control-label" for="motherscitizenship" style="padding-bottom:5px">Citizenship</label>
                                            <input name="motherscitizenship" type="text" class="form-control input-sm">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="control-label" for="mothersoccup" style="padding-bottom:5px">Occupation</label>
                                            <input name="mothersoccup" type="text" class="form-control input-sm">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="control-label" for="mothersofficetel" style="padding-bottom:5px">Office Tel. No</label>
                                            <input name="mothersofficetel" type="number" class="form-control input-sm">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class='col-md-6'>
                            <div class="panel panel-info">
                                <div class="panel-heading text-center">PARTICULARS OF FATHER</div>
                                <div class="panel-body" style="height: 387px;">

                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label class="control-label" for="fathersname" style="padding-bottom:5px">Name</label>
                                            <input name="fathersname" type="text" class="form-control input-sm">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-2">
                                            <label class="control-label" for="fathersage" style="padding-bottom:5px">Age</label>
                                            <input name="fathersage" type="number" class="form-control input-sm">
                                        </div>

                                        <div class="col-md-2">
                                        </div>

                                        <div class="col-md-8">
                                            <label class="control-label" for="fathersnricpassport" style="padding-bottom:5px">NRIC / Passport No.</label>
                                            <input name="fathersnricpassport" type="text" class="form-control input-sm">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-4">
                                            <label class="control-label" for="fatherscitizenship" style="padding-bottom:5px">Citizenship</label>
                                            <input name="fatherscitizenship" type="text" class="form-control input-sm">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="control-label" for="fathersoccup" style="padding-bottom:5px">Occupation</label>
                                            <input name="fathersoccup" type="text" class="form-control input-sm">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="control-label" for="fathersofficetel" style="padding-bottom:5px">Office Tel. No</label>
                                            <input name="fathersofficetel" type="number" class="form-control input-sm">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class='col-md-12' style="padding-left:30px;padding-right:30px">
                        <div class="panel panel-info">
                            <div class="panel-heading text-center">PARTICULARS OF CHILD</div>
                            <div class="panel-body">

                                <div class='col-md-6' style="padding-left:0px">
                                    <div class='col-md-12'>
                                        <div class="panel panel-info">
                                            <div class="panel-body">

                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <label class="control-label" for="childsname" style="padding-bottom:5px">Name</label>
                                                        <input name="childsname" type="text" class="form-control input-sm">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-md-8">
                                                        <label class="control-label" for="childsnric" style="padding-bottom:5px">NRIC No.</label>
                                                        <input name="childsnric" type="text" class="form-control input-sm">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-md-4">
                                                        <label class="control-label" for="childsdob" style="padding-bottom:5px">Date of Birth</label>
                                                        <input name="childsdob" type="date" class="form-control input-sm">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="control-label" for="childstimeOfB" style="padding-bottom:5px">Time of Birth</label>
                                                        <input name="childstimeOfB" type="time" class="form-control input-sm">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="control-label" for="childsethnic" style="padding-bottom:5px">Ethnic Group</label>
                                                        <input name="childsethnic" type="text" class="form-control input-sm">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-md-4">
                                                        <label class="control-label" for="childsbirthplace" style="padding-bottom:5px">Place of Birth</label>
                                                        <input name="childsbirthplace" type="text" class="form-control input-sm">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="control-label" for="birthOrder" style="padding-bottom:5px">Birth Order</label>
                                                        <input name="birthOrder" type="text" class="form-control input-sm">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="control-label" for="sex" style="padding-bottom:5px">Sex</label>
                                                        <input name="sex" type="text" class="form-control input-sm">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-md-4">
                                                        <label class="control-label" for="gestation" style="padding-bottom:5px">Duration of Gestation</label>
                                                        <input name="gestation" type="number" class="form-control input-sm">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="control-label" for="deliveryMode" style="padding-bottom:5px">Mode of Deliver</label>
                                                        <input name="deliveryMode" type="text" class="form-control input-sm">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="col-md-12" style="padding-left:0px;">
                                                            <label class="control-label" for="apgarScore" style="padding-bottom:5px;">Apgar Score</label>
                                                        </div>
                                                        <label class="col-md-4 control-label" for="1min" style="padding-left:0px;">1 min</label>
                                                        <div class="col-md-8">
                                                            <input name="1min" type="number" class="form-control input-sm">
                                                        </div>
                                                        <label class="col-md-4 control-label" for="5min" style="padding-top:10px;padding-left:0px;">5 min</label>
                                                        <div class="col-md-8" style="padding-top:5px;">
                                                            <input name="5min" type="number" class="form-control input-sm">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md-4">
                                                        <label for="weightAtBirth">Weight at Birth</label>  
                                                        <div class="input-group">
                                                            <input name="weightAtBirth" type="number" class="form-control input-sm">
                                                            <span class="input-group-addon">gm</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-4" style="margin-left: 25px">
                                                        <label for="lengthAtBirth">Length at Birth</label>  
                                                        <div class="input-group">
                                                            <input name="lengthAtBirth" type="number" class="form-control input-sm">
                                                            <span class="input-group-addon">cm</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-4" style="margin-left: 25px">
                                                        <label for="headCircumference">Head Circumference</label>  
                                                        <div class="input-group">
                                                            <input name="headCircumference" type="number" class="form-control input-sm">
                                                            <span class="input-group-addon">cm</span>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class='col-md-12'>
                                        <div class="panel panel-info">
                                            <div class="panel-heading text-center">Significant events during pregnancy  / antenatal history</div>
                                            <div class="panel-body">
                                                <textarea id="antenatalHist" name="antenatalHist" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class='col-md-12'>
                                        <div class="panel panel-info">
                                            <div class="panel-heading text-center">Significant events at delivery  / post-natal period</div>
                                            <div class="panel-body">
                                                <textarea id="postnatalPeriod" name="postnatalPeriod" type="text" class="form-control input-sm"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class='col-md-6' style="padding-left:0px">
                                    <div class='col-md-12'>
                                        <div class="panel panel-info">
                                            <div class="panel-body" style="height: 792px">

                                                <div class="form-group" style="margin-left:10px;">
                                                    <label class="col-md-3 control-label" for="bloodgroup_child">Blood Group</label>
                                                    <div class="col-md-3">
                                                        <div class='input-group'>
                                                            <input id="bloodgroup_child" name="bloodgroup_child" type="text" class="form-control input-sm">
                                                            <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
                                                        </div>
                                                        <span class="help-block"></span>
                                                    </div>

                                                    <label class="col-md-2 control-label" for="rhesus">Rhesus</label>
                                                    <div class="col-md-3">
                                                        <input id="rhesus" name="rhesus" type="text" class="form-control input-sm">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-6 control-label" for="tshLevel">
                                                        TSH Level <br> 3.0-25.0u|U/mL
                                                    </label> 
                                                    <label class="radio-inline">
                                                        <input type="radio" name="tshLevel" value="normal">Normal
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="tshLevel" value=">25.0">>25.0u|U/mL
                                                    </label>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-6 control-label" for="g6pd">G6PD Deficiency</label> 
                                                    <label class="radio-inline">
                                                        <input type="radio" name="g6pd" value="0">No
                                                    </label>
                                                    <label class="radio-inline" style="margin-left:33px;">
                                                        <input type="radio" name="g6pd" value="1">Yes
                                                    </label>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-6 control-label" for="abo">
                                                        ABO Incompatibility <br> 
                                                        <div class='col-md-12' style="padding-left:0px;padding-right:0px">
                                                            <div class="panel panel-info">
                                                                <div class="panel-body" style="padding: 5px 5px">
                                                                    <label class="col-md-7 control-label" for="bloodgroup_mother" style="padding:15px 3px 3px 0px">Mother's blood group</label>
                                                                    <div class="col-md-5" style="padding:7px 0px 0px 0px">
                                                                        <div class='input-group'>
                                                                            <input id="bloodgroup_mother" name="bloodgroup_mother" type="text" class="form-control input-sm">
                                                                            <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
                                                                        </div>
                                                                        <span class="help-block"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </label> 
                                                    <label class="radio-inline">
                                                        <input type="radio" name="abo" value="0">No
                                                    </label>
                                                    <label class="radio-inline" style="margin-left:33px;">
                                                        <input type="radio" name="abo" value="1">Yes
                                                    </label>                                                
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-6 control-label" for="breastFed">Breast Fed</label> 
                                                    <label class="radio-inline">
                                                        <input type="radio" id="breastFed" name="breastFed" value="0">No
                                                    </label>
                                                    <label class="radio-inline" style="margin-left:33px;">
                                                        <input type="radio" id="breastFed" name="breastFed" value="1">Yes
                                                    </label>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-6 control-label" for="neonatalJaundice">
                                                        Neonatal Jaundice <br> 
                                                        <div class='col-md-12' style="padding-left:0px;padding-right:0px">
                                                            <div class="panel panel-info">
                                                                <div class="panel-body" style="padding: 5px 5px">
                                                                    <label class="col-md-1 control-label" for="SB" style="padding:10px 3px">SB</label>
                                                                    <div class="col-md-5">
                                                                        <input name="SB" type="text" class="form-control input-sm">
                                                                    </div>
                                                                    <label class="col-md-1 control-label" for="day" style="padding:10px 3px">Day</label>
                                                                    <div class="col-md-5">
                                                                        <input name="day" type="text" class="form-control input-sm">
                                                                    </div>                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </label> 
                                                    <label class="radio-inline">
                                                        <input type="radio" name="neonatalJaundice" value="0">No
                                                    </label>
                                                    <label class="radio-inline" style="margin-left:33px;">
                                                        <input type="radio" name="neonatalJaundice" value="1">Yes
                                                    </label>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-6 control-label" for="phototherapy">Phototherapy</label> 
                                                    <label class="radio-inline">
                                                        <input type="radio" name="phototherapy" value="0">No
                                                    </label>
                                                    <label class="radio-inline" style="margin-left:33px;">
                                                        <input type="radio" name="phototherapy" value="1">Yes
                                                    </label>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-6 control-label" for="vitK">Vitamin K (given)</label> 
                                                    <label class="radio-inline">
                                                        <input type="radio" name="vitK" value="0">No
                                                    </label>
                                                    <label class="radio-inline" style="margin-left:33px;">
                                                        <input type="radio" name="vitK" value="1">Yes
                                                    </label>
                                                </div>

                                                <div class="panel panel-info" style="margin-top:200px">
                                                    <div class="panel-body">

                                                        <div class='col-md-12'>
                                                            <label class="col-md-3 control-label" for="obstetrician">Obstetrician</label>
                                                            <div class="col-md-8">
                                                                <input name="obstetrician" type="text" class="form-control input-sm">
                                                            </div>
                                                        </div>

                                                        <div class='col-md-12' style="padding-top:10px">
                                                            <label class="col-md-3 control-label" for="paediatrician">Paediatrician</label>
                                                            <div class="col-md-8">
                                                                <input name="paediatrician" type="text" class="form-control input-sm">
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>                   

                    <div class='col-md-12' style="padding-left:30px;padding-right:30px">
                        <div class="panel panel-info">
                            <div class="panel-heading text-center">CHILDHOOD DISEASES RECORD</div>
                            <div class="panel-body">

                                <div class='col-md-3'></div>

                                <div class='col-md-3'>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" name="chickenPox">
                                        <label class="form-check-label" for="chickenPox" style="padding-left:10px">Chicken pox</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" name="diphtheria">
                                        <label class="form-check-label" for="diphtheria" style="padding-left:10px">Diphtheria</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" name="measles">
                                        <label class="form-check-label" for="measles" style="padding-left:10px">Measles</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" name="rubella">
                                        <label class="form-check-label" for="rubella" style="padding-left:10px">Rubella</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" name="hepatities">
                                        <label class="form-check-label" for="hepatities" style="padding-left:10px">Hepatities</label>
                                    </div>

                                    <div class="form-group" style="padding-left:15px;">
                                        <div class="form-check">
                                            <div class="col-md-1" style="padding-left:0px;">
                                                <input class="form-check-input" type="checkbox" value="1" id="specify1" name="specify1">
                                            </div>
                                            <div class="col-md-11" style="padding-left:0px;">
                                                <input id="specify1_text" name="specify1_text" type="text" class="form-control input-sm" placeholder="specify">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class='col-md-3'>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" name="mumps">
                                        <label class="form-check-label" for="mumps" style="padding-left:10px">Mumps</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" name="poliomyelitis">
                                        <label class="form-check-label" for="poliomyelitis" style="padding-left:10px">Poliomyelitis</label>
                                    </div>
                                    
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" name="tetanus">
                                        <label class="form-check-label" for="tetanus" style="padding-left:10px">Tetanus</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" name="whoopingCough">
                                        <label class="form-check-label" for="whoopingCough" style="padding-left:10px">Whooping cough</label>
                                    </div>

                                    <div class="form-group" style="padding-left:15px;">
                                        <div class="form-check">
                                            <div class="col-md-1" style="padding-left:0px;">
                                                <input class="form-check-input" type="checkbox" value="1" id="specify2" name="specify2">
                                            </div>
                                            <div class="col-md-11" style="padding-left:0px;">
                                                <input id="specify2_text" name="specify2_text" type="text" class="form-control input-sm" placeholder="specify">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group" style="padding-left:15px;">
                                        <div class="form-check">
                                            <div class="col-md-1" style="padding-left:0px;">
                                                <input class="form-check-input" type="checkbox" value="1" id="specify3" name="specify3">
                                            </div>
                                            <div class="col-md-11" style="padding-left:0px;">
                                                <input id="specify3_text" name="specify3_text" type="text" class="form-control input-sm" placeholder="specify">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class='col-md-3'></div>

                            </div>
                        </div>
                    </div>

                    <div class='col-md-12' style="padding-left:30px;padding-right:30px">
                        <div class="panel panel-info" id="jqGridMedicalSurgical_c">
                            <div class="panel-heading text-center">MEDICAL / SURGICAL RECORD</div>
                            <div class="panel-body">
                                <div class='col-md-12' style="padding:0 0 15px 0">
                                    <table id="jqGridMedicalSurgical" class="table table-striped"></table>
                                    <div id="jqGridPagerMedicalSurgical"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class='col-md-12' style="padding-left:30px;padding-right:30px">
                        <div class="panel panel-info">
                            <div class="panel-heading text-center">IMMUNISATION RECORD</div>
                            <div class="panel-body">
                                
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">Age Due</th>
                                            <th scope="col">Type of Vaccinations</th>
                                            <th scope="col">Date given</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row">Soon after birth</th>
                                            <td>B.C.G. & Hepatitis B Vaccine (1st dose)</td>
                                            <td><input name="bcg" type="date" class="form-control input-sm"></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">1 month</th>
                                            <td>Hepatitis B Vaccine (2nd dose)</td>
                                            <td><input name="hepaB2nd" type="date" class="form-control input-sm"></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">2-3 months</th>
                                            <td>1st Triple Antigen (DPT) & Oral Polio Vaccine</td>
                                            <td><input name="1dpt" type="date" class="form-control input-sm"></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">3-4 months</th>
                                            <td>2nd Triple Antigen (DPT) & Oral Polio Vaccine</td>
                                            <td><input name="2dpt" type="date" class="form-control input-sm"></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">5-6 months</th>
                                            <td>3rd Triple Antigen (DPT) & Oral Polio Vaccine</td>
                                            <td><input name="2dpt" type="date" class="form-control input-sm"></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">6-7 months</th>
                                            <td>Hepatitis B Vaccine (3rd dose)</td>
                                            <td><input name="hepa3rd" type="date" class="form-control input-sm"></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">12-13 months</th>
                                            <td>Measles/Mumps/Rubella (MMR)</td>
                                            <td><input name="mmr" type="date" class="form-control input-sm"></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">13-14 months</th>
                                            <td>Chickenpox Vaccine</td>
                                            <td><input name="chickenpox" type="date" class="form-control input-sm"></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">18-24 months</th>
                                            <td>1st Booster Triple Antigen (DPT/Polio)</td>
                                            <td><input name="1stbooster" type="date" class="form-control input-sm"></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">4-6 years</th>
                                            <td>Measles/Mumps/Rubella (MMR) Booster</td>
                                            <td><input name="mmrBooster" type="date" class="form-control input-sm"></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">7 years</th>
                                            <td>2nd Booster Double Antigen (DT) & Oral Polio Vaccine</td>
                                            <td><input name="2ndDT" type="date" class="form-control input-sm"></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">11-12 years</th>
                                            <td>BCG</td>
                                            <td><input name="bcg" type="date" class="form-control input-sm"></td>
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
</div>
	