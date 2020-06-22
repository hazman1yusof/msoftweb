
$(document).ready(function () {

	disableForm('#formWard');

	$("#new_ward").click(function(){
		button_state_ward('wait');
		enableForm('#formWard');
		rdonly('#formWard');
		// dialog_mrn_edit.on();
		
	});

	$("#edit_ward").click(function(){
		button_state_ward('wait');
		enableForm('#formWard');
		rdonly('#formWard');
		// dialog_mrn_edit.on();
		
	});

	$("#save_ward").click(function(){
		disableForm('#formWard');
		if( $('#formWard').isValid({requiredFields: ''}, conf, true) ) {
			saveForm_ward(function(){
				$("#cancel_ward").data('oper','edit');
				$("#cancel_ward").click();
				$('#refresh_jqGrid').click();
			});
		}else{
			enableForm('#formWard');
			rdonly('#formWard');
		}

	});

	$("#cancel_ward").click(function(){
		disableForm('#formWard');
		button_state_ward($(this).data('oper'));
		// dialog_mrn_edit.off();

	});

	// to format number input to two decimal places (0.00)
	$(".floatNumberField").change(function() {
		$(this).val(parseFloat($(this).val()).toFixed(2));
	});

	// to limit to two decimal places (onkeypress)
	$(document).on('keydown', 'input[pattern]', function(e){
		var input = $(this);
		var oldVal = input.val();
		var regex = new RegExp(input.attr('pattern'), 'g');
	  
		setTimeout(function(){
			var newVal = input.val();
			if(!regex.test(newVal)){
				input.val(oldVal); 
		  	}
		}, 0);
	});

});

var errorField = [];
conf = {
	modules : 'logic',
	language: {
		requiredFields: 'You have not answered all required fields'
	},
	onValidate: function ($form) {
		if (errorField.length > 0) {
			return {
				element: $(errorField[0]),
				message: ''
			}
		}
	},
};

// button_state_ward('empty');
function button_state_ward(state){
	switch(state){
		case 'empty':
			$("#toggle_ward").removeAttr('data-toggle');
			$('#cancel_ward').data('oper','add');
			$('#new_ward,#save_ward,#cancel_ward,#edit_ward').attr('disabled',true);
			break;
		case 'add':
			$("#toggle_ward").attr('data-toggle','collapse');
			$('#cancel_ward').data('oper','add');
			$("#new_ward").attr('disabled',false);
			$('#save_ward,#cancel_ward,#edit_ward').attr('disabled',true);
			break;
		case 'edit':
			$("#toggle_ward").attr('data-toggle','collapse');
			$('#cancel_ward').data('oper','edit');
			$("#edit_ward").attr('disabled',false);
			$('#save_ward,#cancel_ward,#new_ward').attr('disabled',true);
			break;
		case 'wait':
			dialog_tri_col.on();
			examination.on().enable();
			$("#toggle_ward").attr('data-toggle','collapse');
			$("#save_ward,#cancel_ward").attr('disabled',false);
			$('#edit_ward,#new_ward').attr('disabled',true);
			break;
	}

	// if(!moment(gldatepicker_date).isSame(moment(), 'day')){
	// 	$('#new_ward,#save_ward,#cancel_ward,#edit_ward').attr('disabled',true);
	// }
}

function populate_formWard(obj,rowdata){

	//panel header
	$('#name_show_ward').text(obj.name);
	$('#mrn_show_ward').text(obj.mrn);

	//formWard
	$('#mrn_ward').val(obj.mrn);
	$("#episno_ward").val(obj.episno);

	var saveParam={
        action:'get_table_ward',
    }
    var postobj={
    	_token : $('#csrf_token').val(),
    	mrn:obj.mrn,
    	episno:obj.episno

    };

    $.post( "/wardpanel/form?"+$.param(saveParam), $.param(postobj), function( data ) {
        
    },'json').fail(function(data) {
        alert('there is an error');
    }).success(function(data){
    	if(!$.isEmptyObject(data)){
			autoinsert_rowdata("#formWard",data.ward);
			autoinsert_rowdata("#formWard",data.ward_gen);
			autoinsert_rowdata("#formWard",data.ward_exm);
			button_state_ward('edit');
        }else{
			button_state_ward('add');
        }

    });

}

function autoinsert_rowdata(form,rowData){
	$.each(rowData, function( index, value ) {
		var input=$(form+" [name='"+index+"']");
		if(input.is("[type=radio]")){
			$(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
		}else if(input.is("[type=checkbox]")){
			if(value==1){
				$(form+" [name='"+index+"']").prop('checked', true);
			}
		}else{
			input.val(value);
		}
	});
}

function saveForm_ward(callback){
	var saveParam={
        action:'save_table_ward',
        oper:$("#cancel_ward").data('oper')
    }
    var postobj={
    	_token : $('#csrf_token').val(),
    	// sex_edit : $('#sex_edit').val(),
    	// idtype_edit : $('#idtype_edit').val()

    };

    values = $("#formWard").serializeArray();

    values = values.concat(
        $('#formWard input[type=checkbox]:not(:checked)').map(
        function() {
            return {"name": this.name, "value": 0}
        }).get()
    );

    values = values.concat(
        $('#formWard input[type=checkbox]:checked').map(
        function() {
            return {"name": this.name, "value": 1}
        }).get()
	);
	
	values = values.concat(
        $('#formWard input[type=radio]:checked').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
    );

    values = values.concat(
        $('#formWard select').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
	);

    // values = values.concat(
    //     $('#formWard input[type=radio]:checked').map(
    //     function() {
    //         return {"name": this.name, "value": this.value}
    //     }).get()
    // );

    $.post( "/wardpanel/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function( data ) {
        
    },'json').fail(function(data) {
        // alert('there is an error');
        callback();
    }).success(function(data){
        callback();
    });
}

var dialog_tri_col = new ordialog(
	'ward_tri_col','sysdb.sysparam',"#formWard input[name='triagecolor']",errorField,
	{	colModel:
		[
			{label:'Color',name:'colorcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
			{label:'Description',name:'description',width:400,classes:'pointer', hidden: true,canSearch:false,or_search:true},
		],
		urlParam: {
			url:'./sysparam_triage_color',
			filterCol:['recstatus','compcode'],
			filterVal:['A', 'session.compcode']
			},
		ondblClickRow:function(event){

			$(dialog_tri_col.textfield).val(selrowData("#"+dialog_tri_col.gridname)['description']);
			$(dialog_tri_col.textfield)
							.removeClass( "red" )
							.removeClass( "yellow" )
							.removeClass( "green" )
							.addClass( selrowData("#"+dialog_tri_col.gridname)['description'] );

			$(dialog_tri_col.textfield).next()
							.removeClass( "red" )
							.removeClass( "yellow" )
							.removeClass( "green" )
							.addClass( selrowData("#"+dialog_tri_col.gridname)['description'] );

		},
		onSelectRow:function(rowid, selected){
			$('#'+dialog_tri_col.gridname+' tr#'+rowid).dblclick();
			// $(dialog_tri_col.textfield).val(selrowData("#"+dialog_tri_col.gridname)['description']);

		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
			}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
				$('#'+obj.dialogname).dialog('close');
			}
		},
		loadComplete: function(data,obj){
			$("input[type='radio'][name='colorcode_select']").click(function(){
				let self = this;
				delay(function(){
						$(self).parent().click();
				}, 100 );
			});

			var gridname = '#'+obj.gridname;
			var ids = $(gridname).jqGrid("getDataIDs"), l = ids.length, i, rowid, status;
	        for (i = 0; i < l; i++) {
	            rowid = ids[i];
	            colorcode = $(gridname).jqGrid("getCell", rowid, "description");

	            $(gridname+' tr#' + rowid).addClass(colorcode);

	        }
		}
	},{
		title:"Select Bed Status",
		open: function(){
			dialog_tri_col.urlParam.filterCol = ['recstatus','compcode'];
			dialog_tri_col.urlParam.filterVal = ['A', 'session.compcode'];
		},
		width:5/10 * $(window).width()
	},'urlParam','radio','tab','table'
);
dialog_tri_col.makedialog();

function tri_color_set(empty){
	if(empty == 'empty'){
		$(dialog_tri_col.textfield).removeClass( "red" ).removeClass( "yellow" ).removeClass( "green" );

		$(dialog_tri_col.textfield).next().removeClass( "red" ).removeClass( "yellow" ).removeClass( "green" );
	}

	var color = $(dialog_tri_col.textfield).val();
	$(dialog_tri_col.textfield)
					.removeClass( "red" )
					.removeClass( "yellow" )
					.removeClass( "green" )
					.addClass( color );

	$(dialog_tri_col.textfield).next()
					.removeClass( "red" )
					.removeClass( "yellow" )
					.removeClass( "green" )
					.addClass( color );
}

var examination = new examination();
function examination(){
	this.examarray=[];
	this.on=function(){
		$("#ward_exam_plus").on('click',{data:this},addexam);
		return this;
	}

	this.empty=function(){
		this.examarray.length=0;
		$("#ward_exam_div").html('');
		return this;
	}

	this.off=function(){
		$("#ward_exam_plus").off('click',addexam);
		return this;
	}

	this.disable=function(){
		disableForm('#ward_exam_div');
		return this;
	}

	this.enable=function(){
		enableForm('#ward_exam_div');
		return this;
	}

	this.loadexam = function(){
		this.examarray.forEach(function(item, index){
			$("#ward_exam_div").append(`
				<hr>
				<div class="form-group">
					<input type="hidden" name="examidno_`+index+`" value="`+item.idno+`">
					<div class="col-md-2">Exam</div>
					<div class="col-md-10">
						<select class="form-select form-control" name="examsel_`+index+`" id="exam_`+index+`">
							<option value="General">General</option>
							<option value="Head" >Head</option>
							<option value="Neck" >Neck</option>
							<option value="Throat" >Throat</option>
							<option value="Abdomen" >Abdomen</option>
							<option value="Eye" >Eye</option>
							<option value="Lungs" >Lungs</option>
							<option value="Neuro" >Neuro</option>
							<option value="Limbs" >Limbs</option>
							<option value="Chest" >Chest</option>
							<option value="BACK" >BACK</option>
							<option value="Heart" >Heart</option>
							<option value="Skin" >Skin</option>
							<option value="Musculosketel" >Musculosketel</option>
							<option value="Neurological" >Neurological</option>
							<option value="stomach" >stomach</option>
							<option value="middle finger" >middle finger</option>
						</select>
					</div>
				</div>

				<div class="form-group">
					<div class="col-md-2">Note</div>
					<div class="col-md-10">
						<textarea class="form-control input-sm uppercase" rows="5"  name="examnote_`+index+`" id="examnote_`+index+`">`+item.examnote+`</textarea>
					</div>
				</div>
			`);

			$("#exam_"+index).val(item.exam);
		});
		return this;
	}

	function addexam(event){
		var obj = event.data.data;
		var currentid = 0;
		if(obj.examarray.length==0){
			obj.examarray.push(0);
			currentid = 0;
		}else{
			currentid = obj.examarray.length;
			obj.examarray.push(obj.examarray.length);
		}

		$("#ward_exam_div").append(`
			<hr>
			<div class="form-group">
				<input type="hidden" name="examidno_`+currentid+`" value="0">
				<div class="col-md-2">Exam</div>
				<div class="col-md-10">
					<select class="form-select form-control" name="examsel_`+currentid+`" id="exam_`+currentid+`">
						<option value="General" selected="selected" >General</option>
						<option value="Head" >Head</option>
						<option value="Neck" >Neck</option>
						<option value="Throat" >Throat</option>
						<option value="Abdomen" >Abdomen</option>
						<option value="Eye" >Eye</option>
						<option value="Lungs" >Lungs</option>
						<option value="Neuro" >Neuro</option>
						<option value="Limbs" >Limbs</option>
						<option value="Chest" >Chest</option>
						<option value="BACK" >BACK</option>
						<option value="Heart" >Heart</option>
						<option value="Skin" >Skin</option>
						<option value="Musculosketel" >Musculosketel</option>
						<option value="Neurological" >Neurological</option>
						<option value="stomach" >stomach</option>
						<option value="middle finger" >middle finger</option>
					</select>
				</div>
			</div>

			<div class="form-group">
				<div class="col-md-2">Note</div>
				<div class="col-md-10">
					<textarea class="form-control input-sm uppercase" rows="5"  name="examnote_`+currentid+`" id="examnote_`+currentid+`"></textarea>
				</div>
			</div>
		`);

	}
}

