
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
                var retval = "<button title='Address' type='button' class='btn btn-xs btn-default btn-md command-add' data-row-id=\"" + row.MRN + "\"  name=\"cmd_add" + row.MRN + "\" data-telhp=\"" + row.telhp + "\"data-telh=\"" + row.telh + "\"data-Address1=\"" + row.Address1 + "\"data-Address2=\"" + row.Address2 + "\"data-Address3=\"" + row.Address3 + "\"data-Postcode=\"" + row.Postcode + "\"data-OffAdd1=\"" + row.OffAdd1 + "\"data-OffAdd2=\"" + row.OffAdd2 + "\"data-OffAdd3=\"" + row.OffAdd3 + "\"data-OffPostcode=\"" + row.OffPostcode + "\"data-pAdd1=\"" + row.pAdd1 + "\"data-pAdd2=\"" + row.pAdd2 + "\"data-pAdd3=\"" + row.pAdd3 + "\"data-pPostCode=\"" + row.pPostCode + "\" ><span class=\"glyphicon glyphicon-home\" aria-hidden=\"true\"></span></button>";
                if(row.PatStatus == 1){
                    retval+="&nbsp;<a class='btn btn-xs btn-default'><img src='img/pat1.png' width='18'></a>";
                }
                return retval;
            },
            "col_mrn": function (column,row) {
                return ('0000000' + row.MRN).slice(-7);
            },
            "col_dob": function (column,row) {
                var birthday = new Date(row.DOB);
                return (isNaN(birthday.getFullYear()) ? 'n/a' : moment(birthday).format('DD/MM/YYYY'));
            },
            "col_age": function (column,row) {
                var birthday = new Date(row.DOB);
                return (isNaN(birthday)) ? '' : moment().diff(birthday, 'years',false);;
            },
            "commands": function (column,row) {
                let rowid = counter++;//just for specify each row
                return "<button title='Edit' type='button' class='btn btn-xs btn-warning btn-md command-edit' data-row-id=\"" + rowid + "\"  id=\"cmd_edit" + row.MRN + "\"><span class='glyphicon glyphicon-edit' aria-hidden='true'></span></button> " +
                       "<button title='Episode' type='button' class='btn btn-xs btn-danger btn-md command-episode' data-row-id=\"" + rowid + "\" data-mrn=\"" + row.MRN + "\" data-patstatus=\"" + row.PatStatus + "\"  id=\"cmd_history" + row.MRN + "\"><b>"+$("#epistycode").val()+"</b></button>" +
                       "<button title='OTC Episode' type='button' class='btn btn-xs btn-danger btn-md command-otc-episode' data-row-id=\"" + rowid + "\" data-mrn=\"" + row.MRN + "\" id=\"cmd_otc" + row.MRN + "\"><b>"+$("#epistycode2").val()+"</b></button>";
            }
        }
    }).on("loaded.rs.jquery.bootgrid", function(){
        counter = 0;

        if(!$("#Scol").length){ //tambah search col kat atas utk search by field and shit
            $(".actionBar").prepend(`
                <select id='Scol' class='search form-group form-control'>
                    <option value='MRN'>MRN</option>
                    <option selected='true' value='Name'>Name</option>
                    <option value='Newic'>Newic</option>
                    <option value='Staffid'>Staffid</option>
                    <option value='telhp'>Handphone</option>
                </select>`);
        }

        var detailRows = '';

        /* Executes after data is loaded and rendered */
        grid.find(".command-edit").on("click", function(e){
            let rowid = $(this).data("rowId");

            populate_patient(rowid);
            $('#mdl_patient_info').modal({backdrop: "static"});
            $("#btn_register_patient").data("oper","edit");
            // console.log($("#grid-command-buttons").bootgrid("getCurrentRows")[rowid]);
            $("#btn_register_patient").data("idno",$("#grid-command-buttons").bootgrid("getCurrentRows")[rowid].idno);
            
            desc_show.write_desc();
        }).end().find(".command-episode").on("click", function(e) {
            populate_episode($(this).data("rowId"));
            $('#editEpisode').modal({backdrop: "static"});

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

    // populatecombo1();

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

    $( "#patientBox").click(function() { // register new patient
        $('#mdl_patient_info').modal({backdrop: "static"});
        $("#btn_register_patient").data("oper","add");
        $('#episno').val('1');
    });

    /////////////////mykad///////////////

    $('#btn_mykad').click(function(){
       $('#mdl_mykad').modal('show');
    });

    $('#read_mykad').click(function(){
        $.getJSON('/util/mycard_read', function(data){
            console.log(data);
            if(data.status == failed){
                alert("Error reading Mycard");
            }else{
                $("#mykad_reponse").text("");
                $("#mykad_newic").val(data.ic);
                $("#mykad_DOB").val(data.dob);
                $("#mykad_birthPlace").val(data.birthplace);
                $("#mykad_pat_name").val(data.name);
                $("#mykad_oldic").val(data.oldic);
                $("#mykad_religion").val(data.religion);
                $("#mykad_gender").val(data.sex);
                $("#mykad_race").val(data.race);
                $("#mykad_address1").val(data.addr1);
                $("#mykad_address2").val(data.addr2);
                $("#mykad_address3").val(data.addr3);
                $("#mykad_city").val(data.city);
                $("#mykad_state").val(data.state);
                $("#mykad_postcode").val(data.postcode);
                $("#mykad_photo").attr('src', 'data:image/png;base64,'+data.mykad_photo);
            }
        });
    });

    ////////////////habis mykad///////

    $("#txt_epis_dept,#txt_epis_source,#txt_epis_case,#txt_epis_doctor,#txt_epis_fin,#txt_pat_title,#txt_ID_Type,#txt_RaceCode,#txt_Religion,#txt_LanguageCode,#txt_pat_citizen,#txt_pat_area,#txt_payer_company,#txt_pat_occupation").on('keydown',{data:this},onTab);


    if($('#curpat').val() == "true"){
        var preepisode = new preepisode_init();
        preepisode.makejqgrid();
    }

    function preepisode_init(){
        this.urlParam_preepis;

        this.makejqgrid = function(){

            this.urlParam_preepis = {
                    action:'get_table_default',
                    url:'/util/get_table_default',
                    field: '',
                    table_name: 'hisdb.pre_episode',
                    filterCol:['da.compcode'],
                    filterVal:['session.compcode'],
                }

            $("#jqGrid_preepis").jqGrid({
                datatype: "local",
                colModel: [
                    { label: 'Compcode', name: 'compcode' , width: 20 },
                    { label: 'MRN', name: 'mrn' , width: 60},
                    { label: 'Episode No.', name: 'episno', width: 20 }
                ],
                autowidth: true,
                multiSort: true,
                viewrecords: true,
                loadonce: false,
                viewrecords: false,
                width: 900,
                height: 200, 
                rowNum: 30,
                pager: "#jqGridPager_preepis",
                onSelectRow:function(rowid, selected){
                },
                loadComplete: function(){
                    
                },
                ondblClickRow: function(rowid, iRow, iCol, e){
                },
                gridComplete: function () {
                },
            });

            addParamField('#jqGrid_preepis', false, this.urlParam_preepis);
            var self = this;
            $("#tabpreepis").on("shown.bs.collapse", function(){
                $("#jqGrid_preepis").jqGrid ('setGridWidth', Math.floor($("#jqGrid_preepis_c")[0].offsetWidth-$("#jqGrid_preepis_c")[0].offsetLeft-0));
                refreshGrid("#jqGrid_preepis", self.urlParam_preepis);
            });
        }
    }

    

} );