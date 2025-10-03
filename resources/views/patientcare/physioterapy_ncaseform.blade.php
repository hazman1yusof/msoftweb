<div class="ui secondary segment bluecloudsegment" id="rehab_segment" style="padding: 30px !important;">
	<div class="inline field" style="position: absolute; top: 10px; left: 10px;">
		<div class="ui checkbox rehab box">
			<input type="checkbox" class="hidden" name="Rehabilitation" value="Rehabilitation">
			<label>Rehabilitation</label>
		</div>
		<div class="ui checkbox phys box">
			<input type="checkbox" class="hidden" name="Physioteraphy" value="Physioteraphy">
			<label>Physioteraphy</label>
		</div>
		
		<div class="ui checkbox toggle right aligned referdiet box" style="padding-left: 80px;">
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
		<button class="ui button" id="new_phys_ncase"><span class="fa fa-plus-square-o"></span>New</button>
		<button class="ui button" id="edit_phys_ncase"><span class="fa fa-edit fa-lg"></span>Edit</button>
		<button class="ui button" id="save_phys_ncase"><span class="fa fa-save fa-lg"></span>Save</button>
		<button class="ui button" id="cancel_phys_ncase"><span class="fa fa-ban fa-lg"></span>Cancel</button>
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
		
		<div id="physioNcaseTabs" class="ui segment">
			<div class="ui top attached tabular menu">
				<a class="item active" data-tab="physMedCond" id="navtab_physMedCond">MEDICAL CONDITION</a>
				<a class="item" data-tab="physHistory" id="navtab_physHistory">HISTORY</a>
				<a class="item" data-tab="physPainBehaviour" id="navtab_physPainBehaviour">PAIN BEHAVIOUR</a>
				<a class="item" data-tab="physRelFind" id="navtab_physRelFind">RELEVANT FINDING(S)</a>
				<a class="item" data-tab="physTreatment" id="navtab_physTreatment">TREATMENT</a>
				<a class="item" data-tab="physPerkeso" id="navtab_physPerkeso">PERKESO</a>
				<a class="item" data-tab="physNotes" id="navtab_physNotes">NOTES</a>
			</div>
			
			<div class="ui bottom attached tab raised segment active" data-tab="physMedCond">
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
			
			<div class="ui bottom attached tab raised segment" data-tab="physHistory">
				<div class="ui form">
					<div class="field"><textarea rows="6" cols="50" name="history"></textarea></div>
				</div>
			</div>
			
			<div class="ui bottom attached tab raised segment" data-tab="physPainBehaviour">
				<div class="ui grid">
					<div class="thirteen wide column">
						<div class="ui four cards">
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
			
			<div class="ui bottom attached tab raised segment" data-tab="physRelFind">
				<div class="ui form">
					<div class="field"><textarea rows="6" cols="50" name="findings"></textarea></div>
				</div>
			</div>
			
			<div class="ui bottom attached tab raised segment" data-tab="physTreatment">
				<div class="ui form ui grid">
					<div class='ui grid' style="padding: 15px 30px;">
						<div class="four wide column" style="padding: 14px 14px 0px 150px;">
							<div class="field">
								<label>Date</label>
							</div>
						</div>
						
						<div class="twelve wide column" style="padding: 14px 14px 0px 14px;">
							<div class="field eight wide column">
								<input id="Physio_req_date" name="req_date" type="date" rdonly>
							</div>
						</div>
						
						<div class="four wide column" style="padding: 14px 14px 0px 150px;">
							<div class="field">
								<label>Clinical Diagnosis</label>
							</div>
						</div>
						
						<div class="twelve wide column" style="padding: 14px 14px 0px 14px;">
							<div class="field eight wide column">
								<textarea id="Physio_clinic_diag" name="clinic_diag" type="text" rows="5" readonly="" disabled></textarea>
							</div>
						</div>
						
						<div class="four wide column" style="padding: 14px 14px 0px 150px;">
							<div class="field">
								<label>Relevant Finding(s)</label>
							</div>
						</div>
						
						<div class="twelve wide column" style="padding: 14px 14px 0px 14px;">
							<div class="field eight wide column">
								<textarea id="Physio_findings" name="findings" type="text" rows="5" readonly="" disabled></textarea>
							</div>
						</div>
						
						<div class="three wide column" style="padding: 14px 14px 0px 150px;">
							<div class="field">
								<label>Treatment</label>
							</div>
						</div>
						
						<div class="thirteen wide column" style="padding: 14px 14px 0px 30px;">
							<div class="field eight wide column">
								<!-- <textarea id="phyPhysio_treatment" name="phy_treatment" type="text" rows="5"></textarea> -->
								<div class="ui form">
									<div class="grouped fields">
										<div class="field">
											<div class="ui read-only checkbox">
												<input type="checkbox" name="tr_physio" id="Physio_tr_physio" value="1">
												<label for="Physio_tr_physio">Physiotherapy</label>
											</div>
										</div>
										<div class="field">
											<div class="ui read-only checkbox">
												<input type="checkbox" name="tr_occuptherapy" id="Physio_tr_occuptherapy" value="1">
												<label for="Physio_tr_occuptherapy">Occupational Therapy</label>
											</div>
										</div>
										<div class="field">
											<div class="ui read-only checkbox">
												<input type="checkbox" name="tr_respiphysio" id="Physio_tr_respiphysio" value="1">
												<label for="Physio_tr_respiphysio">Respiratory Physiotherapy</label>
											</div>
										</div>
										<div class="field">
											<div class="ui read-only checkbox">
												<input type="checkbox" name="tr_neuro" id="Physio_tr_neuro" value="1">
												<label for="Physio_tr_neuro">Neuro Rehab</label>
											</div>
										</div>
										<div class="field">
											<div class="ui read-only checkbox">
												<input type="checkbox" name="tr_splint" id="Physio_tr_splint" value="1">
												<label for="Physio_tr_splint">Splinting</label>
											</div>
										</div>
										<div class="field">
											<div class="ui read-only checkbox">
												<input type="checkbox" name="tr_speech" id="Physio_tr_speech" value="1">
												<label for="Physio_tr_speech">Speech</label>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="four wide column" style="padding: 14px 14px 0px 150px;">
							<div class="field">
								<label>Remarks</label>
							</div>
						</div>
						
						<div class="twelve wide column" style="padding: 14px 14px 0px 14px;">
							<div class="field eight wide column">
								<textarea id="Physio_remarks" name="remarks" type="text" rows="5" readonly="" disabled></textarea>
							</div>
						</div>
						
						<div class="sixteen wide column centered grid" style="padding-left: 150px;">
							<div class="inline field">
								<label>Name of Requesting Doctor</label>
								<input id="phyPhysio_doctorname" name="phy_doctorname" type="text" style="width: 350px; text-transform: uppercase;" rdonly>
							</div>
							
							<div class="inline field">
								<label>Entered By</label>
								<input id="phyPhysio_lastuser" name="phy_lastuser" type="text" style="width: 350px; text-transform: uppercase;" rdonly>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="ui bottom attached tab raised segment" data-tab="physPerkeso">
				<div class="ui form">
					@include('patientcare.physioterapy_perkeso')
				</div>
			</div>
			
			<div class="ui bottom attached tab raised segment" data-tab="physNotes">
				<div class="ui form">
					<div class="field"><textarea rows="6" cols="50" name="addNotes"></textarea></div>
				</div>
			</div>
		</div>
		
		<!-- if nak guna balik tab ni, check if boleh overwrite data from physiotherapy>postural assessment -->
		<div class="ui segments" style="display: none;">
			<div class="ui secondary segment collapsed" data-toggle="collapse" data-target="#phys_post_physio">
				<i class="angle down icon large"></i>
				<i class="angle up icon large"></i>
				<h4 style="text-align: center; margin-top: 3px;">POSTURAL ASSESSMENT (PHYSIO)</h4>
			</div>
			<div class="ui segment collapse" id="phys_post_physio">
				<div class="ui grid">
					<div class="eight wide column">
						<div class="ui segments">
							<div class="ui secondary segment">Anterior & Posterior View</div>
							<div class="ui segment">
								<table class="table;border border-white">
									<thead>
										<tr>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;" colspan="2">
												<i>Tick where seen & refer to Movement Management Plan</i>
											</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"></td>
										</tr>
										<tr>
											<th style="margin: 0px; padding: 3px 14px 14px 14px;">Lower Body</th>
											<th style="margin: 0px; padding: 3px 14px 14px 14px;"></th>
											<th style="margin: 0px; padding: 3px 14px 14px 14px;">L</th>
											<th style="margin: 0px; padding: 3px 14px 14px 14px;">R</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td style="margin: 0px; padding: 3px 14px 14px 14px; font-weight: bold;">Foot & ankle complex</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">Toe - Out</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_FACToeOutL" name="FACToeOutL" value="1"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_FACToeOutR" name="FACToeOutR" value="1"></td>
										</tr>
										<tr>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">Toe - In</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_FACToeInL" name="FACToeInL" value="1"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_FACToeInR" name="FACToeInR" value="1"></td>
										</tr>
										<tr>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">Pronation</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_FACPronationL" name="FACPronationL" value="1"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_FACPronationR" name="FACPronationR" value="1"></td>
										</tr>
										<tr>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">Flat Feet</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_FACFlatFeetL" name="FACFlatFeetL" value="1"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_FACFlatFeetR" name="FACFlatFeetR" value="1"></td>
										</tr>
										<tr>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">High Arch</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_FACHighArchL" name="FACHighArchL" value="1"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_FACHighArchR" name="FACHighArchR" value="1"></td>
										</tr>
										<tr>
											<td style="margin: 0px; padding: 3px 14px 14px 14px; font-weight: bold;">Knee/Hip</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">Knock Knees</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_KHKnockKneesL" name="KHKnockKneesL" value="1"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_KHKnockKneesR" name="KHKnockKneesR" value="1"></td>
										</tr>
										<tr>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">Bow Legs</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_KHBowLegsL" name="KHBowLegsL" value="1"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_KHBowLegsR" name="KHBowLegsR" value="1"></td>
										</tr>
									</tbody>
									<thead>
										<tr>
											<th style="margin: 0px; padding: 3px 14px 14px 14px;">Upper Body</th>
											<th style="margin: 0px; padding: 3px 14px 14px 14px;"></th>
											<th style="margin: 0px; padding: 3px 14px 14px 14px;">L</th>
											<th style="margin: 0px; padding: 3px 14px 14px 14px;">R</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td style="margin: 0px; padding: 3px 14px 14px 14px; font-weight: bold;">Spine</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">Scoliosis</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_spineScoliosisL" name="spineScoliosisL" value="1"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_spineScoliosisR" name="spineScoliosisR" value="1"></td>
										</tr>
										<tr>
											<td style="margin: 0px; padding: 3px 14px 14px 14px; font-weight: bold;">Scapula</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">Deviation</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_scapulaDeviationL" name="scapulaDeviationL" value="1"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_scapulaDeviationR" name="scapulaDeviationR" value="1"></td>
										</tr>
										<tr>
											<td style="margin: 0px; padding: 3px 14px 14px 14px; font-weight: bold;">Shoulder</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">Deviation</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_shoulderDeviationL" name="shoulderDeviationL" value="1"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_shoulderDeviationR" name="shoulderDeviationR" value="1"></td>
										</tr>
										<tr>
											<td style="margin: 0px; padding: 3px 14px 14px 14px; font-weight: bold;">Head</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">Tilt</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_headTiltL" name="headTiltL" value="1"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_headTiltR" name="headTiltR" value="1"></td>
										</tr>
										<tr>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">Rotation</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_headRotateL" name="headRotateL" value="1"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_headRotateR" name="headRotateR" value="1"></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					
					<div class="eight wide column">
						<div class="ui two cards">
							<a class="ui card bodydia_physio" data-type='BF_PHYSIO' style="height: 500px;">
								<div class="image">
									<img src="{{ asset('patientcare/img/bodydia5.png') }}" style="height: 500px;">
								</div>
							</a>
							<!-- <a class="ui card bodydia_physio" data-type='BR_PHYSIO'>
								<div class="image">
									<img src="{{ asset('patientcare/img/bodydia6.png') }}">
								</div>
							</a>
							<a class="ui card bodydia_physio" data-type='BL_PHYSIO'>
								<div class="image">
									<img src="{{ asset('patientcare/img/bodydia7.png') }}">
								</div>
							</a> -->
							<a class="ui card bodydia_physio" data-type='BB_PHYSIO' style="height: 500px;">
								<div class="image">
									<img src="{{ asset('patientcare/img/bodydia8.png') }}" style="height: 500px;">
								</div>
							</a>
						</div>
						
						<div class="ui form" style="padding-top: 10px;">
							<div class="field">
								<label>Comments</label>
								<textarea rows="6" cols="50" id="phys_anteriorPosteriorRmk" name="anteriorPosteriorRmk"></textarea>
							</div>
						</div>
					</div>
					
					<div class="eight wide column">
						<div class="ui segments">
							<div class="ui secondary segment">Lateral View</div>
							<div class="ui segment">
								<table class="table;border border-white">
									<thead>
										<tr>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;" colspan="2">
												<i>Tick where seen & refer to Movement Management Plan</i>
											</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"></td>
										</tr>
										<tr>
											<th style="margin: 0px; padding: 3px 14px 14px 14px;">Lower Body</th>
											<th style="margin: 0px; padding: 3px 14px 14px 14px;"></th>
											<th style="margin: 0px; padding: 3px 14px 14px 14px;">L</th>
											<th style="margin: 0px; padding: 3px 14px 14px 14px;">R</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td style="margin: 0px; padding: 3px 14px 14px 14px; font-weight: bold;">Ankle</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">Dorsiflexion</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_ankleDorsiflexL" name="ankleDorsiflexL" value="1"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_ankleDorsiflexR" name="ankleDorsiflexR" value="1"></td>
										</tr>
										<tr>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">Plantarflexion</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_anklePlantarL" name="anklePlantarL" value="1"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_anklePlantarR" name="anklePlantarR" value="1"></td>
										</tr>
										<tr>
											<td style="margin: 0px; padding: 3px 14px 14px 14px; font-weight: bold;">Knee</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">Flexed</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_kneeFlexedL" name="kneeFlexedL" value="1"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_kneeFlexedR" name="kneeFlexedR" value="1"></td>
										</tr>
										<tr>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">Hyperextended</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_kneeHyperextendL" name="kneeHyperextendL" value="1"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_kneeHyperextendR" name="kneeHyperextendR" value="1"></td>
										</tr>
										<tr>
											<td style="margin: 0px; padding: 3px 14px 14px 14px; font-weight: bold;">Pelvis</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">Anterior translation</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_pelvisAnterTransL" name="pelvisAnterTransL" value="1"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_pelvisAnterTransR" name="pelvisAnterTransR" value="1"></td>
										</tr>
									</tbody>
									<thead>
										<tr>
											<th style="margin: 0px; padding: 3px 14px 14px 14px;"></th>
											<th style="margin: 0px; padding: 3px 14px 14px 14px;"></th>
											<th style="margin: 0px; padding: 3px 14px 14px 14px;">Y</th>
											<th style="margin: 0px; padding: 3px 14px 14px 14px;">N</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">Is the deviation symmetrical?</td>
											<!-- <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_devSymmetryY" name="devSymmetryY" value="1"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_devSymmetryN" name="devSymmetryN" value="1"></td> -->
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">
												<!-- <label class="radio-inline"> -->
													<input type="radio" name="devSymmetry" value="1">
												<!-- </label> -->
											</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">
												<!-- <label class="radio-inline"> -->
													<input type="radio" name="devSymmetry" value="0">
												<!-- </label> -->
											</td>
										</tr>
										<tr>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">Tilt: Anterior</td>
											<!-- <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_tiltAnteriorY" name="tiltAnteriorY" value="1"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_tiltAnteriorN" name="tiltAnteriorN" value="1"></td> -->
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">
												<!-- <label class="radio-inline"> -->
													<input type="radio" name="tiltAnterior" value="1">
												<!-- </label> -->
											</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">
												<!-- <label class="radio-inline"> -->
													<input type="radio" name="tiltAnterior" value="0">
												<!-- </label> -->
											</td>
										</tr>
										<tr>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">Posterior</td>
											<!-- <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_tiltPosteriorY" name="tiltPosteriorY" value="1"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_tiltPosteriorN" name="tiltPosteriorN" value="1"></td> -->
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">
												<!-- <label class="radio-inline"> -->
													<input type="radio" name="tiltPosterior" value="1">
												<!-- </label> -->
											</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">
												<!-- <label class="radio-inline"> -->
													<input type="radio" name="tiltPosterior" value="0">
												<!-- </label> -->
											</td>
										</tr>
									</tbody>
									<thead>
										<tr>
											<th style="margin: 0px; padding: 3px 14px 14px 14px;">Upper Body</th>
											<th style="margin: 0px; padding: 3px 14px 14px 14px;"></th>
											<th style="margin: 0px; padding: 3px 14px 14px 14px;">Y</th>
											<th style="margin: 0px; padding: 3px 14px 14px 14px;">N</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td style="margin: 0px; padding: 3px 14px 14px 14px; font-weight: bold;">Lumbar spine</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">Lordosis</td>
											<!-- <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_LSLordosisY" name="LSLordosisY" value="1"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_LSLordosisN" name="LSLordosisN" value="1"></td> -->
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">
												<!-- <label class="radio-inline"> -->
													<input type="radio" name="LSLordosis" value="1">
												<!-- </label> -->
											</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">
												<!-- <label class="radio-inline"> -->
													<input type="radio" name="LSLordosis" value="0">
												<!-- </label> -->
											</td>
										</tr>
										<tr>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">Flat</td>
											<!-- <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_LSFlatY" name="LSFlatY" value="1"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_LSFlatN" name="LSFlatN" value="1"></td> -->
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">
												<!-- <label class="radio-inline"> -->
													<input type="radio" name="LSFlat" value="1">
												<!-- </label> -->
											</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">
												<!-- <label class="radio-inline"> -->
													<input type="radio" name="LSFlat" value="0">
												<!-- </label> -->
											</td>
										</tr>
										<tr>
											<td style="margin: 0px; padding: 3px 14px 14px 14px; font-weight: bold;">Thorac spine</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">Kyphosis</td>
											<!-- <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_TSKyphosisY" name="TSKyphosisY" value="1"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_TSKyphosisN" name="TSKyphosisN" value="1"></td> -->
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">
												<!-- <label class="radio-inline"> -->
													<input type="radio" name="TSKyphosis" value="1">
												<!-- </label> -->
											</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">
												<!-- <label class="radio-inline"> -->
													<input type="radio" name="TSKyphosis" value="0">
												<!-- </label> -->
											</td>
										</tr>
										<tr>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">Flat</td>
											<!-- <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_TSFlatY" name="TSFlatY" value="1"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_TSFlatN" name="TSFlatN" value="1"></td> -->
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">
												<!-- <label class="radio-inline"> -->
													<input type="radio" name="TSFlat" value="1">
												<!-- </label> -->
											</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">
												<!-- <label class="radio-inline"> -->
													<input type="radio" name="TSFlat" value="0">
												<!-- </label> -->
											</td>
										</tr>
										<tr>
											<td style="margin: 0px; padding: 3px 14px 14px 14px; font-weight: bold;">Trunk</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">Rotation (Symmetry)</td>
											<!-- <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_trunkRotationY" name="trunkRotationY" value="1"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_trunkRotationN" name="trunkRotationN" value="1"></td> -->
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">
												<!-- <label class="radio-inline"> -->
													<input type="radio" name="trunkRotation" value="1">
												<!-- </label> -->
											</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">
												<!-- <label class="radio-inline"> -->
													<input type="radio" name="trunkRotation" value="0">
												<!-- </label> -->
											</td>
										</tr>
										<tr>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"></td>
										</tr>
										<tr>
											<td style="margin: 0px; padding: 3px 14px 14px 14px; font-weight: bold;">Shoulders</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">Forward</td>
											<!-- <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_shoulderForwardY" name="shoulderForwardY" value="1"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_shoulderForwardN" name="shoulderForwardN" value="1"></td> -->
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">
												<!-- <label class="radio-inline"> -->
													<input type="radio" name="shoulderForward" value="1">
												<!-- </label> -->
											</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">
												<!-- <label class="radio-inline"> -->
													<input type="radio" name="shoulderForward" value="0">
												<!-- </label> -->
											</td>
										</tr>
										<tr>
											<td style="margin: 0px; padding: 3px 14px 14px 14px; font-weight: bold;">Head position</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">Forward</td>
											<!-- <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_HPForwardY" name="HPForwardY" value="1"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_HPForwardN" name="HPForwardN" value="1"></td> -->
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">
												<!-- <label class="radio-inline"> -->
													<input type="radio" name="HPForward" value="1">
												<!-- </label> -->
											</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">
												<!-- <label class="radio-inline"> -->
													<input type="radio" name="HPForward" value="0">
												<!-- </label> -->
											</td>
										</tr>
										<tr>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">Back</td>
											<!-- <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_HPBackY" name="HPBackY" value="1"></td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="phys_HPBackN" name="HPBackN" value="1"></td> -->
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">
												<!-- <label class="radio-inline"> -->
													<input type="radio" name="HPBack" value="1">
												<!-- </label> -->
											</td>
											<td style="margin: 0px; padding: 3px 14px 14px 14px;">
												<!-- <label class="radio-inline"> -->
													<input type="radio" name="HPBack" value="0">
												<!-- </label> -->
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					
					<div class="eight wide column">
						<div class="ui two cards">
							<!-- <a class="ui card bodydia_physio" data-type='BF_PHYSIO'>
								<div class="image">
									<img src="{{ asset('patientcare/img/bodydia5.png') }}">
								</div>
							</a> -->
							<a class="ui card bodydia_physio" data-type='BR_PHYSIO'>
								<div class="image">
									<img src="{{ asset('patientcare/img/bodydia6.png') }}">
								</div>
							</a>
							<a class="ui card bodydia_physio" data-type='BL_PHYSIO'>
								<div class="image">
									<img src="{{ asset('patientcare/img/bodydia7.png') }}">
								</div>
							</a>
							<!-- <a class="ui card bodydia_physio" data-type='BB_PHYSIO'>
								<div class="image">
									<img src="{{ asset('patientcare/img/bodydia8.png') }}">
								</div>
							</a> -->
						</div>
						
						<div class="ui form" style="padding-top: 10px;">
							<div class="field">
								<label>Comments</label>
								<textarea rows="6" cols="50" id="phys_lateralRmk" name="lateralRmk"></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="ui segments" style="display: none;">
			<div class="ui secondary segment collapsed" data-toggle="collapse" data-target="#phys_elec">
				<i class="angle down icon large"></i>
				<i class="angle up icon large"></i>
				<h4 style="text-align: center; margin-top: 3px;">ELECTROCARDIOGRAM (EKG) NOTES</h4>
			</div>
			<div class="ui segment collapse" id="phys_elec">
				<div class="ui form">
					<div class="field">
						<label>ECG Interpretation</label>
						<textarea rows="6" cols="50" name="electrodg"></textarea>
					</div>
					
					<p>Stress Test Interpretation</p>
					<div class="inline field">
						<label>Protocol</label>
						<input type="text" name="protocol" placeholder="Protocol">
						<label style="padding-left: 20px;">Equipment</label>
						<input type="text" name="equipment" placeholder="Equipment">
					</div>
					
					<div class="field">
						<label>Recommendation</label>
						<textarea rows="6" cols="50" name="recommendation"></textarea>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>