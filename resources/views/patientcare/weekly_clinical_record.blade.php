<div class="ui mini form" style="width: 70% !important;margin: auto;">
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
		<td width="100" rowspan="2" align="center" bgcolor="#BEBEBE"><b>Description</b></td>
		<th width="80" align="center" colspan="14" bgcolor="#BEBEBE"><b>Date</b></th>
	</tr>
	<tr id="monthly_date_w">
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="start_time_w">
		<td width="200" align="left"><b>Start Treatment</b></td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="end_time_w">
		<td width="100" align="left"><b>&nbsp;End of Treatment</b></td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="type_dialyser_w">
		<td width="100" align="left"><b>&nbsp;Type of Dialyser</b></td>
		<td width="80" align="center"><b></b></td>
		<td width="80" align="center"><b></b></td>
		<td width="80" align="center"><b></b></td>
	</tr>
	<tr id="noofuse_w">
		<td width="100" align="left"><b>&nbsp;No of Use</b></td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="total_uf_w">
		<td width="100" align="left">&nbsp;<b>Ultra Filteration</b></td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="hep_loading_w">
		<td width="100" align="left">&nbsp;<b>Heparin Loading</b></td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="hep_infusion_w">
		<td width="100" align="left">&nbsp;<b>Heparin Infusion</b></td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="dialysate_calcium_w">
		<td width="100" align="left">&nbsp;<b>Dialysate Calcium</b></td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="_w">
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="left">&nbsp;</td>
		<td width="80" align="left">&nbsp;</td>
		<td width="80" align="left">&nbsp;</td>
	</tr>
	<tr>
		<td width="200" align="left" colspan="15" bgcolor="#BEBEBE"><b>Pre HD</b></td>
	</tr>
	<tr id="prehd_weight_w">
		<td width="100" align="left">&nbsp;Weight</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="dialytic_weight_w">
		<td width="100" align="left">&nbsp;IDWG (Pre-Post Last HD) </td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="_w">
		<td width="100" align="left">&nbsp;TMP</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="bp_pre_w">
		<td width="100" align="left">&nbsp;Blood Pressure</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="pulse_pre_w">
		<td width="100" align="left">&nbsp;Pulse</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="_w">
		<td width="100" align="left">&nbsp;Blood Flow Rate</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="_w">
		<td width="100" align="left">&nbsp;Dialysate Flow Rate</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="_w">
		<td width="100" align="left">&nbsp;Venous Pressure</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="rec_2_w">
		<td width="200" align="left" bgcolor="#BEBEBE"><b>1<sup>st Hour</sup></b></td>
		<td width="80" align="center" bgcolor="#BEBEBE">&nbsp;</td>
		<td width="80" align="center" bgcolor="#BEBEBE">&nbsp;</td>
		<td width="80" align="center" bgcolor="#BEBEBE">&nbsp;</td>
	</tr>
	<tr id="tmp_2_w">
		<td width="100" align="left">&nbsp;TMP</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="bp_2_w">
		<td width="100" align="left">&nbsp;Blood Pressure</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="pulse_2_w">
		<td width="100" align="left">&nbsp;Pulse</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="hepn_1_w">
		<td width="100" align="left">&nbsp;Heparin</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="bf_2_w">
		<td width="100" align="left">&nbsp;Blood Flow Rate</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="uf_rate_1_w">
		<td width="100" align="left">&nbsp;UF Rate</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="df_2_w">
		<td width="100" align="left">&nbsp;Dialysate Flow Rate</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="vp_2_w">
		<td width="100" align="left">&nbsp;Veneous Pressure</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="rec_3_w">
		<td width="200" align="left" bgcolor="#BEBEBE"><b>2<sup>nd Hour</sup></b></td>
		<td width="80" align="center" bgcolor="#BEBEBE">&nbsp;</td>
		<td width="80" align="center" bgcolor="#BEBEBE">&nbsp;</td>
		<td width="80" align="center" bgcolor="#BEBEBE">&nbsp;</td>
	</tr>
	<tr id="tmp_3_w">
		<td width="100" align="left">&nbsp;TMP</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="bp_3_w">
		<td width="100" align="left">&nbsp;Blood Pressure</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="pulse_3_w">
		<td width="100" align="left">&nbsp;Pulse</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="hepn_2_w">
		<td width="100" align="left">&nbsp;Heparin</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>	
	<tr id="bf_3_w">
		<td width="100" align="left">&nbsp;Blood Flow Rate</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="uf_rate_2_w">
		<td width="100" align="left">&nbsp;UF Rate</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="df_3_w">
		<td width="100" align="left">&nbsp;Dialysate Flow Rate</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="vp_3_w">
		<td width="100" align="left">&nbsp;Veneous Pressure</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="rec_4_w">
		<td width="200" align="left" bgcolor="#BEBEBE"><b>3<sup>rd Hour</sup></b></td>
		<td width="80" align="center" bgcolor="#BEBEBE">&nbsp;</td>
		<td width="80" align="center" bgcolor="#BEBEBE">&nbsp;</td>
		<td width="80" align="center" bgcolor="#BEBEBE">&nbsp;</td>
	</tr>
	<tr id="tmp_4_w">
		<td width="100" align="left">&nbsp;TMP</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="bp_4_w">
		<td width="100" align="left">&nbsp;Blood Pressure</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="pulse_4_w">
		<td width="100" align="left">&nbsp;Pulse</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		</tr>
	<tr id="hepn_3_w">
		<td width="100" align="left">&nbsp;Heparin</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="bf_4_w">
		<td width="100" align="left">&nbsp;Blood Flow Rate</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="uf_rate_3_w">
		<td width="100" align="left">&nbsp;UF Rate</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="df_4_w">
		<td width="100" align="left">&nbsp;Dialysate Flow Rate</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="vp_4_w">
		<td width="100" align="left">&nbsp;Veneous Pressure</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="rec_post_w">
		<td width="200" align="left" bgcolor="#BEBEBE"><b>4<sup>th Hour</sup></b></td>
		<td width="80" align="center" bgcolor="#BEBEBE">&nbsp;</td>
		<td width="80" align="center" bgcolor="#BEBEBE">&nbsp;</td>
		<td width="80" align="center" bgcolor="#BEBEBE">&nbsp;</td>
	</tr>
	<tr id="tmp_post_w">
		<td width="100" align="left">&nbsp;TMP</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="bp_post_w">
		<td width="100" align="left">&nbsp;Blood Pressure</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="pulse_post_w">
		<td width="100" align="left">&nbsp;Pulse</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="_w">
		<td width="100" align="left">&nbsp;Heparin</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="bf_post_w">
		<td width="100" align="left">&nbsp;Blood Flow Rate</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="uf_rate_4_w">
		<td width="100" align="left">&nbsp; UF Rate</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="df_post_w">
		<td width="100" align="left">&nbsp;Dialysate Flow Rate</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="vp_post_w">
		<td width="100" align="left">&nbsp;Veneous Pressure</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="_w">
		<td width="200" align="left" colspan="15" bgcolor="#BEBEBE"><b>POST HD</b></td>
	</tr>
	<tr id="bp_sit_w">
		<td width="100" align="left">&nbsp;Blood Pressure(Sit)</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="_w">
		<td width="100" align="left">&nbsp;Pulse</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="hd_weight_w">
		<td width="100" align="left">&nbsp;Blood Pressure(Stand)</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="pulse_stand_w">
		<td width="100" align="left">&nbsp;Pulse </td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="_w">
		<td width="100" align="left">&nbsp;Weight </td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="ultra_w">
		<td width="100" align="left">&nbsp;Achieved UF</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="kt_v_w">
		<td width="100" align="left">&nbsp;KT / V / CBV</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="_w">
		<td width="200" align="left" colspan="15" bgcolor="#BEBEBE"><b></b></td>
	</tr>
	<tr id="pre_verifier_name_w">
		<td width="100" align="left">&nbsp;<b>Started</b></td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="verifier_by_w">
		<td width="100" align="left">&nbsp;<b>Ending By</b> </td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="epo_type_w">
		<td width="100" align="left">&nbsp;<b>Erythropoetin</b> </td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="dose_type_w">
		<td width="100" align="left">&nbsp;<b>Doses</b> </td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="_w">
		<td width="100" align="left">&nbsp;<b>Medication</b> </td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
	<tr id="_w">
		<td width="100" align="left">&nbsp;<b>Doses</b> </td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
		<td width="80" align="center">&nbsp;</td>
	</tr>
</tbody></table>