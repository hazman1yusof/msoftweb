$(document).ready(function() {

	$("input[type='radio'][name_='Previous Surgery']").change(function(){
		let key = $(this).data('key');

		let value = $("input[type='radio'][name_='Previous Surgery'][data-key='"+key+"']:checked").val();

		if(value == 'op2'){
			$('#row_procedure_'+key+',#hr_procedure_'+key).hide();
		}else{
			$('#row_procedure_'+key+',#hr_procedure_'+key).show();
		}

	});

	init_procedure_div();
	function init_procedure_div(){
		for (var i = 0; i <= gkcasses_count; i++) {
			let value = $("input[type='radio'][name_='Previous Surgery'][data-key='"+i+"']:checked").val();

			if(value == 'op2'){
				$('#row_procedure_'+i+',#hr_procedure_'+i).hide();
			}else{
				$('#row_procedure_'+i+',#hr_procedure_'+i).show();
			}
		}
		
	}

	$("button.at_AcousticNeuroma_procedure").click(function(){
    let progress = $(this).data('progress');
    let at1_curr_index = $(this).data('at1_curr_index');
    let field_key = $(this).data('field_key');
    let regdate = $(this).data('regdate');
    let pm_idno = $(this).data('pm_idno');

		$("div.AcousticNeuroma_procedure_class_"+progress.replace(" ", "_")).append(
			`
				<div class="col-md-5 col-xs-12">
          <label>Name</label>
          <input type="text" 
            name_="Procedure" 
            ques_num='3'
            data-at_key='at1'
            data-at_index='`+at1_curr_index+`'
            data-at_field='field1'
            data-at_id='AcousticNeuroma_procedure_`+progress+`'
            id="Assessment_Acoustic_Neuroma Procedure_0_`+field_key+`" 
            value=""
            data-format="at" 
            data-pm_idno="`+pm_idno+`" 
            data-diagcode="AcousticNeuroma" 
            data-description="Assessment_Acoustic_Neuroma" 
            data-regdate="`+regdate+`"
            data-progress="`+progress+`"
            class="form-control"
          >
        </div>
        <div class="col-md-5 col-xs-12">
          <label>Date</label>
          <input type="date" 
            name_="Procedure" 
            ques_num='3'
            data-at_key='at1'
            data-at_index='`+at1_curr_index+`'
            data-at_field='field_date1'
            data-at_id='AcousticNeuroma_procedure_`+progress+`'
            id="Assessment_Acoustic_Neuroma Procedure_0_`+field_key+`" 
            value=""
            data-format="at" 
            data-pm_idno="`+pm_idno+`" 
            data-diagcode="AcousticNeuroma" 
            data-description="Assessment_Acoustic_Neuroma" 
            data-regdate="`+regdate+`"
            data-progress="`+progress+`"
            class="form-control"
          >
        </div>

			`

		);
    at1_curr_index = at1_curr_index+1;
    $(this).data('at1_curr_index',at1_curr_index);

    $("div[action='/study'] input, div[action='/study'] textarea").off('change',saveonchange);
    $("div[action='/study'] input, div[action='/study'] textarea").on('change',saveonchange);

	});

});