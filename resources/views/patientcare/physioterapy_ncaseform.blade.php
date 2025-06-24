
<div class="ui secondary segment bluecloudsegment" id="rehab_segment" style="
    padding: 30px !important; ">

	<div class="inline field" style="position:absolute;top: 10px; left: 10px;">
		<div class="ui checkbox rehab box">
	      <input type="checkbox" class="hidden" name="Rehabilitation" value="Rehabilitation">
	      <label>Rehabilitation</label>
	    </div>
		<div class="ui checkbox phys box">
	      <input type="checkbox" class="hidden" name="Physioteraphy" value="Physioteraphy">
	      <label>Physioteraphy</label>
	    </div>

		<div class="ui checkbox toggle right aligned referdiet box" style="padding-left:80px">
		  <input type="checkbox" class="hidden" name="referdiet" value="referdiet">
		  <label>Refer to Dietician</label>
		</div>

		<a class="ui orange disabled label" id="stats_rehab" style="display: none;"></a>
		<a class="ui orange disabled label" id="stats_physio" style="display: none;"></a>
	</div>

	<div class="ui small blue icon buttons" id="btn_grp_edit_phys_ncase" style="position: absolute;
				padding: 0 0 0 0;
				right: 40px;
				top: 14px;
				z-index: 2;">
	  <button class="ui button" id="new_phys_ncase"><span class="fa fa-plus-square-o"></span> New</button>
	  <button class="ui button" id="edit_phys_ncase"><span class="fa fa-edit fa-lg"></span> Edit</button>
	  <button class="ui button" id="save_phys_ncase"><span class="fa fa-save fa-lg"></span> Save</button>
	  <button class="ui button" id="cancel_phys_ncase"><span class="fa fa-ban fa-lg"></span> Cancel</button>
	</div>

	<!-- <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
		id="btn_grp_edit_phys_ncase"
		style="position: absolute;
				padding: 0 0 0 0;
				right: 40px;
				top: 10px;
				z-index: 2;">
		<button type="button" class="btn btn-default" id="new_phys_ncase">
			<span class="fa fa-plus-square-o"></span> New
		</button>
		<button type="button" class="btn btn-default" id="edit_phys_ncase">
			<span class="fa fa-edit fa-lg"></span> Edit
		</button>
		<button type="button" class="btn btn-default" data-oper='add' id="save_phys_ncase">
			<span class="fa fa-save fa-lg"></span> Save
		</button>
		<button type="button" class="btn btn-default" id="cancel_phys_ncase">
			<span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
		</button>
	</div> -->
</div>

<div class="ui segment">
	<form id="formphys_ncase">
		<input id="referdiet_ncase" name="referdiet" type="hidden" value="no">
		<input id="category_phys_ncase" name="category" type="hidden">
		
		<div class="ui segments">
			<div class="ui secondary segment collapsed" data-toggle="collapse" data-target="#phys_mcond">
				<i class="angle down icon large"></i>
				<i class="angle up icon large"></i>
				<h4 style="text-align:center;margin-top:3px">MEDICAL CONDITION</h4>
			</div>
			<div class="ui segment collapse" id="phys_mcond">
				<div class="ui form">
					<table class="ui celled table">
					  	<thead>
						    <tr>
						    	<th width="5%">No.</th>
						    	<th width="60%">Medical Condition</th>
						    	<th width="12%">Answer</th>
						    	<th width="28%">Details</th>
						  	</tr>
						</thead>
					  	<tbody>
							<tr>
								<td data-label="no">1</td>
								<td data-label="con">Have you ever had a heart attack, coronary revascularization surgery or a stroke</td>
								<td data-label="ans">
									<div class="inline fields">
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques1" checked="checked" value='no'>
												<label>No</label>
											</div>
										</div>
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques1" value='yes'>
												<label>Yes</label>
											</div>
										</div>
									</div>
								</td>
								<td data-label="det">
									<div class="field">
										<input type="text" name="quesdet1" placeholder="Details">
									</div>
								</td>
							</tr>
							<tr>
								<td data-label="no">2</td>
								<td data-label="con">Has your doctor ever told you have heart trouble or vascular disease</td>
								<td data-label="ans">
									<div class="inline fields">
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques2" checked="checked" value='no'>
												<label>No</label>
											</div>
										</div>
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques2" value='yes'>
												<label>Yes</label>
											</div>
										</div>
									</div>
								</td>
								<td data-label="det">
									<div class="field">
										<input type="text" name="quesdet2" placeholder="Details">
									</div>
								</td>
							</tr>
							<tr>
								<td data-label="no">3</td>
								<td data-label="con">Has your doctor ever told that you have heart murmur</td>
								<td data-label="ans">
									<div class="inline fields">
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques3" checked="checked" value='no'>
												<label>No</label>
											</div>
										</div>
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques3" value='yes'>
												<label>Yes</label>
											</div>
										</div>
									</div>
								</td>
								<td data-label="det">
									<div class="field">
										<input type="text" name="quesdet3" placeholder="Details">
									</div>
								</td>
							</tr>
							<tr>
								<td data-label="no">4</td>
								<td data-label="con">Do you suffer from pains in your chest especially with exercise</td>
								<td data-label="ans">
									<div class="inline fields">
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques4" checked="checked" value='no'>
												<label>No</label>
											</div>
										</div>
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques4" value='yes'>
												<label>Yes</label>
											</div>
										</div>
									</div>
								</td>
								<td data-label="det">
									<div class="field">
										<input type="text" name="quesdet4" placeholder="Details">
									</div>
								</td>
							</tr>
							<tr>
								<td data-label="no">5</td>
								<td data-label="con">Do you ever get pains in your calves, buttock or at the back of your legs during exercise which are not due to soreness or stiffness.</td>
								<td data-label="ans">
									<div class="inline fields">
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques5" checked="checked" value='no'>
												<label>No</label>
											</div>
										</div>
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques5" value='yes'>
												<label>Yes</label>
											</div>
										</div>
									</div>
								</td>
								<td data-label="det">
									<div class="field">
										<input type="text" name="quesdet5" placeholder="Details">
									</div>
								</td>
							</tr>
							<tr>
								<td data-label="no">6</td>
								<td data-label="con">Do you ever feel faint or have spells of severe dizziness, particularly with exercise?</td>
								<td data-label="ans">
									<div class="inline fields">
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques6" checked="checked" value='no'>
												<label>No</label>
											</div>
										</div>
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques6" value='yes'>
												<label>Yes</label>
											</div>
										</div>
									</div>
								</td>
								<td data-label="det">
									<div class="field">
										<input type="text" name="quesdet6" placeholder="Details">
									</div>
								</td>
							</tr>
							<tr>
								<td data-label="no">7</td>
								<td data-label="con">Do you experience swelling or accumulation of fluid about the ankles?</td>
								<td data-label="ans">
									<div class="inline fields">
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques7" checked="checked" value='no'>
												<label>No</label>
											</div>
										</div>
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques7" value='yes'>
												<label>Yes</label>
											</div>
										</div>
									</div>
								</td>
								<td data-label="det">
									<div class="field">
										<input type="text" name="quesdet7" placeholder="Details">
									</div>
								</td>
							</tr>
							<tr>
								<td data-label="no">8</td>
								<td data-label="con">Do you ever get feeling that your heart is suddenly beating faster, racing or skipping beats, either at rest or during physical activity.</td>
								<td data-label="ans">
									<div class="inline fields">
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques8" checked="checked" value='no'>
												<label>No</label>
											</div>
										</div>
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques8" value='yes'>
												<label>Yes</label>
											</div>
										</div>
									</div>
								</td>
								<td data-label="det">
									<div class="field">
										<input type="text" name="quesdet8" placeholder="Details">
									</div>
								</td>
							</tr>
							<tr>
								<td data-label="no">9</td>
								<td data-label="con">Do you have chronic obstructive pulmonary disease, interstitial lung disease or cystic fibrosis?</td>
								<td data-label="ans">
									<div class="inline fields">
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques9" checked="checked" value='no'>
												<label>No</label>
											</div>
										</div>
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques9" value='yes'>
												<label>Yes</label>
											</div>
										</div>
									</div>
								</td>
								<td data-label="det">
									<div class="field">
										<input type="text" name="quesdet9" placeholder="Details">
									</div>
								</td>
							</tr>
							<tr>
								<td data-label="no">10</td>
								<td data-label="con">Have you ever had an attack of shortness of breath that developed after you were not doing anything strenuous, at any time in the last 12 months?</td>
								<td data-label="ans">
									<div class="inline fields">
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques10" checked="checked" value='no'>
												<label>No</label>
											</div>
										</div>
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques10" value='yes'>
												<label>Yes</label>
											</div>
										</div>
									</div>
								</td>
								<td data-label="det">
									<div class="field">
										<input type="text" name="quesdet10" placeholder="Details">
									</div>
								</td>
							</tr>
							<tr>
								<td data-label="no">11</td>
								<td data-label="con">Have you ever had an attack of shortness breath developed after you stopped exercising, at any time in the last 12 months?</td>
								<td data-label="ans">
									<div class="inline fields">
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques11" checked="checked" value='no'>
												<label>No</label>
											</div>
										</div>
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques11" value='yes'>
												<label>Yes</label>
											</div>
										</div>
									</div>
								</td>
								<td data-label="det">
									<div class="field">
										<input type="text" name="quesdet11" placeholder="Details">
									</div>
								</td>
							</tr>
							<tr>
								<td data-label="no">12</td>
								<td data-label="con">Have you ever been woken at night by an attack of shortness of breath at any time in the last 12 months?</td>
								<td data-label="ans">
									<div class="inline fields">
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques12" checked="checked" value='no'>
												<label>No</label>
											</div>
										</div>
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques12" value='yes'>
												<label>Yes</label>
											</div>
										</div>
									</div>
								</td>
								<td data-label="det">
									<div class="field">
										<input type="text" name="quesdet12" placeholder="Details">
									</div>
								</td>
							</tr>
							<tr>
								<td data-label="no">13</td>
								<td data-label="con">Do you have diabetes? If do you have trouble controlling diabetes?</td>
								<td data-label="ans">
									<div class="inline fields">
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques13" checked="checked" value='no'>
												<label>No</label>
											</div>
										</div>
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques13" value='yes'>
												<label>Yes</label>
											</div>
										</div>
									</div>
								</td>
								<td data-label="det">
									<div class="field">
										<input type="text" name="quesdet13" placeholder="Details">
									</div>
								</td>
							</tr>
							<tr>
								<td data-label="no">14</td>
								<td data-label="con">Do you have any ulcerated wound or cuts on your feet that do not seem to heal?</td>
								<td data-label="ans">
									<div class="inline fields">
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques14" checked="checked" value='no'>
												<label>No</label>
											</div>
										</div>
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques14" value='yes'>
												<label>Yes</label>
											</div>
										</div>
									</div>
								</td>
								<td data-label="det">
									<div class="field">
										<input type="text" name="quesdet14" placeholder="Details">
									</div>
								</td>
							</tr>
							<tr>
								<td data-label="no">15</td>
								<td data-label="con">Do you have any liver, kidney or thyroid disorder?</td>
								<td data-label="ans">
									<div class="inline fields">
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques15" checked="checked" value='no'>
												<label>No</label>
											</div>
										</div>
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques15" value='yes'>
												<label>Yes</label>
											</div>
										</div>
									</div>
								</td>
								<td data-label="det">
									<div class="field">
										<input type="text" name="quesdet15" placeholder="Details">
									</div>
								</td>
							</tr>
							<tr>
								<td data-label="no">16</td>
								<td data-label="con">Do you experience unusual fatigue or shortness of breath with usual activities</td>
								<td data-label="ans">
									<div class="inline fields">
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques16" checked="checked" value='no'>
												<label>No</label>
											</div>
										</div>
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques16" value='yes'>
												<label>Yes</label>
											</div>
										</div>
									</div>
								</td>
								<td data-label="det">
									<div class="field">
										<input type="text" name="quesdet16" placeholder="Details">
									</div>
								</td>
							</tr>
							<tr>
								<td data-label="no">17</td>
								<td data-label="con">Is there any other physical reason or medical condition, or are you taking any medication which could prevent you from undertaking an exercise program?</td>
								<td data-label="ans">
									<div class="inline fields">
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques17" checked="checked" value='no'>
												<label>No</label>
											</div>
										</div>
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques17" value='yes'>
												<label>Yes</label>
											</div>
										</div>
									</div>
								</td>
								<td data-label="det">
									<div class="field">
										<input type="text" name="quesdet17" placeholder="Details">
									</div>
								</td>
							</tr>
							<tr>
								<td data-label="no">18</td>
								<td data-label="con">Do you have smoke cigarettes regularly or have you quit smoking in the last 6 months?</td>
								<td data-label="ans">
									<div class="inline fields">
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques18" checked="checked" value='no'>
												<label>No</label>
											</div>
										</div>
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques18" value='yes'>
												<label>Yes</label>
											</div>
										</div>
									</div>
								</td>
								<td data-label="det">
									<div class="field">
										<input type="text" name="quesdet18" placeholder="Details">
									</div>
								</td>
							</tr>
							<tr>
								<td data-label="no">19</td>
								<td data-label="con">Do you have a family history of cardiovascular disease? (This refers to a primary relative i.e parent, child or sibling who has had a myocardial infarction, coronary revascularization or died suddenly due to heart attack before age of 55 years (male) or 65 years (Female).</td>
								<td data-label="ans">
									<div class="inline fields">
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques19" checked="checked" value='no'>
												<label>No</label>
											</div>
										</div>
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques19" value='yes'>
												<label>Yes</label>
											</div>
										</div>
									</div>
								</td>
								<td data-label="det">
									<div class="field">
										<input type="text" name="quesdet19" placeholder="Details">
									</div>
								</td>
							</tr>
							<tr>
								<td data-label="no">20</td>
								<td data-label="con">Have you been told you have a systolic blood pressure measure at greater 140mmHg on two separate occasion OR a diastolic blood pressure measure at greater than 90 mmHg? Or you are taking anti-hypertensive medication? </td>
								<td data-label="ans">
									<div class="inline fields">
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques20" checked="checked" value='no'>
												<label>No</label>
											</div>
										</div>
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques20" value='yes'>
												<label>Yes</label>
											</div>
										</div>
									</div>
								</td>
								<td data-label="det">
									<div class="field">
										<input type="text" name="quesdet20" placeholder="Details">
									</div>
								</td>
							</tr>
							<tr>
								<td data-label="no">21</td>
								<td data-label="con">Have you been told you have impaired fasting glucose? (Fasting glucose less than 6.1 mmol/L on two separate occasions).</td>
								<td data-label="ans">
									<div class="inline fields">
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques21" checked="checked" value='no'>
												<label>No</label>
											</div>
										</div>
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques21" value='yes'>
												<label>Yes</label>
											</div>
										</div>
									</div>
								</td>
								<td data-label="det">
									<div class="field">
										<input type="text" name="quesdet21" placeholder="Details">
									</div>
								</td>
							</tr>
							<tr>
								<td data-label="no">22</td>
								<td data-label="con">Have you ever been told that you have a total serum cholesterol concentration of greater than 5.2mmol/L or a high-density lipoprotein concentration of less than 0.9mmol/L? or you on lipid lowering medication? </td>
								<td data-label="ans">
									<div class="inline fields">
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques22" checked="checked" value='no'>
												<label>No</label>
											</div>
										</div>
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques22" value='yes'>
												<label>Yes</label>
											</div>
										</div>
									</div>
								</td>
								<td data-label="det">
									<div class="field">
										<input type="text" name="quesdet22" placeholder="Details">
									</div>
								</td>
							</tr>
							<tr>
								<td data-label="no">23</td>
								<td data-label="con">Do you have an occupation where you are seated for long periods of time AND do no regular exercise? (Regular exercise defined as more than 150 minutes of moderate intensity physical activity per week).</td>
								<td data-label="ans">
									<div class="inline fields">
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques23" checked="checked" value='no'>
												<label>No</label>
											</div>
										</div>
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques23" value='yes'>
												<label>Yes</label>
											</div>
										</div>
									</div>
								</td>
								<td data-label="det">
									<div class="field">
										<input type="text" name="quesdet23" placeholder="Details">
									</div>
								</td>
							</tr>
							<tr>
								<td data-label="no">24</td>
								<td data-label="con">Section Fill Up by Exercise Physiologist / Personal Trainer Does the client have a BMI greater than 30 kg/m2 or a waist girth greater than 100cm?</td>
								<td data-label="ans">
									<div class="inline fields">
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques24" checked="checked" value='no'>
												<label>No</label>
											</div>
										</div>
										<div class="field">
											<div class="ui radio checkbox">
												<input type="radio" name="ques24" value='yes'>
												<label>Yes</label>
											</div>
										</div>
									</div>
								</td>
								<td data-label="det">
									<div class="field">
										<input type="text" name="quesdet24" placeholder="Details">
									</div>
								</td>
							</tr>
					  	</tbody>
					</table>

					<input id="risk_phys_ncase" name="risk" type="hidden">
					<div class="three ui buttons">
					  <button class="ui toggle button low" type="button" data-risk='low'>Low Risk</button>
					  <button class="ui toggle button moderate" type="button" data-risk='moderate'>Moderate Risk</button>
					  <button class="ui toggle button high" type="button" data-risk='high'>High Risk</button>
					</div>
				</div>
			</div>
		</div>
		
		<div class="ui segments">
			<div class="ui secondary segment collapsed" data-toggle="collapse" data-target="#phys_hist">
				<i class="angle down icon large"></i>
				<i class="angle up icon large"></i> 
				<h4 style="text-align:center;margin-top:3px">HISTORY</h4>
			</div>
			<div class="ui segment collapse" id="phys_hist">
				<div class="ui form">
					<div class="field"><textarea rows="6" cols="50" name="history" ></textarea></div>
				</div>
			</div>
		</div>
		
		<div class="ui segments">
			<div class="ui secondary segment collapsed" data-toggle="collapse" data-target="#phys_post_rehab">
				<i class="angle down icon large"></i>
				<i class="angle up icon large"></i>
				<!-- <h4 style="text-align:center;margin-top:3px">POSTURAL ASSESSMENT (REHAB)</h4> -->
				<h4 style="text-align:center;margin-top:3px">PAIN BEHAVIOUR</h4>
			</div>
			<div class="ui segment collapse" id="phys_post_rehab">
				<div class="ui grid">
					<div class="thirteen wide column">
						<div class="ui four cards" >
							<a class="ui card bodydia_ncase" data-type='BF_REHAB'>
								<div class="image">
							      <img src="{{ asset('patientcare/img/bodydia1.png') }}" >
							    </div>
							</a>
							<a class="ui card bodydia_ncase" data-type='BR_REHAB'>
								<div class="image">
							      <img src="{{ asset('patientcare/img/bodydia2.png') }}">
							    </div>
							</a>
							<a class="ui card bodydia_ncase" data-type='BL_REHAB'>
								<div class="image">
							      <img src="{{ asset('patientcare/img/bodydia3.png') }}">
							    </div>
							</a>
							<a class="ui card bodydia_ncase" data-type='BB_REHAB'>
								<div class="image">
							      <img src="{{ asset('patientcare/img/bodydia4.png') }}">
							    </div>
							</a>
						</div>
					</div>
					
					<div class="three wide column">
						<div class="ui form">
							<div class="field">
							    <label>VAS</label>
							    <input type="text" name="vas_ncase" placeholder="VAS">
							</div>
							<div class="field">
							    <label>Aggravating Factors</label>
							    <input type="text" name="aggr_ncase" placeholder="Aggravating Factors">
							</div>
							<div class="field">
							    <label>Easing Factors</label>
							    <input type="text" name="easing_ncase" placeholder="Easing Factors">
							</div>
							<div class="field">
							    <label>Type Of Pain</label>
							    <input type="text" name="pain_ncase" placeholder="Type Of Pain">
							</div>
							<div class="field">
							    <label>24 Hours Behaviour</label>
							    <input type="text" name="behaviour_ncase" placeholder="24 Hours Behaviour">
							</div>
							<div class="field">
							    <label>Irritability</label>
							    <input type="text" name="irritability_ncase" placeholder="Irritability">
							</div>
							<div class="field">
							    <label>Severity</label>
							    <input type="text" name="severity_ncase" placeholder="Severity">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="ui segments" style="display: none;">
			<div class="ui secondary segment collapsed" data-toggle="collapse" data-target="#phys_post_physio">
				<i class="angle down icon large"></i>
				<i class="angle up icon large"></i>
				<h4 style="text-align:center;margin-top:3px">POSTURAL ASSESSMENT (PHYSIO)</h4>
			</div>
			<div class="ui segment collapse" id="phys_post_physio">
				@include('rehab.posturalAssessmt_div')
			</div>
		</div>
		
		<div class="ui segments" style="display: none;">
			<div class="ui secondary segment collapsed" data-toggle="collapse" data-target="#phys_elec">
				<i class="angle down icon large"></i>
				<i class="angle up icon large"></i>
				<h4 style="text-align:center;margin-top:3px">ELECTROCARDIOGRAM (EKG) NOTES</h4>
			</div>
			<div class="ui segment collapse" id="phys_elec">
				<div class="ui form">
					<div class="field">
						<label>ECG Interpretation</label>
						<textarea rows="6" cols="50" name="electrodg" ></textarea>
					</div>
					
					<p>Stress Test Interpretation</p>
					<div class="inline field">
						<label>Protocol</label>
						<input type="text" name="protocol" placeholder="Protocol">
						<label style="padding-left:20px">Equipment</label>
						<input type="text" name="equipment" placeholder="Equipment">
					</div>
					
					<div class="field">
						<label>Recommendation</label>
						<textarea rows="6" cols="50" name="recommendation" ></textarea>
					</div>
				</div>
			</div>
		</div>
		
		<div class="ui segments">
			<div class="ui secondary segment collapsed" data-toggle="collapse" data-target="#phys_findings">
				<i class="angle down icon large"></i>
				<i class="angle up icon large"></i> 
				<h4 style="text-align: center; margin-top: 3px;">RELEVANT FINDING(S)</h4>
			</div>
			<div class="ui segment collapse" id="phys_findings">
				<div class="ui form">
					<div class="field"><textarea rows="6" cols="50" name="findings"></textarea></div>
				</div>
			</div>
		</div>
		
		<div class="ui segments">
			<div class="ui secondary segment collapsed" data-toggle="collapse" data-target="#phys_treatment">
				<i class="angle down icon large"></i>
				<i class="angle up icon large"></i> 
				<h4 style="text-align: center; margin-top: 3px;">TREATMENT</h4>
			</div>
			<div class="ui segment collapse" id="phys_treatment">
				<div class="ui form ui grid">
					<div class="five wide column grouped fields">
						<div class="sixteen wide column field">
							<div class="ui checkbox">
								<input type="checkbox" name="tr_physio" id="Physio_tr_physio" value="1">
								<label for="Physio_tr_physio">Physiotherapy</label>
							</div>
						</div>
						<div class="sixteen wide column field">
							<div class="ui checkbox">
								<input type="checkbox" name="tr_occuptherapy" id="Physio_tr_occuptherapy" value="1">
								<label for="Physio_tr_occuptherapy">Occupational Therapy</label>
							</div>
						</div>
					</div>
					<div class="five wide column grouped fields">
						<div class="sixteen wide column field">
							<div class="ui checkbox">
								<input type="checkbox" name="tr_respiphysio" id="Physio_tr_respiphysio" value="1">
								<label for="Physio_tr_respiphysio">Respiratory Physiotherapy</label>
							</div>
						</div>
						<div class="sixteen wide column field">
							<div class="ui checkbox">
								<input type="checkbox" name="tr_neuro" id="Physio_tr_neuro" value="1">
								<label for="Physio_tr_neuro">Neuro Rehab</label>
							</div>
						</div>
					</div>
					<div class="five wide column grouped fields">
						<div class="sixteen wide column field">
							<div class="ui checkbox">
								<input type="checkbox" name="tr_splint" id="Physio_tr_splint" value="1">
								<label for="Physio_tr_splint">Splinting</label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>