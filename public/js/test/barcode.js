
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	$("body").show();

	var dialog_itemcode = new ordialog(
		'itemcode',['material.stockloc AS s','material.product AS p','hisdb.taxmast AS t','material.uom AS u'],"input[name='itemcode']",{},
		{	colModel:
			[
				{label: 'Item Code',name:'p_itemcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label: 'Description',name:'p_description',width:400,classes:'pointer',canSearch:true,or_search:true},
				{label: 'Tax Code', name: 'p_TaxCode', width: 100, classes: 'pointer' },
				{label: 'Group Code', name: 'p_groupcode', width: 100, classes: 'pointer' },
				{label: 'Conversion', name: 'u_convfactor', width: 50, classes: 'pointer', hidden:true },
				{label: 'rate', name: 't_rate', width: 100, classes: 'pointer',hidden:true }
			],
			ondblClickRow:function(event){

				let data=selrowData('#'+dialog_itemcode.gridname);

				$('#desc').val(data['p_description']);

			},
			loadComplete:function(data){

			}
		},{
			title:"Select Item For Delivery Order",
			open:function(){
				// dialog_itemcode_init();
			},
			close: function(){
			}
		},'none','radio','tab'//urlParam means check() using urlParam not check_input
	);
	dialog_itemcode.makedialog(false);
	dialog_itemcode.urlParam.table_name = ['material.product AS p','hisdb.taxmast AS t','material.uom AS u'];
	dialog_itemcode.urlParam.fixPost = "true";
	dialog_itemcode.urlParam.table_id = "none_";
	dialog_itemcode.urlParam.filterCol = ['p.compcode', 'p.unit'];
	dialog_itemcode.urlParam.filterVal = ['session.compcode', 'session.unit'];
	dialog_itemcode.urlParam.join_type = ['LEFT JOIN','LEFT JOIN'];
	dialog_itemcode.urlParam.join_onCol = ['p.taxcode','u.uomcode'];
	dialog_itemcode.urlParam.join_onVal = ['t.taxcode','p.uomcode'];
	dialog_itemcode.urlParam.join_filterCol = [];
	dialog_itemcode.urlParam.join_filterVal = [];
	dialog_itemcode.on();

	$('#submit').click(function(){

		$.post(  '/barcode/form',$( "#testform" ).serialize(), function( data ) {
			
		}).fail(function(data) {
			
		}).success(function(data){
			
		});

	});

});