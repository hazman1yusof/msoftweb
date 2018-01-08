$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

$(document).ready(function () {
    $("body").show();
    
    /////////////////////////validation//////////////////////////
    $.validate({
        language : {
            requiredFields: ''
        },
    });
    
    var errorField=[];
    conf = {
        onValidate : function($form) {
            if(errorField.length>0){
                return {
                    element : $(errorField[0]),
                    message : ' '
                }
            }
        },
    };
    var Class2 = $('#Class2').val();
    ////////////////////////////////////start dialog///////////////////////////////////////
    var butt1=[{
        text: "Save",click: function() {
            if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
                saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam);
            }
        }
    },{
        text: "Cancel",click: function() {
            $(this).dialog('close');
        }
    }];

    var butt2=[{
        text: "Close",click: function() {
            $(this).dialog('close');
        }
    }];

    var oper;
    $("#dialogForm")
      .dialog({ 
        width: 9/10 * $(window).width(),
        modal: true,
        autoOpen: false,
        open: function( event, ui ) {
            parent_close_disabled(true);
            switch(oper) {
                case state = 'add':
                    $( this ).dialog( "option", "title", "Add" );
                    enableForm('#formdata');
                    hideOne('#formdata');
                    break;
                case state = 'edit':
                    $( this ).dialog( "option", "title", "Edit" );
                    enableForm('#formdata');
                    frozeOnEdit("#dialogForm");
                    $('#formdata :input[hideOne]').show();
                    break;
                case state = 'view':
                    $( this ).dialog( "option", "title", "View" );
                    disableForm('#formdata');
                    $('#formdata :input[hideOne]').show();
                    $(this).dialog("option", "buttons",butt2);
                    break;
            }
            if(oper!='view'){
                //dialog_dept.handler(errorField);
            }
            if(oper!='add'){
                toggleFormData('#jqGrid','#formdata');
                //dialog_dept.check(errorField);
            }
        },
        close: function( event, ui ) {
            parent_close_disabled(false);
            emptyFormdata(errorField,'#formdata');
            //$('.alert').detach();
            $('#formdata .alert').detach();
            $("#formdata a").off();
            if(oper=='view'){
                $(this).dialog("option", "buttons",butt1);
            }
        },
        buttons :butt1,
      });

    /////////////////////parameter for jqgrid url/////////////////////////////////////////////////
    
    var urlParam={
        action:'get_table_default',
        field:'',
        table_name:'material.product',
        table_id:'idno',
        sort_idno:true,
        filterCol:['Class'],
        filterVal:[ $('#Class2').val()]
    }

    /////////////////////parameter for saving url////////////////////////////////////////////////
    
    
    $("#jqGrid").jqGrid({
        datatype: "local",
         colModel: [
            {label: 'idno', name: 'idno', hidden: true},
            { label: 'Item code', name: 'itemcode', width: 20, classes: 'wrap', canSearch: true, checked:true},						
            { label: 'Item Description', name: 'description', width: 40, classes: 'wrap', canSearch: true},
            { label: 'UOM Code', name: 'uomcode', width: 20, classes: 'wrap'},
            { label: 'Quantity on Hand', name: 'qtyonhand', width: 30,classes: 'wrap',align: 'right'},
            { label: 'Average Cost', name: 'avgcost', width: 40,classes: 'wrap',align: 'right'},
            { label: 'Current Price', name: 'currprice', width: 40, classes: 'wrap',align: 'right'},

            
        ],
        autowidth:true,
        multiSort: true,
        viewrecords: true,
        loadonce:false,
        height: 124,
        //width: 100,
        rowNum: 30,
        pager: "#jqGridPager",
        onSelectRow:function(rowid, selected){
            var jg=$("#jqGrid").jqGrid('getRowData',rowid);
            if(rowid != null) {
                urlParam2.filterVal[0]=selrowData("#jqGrid").itemcode; 
                urlParam2.filterVal[1]=selrowData("#jqGrid").uomcode;
                refreshGrid('#detail',urlParam2);

                urlParam3.filterVal[0]=selrowData("#jqGrid").itemcode;
                                    
                refreshGrid('#itemExpiry',urlParam3);

            }
        },
    });
    
    $("#jqGrid").jqGrid('navGrid','#jqGridPager',
        {	
            edit:false,view:false,add:false,del:false,search:false,
            beforeRefresh: function(){
                refreshGrid("#jqGrid",urlParam);
            },
            
        }	
    );

     $("#jqGrid").jqGrid('setLabel', 'qtyonhand', 'Quantity on Hand', {'text-align':'right'});
     $("#jqGrid").jqGrid('setLabel', 'avgcost', 'Average Cost', {'text-align':'right'});
     $("#jqGrid").jqGrid('setLabel', 'currprice', 'Current Price', {'text-align':'right'});

    /////////////////////parameter for jqgrid url/////////////////////////////////////////////////
    var urlParam2={
        action:'get_table_default',
        field:['deptcode','stocktxntype','uomcode','qtyonhand','itemcode'],
        table_name:'material.stockloc',
        table_id:'itemcode',
        filterCol:['itemcode', 'uomcode','year'],
        filterVal:['', '',$("#getYear").val()],
       
    }

    $("#detail").jqGrid({
        datatype: "local",
        colModel: [
            //{ label: 'idno', name: 'idno', width: 40, classes: 'wrap', hidden:true},
             { label: 'Department Code', name: 'deptcode', width: 40, classes: 'wrap'},
            { label: 'Stock TrxType', name: 'stocktxntype', width: 40, classes: 'wrap'},
            { label: 'UOM Code', name: 'uomcode', width: 40, classes: 'wrap'},
            { label: 'Quantity on Hand', name: 'qtyonhand', width: 40, classes: 'wrap',align: 'right'},
            { label: 'itemcode', name: 'itemcode', width: 40, classes: 'wrap',hidden:true},
            { label: 'Stock Value', name: 'stockvalue', width: 40, classes: 'wrap'},
            
            //{ label: 'idno', name: 'idno', width: 30, classes: 'wrap', hidden:true},
        ],
        autowidth:true,
        multiSort: true,
        viewrecords: true,
        loadonce:false,
        height: 124,
        rowNum: 30,
        width: 700,
        pager: "#jqGridPager2",
        
        onSelectRow:function(rowid, selected){
            var jg=$("#jqGrid").jqGrid('getRowData',rowid);

            /*    urlParam3.filterVal[0]=selrowData("#jqGrid").itemcode; 
                
                urlParam3.filterVal[1]=selrowData("#jqGrid").uomcode;
                urlParam3.filterVal[2]=selrowData("#jqGrid").deptcode;
                refreshGrid('#itemExpiry',urlParam3); 
                */
        },
    });
      
    
    $("#jqGrid").jqGrid('navGrid','#jqGridPager',
        {	
            edit:false,view:false,add:false,del:false,search:false,
            beforeRefresh: function(){
                refreshGrid("#jqGrid",urlParam);
            },
            
        }	
    );

     $("#detail").jqGrid('setLabel', 'qtyonhand', 'Quantity on Hand', {'text-align':'right'});
    
        var urlParam3={
        action:'get_table_default',
        field:['expdate','batchno','balqty','uomcode','itemcode','deptcode'],
        table_name:'material.stockexp',
        table_id:'itemcode',
        sort_itemcode:true,
        /*filterCol:['itemcode', 'uomcode','deptcode'],
        filterVal:['', '',''],*/
        filterCol:['itemcode','year'],
        filterVal:['',$("#getYear").val()],
    }

    /////////////////////parameter for saving url////////////////////////////////////////////////
    
    $("#itemExpiry").jqGrid({
        datatype: "local",
         colModel: [
            //{label: 'idno', name: 'idno', hidden: true},
            { label: 'Expiry Date', name: 'expdate', width: 40, classes: 'wrap', canSearch: true, checked:true},						
            { label: 'Batch No', name: 'batchno', width: 40, classes: 'wrap'},
            { label: 'Balance Quantity', name: 'balqty', width: 40, classes: 'wrap'},
        
        ],
        autowidth:true,
        multiSort: true,
        viewrecords: true,
        loadonce:false,
        height: 124,
        rowNum: 30,
        width:450,
        pager: "#jqGridPager3",

        onSelectRow:function(rowid, selected){
            var jg=$("#jqGrid").jqGrid('getRowData',rowid);
            
        },
    });

    function getStockvalue() {
        var netmvqty1 = $("#netmvqty1").val();
        var netmvqty2 = $("#netmvqty2").val();
        var netmvqty3 = $("#netmvqty3").val();
        var netmvqty4 = $("#netmvqty4").val();
        var netmvqty5 = $("#netmvqty5").val();
        var netmvqty6 = $("#netmvqty6").val();
        var netmvqty7 = $("#netmvqty7").val();
        var netmvqty8 = $("#netmvqty8").val();
        var netmvqty9 = $("#netmvqty9").val();
        var netmvqty10 = $("#netmvqt10").val();
        var netmvqty11= $("#netmvqty11").val();
        var netmvqty12 = $("#netmvqty12").val();

        total = netmvqty1 + netmvqty2 + netmvqty3+ netmvqty4 + netmvqty5+ netmvqty6 + netmvqty7
        + netmvqty8 + netmvqty9+ netmvqty10 + netmvqty11+ netmvqty12;
        $("#stockvalue").val(total.toFixed(2));
    }
    
    /*$("#itemExpiry").jqGrid('navGrid','#jqGridPager',
        {	
            edit:false,view:false,add:false,del:false,search:false,
            beforeRefresh: function(){
                refreshGrid("#itemExpiry",urlParam);
            },
            
        }	
    );*/

    $("#itemExpiry").jqGrid('setLabel', 'balqty', 'Balance Quantity', {'text-align':'right'});
    //////////handle searching, its radio button and toggle ///////////////////////////////////////////////
    
    toogleSearch('#sbut1','#searchForm','on');
    populateSelect('#jqGrid','#searchForm');
    searchClick('#jqGrid','#searchForm',urlParam);

    toogleSearch('#sbut2','#searchForm2','off');
    populateSelect('#detail','#searchForm2');
    searchClick('#detail','#searchForm2',urlParam2);

    toogleSearch('#sbut3','#searchForm3','off');
    populateSelect('#itemExpiry','#searchForm3');
    searchClick('#itemExpiry','#searchForm3',urlParam3);

    //////////add field into param, refresh grid if needed////////////////////////////////////////////////
    addParamField('#jqGrid',true,urlParam);
    //addParamField('#jqGrid',false,saveParam,['idno']);
    //addParamField('#detail',false,urlParam2,['idno']);

    $("#pg_jqGridPager2 table").hide();
    $("#pg_jqGridPager3 table").hide();
});
