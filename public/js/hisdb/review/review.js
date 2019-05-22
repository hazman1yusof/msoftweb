$(document).ready(function () {
	$('body').show();

    var DataTable_preview = $('#tablePreview').DataTable({
    
        responsive: true,
        scrollY: 450,
        paging: false,
        order: [[ 1, "desc" ]],
        columns: [
            { data: 'auditno', width: "5%"},
            { data: 'trxdate', width: "15%"},
            { data: 'preview', width: "40%"},
            { data: 'mrn' , width: "5%"},
            { data: 'adduser', width: "10%"},
            { data: 'adddate', width: "10%"},
            { data: 'download', width: "15%"},
            { data: 'type', visible: false},
        ],
        drawCallback: function( settings ) {
        },
        initComplete: function( settings, json ) {
        }

    });preview_load_data();

    function preview_load_data(){
        DataTable_preview.clear().draw();
        let mrn = $("#mrn").val();

        var urlParam={
            action:'preview value',
            url:'util/get_value_default',
            field:['auditno','attachmentfile','trxdate','type','mrn','adduser','adddate'],
            table_name:['hisdb.patresult'],
            table_id:'none_',
            filterCol:['mrn'],
            filterVal:[parseInt(mrn)]
        }

        $.get( urlParam.url+"?"+$.param(urlParam), function( data ) {
                    
        },'json').done(function(data) {
            if(!$.isEmptyObject(data.rows)){
                data.rows.forEach(function(obj,i){
                    obj.auditno = obj.auditno; 
                    obj.trxdate = obj.trxdate;
                    obj.preview = make_preview_image(i,obj.attachmentfile,obj.type);
                    obj.mrn = obj.mrn;
                    obj.type = obj.type;
                    obj.adduser = obj.adduser;
                    obj.adddate = obj.adddate;
                    obj.download = make_download_butt(i,obj.attachmentfile,obj.type);
                });

                DataTable_preview.rows.add(data.rows).draw();
                DataTable_preview.columns.adjust().draw();
                // upload_but_on(mrn);
            }
        });
    }

    function make_preview_image(i,filepath,type){
        let app_url = $('#app_url').val();
        let filetype = type.split('/')[0];
        let fileextension = type.split('/')[1];
        let return_value='';

        if(filetype=='image'){
            return_value = `
            	<div class="imgcontainer">
            		<img src="`+app_url+`thumbnail/`+filepath+`" >
  					<a class="btn btn-default btn-sm" target="_blank" href="`+app_url+`uploads/`+filepath+`">
  						<i class='fa fa-search fa-xs' ></i>
  					</a>
            	</div>`;

        }else if(filetype=='application'){
            switch(fileextension){
                case 'pdf': return_value =  `
            						<div class="imgcontainer">
                						<img src="`+app_url+`thumbnail/application/pdf">
					  					<a class="btn btn-default btn-sm" target="_blank" href="`+app_url+`uploads/`+filepath+`" >
					  						<i class='fa fa-search fa-xs' ></i>
					  					</a>
					            	</div>`; 

                			break;

                default: return_value = app_url+'thumbnail/application/pdf';

            }

        }else if(filetype=='video'){
            return_value = app_url+'thumbnail/video';

        }else{
            return_value = 'download';

        }

        return return_value;

    }

    function make_download_butt(i,filepath,type){
        let filetype = type.split('/')[0];
        let fileextension = type.split('/')[1];

        return `<a class='btn btn-default btn-xs nav-link' href="/download/`+filepath+`" data-index="`+i+`"><i class='fa fa-download fa-2x' ></i></a>`
        
    }
});
