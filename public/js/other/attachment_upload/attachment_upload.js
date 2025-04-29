$(document).ready(function () {

    

    $("#click").on("click",function(){
        $("#file").click();
    });

    $('#file').on("change", function(){
        let filename = $(this).val();

        $("#cancel").show();
        $("#submit").show();
        $("#rename").show();
    });

    $("#cancel").on("click", function(){
        $("#cancel").hide();
        $("#submit").hide();
        $("#rename").hide();

        $("#formdata").trigger('reset');
    });

    $( "#formdata" ).on( "submit", function( event ) {
        $("#submit").attr('disabled','disabled');
        // event.preventDefault();
    });

    preview_load_data();

});

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

function make_delete_btn(i,page,auditno,idno){
    return `<span class='small circular red ui icon button' myhref="./attachment_delete?auditno=`+auditno+`&idno=`+idno+`&page=`+page+`" data-index="`+i+`" onclick="delete_btn(`+auditno+`,`+idno+`,'`+page+`')"><i class="trash icon" ></i></span>`
}

function make_download_butt(i,filepath,type,filename){
    let filetype = type.split('/')[0];
    filename = filename+'.'+filepath.split('.')[1];
    let fileextension = type.split('/')[1];


    return `<a class='small circular orange basic ui icon button' href="./attachment_download/`+filepath+`?filename=`+filename+`" data-index="`+i+`"><i class="download icon"></i></a>`;
}

function formatDate_mom(date,format,returnformat = 'DD-MM-YYYY'){
    let mom = moment(date, format);
    return mom.format(returnformat);
}

var DataTable_preview = $('#tablePreview').DataTable({

    responsive: true,
    scrollY: 250,
    paging: false,
    order: [[ 0, "desc" ]],
    columns: [
        { data: 'idno', width: "5%"},
        { data: 'trxdate', width: "10%"},
        { data: 'filename', width: "15%"},
        { data: 'preview', width: "35%"},
        { data: 'mrn' , width: "5%"},
        { data: 'adduser', width: "10%"},
        { data: 'adddate', width: "10%"},
        { data: 'download', width: "10%"},
        { data: 'type', visible: false},
        { data: 'auditno', visible: false},
    ],
    drawCallback: function( settings ) {
    },
    initComplete: function( settings, json ) {
    }

});

function preview_load_data(){
    DataTable_preview.clear().draw();
    let mrn = $("#mrn").val();

    var urlParam={
        url:'./attachment_upload/table',
        page: $('#my_page').val(),
        idno: $('#my_idno').val(),
    }

    $.get( urlParam.url+"?"+$.param(urlParam), function( data ) {
                
    },'json').done(function(data) {
        if(!$.isEmptyObject(data.rows)){
            data.rows.forEach(function(obj,i){
                obj.idno = obj.idno; 
                obj.auditno = obj.auditno; 
                obj.trxdate = formatDate_mom(obj.trxdate,'YYYY-MM-DD HH:mm:ss');
                obj.filename = obj.resulttext;
                obj.preview = make_preview_image(i,obj.attachmentfile,obj.type,obj.auditno);
                obj.mrn = make_delete_btn(i,obj.page,obj.auditno,obj.idno);
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


function delete_btn(auditno,idno,page){
    if (confirm("Are you sure to delete this attachment?") == true) {
        var obj={
            '_token':$('#_token').val(),
            'idno':idno,
            'auditno':auditno,
            'page_delete':page
        };

        $.post( "./attachment_upload/form?page=delete", obj , function( data ) {
            
        }).fail(function(data) {
            alert('error');
        }).done(function(data){
            preview_load_data();
        });
    } 
}
