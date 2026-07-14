$(document).ready(function () {

    $("button.refreshbtn_userfile").click(function(){
        empty_userfile();
        populate_userfile(selrowData('#jqGrid'));
    });

    $("#tab_userfile").on("show.bs.collapse", function(){
        return check_if_user_selected();
    });

	// $("#tab_userfile").on("shown.bs.collapse", function(){
    //     DataTable_preview.columns.adjust();
	// 	SmoothScrollTo("#tab_userfile", 300);
	// 	preview_load_data();
	// });
    preview_load_data();

    $("#refresh_userfile_btn").click(function(){
        preview_load_data();
    });

    $("#upload_userfile_btn").click(function(){
        $('#upload_userfile_fld').click();
    });
    
    $('#upload_userfile_fld').on("change", function (){
        let filename = $(this).val();
        uploadfile_userfile();
    });

    $('#userfile_tab .item').tab({});

    $('#userfile_tab .item').click(function(){
        preview_load_data();
    });

});

function uploadfile_userfile(){
    var formData = new FormData();
    formData.append('file', $('#upload_userfile_fld')[0].files[0]);
    formData.append('_token', $("#csrf_token").val());
    formData.append('mrn', $("#mrn_apptMain").val());
    formData.append('episno', $("#episno_apptMain").val());
    formData.append('type', $('#userfile_tab .item.active').data('tab'));
    
    $.ajax({
        url: './ptcare_preview/form?action=uploadfile_userfile',
        type: 'POST',
        data: formData,
        dataType: 'json',
        async: false,
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
    }).done(function (msg){
        // make_all_attachment(msg.invChart_allAttach);
        // $('#idno_mmse').val(msg.idno);
        preview_load_data();
    });
}

var DataTable_preview = $('#tablePreview').DataTable({
    autoWidth: true,
    responsive: true,
    // scrollY: 450,
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
        { data: 'download', width: "5%"},
        { data: 'type', visible: false},
        { data: 'delete', visible: "5%"},
    ],
    drawCallback: function( settings ) {
    },
    initComplete: function( settings, json ) {
    }
});

function preview_load_data(){
    // let mrn = $('#userfile_mrn').val();
    let mrn = $('#mrn_apptMain').val();
    DataTable_preview.clear().draw();
    
    if(mrn.trim().length == 0){
        return false;
    }

    var urlParam={
        action:'previewdata',
        url:'./ptcare_preview/data',
        type: $('#userfile_tab .item.active').data('tab'),
        mrn:parseInt(mrn)
    }

    $.get( urlParam.url+"?"+$.param(urlParam), function( data ) {
                
    },'json').done(function(data) {
        if(!$.isEmptyObject(data.rows)){
            data.rows.forEach(function(obj,i){
                obj.auditno = obj.idno; 
                obj.trxdate = formatDate_mom(obj.adddate,'YYYY-MM-DD HH:mm:ss');
                obj.filename = obj.filename;
                obj.preview = make_preview_image(i,obj.path);
                obj.mrn = obj.mrn;
                obj.type = obj.computerid;
                obj.adduser = obj.adduser;
                obj.adddate = formatDate_mom(obj.adddate,'YYYY-MM-DD HH:mm:ss');
                obj.download = make_download_butt(i,obj.path,obj.filename);
                obj.delete = make_delete_butt(obj.idno);
            });

            DataTable_preview.rows.add(data.rows).draw();
            DataTable_preview.columns.adjust().draw();
        }
    });
}

function make_preview_image(i,filepath){
    var ext = filepath.split('.').pop().toLowerCase();
    console.log(ext);
    var imageExtensions = [
        'jpg', 'jpeg', 'jpe', 'jif', 'jfif', 'jfi', // JPEG formats
        'png',                                      // PNG
        'gif',                                      // GIF
        'webp',                                     // WebP
        'tiff', 'tif',                              // TIFF
        'bmp', 'dib',                               // Bitmap
        'svg', 'svgz',                              // SVG Vector
        'heic', 'heif'                              // Apple HEIF
    ];

    var msWordExtensions = [
      "docx", // Office Open XML Document
      "doc",  // Binary Word Document
      "docm", // Macro-Enabled Document
      "dotx", // XML Template
      "dot",  // Binary Template
      "dotm", // Macro-Enabled Template
      "rtf",  // Rich Text Format
      "odt"   // OpenDocument Text
    ];
    var msExcelExtensions = [
      "xlsx", // Office Open XML Spreadsheet
      "xls",  // Binary Excel Spreadsheet
      "xlsm", // Macro-Enabled Spreadsheet
      "xltx", // XML Template
      "xlt",  // Binary Template
      "xltm", // Macro-Enabled Template
      "xlsb", // Excel Binary Spreadsheet
      "csv",  // Comma-Separated Values
      "ods"   // OpenDocument Spreadsheet
    ];
    var msPowerPointExtensions = [
      "pptx", // Office Open XML Presentation
      "ppt",  // Binary PowerPoint Presentation
      "pptm", // Macro-Enabled Presentation
      "potx", // XML Template
      "pot",  // Binary Template
      "potm", // Macro-Enabled Template
      "ppsx", // XML Slide Show
      "pps",  // Binary Slide Show
      "ppsm", // Macro-Enabled Slide Show
      "odp"   // OpenDocument Presentation
    ];
    var textFileExtensions = [
      "txt",  // Plain Text File
      "log",  // Log File
      "md",   // Markdown Documentation
      "rst",  // ReStructuredText
      "cfg",  // Configuration File
      "ini",  // Initialization File
      "tsv",   // Tab-Separated Values
      "bin",  // BIN File
    ];

    let return_value='';

    if($.inArray(ext, imageExtensions) !== -1){
        return_value = `
            <div class="imgcontainer" style="position:relative;width:fit-content" >
                <img src="./attachment_upload/thumbnail/`+filepath+`" >
                  <a class="small circular orange ui icon button btn" target="_blank" href="./uploads/`+filepath+`" syle="position: absolute;top: 20%;left: 20%;">
                      <i class='search icon' ></i>
                  </a>
            </div>`;

    }else if ($.inArray(ext, msWordExtensions) !== -1){
        return_value =  `
            <div class="imgcontainer" style="position:relative;width:fit-content" >
                <img src="./attachment_upload/thumbnail/application/msword">
                  <a class="small circular orange ui icon button btn" target="_blank" href="./uploads/`+filepath+`" syle="position: absolute;top: 20%;left: 20%;">
                      <i class='search icon' ></i>
                  </a>
            </div>`; 


    }else if ($.inArray(ext, msExcelExtensions) !== -1){
        return_value =  `
            <div class="imgcontainer" style="position:relative;width:fit-content" >
                <img src="./attachment_upload/thumbnail/application/excel">
                  <a class="small circular orange ui icon button btn" target="_blank" href="./uploads/`+filepath+`" syle="position: absolute;top: 20%;left: 20%;">
                      <i class='search icon' ></i>
                  </a>
            </div>`; 


    }else if ($.inArray(ext, msPowerPointExtensions) !== -1){
        return_value =  `
            <div class="imgcontainer" style="position:relative;width:fit-content" >
                <img src="./attachment_upload/thumbnail/application/powerpoint">
                  <a class="small circular orange ui icon button btn" target="_blank" href="./uploads/`+filepath+`" syle="position: absolute;top: 20%;left: 20%;">
                      <i class='search icon' ></i>
                  </a>
            </div>`; 


    }else if ($.inArray(ext, textFileExtensions) !== -1){
        return_value =  `
            <div class="imgcontainer" style="position:relative;width:fit-content" >
                <img src="./attachment_upload/thumbnail/text/notepad">
                  <a class="small circular orange ui icon button btn" target="_blank" href="./uploads/`+filepath+`" syle="position: absolute;top: 20%;left: 20%;">
                      <i class='search icon' ></i>
                  </a>
            </div>`; 


    }else if (ext == 'pdf'){
        return_value =  `
                <div class="imgcontainer" style="position:relative;width:fit-content" >
                    <img src="./attachment_upload/thumbnail/application/pdf">
                      <a class="small circular orange ui icon button btn" target="_blank" href="./uploads/`+filepath+`" syle="position: absolute;top: 20%;left: 20%;">
                          <i class='search icon' ></i>
                      </a>
                </div>`; 


    }else{
        return_value = 'download';
    }

    return return_value;
}

function make_download_butt(i,filepath,filename){
    var ext = filepath.split('.').pop().toLowerCase();
    var filename2 = filename;

    return `<a class='small circular orange basic ui icon button' href="./attachment_download/`+filepath+`?filename=`+filename2+`" data-index="`+i+`"><i class="download icon"></i></a>`
}

function make_delete_butt(idno){
    return `<a class='small circular red ui icon button' onclick="delete_userfile('`+idno+`')" data-idno="`+idno+`" ><i class="delete icon"></i></a>`
}

function delete_userfile(idno){

    var userConfirmed = window.confirm("Are you sure you want to delete the file?");
    
    if (userConfirmed) {
        
        var urlParam={
            action:'delete_userfile',
            url:'./ptcare_preview/table',
            idno: idno
        }

        $.get( urlParam.url+"?"+$.param(urlParam), function( data ) {
                    
        }).done(function(data) {
            preview_load_data();
        });

    }
}

function empty_userfile(){
    DataTable_preview.clear().draw();
    $("#tab_userfile").collapse('hide')
    $('#userfile_mrn').val('');
    
    //panel header
    $('#name_show_userfile').text('');
    $('#mrn_show_userfile').text('');
    $('#sex_show_userfile').text('');
    $('#dob_show_userfile').text('');
    $('#age_show_userfile').text('');
    $('#race_show_userfile').text('');
    $('#religion_show_userfile').text('');
    $('#occupation_show_userfile').text('');
    $('#citizenship_show_userfile').text('');
    $('#area_show_userfile').text('');
}

//screen current patient//
function populate_userfile(obj){
    $('#userfile_mrn').val(obj.MRN);
    //panel header
    $('#name_show_userfile').text(obj.Name);
    $('#mrn_show_userfile').text(("0000000" + obj.MRN).slice(-7));
    $('#sex_show_userfile').text(if_none(obj.Sex).toUpperCase());
    $('#dob_show_userfile').text(dob_chg(obj.DOB));
    $('#age_show_userfile').text(dob_age(obj.DOB)+' (YRS)');
    $('#race_show_userfile').text(if_none(obj.raceDesc).toUpperCase());
    $('#religion_show_userfile').text(if_none(obj.religion).toUpperCase());
    $('#occupation_show_userfile').text(if_none(obj.OccupCode).toUpperCase());
    $('#citizenship_show_userfile').text(if_none(obj.Citizencode).toUpperCase());
    $('#area_show_userfile').text(if_none(obj.AreaCode).toUpperCase());
    
    DataTable_preview.clear().draw();
    $("#tab_userfile").collapse('hide')
}