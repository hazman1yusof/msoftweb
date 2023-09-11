
<div class="ui segments" style="position: relative;">
	<div class="ui inverted dimmer" id="loader_daily">
	   <div class="ui large text loader">Loading</div>
	</div>
	<div class="ui secondary segment bluecloudsegment">
		<div class="ui labeled small input">
			<div class="ui blue label">Visit Date</div>
			<input type="date" name="visit_date" id="visit_date" readonly value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
		</div>
		<select class="ui small dropdown" id="dialysisbefore">
		  <option value="">Dialysis Before</option>
		</select>

		<div class="ui red label" id="absent_label" style="display: none;">
		  ABSENT
		</div>

		<button class="ui yellow small button" id="edit_permission" style="display: none;"><i class="unlock icon"></i> Edit Permission</button>

		<div class="ui small blue icon buttons" id="btn_grp_dialysis" style="position: absolute;
					padding: 0 0 0 0;
					right: 40px;
					top: 9px;
					z-index: 2;">
		  <button class="ui button" id="new_dialysis" disabled><span class="fa fa-plus-square-o"></span> New</button>
		  <button class="ui button" id="edit_dialysis" disabled><span class="fa fa-edit"></span> Edit</button>
		  <button class="ui button" id="save_dialysis" disabled><span class="fa fa-save"></span> Save</button>
		  <button class="ui button" id="cancel_dialysis" disabled><span class="fa fa-ban"></span> Cancel</button>
		</div>
	</div>


	<div class="ui segment diaform">
		<!-- <div class="ui message">
		<div class="ui mini form">
		  <div class="three fields">
		    <div class="field">
					<label><div class="label_hd">Start Date:</div></label>
		      <input type="date" id="seldate" disabled="" value="{{Carbon\Carbon::now()}}">
		    </div>
		    <div class="field"><label><div class="label_hd">&nbsp;</div></label>
		      <button type="button" class="ui mini blue submit button" id="addnew_dia" disabled="">Add New Record</button>
		      <button type="button" class="ui mini blue submit button" id="edit_dia" disabled="">Edit Record</button>
		    </div>
		  </div>
		</div>
		</div> -->

		<input type="hidden" name="episno" id="episno">
		<input type="hidden" name="mrn" id="mrn">
    	<input id="dialysis_episode_idno" name="dialysis_episode_idno" type="hidden">

		<form id="daily_form" class="ui mini form" autocomplete="off">
			<input type="hidden" name="idno" id="idno">
			<input type="hidden" name="arrivalno" id="arrivalno">
			<input type="hidden" name="user_prehd" id="user_prehd">
			<input type="hidden" name="user_0" id="user_0">
			<input type="hidden" name="user_1" id="user_1">
			<input type="hidden" name="user_2" id="user_2">
			<input type="hidden" name="user_3" id="user_3">
			<input type="hidden" name="user_4" id="user_4">
			<input type="hidden" name="user_5" id="user_5">
			<div class="four fields prehddiv" id="valid_prehd">
				<div class="field">
					<div class="clinic_code">
						<label><div class="label_hd">START TIME:</div></label>
						<input type="time" name="start_time" id="start_time" class="purplebg" value="" required>
					</div>
				</div>
				<div class="field">
					<div class="clinic_code">
						<label><div class="label_hd">PREV POST WEIGHT:</div></label>
						<div class="ui right labeled input">
							<input type="text" name="prev_post_weight" id="prev_post_weight" class="" value="" rdonly>
							<div class="ui basic label mylabel">KG</div>
						</div>
					</div>
				</div>
				<div class="field">
					<div class="clinic_code">
						<label> MACHINE NO:</label>
						<input type="text" name="machine_no" id="machine_no" class="purplebg" value="" required>
					</div>
				</div>
				<div class="field">
					<label> DIALYSATE CA:</label>
					<select class="ui selection dropdown purplebg" id="dialysate_ca" name="dialysate_ca" required>
					  <option value="">Select Here</option>
					  <option value="1">1</option>
					  <option value="1.25">1.25</option>
					  <option value="1.5">1.5</option>
					</select>
				</div>
				<div class="field">
					<label> DIALYSER:</label>
					<select class="ui selection dropdown purplebg" id="dialyser" name="dialyser" required>
					  <option value="">Select Here</option>
					  <option value="SINGLE USE">SINGLE USE</option>
					  <option value="REUSE">REUSE</option>
					</select>
				</div>
			</div>
			
			<div class="four fields prehddiv">
				<div class="field">
					<div class="clinic_code">
						<label><div class="label_hd">LAST VISIT:</div></label>
						<input type="date" name="last_visit" id="last_visit" class="" value="" rdonly>
					</div>
				</div>
				<div class="field">
					<div class="clinic_code">
					    <label><div class="label_hd">PRE WEIGHT:</div></label>
							<div class="ui right labeled input">
								<input type="text" name="pre_weight" id="pre_weight" class="purplebg" value="" required>
								<div class="ui basic label mylabel">KG</div>
							</div>
					</div>
				</div>
				<div class="field">
					<label> HEPARIN TYPE:</label>
					<select class="ui selection dropdown purplebg" id="heparin_type" name="heparin_type" required>
					  <option value="">Select Here</option>
					  <option value="NORMAL">NORMAL</option>
					  <option value="TIGHT">TIGHT</option>
					  <option value="FREE">FREE</option>
					</select>
				</div>

				 <div class="field">
					<label> DIALYSATE FLOW:</label>
					<select class="ui selection dropdown purplebg" id="dialysate_flow" name="dialysate_flow" required>
					  <option value="">Select Here</option>
					  <option value="300">300</option>
					  <option value="500">500</option>
					  <option value="800">800</option>
					</select>
				</div>
				<div class="field">
					<label> NO OF USE:</label>
					<select class="ui selection dropdown purplebg" id="no_of_use" name="no_of_use">
					  <option value="">Select Here</option>
					  <option value="1">1</option>
					  <option value="2">2</option>
					  <option value="3">3</option>
					  <option value="4">4</option>
					  <option value="5">5</option>
					  <option value="6">6</option>
					  <option value="7">7</option>
					  <option value="8">8</option>
					  <option value="9">9</option>
					  <option value="10">10</option>
					  <option value="11">11</option>
					  <option value="12">12</option>
					  <option value="13">13</option>
					</select>
				</div>
			</div>

			<div class="four fields prehddiv">
				<div class="field">
					<div class="clinic_code">
						<label><div class="label_hd">DURATION OF HD:</div></label>
						<div class="ui right labeled input">
							<input type="text" name="duration_of_hd" id="duration_of_hd" class="" value="" rdonly>
							<div class="ui basic label mylabel">HRS</div>
						</div>
					</div>
				</div>
			    <div class="field">
					<div class="clinic_code">
						<label><div class="label_hd">IDWG:</div></label>
						<div class="ui right labeled input">
							<input type="text" name="idwg" id="idwg" class="" value="" rdonly >
							<div class="ui basic label mylabel">KG</div>
						</div>
					</div>
				</div>
				<div class="field">
					<div class="clinic_code">
						<label> HEPARIN BOLUS:</label>
						<div class="ui right labeled input">
							<input type="text" name="heparin_bolus" id="heparin_bolus" class="purplebg" value="">
							<div class="ui basic label mylabel">iu</div>
						</div>
					</div>
				</div>
				<div class="field">
					<div class="clinic_code">
						<label> CONDUCTIVITY:</label>
						<div class="ui right labeled input">
							<input type="text" name="conductivity" id="conductivity" class="purplebg" value="" required>
							<div class="ui basic label mylabel">mS/cm</div>
						</div>
					</div>
				</div>
				<div class="field"></div>
			</div>

			<div class="four fields prehddiv">
				<div class="field">
					<div class="clinic_code">
						<label><div class="label_hd">DRY WEIGHT:</div></label>
						<div class="ui right labeled input">
							<input type="text" name="dry_weight" id="dry_weight" class="" value="" rdonly>
							<div class="ui basic label mylabel">KG</div>
						</div>
					</div>
				</div>
			    <div class="field">
					<div class="clinic_code">
						<label><div class="label_hd">TARGET WEIGHT:</div></label>
						<div class="ui right labeled input">
							<input type="text" name="target_weight" id="target_weight" class="" value="" rdonly>
							<div class="ui basic label mylabel">KG</div>
						</div>
					</div>
				</div>
				<div class="field">
					<div class="clinic_code">
						<label> HEPARIN MAINTAINANCE:</label>
						<div class="ui right labeled input">
							<input type="text" name="heparin_maintainance" id="heparin_maintainance" class="purplebg" value="">
							<div class="ui basic label mylabel">iu</div>
						</div>
					</div>
				</div>
				<div class="field">
					<label> CHECK FOR RESIDUAL:</label>
					<select class="ui selection dropdown purplebg" id="check_for_residual" name="check_for_residual">
					  <option value="">Select Here</option>
					  <option value="YES">YES</option>
					  <option value="NO">NO</option>
					</select>
				</div>
				<div class="field">
				</div>
			</div>

			<div class="four fields prehddiv">
			    <div class="field">
					<div class="clinic_code">
						<label><div class="label_hd">TARGET UF:</div></label>
						<input type="text" name="target_uf" id="target_uf" class="purplebg" value="" required>
					</div>
				</div>
				<div class="field">
					<div class="clinic_code">
						<label> PRIME BY:</label>
						<input type="text" name="prime_by" id="prime_by" class="purplebg" value="" required>
					</div>
				</div>
				<div class="field">
					<div class="clinic_code">
						<label> INITIATED BY:</label>
						<input type="text" name="initiated_by" id="initiated_by" class="" value="" rdonly >
					</div>
				</div>
				<div class="field">
					<div class="clinic_code">
						<label> VERIFIED BY:</label>
						<div class="ui action input">
						  <input type="text" name="verified_by" id="verified_by" rdonly required>
						  <button class="ui button" type="button" id="verified_btn">Verifiy</button>
						</div>
					</div>
				</div>
				<div class="field">
				</div>
			</div>
		    
		    <hr>

			<h4 class="ui dividing header">PRE HD ASSESSMENT
				<div class="ui tiny userlabel circular primary label" style="display:none;">
				  <i class="user icon"></i>Pre-HD: <span id="span_user_prehd"></span>
				</div>
			</h4>

			<div class="two fields prehddiv">
			  	<div class="field">
			  		<div class="clinic_code">
			  			<label style="">BLOOD PRESSURE (mmHg):</label>
			  				<div class="two fields">
			  					<div class="ui labeled input" style="padding-right:5px">
			  						<div class="ui label">Systolic</div>
			  						<input type="text" name="prehd_systolic" id="prehd_systolic" class="purplebg" value="" required>
			  					</div>
			  					<div class="ui labeled input">
			  						<div class="ui label">Diastolic</div>
			  						<input type="text" name="prehd_diastolic" id="prehd_diastolic" class="purplebg" value="" required>
			  					</div>
			  				</div>
			  		</div>
			  	</div>
		      
		    	<div class="field">
					<div class="clinic_code">
						<label style="">T.P.R:</label>
		  				<div class="three fields" style="padding-left:5px">
								<div class="ui right labeled input">
									<input type="text" placeholder="Temperature" name="prehd_temperature" id="prehd_temperature" value="" class="purplebg" required>
									<div class="ui basic label mylabel" style="margin-right: 5px;">°C</div>
								</div>
								<div class="ui right labeled input">
	  							<input type="text" placeholder="Pulse" name="prehd_pulse" id="prehd_pulse" value="" class="purplebg" required>
									<div class="ui basic label mylabel" style="margin-right: 5px;">bpm</div>
								</div>
								<div class="ui right labeled input">
									<input type="text" placeholder="Respiratory" name="prehd_respiratory" id="prehd_respiratory" value="" class="purplebg" required>
									<div class="ui basic label mylabel">/minute</div>
								</div>
		  				</div>
					</div>
				</div>
			</div>

			<div class="four fields prehddiv">
			    <div class="field">
					<label> RESPIRATORY:</label>
					<select class="ui selection dropdown purplebg" id="respiratory" name="respiratory" required>
					  <option value="">Select Here</option>
					  <option value="EUPNEA">EUPNEA</option>
					  <option value="BRADYPNEA">BRADYPNEA</option>
					  <option value="TACHYPNEA">TACHYPNEA</option>
					  <option value="HYPERPNEA">HYPERPNEA</option>
					</select>
			    </div>
			    <div class="field">
		  			<label >EYE:</label>
					<select class="ui selection dropdown purplebg" id="eye" name="eye" required>
					  <option value="">Select Here</option>
					  <option value="PERIOBITAL EDEMA">PERIOBITAL EDEMA</option>
					  <option value="REDNESS">REDNESS</option>
					  <option value="CATARACT">CATARACT</option>
					  <option value="BLIND">BLIND</option>
					  <option value="N/A">N/A</option>
					</select>
			  	</div>

			    <div class="field">
					<label> NECK:</label>
					<select class="ui selection dropdown purplebg" id="neck" name="neck" required>
					  <option value="">Select Here</option>
					  <option value="JUGULAR VENOUS DISTENSION">JUGULAR VENOUS DISTENSION</option>
					  <option value="N/A">N/A</option>
					</select>
			    </div>

			    <div class="field">
					<label> ABDOMEN:</label>
					<select class="ui selection dropdown purplebg" id="abdomen" name="abdomen" required>
					  <option value="">Select Here</option>
					  <option value="DISTENDED">DISTENDED</option>
					  <option value="SOFT & NON-TENDER">SOFT & NON-TENDER</option>
					</select>
			    </div>
			</div>

			<div class="four fields prehddiv">
			    <div class="field">
					<label> SKIN:</label>
					<select class="ui selection dropdown purplebg" id="skin" name="skin" required>
					  <option value="">Select Here</option>
					  <option value="DRY">DRY</option>
					  <option value="RASHES">RASHES</option>
					  <option value="PRURITIS">PRURITIS</option>
					  <option value="N/A">N/A</option>
					</select>
			    </div>
			    <div class="field">
					<label> LOWER LIMB:</label>
					<select class="ui selection dropdown purplebg" id="lower_limb" name="lower_limb" required>
					  <option value="">Select Here</option>
					  <option value="NORMAL">NORMAL</option>
					  <option value="OEDEMATOUS">OEDEMATOUS</option>
					  <option value="ULCER">ULCER</option>
					  <option value="CALLUSES">CALLUSES</option>
					  <option value="BLISTER">BLISTER</option>
					</select>
			    </div>
			</div>

			<div class="ui horizontal divider"><h5 style="color: grey;font-size: 12px;">Access</h5></div>

			<div class="two fields prehddiv">
				<div class="field">
					<input class="purplebg" type="text" name="access_placeholder" placeholder="Access" rdonly id="access_placeholder">
				</div>

				<div class="field">
					<div class="three fields" style="padding-left:5px">
						<div class="field">
							<select class="ui selection dropdown purplebg" id="type" name="type" required>
							  <option value="">Access Type</option>
							  <option value="PERMANENT CATHETER">PC - PERMANENT CATHETER</option>
							  <option value="INTRAJUGULAR CATHETER">IJC - INTRAJUGULAR CATHETER</option>
							  <option value="FEMORAL CATHETER">FC - FEMORAL CATHETER</option>
							  <option value="SUBCLAVIAN CATHETER">SVC - SUBCLAVIAN CATHETER</option>
							  <option value="ARTERIOVENOUS FISTULA">AVF - ARTERIOVENOUS FISTULA</option>
							  <option value="BRACHIOCEPHALIC FISTULA">BCF - BRACHIOCEPHALIC FISTULA</option>
							  <option value="BRACHIOBASILIC FISTULA">BBF - BRACHIOBASILIC FISTULA</option>
							  <option value="ARTERIOVENOUS GRAFT">GRAFT - ARTERIOVENOUS GRAFT</option>
							</select>
					    </div>

						<div class="field">
							<select class="ui selection dropdown purplebg" id="site" name="site">
							  <option value=""></option>
							  <option value="LEFT">LEFT</option>
							  <option value="RIGHT">RIGHT</option>
							  <option value="NA">N/A</option>
							</select>
					    </div>

					    <div class="field">
							<select class="ui selection dropdown purplebg" id="access" name="access">
							  <option value=""></option>
							  <option value="PERMANENT">PERMANENT</option>
							  <option value="TEMPORARY">TEMPORARY</option>
							  <option value="NA">N/A</option>
							</select>
					    </div>
					</div>
				</div>
			</div>

			<div class="three fields prehddiv">
			    <div class="field">
					<label> BRUIT & THRILL:</label>
					<select class="ui selection dropdown purplebg" id="bruit" name="bruit" required>
					  <option value="">Select Here</option>
					  <option value="YES">YES</option>
					  <option value="NO">NO</option>
					</select>
			    </div>

			    <div class="field">
					<label> DRESSING:</label>
					<select class="ui selection dropdown purplebg" id="dressing" name="dressing" required>
					  <option value="">Select Here</option>
					  <option value="N/A">N/A</option>
					  <option value="INTACT">INTACT</option>
					  <option value="WET">WET</option>
					  <option value="LOOSE">LOOSE</option>
					</select>
			    </div>

			    <div class="field">
					<div class="clinic_code">
						<label> CONDITION AVF/EXIT SITE:</label>
						<input type="text" name="cond_avf_ext_site" id="cond_avf_ext_site" class="purplebg" value="" required>
					</div>
			    </div>
			</div>
		    
			<div class="field prehddiv">
				<div class="clinic_code">
		       		<label style="">General Assesment:</label>
					<textarea name="general_assesment" id="general_assesment" rows="3" cols="100" class="purplebg" required></textarea>
				</div>
			</div>

			<hr>

			<h4 class="ui dividing header">HOURLY CHART
				<div class="ui tiny userlabel circular grey label" style="display:none;">
				  <i class="user icon"></i>Commencing: <span id="span_user_0"></span>
				</div>
				<div class="ui tiny userlabel circular teal label" style="display:none;">
				  <i class="user icon"></i>1st Hour: <span id="span_user_1"></span>
				</div>
				<div class="ui tiny userlabel circular green label" style="display:none;">
				  <i class="user icon"></i>2nd Hour: <span id="span_user_2"></span>
				</div>
				<div class="ui tiny userlabel circular red label" style="display:none;">
				  <i class="user icon"></i>3rd Hour: <span id="span_user_3"></span>
				</div>
				<div class="ui tiny userlabel circular yellow label" style="display:none;">
				  <i class="user icon"></i>4th Hour: <span id="span_user_4"></span>
				</div>
				<div class="ui tiny userlabel circular pink label" style="display:none;">
				  <i class="user icon"></i>5th Hour: <span id="span_user_5"></span>
				</div>
			</h4>

			<table class="table ui form" id="preHDListMeasure">
				<thead>
					<tr class="0_tr">
						<th style="text-align: left;">
							Commencing</br>Hour:
							</br>
							<div class="ui small icon input">
							  <input type="time" placeholder="" id="0_tc" name="0_tc" class="smallinputpad">
							</div>
						</th>
						<th style="text-align: center;">&nbsp;</br>BP
							</br>
							<div class="ui right labeled input">
								<div class="ui small icon input">
									<input type="text" placeholder="" id="0_bp" name="0_bp" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">mmHg</div>
							</div>
						</th>
						<th style="text-align: center;">&nbsp;</br>PULSE
							</br>
							<div class="ui right labeled input">
								<div class="ui small icon input">
									<input type="text" placeholder="" id="0_pulse" name="0_pulse" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">bpm</div>
							</div>
						</th>
						<th style="text-align: center;">&nbsp;</br>BLOOD FLOW RATE
							</br>
							<div class="ui right labeled input">
								<div class="ui small icon input">
									<input type="text" placeholder="" id="0_bfr" name="0_bfr" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">mls/minute</div>
							</div>
						</th>
						<th style="text-align: center;">&nbsp;</br>VENOUS PRESSURE
							</br>
							<div class="ui right labeled input">
								<div class="ui small icon input">
									<input type="text" placeholder="" id="0_vp" name="0_vp" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">mmHg</div>
							</div>
						</th>
						<th style="text-align: center;">&nbsp;</br>TMP
							</br>
							<div class="ui right labeled input">
								<div class="ui small icon input">
									<input type="text" placeholder="" id="0_tmp" name="0_tmp" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">mmHg</div>
							</div>
						</th>
						<th style="text-align: center;">&nbsp;</br>DELIVERED HEPARIN
							</br>
							<div class="ui right labeled input">
								<div class="ui small icon input">
									<input type="text" placeholder="" id="0_dh" name="0_dh" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">iu</div>
							</div>
						</th>
						<th style="text-align: center;">&nbsp;</br>UF VOLUME
							</br>
							<div class="ui right labeled input">
								<div class="ui small icon input">
									<input type="text" placeholder="" id="0_uv" name="0_uv" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">ml</div>
							</div>
						</th>
						<th style="text-align: center;">&nbsp;</br>FLUIDS
							</br>
							<div class="ui right labeled input">
								<div class="ui small icon input">
									<input type="text" placeholder="" id="0_f" name="0_f" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">ml</div>
							</div>
						</th>
					</tr>
				</thead>


				<tbody>
					<tr style="background-color:#f3ffff;" class="1_tr">
						<td class="labeltd">1st Hour:
							</br>
							<div class="ui small icon input">
							  <input type="time" placeholder="" id="1_tc" name="1_tc" class="smallinputpad">
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="1_bp" id="1_bp" value="" placeholder="BP" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">mmHg</div>
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="1_pulse" id="1_pulse" value="" placeholder="PULSE" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">bpm</div>
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="1_bfr" id="1_bfr" value="" placeholder="BLOOD FLOW RATE" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">mls/minute</div>
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="1_vp" id="1_vp" value="" placeholder="VENOUS PRESSURE" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">mmHg</div>
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="1_tmp" id="1_tmp" value="" placeholder="TMP" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">mmHg</div>
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="1_dh" id="1_dh" value="" placeholder="DELIVERED HEPARIN" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">iu</div>
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="1_uv" id="1_uv" value="" placeholder="UF VOLUME" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">ml</div>
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="1_f" id="1_f" value="" placeholder="FLUIDS" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">ml</div>
							</div>
						</td>
					</tr>
					<tr style="background-color:#f3ffff;" class="1_tr">
						<td class="labeltd">Remarks:</td>
						<td colspan="8"><textarea name="1_remarks" id="1_remarks" rows="3" cols="100" style="background-color: #dcf7f7;"></textarea></td>
					</tr>
					<tr style="background-color:#e4ffe4;" class="2_tr">
						<td class="labeltd">2nd Hour:
							</br>
							<div class="ui small icon input">
							  <input type="time" placeholder="" id="2_tc" name="2_tc" class="smallinputpad">
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="2_bp" id="2_bp" value="" placeholder="BP" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">mmHg</div>
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="2_pulse" id="2_pulse" value="" placeholder="PULSE" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">bpm</div>
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="2_bfr" id="2_bfr" value="" placeholder="BLOOD FLOW RATE" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">mls/minute</div>
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="2_vp" id="2_vp" value="" placeholder="VENOUS PRESSURE" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">mmHg</div>
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="2_tmp" id="2_tmp" value="" placeholder="TMP" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">mmHg</div>
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="2_dh" id="2_dh" value="" placeholder="DELIVERED HEPARIN" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">iu</div>
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="2_uv" id="2_uv" value="" placeholder="UF VOLUME" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">ml</div>
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="2_f" id="2_f" value="" placeholder="FLUIDS" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">ml</div>
							</div>
						</td>
					</tr>
					<tr style="background-color:#e4ffe4;" class="2_tr">
						<td class="labeltd">Remarks:</td>
						<td colspan="8"><textarea name="2_remarks" id="2_remarks" rows="3" cols="100" style="background-color: #d9f1d9;"></textarea></td>
					</tr>
					<tr style="background-color:#ffdcdc;" class="3_tr">
						<td class="labeltd">3rd Hour:
							</br>
							<div class="ui small icon input">
							  <input type="time" placeholder="" id="3_tc" name="3_tc" class="smallinputpad">
							</div>

						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="3_bp" id="3_bp" value="" placeholder="BP" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">mmHg</div>
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="3_pulse" id="3_pulse" value="" placeholder="PULSE" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">bpm</div>
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="3_bfr" id="3_bfr" value="" placeholder="BLOOD FLOW RATE" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">mls/minute</div>
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="3_vp" id="3_vp" value="" placeholder="VENOUS PRESSURE" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">mmHg</div>
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="3_tmp" id="3_tmp" value="" placeholder="TMP" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">mmHg</div>
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="3_dh" id="3_dh" value="" placeholder="DELIVERED HEPARIN" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">iu</div>
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="3_uv" id="3_uv" value="" placeholder="UF VOLUME" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">ml</div>
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="3_f" id="3_f" value="" placeholder="FLUIDS" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">ml</div>
							</div>
						</td>
					</tr>
					<tr style="background-color:#ffdcdc;" class="3_tr">
						<td class="labeltd">Remarks:</td>
						<td colspan="8"><textarea name="3_remarks" id="3_remarks" rows="3" cols="100" style="background-color: #ebcaca;"></textarea></td>
					</tr>
					<tr style="background-color:#ffffc9;" class="4_tr">
						<td class="labeltd">4th Hour:
							</br>
							<div class="ui small icon input">
							  <input type="time" placeholder="" id="4_tc" name="4_tc" class="smallinputpad">
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="4_bp" id="4_bp" value="" placeholder="BP" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">mmHg</div>
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="4_pulse" id="4_pulse" value="" placeholder="PULSE" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">bpm</div>
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="4_bfr" id="4_bfr" value="" placeholder="BLOOD FLOW RATE" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">mls/minute</div>
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="4_vp" id="4_vp" value="" placeholder="VENOUS PRESSURE" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">mmHg</div>
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="4_tmp" id="4_tmp" value="" placeholder="TMP" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">mmHg</div>
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="4_dh" id="4_dh" value="" placeholder="DELIVERED HEPARIN" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">iu</div>
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="4_uv" id="4_uv" value="" placeholder="UF VOLUME" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">ml</div>
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="4_f" id="4_f" value="" placeholder="FLUIDS" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">ml</div>
							</div>
						</td>
					</tr>
					<tr style="background-color:#ffffc9;" class="4_tr">
						<td class="labeltd">Remarks:</td>
						<td colspan="8"><textarea name="4_remarks" id="4_remarks" rows="3" cols="100" style="background-color: #efefbe;"></textarea></td>
					</tr>
					<tr style="background-color:#fff5e8;" class="5_tr">
						<td class="labeltd">5th Hour:
							</br>
							<div class="ui small icon input">
							  <input type="time" placeholder="" id="5_tc" name="5_tc" class="smallinputpad">
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="5_bp" id="5_bp" value="" placeholder="BP" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">mmHg</div>
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="5_pulse" id="5_pulse" value="" placeholder="PULSE" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">bpm</div>
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="5_bfr" id="5_bfr" value="" placeholder="BLOOD FLOW RATE" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">mls/minute</div>
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="5_vp" id="5_vp" value="" placeholder="VENOUS PRESSURE" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">mmHg</div>
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="5_tmp" id="5_tmp" value="" placeholder="TMP" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">mmHg</div>
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="5_dh" id="5_dh" value="" placeholder="DELIVERED HEPARIN" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">iu</div>
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="5_uv" id="5_uv" value="" placeholder="UF VOLUME" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">ml</div>
							</div>
						</td>
						<td>&nbsp;</br>
							<div class="ui right labeled input">
								<div class="ui small input">
									<input type="text" name="5_f" id="5_f" value="" placeholder="FLUIDS" class="smallinputpad">
								</div>
								<div class="ui basic label mylabel">ml</div>
							</div>
						</td>
					</tr>
					<tr style="background-color:#fff5e8;" class="5_tr">
						<td class="labeltd">Remarks:</td>
						<td colspan="8"><textarea name="5_remarks" id="5_remarks" rows="3" cols="100" style="background-color: #f1e7da;"></textarea></td>
					</tr>
					<!-- <tr>
						<td colspan="9">
							<label>POST HD ASSESSMENT</label>
							<textarea name="post_hd_assesment" id="post_hd_assesment" rows="3" cols="100" ></textarea>
						</td>
					</tr> -->
				</tbody>
			</table>

		    <hr>

		</form>

		<div class="ui segments">
            <div class="ui secondary segment">ADDITIONAL NOTES</div>
            <div class="ui segment" id="jqGridAddNotesDialysis_c">
                <table id="jqGridAddNotesDialysis" class="table table-striped"></table>
                <div id="jqGridPagerAddNotesDialysis"></div>
            </div>
        </div>

		<form id="daily_form_patmedication" class="ui mini form" autocomplete="off" style="margin-bottom:30px">
			<h4 class="ui dividing header">DRUG ADMINISTRATION</h4>
			<div class="ui grid">
				<div class="two wide column">
					<table id="patmedication_trx_tbl" class="ui celled table" style="min-width:100%;">
		                <thead>
		                    <tr>
		                        <th class="scope">idn</th>
		                        <th class="scope">mrn</th>
		                        <th class="scope">episno</th>
		                        <th class="scope">Drug List <i id="own_med_add" class="right floated link plus icon blue" data-content="Add user's own medicine" data-variation="small"></i></th>
		                        <th class="scope">chg_code</th>
		                        <th class="scope">quantity</th>
		                        <th class="scope">ins_code</th>
		                        <th class="scope">dos_code</th>
		                        <th class="scope">fre_code</th>
		                        <th class="scope">ins_desc</th>
		                        <th class="scope">dos_desc</th>
		                        <th class="scope">fre_desc</th>
		                    </tr>
		                </thead>
		            </table>
				</div>
				

				<input type="hidden" name="patmedication_trx_tbl_idno" id="patmedication_trx_tbl_idno">
				<div class="fourteen wide column">
					<table id="patmedication_tbl" class="ui celled table" style="min-width:100%;" data-addmode='false'>
		                <thead>
		                    <tr>
		                        <th class="scope">idno</th>
		                        <th class="scope">chgcode</th>
		                        <th class="scope">ownmed</th>
		                        <th class="scope" width="30%">Drug Name</th>
		                        <th class="scope" width="5%">Dosage</th>
		                        <th class="scope" width="5%">Frequancy</th>
		                        <th class="scope" width="5%">Quantity</th>
		                        <th class="scope" width="10%">Initiated By</th>
		                        <th class="scope" width="10%">Verified By</th>
		                        <th class="scope" width="15%">Status</th>
		                    </tr>
		                </thead>
		            </table>
				</div>
			</div>

		</form>

		<form id="daily_form_completed" class="ui mini form" autocomplete="off">

			<input type="hidden" name="user_posthd" id="user_posthd">
			<h4 class="ui dividing header">POST HD ASSESSMENT
				<div class="ui tiny userlabel circular purple label" style="display:none;">
				  <i class="user icon"></i>Post-HD: <span id="span_user_posthd"></span>
				</div>
			</h4>

			<div class="field">
				<div class="clinic_code">
					<label>POST HD ASSESSMENT</label>
					<textarea name="post_hd_assesment" id="post_hd_assesment" rows="3" cols="100" class="purplebg" required></textarea>
				</div>
			</div>

			<div class="five fields">
			  	<div class="six wide field">
			  		<div class="clinic_code">
			  			<label style="">BLOOD PRESSURE (mmHg):</label>
			  				<div class="two fields">
			  					<div class="ui labeled input" style="padding-right:5px">
			  						<div class="ui label">Systolic</div>
			  						<input type="text" name="posthd_systolic" id="posthd_systolic" value="" class="purplebg" required>
			  					</div>
			  					<div class="ui labeled input">
			  						<div class="ui label">Diastolic</div>
			  						<input type="text" name="posthd_diastolic" id="posthd_diastolic" value="" class="purplebg" required>
			  					</div>
			  				</div>
			  		</div>
			  	</div>
		      
		    	<div class="seven wide field">
					<div class="clinic_code">
						<label style="">T.P.R:</label>
		  				<div class="three fields" style="padding-left:5px">
								<div class="ui right labeled input">
									<input type="text" placeholder="Temperature" name="posthd_temperatue" id="posthd_temperatue" value="" class="purplebg" required>
									<div class="ui basic label mylabel" style="margin-right: 5px;">°C</div>
								</div>
								<div class="ui right labeled input">
	  							<input type="text" placeholder="Pulse" name="posthd_pulse" id="posthd_pulse" value="" class="purplebg" required>
									<div class="ui basic label mylabel" style="margin-right: 5px;">bpm</div>
								</div>
								<div class="ui right labeled input">
									<input type="text" placeholder="Respiratory" name="posthd_respiratory" id="posthd_respiratory" value="" class="purplebg" required>
									<div class="ui basic label mylabel">/minute</div>
								</div>
		  				</div>
					</div>
				</div>
		    	<div class="field"></div>
			</div>

			<div class="five fields">
				<div class="field">
					<div class="clinic_code">
						<label><div class="label_hd">TIME COMPLETE:</div></label>
						<input type="time" name="time_complete" id="time_complete" value="" class="purplebg" required>
					</div>
				</div>
			    <div class="field">
					<div class="clinic_code">
						<label> POST WEIGHT:</label>
						<div class="ui right labeled input">
							<input type="text" name="post_weight" id="post_weight" value="" class="purplebg" required>
							<div class="ui basic label mylabel">KG</div>
						</div>
					</div>
			    </div>

			    <div class="field">
					<div class="clinic_code">
						<label> WEIGHT LOSS:</label>
						<div class="ui right labeled input">
							<input type="text" name="weight_loss" id="weight_loss" value="" class="purplebg" required>
							<div class="ui basic label mylabel">KG</div>
						</div>
					</div>
			    </div>

			    <div class="field">
					<label> INTRADIALYTIC COMPLICATION:</label>
					<select class="ui selection dropdown purplebg" id="i_complication" name="i_complication" required>
					  <option value="">Select Here</option>
					  <option value="HYPOTENSION">HYPOTENSION</option>
					  <option value="PYROGENIC REACTION">PYROGENIC REACTION</option>
					  <option value="AIR EMBOLISM">AIR EMBOLISM</option>
					  <option value="MUSCLE CRAMPS">MUSCLE CRAMPS</option>
					  <option value="FIRST USE SYNDROME">FIRST USE SYNDROME</option>
					  <option value="CHEST PAIN">CHEST PAIN</option>
					  <option value="BLEEDING">BLEEDING </option>
					  <option value="HEMOLYSIS">HEMOLYSIS </option>
					  <option value="DISEQUILIBRIUM SYNDROME">DISEQUILIBRIUM SYNDROME</option>
					  <option value="SEIZURE">SEIZURE</option>
					  <option value="TECHNICAL">TECHNICAL</option>
					  <option value="N/A">N/A</option>
					</select>
			    </div>

		    	<div class="field"></div>
			</div>

			<div class="five fields">
			    <div class="field">
					<div class="clinic_code">
						<label> DELIVERED DURATION:</label>
						<input type="text" name="delivered_duration" id="delivered_duration" value="" class="purplebg" rdonly >
						<span id="delivered_duration_errortext" class="error"></span>
					</div>
			    </div>
			    <div class="field">
					<div class="clinic_code">
						<label> HD ADEQUANCY:</label>
						<input type="text" name="hd_adequancy" id="hd_adequancy" value="" class="purplebg" required>
					</div>
			    </div>

			    <div class="field">
					<div class="clinic_code">
						<label> KT/V :</label>
						<input type="text" name="ktv" id="ktv" value="" class="purplebg" required>
					</div>
			    </div>

			    <div class="field">
					<div class="clinic_code">
						<label> URR % :</label>
						<input type="text" name="urr" id="urr" value="" class="purplebg" required>
					</div>
			    </div>

			    <div class="field">
					<div class="clinic_code">
						<label> TERMINATE BY:</label>
						<input type="text" name="terminate_by" id="terminate_by" value="" class="purplebg" rdonly>
					</div>
			    </div>
			    <div class="field"></div>
		    	<div class="field" style="position:relative;"><button id="complete_dialysis" class="ui red button" type="button" style="position:absolute; bottom: 0px;right: 20px;" disabled>Completed</button></div>
			</div>
		</form>
	</div>
</div>

<div class="ui mini modal scrolling" id="password_mdl">
  <i class="close icon" style="position: inherit;color: black;"></i>
  <div class="content">
    <form class="ui form" id="verify_form" autocomplete="off">
	  <div class="field">
	    <label>Username</label>
	    <input type="text" name="username_verify" id="username_verify" placeholder="Username" required autocomplete="off">
	  </div>
	  <div class="field">
	    <label>Password</label>
	    <input type="password" name="password_verify" id="password_verify" placeholder="Password" required autocomplete="off">
	  </div>
	  <button class="ui primary button" type="button" id="verify_btn">VERIFIED</button>
	  <div class="ui red left basic label" id="verify_error" style="display: none;">Username or password wrong</div>
	</form>
  </div>
</div>