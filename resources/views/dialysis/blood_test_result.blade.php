<div class="ui mini form" style="width: 70% !important;margin: auto;">
  <button class="ui basic small button right floated"  id="print_rec_br">
    <i class="print icon"></i> Print
  </button>
  <div class="fields" style="margin-top:12px;">
    <div class="inline field">
        <label>Select Month</label>
        <div class="ui calendar" id="month_year_br">
          <div class="ui input left icon">
            <i class="calendar icon"></i>
            <input type="text" placeholder="Date" id="month_year_br">
          </div>
        </div>

      <button type="button" class="ui mini blue submit button" id="rec_but_br">Go</button>
    </div>
    <div class="ui menu" id="paging_br" style="margin-top:0px">
          
    </div>
  </div>

  <!-- <div class="two fields" style="margin-top:12px;">
   <div class="fields">
     
   </div>
  </div> -->

</div>

    
<table class="table table-bordered diatbl" id="bloodres">
<tbody>
    <tr>
        <th width="100" rowspan="2" bgcolor="#DCDCDC" class="listHeading"><b>Description</b></th>
        <th width="80" align="center" colspan="14" bgcolor="#DCDCDC" class="listHeading"><b>Date</b></th>
    </tr>
    <tr class="listHeading" id="br_sampledate">
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_esr">
        <td width="200" align="left" class="txtaln">ERYTHROCYTE SEDIMENTATION RATE (ESR)</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_trbc">
        <td width="200" align="left" class="txtaln">TOTAL RBC(TRBC)</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_hb">
        <td width="200" align="left" class="txtaln">HAEMOGLOBIN(Hb)</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_pcv">
        <td width="200" align="left" class="txtaln">PACKED CELL VOLUME(PCV)</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_mcv">
        <td width="200" align="left" class="txtaln">MCV</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_mch">
        <td width="200" align="left" class="txtaln">MCH</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_mchc">
        <td width="200" align="left" class="txtaln">MCHC</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_pc">
        <td width="200" align="left" class="txtaln">PLATELET COUNT(PC)</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_twbc">
        <td width="200" align="left" class="txtaln">TOTAL WHITE BLOOD CELL(TWBC)</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_dc">
        <td width="200" align="left" class="txtaln">DIFFERENTIAL COUNT(DC)</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_preurea">
        <td width="200" align="left" class="txtaln">PRE UREA</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_posturea">
        <td width="200" align="left" class="txtaln">POST UREA</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_creatinine">
        <td width="200" align="left" class="txtaln">CREATININE</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_calcium">
        <td width="200" align="left" class="txtaln">CALCIUM</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_inorganicphosphate">
        <td width="200" align="left" class="txtaln">INORGANIC PHOSPHATE</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_uricacid">
        <td width="200" align="left" class="txtaln">URIC ACID</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_sodium">
        <td width="200" align="left" class="txtaln">SODIUM</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_potassium">
        <td width="200" align="left" class="txtaln">POTASSIUM</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_chloride">
        <td width="200" align="left" class="txtaln">CHLORIDE</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_glucose">
        <td width="200" align="left" class="txtaln">GLUCOSE</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_hc03">
        <td width="200" align="left" class="txtaln">BICARBONATE(HCO3)</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_totalcholesteral">
        <td width="200" align="left" class="txtaln">TOTAL CHOLESTERAL</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_hdlcholesteral">
        <td width="200" align="left" class="txtaln">HDL CHOLESTERAL</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_ldlcholesteral">
        <td width="200" align="left" class="txtaln">LDL CHOLESTERAL</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_triglycerides">
        <td width="200" align="left" class="txtaln">TRIGLYCERIDES</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_hdlratio">
        <td width="200" align="left" class="txtaln">TOTAL/HDL RATIO</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_totalprotein">
        <td width="200" align="left" class="txtaln">TOTAL PROTEIN</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_albumin">
        <td width="200" align="left" class="txtaln">ALBUMIN</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_globulin">
        <td width="200" align="left" class="txtaln">GLOBULIN</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_albuminglobulinratio">
        <td width="200" align="left" class="txtaln">ALBUMIN/GLOBULIN RATIO</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_totalbilirubin">
        <td width="200" align="left" class="txtaln">TOTAL BILIRUBIN</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_alkalinephosphatase">
        <td width="200" align="left" class="txtaln">ALKALINE PHOSPHATASE</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_ast">
        <td width="200" align="left" class="txtaln">SGOT(AST)</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_alt">
        <td width="200" align="left" class="txtaln">SGPT(ALT)</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_ggt">
        <td width="200" align="left" class="txtaln">GAMMA GLUTAMYL TRANSPEPTIDASE(GGT)</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_afp">
        <td width="200" align="left" class="txtaln">ALPHA FETOPROTEIN(AFP)</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_vdrl">
        <td width="200" align="left" class="txtaln">VDRL</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_hbsantibody">
        <td width="200" align="left" class="txtaln">HBs ANTIBODY</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_hbsantigen">
        <td width="200" align="left" class="txtaln">HBs ANTIGEN</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_hiv12">
        <td width="200" align="left" class="txtaln">HIV I & II</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_hepatitiscantibody">
        <td width="200" align="left" class="txtaln">HEPATITIS C ANTIBODY</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_pthintact">
        <td width="200" align="left" class="txtaln">PTH INTACT</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_hba1c">
        <td width="200" align="left" class="txtaln">HBA1C</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_imm">
        <td width="200" align="left" class="txtaln">FAECAL OCCULT BLOOD(IMM)</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_hbvdnarealtimepcr">
        <td width="200" align="left" class="txtaln">HBV DNA REAL-TIME PCR</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_hepatitisbcoreantibody">
        <td width="200" align="left" class="txtaln">HEPATITIS B CORE ANTIBODY</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_tsh">
        <td width="200" align="left" class="txtaln">TSH</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_freet4">
        <td width="200" align="left" class="txtaln">FREE T4</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_freet3">
        <td width="200" align="left" class="txtaln">FREE T3</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_neutrophil">
        <td width="200" align="left" class="txtaln">NEUTROPHIL</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_lymphocyte">
        <td width="200" align="left" class="txtaln">LYMPHOCYTE</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_monocyte">
        <td width="200" align="left" class="txtaln">MONOCYTE</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_eosinophil">
        <td width="200" align="left" class="txtaln">EOSINOPHIL</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_basophil">
        <td width="200" align="left" class="txtaln">BASOPHIL</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_atypicallymphocyte">
        <td width="200" align="left" class="txtaln">ATYPICAL LYMPHOCYTE</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_tibc">
        <td width="200" align="left" class="txtaln">TIBC</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_ferritin">
        <td width="200" align="left" class="txtaln">FERRITIN</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_serumiron">
        <td width="200" align="left" class="txtaln">SERUM IRON</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_patientname">
        <td width="200" align="left" class="txtaln">PATIENT NAME</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_controltime">
        <td width="200" align="left" class="txtaln">CONTROL TIME</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_inr">
        <td width="200" align="left" class="txtaln">INR</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_bloodgroup">
        <td width="200" align="left" class="txtaln">BLOOD GROUP</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_tppa">
        <td width="200" align="left" class="txtaln">TPPA</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_transferrinsaturation">
        <td width="200" align="left" class="txtaln">TRANSFERRIN SATURATION</td>
        <td width="80" align="center">&nbsp;</td>
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
    <tr id="br_vitaminb12">
        <td width="200" align="left" class="txtaln">VITAMIN B12</td>
        <td width="80" align="center">&nbsp;</td>
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