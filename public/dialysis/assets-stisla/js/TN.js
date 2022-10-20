$(document).ready(function() {
	$("input[type='radio'][name_='BNI Pain'], input[type='radio'][name_='BNI Numb']").change(function(){
		let key = $(this).data('key');

		let pain = $("input[type='radio'][name_='BNI Pain'][data-key='"+key+"']:checked");
		let numb = $("input[type='radio'][name_='BNI Numb'][data-key='"+key+"']:checked");

		if(pain.length>0){
			pain_p = pain.data('point');
		}
		if(numb.length>0){
			numb_p = numb.data('point');
		}

		var totalScore = parseInt(pain_p)+parseInt(numb_p)

		$("#Assessment_TN_Total_BNI_Score_0_"+key).val(totalScore);
		$("#Assessment_TN_Total_BNI_Score_0_"+key).change();

	});

});