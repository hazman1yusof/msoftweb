$(document).ready(function () {

    $("button.refreshbtn_userfile").click(function(){
        empty_userfile();
        populate_userfile(selrowData('#jqGrid'));
    });

    $("#tab_userfile").on("show.bs.collapse", function(){
        return check_if_user_selected();
    });

	$("#tab_userfile").on("shown.bs.collapse", function(){
        DataTable_preview.columns.adjust();
		SmoothScrollTo("#tab_userfile", 300);
		preview_load_data();
	});

    function preview_load_data(){
    	let mrn = $('#userfile_mrn').val();
        DataTable_preview.clear().draw();
        
        if(mrn.trim().length == 0){
            return false;
        }

        var urlParam={
            action:'preview value',
            url:'./ptcare_preview/data',
            mrn:parseInt(mrn)
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
        console.log(fileextension)
        let return_value='';

        if(filetype=='image'){
            return_value = `
                <div class="imgcontainer">
                    <img src="./ptcare_thumbnail/`+filepath+`" >
                      <a class="small circular orange ui icon button btn" target="_blank" href="./uploads/`+filepath+`">
                          <i class='search icon' ></i>
                      </a>
                </div>`;

        }else if(filetype=='application'){
            switch(fileextension){
                case 'pdf': return_value =  `
                                    <div class="imgcontainer">
                                        <img src="./ptcare_thumbnail/application/pdf">
                                          <a class="small circular orange ui icon button btn" target="_blank" href="./uploads/`+filepath+`" >
                                              <i class='search icon' ></i>
                                          </a>
                                    </div>`; 

                            break;
                case 'vnd.openxmlformats-officedocument.wordprocessingml.document':
                case 'msword': return_value =  `
                                    <div class="imgcontainer">
                                        <img src="./ptcare_thumbnail/application/msword">
                                          <a class="small circular orange ui icon button btn" target="_blank" href="./uploads/`+filepath+`" >
                                              <i class='search icon' ></i>
                                          </a>
                                    </div>`; 

                            break;
                case 'vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                case 'vnd.ms-excel':
                             return_value =  `
                                    <div class="imgcontainer">
                                        <img src="./ptcare_thumbnail/application/excel">
                                          <a class="small circular orange ui icon button btn" target="_blank" href="./uploads/`+filepath+`" >
                                              <i class='search icon' ></i>
                                          </a>
                                    </div>`; 

                            break;
                case 'vnd.openxmlformats-officedocument.presentationml.presentation':
                             return_value =  `
                                    <div class="imgcontainer">
                                        <img src="./ptcare_thumbnail/application/powerpoint">
                                          <a class="small circular orange ui icon button btn" target="_blank" href="./uploads/`+filepath+`" >
                                              <i class='search icon' ></i>
                                          </a>
                                    </div>`; 

                            break;

            }

        }else if(filetype=='video'){
            return_value =  `
                            <div class="imgcontainer">
                                <img src="./ptcare_thumbnail/video/video">
                                  <a class="small circular orange ui icon button btn" target="_blank" href="./ptcare_previewvideo/`+auditno+`" >
                                      <i class='search icon' ></i>
                                  </a>
                            </div>`; 
                                    

        }else{
            return_value = 'download';

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