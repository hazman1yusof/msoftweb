<div class="ui form diaform">

<div class="ui message">
<div class="ui mini form">
  <div class="three fields">
    <div class="field">
	<label><div class="label_hd">Start Date:</div></label>
      <input type="date" id="lama_seldate" disabled="" value="{{Carbon\Carbon::now()}}">
    </div>
    <div class="field"><label><div class="label_hd">&nbsp;</div></label>
      <button type="button" class="ui mini blue submit button" id="lama_addnew_dia" disabled="">Add New Record</button>
      <button type="button" class="ui mini blue submit button" id="lama_edit_dia" disabled="">Edit Record</button>
    </div>
  </div>
</div>
</div>

<input type="hidden" name="episno" id="lama_episno">
<input type="hidden" name="mrn" id="lama_mrn">
<form id="lama_daily_form" class="ui mini form" >
	<div class="three fields">
		<div class="field">
			<div class="clinic_code">
				<label><div class="label_hd">Start Time:</div></label>
				<input type="time" class="w" name="start_time" id="lama_start_time" style="background-color:grey cloud; "   value="">
			</div>
		</div>
		<div class="field">
			<div class="clinic_code">
				<label><div class="label_hd">Pre HD Weight:</div></label>
				<input type="text" name="prehd_weight" id="lama_prehd_weight"  value="" style="background-color:grey cloud; " >
			</div>
		</div>
		<div class="field">
			<div class="clinic_code">
				<label> Hep. Loading Dose:</label>
				<input type="text" name="hep_loading" id="lama_hep_loading" value="" style="background-color:#EBEBE4; " >
			</div>
		</div>
	</div>
	
	<div class="three fields">
		<div class="field">
			<div class="clinic_code">
				<label><div class="label_hd">Time Complete:</div></label>
				<input type="time" name="end_time" class="w" id="lama_end_time" style="background-color:grey cloud; "   value="">
			</div>
		</div>
		<div class="field">
			<div class="clinic_code">
			    			<label><div class="label_hd">Prev.Post Weight:</div></label>
				<input type="text" name="prev_post_weight" id="lama_prev_post_weight"  value="" style="background-color:grey cloud; " >
			</div>
		</div>
		 <div class="field">
			<div class="clinic_code">
				<label> Hep. Infusion:</label>
				<input type="text" name="hep_infusion" id="lama_hep_infusion" value="" style="background-color:#EBEBE4; " >
			</div>
		</div>
	</div>
	

	<div class="three fields">
		<div class="field">
			<div class="clinic_code">
				<label><div class="label_hd">Duration of Dialysis:</div></label>
				<input type="text" class="w" name="duration" id="lama_duration"  value="" style="background-color:grey cloud; " >
			</div>
		</div>
	    <div class="field">
			<div class="clinic_code">
				<label><div class="label_hd">IDWG:</div></label>
				<input type="text" name="dialytic_weight" id="lama_dialytic_weight"  value="" style="background-color:grey cloud; " >
			</div>
		</div>
		<div class="field">
			<div class="clinic_code">
				<label> Tinzaparin (Innohep):</label>
				<input type="text" name="tinzaparin" id="lama_tinzaparin" value="" style="background-color:#EBEBE4; " >
			</div>
		</div>
	</div>

	<div class="three fields">
	    <div class="field">
				<label><div class="label_hd">Last Visit:</div></label>
			<div class="fields">
				<div class="ten wide field">
				<input type="text" name="age" id="lama_age"  value="" style="background-color:grey cloud; " >
				</div>
				<div class="six wide field">
				<input type="text" name="visit_Cnt" id="lama_visit_Cnt" value="" style="background-color:#EBEBE4; " >
				</div>
			</div>
		</div>
		<div class="field">
			<div class="clinic_code">
				<label><div class="label_hd">Target Weight:</div></label>
				<input type="text" name="dry_weight" id="lama_dry_weight"  value="" style="background-color:grey cloud; " >
			</div>
		</div>
		 <div class="field">
			<div class="clinic_code">
				<label> Dialysate Calcium:</label>  
				<input type="text" name="dialysate_calcium" id="lama_dialysate_calcium" value="" style="background-color:#EBEBE4; " >

			</div>
		</div>
	</div>

	<div class="three fields">
		<div class="field">
			<div class="clinic_code">
				<label>Machine No:</label>
				<input type="text" name="machine_no" id="lama_machine_no" class="required w"  value="" style="background-color:#EBEBE4; " >
			</div>
		</div>
		<div class="field">
			<div class="clinic_code">
				<label><div class="label_hd">Target UF:</div></label>
				<input type="text" name="total_uf" id="lama_total_uf" value="" style="background-color:grey cloud; " >
			</div>
		</div>

	    <div class="field">
			<div class="clinic_code">
				<label>AVF Needle:</label>  
	            <input type="text" name="avf_needle" id="lama_avf_needle" value="" style="background-color:#EBEBE4; " >
			</div>
		</div>
   	</div>
    
    <hr>

	<h4 class="ui dividing header">Pre-HD Conditions</h4>

	<div class="fields">
        <div class="two wide field">
    		<div class="clinic_code">
    			<label style="width: 50px;"> Site:</label>
    			<input type="text" name="prehd_site" id="lama_prehd_site"  value=""  >
    		</div>
    	</div>

    	<div class="seven wide field">
    		<div class="clinic_code">
    			<label style="">Condition of AVF/Catheter Exit Site:</label>
    			<input type="text" name="prehd_exit_site" id="lama_prehd_exit_site" value="" >
    		</div>
    
    	</div>
        
        <div class="seven wide field">
			<div class="clinic_code">
				<label style="">Symptoms:</label>
                    <input type="text" name="symtomps" id="lama_symtomps" value=""  >
			</div>
		</div>
	</div>
    

	<div class="field">
		<div class="clinic_code">
        <label style="">Remarks:</label>
			<textarea name="remarks" rows="3" cols="100" style="background-color:#EBEBE4; "></textarea>
		</div>
	</div>

	<hr>

	<table class="table ui form" id="lama_preHDListMeasure">
		<thead>
			<tr>
				<th style="width: 20%; text-align: right;">Measurements</th>
				<th style="text-align: center;">Pre HD</th>
				<th style="text-align: center;">1st Hour</th>
				<th style="text-align: center;">2nd Hour</th>
				<th style="text-align: center;">3rd Hour</th>
				<th style="text-align: center;">4th Hour</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="labeltd">Recorded Time:</td>
				<td><input type="hidden" name="rec_pre" id="lama_rec_pre" value="" style="background-color:grey cloud; ; " ></td>
				<td><input type="text"   name="rec_2" id="lama_rec_2" style="  background-color:#D4FFFF;" value=""></td>
				<td><input type="text"   name="rec_3" id="lama_rec_3" style=" background-color:#AAFFAA;" value=""></td>
				<td><input type="text"   name="rec_4" id="lama_rec_4" style=" background-color:#FFD4FF;" value=""></td>
				<td><input type="text"   name="rec_post" id="lama_rec_post" style=" background-color:#FFFF55;" value=""></td>
			</tr>
			<tr>
				<td class="labeltd">TMP:</td>
				<td><input type="hidden" name="tmp_pre" id="lama_tmp_pre" value="" style="background-color:grey cloud; ; " ></td>
				<td><input type="text"   name="tmp_2" id="lama_tmp_2" style=" background-color:#D4FFFF;" value=""></td>
				<td><input type="text"   name="tmp_3" id="lama_tmp_3" style=" background-color:#AAFFAA;" value=""></td>
				<td><input type="text"   name="tmp_4" id="lama_tmp_4" style=" background-color:#FFD4FF;" value=""></td>
				<td><input type="text"   name="tmp_post" id="lama_tmp_post" style=" background-color:#FFFF55;" value=""></td>
			</tr>
			<tr>
				<td class="labeltd">Blood Pressure:</td>
				<td><input type="text" name="bp_pre" id="lama_bp_pre" value="" style="background-color:grey cloud; ; " ></td>
				<td><input type="text"   name="bp_2" id="lama_bp_2" style=" background-color:#D4FFFF;" value=""></td>
				<td><input type="text"   name="bp_3" id="lama_bp_3" style=" background-color:#AAFFAA;" value=""></td>
				<td><input type="text"   name="bp_4" id="lama_bp_4" style=" background-color:#FFD4FF;" value=""></td>
				<td><input type="text"   name="bp_post" id="lama_bp_post" style=" background-color:#FFFF55;" value=""></td>
			</tr>
			<tr>
				<td class="labeltd">Pulse:</td>
				<td><input type="text" name="pulse_pre" id="lama_pulse_pre" value="" style="background-color:grey cloud; ; " ></td>
				<td><input type="text"   name="pulse_2" id="lama_pulse_2" style=" background-color:#D4FFFF;" value=""></td>
				<td><input type="text"   name="pulse_3" id="lama_pulse_3" style=" background-color:#AAFFAA;" value=""></td>
				<td><input type="text"   name="pulse_4" id="lama_pulse_4" style=" background-color:#FFD4FF;" value=""></td>
				<td><input type="text"   name="pulse_post" id="lama_pulse_post" style=" background-color:#FFFF55;" value=""></td>
			</tr>
			<tr>

				<td class="labeltd">Heparin:</td>
				<td><input type="hidden" name="hepn" id="lama_hepn" style="" value="" ></td>
				<td><input type="text"   name="hepn_1" id="lama_hepn_1" style=" background-color:#D4FFFF;" value=""></td>
				<td><input type="text"   name="hepn_2" id="lama_hepn_2" style=" background-color:#AAFFAA;" value=""></td>
				<td><input type="text"   name="hepn_3" id="lama_hepn_3" style=" background-color:#FFD4FF;" value=""></td>
				<td><input type="text"   name="hepn_4" id="lama_hepn_4" style=" background-color:#FFFF55;" value=""></td>

			</tr>
			<tr>
				<td class="labeltd">Blood Flow Rate:</td>
				<td><input type="hidden" name="bf_pre" id="lama_bf_pre1" style="" value="" ></td>
				<td><input type="text"   name="bf_2" id="lama_bf_2" style=" background-color:#D4FFFF;" value=""></td>
				<td><input type="text"   name="bf_3" id="lama_bf_3" style=" background-color:#AAFFAA;" value=""></td>
				<td><input type="text"   name="bf_4" id="lama_bf_4" style=" background-color:#FFD4FF;" value=""></td>
				<td><input type="text"   name="bf_post" id="lama_bf_post" style=" background-color:#FFFF55;" value=""></td>
			</tr>
			<tr>
				<td class="labeltd">UF Rate:</td>
				<td><input type="hidden" name="uf_rate" id="lama_uf_rate" style="" value="" ></td>
				<td><input type="hidden"   name="uf_rate_1" id="lama_uf_rate_1" style=" background-color:#D4FFFF;" value=""></td>
				<td><input type="text"   name="uf_rate_2" id="lama_uf_rate_2" style=" background-color:#AAFFAA;" value=""></td>
				<td><input type="text"   name="uf_rate_3" id="lama_uf_rate_3" style=" background-color:#FFD4FF;" value=""></td>
				<td><input type="text"   name="uf_rate_4" id="lama_uf_rate_4" style=" background-color:#FFFF55;" value=""></td>
			</tr>
			<tr>
				<td class="labeltd">Dialsate Flow Rate:</td>
				<td><input type="hidden" name="df_pre" id="lama_df_pre1" value="" style="background-color:grey cloud; ; " ></td>
				<td><input type="text"   name="df_2" id="lama_df_2" style=" background-color:#D4FFFF;" value=""></td>
				<td><input type="text"   name="df_3" id="lama_df_3" style=" background-color:#AAFFAA;" value=""></td>
				<td><input type="text"   name="df_4" id="lama_df_4" style=" background-color:#FFD4FF;" value=""></td>
				<td><input type="text"   name="df_post" id="lama_df_post" style=" background-color:#FFFF55;" value=""></td>
			</tr>
			<tr>
				<td class="labeltd">Conductivity:</td>
				<td><input type="hidden" name="conduc" id="lama_conduc" style="" value="" ></td>
				<td><input type="text"   name="conduc_1" id="lama_conduc_1" style=" background-color:#D4FFFF;" value=""></td>
				<td><input type="text"   name="conduc_2" id="lama_conduc_2" style=" background-color:#AAFFAA;" value=""></td>
				<td><input type="text"   name="conduc_3" id="lama_conduc_3" style=" background-color:#FFD4FF;" value=""></td>
				<td><input type="text"   name="conduc_4" id="lama_conduc_4" style=" background-color:#FFFF55;" value=""></td>
			</tr>
			<tr>
				<td class="labeltd">Veneous Pressure:</td>
				<td><input type="hidden" name="vp_pre" id="lama_vp_pre1" style="" value="" ></td>
				<td><input type="text"   name="vp_2" id="lama_vp_2" style=" background-color:#D4FFFF;" value=""></td>
				<td><input type="text"   name="vp_3" id="lama_vp_3" style=" background-color:#AAFFAA;" value=""></td>
				<td><input type="text"   name="vp_4" id="lama_vp_4" style=" background-color:#FFD4FF;" value=""></td>
				<td><input type="text"   name="vp_post" id="lama_vp_post" style=" background-color:#FFFF55;" value=""></td>
			</tr>
		    <tr>
				<td class="labeltd">Complications:</td>
				<td><input type="hidden" name="comp_pre" id="lama_comp_pre1" style="" value="" ></td>
				<td><input type="checkbox" name="comp_2" id="lama_comp_2" style="margin-left: 30px; float: left; margin-top: 8px;" value="1"></td>
				<td><input type="checkbox" name="comp_3" id="lama_comp_3" style="margin-left: 30px; float: left; margin-top: 8px;" value="1"></td>
				<td><input type="checkbox" name="comp_4" id="lama_comp_4" style="margin-left: 30px; float: left; margin-top: 8px;" value="1"></td>
				<td><input type="checkbox" name="comp_post" id="lama_comp_post" style="margin-left: 30px; float: left; margin-top: 8px;" value="1"></td>
			</tr>
			<tr>
				<td class="labeltd" style="height: 30px;">EPO:</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td><input type="checkbox"   name="epo_post" id="lama_epo_post" style="margin-left: 30px;" value="1"></td>
			</tr>
			<tr>
				<td colspan="6"></td>
			</tr>
		</tbody>
	</table>



	<table class="table ui form" id="lama_preHDList">
		<thead>
			<tr>
				<th colspan="4">Pre HD</th>
				<th colspan="4">Post HD</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="labeltd">Temperature:</td>
				<td>
					<input type="text" name="temp_reading" id="lama_temp_reading"  style="" value="" >
				</td>
				<td class="labeltd">HCT:</td>
				<td>
					<input type="text" name="hct" id="lama_hct"  style="" value="" >
				</td>
				<td class="labeltd">Temperature:</td>
				<td colspan="3">
					<input type="text" name="temperature" id="lama_temperature" style="" value=""  >
				</td>
			</tr>
			<tr>
				<td class="labeltd">Dialysate Temp:</td>
				<td>
					<input type="text" name="dialyser_temp" id="lama_dialyser_temp" style=" background-color:grey cloud; "   value="">
				</td>
				<td class="labeltd">Program:</td>
				<td>
					<input type="text" name="program" id="lama_program" style=" background-color:grey cloud; "   value="">
				</td>
				<td class="labeltd">Blood Pressure(Stand):</td>
				<td>
					<input type="text" name="bp_stand" id="lama_bp_stand" style=""   value="">
				</td>
				<td class="labeltd">Pulse(Stand):</td>
				<td>
					<input type="text" name="pulse_stand" id="lama_pulse_stand" style=""   value="">
				</td>
			</tr>
		
			<tr>
				<td class="labeltd">EPO Type:</td>
				<td>
				    <input type="text" name="epo_type" id="lama_epo_type"  value="" style=" background-color:grey cloud; " >
				</td>
				<td class="labeltd">EPO Dose:</td>
				<td>
	            	<input type="text" name="dose_type" id="lama_dose_type"  value="" style=" background-color:grey cloud; " >
				</td>
				<td class="labeltd">Blood Pressure(Sit):</td>
				<td>
					<input type="text" name="bp_sit" id="lama_bp_sit" style=""   value="">
				</td>
				<td class="labeltd">Pulse(Sit):</td>
				<td>
					<input type="text" name="pulse_sit" id="lama_pulse_sit" style=""   value="">
				</td>
			</tr>
			<tr>
	            <td class="labeltd">Dialyzer Name:</td>
	            <td colspan="3">
	                <input type="text" name="type_dialyser" id="lama_type_dialyser"  value="" style="background-color:#EBEBE4;  width: 269px;" >
	            </td>  
				<td class="labeltd">Post HD Weight:</td>
				<td>
					<input type="text" name="hd_weight" id="lama_hd_weight"   value="" style="">
				</td>
				<td class="labeltd">Weight Loss:</td>
				<td>
					<input type="text" name="weight_loss" id="lama_weight_loss"  style=" background-color:#F0F0F0; " value="">
				</td>

			</tr>
 
			<tr>
	            <td class="labeltd">Dialyzer Type:</td>
				<td style="width:100px">
					<input type="text" name="type_of_dialyser" id="lama_type_of_dialyser"  value="" style="background-color:grey cloud;  width: 95px;" >
	            </td> 
				<td class="labeltd">No of Used:</td>
				<td><input type="text" name="dialyser_num_used" id="lama_dialyser_num_used"  value="" style=" background-color:grey cloud; " ></td>
				<td class="labeltd">Achieved UF:</td>
				<td colspan="3"><input type="text" name="ultra" id="lama_ultra"   value="" style=""></td>
			</tr>
			<tr>
				<td class="labeltd">Vascular Access:</td>
				<td colspan="3"><input type="text" name="vascular_name" id="lama_vascular_name"  value="" style="width: 269px; background-color:grey cloud; " ></td>
				<td class="labeltd">KT/V/CBV:</td>
				<td colspan="3"><input type="text" name="kt_v" id="lama_kt_v"   value="" style=""></td>
			</tr>
			<tr>
				<td class="labeltd">Pre HD Verified By:</td>
				<td colspan="3">
					<input type="text" name="pre_verifier_name" id="lama_pre_verifier_name"  value="" style="background-color:#F0F0F0; ; width: 269px;" >
				</td>
				<td class="labeltd">Post HD Verified By:</td>
				<td colspan="3">
					<input type="text" name="verifier_by" id="lama_verifier_by"  value="" style="background-color:#F0F0F0; ; width: 269px;" >
				</td>
			</tr>
		</tbody>
	</table>

	<!--<DIV id="lama_print-page-break"></DIV>

	<div style="clear: both;">&nbsp;</div>

	<DIV id="lama_print-margin-break"></DIV>-->

	<h4 class="ui dividing header">Intra-dialytic complications</h4>
	<div class="fields">
		<div class="field">
			<label style="text-align: left">Remarks:  &nbsp;&nbsp;&nbsp;<strong></strong></label>
			<div class="clinic_code">
				<textarea name="remarks1" cols="100"   style=""></textarea>
			</div>
		</div>
		<div class="field">
			<label style="text-align: left;">Action(s):</label>
			<div class="clinic_code">
				<textarea name="diagnosis" cols="100"   style=""></textarea>
			</div>
		</div>
	</div>

	<button type="button" id="lama_submit" class="ui submit button">Submit</button>
	<div class="ui error message"></div>
</form>
</div>