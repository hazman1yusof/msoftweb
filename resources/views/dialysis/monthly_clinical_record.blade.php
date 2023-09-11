<div class="ui mini form" style="width: 70% !important;margin: auto;">
  <button class="ui basic small button right floated"  id="print_rec_m">
    <i class="print icon"></i> Print
  </button>

  <form method="post" action="./pivot_post?action=pat_monthly">
    {{ csrf_field() }}
    <input type="hidden" name="pat_name" id="excel_pat_name_m" required>
    <input type="hidden" name="mrn" id="excel_mrn_m" required>
    <input type="hidden" name="episno" id="excel_episno_m" required>
    <input type="hidden" name="month" id="excel_month_m" required>
    <input type="hidden" name="year" id="excel_year_m" required>
      <button class="ui blue small button right floated" type="submit" id="print_excel_m">
        <i class="file excel icon"></i> Download Excel
      </button>
  </form>

  <div class="two fields" style="margin-top:12px;">
    <div class="inline field">
        <label>Select Month</label>
        <div class="ui calendar" id="month_year_calendar">
          <div class="ui input left icon">
            <i class="calendar icon"></i>
            <input type="text" placeholder="Date" id="month_year">
          </div>
        </div>

      <button type="button" class="ui mini blue submit button" id="rec_monthly_but">Go</button>
    </div>
  </div>

  <!-- <div class="three fields">
    <div class="field">
      <label>Select Month</label>
      <input type="month" id="selectmonth" >&nbsp;&nbsp;
      <div class="ui calendar" id="month_year_calendar">
          <div class="ui input left icon">
            <i class="calendar icon"></i>
            <input type="text" placeholder="Date" id="trxdate">
          </div>
        </div>
      <button type="button" class="ui mini blue submit button" id="rec_monthly_but">Go</button>
    </div>
  </div> -->
</div>
    
<table class="table table-bordered diatbl" id="dia_monthly">
<tbody>
    <tr>
        <th width="100" rowspan="2" bgcolor="#DCDCDC" class="listHeading"><b>Description</b></th>
        <th width="80" align="center" colspan="14" bgcolor="#DCDCDC" class="listHeading"><b>Date</b></th>
    </tr>
    <tr class="listHeading" id="visit_date_m">
        <td width="80" align="center">&nbsp;</td>
        <td width="80" align="center">&nbsp;</td>
        <td width="80" align="center">&nbsp;</td>
        <td width="80" align="center">&nbsp;</td>
        <td width="80" align="center">&nbsp;</td>
        <td width="80" align="center">&nbsp;</td>
        <td width="80" align="center">&nbsp;</td>
        <td width="80" align="center">&nbsp;</td>
        <td width="80" align="center">&nbsp;</td>
        <td width="80" align="center">&nbsp;</td>
        <td width="80" align="center">&nbsp;</td>
        <td width="80" align="center">&nbsp;</td>
        <td width="80" align="center">&nbsp;</td>
        <td width="80" align="center">&nbsp;</td>
    </tr>
<tr id="start_time_m">
    <td width="200" align="left" class="txtaln">START TREATMENT</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="dialyser_m">
    <td width="100" align="left" class="txtaln">&nbsp;DIALYSER</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="no_of_use_m">
    <td width="100" align="left" class="txtaln">&nbsp;NO OF USE</td>
    <td width="80" align="center"></td>
    <td width="80" align="center"></td>
    <td width="80" align="center"></td>
    <td width="80" align="center"></td>
    <td width="80" align="center"></td>
    <td width="80" align="center"></td>
    <td width="80" align="center"></td>
    <td width="80" align="center"></td>
    <td width="80" align="center"></td>
    <td width="80" align="center"></td>
    <td width="80" align="center"></td>
    <td width="80" align="center"></td>
    <td width="80" align="center"></td>
    <td width="80" align="center"></td>
</tr>
<tr id="target_uf_m">
    <td width="100" align="left" class="txtaln">&nbsp;TARGET UF</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="heparin_bolus_m">
    <td width="100" align="left" class="txtaln">&nbsp;HEPARIN BOLUS</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="heparin_maintainance_m">
    <td width="100" align="left" class="txtaln">&nbsp;HEPARIN MAINTAINANCE</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="dialysate_ca_m">
    <td width="100" align="left" class="txtaln">&nbsp;DIALYSATE CA</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="pre_weight_m">
    <td width="100" align="left" class="txtaln">&nbsp;WEIGHT</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="idwg_m">
    <td width="100" align="left" class="txtaln">&nbsp;IDWG (Pre-Post Last HD) </td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="prehd_tmp_m">
    <td width="100" align="left" class="txtaln">&nbsp;TMP</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="prehd_bp_m">
    <td width="100" align="left" class="txtaln">&nbsp;BLOOD PRESSURE</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="pulse_pre_m">
    <td width="100" align="left" class="txtaln">&nbsp;PULSE</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="prehd_bfr_m">
    <td width="100" align="left" class="txtaln">&nbsp;BLOOD FLOW RATE</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="prehd_dfr_m">
    <td width="100" align="left" class="txtaln">&nbsp;DIALYSATE FLOW RATE</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="prehd_vp_m">
    <td width="100" align="left" class="txtaln">&nbsp;VENEOUS PRESSURE</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="user_prehd_m">
    <td width="100" align="left" class="txtaln">&nbsp;ADDED BY</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>

<tr class="listHeading color_1sthour_b" id="1_tc_m">
    <td width="200" align="left" ><b>1<sup>st</sup> Hour</b></td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
</tr>
<tr id="1_bp_m" class="color_1sthour">
    <td width="100" align="left" class="txtaln">&nbsp;BLOOD PRESSURE</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="1_pulse_m" class="color_1sthour">
    <td width="100" align="left" class="txtaln">&nbsp;PULSE</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="1_bfr_m" class="color_1sthour">
    <td width="100" align="left" class="txtaln">&nbsp;BLOOD FLOW RATE</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="1_vp_m" class="color_1sthour">
    <td width="100" align="left" class="txtaln">&nbsp;VENEOUS PRESSURE</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="1_tmp_m" class="color_1sthour">
    <td width="100" align="left" class="txtaln">&nbsp;TMP</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="1_dh_m" class="color_1sthour">
    <td width="100" align="left" class="txtaln">&nbsp;DELIVERED HEPARIN</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="1_uv_m" class="color_1sthour">
    <td width="100" align="left" class="txtaln">&nbsp;UF VOLUME</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="1_f_m" class="color_1sthour">
    <td width="100" align="left" class="txtaln">&nbsp;FLUIDS</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="user_1_m" class="color_1sthour">
    <td width="100" align="left" class="txtaln">&nbsp;ADDED BY</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>

<tr class="listHeading color_2ndthour_b" id="2_tc_m">
    <td width="200" align="left" ><b>2<sup>nd</sup> Hour</b></td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
</tr>
<tr id="2_bp_m" class="color_2ndthour">
    <td width="100" align="left" class="txtaln">&nbsp;BLOOD PRESSURE</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="2_pulse_m" class="color_2ndthour">
    <td width="100" align="left" class="txtaln">&nbsp;PULSE</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="2_bfr_m" class="color_2ndthour">
    <td width="100" align="left" class="txtaln">&nbsp;BLOOD FLOW RATE</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="2_vp_m" class="color_2ndthour">
    <td width="100" align="left" class="txtaln">&nbsp;VENEOUS PRESSURE</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="2_tmp_m" class="color_2ndthour">
    <td width="100" align="left" class="txtaln">&nbsp;TMP</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="2_dh_m" class="color_2ndthour">
    <td width="100" align="left" class="txtaln">&nbsp;DELIVERED HEPARIN</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="2_uv_m" class="color_2ndthour">
    <td width="100" align="left" class="txtaln">&nbsp;UF VOLUME</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="2_f_m" class="color_2ndthour">
    <td width="100" align="left" class="txtaln">&nbsp;FLUIDS</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="user_2_m" class="color_2ndthour">
    <td width="100" align="left" class="txtaln">&nbsp;ADDED BY</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>

<tr class="listHeading color_3rdhour_b" id="3_tc_m">
    <td width="200" align="left" ><b>3<sup>rd</sup> Hour</b></td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
</tr>
<tr id="3_bp_m" class="color_3rdhour">
    <td width="100" align="left" class="txtaln">&nbsp;BLOOD PRESSURE</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="3_pulse_m" class="color_3rdhour">
    <td width="100" align="left" class="txtaln">&nbsp;PULSE</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="3_bfr_m" class="color_3rdhour">
    <td width="100" align="left" class="txtaln">&nbsp;BLOOD FLOW RATE</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="3_vp_m" class="color_3rdhour">
    <td width="100" align="left" class="txtaln">&nbsp;VENEOUS PRESSURE</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="3_tmp_m" class="color_3rdhour">
    <td width="100" align="left" class="txtaln">&nbsp;TMP</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="3_dh_m" class="color_3rdhour">
    <td width="100" align="left" class="txtaln">&nbsp;DELIVERED HEPARIN</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="3_uv_m" class="color_3rdhour">
    <td width="100" align="left" class="txtaln">&nbsp;UF VOLUME</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="3_f_m" class="color_3rdhour">
    <td width="100" align="left" class="txtaln">&nbsp;FLUIDS</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="user_3_m" class="color_3rdhour">
    <td width="100" align="left" class="txtaln">&nbsp;ADDED BY</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>

<tr class="listHeading color_4thhour_b" id="4_tc_m">
    <td width="200" align="left" ><b>4<sup>th</sup> Hour</b></td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
</tr>
<tr id="4_bp_m" class="color_4thhour">
    <td width="100" align="left" class="txtaln">&nbsp;BLOOD PRESSURE</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="4_pulse_m" class="color_4thhour">
    <td width="100" align="left" class="txtaln">&nbsp;PULSE</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="4_bfr_m" class="color_4thhour">
    <td width="100" align="left" class="txtaln">&nbsp;BLOOD FLOW RATE</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="4_vp_m" class="color_4thhour">
    <td width="100" align="left" class="txtaln">&nbsp;VENEOUS PRESSURE</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="4_tmp_m" class="color_4thhour">
    <td width="100" align="left" class="txtaln">&nbsp;TMP</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="4_dh_m" class="color_4thhour">
    <td width="100" align="left" class="txtaln">&nbsp;DELIVERED HEPARIN</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="4_uv_m" class="color_4thhour">
    <td width="100" align="left" class="txtaln">&nbsp;UF VOLUME</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="4_f_m" class="color_4thhour">
    <td width="100" align="left" class="txtaln">&nbsp;FLUIDS</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="user_4_m" class="color_4thhour">
    <td width="100" align="left" class="txtaln">&nbsp;ADDED BY</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>

<tr class="listHeading color_5thhour_b" id="5_tc_m">
    <td width="200" align="left" ><b>5<sup>th</sup> Hour</b></td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
    <td width="80" align="center" >&nbsp;</td>
</tr>
<tr id="5_bp_m" class="color_5thhour">
    <td width="100" align="left" class="txtaln">&nbsp;BLOOD PRESSURE</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="5_pulse_m" class="color_5thhour">
    <td width="100" align="left" class="txtaln">&nbsp;PULSE</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="5_bfr_m" class="color_5thhour">
    <td width="100" align="left" class="txtaln">&nbsp;BLOOD FLOW RATE</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="5_vp_m" class="color_5thhour">
    <td width="100" align="left" class="txtaln">&nbsp;VENEOUS PRESSURE</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="5_tmp_m" class="color_5thhour">
    <td width="100" align="left" class="txtaln">&nbsp;TMP</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="5_dh_m" class="color_5thhour">
    <td width="100" align="left" class="txtaln">&nbsp;DELIVERED HEPARIN</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="5_uv_m" class="color_5thhour">
    <td width="100" align="left" class="txtaln">&nbsp;UF VOLUME</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="5_f_m" class="color_5thhour">
    <td width="100" align="left" class="txtaln">&nbsp;FLUIDS</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="user_5_m" class="color_5thhour">
    <td width="100" align="left" class="txtaln">&nbsp;ADDED BY</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>

<tr class="listHeading">
    <td width="200" align="left" colspan="15" bgcolor="#DCDCDC"><b>POST HD</b></td>
</tr>

<tr id="posthd_bp_m">
    <td width="100" align="left" class="txtaln">&nbsp;BLOOD PRESSURE</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="posthd_temperatue_m">
    <td width="100" align="left" class="txtaln">&nbsp;TEMPERATURE</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="posthd_pulse_m">
    <td width="100" align="left" class="txtaln">&nbsp;PULSE </td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="posthd_respiratory_m">
    <td width="100" align="left" class="txtaln">&nbsp;RESPIRATORY</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="post_weight_m">
    <td width="100" align="left" class="txtaln">&nbsp;POST WEIGHT</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="weight_loss_m">
    <td width="100" align="left" class="txtaln">&nbsp;WEIGHT LOST</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="time_complete_m">
    <td width="100" align="left" class="txtaln">&nbsp;TIME COMPLETED</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="hd_adequancy_m">
    <td width="100" align="left" class="txtaln">&nbsp;HD ADEQUACY</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="ktv_m">
    <td width="100" align="left" class="txtaln">&nbsp;KT/V</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>
<tr id="medication_m">
    <td width="100" align="left" class="txtaln">&nbsp;MEDICATION</td>
    <td width="80" align="left" class="med_td">&nbsp;</td>
    <td width="80" align="left" class="med_td">&nbsp;</td>
    <td width="80" align="left" class="med_td">&nbsp;</td>
    <td width="80" align="left" class="med_td">&nbsp;</td>
    <td width="80" align="left" class="med_td">&nbsp;</td>
    <td width="80" align="left" class="med_td">&nbsp;</td>
    <td width="80" align="left" class="med_td">&nbsp;</td>
    <td width="80" align="left" class="med_td">&nbsp;</td>
    <td width="80" align="left" class="med_td">&nbsp;</td>
    <td width="80" align="left" class="med_td">&nbsp;</td>
    <td width="80" align="left" class="med_td">&nbsp;</td>
    <td width="80" align="left" class="med_td">&nbsp;</td>
    <td width="80" align="left" class="med_td">&nbsp;</td>
    <td width="80" align="left" class="med_td">&nbsp;</td>
</tr>
<tr id="user_posthd_m">
    <td width="100" align="left" class="txtaln">&nbsp;ADDED BY</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
    <td width="80" align="center">&nbsp;</td>
</tr>


</tbody>
</table>