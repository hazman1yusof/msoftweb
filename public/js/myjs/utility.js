

///////////////////////start utility function/////////////////////////////////////////////////////////
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

			console.log(ids);

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
		console.log(index);
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
		$(grid).jqGrid('setGridParam',{datatype:'json',url:'../../../../assets/php/entry.php?'+$.param(urlParam)}).trigger('reloadGrid', [{page:1}]);
	}else if(oper == 'edit' || oper == 'del'){
		$(grid).jqGrid('setGridParam',{datatype:'json',url:'../../../../assets/php/entry.php?'+$.param(urlParam)}).trigger('reloadGrid', [{current:true}]);
	}else if(oper == 'kosongkan'){
		$(grid).jqGrid('setGridParam',{datatype:'local'}).trigger('reloadGrid');
	}else{
		$(grid).jqGrid('setGridParam',{datatype:'json',url:'../../../../assets/php/entry.php?'+$.param(urlParam)}).trigger('reloadGrid');
	}
}

function disableForm(formName){
	$(formName+' textarea').prop("readonly",true);
	$(formName+' input').prop("readonly",true);
	$(formName+' input[type=radio]').prop("disabled",true);
	$(formName+' select').prop("disabled",true);
}

function enableForm(formName){
	$(formName+' textarea').prop("readonly",false);
	$(formName+' input').prop("readonly",false);
	$(formName+' input[type=radio]').prop("disabled",false);
	$(formName+' select').prop("disabled",false);
}

function populateFormdata(grid,dialog,form,selRowId,state){
	if(!selRowId){
		alert('Please select row');
		return emptyFormdata([],form);
	}
	rowData = $(grid).jqGrid ('getRowData', selRowId);
	$.each(rowData, function( index, value ) {
		var input=$(form+" [name='"+index+"']");
		if(input.is("[type=radio]")){
			$(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
		}else{
			input.val(value);
		}
	});
	if(dialog!=''){
		$(dialog).dialog( "open" );	
	}
}

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
}

function hideOne(form){
	$(form+' input[hideOne]').hide();
}

function parent_close_disabled(isClose){
	if (window.frameElement) {
		parent.disableCloseButton(isClose);
	}
}

function selrowData(grid){
	selrow = $(grid).jqGrid ('getGridParam', 'selrow');
	return $(grid).jqGrid ('getRowData', selrow);
}

function emptyFormdata(errorField,form,except){
	var temp=[];
	if(except!=null){
		$.each(except, function( index, value ) {
			temp.push($(value).val());
		});
	}
	errorField.length=0;
	$(form).trigger('reset');
	$(form+' .help-block').html('');
	if(except!=null){
		$.each(except, function( index, value ) {
			$(value).val(temp[index]);
		});
	}
}

function saveFormdata(grid,dialog,form,oper,saveParam,urlParam,searchForm,obj){
	if(obj==null){
		obj={};
	}
	$('.ui-dialog-buttonset button[role=button]').prop('disabled',true);
	saveParam.oper=oper;

	$.post( "../../../../assets/php/entry.php?"+$.param(saveParam), $( form ).serialize()+'&'+$.param(obj) , function( data ) {
		
	}).fail(function(data) {
		errorText(dialog.substr(1),data.responseText);
		$('.ui-dialog-buttonset button[role=button]').prop('disabled',false);
	}).success(function(data){
		if(grid!=null){
			refreshGrid(grid,urlParam,oper);
			$('.ui-dialog-buttonset button[role=button]').prop('disabled',false);
			$(dialog).dialog('close');
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
	$("div[aria-describedby="+dialog+"] .ui-dialog-buttonpane" ).prepend("<div class='alert alert-warning'><a href='#' class='close' data-dismiss='alert'>&times;</a><strong>Error!</strong> "+text+"</div>");
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

function searchClick(grid,form,urlParam){
	$(form+' [name=Stext]').on( "keyup", function() {
		delay(function(){
			search(grid,$(form+' [name=Stext]').val(),$(form+' input:radio[name=dcolr]:checked').val(),urlParam);
		}, 500 );
	});

	$(form+' [name=Scol]').on( "change", function() {
		search(grid,$(form+' [name=Stext]').val(),$(form+' input:radio[name=dcolr]:checked').val(),urlParam);
	});
}

function searchClick2(grid,form,urlParam){
	$(form+' [name=Stext]').on( "keyup", function() {
		delay(function(){
			search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
			$('#recnodepan').text("");//tukar kat depan tu
			$('#reqdeptdepan').text("");
			refreshGrid("#jqGrid3",null,"kosongkan");
		}, 500 );
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

	console.log(y + '-'+ mm + '-'+ dd);
	return y + '-'+ mm + '-'+ dd;
}

function setDateToNow(){
	$('input[type=date]').val(moment().format('YYYY/M/D'));
}

function currencymode(arraycurrency){
	this.array = arraycurrency;
	this.formatOn = function(){
		$.each(this.array, function( index, value ) {
			$(value).val(numeral($(value).val()).format('0,0.00'));
		});
	}
	this.formatOnBlur = function(){
		$.each(this.array, function( index, value ) {
			currencyBlur(value);currencyChg(value)
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

	function currencyBlur(v){
		$(v).on( "blur", function(){
			$(v).val(numeral($(v).val()).format('0,0.00'));
		});
	}

	function currencyChg(v){
		$(v).on( "keyup", function(event){
			var val = $(this).val();
			if(val.match(/[^0-9\.]/)){
				event.preventDefault();
				$(this).val(val.slice(0,val.length-1));
			}
		});
	}
}

function currencyRealval(v){
	return numeral().unformat($(v).val());
}

function modal(){
	this.style={
		display:"block",
		position:"absolute",
		"z-index":"1000",
		background:"rgba( 255, 255, 255, .8 ) url('../../../../assets/img/pIkfp.gif') 50% 50% no-repeat"
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
	return moment(cellvalue).format("DD/MM/YYYY");
}

function dateUNFormatter(cellvalue, options, rowObject){
	return moment(cellvalue, "DD/MM/YYYY").format("YYYY-MM-DD");
}

function jqgrid_label_align_right(grid){
	$.each($(grid).jqGrid('getGridParam','colModel'), function( index, value ) {
		if(value['align'] == 'right'){
			$(grid).jqGrid('setLabel',value['name'],value['label'],{'text-align':'right'});
		}
	});
}

function setactdate(target){
	this.actdateopen=[];
	this.lowestdate;
	this.highestdate;
	this.target=target;
	this.param={
		action:'get_value_default',
		field: ['*'],
		table_name:'sysdb.period',
		table_id:'idno'
	}

	this.getdata = function(){
		var self=this;
		$.get( "../../../../assets/php/entry.php?"+$.param(this.param), function( data ) {
			
		},'json').done(function(data) {
			if(!$.isEmptyObject(data.rows)){
				self.lowestdate = data.rows[0]["datefr1"];
				self.highestdate = data.rows[data.rows.length-1]["dateto12"];
				data.rows.forEach(function(element){
					$.each(element, function( index, value ) {
						if(index.match('periodstatus') && value == 'O'){
							self.actdateopen.push({
								from:element["datefr"+index.match(/\d+/)[0]],
								to:element["dateto"+index.match(/\d+/)[0]]
							})
						}
					});
				});
			}
		});
		return this;
	}

	this.set = function(){
		this.target.forEach(function(element){
			$(element).on('change',validate_actdate);
		});
	}

	function validate_actdate(obj){
		var permission = false;
		actdateObj.actdateopen.forEach(function(element){
		 	if(moment(obj.target.value).isBetween(element.from,element.to, null, '[]')) {
				permission=true
			}else{
				(permission)?permission=true:permission=false;
			}
		});
		if(!moment(obj.target.value).isBetween(actdateObj.lowestdate,actdateObj.highestdate)){
			bootbox.alert('Date not in accounting period setup');
			$(obj.currentTarget).val('').addClass( "error" ).removeClass( "valid" );
		}else if(!permission){
			bootbox.alert('Accounting Period Has been Closed');
			$(obj.currentTarget).val('').addClass( "error" ).removeClass( "valid" );
		} //Accounting Period Has been Closed
			//Date not in accounting period setup
		
	}
}

function ordialog(unique,table,id,errorField,jqgrid_,dialog_,checkstat='default'){
	this.unique=unique;
	this.gridname="othergrid_"+unique;
	this.dialogname="otherdialog_"+unique;
	this.otherdialog = "<div id='"+this.dialogname+"' title='"+dialog_.title+"'><div class='panel panel-default'><div class='panel-heading'><form id='checkForm_"+unique+"' class='form-inline'><div class='form-group'><b>Search: </b><div id='Dcol_"+unique+"' name='Dcol_"+unique+"'></div></div><div class='form-group' style='width:70%'><input id='Dtext_"+unique+"' name='Dtext_"+unique+"' type='search' style='width:100%' placeholder='Search here ...' class='form-control text-uppercase' autocomplete='off'></div></form></div><div class=panel-body><div id='"+this.gridname+"_c' class='col-xs-12' align='center'><table id='"+this.gridname+"' class='table table-striped'></table><div id='"+this.gridname+"Pager'></div></div></div></div></div>";
	this.errorField=errorField;
	this.dialog_=dialog_;
	this.jqgrid_=jqgrid_;
	this.check=checkInput;
	this.field=jqgrid_.colModel;
	this.textfield=id;
	this.eventstat='off';
	this.checkstat=checkstat;
	this.urlParam={
		from:unique,
		action:'get_table_default',
		table_name:table,
		field:getfield(jqgrid_.colModel),
		table_id:getfield(jqgrid_.colModel)[0],
		filterCol:[],filterVal:[],
		searchCol2:null,searchCol2:null,searchCol:null,searchCol:null
	};
	this.on = function(){
		this.eventstat='on';
		$(this.textfield).on('keydown',{data:this},onTab);
		$(this.textfield+" ~ a").on('click',{data:this},onClick);
		$("#Dtext_"+unique).on('keyup',{data:this},onChange);
		$("#Dcol_"+unique).on('change',{data:this},onChange);
		$(this.textfield).on('blur',{data:this,errorField:errorField},onBlur);
	}
	this.off = function(){
		this.eventstat='off';
		$(this.textfield).off('keydown',onTab);
		$(this.textfield+" ~ a").off('click',onClick);
		$("#Dtext_"+unique).off('keyup',onChange);
		$("#Dcol_"+unique).off('change',onChange);
		$(this.textfield).off('blur',onBlur);
	}
	this.makedialog = function(on=true){
		$("html").append(this.otherdialog);
		makejqgrid(this);
		makedialog(this);
		othDialog_radio(this);
		if(on){
			this.eventstat='on';
			$(this.textfield).on('keydown',{data:this},onTab);
			$(this.textfield+" ~ a").on('click',{data:this},onClick);
			$("#Dtext_"+unique).on('keyup',{data:this},onChange);
			$("#Dcol_"+unique).on('change',{data:this},onChange);
			$(this.textfield).on('blur',{data:this,errorField:errorField},onBlur);	
		}
	}

	function onClick(event){
		$("#"+event.data.data.dialogname).dialog( "open" );
		refreshGrid("#"+event.data.data.gridname,event.data.data.urlParam);
	}

	function onBlur(event){
		event.data.data.check(event.data.errorField);
	}

	function onTab(event){
		renull_search(event.data.data);
		if(event.key == "Tab"){
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
			$("#"+event.data.data.dialogname).dialog("open");
			refreshGrid("#"+event.data.data.gridname,event.data.data.urlParam);
			$("#Dtext_"+unique).val(text);
		}
	}

	function onChange(event){
		obj = event.data.data;
		renull_search(obj);
		let Dtext=$("#Dtext_"+obj.unique).val().trim();
		let Dcol=$("#Dcol_"+obj.unique+" input:radio[name=dcolr]:checked").val();
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

	function renull_search(obj){
		obj.urlParam.searchCol2=obj.urlParam.searchVal2=obj.urlParam.searchCol=obj.urlParam.searchVal=null;
	}

	function makedialog(obj){
		$("#"+obj.dialogname).dialog({
			autoOpen: false,
			width: 7/10 * $(window).width(),
			modal: true,
			open: function(){
				$("#"+obj.gridname).jqGrid ('setGridWidth', Math.floor($("#"+obj.gridname+"_c")[0].offsetWidth-$("#"+obj.gridname+"_c")[0].offsetLeft));
				if(obj.dialog_.hasOwnProperty('open'))obj.dialog_.open();
			},
			close: function( event, ui ){
				$("#Dtext_"+unique).val('');
				if(obj.dialog_.hasOwnProperty('close'))obj.dialog_.close();
			},
		});
	}

	function makejqgrid(obj){
		$("#"+obj.gridname).jqGrid({
			datatype: "local",
			colModel: obj.field,
			autowidth:true,viewrecords:true,loadonce:false,width:200,height:200,owNum:30,
			pager: "#"+obj.gridname+"Pager",
			onSelectRow:function(rowid, selected){
				if(obj.jqgrid_.hasOwnProperty('onSelectRow'))obj.jqgrid_.onSelectRow();
			},
			ondblClickRow: function(rowid, iRow, iCol, e){
				$(obj.textfield).val(rowid);
				$(obj.textfield).parent().next().html(selrowData("#"+obj.gridname)[getfield(obj.field)[1]]);
				$(obj.textfield).focus();
				if(obj.jqgrid_.hasOwnProperty('ondblClickRow'))obj.jqgrid_.ondblClickRow();
				$("#"+obj.dialogname).dialog( "close" );
				$("#"+obj.gridname).jqGrid("clearGridData", true);
			},
		});
		addParamField("#"+obj.gridname,false,obj.urlParam);
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

	function checkInput(errorField,urlParamID=0,desc=1){///can choose code and desc used, usually field number 0 and 1
		var table=this.urlParam.table_name,id=this.textfield,field=this.urlParam.field,value=$(this.textfield).val(),param={},self=this;
		let code_ = this.urlParam.field[urlParamID];
		let desc_ = this.urlParam.field[desc];

		if(this.checkstat=='default'){
			param={action:'input_check',table:table,field:field,value:value};

		}else{
			param=Object.assign({},this.urlParam);
			param.action="get_value_default";
			param.field=[code_,desc_];
			let index=jQuery.inArray(code_,param.filterCol);
			if(index == -1){
				param.filterCol.push(code_);
				param.filterVal.push(value);
			}else{
				param.filterVal[index]=value;
			}
		}

		$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {

		},'json').done(function(data) {
			let fail=true,code,desc;
			if(self.checkstat=='default'){
				if(data.msg=='success'){
					fail=false;desc=data.row[field[1]];
				}else if(data.msg=='fail'){
					fail=true;code=field[0];
				}
			}else{
				if(!$.isEmptyObject(data.rows)){
					fail=false;
					desc_ =(desc_.indexOf('.') !== -1)?desc_.split('.')[1]:desc_;
					desc=data.rows[0][desc_];
				}else{
					fail=true;code=code_;
				}
			}

			if(!fail){
				if($.inArray(id,errorField)!==-1 && typeof this.errorField == 'object'){
					errorField.splice($.inArray(id,errorField), 1);
				}
				$( id ).parent().removeClass( "has-error" ).addClass( "has-success" );
				$( id ).removeClass( "error" ).addClass( "valid" );
				$( id ).parent().siblings( ".help-block" ).html(desc);
				$( id ).parent().siblings( ".help-block" ).show();
			}else{
				$( id ).parent().removeClass( "has-success" ).addClass( "has-error" );
				$( id ).removeClass( "valid" ).addClass( "error" );
				$( id ).parent().siblings( ".help-block" ).html("Invalid Code ( "+code+" )");
				if($.inArray(id,errorField)===-1 && typeof this.errorField == 'object'){
					errorField.push( id );
				}
			}
		});
	}
}

/////////////////////////////////compid and ipaddress at cookies//////////////////////
function getcompid(callback){
	$.get( "../../../../assets/php//lib/my/getcompid.php" , function( data ) {
			
	},'json').done(function(data) {
		if(!$.isEmptyObject(data)){
			callback(data);
		}
	});
}

function check_compid_exist(lastcompid,lastip,compid,ip){
	var msoftweb_computerid = localStorage.getItem('msoftweb_computerid');
	var msoftweb_ipaddress = localStorage.getItem('msoftweb_ipaddress');
	if(!msoftweb_computerid){
		getcompid(function(data){
			localStorage.setItem('msoftweb_computerid', data.computerid);
			localStorage.setItem('msoftweb_ipaddress', data.ipaddress);
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
function getpadlen(callback){
	var param = {
		action: 'get_value_default',
		field: ['pvalue1'],
		table_name: ['sysdb.sysparam'],
		table_id: 'sysno',
		filterCol: ['source','trantype'],
		filterVal: ['IV','ZERO'],
	}

	$.get( "../../../../assets/php/entry.php?"+$.param(param) , function( data ) {
			
	},'json').done(function(data) {
		if(!$.isEmptyObject(data.rows[0])){
			callback(data);
		}
	});
}

function checkPadExist(){
	var msoftweb_padzero = localStorage.getItem('msoftweb_padzero');
	if(!msoftweb_padzero){
		getpadlen(function(data){
			localStorage.setItem('msoftweb_padzero', data.rows[0].pvalue1);
		});
	}
}
checkPadExist();//start checking..

function padzero(cellvalue, options, rowObject){
	let padzero = localStorage.getItem('msoftweb_padzero'), str="";
	while(padzero>0){
		str=str.concat("0");
		padzero--;
	}
	return pad(str, cellvalue, true);
}

function unpadzero(cellvalue, options, rowObject){
	return cellvalue.substring(cellvalue.search(/[1-9]/));
}

/////////////////////////////////End utility function////////////////////////////////////////////////