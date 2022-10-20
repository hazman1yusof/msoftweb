$(document).ready(function() {
	$("input[type='radio'][name_='Spetzler Martin Grading Scale - Size'], input[type='radio'][name_='Spetzler Martin Grading Scale - Location'] ,input[type='radio'][name_='Spetzler Martin Grading Scale - Pattern of venous drainage']").change(function(){
		let key = $(this).data('key');

		let size = $("input[type='radio'][name_='Spetzler Martin Grading Scale - Size'][data-key='"+key+"']:checked");
		let loc = $("input[type='radio'][name_='Spetzler Martin Grading Scale - Location'][data-key='"+key+"']:checked");
		let pat = $("input[type='radio'][name_='Spetzler Martin Grading Scale - Pattern of venous drainage'][data-key='"+key+"']:checked");

		if(size.length>0){
			size_p = size.data('point');
		}
		if(loc.length>0){
			loc_p = loc.data('point');
		}
		if(pat.length>0){
			pat_p = pat.data('point');
		}

		var totalScore = parseInt(size_p)+parseInt(loc_p)+parseInt(pat_p);

		$("#Assesment_AVM_Total_Score_0_"+key).val(totalScore);
		$("#Assesment_AVM_Total_Score_0_"+key).change();

	});

});