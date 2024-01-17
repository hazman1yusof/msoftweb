function attachment_page(grid="#jqGrid"){
    this.gridid = grid;
    var self = this;

    var DataTable_preview = $('#attch_tablePreview').DataTable({
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
            $('#attch_tablePreview_filter').prepend(`
                <form class="upload_form ui input" id="attch_formdata" method="post" action="./attachment_upload/form" enctype="multipart/form-data">
                    <input type="text" id="attch_rename" name='rename' class="ui input" placeholder="Rename" style="display: none">
                    <button type="button" id='attch_click' class='ui icon button orange btn' ><i class='cloud upload icon' ></i></button>
                    <button type="button" id="attch_cancel" class='ui icon small red button btn' style="margin-left:5px;display: none;"><i class="fa fa-times" aria-hidden="true"></i></button>
                    <button type="button" id="attch_submit" class='ui icon small green button btn' style="display:none;"><i class='check icon'></i></button>

                    <input type="file" name="file" id="attch_file" accept="audio/*,image/*,video/*,application/pdf" style="display: none;">

                </form>
            `);

            init_submit_btn();

        }

    });

    function init_submit_btn(){
        $("#attch_click").on("click",function(){
            $("#attch_file").click();
        });

        $('#attch_file').on("change", function(){
            let filename = $(this).val();

            $("#attch_cancel").show();
            $("#attch_submit").show();
            $("#attch_rename").show();
        });

        $("#attch_cancel").on("click", function(){
            $("#attch_cancel").hide();
            $("#attch_submit").hide();
            $("#attch_rename").hide();

            $("#attch_formdata").trigger('reset');
        });

        $("#attch_submit").on("click",function(){
            let obj = {
                page:'invoiceap',
                idno:selrowData(self.gridid).apacthdr_idno,
                _token:$('#_token').val(),
            };
            let serializedForm = $("#attch_formdata").serialize();

            $.post( './attachment_upload/form?', serializedForm+'&'+$.param(obj) , function( data ) {
                
            }).fail(function(data) {
                self.preview_load_data();
            }).success(function(data){
                self.preview_load_data();
            });
        });
    }

    this.preview_load_data = function(){
        DataTable_preview.clear().draw();
        let mrn = $("#mrn").val();

        var urlParam={
            url:'./attachment_upload/table',
            page: 'invoiceap',
            idno: selrowData(this.gridid).apacthdr_idno,
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

    $('#attcahment_go').click(function(){
        if($("#gridAttch_panel").attr('aria-expanded') == 'false' || 
           $("#gridAttch_panel").attr('aria-expanded') == undefined)
        {
            $("#gridAttch_panel").collapse('show');
        }else{
            $("#gridAttch_panel").collapse('hide');
            $("#gridAttch_panel").data('reopen','true');
        }
    });

    $("#gridAttch_panel").on("hidden.bs.collapse", function(){
        if($(this).data('reopen') == 'true'){
            $(this).collapse('show');
        }
    });

    $("#gridAttch_panel").on("shown.bs.collapse", function(){
        $(this).data('reopen','false');
        DataTable_preview.columns.adjust();
        SmoothScrollTo("#gridAttch_c",300);
        self.preview_load_data();
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

    function make_download_butt(i,filepath,type,filename){
        let filetype = type.split('/')[0];
        filename = filename+'.'+filepath.split('.')[1];
        let fileextension = type.split('/')[1];


        return `<a class='small circular orange basic ui icon button' href="./download/`+filepath+`?filename=`+filename+`" data-index="`+i+`"><i class="download icon"></i></a>`
        
    }
}