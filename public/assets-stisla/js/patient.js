$(document).ready(function() {

	var table = $('#example').DataTable( {
		"processing": true,
        "serverSide": true,
        "ajax": {
        	"url": "./patient_ajax",
        	"data": function ( d ) {
        		if(cur_page==null){
        			d.page = 1;
        		}else{
        			d.page = cur_page+1;
        		}
        		
		        d.where = $('#search-select').val();
		    }
        },
        "columns": [
            { "data": "Name" },
            { "data": "MRN" },
            { "data": "OldMrn" },
            { 'data' : "button"},
            { "data": "Baseline" , "className": "centertd"},
            { "data": "_1st_Month" , "className": "centertd"},
            { "data": "_3rd_Month" , "className": "centertd"},
            { "data": "_6th_Month" , "className": "centertd"},
            { 'data' : "_1_Year", "className": "centertd"},
            { 'data' : "_2_Year", "className": "centertd"},
            { 'data' : "_3_Year", "className": "centertd"},
            { 'data' : "_4_Year", "className": "centertd"},
        ]
	} ).on( 'init.dt', function () {
        $('#example_filter').prepend( `Search By &nbsp;<label>
        	<select class="custom-select custom-select-sm form-control form-control-sm" id="search-select">
			    <option value="OldMrn">HUKM sMRN</option>
			    <option value="Name" selected>Name</option>
			    <option value="Newic">New IC</option>
			</select></label>&nbsp;` );
    } );

	var cur_page=null;
	$('#example').on( 'page.dt', function () {
		var info = table.page.info();
		cur_page = info.page;
	});





	$('#example tbody').on( 'click', 'tr', function () {
		// $('#pathref').attr('href','/')

        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        } else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }

    	// var oData = table.rows('.selected').data();
    	// $('#pathref').attr('href','./study/'+oData[0][0]);
    } );

    $('#pathref').click( function () {

    	
    } );


});