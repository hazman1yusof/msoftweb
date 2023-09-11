 

///////////////////////start utility function/////////////////////////////////////////////////////////
$('input').on('beforeValidation', function(value, lang, config) {
	$(this).attr('data-validation-error-msg', ' ');
	// $(this).attr('data-validation-skipped', 1);
});

function toogleSearch(butID,formID,statenow){
	this.state=false;
	$(butID+' i').attr('class','fa fa-chevron-down');
	$(butID).on( "click", function() {
		$(formID).toggle("fast");
		$(butID+' i').toggleClass('fa fa-chevron-down', this.state );
		$(butID+' i').toggleClass('fa fa-chevron-up', !this.state );
	});
	if(statenow=='off'){
		this.state=true;
		$(formID).toggle();
		$(butID+' i').attr('class','fa fa-chevron-up');
	}
}

function toggleFormData(grid,formName,oper){
	if(!$(formName+' .prevnext').length){
		$(formName).prepend("<div class='prevnext btn-group try'><a class='btn btn-default' name='prev'><i class='fa fa-chevron-left'></i></a><a class='btn btn-primary' name='next' style='color:white'><i class='fa fa-chevron-right'></i></a></div>");
	}
	makeDivFix(formName+' .prevnext',20,0);
	if(oper=='add'){
		$(formName+" .prevnext").hide();
	}else{
		$(formName+" .prevnext").show();
	}
	$(formName+" .prevnext a[name='next']").on( "click", function() {
		var selrow = $(grid).jqGrid('getGridParam', 'selrow');
		if (selrow == null) return;

		var ids = $(grid).jqGrid('getDataIDs');
		if (ids.length < 2) return;

		var index = $(grid).jqGrid('getInd', selrow);index++;
		if (index > ids.length)index = 1;

			$(grid).jqGrid('setSelection', ids[index - 1]);
		populateFormdata(grid,null,formName,ids[index - 1], oper);
	});
	$(formName+" .prevnext a[name='prev']").on( "click", function() {
		var selrow = $(grid).jqGrid('getGridParam', 'selrow');
		if (selrow == null) return;

		var ids = $(grid).jqGrid('getDataIDs');
		if (ids.length < 2) return;

		var index = $(grid).jqGrid('getInd', selrow);index--;
		if (index == 0)index = ids.length;

			$(grid).jqGrid('setSelection', ids[index - 1]);
		populateFormdata(grid,'',formName,ids[index - 1],oper);
	});
}

function makeDivFix(div,top,right){
	var fixmeTop = $(div).offset().top;
	$(window).scroll(function() { 
		var currentScroll = $(window).scrollTop();
		if (currentScroll >= fixmeTop) {
			$(div).css({
				position: 'fixed',
				top: top,
				right: right,
			});
		} else {
			$(div).css({
				position: 'static'
			});
		}
	});
}

function caption(form,caption){
	if(caption==undefined)caption='Search';
	return "<form id='"+form+"'><div class='input-group input-group-sm pull-left'><span class='input-group-addon'><strong>"+caption+" :</strong></span><input type='text' name='Stext' class='form-control' placeholder='Search here...' ><span class='input-group-addon' name='Scol'></span></div></form><div class='clearfix'></div>";
}

function addParamField(grid,needRefresh,param,except){
	var temp=[];
	$.each($(grid).jqGrid('getGridParam','colModel'), function( index, value ) {
		if(except!=undefined && except.indexOf(value['name']) === -1){
			temp.push(value['name']);
		}else if(except==undefined){
			temp.push(value['name']);
		}
	});
	param.field=temp;
	if(needRefresh){
		refreshGrid(grid,param);
	}
}

function refreshGrid(grid,urlParam,oper){
	if(oper == 'add'){
		$(grid).jqGrid('setGridParam',{datatype:'json',url:urlParam.url+'?'+$.param(urlParam)}).trigger('reloadGrid', [{page:1}]);
	}else if(oper == 'edit' || oper == 'del'){
		$(grid).jqGrid('setGridParam',{datatype:'json',url:urlParam.url+'?'+$.param(urlParam)}).trigger('reloadGrid', [{current:true}]);
	}else if(oper == 'kosongkan'){
		$(grid).jqGrid('setGridParam',{datatype:'local'}).trigger('reloadGrid');
	}else{
		$(grid).jqGrid('setGridParam',{datatype:'json',url:urlParam.url+'?'+$.param(urlParam)}).trigger('reloadGrid',[{page:1}]);
	}
}

function disableForm(formName, except){
	$(formName+' textarea').prop("readonly",true);
	$(formName+' input').prop("readonly",true);
	$(formName+' input[type=radio]').prop("disabled",true);
	$(formName+' input[type=checkbox]').prop("disabled",true);
	$(formName+' select').prop("disabled",true);
	
	if(except!=null){
		$.each(except, function( index, value ) {
			$(formName+' textarea[name='+value+']').prop("readonly",false);
			$(formName+' input[name='+value+']').prop("readonly",false);
			$(formName+' input[type=radio][name='+value+']').prop("disabled",false);
			$(formName+' input[type=checkbox][name='+value+']').prop("disabled",false);
			$(formName+' select[name='+value+']').prop("disabled",false);
		});
	}
}

function enableForm(formName, except){
	$(formName+' textarea').prop("readonly",false);
	$(formName+' input').prop("readonly",false);
	$(formName+' input[type=radio]').prop("disabled",false);
	$(formName+' input[type=checkbox]').prop("disabled",false);
	$(formName+' select').prop("disabled",false);

	if(except!=null){
		$.each(except, function( index, value ) {
			$(formName+' textarea[name='+value+']').prop("readonly",true);
			$(formName+' input[name='+value+']').prop("readonly",true);
			$(formName+' input[type=radio][name='+value+']').prop("disabled",true);
			$(formName+' input[type=checkbox][name='+value+']').prop("disabled",true);
			$(formName+' select[name='+value+']').prop("disabled",true);
		});
	}
}

function populateFormdata(grid,dialog,form,selRowId,state,except){
	if(!selRowId){
		alert('Please select row');
		return emptyFormdata([],form);
	}
	if(except == undefined)except = [];
	rowData = $(grid).jqGrid ('getRowData', selRowId);
	$.each(rowData, function( index, value ) {
		var input=$(form+" [name='"+index+"']");
		if(input.is("[type=radio]")){
			$(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
		}else if( except != undefined && except.indexOf(index) === -1){
			input.val(decodeEntities(value));
		}
	});
	if(dialog!=''){
		$(dialog).dialog( "open" );	
	}
}

var decodeEntities = (function() {
  // this prevents any overhead from creating the object each time
  var element = document.createElement('div');

  function decodeHTMLEntities (str) {
    if(str && typeof str === 'string') {
      // strip script/html tags
      str = str.replace(/<script[^>]*>([\S\s]*?)<\/script>/gmi, '');
      str = str.replace(/<\/?\w(?:[^"'>]|"[^"]*"|'[^']*')*>/gmi, '');
      element.innerHTML = str;
      str = element.textContent;
      element.textContent = '';
    }

    return str;
  }

  return decodeHTMLEntities;
})();

function inputCtrl(dialog,form,oper,butt2){
	switch(oper) {
		case state = 'add':
			$( dialog ).dialog( "option", "title", "Add" );
			enableForm(form);
			rdonly(form);
			break;
		case state = 'edit':
			$( dialog ).dialog( "option", "title", "Edit" );
			enableForm(form);
			frozeOnEdit(form);
			rdonly(form);
			break;
		case state = 'view':
			$( dialog ).dialog( "option", "title", "View" );
			disableForm(form);
			$( dialog ).dialog("option", "buttons",butt2);
			break;
	}
}

function frozeOnEdit(form){
	$(form+' input[frozeOnEdit]').prop("readonly",true);
}

function rdonly(form){
	$(form+' input[rdonly]').prop("readonly",true);
	$(form+' input[dsabled]').prop("disabled",true);
}

function hideOne(form){
	$(form+' [hideOne]').hide();
}

// function parent_close_disabled(isClose){
// 	if (window.frameElement) {
// 		parent.disableCloseButton(isClose);
// 	}
// }

// function parent_change_title(title){
// 	if (window.frameElement) {
// 		parent.changeParentTitle(title);
// 	}
// }

function selrowData(grid){
	selrow = $(grid).jqGrid ('getGridParam', 'selrow');
	return $(grid).jqGrid ('getRowData', selrow);
}

function emptyFormdata(errorField,form,except=[]){
	var temp=[];
	except.push(form+' input[name="_token"]');
	if(except!=null){
		$.each(except, function( index, value ) {
			temp.push($(value).val());
		});
	}
	errorField.length=0;
	$(form).trigger('reset');
	$(form+' input[type=hidden]').val('');
	$(form+' .help-block').html('');
	if(except!=null){
		$.each(except, function( index, value ) {
			$(value).val(temp[index]);
		});
	}
}

function emptyFormdata_div(div,except=[]){
	var temp=[];
	except.push(div+' input[name="_token"]');
	if(except!=null){
		$.each(except, function( index, value ) {
			temp.push($(value).val());
		});
	}

	$(div+' textarea').val("");
	$(div+' input').val("");

	$(div+' .help-block').html('');
	if(except!=null){
		$.each(except, function( index, value ) {
			$(value).val(temp[index]);
		});
	}
}

function trimmall(form,uppercase){
	var serializedForm =  $( form ).serializeArray();
	$.each( serializedForm, function( i, field ) {
		if(field.name!='_token'){
			if(uppercase){
    			field.value=field.value.trim().toUpperCase();
			}else{
				field.value=field.value.trim();
			}
    	}
    	//turn uppercase and trim
    });
	//turn it into a string if you wish
	let serializedForm_ = $.param(serializedForm);

	return serializedForm_;
}

function saveFormdata(grid,dialog,form,oper,saveParam,urlParam,obj,callback,uppercase=true){
	if(obj==null){
		obj={};
	}
	$('.ui-dialog-buttonset button[role=button]').prop('disabled',true);
	saveParam.oper=oper;

	let serializedForm = trimmall(form,uppercase);

	$.post( saveParam.url+'?'+$.param(saveParam), serializedForm+'&'+$.param(obj) , function( data ) {
		
	}).fail(function(data) {
		errorText(dialog.substr(1),data.responseText);
		$('.ui-dialog-buttonset button[role=button]').prop('disabled',false);
	}).success(function(data){
		if(grid!=null){
			refreshGrid(grid,urlParam,oper);
			$('.ui-dialog-buttonset button[role=button]').prop('disabled',false);
			$(dialog).dialog('close');
			if (callback !== undefined) {
				callback();
			}

			// addmore($(searchForm+' .StextClass input[type=checkbox]').is(':checked'),grid,oper);
		}
	});
}

function addmore(addmore,grid,oper){
	var pager=$(grid).jqGrid('getGridParam', 'pager');
	if(oper == 'add' && addmore){
		delay(function(){
			$(pager+" td[title='Add New Row']").click();
		}, 500 );
	}
}

function errorText(dialog,text){///?
	$("div[aria-describedby="+dialog+"] .ui-dialog-buttonpane" ).prepend("<div class='alert alert-warning my-alert'><a href='#' class='close' data-dismiss='alert'>&times;</a><strong>Error!</strong> "+text+"</div>");
}

var delay = (function(){
	var timer = 0;
	return function(callback, ms){
		clearTimeout (timer);
		timer = setTimeout(callback, ms);
	};
})();

function populateSelect(grid,form){
	$.each($(grid).jqGrid('getGridParam','colModel'), function( index, value ) {
		if(value['canSearch']){
			if(value['checked']){
				$( form+" [name=Scol]" ).append( "<label class='radio-inline'><input type='radio' name='dcolr' value='"+value['name']+"' checked>"+value['label']+"</input></label>" );
			}
			else{
				$( form+" [name=Scol]" ).append( "<label class='radio-inline'><input type='radio' name='dcolr' value='"+value['name']+"'>"+value['label']+"</input></label>" );
			}
		}
	});
}

function populateSelect2(grid,form){

	$.each($(grid).jqGrid('getGridParam','colModel'), function( index, value ) {
		if(value['canSearch']){
			if(value['checked']){
				$( form+" [id=Scol]" ).append(" <option selected value='"+value['name']+"'>"+value['label']+"</option>");
			}else{
				$( form+" [id=Scol]" ).append(" <option value='"+value['name']+"'>"+value['label']+"</option>");
			}
		}
	});
}

function searchClick(grid,form,urlParam){
	$(form+' [name=Stext]').on( "keyup", onchgscol_500);

	$(form+' [name=Scol]').on( "change", onchgscol);

	function onchgscol(){
		search(grid,$(form+' [name=Stext]').val(),$(form+' input:radio[name=dcolr]:checked').val(),urlParam);
	}

	function onchgscol_500(e){
		var code = e.keyCode || e.which;
		if(code != '9'){
			delay(function(){
				search(grid,$(form+' [name=Stext]').val(),$(form+' input:radio[name=dcolr]:checked').val(),urlParam);
			}, 500 );
		}
	}
}

function searchClick2(grid,form,urlParam){
	$(form+' [name=Stext]').on( "keyup", function(e) {
		var code = e.keyCode || e.which;
		if(code != '9'){
			delay(function(){
				search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
				$('#recnodepan').text("");//tukar kat depan tu
				$('#reqdeptdepan').text("");
				refreshGrid("#jqGrid3",null,"kosongkan");
			}, 500 );
		}
	});

	$(form+' [name=Scol]').on( "change", function() {
		search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
		$('#recnodepan').text("");//tukar kat depan tu
		$('#reqdeptdepan').text("");
		refreshGrid("#jqGrid3",null,"kosongkan");
	});
}

function search(grid,Stext,Scol,urlParam){
	urlParam.searchCol=null;
	urlParam.searchVal=null;
	if(Stext.trim() != ''){
		var split = Stext.split(" "),searchCol=[],searchVal=[];
		$.each(split, function( index, value ) {
			searchCol.push(Scol);
			searchVal.push('%'+value+'%');
		});
		urlParam.searchCol=searchCol;
		urlParam.searchVal=searchVal;
	}
	refreshGrid(grid,urlParam);
}

function search2(grid,Stext,Scol,urlParam,extra){
	urlParam.searchCol=null;
	urlParam.searchVal=null;
	if(Stext.trim() != ''){
		var split = Stext.split(" "),searchCol=[],searchVal=[];
		$.each(split, function( index, value ) {
			searchCol.push(Scol);
			searchVal.push('%'+value+'%');
			searchCol.push(extra);
			searchVal.push('%'+value+'%');
		});
		urlParam.searchCol=searchCol;
		urlParam.searchVal=searchVal;
	}
	refreshGrid(grid,urlParam);
}

function autoPad(array){
	$.each(array,function(i,v){
		$(v).on( "blur", function(){
			$(v).val(pad('000000000',$(v).val(),true));
		});
		$(v).on( "focus", function(){
			$(v).val(parseInt($(v).val(), 10));
		});
	});
}

function padArray(array){
	$.each(array,function(i,v){
		$(v).val(pad('000000000',$(v).val(),true));
	});
}

function pad(pad, str, padLeft) {
	if (typeof str === 'undefined') 
		return pad;
	if (padLeft) {
		return (pad + str).slice(-pad.length);
	} else {
		return (str + pad).substring(0, pad.length);
	}
}

function removeValidationClass(array){
	$.each(array,function(i,v){
		$(v).removeClass( "valid" );
		$(v).removeClass( "error" );
		if ( $(v).closest("div").hasClass('has-success') ||  $(v).closest("div").hasClass('has-error') ){
			$(v).closest("div").removeClass('has-success');
			$(v).closest("div").removeClass('has-error');
		}
	});
}

Date.prototype.addDays = function(days){
    var dat = new Date(this.valueOf());
    dat.setDate(dat.getDate() + days);
    return dat;
}

function formatDate(someDate){
	var dd = someDate.getDate();
	var mm = pad('00', someDate.getMonth() + 1, true);
	var y = someDate.getFullYear();

	return y + '-'+ mm + '-'+ dd;
}

function formatDate_mom(date,format = 'YYYY/M/D',returnformat = 'DD-MM-YYYY'){
	let mom = moment(date, format);
	return mom.format(returnformat);
}

function setDateToNow(){
	$('input[type=date]').val(moment().format('YYYY/M/D'));
}

function Sequences(trxtype,textfield){
	this.backday;
	this.deptcode;
	this.trxtype=trxtype;
	this.textfield=textfield;

	this.set = function(deptcode){
		this.deptcode = deptcode;
		return this;
	}

	this.get = function(){
		let self =  this;
		var param={
			action:'get_value_default',
			url: '/util/get_value_default',
			field:['backday'],
			table_name:'material.sequence',
			filterCol:['compcode','dept','trantype'],
			filterVal:['session.compcode',this.deptcode,this.trxtype]
		}
		$.get( param.url+"?"+$.param(param), function( data ) {
			
		},'json').done(function(data) {
			if(!$.isEmptyObject(data)){
				self.backday = data.rows[0].backday;
				$(self.textfield).attr('min',moment().subtract(self.backday, "days").format("YYYY-MM-DD"))
				$(self.textfield).attr('max',moment().format("YYYY-MM-DD"))
			}
		});
	}

}

function currencymode(arraycurrency,nopoint=false){
	this.array = arraycurrency;
	this.nopoint = nopoint;
	this.formatOn = function(){
		var self = this;
		$.each(this.array, function( index, value ) {
			if(self.nopoint){
				$(value).val(numeral($(value).val()).format('0,0'));
			}else{
				$(value).val(numeral($(value).val()).format('0,0.00'));
			}
		});
	}
	this.formatOnBlur = function(){
		var self = this;
		$.each(this.array, function( index, value ) {
			$(value).on("blur",{value:value,np:self.nopoint},currencyBlur);
			$(value).on("keyup",{value:value,np:self.nopoint},currencyChg);
			// currencyBlur(value);currencyChg(value)
		});
	}
	this.off = function(){
		$.each(this.array, function( index, value ) {
			$(value).off("blur",currencyBlur);
			$(value).off("keyup",currencyChg);
			// currencyBlur(value);currencyChg(value)
		});
	}
	this.formatOff = function(){
		$.each(this.array, function( index, value ) {
			$(value).val(currencyRealval(value));
		});
	}
	this.check0value = function(errorField){
		$.each(this.array, function( index, value ) {
			if($(value).val()=='0' || $(value).val()=='0.00'){
				$(value).val('');
			}
		});
	}

	function currencyBlur(event){
		let value = event.data.value;
		let nopoint = event.data.np;
		if(nopoint){
			$(value).val(numeral($(value).val()).format('0,0'));
		}else{
			$(value).val(numeral($(value).val()).format('0,0.00'));
		}
	}

	function currencyChg(event){
		value = event.data.value;
		var val = $(value).val();
		if(val.match(/[^0-9\.]/)){
			event.preventDefault();
			$(this).val(val.slice(0,val.length-1));
		}
	}

	// function currencyBlur(v){
	// 	$(v).on( "blur", function(){
	// 		$(v).val(numeral($(v).val()).format('0,0.00'));
	// 	});
	// }

	// function currencyChg(v){
	// 	$(v).on( "keyup", function(event){
	// 		var val = $(this).val();
	// 		if(val.match(/[^0-9\.]/)){
	// 			event.preventDefault();
	// 			$(this).val(val.slice(0,val.length-1));
	// 		}
	// 	});
	// }

	function currencyRealval(v){
		return numeral().unformat($(v).val());
	}
}

function currencyRealval(v){
	return numeral().unformat($(v).val());
}

function isNumberKey(evt){
  var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode < 48 || charCode > 57)
     return false;

  return true;
}

function modal(){
	this.style={
		display:"block",
		position:"absolute",
		"z-index":"1000",
		background:"rgba( 255, 255, 255, .8 ) url('img/pIkfp.gif') 50% 50% no-repeat"
	}
	this.modal = "<div id='mahmodal'></div>";
	this.button = null;
	$("body").append(this.modal);

	this.show=function(div,button){
		this.style.height = $(div)[0].offsetHeight;
		this.style.width = $(div)[0].offsetWidth;
		this.style.top = $(div).offset().top;
		this.style.left = $(div).offset().left;
		this.button = button;

		$(this.button).prop('disabled', true);
		$('#mahmodal').css(this.style);
		$('#mahmodal').show();
	}

	this.hide=function(){
		if(this.button!=null){$(this.button).prop('disabled', false);this.button=null;}
		$('#mahmodal').hide();
	}
}

function dateFormatter(cellvalue, options, rowObject){
	if(cellvalue == null) return '';
	return moment(cellvalue).format("DD-MM-YYYY");
}

function dateUNFormatter(cellvalue, options, rowObject){
	if(cellvalue == null || cellvalue == '' ) return '';
	return moment(cellvalue, "DD-MM-YYYY").format("YYYY-MM-DD");
}

function timeFormatter(cellvalue, options, rowObject){
	return moment(cellvalue, 'HH:mm:ss').format("hh:mm A");
}

function timeUNFormatter(cellvalue, options, rowObject){
	return moment(cellvalue, "hh:mm A").format('HH:mm:ss');
}

function truefalseFormatter(cellvalue, options, rowObject){
	if (cellvalue == '1') {
		return 'TRUE';
	}else if (cellvalue == '0') {
		return 'FALSE';
	}
}

function truefalseUNFormatter(cellvalue, options, rowObject){
	if (cellvalue == 'TRUE') {
		return '1';
	}else if (cellvalue == 'FALSE') {
		return '0';
	}
}


////////////////////formatter status////////////////////////////////////////
function formatterstatus(cellvalue, option, rowObject) {
	if (cellvalue == 'A') {
		return 'ACTIVE';
	}else if (cellvalue == 'D') {
		return 'DEACTIVE';
	}
}

function formatterstatus_tick(cellvalue, option, rowObject) {
	if (cellvalue == 'A') {
		return '<span class="fa fa-check" ></span>';
	}else if (cellvalue == 'D') {
		return '<span class="fa fa-times" ></span>';
	}else{
		return cellvalue;
	}
}

////////////////////unformatter status////////////////////////////////////////
function unformatstatus(cellvalue, option, rowObject) {
	if (cellvalue == 'ACTIVE') {
		return 'A';
	}else if (cellvalue == 'DEACTIVE') {
		return 'D';
	}
}

function unformatstatus_tick(cellvalue, option, rowObject) {
	if (cellvalue == '<span class="fa fa-check"></span>') {
		return 'A';
	}else if (cellvalue == '<span class="fa fa-times" ></span>') {
		return 'D';
	}
}

function un_showdetail(cellvalue, option, rowObject){
	let val = $(rowObject).html();
	
	if(val.search("[<]") == -1){
		val = val;
	}else{
		val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
	}

	if(val == " "){
		val = "";
	}
	
	return val;
}

function jqgrid_label_align_right(grid){
	$.each($(grid).jqGrid('getGridParam','colModel'), function( index, value ) {
		if(value['align'] == 'right'){
			$(grid).jqGrid('setLabel',value['name'],value['label'],{'text-align':'right'});
		}
	});
}


function checkbox_selection(grid,colname,idno='idno',recstatus = "recstatus"){
	this.idno=idno
	this.recstatus = recstatus;
	this.checkall_ = false;
	this.on = function(){
		$(grid).jqGrid('setLabel',colname,`
			<input type="checkbox" name="checkbox_all_" id="checkbox_all_check"  style="display:none">
			<input type="checkbox" name="checkbox_all_" id="checkbox_all_uncheck" checked style="display:none">
			`,
			{'text-align':'center'});

		let self = this;
		$('#checkbox_all_check').click(function(){//click will check all
			self.checkall_ = true;
			let idno = self.idno;
			let recstatus = self.recstatus;
			$(this).hide();
			$('#checkbox_all_uncheck').show();
			$("#jqGrid input[type='checkbox'][name='checkbox_selection']").prop('checked',true);
			let rowdatas = $('#jqGrid').jqGrid ('getRowData');
			rowdatas.forEach(function(rowdata,index){
				let rowdata_jqgridsel = $('#jqGrid_selection').jqGrid ('getRowData',rowdata[idno]);
				if(rowdata[recstatus] == "PARTIAL" || rowdata[recstatus] == "APPROVED"){
					return ;
				}
				if($.isEmptyObject(rowdata_jqgridsel)){
					$('#jqGrid_selection').jqGrid ('addRowData', rowdata[idno],rowdata);
					self.delete_function_on(rowdata[idno],index+1);
				}
			});
			self.show_hide_table();
		});

		$('#checkbox_all_uncheck').click(function(){//click will uncheck all
			self.checkall_ = false;
			let idno = self.idno;
			$(this).hide();
			$('#checkbox_all_check').show();
			$("#jqGrid input[type='checkbox'][name='checkbox_selection']").prop('checked',false);
			let rowdatas = $('#jqGrid').jqGrid ('getRowData');
			rowdatas.forEach(function(rowdata){
				let rowdata_jqgridsel = $('#jqGrid_selection').jqGrid ('getRowData',rowdata[idno]);
				if(!$.isEmptyObject(rowdata_jqgridsel)){
					$('#jqGrid_selection').jqGrid ('delRowData', rowdata[idno]);
				}
			});
			self.show_hide_table();
		});

		$("#show_sel_tbl").click(function(){
			let hidden = $(this).data('hide');
			if(hidden){
				$('#sel_tbl_panel').show('fast',function(){
					$("#jqGrid_selection").jqGrid ('setGridWidth', Math.floor($("#sel_tbl_div")[0].offsetWidth-$("#sel_tbl_div")[0].offsetLeft)+20);
				});
				$(this).data('hide',false);
				$(this).text('Hide Selection Item')
			}else{
				$('#sel_tbl_panel').hide('fast');
				$(this).data('hide',true);
				$(this).text('Show Selection Item')
			}
		});

		var newcolmodel = $.extend([], $("#jqGrid").jqGrid('getGridParam','colModel'));
		newcolmodel.push({ label: 'jqgrid_rowid', name: 'jqgrid_rowid', hidden:false});

		$('#jqGrid_selection').jqGrid('setGridParam',{colModel:newcolmodel}).trigger('reloadGrid');
	}

	this.delete_function_on = function(idno,rowid){
		let self = this;
		$("#jqGrid_selection #delete_"+idno).click(function(){
			self.uncheckall_();
			let rowdata_jqgridsel = $('#jqGrid_selection').jqGrid('getRowData',idno);
			$('#jqGrid_selection').jqGrid ('delRowData', idno);
			let rowdata = $('#jqGrid').jqGrid ('getRowData',rowid);
			if(!$.isEmptyObject(rowdata)){
				$("#jqGrid #checkbox_selection_"+idno).prop('checked',false);
			}
			self.show_hide_table();
		});
	}

	this.checkbox_function_on = function(callback){
		let self = this;
		let idno = self.idno;
		$("#jqGrid input[type='checkbox'][name='checkbox_selection']").on('click',function(){
			let rowid = $(this).data('rowid');
			let checked = $(this).is(":checked");
			let rowdata = $('#jqGrid').jqGrid ('getRowData', rowid);
			if (callback !== undefined) {
				callback(rowdata);
			}
			rowdata.jqgrid_rowid = rowid;
			if(checked){//delete from seltable
				$('#jqGrid_selection').jqGrid ('addRowData', rowdata[idno], rowdata);
				self.delete_function_on(rowdata[idno],rowid);
			}else{//add to seltable
				self.uncheckall_();
				$('#jqGrid_selection').jqGrid ('delRowData', rowdata[idno]);
			}
			self.show_hide_table();
		});
	}

	this.uncheckall_ = function(){
		self.checkall_ = false;
		$('#checkbox_all_uncheck').hide();
		if($("select#Status").val()!='All')$('#checkbox_all_check').show();
	}

	this.show_hide_table = function(){
		let reccount = $('#jqGrid_selection').jqGrid('getGridParam', 'reccount');
		var status_ = $("select#Status").val();
		if(status_ == undefined || status_ == null){
			status_ = 'All';
		}
		// $("#show_sel_tbl").is(":hidden") && 
		$("#show_sel_tbl,#but_post_jq,#but_reopen_jq,#but_cancel_jq").hide();
		if(reccount > 0){
			switch(status_){
				case 'All':
					// $('#checkbox_all_uncheck,#checkbox_all_check').hide();
					$("#show_sel_tbl,#but_post_jq,#but_cancel_jq").show();
					break;
				case 'CANCELLED':
					$("#show_sel_tbl,#but_reopen_jq").show();
					break;
				case 'OPEN':
				case 'REQUEST':
				case 'SUPPORT':
				case 'VERIFIED':
				case 'APPROVED':
					$("#show_sel_tbl,#but_post_jq,#but_cancel_jq").show();
					break;
			}
		}else if(reccount == 0){
			$('#checkbox_all_check').show();
			$("#checkbox_all_uncheck").hide();
			$('#sel_tbl_panel').hide('fast');
			$("#show_sel_tbl,#but_post_jq,#but_reopen_jq,#but_cancel_jq").hide();
			$("#show_sel_tbl").data('hide',true);
			$("#show_sel_tbl").text('Show Selection Item');
		}
	}

	this.init_allcb = function(){
		$('#checkbox_all_check,#checkbox_all_uncheck').hide();
		$('#checkbox_all_check').prop('checked', false);
		$("#checkbox_all_check").show();
		$('#checkbox_all_uncheck').prop('checked', true);
		$("#checkbox_all_uncheck").hide();
	}

	this.refresh_seltbl = function(){
		let self = this;
		let idno = self.idno;
		let reccount = $('#jqGrid_selection').jqGrid('getGridParam', 'reccount');
		if(reccount > 0){
			let rowdatas_jqgridsel = $('#jqGrid_selection').jqGrid ('getRowData');
			rowdatas_jqgridsel.forEach(function(rowdata){
				$("#jqGrid #checkbox_selection_"+rowdata[idno]).prop('checked',true);
			});
		}
	}

	this.empty_sel_tbl = function(){
		this.uncheckall_();
		$('#checkbox_all_uncheck').click();
		$("#jqGrid_selection").jqGrid("clearGridData", true);
		// this.refresh_seltbl();
	}

	this.get_all_idno = function(){

	}
}

function setactdate(target){
	this.actdateopen=[];
	this.lowestdate;
	this.highestdate;
	this.target=target;
	this.param={
		action:'get_value_default',
		url:"util/get_value_default",
		field: ['*'],
		table_name:'sysdb.period',
		table_id:'idno'
	}

	this.getdata = function(){
		var self=this;
		$.get( this.param.url+"?"+$.param(this.param), function( data ) {
			
		},'json').done(function(data) {
			if(!$.isEmptyObject(data.rows)){
				self.lowestdate = data.rows[0]["datefr1"];
				self.highestdate = data.rows[data.rows.length-1]["dateto12"];
				data.rows.forEach(function(element){
					if(element.year == moment().year()){
						$.each(element, function( index, value ) {
							if(index.match('periodstatus') && value == 'O'){
								self.actdateopen.push({
									from:element["datefr"+index.match(/\d+/)[0]],
									to:element["dateto"+index.match(/\d+/)[0]]
								})
							}
						});
					}
				});

				self.target.forEach(function(element,i){
					$(element).attr('min',self.actdateopen[0].from);
					$(element).attr('max',self.highestdate);
				});
			}
		});
		return this;
	}

	this.set = function(){
		var self = this;
		this.target.forEach(function(element,i){
			$(element).on('change',{data:self,index:i},validate_actdate);
		});
	}

	function validate_actdate(event){
		var permission = false;
		var actdateObj = event.data.data; 
		var index = event.data.index;
		var value = $(actdateObj.target[index]).val();
		var currentTarget = actdateObj.target[index];
		actdateObj.actdateopen.forEach(function(element){
		 	if(moment(value).isBetween(element.from,element.to, null, '[]')) {
				permission=true
			}else{
				(permission)?permission=true:permission=false;
			}
		});

		if(!moment(value).isBetween(actdateObj.lowestdate,actdateObj.highestdate, null, '[]')){
			alert('Date not in accounting period setup');
			$(currentTarget).val('').addClass( "error" ).removeClass( "valid" );
		}else if(!permission){
			alert('Accounting Period Has been Closed');
			$(currentTarget).val('').addClass( "error" ).removeClass( "valid" );
		} //Accounting Period Has been Closed
			//Date not in accounting period setup
	}
}

function text_error1(id){
	$( id ).parent().removeClass( "has-success" ).addClass( "has-error" );
	$( id ).removeClass( "valid" ).addClass( "error" );
}

function text_success1(id){
	$( id ).parent().removeClass( "has-error" ).addClass( "has-success" );
	$( id ).removeClass( "error" ).addClass( "valid" );
}

function ordialog(unique,table,id,errorField,jqgrid_,dialog_,checkstat='urlParam',dcolrType='radio',needTab='notab',required=true,tablestriped='table-striped'){
	this.unique=unique;
	this.gridname="othergrid_"+unique;
	this.dialogname="otherdialog_"+unique;
	this.otherdialog = "<div id='"+this.dialogname+"' title='"+dialog_.title+"'><div class='panel panel-default'><div class='panel-heading'><form id='checkForm_"+unique+"' class='form-inline'><div class='form-group'><b>Search: </b><div id='Dcol_"+unique+"' name='Dcol_"+unique+"'></div></div><div class='form-group' style='width:70%' id='Dparentdiv_"+unique+"'><input id='Dtext_"+unique+"' name='Dtext_"+unique+"' type='search' style='width:100%' placeholder='Search here ...' class='form-control text-uppercase' autocomplete='off'></div></form></div><div class=panel-body><div id='"+this.gridname+"_c' class='col-xs-12' align='center'><table id='"+this.gridname+"' class='table "+this.tablestriped+"'></table><div id='"+this.gridname+"Pager'></div></div></div></div></div>";
	this.errorField=errorField;
	this.dialog_=dialog_;
	this.jqgrid_=jqgrid_;
	this.check=checkInput;
	this.field=jqgrid_.colModel;
	this.textfield=id;
	this.ck_desc=1;
	this.check_take_all_field=false;
	this.eventstat='off';
	this.checkstat=checkstat;
	this.ontabbing=false;
	this.urlParam={
		from:unique,
		fixPost:jqgrid_.urlParam.fixPost,
		action:'get_table_default',
		url:geturl(jqgrid_.urlParam),
		table_name:table,
		field:getfield(jqgrid_.colModel),
		table_id:getfield(jqgrid_.colModel)[0],
		filterCol:jqgrid_.urlParam.filterCol,filterVal:jqgrid_.urlParam.filterVal,
		join_onCol:jqgrid_.urlParam.join_onCol,join_onVal:jqgrid_.urlParam.join_onVal,
		join_filterCol:jqgrid_.urlParam.join_filterCol,join_filterVal:jqgrid_.urlParam.join_filterVal,
		searchCol2:null,searchCol2:null,searchCol:null,searchCol:null
	};
	this.id_optid=null;
	this.needTab=needTab;
	this.dcolrType=dcolrType;
	this.required=required;
	this.on = function(){
		this.eventstat='on';

		if(this.needTab=='tab'){
			$(this.textfield).on('keydown',{data:this},onTab);
		}
		$(this.textfield+" ~ a").on('click',{data:this},onClick);
		$("#Dtext_"+unique).on('keyup',{data:this},onChange);
		$("#Dcol_"+unique).on('change',{data:this},onChange);
		$(this.textfield).on('blur',{data:this,errorField:errorField},onBlur);
		return this;
	}
	this.off = function(){
		this.eventstat='off';
		if(this.needTab=='tab'){
			$(this.textfield).off('keydown',onTab);
		}
		$(this.textfield+" ~ a").off('click',onClick);
		$("#Dtext_"+unique).off('keyup',onChange);
		$("#Dcol_"+unique).off('change',onChange);
		$(this.textfield).off('blur',onBlur);
		$("#Dtext_"+unique).off('keydown',onTabSearchfield)
	}
	this.makedialog = function(on=false){
		$("html").append(this.otherdialog);
		makedialog(this);
		makejqgrid(this);
		if(this.dcolrType == 'radio'){
			othDialog_radio(this);
		}else{
			othDialog_dropdown(this);
		}
		if(on){
			this.eventstat='on';
			if(this.needTab=='tab'){
				$(this.textfield).on('keydown',{data:this},onTab);
			}
			$(this.textfield+" ~ a").on('click',{data:this},onClick);
			$("#Dtext_"+unique).on('keyup',{data:this},onChange);
			$("#Dcol_"+unique).on('change',{data:this},onChange);
			$(this.textfield).on('blur',{data:this,errorField:errorField},onBlur);
			$("#Dtext_"+unique).on('keydown',{data:this},onTabSearchfield);
		}
	}

	this.renull_search = function(){
		obj = this;
		obj.urlParam.searchCol2=obj.urlParam.searchVal2=obj.urlParam.searchCol=obj.urlParam.searchVal=null
	}

	function onTabSearchfield(event){
		var obj = event.data.data;
		var code = event.keyCode || event.which;

		if (code == '9'){
			event.preventDefault();
			$("#"+obj.gridname+' tr#1').click().focus();
		}
	}


	function onClick(event){
		var textfield = $(event.currentTarget).siblings("input[type='text']");

		var optid = $(event.currentTarget).siblings("input[type='text']").attr("optid");
		if(optid!=null || optid!=undefined){
			var id_optid = optid.substring(0,optid.search("_"));
			event.data.data.id_optid = id_optid;
		}

		var obj = event.data.data;
		$("#"+obj.gridname).jqGrid('setGridParam',{ ondblClickRow: function(id){ 
			if(!obj.jqgrid_.hasOwnProperty('ondblClickRow_off')){
				textfield.off('blur',onBlur);
				textfield.val(selrowData("#"+obj.gridname)[getfield(obj.field)[0]]);
				textfield.parent().next().html(selrowData("#"+obj.gridname)[getfield(obj.field)[1]]);
				textfield.focus();
				$("#"+obj.dialogname).dialog( "close" );
				// $("#"+obj.gridname).jqGrid("clearGridData", true);
				$(obj.textfield).parent().parent().removeClass( "has-error" ).addClass( "has-success" );
				textfield.removeClass( "error" ).addClass( "valid" );
				textfield.on('blur',{data:obj,errorField:errorField},onBlur);
				if(obj.jqgrid_.hasOwnProperty('ondblClickRow'))obj.jqgrid_.ondblClickRow(event);
			}

			// var idtopush = (obj.textfield.substring(0, 1) == '#')?obj.textfield.substring(1):obj.textfield;
			var idtopush = $(event.currentTarget).siblings("input[type='text']").attr('id');
			if($.inArray(idtopush,obj.errorField)!==-1 && obj.required){
				obj.errorField.splice($.inArray(idtopush,obj.errorField), 1);
			}
		}});

		$("#"+obj.gridname).jqGrid('setGridParam',{ onSelectRow: function(rowid){ 
			if(obj.jqgrid_.hasOwnProperty('onSelectRow'))obj.jqgrid_.onSelectRow(rowid);
		}});

		renull_search(obj);
		$("#"+obj.dialogname).dialog( "open" );

		// var idtopush = $(event.currentTarget).siblings("input[type='text']").attr('id');
		// var jqgrid = $(event.currentTarget).siblings("input[type='text']").attr('jqgrid');
		// var optid = (event.data.data.urlParam.hasOwnProperty('optid'))? event.data.data.urlParam.optid:null;

		// if(optid!=null){
		// 	var id_optid = idtopush.substring(0,idtopush.search("_"));
		// 	optid.field.forEach(function(element,i){
		// 		obj.urlParam.filterVal[optid.id[i]] = $(optid.jq+' input#'+id_optid+element).val();
		// 	});
		// 	event.data.data.id_optid = id_optid;
		// }

		let text = textfield.val().trim();
		if(text != ''){
			let split = text.split(" "),searchCol2=[],searchVal2=[];
			$.each(split, function( index, value ) {
				getfield(event.data.data.field,true).forEach(function(element){
					searchCol2.push(element);
					searchVal2.push('%'+value+'%');
				});
			});
			event.data.data.urlParam.searchCol2=searchCol2;
			event.data.data.urlParam.searchVal2=searchVal2;
		}
		if(obj.dialog_.hasOwnProperty('justb4refresh'))obj.dialog_.justb4refresh(obj);
		refreshGrid("#"+event.data.data.gridname,event.data.data.urlParam);
		$("#Dtext_"+unique).val(text);


		// refreshGrid("#"+obj.gridname,obj.urlParam);
	}

	function onBlur(event){
		var obj = event.data.data;
		var idtopush = $(event.currentTarget).siblings("input[type='text']").end().attr('id');
		var jqgrid = $(event.currentTarget).siblings("input[type='text']").end().attr('jqgrid');
		var optid = (obj.urlParam.hasOwnProperty('optid'))? obj.urlParam.optid:null;

		if(obj.checkstat!='none'){
			// renull_search(event.data.data);
			if(obj.dialog_.hasOwnProperty('after_check')){
				obj.check(obj.errorField,idtopush,jqgrid,optid,undefined,function(){
					obj.dialog_.after_check(obj);
				});
			}else{
				obj.check(obj.errorField,idtopush,jqgrid,optid);
			}
		}
	}

	function onTab(event){
		renull_search(event.data.data);
		var textfield = $(event.currentTarget);
		var optid = $(event.currentTarget).get(0).getAttribute("optid");
		if(optid!=null || optid!=undefined){
			var id_optid = optid.substring(0,optid.search("_"));
			event.data.data.id_optid = id_optid;
		}

		if(event.key == "Tab" && textfield.val() != ""){
			$("#"+event.data.data.dialogname).dialog("open");
			event.data.data.ontabbing=true;
			var obj = event.data.data;
			$("#"+obj.gridname).jqGrid('setGridParam',{ ondblClickRow: function(id){ 
				if(!obj.jqgrid_.hasOwnProperty('ondblClickRow_off')){
					textfield.off('blur',onBlur);
					textfield.val(selrowData("#"+obj.gridname)[getfield(obj.field)[0]]);
					textfield.parent().next().html(selrowData("#"+obj.gridname)[getfield(obj.field)[1]]);
					textfield.focus();
					$("#"+obj.dialogname).dialog( "close" );
					// $("#"+obj.gridname).jqGrid("clearGridData", true);
					$(obj.textfield).parent().parent().removeClass( "has-error" ).addClass( "has-success" );
					textfield.removeClass( "error" ).addClass( "valid" );
					textfield.on('blur',{data:obj,errorField:errorField},onBlur);
					if(obj.jqgrid_.hasOwnProperty('ondblClickRow'))obj.jqgrid_.ondblClickRow(event);
				}

				// var idtopush = (obj.textfield.substring(0, 1) == '#')?obj.textfield.substring(1):obj.textfield;
				var idtopush = $(event.currentTarget).attr('id');
				if($.inArray(idtopush,obj.errorField)!==-1 && obj.required){
					obj.errorField.splice($.inArray(idtopush,obj.errorField), 1);
				}
			}});

			$("#"+obj.gridname).jqGrid('setGridParam',{ onSelectRow: function(id){ 
				if(obj.jqgrid_.hasOwnProperty('onSelectRow'))obj.jqgrid_.onSelectRow(rowid, selected);
			}});

			event.preventDefault();
			let text = $(this).val().trim();
			if(text != ''){
				let split = text.split(" "),searchCol2=[],searchVal2=[];
				$.each(split, function( index, value ) {
					getfield(event.data.data.field,true).forEach(function(element){
						searchCol2.push(element);
						searchVal2.push('%'+value+'%');
					});
				});
				event.data.data.urlParam.searchCol2=searchCol2;
				event.data.data.urlParam.searchVal2=searchVal2;
			}
			refreshGrid("#"+event.data.data.gridname,event.data.data.urlParam);
			$("#Dtext_"+unique).val(text);
		}
	}

	function onChange(event){
		renull_search(event.data.data);
		let obj = event.data.data;
		let Dtext=$("#Dtext_"+obj.unique).val().trim();
		if(obj.dcolrType == 'radio'){
			var Dcol=$("#Dcol_"+obj.unique+" input:radio[name=dcolr]:checked").val();
		}else{
			var Dcol=$("#Dcol_"+obj.unique+" select[name=dcolr]").val();
		}
		let split = Dtext.split(" "),searchCol=[],searchVal=[];
		$.each(split, function( index, value ) {
			searchCol.push(Dcol);
			searchVal.push('%'+value+'%');
		});
		if(event.type=="keyup" && Dtext != ''){
			delay(function(){
				obj.urlParam.searchCol=searchCol;
				obj.urlParam.searchVal=searchVal;
				refreshGrid("#"+obj.gridname,obj.urlParam);
			},500);
		}else if(event.type=="change" && Dtext != ''){
			obj.urlParam.searchCol=searchCol;
			obj.urlParam.searchVal=searchVal;
			refreshGrid("#"+obj.gridname,obj.urlParam);
		}else{
			refreshGrid("#"+obj.gridname,obj.urlParam);
		}
	}

	function othDialog_radio(obj){
		$.each($("#"+obj.gridname).jqGrid('getGridParam','colModel'), function( index, value ) {
			if(value['canSearch']){
				if(value['checked']){
					$("#Dcol_"+unique+"").append("<label class='radio-inline'><input type='radio' name='dcolr' value='"+value['name']+"' checked>"+value['label']+"</input></label>" );
				}else{
					$("#Dcol_"+unique+"").append( "<label class='radio-inline'><input type='radio' name='dcolr' value='"+value['name']+"' >"+value['label']+"</input></label>" );
				}
			}
		});
	}

	function othDialog_dropdown(obj){
		$("#Dcol_"+unique+"").append("<select name='dcolr' class='form-control input-sm' style='margin-right:10px;min-width:150px'></select>");
		$("#Dtext_"+unique+"").parent().prepend("<b>&nbsp;</b>");
		$.each($("#"+obj.gridname).jqGrid('getGridParam','colModel'), function( index, value ) {
			if(value['canSearch']){
				if(value['checked']){$("#Dcol_"+unique+" select[name=dcolr]").append( "<option value='"+value['name']+"' selected>"+value['label']+"</option>" );
				}else{
					$("#Dcol_"+unique+" select[name=dcolr]").append( "<option value='"+value['name']+"'>"+value['label']+"</option>" );
				}
			}
		});
	}

	function renull_search(obj){
		obj.urlParam.searchCol2=obj.urlParam.searchVal2=obj.urlParam.searchCol=obj.urlParam.searchVal=null;
	}

	function makedialog(obj){
		let width = 7/10 * $(window).width();
		if(obj.dialog_.hasOwnProperty('width')){
			width = obj.dialog_.width;
		}
		$("#"+obj.dialogname).dialog({
			autoOpen: false,
			width: width,
			modal: true,
			open: function(event, ui){
				$("#"+obj.gridname).jqGrid ('setGridWidth', Math.floor($("#"+obj.gridname+"_c")[0].offsetWidth-$("#"+obj.gridname+"_c")[0].offsetLeft));
				if(obj.dialog_.hasOwnProperty('open'))obj.dialog_.open(obj);
				if(obj.needTab == 'notab')$("#Dtext_"+unique).focus();

			},
			close: function( event, ui ){
				$("#Dtext_"+unique).val('');
				obj.ontabbing = false;
				if(obj.dialog_.hasOwnProperty('close'))obj.dialog_.close(obj);
			},
		});
	}

	function makejqgrid(obj){
		$("#"+obj.gridname).jqGrid({
			datatype: "local",
			colModel: obj.field,
			autowidth: true,viewrecords:true,loadonce:false,width:200,height:200,owNum:30,hoverrows:false,
			pager: "#"+obj.gridname+"Pager",
			sortname: (obj.jqgrid_.hasOwnProperty('sortname'))?obj.jqgrid_.sortname:'',
			sortorder: (obj.jqgrid_.hasOwnProperty('sortorder'))?obj.jqgrid_.sortorder:'',
			onSelectRow:function(rowid, selected){
				if(obj.jqgrid_.hasOwnProperty('onSelectRow'))obj.jqgrid_.onSelectRow(rowid, selected);
			},
			ondblClickRow: function(rowid, iRow, iCol, e){
				if(!obj.jqgrid_.hasOwnProperty('ondblClickRow_off')){
					$(obj.textfield).off('blur',onBlur);
					$(obj.textfield).val(selrowData("#"+obj.gridname)[getfield(obj.field)[0]]);
					$(obj.textfield).parent().next().html(selrowData("#"+obj.gridname)[getfield(obj.field)[1]]);
					$(obj.textfield).focus();
					if(obj.jqgrid_.hasOwnProperty('ondblClickRow'))obj.jqgrid_.ondblClickRow();
					$("#"+obj.dialogname).dialog( "close" );
					// $("#"+obj.gridname).jqGrid("clearGridData", true);
					$(obj.textfield).parent().parent().removeClass( "has-error" ).addClass( "has-success" );
					$(obj.textfield).removeClass( "error" ).addClass( "valid" );
					$(obj.textfield).on('blur',{data:obj,errorField:errorField},onBlur);
				}
				var idtopush = (obj.textfield.substring(0, 1) == '#')?obj.textfield.substring(1):obj.textfield;
				if($.inArray(idtopush,obj.errorField)!==-1 && obj.required){
					obj.errorField.splice($.inArray(idtopush,obj.errorField), 1);
				}
			},
			loadComplete: function(data) {
				if(obj.jqgrid_.hasOwnProperty('loadComplete'))obj.jqgrid_.loadComplete(data,obj);
		    },
			gridComplete: function() {
				if(obj.jqgrid_.hasOwnProperty('gridComplete'))obj.jqgrid_.gridComplete(obj);
		    },

		});

		if(obj.jqgrid_.hasOwnProperty('sortname')){
			$("#"+obj.gridname).jqGrid('setGridParam',{ sortname: obj.jqgrid_.sortname});
		};
		if(obj.jqgrid_.hasOwnProperty('sortorder')){
			$("#"+obj.gridname).jqGrid('setGridParam',{ sortorder: obj.jqgrid_.sortorder});
		};

		$("#"+obj.gridname).jqGrid('bindKeys', {"onEnter":function( rowid ) { 
				$("#"+obj.gridname+' tr#'+rowid).dblclick();
			}
		})
		addParamField("#"+obj.gridname,false,obj.urlParam);
	}

	function geturl(urlParam){
		let returl = 'util/get_table_default';
		if(urlParam.url === undefined){
			returl = 'util/get_table_default';
		}else{
			returl = urlParam.url;
		}
		return returl;
	}

	function getfield(field,or_search){
		var fieldReturn = [];
		field.forEach(function(element){
			if(or_search){
				if(element.or_search)fieldReturn.push(element.name);
			}else{
				fieldReturn.push(element.name);
			}
		});
		return fieldReturn;
	}

	function checkInput(errorField,idtopush,jqgrid=null,optid=null,before_check,after_check){
		var table=this.urlParam.table_name,field=this.urlParam.field,value=$(this.textfield).val(),param={},self=this,urlParamID=0,desc=this.ck_desc;
		
		if (before_check !== undefined) {
			renull_search(this);
			before_check(self);
		}

		if(idtopush){ /// ni nk tgk sama ada from idtopush exist atau tak
			var idtopush = idtopush,id;
			if(jqgrid==null){
				id = 'input#'+idtopush;
				value = $(id).val();
			}else{
				id = '#'+jqgrid+' input#'+idtopush;
				value = $(id).val();
			}
		}else{
			var idtopush = (this.textfield.substring(0, 1) == '#')?this.textfield.substring(1):this.textfield;
			value = $('#'+idtopush).val();
			var id = '#'+idtopush;
		}

		if(this.urlParam.fixPost == 'true'){
			code_ = this.urlParam.field[urlParamID];
			desc_ = this.urlParam.field[desc];
			code_ = code_.replaceAt(code_.search("_"),'.');
		}else{
			code_ = this.urlParam.field[urlParamID];
			desc_ = this.urlParam.field[desc];
		}
		let index=0;

		if(this.checkstat=='default'){
			param={action:'input_check',table:table,field:field,value:value};

		}else if(this.checkstat=='none' && value == ''){
			return false;
		}else{

			param=Object.assign({},this.urlParam);

			if(optid!=null){
				var id_optid = idtopush.substring(0,idtopush.search("_"));
				optid.field.forEach(function(element,i){
					param.filterVal[optid.id[i]] = $(optid.jq+' input#'+id_optid+element).val();
				});
			}

			param.action="get_value_default";
			param.url='util/get_value_default';

			if(this.urlParam.hasOwnProperty('url_chk')){
				param.url = this.urlParam.url_chk;
				param.action= this.urlParam.action_chk;
			}

			if(this.check_take_all_field){
				param.field=this.urlParam.field;
			}else{
				param.field=[code_,desc_];
			}
			index=jQuery.inArray(code_,param.filterCol);
			if(index == -1){
				param.filterCol.push(code_);
				param.filterVal.push(value);
			}else{
				param.filterVal[index]=value;
			}
		}

		$.get( param.url+"?"+$.param(param), function( data ) {

		},'json').done(function(data) {
			if(index == -1){
				// param.filterCol.pop();
				// param.filterVal.pop();
			}
			let fail=true,code,desc2;
			if(self.checkstat=='default'){
				if(data.msg=='success'){
					fail=false;desc2=data.rows[field[1]];
				}else if(data.msg=='fail'){
					fail=true;code=field[0];
				}
			}else{
				if(data.rows.length>0){
					fail=false;
					if(param.fixPost == 'true'){
						desc2=data.rows[0][self.urlParam.field[desc]];
						// desc2=data.rows[0][self.urlParam.field[desc].split('_')[1]];
					}else{
						desc2=data.rows[0][self.urlParam.field[desc]];
					}
				}else{
					fail=true;code=code_;
				}
			}

			if(typeof errorField != 'string' && self.required){
				if(!fail){
					if($.inArray(idtopush,errorField)!==-1){
						errorField.splice($.inArray(idtopush,errorField), 1);
					}
					$( id ).parent().parent().removeClass( "has-error" ).addClass( "has-success" );
					$( id ).removeClass( "error" ).addClass( "valid" );
					$( id ).parent().siblings( ".help-block" ).html(desc2);
					$( id ).parent().siblings( ".help-block" ).show();
				}else{
					$( id ).parent().parent().removeClass( "has-success" ).addClass( "has-error" );
					$( id ).removeClass( "valid" ).addClass( "error" );
					$( id ).parent().siblings( ".help-block" ).html("Invalid Code");
					if($.inArray(idtopush,errorField)===-1){
						errorField.push( idtopush );
					}
				}
			}else if(self.required == false && value == ''){
				if($.inArray(idtopush,errorField)!==-1){
					errorField.splice($.inArray(idtopush,errorField), 1);
				}
				$( id ).parent().parent().removeClass( "has-error" ).removeClass( "has-success" );
				$( id ).removeClass( "error" ).removeClass( "valid" );
				$( id ).parent().siblings( ".help-block" ).html('');

			}else if(self.required == false && value != ''){
				if(!fail){
					if($.inArray(idtopush,errorField)!==-1){
						errorField.splice($.inArray(idtopush,errorField), 1);
					}
					$( id ).parent().parent().removeClass( "has-error" ).removeClass( "has-success" );
					$( id ).removeClass( "error" ).removeClass( "valid" );
					$( id ).parent().siblings( ".help-block" ).html(desc2);
					$( id ).parent().siblings( ".help-block" ).show();
				}else{
					$( id ).parent().parent().removeClass( "has-success" ).addClass( "has-error" );
					$( id ).removeClass( "valid" ).addClass( "error" );
					$( id ).parent().siblings( ".help-block" ).html("Invalid Code");
				}

			}

			if (after_check !== undefined) {
				after_check(self,data);
			}
			
		});
		if(index == -1){
			param.filterCol.pop();
			param.filterVal.pop();
		}
	}

	this.init_func = null;
	this._init_func = function _init_func(init_func){
		this.init_func = init_func;
	}
	this._init = function(){
		this.init_func(this);
	}
	
}

function getfield(field,or_search){
	var fieldReturn = [];
	field.forEach(function(element){
		if(or_search){
			if(element.or_search)fieldReturn.push(element.name);
		}else{
			fieldReturn.push(element.name);
		}
	});
	return fieldReturn;
}

String.prototype.replaceAt=function(index, replacement) {
    return this.substr(0, index) + replacement+ this.substr(index + replacement.length);
}

/////////////////////////////////compid and ipaddress at cookies//////////////////////
function getcompid(callback){
	// $.getJSON('http://ip-api.com/json?callback=?', function(data) {
	//   if(!$.isEmptyObject(data)){
	// 		callback(data);
	// 	}
	// });
}

function check_compid_exist(lastcompid,lastip,compid,ip){
	var msoftweb_computerid = localStorage.getItem('msoftweb_computerid');
	var msoftweb_ipaddress = localStorage.getItem('msoftweb_ipaddress');
	if(!msoftweb_computerid){
		getcompid(function(data){
			localStorage.setItem('msoftweb_computerid', data.city);
			localStorage.setItem('msoftweb_ipaddress', data.query);
			set_compid_from_storage(lastcompid,lastip,compid,ip);
		});
	}
}

function set_compid_from_storage(lastcompid,lastip,compid,ip){
	var msoftweb_computerid = localStorage.getItem('msoftweb_computerid');
	var msoftweb_ipaddress = localStorage.getItem('msoftweb_ipaddress');
	$(lastcompid).val(msoftweb_computerid);
	$(lastip).val(msoftweb_ipaddress);
	if($(compid).val()=='')$(compid).val(msoftweb_computerid);
	if($(ip).val()=='')$(ip).val(msoftweb_ipaddress);
}

//////////////////////////////////padding dekat sysparam -IV-ZERO////////////////////
// function getpadlen(callback){
// 	$.get( "util/getpadlen", function( data ) {
			
// 	},'json').done(function(data) {
// 		if(!$.isEmptyObject(data[0])){
// 			callback(data);
// 		}
// 	});
// }

// function checkPadExist(){
// 	var msoftweb_padzero = localStorage.getItem('msoftweb_padzero');
// 	if(!msoftweb_padzero){
// 		getpadlen(function(data){
// 			localStorage.setItem('msoftweb_padzero', data[0].pvalue1);
// 		});
// 	}
// }
// checkPadExist();//start checking..

function padzero(cellvalue, options, rowObject){
	if(cellvalue == null || cellvalue.toString().trim() == ''){
		return ''
	}

	let padzero = localStorage.getItem('msoftweb_padzero'), str="";
	while(padzero>0){
		str=str.concat("0");
		padzero--;
	}
	return pad(str, cellvalue, true);
}

function padzero5(cellvalue, options, rowObject){
	if(cellvalue == null || cellvalue.toString().trim() == ''){
		return ''
	}

	let padzero = 5, str="";
	while(padzero>0){
		str=str.concat("0");
		padzero--;
	}
	return pad(str, cellvalue, true);
}

function unpadzero(cellvalue, options, rowObject){
	return cellvalue.substring(cellvalue.search(/[1-9]/));
}

function checkradiobutton(radiobuttons){
	this.radiobuttons=radiobuttons;
	this.check = function(){
		$.each(this.radiobuttons, function( index, value ) {
			var checked = $("input[name="+value+"]:checked").val();
		    if(!checked){
		     	$("label[for="+value+"]").css('color', '#a94442');
		     	$(":radio[name='"+value+"']").parent('label').css('color', '#a94442');
			}else{
				$("label[for="+value+"]").css('color', '#444444');
				$(":radio[name='"+value+"']").parent('label').css('color', '#444444');
			}
		});
	}
	this.reset = function(){
		$.each(this.radiobuttons, function( index, value ) {
			$("label[for="+value+"]").css('color', '#444444');
			$(":radio[name="+value+"]").parent('label').css('color', '#444444');
		});
	}
}

////////////////////////////////// faster detail loading  ///////////////////////////////////////////

function faster_detail_load(){
	this.array = [];
	this.get_array = function(page,options,param,case_,cellvalue){
		let storage_name = 'fastload_'+page+'_'+case_+'_'+cellvalue;
		let storage_obj = localStorage.getItem(storage_name);
		let desc_name = param.field[1];
		if(!storage_obj){
			if(cellvalue !== null && cellvalue !== undefined && cellvalue !== 'undefined<span class="help-block"></span>' && cellvalue.trim() != ''){
				$.get( param.url+"?"+$.param(param), function( data ) {
						
				},'json').done(function(data) {
					if(!$.isEmptyObject(data.rows)){
						$("#"+options.gid+" #"+options.rowId+" td:nth-child("+(options.pos+1)+")").append("<span class='help-block'>"+data.rows[0][desc_name]+"</span>");

						let desc = data.rows[0][desc_name];
						let now = moment();

						var json = JSON.stringify({
							'description':desc,
							'timestamp': now
						});

						localStorage.setItem(storage_name,json);
					}
				});
			}else{
				let desc = '';
				let now = moment();

				var json = JSON.stringify({
					'description':desc,
					'timestamp': now
				});

				localStorage.setItem(storage_name,json);

			}

		}else{

			let obj_stored = {
				'json':JSON.parse(storage_obj),
				'options':options
			}
			this.array.push(obj_stored);

			//remove storage after 7 days
            let moment_stored = obj_stored.json.timestamp;
            if(moment().diff(moment(moment_stored),'days') > 7){
                localStorage.removeItem(storage_name);
            }
		}
	}
	this.set_array = function(except){
		this.array.forEach(function(elem,i){
			let options = elem.options;
			let desc = elem.json.description;

			if(except == undefined || except.indexOf(options.colModel.name) === -1){
				if($("#"+options.gid+" #"+options.rowId+" td:nth-child("+(options.pos+1)+")").children('span.help-block').length==0){//kalau xde help-block baru letak
					$("#"+options.gid+" #"+options.rowId+" td:nth-child("+(options.pos+1)+")").append("<span class='help-block'>"+desc+"</span>");
				}
			}else if(except != undefined && except.indexOf(options.colModel.name) !== -1){
				$("#"+options.gid+" #"+options.rowId+" td:nth-child("+(options.pos+1)+")").text('');
			}else{
			}
		});
		return this;
	}
	this.reset = function(){
		this.array.length = 0;
	}

}

fixPositionsOfFrozenDivs = function () {
    var $rows;
    if (typeof this.grid.fbDiv !== "undefined") {
        $rows = $(">div>table.ui-jqgrid-btable>tbody>tr", this.grid.bDiv);
        $(">table.ui-jqgrid-btable>tbody>tr", this.grid.fbDiv).each(function (i) {
            var rowHight = $($rows[i]).height(), rowHightFrozen = $(this).height();
            if ($(this).hasClass("jqgrow")) {
                $(this).height(rowHight);
                rowHightFrozen = $(this).height();
                if (rowHight !== rowHightFrozen) {
                    $(this).height(rowHight + (rowHight - rowHightFrozen));
                }
            }
        });
        $(this.grid.fbDiv).height(this.grid.bDiv.clientHeight);
        $(this.grid.fbDiv).css($(this.grid.bDiv).position());
    }
    if (typeof this.grid.fhDiv !== "undefined") {
        $rows = $(">div>table.ui-jqgrid-htable>thead>tr", this.grid.hDiv);
        $(">table.ui-jqgrid-htable>thead>tr", this.grid.fhDiv).each(function (i) {
            var rowHight = $($rows[i]).height(), rowHightFrozen = $(this).height();
            $(this).height(rowHight);
            rowHightFrozen = $(this).height();
            if (rowHight !== rowHightFrozen) {
                $(this).height(rowHight + (rowHight - rowHightFrozen));
            }
        });
        $(this.grid.fhDiv).height(this.grid.hDiv.clientHeight);
        $(this.grid.fhDiv).css($(this.grid.hDiv).position());
    }
}

function recstatusDisable(recstatus = 'recstatus'){
	var recstatusvalue = $("#formdata [name='"+recstatus+"']:checked").val();
	if(recstatusvalue == 'ACTIVE'){
		$("#formdata input[name='"+recstatus+"']").prop('disabled', true);
	}else if (recstatusvalue == 'DEACTIVE'){
		$("#formdata input[name='"+recstatus+"']").prop('disabled', false);
	}else{
		$("#formdata input[name='"+recstatus+"']").prop('disabled', false);
	}
}

function my_remark_Function() {
	var dots = document.getElementById("dots");
	var moreText = document.getElementById("more");
	var btnText = document.getElementById("myBtn");

	if (dots.style.display === "none") {
		dots.style.display = "inline";
		btnText.innerHTML = "Read more"; 
		moreText.style.display = "none";
	} else {
		dots.style.display = "none";
		btnText.innerHTML = "Read less"; 
		moreText.style.display = "inline";
	}
}

$.jgrid.extend({
    setColWidth: function (iCol, newWidth, adjustGridWidth) {
        return this.each(function () {
            var $self = $(this), grid = this.grid, p = this.p, colName, colModel = p.colModel, i, nCol;
            if (typeof iCol === "string") {
                // the first parametrer is column name instead of index
                colName = iCol;
                for (i = 0, nCol = colModel.length; i < nCol; i++) {
                    if (colModel[i].name === colName) {
                        iCol = i;
                        break;
                    }
                }
                if (i >= nCol) {
                    return; // error: non-existing column name specified as the first parameter
                }
            } else if (typeof iCol !== "number") {
                return; // error: wrong parameters
            }
            grid.resizing = { idx: iCol };
            grid.headers[iCol].newWidth = newWidth;
            grid.newWidth = p.tblwidth + newWidth - grid.headers[iCol].width;
            grid.dragEnd();   // adjust column width
            if (adjustGridWidth !== false) {
                $self.jqGrid("setGridWidth", grid.newWidth, false); // adjust grid width too
            }
        });
    }
});


function seemoreFunction(dots,more,morebtn,callback) {
  var dots = document.getElementById(dots);
  var moreText = document.getElementById(more);
  var btnText = document.getElementById(morebtn);

  if (dots.style.display === "none") {
    dots.style.display = "inline";
    btnText.innerHTML = "Read more";
    moreText.style.display = "none";
  } else {
    dots.style.display = "none";
    btnText.innerHTML = "</br> Read less";
    moreText.style.display = "inline";
  }
  callback();
}

function remove_error(id){
	$( id ).parent().parent().removeClass( "has-error" );
	$( id ).parent().removeClass( "has-error" );
	$( id ).removeClass( "error" );
}

function getEditVal(val){
	if(val == undefined || val == 'undefined' || val == null){
		return '';
	}else if(val.search("[<]") != -1){
		return val.slice(0, val.search("[<]"));
	}
	return val
}

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
};


function galGridCustomValue (elem, operation, value){
	if(operation == 'get') {
		return $(elem).find("input").val();
	} 
	else if(operation == 'set') {
		$('input',elem).val(value);
	}
}

function dob_chg(date){
	let arrdate = date.split("-");
	return arrdate[2]+'-'+arrdate[1]+'-'+arrdate[0];
}

function dob_age(date){
	return moment().diff(date, 'years');
}

function if_none(str){
	return (str)?str:'NONE';
}

function SmoothScrollTo(id_or_Name, timelength){
  var timelength = timelength || 500;
  $('html, body').animate({
      scrollTop: $(id_or_Name).offset().top-70
  }, timelength, function(){

  });
}

function SmoothScrollToTop(){
  $("html, body").stop().animate({
  		scrollTop:0
  }, 500, 'swing', function() {

  });
}

function myerrorIt(id,errorField,fail){
	if(!fail){
		if($.inArray(id,errorField)!==-1){
			errorField.splice($.inArray(id,errorField), 1);
		}
		$( id ).parent().removeClass( "has-error" ).addClass( "has-success" );
		$( id ).removeClass( "error" ).addClass( "valid" );
	}else{
		if($.inArray(id,errorField)===-1){
			errorField.push( id );
			$( id ).parent().removeClass( "has-success" ).addClass( "has-error" );
			$( id ).removeClass( "valid" ).addClass( "error" );
		}
	}
}

function myerrorIt_only(id,fail){
	if(!fail){
		$( id ).parent().removeClass( "has-error" ).addClass( "has-success" );
		$( id ).removeClass( "error" ).addClass( "valid" );
		// $( id ).parent().siblings( ".help-block" ).html("");
	}else{
		$( id ).parent().removeClass( "has-success" ).addClass( "has-error" );
		$( id ).removeClass( "valid" ).addClass( "error" );
		$( id ).parent().siblings( ".help-block" ).html("");
	}
}

function emptyobj_(obj){
	if($.isEmptyObject(obj)){
		return true;
	}else{
		return false;
	}
}

function getjqcust_oper(opt){
	var rowid = opt.rowId;
	let got = opt.rowId.search('jqg');

	if(got == -1){
		return 'edit';
	}else{
		return 'add';
	}
}

$(document).ready(function () {

	$('.panel-heading.clearfix.collapsed.position .arrow.fa').click(function(){
		var toggle = $(this).data('toggle');
		if(toggle != undefined || toggle != null){
			if($(this).hasClass('fa-angle-double-up')){
				$(this).css('display','none');
				$(this).siblings('i.arrow.fa.fa-angle-double-down').css('display','inline-block');
			}else if($(this).hasClass('fa-angle-double-down')){
				$(this).css('display','none');
				$(this).siblings('i.arrow.fa.fa-angle-double-up').css('display','inline-block');
			}
		}
	});
});



/////////////////////////////////End utility function////////////////////////////////////////////////