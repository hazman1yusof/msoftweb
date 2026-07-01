function formatter_btn(cellvalue, option, rowObject) {
	var ttl = 'OP';
	if(rowObject.epistycode == undefined){
		ttl = 'OP';
	}else{
		ttl = rowObject.epistycode;
	}

	let retval = `
		<span>
			<button title='Edit' type='button' class='btn btn-xs btn-warning btn-md command-edit' 
				style=''
				data-idno="`+rowObject.idno+`" 
				data-mrn="`+rowObject.MRN+`" 
				data-episno="`+rowObject.Episno+`" 
				onclick="open_iframe_patmast('`+rowObject.MRN+`','`+rowObject.Episno+`')"
				>
				<span class='glyphicon glyphicon-edit' aria-hidden='true'></span>
			</button>
			<button title='Episode' type='button' class='btn btn-xs btn-danger btn-md command-episode'
				data-idno="`+rowObject.idno+`" 
				data-mrn="`+rowObject.MRN+`" 
				data-episno="`+rowObject.Episno+`" 
				onclick="open_iframe_episode('`+rowObject.MRN+`','`+rowObject.Episno+`')"
				>
				<b>`+ttl+`</b>
			</button>
		</span>`

    return retval.replace(/[\t\n\r]/g, "");
}

function open_iframe_patmast(mrn,episno){
    $("#mdl_patient_iframe").attr('src','./pat_mast_iframe?mrn='+mrn+'&episno='+episno);
    $('#mdl_patient_info').modal({
    	closable  : false,
	    centered: false
	}).modal('show');
}

function open_iframe_episode(mrn,episno){
    $("#mdl_episode_iframe").attr('src','./episode_iframe?mrn='+mrn+'&episno='+episno);
    $('#mdl_episode_info').modal({
    	closable  : false,
	    centered: false
	}).modal('show');
}

window.open_iframe_close = function (data){ // inside the iframe
    $("#mdl_episode_iframe").attr('src','');
    $("#mdl_patient_iframe").attr('src','');
    $('#mdl_patient_info').modal('hide');
    $('#mdl_episode_info').modal('hide');
};