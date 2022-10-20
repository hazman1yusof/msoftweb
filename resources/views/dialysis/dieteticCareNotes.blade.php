<div class='ui column'>
	<div class="ui segments" style="position: relative;">
		<div class="ui secondary segment bluecloudsegment">
			DIETETIC CARE NOTES	

			<a class="ui orange disabled label" id="stats_diet" style="display: none;"></a>
			<div class="ui small blue icon buttons" id="btn_grp_edit_dieteticCareNotes" style="position: absolute;
						padding: 0 0 0 0;
						right: 40px;
						top: 9px;
						z-index: 2;">
			  <button class="ui button" id="new_dieteticCareNotes"><span class="fa fa-plus-square-o"></span> New</button>
			  <button class="ui button" id="edit_dieteticCareNotes"><span class="fa fa-edit fa-lg"></span> Edit</button>
			  <button class="ui button" id="save_dieteticCareNotes"><span class="fa fa-save fa-lg"></span> Save</button>
			  <button class="ui button" id="cancel_dieteticCareNotes"><span class="fa fa-ban fa-lg"></span> Cancel</button>
			</div>
			<!-- <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
				id="btn_grp_edit_dieteticCareNotes"
				style="position: absolute;
						padding: 0 0 0 0;
						right: 40px;
						top: 5px;">
				<button type="button" class="btn btn-default" id="new_dieteticCareNotes">
					<span class="fa fa-plus-square-o"></span> New
				</button>
				<button type="button" class="btn btn-default" id="edit_dieteticCareNotes">
					<span class="fa fa-edit fa-lg"></span> Edit
				</button>
				<button type="button" class="btn btn-default" data-oper='add' id="save_dieteticCareNotes">
					<span class="fa fa-save fa-lg"></span> Save
				</button>
				<button type="button" class="btn btn-default" id="cancel_dieteticCareNotes">
					<span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
				</button>
			</div> -->
		</div>

		<div class="ui segment">
			<form id="formDieteticCareNotes" class="ui form">

				<input id="mrn_dieteticCareNotes" name="mrn_dieteticCareNotes" type="hidden">
				<input id="episno_dieteticCareNotes" name="episno_dieteticCareNotes" type="hidden">

				<div class='ui grid'>
					<div class="twelve wide column">
						<div class="ui grid">
							<div class="sixteen wide column">
								<div class="ui segments">
									<div class="ui secondary segment">ASSESSMENT</div>
									<div class="ui segment">
										<div class="ui grid">
											<div class="eight wide column">
												<div class="field">
												    <label>Medical History</label>
												    <textarea rows="3" cols="50" name="ncase_medical_his" id="ncase_medical_his" ></textarea>
												</div>
											</div>

											<div class="eight wide column">
												<div class="field">
												    <label>Surgical History</label>
												    <textarea rows="3" cols="50" name="ncase_surgical_his" id="ncase_surgical_his" ></textarea>
												</div>
											</div>

											<div class="eight wide column">
												<div class="field">
												    <label>Family Medical History</label>
												    <textarea rows="3" cols="50" name="ncase_fam_medical_his" id="ncase_fam_medical_his" ></textarea>
												</div>
											</div>

											<div class="eight wide column">
												<div class="field">
												    <label>Medication</label>
												    <textarea rows="3" cols="50" name="ncase_medication" id="ncase_medication" ></textarea>
												</div>
											</div>

											<div class="eight wide column">
												<div class="field">
												    <label>Physical findings</label>
												    <textarea rows="3" cols="50" name="ncase_phyfind" id="ncase_phyfind" ></textarea>
												</div>
											</div>

											<div class="eight wide column">
												<div class="field">
												    <label>Mobility / Physical Activity</label>
												    <textarea rows="3" cols="50" name="ncase_phyact" id="ncase_phyact" ></textarea>
												</div>
											</div>

											<div class="eight wide column">
												<div class="field">
												    <label>Remarks</label>
												    <textarea rows="3" cols="50" name="ncase_remark" id="ncase_remark" ></textarea>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class='sixteen wide column'>
								<div class="ui grid">
									<div class="eight wide column">
										<div class="field">
										    <label>Diet History/Summary</label>
										    <textarea rows="3" cols="50" name="ncase_history" id="ncase_history" ></textarea>
										</div>
									</div>
									<div class="eight wide column">
										<div class="field">
										    <label>Nutrition Diagnosis</label>
										    <textarea rows="3" cols="50" name="ncase_diagnosis" id="ncase_diagnosis" ></textarea>
										</div>
									</div>
									<div class="eight wide column">
										<div class="field">
										    <label>Nutrition Intervention/Plan</label>
										    <textarea rows="3" cols="50" name="ncase_intervention" id="ncase_intervention" ></textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="four wide column">
						<div class="ui segments">
							<div class="ui secondary segment">Vital Sign</div>
							<div class="ui segment">
								<div class="field">
									<label>Medical History</label>
									<div class="ui right labeled input">
									  <input type="text" onKeyPress="if(this.value.length==6) return false;" id="ncase_temperature" name="ncase_temperature">
									  <div class="ui basic label">°C</div>
									</div>
								</div>

								<div class="field">
									<label>Pulse</label>
									<input type="text" onKeyPress="if(this.value.length==6) return false;" id="ncase_pulse" name="ncase_pulse">
								</div>

								<div class="field">
									<label>Respiration</label>
									<input type="text" onKeyPress="if(this.value.length==6) return false;" id="ncase_respiration" name="ncase_respiration">
								</div>

								<div class="field">
									<label>Blood Pressure</label>
									<div class="ui right labeled input">
									  <input type="text" onKeyPress="if(this.value.length==6) return false;" id="ncase_bp_sys1" name="ncase_bp_sys1" style="width:25%">
									  <input type="text" onKeyPress="if(this.value.length==6) return false;" id="ncase_bp_dias2" name="ncase_bp_dias2" style="width:25%">
									  <div class="ui basic label">mmHg</div>
									</div>
								</div>

								<div class="field">
									<label>GXT</label>
									<div class="ui right labeled input">
									  <input type="text" onKeyPress="if(this.value.length==6) return false;" id="ncase_gxt" name="ncase_gxt">
									  <div class="ui basic label">mmOL</div>
									</div>
								</div>

								<div class="field">
									<label>Pain Score</label>
									<div class="ui right labeled input">
									  <input type="text" onKeyPress="if(this.value.length==6) return false;" id="ncase_painscore" name="ncase_painscore">
									  <div class="ui basic label">/10</div>
									</div>
								</div>

							</div>
						</div>
						<div class="ui segments">
							<div class="ui secondary segment">Anthropometric Measurement</div>
							<div class="ui segment">
								<div class="field">
									<label>Height</label>
									<div class="ui right labeled input">
									  <input type="text" onKeyPress="if(this.value.length==6) return false;" id="ncase_height" name="ncase_height">
									  <div class="ui basic label">CM</div>
									</div>
								</div>

								<div class="field">
									<label>Weight</label>
									<div class="ui right labeled input">
									  <input type="text" onKeyPress="if(this.value.length==6) return false;" id="ncase_weight" name="ncase_weight">
									  <div class="ui basic label">KG</div>
									</div>
								</div>

								<div class="field">
									<label>BMI</label>
									<input type="text" onKeyPress="if(this.value.length==6) return false;" id="ncase_bmi" name="ncase_bmi">
								</div>

								<div class="field">
									<label>IBW</label>
									<input type="text" onKeyPress="if(this.value.length==6) return false;" id="ncase_ibw" name="ncase_ibw">
								</div>

							</div>
						</div>
					</div>

				</div>

			</form>

		</div>
	</div>
		
	<div class="ui segments" style="position: relative;">
		<div class="ui secondary segment bluecloudsegment">
			FOLLOW UP DIETETIC CARE NOTES	
			<div class="ui small blue icon buttons" id="btn_grp_edit_dieteticCareNotes_fup" style="position: absolute;
						padding: 0 0 0 0;
						right: 40px;
						top: 9px;
						z-index: 2;">
			  <button class="ui button" id="new_dieteticCareNotes_fup"><span class="fa fa-plus-square-o"></span> New</button>
			  <button class="ui button" id="edit_dieteticCareNotes_fup"><span class="fa fa-edit fa-lg"></span> Edit</button>
			  <button class="ui button" id="save_dieteticCareNotes_fup"><span class="fa fa-save fa-lg"></span> Save</button>
			  <button class="ui button" id="cancel_dieteticCareNotes_fup"><span class="fa fa-ban fa-lg"></span> Cancel</button>
			</div>
			<!-- <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
				id="btn_grp_edit_dieteticCareNotes_fup"
				style="position: absolute;
						padding: 0 0 0 0;
						right: 40px;
						top: 5px;">
				<button type="button" class="btn btn-default" id="new_dieteticCareNotes_fup">
					<span class="fa fa-plus-square-o"></span> New
				</button>
				<button type="button" class="btn btn-default" id="edit_dieteticCareNotes_fup">
					<span class="fa fa-edit fa-lg"></span> Edit
				</button>
				<button type="button" class="btn btn-default" data-oper='add' id="save_dieteticCareNotes_fup">
					<span class="fa fa-save fa-lg"></span> Save
				</button>
				<button type="button" class="btn btn-default" id="cancel_dieteticCareNotes_fup">
					<span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
				</button>
			</div>	 -->
		</div>
		<div class="ui segment">
			<div class="ui grid">
				<div class="four wide column">
					<table id="dietetic_date_tbl" class="ui celled table" style="width: 100%;">
						<thead>
							<tr>
								<th class="scope">mrn</th>
								<th class="scope">episno</th>
								<th class="scope">Date</th>
								<th class="scope">adduser</th>
								<th class="scope">Doctor</th>
							</tr>
						</thead>
					</table>
				</div>

				<div class="twelve wide column">
					<form id="formDieteticCareNotes_fup" class="ui form">
						<input id="mrn_dieteticCareNotes_fup" name="mrn_dieteticCareNotes_fup" type="hidden">
						<input id="episno_dieteticCareNotes_fup" name="episno_dieteticCareNotes_fup" type="hidden">
						<input id="fup_recordtime" name="fup_recordtime" type="hidden">

						<div class="ui grid">
							<div class="eleven wide column">
								<div class="ui grid">
									<div class="sixteen wide column">
										<div class="ui segments">
											<div class="ui secondary segment">MONITORING AND EVALUATION</div>
											<div class="ui segment">
												<div class="column">
													<div class="field">
													    <label>Comment on Progress (Anthropometry/Biochemical/Clinical/Dietary)</label>
													    <textarea rows="3" cols="50" name="fup_progress" id="fup_progress" ></textarea>
													</div>
												</div>

												<div class="column">
													<div class="field">
													    <label>Nutrition Diagnosis</label>
													    <textarea rows="3" cols="50" name="fup_diagnosis" id="fup_diagnosis" ></textarea>
													</div>
												</div>
											</div>
										</div>
									</div>

									<div class="sixteen wide column">
										<div class="field">
										    <label>Nutrition Intervention</label>
										    <textarea rows="3" cols="50" name="fup_intervention" id="fup_intervention" ></textarea>
										</div>
									</div>
								</div>
							</div>

							<div class="five wide column">
								<div class="ui segments">
									<div class="ui secondary segment">Vital Sign</div>
									<div class="ui segment">
										<div class="field">
											<label>Medical History</label>
											<div class="ui right labeled input">
											  <input type="text" onKeyPress="if(this.value.length==6) return false;" id="fup_temperature" name="fup_temperature">
											  <div class="ui basic label">°C</div>
											</div>
										</div>

										<div class="field">
											<label>Pulse</label>
											<input type="text" onKeyPress="if(this.value.length==6) return false;" id="fup_pulse" name="fup_pulse">
										</div>

										<div class="field">
											<label>Respiration</label>
											<input type="text" onKeyPress="if(this.value.length==6) return false;" id="fup_respiration" name="fup_respiration">
										</div>

										<div class="field">
											<label>Blood Pressure</label>
											<div class="ui right labeled input">
											  <input type="text" onKeyPress="if(this.value.length==6) return false;" id="fup_bp_sys1" name="fup_bp_sys1" style="width:25%">
											  <input type="text" onKeyPress="if(this.value.length==6) return false;" id="fup_bp_dias2" name="fup_bp_dias2" style="width:25%">
											  <div class="ui basic label">mmHg</div>
											</div>
										</div>

										<div class="field">
											<label>GXT</label>
											<div class="ui right labeled input">
											  <input type="text" onKeyPress="if(this.value.length==6) return false;" id="fup_gxt" name="fup_gxt">
											  <div class="ui basic label">mmOL</div>
											</div>
										</div>

										<div class="field">
											<label>Pain Score</label>
											<div class="ui right labeled input">
											  <input type="text" onKeyPress="if(this.value.length==6) return false;" id="fup_painscore" name="fup_painscore">
											  <div class="ui basic label">/10</div>
											</div>
										</div>

									</div>
								</div>
								<div class="ui segments">
									<div class="ui secondary segment">Anthropometric Measurement</div>
									<div class="ui segment">
										<div class="field">
											<label>Height</label>
											<div class="ui right labeled input">
											  <input type="text" onKeyPress="if(this.value.length==6) return false;" id="fup_height" name="fup_height">
											  <div class="ui basic label">CM</div>
											</div>
										</div>

										<div class="field">
											<label>Weight</label>
											<div class="ui right labeled input">
											  <input type="text" onKeyPress="if(this.value.length==6) return false;" id="fup_weight" name="fup_weight">
											  <div class="ui basic label">KG</div>
											</div>
										</div>

										<div class="field">
											<label>BMI</label>
											<input type="text" onKeyPress="if(this.value.length==6) return false;" id="fup_bmi" name="fup_bmi">
										</div>

										<div class="field">
											<label>IBW</label>
											<input type="text" onKeyPress="if(this.value.length==6) return false;" id="fup_ibw" name="fup_ibw">
										</div>

									</div>
								</div>
							</div>
						</div>

					</form>
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading text-center" id="jqGrid_trans_diet_c">Order Entry</div>
            <table id="jqGrid_trans_diet" class="table table-striped"></table>
            <div id="jqGrid_transPager_diet"></div>
        </div>
    </div>
</div>