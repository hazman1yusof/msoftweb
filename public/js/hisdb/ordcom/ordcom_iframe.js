
var urlParam_gletdept = {
	action:'gletdept',
	url:'./pat_enq/table',
	mrn:null,
	episno:null,
}

var urlParam_gletitem = {
	action:'gletitem',
	url:'./pat_enq/table',
	mrn:null,
	episno:null,
}

$(document).ready(function(){
    $(".preloader").fadeOut();
    var lastrowdata = pat_mast_data;
    populate_ordcom_currpt(lastrowdata);
	refreshGrid('#jqGrid_phar',urlParam_phar,'add');
	$("#jqGrid_phar").jqGrid('setGridWidth', Math.floor($("#jqGrid_ordcom_c")[0].offsetWidth-$("#jqGrid_ordcom_c")[0].offsetLeft-58));
	$("#cyclebill_dtl").attr('href',"./ordcom/table?action=showpdf_detail&mrn="+lastrowdata.MRN+"&episno="+lastrowdata.Episno);
	$("#cyclebill_summ").attr('href',"./ordcom/table?action=showpdf_summ&mrn="+lastrowdata.MRN+"&episno="+lastrowdata.Episno);
	$("#phar_label_link").attr('href',"./pat_mast/patlabel?action=pharlabel&mrn="+lastrowdata.MRN+"&episno="+lastrowdata.Episno);
	$("#phar_prescription_link").attr('href',"./ordcom/table?action=showpdf_detail&mrn="+lastrowdata.MRN+"&episno="+lastrowdata.Episno+"&invcode=50&pres_=1");
});

function allgroupformat(cellvalue, options, rowObject){
	if(cellvalue == '1'){
		return '<span data-orig='+cellvalue+'>Yes</span>';
	}else if(cellvalue == '0'){
		return '<span data-orig='+cellvalue+'>No</span>';
	}else{
		return '<span data-orig='+cellvalue+'></span>';
	}
}

function allgroupformat2(cellvalue, options, rowObject){
	if(cellvalue == '1'){
		return 'Yes';
	}else if(cellvalue == '0'){
		return 'No';
	}else{
		return cellvalue;
	}
}

function allgroupunformat(cellvalue, options, rowObject){
	return $(rowObject).find('span').data('orig');
}

function btn_refno_info_onclick(){
	return null
}