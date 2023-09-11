<div class="ui mini form" style="width: 70% !important;margin: auto;">
	<button class="ui basic small button right floated" style="margin-top:12px;" id="print_rec_w">
    <i class="print icon"></i> Print
  </button>
  <div class="four fields">
    <div class="field">
      <label>Week From:</label>
      <input type="date" id="selectweek_from" >
    </div>

    <div class="field">
      <label>To:</label>
      <input type="date" id="selectweek_to" disabled="">
    </div>
    <div class="field">
      <label>&nbsp;</label>
      <button type="button" class="ui mini blue submit button" id="rec_weekly_but">Go</button>
    </div>
  </div>
</div>

<table class="table table-bordered diatbl" id="dia_weekly">
<tbody>
	<tr>
		<td width="30%" rowspan="2" bgcolor="#BEBEBE"><b>Description</b></td>
		<th width="70%" align="center" colspan="3" bgcolor="#BEBEBE"><b>Date</b></th>
	</tr>
	<tr id="visit_date_w">
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="start_time_w">
		<td width="200" align="left">START TREATMENT</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="dialyser_w">
		<td width="100" align="left">&nbsp;DIALYSER</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="no_of_use_w">
		<td width="100" align="left">&nbsp;NO OF USE</td>
		<td width="80" align="center"></td>
		<td width="80" align="center"></td>
		<td width="80" align="center"></td>
	</tr>
	<tr id="target_uf_w">
		<td width="100" align="left">&nbsp;TARGET UF</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="heparin_bolus_w">
		<td width="100" align="left">&nbsp;HEPARIN BOLUS</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="heparin_maintainance_w">
		<td width="100" align="left">&nbsp;HEPARIN MAINTAINANCE</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="dialysate_ca_w">
		<td width="100" align="left">&nbsp;DIALYSATE CA</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="pre_weight_w">
		<td width="100" align="left">&nbsp;WEIGHT</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="idwg_w">
		<td width="100" align="left">&nbsp;IDWG (Pre-Post Last HD) </td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="prehd_tmp_w">
		<td width="100" align="left">&nbsp;TMP</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="prehd_bp_w">
		<td width="100" align="left">&nbsp;BLOOD PRESSURE</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="pulse_pre_w">
		<td width="100" align="left">&nbsp;PULSE</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="prehd_bfr_w">
		<td width="100" align="left">&nbsp;BLOOD FLOW RATE</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="prehd_dfr_w">
		<td width="100" align="left">&nbsp;DIALYSATE FLOW RATE</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="prehd_vp_w">
		<td width="100" align="left">&nbsp;VENEOUS PRESSURE</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="user_prehd_w">
		<td width="100" align="left">&nbsp;ADDED BY</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>

	<tr id="1_tc_w" class="color_1sthour_b">
		<td width="200" align="left" ><b>1<sup>st</sup> Hour</b></td>
		<td width="80" align="center" >&nbsp;</td>
		<td width="80" align="center" >&nbsp;</td>
		<td width="80" align="center" >&nbsp;</td>
	</tr>
	<tr id="1_bp_w" class="color_1sthour">
		<td width="100" align="left">&nbsp;BLOOD PRESSURE</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="1_pulse_w" class="color_1sthour">
		<td width="100" align="left">&nbsp;PULSE</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="1_bfr_w" class="color_1sthour">
		<td width="100" align="left">&nbsp;BLOOD FLOW RATE</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="1_vp_w" class="color_1sthour">
		<td width="100" align="left">&nbsp;VENEOUS PRESSURE</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="1_tmp_w" class="color_1sthour">
		<td width="100" align="left">&nbsp;TMP</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="1_dh_w" class="color_1sthour">
		<td width="100" align="left">&nbsp;DELIVERED HEPARIN</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="1_uv_w" class="color_1sthour">
		<td width="100" align="left">&nbsp;UF VOLUME</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="1_f_w" class="color_1sthour">
		<td width="100" align="left">&nbsp;FLUIDS</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="user_1_w" class="color_1sthour">
		<td width="100" align="left">&nbsp;ADDED BY</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>

	<tr id="2_tc_w" class="color_2ndthour_b">
		<td width="200" align="left" ><b>2<sup>nd</sup> Hour</b></td>
		<td width="80" align="center" >&nbsp;</td>
		<td width="80" align="center" >&nbsp;</td>
		<td width="80" align="center" >&nbsp;</td>
	</tr>
	<tr id="2_bp_w" class="color_2ndthour">
		<td width="100" align="left">&nbsp;BLOOD PRESSURE</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="2_pulse_w" class="color_2ndthour">
		<td width="100" align="left">&nbsp;PULSE</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="2_bfr_w" class="color_2ndthour">
		<td width="100" align="left">&nbsp;BLOOD FLOW RATE</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="2_vp_w" class="color_2ndthour">
		<td width="100" align="left">&nbsp;VENEOUS PRESSURE</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="2_tmp_w" class="color_2ndthour">
		<td width="100" align="left">&nbsp;TMP</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="2_dh_w" class="color_2ndthour">
		<td width="100" align="left">&nbsp;DELIVERED HEPARIN</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="2_uv_w" class="color_2ndthour">
		<td width="100" align="left">&nbsp;UF VOLUME</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="2_f_w" class="color_2ndthour">
		<td width="100" align="left">&nbsp;FLUIDS</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="user_2_w" class="color_2ndthour">
		<td width="100" align="left">&nbsp;ADDED BY</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>

	<tr id="3_tc_w" class="color_3rdhour_b">
		<td width="200" align="left" ><b>3<sup>rd</sup> Hour</b></td>
		<td width="80" align="center" >&nbsp;</td>
		<td width="80" align="center" >&nbsp;</td>
		<td width="80" align="center" >&nbsp;</td>
	</tr>
	<tr id="3_bp_w" class="color_3rdhour">
		<td width="100" align="left">&nbsp;BLOOD PRESSURE</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="3_pulse_w" class="color_3rdhour">
		<td width="100" align="left">&nbsp;PULSE</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="3_bfr_w" class="color_3rdhour">
		<td width="100" align="left">&nbsp;BLOOD FLOW RATE</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="3_vp_w" class="color_3rdhour">
		<td width="100" align="left">&nbsp;VENEOUS PRESSURE</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="3_tmp_w" class="color_3rdhour">
		<td width="100" align="left">&nbsp;TMP</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="3_dh_w" class="color_3rdhour">
		<td width="100" align="left">&nbsp;DELIVERED HEPARIN</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="3_uv_w" class="color_3rdhour">
		<td width="100" align="left">&nbsp;UF VOLUME</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="3_f_w" class="color_3rdhour">
		<td width="100" align="left">&nbsp;FLUIDS</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="user_3_w" class="color_3rdhour">
		<td width="100" align="left">&nbsp;ADDED BY</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>

	<tr id="4_tc_w" class="color_4thhour_b">
		<td width="200" align="left" ><b>4<sup>th</sup> Hour</b></td>
		<td width="80" align="center" >&nbsp;</td>
		<td width="80" align="center" >&nbsp;</td>
		<td width="80" align="center" >&nbsp;</td>
	</tr>
	<tr id="4_bp_w" class="color_4thhour">
		<td width="100" align="left">&nbsp;BLOOD PRESSURE</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="4_pulse_w" class="color_4thhour">
		<td width="100" align="left">&nbsp;PULSE</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="4_bfr_w" class="color_4thhour">
		<td width="100" align="left">&nbsp;BLOOD FLOW RATE</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="4_vp_w" class="color_4thhour">
		<td width="100" align="left">&nbsp;VENEOUS PRESSURE</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="4_tmp_w" class="color_4thhour">
		<td width="100" align="left">&nbsp;TMP</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="4_dh_w" class="color_4thhour">
		<td width="100" align="left">&nbsp;DELIVERED HEPARIN</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="4_uv_w" class="color_4thhour">
		<td width="100" align="left">&nbsp;UF VOLUME</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="4_f_w" class="color_4thhour">
		<td width="100" align="left">&nbsp;FLUIDS</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="user_4_w" class="color_4thhour">
		<td width="100" align="left">&nbsp;ADDED BY</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>

	<tr id="5_tc_w" class="color_5thhour_b">
		<td width="200" align="left" ><b>5<sup>th</sup> Hour</b></td>
		<td width="80" align="center" >&nbsp;</td>
		<td width="80" align="center" >&nbsp;</td>
		<td width="80" align="center" >&nbsp;</td>
	</tr>
	<tr id="5_bp_w" class="color_5thhour">
		<td width="100" align="left">&nbsp;BLOOD PRESSURE</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="5_pulse_w" class="color_5thhour">
		<td width="100" align="left">&nbsp;PULSE</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="5_bfr_w" class="color_5thhour">
		<td width="100" align="left">&nbsp;BLOOD FLOW RATE</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="5_vp_w" class="color_5thhour">
		<td width="100" align="left">&nbsp;VENEOUS PRESSURE</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="5_tmp_w" class="color_5thhour">
		<td width="100" align="left">&nbsp;TMP</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="5_dh_w" class="color_5thhour">
		<td width="100" align="left">&nbsp;DELIVERED HEPARIN</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="5_uv_w" class="color_5thhour">
		<td width="100" align="left">&nbsp;UF VOLUME</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="5_f_w" class="color_5thhour">
		<td width="100" align="left">&nbsp;FLUIDS</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="user_5_w" class="color_5thhour">
		<td width="100" align="left">&nbsp;ADDED BY</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>

	<tr id="_w">
		<td width="200" align="left" colspan="15" bgcolor="#BEBEBE"><b>POST HD</b></td>
	</tr>
	<tr id="posthd_bp_w">
		<td width="100" align="left">&nbsp;BLOOD PRESSURE</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="posthd_temperatue_w">
		<td width="100" align="left">&nbsp;TEMPERATURE</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="posthd_pulse_w">
		<td width="100" align="left">&nbsp;PULSE</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="posthd_respiratory_w">
		<td width="100" align="left">&nbsp;RESPIRATORY</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="post_weight_w">
		<td width="100" align="left">&nbsp;POST WEIGHT</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="weight_loss_w">
		<td width="100" align="left">&nbsp;WEIGHT LOST</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="time_complete_w">
		<td width="100" align="left">&nbsp;TIME COMPLETED</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="hd_adequancy_w">
		<td width="100" align="left">&nbsp;HD ADEQUACY</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="ktv_w">
		<td width="100" align="left">&nbsp;KT/V</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="medication_w">
		<td width="100" align="left">&nbsp;MEDICATION</td>
		<td width="80" align="left" class="med_td">&nbsp;</td>
		<td width="80" align="left" class="med_td">&nbsp;</td>
		<td width="80" align="left" class="med_td">&nbsp;</td>
	</tr>
	<tr id="user_posthd_w">
		<td width="100" align="left">&nbsp;ADDED BY</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
</tbody></table>