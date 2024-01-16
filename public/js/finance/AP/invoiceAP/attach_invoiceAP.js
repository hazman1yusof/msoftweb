$(document).ready(function () {

    $('#attcahment_go').click(function(){
        $("#gridAttch_panel").collapse('show');
    });

	$("#gridAttch_panel").on("shown.bs.collapse", function(){
        DataTable_preview.columns.adjust();
		SmoothScrollTo("#gridAttch_c",300);
		preview_load_data();
	});

    function preview_load_data(){
        DataTable_preview.clear().draw();
        let mrn = $("#mrn").val();

        var urlParam={
            url:'./attachment_upload/table',
            page: 'invoiceap',
            idno: selrowData("#jqGrid").apacthdr_idno,
        }

        $.get( urlParam.url+"?"+$.param(urlParam), function( data ) {
                    
        },'json').done(function(data) {
            if(!$.isEmptyObject(data.rows)){
                data.rows.forEach(function(obj,i){
                    obj.auditno = obj.auditno; 
                    obj.trxdate = formatDate_mom(obj.trxdate,'YYYY-MM-DD HH:mm:ss');
                    obj.filename = obj.resulttext;
                    obj.preview = make_preview_image(i,obj.attachmentfile,obj.type,obj.auditno);
                    obj.mrn = obj.mrn;
                    obj.type = obj.type;
                    obj.adduser = obj.adduser;
                    obj.adddate = formatDate_mom(obj.adddate,'YYYY-MM-DD HH:mm:ss');
                    obj.download = make_download_butt(i,obj.attachmentfile,obj.type,obj.resulttext);
                });

                DataTable_preview.rows.add(data.rows).draw();
                DataTable_preview.columns.adjust().draw();
            }
        });
    }

    function make_preview_image(i,filepath,type,auditno){
        let filetype = type.split('/')[0];
        let fileextension = type.split('/')[1];
        let return_value='';

        if(filetype=='image'){
            return_value = `
                <div class="imgcontainer">
                    <img src="./attachment_upload/thumbnail/`+filepath+`" >
                      <a class="small circular orange ui icon button btn" target="_blank" href="./uploads/`+filepath+`">
                          <i class='search icon' ></i>
                      </a>
                </div>`;

        }else if(filetype=='text'){
            switch(fileextension){
                case 'csv':  return_value =  `
                                    <div class="imgcontainer">
                                        <img src="./attachment_upload/thumbnail/text/notepad">
                                          <a class="small circular orange ui icon button btn" target="_blank" href="./uploads/`+filepath+`" >
                                              <i class='search icon' ></i>
                                          </a>
                                    </div>`; 

                            break;
                default:   return_value =  `
                                    <div class="imgcontainer">
                                        <img src="./attachment_upload/thumbnail/text/notepad">
                                          <a class="small circular orange ui icon button btn" target="_blank" href="./uploads/`+filepath+`" >
                                              <i class='search icon' ></i>
                                          </a>
                                    </div>`; 

                            break;
            }

        }else if(filetype=='application'){
            switch(fileextension){
                case 'csv': 
                case 'pdf': return_value =  `
                                    <div class="imgcontainer">
                                        <img src="./attachment_upload/thumbnail/application/pdf">
                                          <a class="small circular orange ui icon button btn" target="_blank" href="./uploads/`+filepath+`" >
                                              <i class='search icon' ></i>
                                          </a>
                                    </div>`; 

                            break;
                case 'vnd.openxmlformats-officedocument.wordprocessingml.document':
                case 'msword': return_value =  `
                                    <div class="imgcontainer">
                                        <img src="./attachment_upload/thumbnail/application/msword">
                                          <a class="small circular orange ui icon button btn" target="_blank" href="./uploads/`+filepath+`" >
                                              <i class='search icon' ></i>
                                          </a>
                                    </div>`; 

                            break;
                case 'vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                             return_value =  `
                                    <div class="imgcontainer">
                                        <img src="./attachment_upload/thumbnail/application/excel">
                                          <a class="small circular orange ui icon button btn" target="_blank" href="./uploads/`+filepath+`" >
                                              <i class='search icon' ></i>
                                          </a>
                                    </div>`; 

                            break;
                case 'vnd.openxmlformats-officedocument.presentationml.presentation':
                             return_value =  `
                                    <div class="imgcontainer">
                                        <img src="./attachment_upload/thumbnail/application/powerpoint">
                                          <a class="small circular orange ui icon button btn" target="_blank" href="./uploads/`+filepath+`" >
                                              <i class='search icon' ></i>
                                          </a>
                                    </div>`; 

                            break;

            }

        }else if(filetype=='video'){
            return_value =  `
                            <div class="imgcontainer">
                                <img src="./attachment_upload/thumbnail/video/video">
                                  <a class="small circular orange ui icon button btn" target="_blank" href="./previewvideo/`+auditno+`" >
                                      <i class='search icon' ></i>
                                  </a>
                            </div>`; 
                                    

        }else{
            return_value = 'Image not applicable';

        }

        return return_value;

    }

    function make_download_butt(i,filepath,type,filename){
        let filetype = type.split('/')[0];
        filename = filename+'.'+filepath.split('.')[1];
        let fileextension = type.split('/')[1];


        return `<a class='small circular orange basic ui icon button' href="./download/`+filepath+`?filename=`+filename+`" data-index="`+i+`"><i class="download icon"></i></a>`
        
    }
});

var DataTable_preview = $('#tablePreview').DataTable({
    autoWidth: true,
    responsive: true,
    scrollY: 450,
    paging: false,
    order: [[ 0, "desc" ]],
    columns: [
        { data: 'auditno', width: "5%"},
        { data: 'trxdate', width: "10%"},
        { data: 'filename', width: "15%"},
        { data: 'preview', width: "35%"},
        { data: 'mrn' , width: "5%"},
        { data: 'adduser', width: "10%"},
        { data: 'adddate', width: "10%"},
        { data: 'download', width: "10%"},
        { data: 'type', visible: false},
    ],
    drawCallback: function( settings ) {
    },
    initComplete: function( settings, json ) {
    }

});