
$(document).ready(function() {

    $('#tab_patient_info a:last').hide();    // hide Medical Info tab
    jQuery.validator.setDefaults({
      debug: true,
      success: "valid"
    });
    // $('#frm_patient_info').validate({
    //     rules: {
    //         telh: {
    //           require_from_group: [1, ".phone-group"]
    //         },
    //         telhp: {
    //           require_from_group: [1, ".phone-group"]
    //         }
    //     }
    // });    // patient form validation

    var counter = 0;
    var grid = $("#grid-command-buttons").bootgrid({
        selection: true,
        rowSelect: true,
        ajax: true,
        ajaxSettings: {
            cache: false
        },
        searchSettings: {
            delay: 350,
            characters: 2
        },
        post: function ()
        {
            Stext = $('.search-field').val();
            Scol = $('#Scol').val();

            if(Stext.trim() != ''){

                var split = Stext.split(" "),_searchCol=[],_searchVal=[];
                $.each(split, function( index, value ) {
                    _searchCol.push(Scol);
                    _searchVal.push('%'+value+'%');
                });

            }

            return {
                page: $("#grid-command-buttons").bootgrid("getCurrentPage"),
                searchCol:_searchCol,
                searchVal:_searchVal,
                table_name:'hisdb.pat_mast',
                field:'*',
                _token: $('#csrf_token').val(),
            };
        },
        url: "pat_mast/post_entry?action=get_patient_list&epistycode="+$("#epistycode").val()+"&curpat="+$("#curpat").val(),
        formatters: {
            "col_add": function (column,row) {
                return "<button title='Address' type='button' class='btn btn-xs btn-default btn-md command-add' data-row-id=\"" + row.MRN + "\"  name=\"cmd_add" + row.MRN + "\" data-telhp=\"" + row.telhp + "\"data-telh=\"" + row.telh + "\"data-Address1=\"" + row.Address1 + "\"data-Address2=\"" + row.Address2 + "\"data-Address3=\"" + row.Address3 + "\"data-Postcode=\"" + row.Postcode + "\"data-OffAdd1=\"" + row.OffAdd1 + "\"data-OffAdd2=\"" + row.OffAdd2 + "\"data-OffAdd3=\"" + row.OffAdd3 + "\"data-OffPostcode=\"" + row.OffPostcode + "\"data-pAdd1=\"" + row.pAdd1 + "\"data-pAdd2=\"" + row.pAdd2 + "\"data-pAdd3=\"" + row.pAdd3 + "\"data-pPostCode=\"" + row.pPostCode + "\" ><span class=\"glyphicon glyphicon-home\" aria-hidden=\"true\"></span></button>";
            },
            "col_mrn": function (column,row) {
                return ('0000000' + row.MRN).slice(-7);
            },
            "col_dob": function (column,row) {
                var birthday = new Date(row.DOB);
                return (isNaN(birthday.getFullYear()) ? 'n/a' : moment(birthday).format('DD/MM/YYYY'));
            },
            "col_age": function (column,row) {
                var day = new Date();
                var dob = new Date(row.DOB);
                return day.getFullYear() - dob.getFullYear();
            },
            "commands": function (column,row) {
                let rowid = counter++;//just for specify each row
                return "<button title='Edit' type='button' class='btn btn-xs btn-warning btn-md command-edit' data-row-id=\"" + rowid + "\"  id=\"cmd_edit" + row.MRN + "\"><span class='glyphicon glyphicon-edit' aria-hidden='true'></span></button> " +
                       "<button title='Episode' type='button' class='btn btn-xs btn-danger btn-md command-episode' data-row-id=\"" + rowid + "\" data-mrn=\"" + row.MRN + "\"  id=\"cmd_history" + row.MRN + "\"><span class='glyphicon glyphicon-header' aria-hidden='true'></span></button>" +
                       "<button title='OTC Episode' type='button' class='btn btn-xs btn-danger btn-md command-otc-episode' data-row-id=\"" + rowid + "\" data-mrn=\"" + row.MRN + "\" id=\"cmd_otc" + row.MRN + "\"><span class='fa fa-user-md' aria-hidden='true'></span></button>";
            }
        }
    }).on("loaded.rs.jquery.bootgrid", function(){
        counter = 0;

        if(!$("#Scol").length){ //tambah search col kat atas utk search by field and shit
            $(".actionBar").prepend("<select id='Scol' class='search form-group form-control'><option>MRN</option><option selected='true'>Name</option><option>Newic</option><option>Staffid</option></select>");
        }

        var detailRows = '';

        /* Executes after data is loaded and rendered */
        grid.find(".command-edit").on("click", function(e){
            let rowid = $(this).data("rowId");

            populate_patient_episode("edit",rowid);
            $('#mdl_patient_info').modal({backdrop: "static"});
            $("#btn_register_patient").data("oper","edit");
            // console.log($("#grid-command-buttons").bootgrid("getCurrentRows")[rowid]);
            $("#btn_register_patient").data("idno",$("#grid-command-buttons").bootgrid("getCurrentRows")[rowid].idno);
            
            desc_show.write_desc();
        }).end().find(".command-episode").on("click", function(e) {
            populate_patient_episode("episode",$(this).data("rowId"));
            $('#editEpisode').modal({backdrop: "static"});
            $('#btn_epis_payer').data('mrn',$(this).data("mrn"));

            disableEpisode(true);
        }).end().find(".command-add").on("click", function(e) {
            var html = '';

            html = html + '<table class=\"table table-bordered\">';

            html = html + '<tr>';
            html = html + '<th width=\"25%\">Contact No</th>';
            html = html + '<th width=\"25%\">Current Address</th>';
            html = html + '<th width=\"25%\">Office Address</th>';
            html = html + '<th width=\"25%\">Home Address</th>';
            html = html + '</tr>';

            html = html + '<tr>';
            html = html + '<td valign=\"top\" align=\"left\">';
            html = html + 'Phone (Mobile): 0' + $(this).data("telhp") + '<BR/>';
            html = html + 'Phone (House): 0' + $(this).data("telh") + '<BR/>';
            html = html + '</td>';

            html = html + '<td valign=\"top\" align=\"text-left\">';
            html = html + $(this).data("address1") + '<BR/>';
            html = html + $(this).data("address2") + '<BR/>';
            html = html + $(this).data("address3") + '<BR/>';
            html = html + $(this).data("postcode");
            html = html + '</td>';

            html = html + '<td valign=\"top\" align=\"left\">';
            html = html + $(this).data("offadd1") + '<BR/>';
            html = html + $(this).data("offadd2") + '<BR/>';
            html = html + $(this).data("offadd3") + '<BR/>';
            html = html + $(this).data("offpostcode");
            html = html + '</td>';

            html = html + '<td valign=\"top\" align=\"left\">';
            html = html + $(this).data("padd1") + '<BR/>';
            html = html + $(this).data("padd2") + '<BR/>';
            html = html + $(this).data("padd3") + '<BR/>';
            html = html + $(this).data("ppostcode");
            html = html + '</td>';

            html = html + '</tr>';
            html = html + '</table>';

            detailRows = html;

            var tr = $(this).closest('tr');
            var childID = $(this).data("rowId");
            var childName = "cmd_add" + childID;
            $('[name='+childName+']').hide();

            var addDetail='';
            addDetail = '<tr ><td><a href="javascript:void(0);" class="remCF btn btn-xs btn-primary btn-md"> <span class=\"glyphicon glyphicon-minus\" aria-hidden=\"true\"></span> </a></td><td colspan=\"8\">';
            addDetail =  addDetail + detailRows;
            addDetail = addDetail + '<td></tr>';

            tr.after(addDetail);

            $(".remCF").on('click',function(){
                $('[name='+childName+']').show();
                $(this).parent().parent().remove();
            });
        }).end().find("tr").on("click", function(e) {
            $("#grid-command-buttons tr").removeClass( "justbc" );
            $(e.currentTarget).addClass( "justbc" );
        });
    });

    populatecombo1();

    $("#txt_pat_dob").blur(function(){
       $('#txt_pat_age').val(gettheage($(this).val()));
       $("#txt_pat_dob-error").detach();
    })

    function gettheage(dob){
        if(dob != ''){
            var day = new Date();
            var dob = new Date(dob);
            var age_val =  day.getFullYear() - dob.getFullYear();
            return age_val;
        }
        return null;
    }

    $('#mdl_patient_info').on('hidden.bs.modal', function (e) {
        destoryallerror();
        $(this)
            .find("input,textarea,select")
            .val('')
            .end()
            .find("input[type=checkbox], input[type=radio]")
            .prop("checked", "")
            .end(); //this for clearing input after hide modal
    });

    $( "#patientBox").click(function() { // register new patient
        $('#mdl_patient_info').modal({backdrop: "static"});
        $("#btn_register_patient").data("oper","add");
        var first_visit_val =moment(new Date()).format('DD/MM/YYYY');
        $('#first_visit_date').val(first_visit_val);
        var last_visit_val =moment(new Date()).format('DD/MM/YYYY');
        $('#last_visit_date').val(last_visit_val);
        $('#episno').val('1');
    });

    /////////////////mykad///////////////

    $('#btn_mykad').click(function(){
       $('#mdl_mykad').modal('show');
    });

    $('#read_mykad').click(function(){
        $.getJSON('http://127.0.0.1:4000', function(data){
            if(data.response != "success"){
                $("#mykad_reponse").text(data.response);
            }else{
                $("#mykad_reponse").text("");
                $("#mykad_newic").val(data.mykad_newic);
                $("#mykad_DOB").val(data.mykad_DOB);
                $("#mykad_birthPlace").val(data.mykad_birthPlace);
                $("#mykad_pat_name").val(data.mykad_pat_name);
                $("#mykad_oldic").val(data.mykad_oldic);
                $("#mykad_religion").val(data.mykad_religion);
                $("#mykad_gender").val(data.mykad_gender);
                $("#mykad_race").val(data.mykad_race);
                $("#mykad_address1").val(data.mykad_address1);
                $("#mykad_address2").val(data.mykad_address2);
                $("#mykad_address3").val(data.mykad_address3);
                $("#mykad_city").val(data.mykad_city);
                $("#mykad_state").val(data.mykad_state);
                $("#mykad_postcode").val(data.mykad_postcode);
                $("#mykad_photo").attr('src', 'data:image/png;base64,'+data.mykad_photo);
            }
        });
    });

    ////////////////habis mykad///////

    $("#txt_epis_dept,#txt_epis_source,#txt_epis_case,#txt_epis_doctor,#txt_epis_fin").on('keydown',{data:this},onTab);


} );