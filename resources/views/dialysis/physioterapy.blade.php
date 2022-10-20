
<div class='col-md-12' style="padding:0 0 15px 0">
	<input type="hidden" id="urltodiagram" value="url('/upload/pdf/')">
    <div class="ui segments" id="phys_bigsegment" style="position:relative; margin-top: 0px;">

    	@include('physioterapy_ncaseform')

		<div class="ui grid" style="position: relative; padding-top: 30px; margin: 0px 5px 0px 5px;">

			<!-- <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
				id="btn_grp_edit_phys"
				style="position: absolute;
						padding: 0 0 0 0;
						right: 40px;
						top: 55px;
						z-index: 1000;">
				<button type="button" class="btn btn-default" id="new_phys">
					<span class="fa fa-plus-square-o"></span> New
				</button>
				<button type="button" class="btn btn-default" id="edit_phys">
					<span class="fa fa-edit fa-lg"></span> Edit
				</button>
				<button type="button" class="btn btn-default" data-oper='add' id="save_phys">
					<span class="fa fa-save fa-lg"></span> Save
				</button>
				<button type="button" class="btn btn-default" id="cancel_phys">
					<span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
				</button>
			</div> -->

			<div class="three wide column">
				<table id="phys_date_tbl" class="ui celled table" style="width: 100%;">
					<thead>
						<tr>
							<th class="scope">mrn</th>
							<th class="scope">episno</th>
							<th class="scope">Date</th>
							<th class="scope">Add User</th>
						</tr>
					</thead>
				</table>
			</div>

			<div class="thirteen wide column">
				<div class="ui segment secondary ">
					<div class="ui form">
						<div class="ui grid">
							<div class="eight wide column">
								<div class="inline fields">
									<div class="field">
								      <div class="ui radio checkbox checked pastcurr">
								        <input type="radio" name="pastcurr" checked="" tabindex="0" class="hidden" value="Current">
								        <label>Current</label>
								      </div>
								    </div>
									<div class="field">
								      <div class="ui radio checkbox pastcurr">
								        <input type="radio" name="pastcurr" tabindex="0" class="hidden" value="Past">
								        <label>Past History</label>
								      </div>
								    </div>
								</div>
							</div> 

							<div class="six wide column">
								
							</div>
						</div>
					</div>
				</div>

				<form id="formphys">

				    <input id="mrn_phys" name="mrn_phys" type="hidden">
				    <input id="episno_phys" name="episno_phys" type="hidden">
				    <input id="category_phys" name="category" type="hidden">
					<input id="referdiet_phys" name="referdiet" type="hidden" value="no">

					<div id="phys" class="ui top attached tabular menu">
						<a class="item active" data-tab="subass">Subjective Assesment</a>
						<a class="item" data-tab="objass">Objective Assesment</a>
						<a class="item" data-tab="bodydiag">Body Diagram</a>
						<a class="item" data-tab="analys">Analysis/Plan/Evaluation</a>
						<a class="item" data-tab="ordentry">Order Entry</a>
					</div>

					<div class="ui bottom attached tab raised segment active" data-tab="subass">
						<div class="ui grid">
							<div class="eight wide column">
								<div class="ui form">
									<div class="field">
									    <label>Patient Complain</label>
									    <textarea rows="6" cols="50" name="complain" ></textarea>
									</div>
								</div>
							</div>

						  	<div class="eight wide column">
								<div class="ui form">
								<div class="field">
								    <label>Present History</label>
								    <textarea rows="6" cols="50" name="presenthistory" ></textarea>
								</div>
								</div>
							</div>

						  	<div class="eight wide column">
								<div class="ui form">
								<div class="field">
								    <label>Past History</label>
								    <textarea rows="6" cols="50" name="pasthistory" ></textarea>
								</div>
								</div>
							</div>

						  	<div class="eight wide column">
								<div class="ui form">
								<div class="field">
								    <label>Medical History</label>
								    <textarea rows="6" cols="50" name="mh" ></textarea>
								</div>
								</div>
						  	</div>

						  	<div class="eight wide column">
								<div class="ui form">
								<div class="field">
								    <label>Social History</label>
								    <textarea rows="6" cols="50" name="sh" ></textarea>
								</div>
								</div>
						  	</div>

						  	<div class="eight wide column">
								<div class="ui form">
								<div class="field">
								    <label>Investigation</label>
								    <textarea rows="6" cols="50" name="investigation" ></textarea>
								</div>
								</div>
						  	</div>

						  	<div class="eight wide column">
								<div class="ui form">
								<div class="field">
								    <label>Functional ADL Status</label>
								    <textarea rows="6" cols="50" name="function_" ></textarea>
								</div>
								</div>
						  	</div>

						  	<div class="eight wide column">
								<div class="ui form">
								<div class="field">
								    <label>DR Management</label>
								    <textarea rows="6" cols="50" name="drmgmt" ></textarea>
								</div>
								</div>
						  	</div>
						</div>
					</div>

					<div class="ui bottom attached tab raised segment" data-tab="objass">
						<div class="ui grid">
							<div class="eight wide column">
								<div class="ui form">
								<div class="field">
								    <label>General Observation</label>
								    <textarea rows="6" cols="50" name="genobserv" ></textarea>
								</div>
								</div>
						  	</div>
							<div class="eight wide column">
								<div class="ui form">
								<div class="field">
								    <label>Local Observation</label>
								    <textarea rows="6" cols="50" name="localobserv" ></textarea>
								</div>
								</div>
						  	</div>
							<div class="eight wide column">
								<div class="ui form">
								<div class="field">
								    <label>ROM</label>
								    <textarea rows="6" cols="50" name="rom" ></textarea>
								</div>
								</div>
						  	</div>
							<div class="eight wide column">
								<div class="ui form">
								<div class="field">
								    <label>MMT</label>
								    <textarea rows="6" cols="50" name="mmt" ></textarea>
								</div>
								</div>
						  	</div>
							<div class="eight wide column">
								<div class="ui form">
								<div class="field">
								    <label>Palpation</label>
								    <textarea rows="6" cols="50" name="palpation" ></textarea>
								</div>
								</div>
						  	</div>

						</div>
					</div>

					<div class="ui bottom attached tab raised segment" data-tab="analys">
						<div class="ui grid">
							<div class="eight wide column">
								<div class="ui form">
									<div class="field">
									    <label>Special Test</label>
									    <textarea rows="6" cols="50" name="test" ></textarea>
									</div>
								</div>
							</div>

						  	<div class="eight wide column">
								<div class="ui form">
								<div class="field">
								    <label>Neurological</label>
								    <textarea rows="6" cols="50" name="neuro" ></textarea>
								</div>
								</div>
							</div>

						  	<div class="eight wide column">
								<div class="ui form">
								<div class="field">
								    <label>Analysis / Impression</label>
								    <textarea rows="6" cols="50" name="analysis" ></textarea>
								</div>
								</div>
							</div>

						  	<div class="eight wide column">
								<div class="ui form">
								<div class="field">
								    <label>Goals</label>
								    <textarea rows="6" cols="50" name="long_" ></textarea>
								</div>
								</div>
						  	</div>

						  	<div class="eight wide column">
								<div class="ui form">
								<div class="field">
								    <label>Plan of Intervention / Intervention</label>
								    <textarea rows="6" cols="50" name="plan_" ></textarea>
								</div>
								</div>
						  	</div>

						  	<div class="eight wide column">
								<div class="ui form">
								<div class="field">
								    <label>Evaluation</label>
								    <textarea rows="6" cols="50" name="evaluation" ></textarea>
								</div>
								</div>
						  	</div>

						  	<div class="eight wide column">
								<div class="ui form">
								<div class="field">
								    <label>Reassessment</label>
								    <textarea rows="6" cols="50" name="reassesment" ></textarea>
								</div>
								</div>
						  	</div>
						</div>
					</div>

					<div class="ui bottom attached tab raised segment" data-tab="bodydiag">
						<div class="ui grid">
							<div class="thirteen wide column">
								<div class="ui four cards" >
									<a class="ui card bodydia" data-type='BF'>
										<div class="image">
									      <img src="{{ asset('img/bodydia1.png') }}" >
									    </div>
									</a>
									<a class="ui card bodydia" data-type='BR'>
										<div class="image">
									      <img src="{{ asset('img/bodydia2.png') }}">
									    </div>
									</a>
									<a class="ui card bodydia" data-type='BL'>
										<div class="image">
									      <img src="{{ asset('img/bodydia3.png') }}">
									    </div>
									</a>
									<a class="ui card bodydia" data-type='BB'>
										<div class="image">
									      <img src="{{ asset('img/bodydia4.png') }}">
									    </div>
									</a>
								</div>
							</div>

							<div class="three wide column">
								<div class="ui form">
									<div class="field">
									    <label>VAS</label>
									    <input type="text" name="vas" placeholder="VAS">
									</div>
									<div class="field">
									    <label>Aggravating Factors</label>
									    <input type="text" name="aggr" placeholder="Aggravating Factors">
									</div>
									<div class="field">
									    <label>Easing Factors</label>
									    <input type="text" name="easing" placeholder="Easing Factors">
									</div>
									<div class="field">
									    <label>Type Of Pain</label>
									    <input type="text" name="pain" placeholder="Type Of Pain">
									</div>
									<div class="field">
									    <label>24 Hours Behaviour</label>
									    <input type="text" name="behaviour" placeholder="24 Hours Behaviour">
									</div>
									<div class="field">
									    <label>Irritability</label>
									    <input type="text" name="irritability" placeholder="Irritability">
									</div>
									<div class="field">
									    <label>Severity</label>
									    <input type="text" name="severity" placeholder="Severity">
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="ui bottom attached tab raised segment" data-tab="ordentry" id="jqGrid_trans_phys_c">
			            <table id="jqGrid_trans_phys" class="table table-striped"></table>
			            <div id="jqGrid_transPager_phys"></div>
					</div>
					
				</form>

			</div>
		</div>

	</div>

</div>