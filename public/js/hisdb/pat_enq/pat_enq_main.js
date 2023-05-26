
var bootgrid_last_rowid = null;
var bootgrid_last_row = null;

$(document).ready(function() {
    $(".preloader").fadeOut();
    stop_scroll_on();
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

    var grid = $("#grid-command-buttons").bootgrid({
        selection: true,
        rowSelect: true,
        ajax: true,
        ajaxSettings: {
            cache: false
        },
        searchSettings: {
            delay: 350,
            characters: 1
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

            let _page = $("#grid-command-buttons").bootgrid("getCurrentPage");
            let lastMrn = null;
            let lastidno = null;

            return {
                lastMrn:lastMrn,
                lastidno:lastidno,
                page: _page,
                searchCol:_searchCol,
                searchVal:_searchVal,
                _token: $('#csrf_token').val(),
            };
        },
        url: "pat_enq/form?action=maintable",
        formatters: {
            "col_add": function (column,row) {
                var retval = "<button title='Address' type='button' class='btn btn-xs btn-default btn-md command-add' data-row-id=\"" + row.MRN + "\"  name=\"cmd_add" + row.MRN + "\" data-telhp=\"" + row.telhp + "\"data-telh=\"" + row.telh + "\"data-Address1=\"" + row.Address1 + "\"data-Address2=\"" + row.Address2 + "\"data-Address3=\"" + row.Address3 + "\"data-Postcode=\"" + row.Postcode + "\"data-OffAdd1=\"" + row.OffAdd1 + "\"data-OffAdd2=\"" + row.OffAdd2 + "\"data-OffAdd3=\"" + row.OffAdd3 + "\"data-OffPostcode=\"" + row.OffPostcode + "\"data-pAdd1=\"" + row.pAdd1 + "\"data-pAdd2=\"" + row.pAdd2 + "\"data-pAdd3=\"" + row.pAdd3 + "\"data-pPostCode=\"" + row.pPostCode + "\" ><span class=\"glyphicon glyphicon-home\" aria-hidden=\"true\"></span></button>";
                return retval;
            },
            "col_mrn": function (column,row) {
                return ('0000000' + row.MRN).slice(-7);
            },
            "col_dob": function (column,row) {
                var birthday = new Date(row.DOB);
                return (isNaN(birthday.getFullYear()) ? '' : moment(birthday).format('DD/MM/YYYY'));
            },
            "col_age": function (column,row) {
                var birthday = new Date(row.DOB);
                return (isNaN(birthday)) ? '' : moment().diff(birthday, 'years',false);;
            },
            "col_preg": function (column,row) {
                var retval;
                if(row.pregnant == 1){
                    retval="&nbsp;<a class='btn btn-xs btn-default'><img src='img/pregnant.png' width='25' title='In Patinet'></a>";
                }else{
                    retval="";
                }
                return retval;
            }
        }
    }).on("loaded.rs.jquery.bootgrid", function(){

        grid.find("tr").on("click", function(e) {
            $("#grid-command-buttons tr").removeClass( "justbc" );
            $(e.currentTarget).addClass( "justbc" );
            closealltab();
        }).find(".command-add").on("click", function(e) {
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
        });

        if(!$("#Scol").length){ //tambah search col kat atas utk search by field
            $(".actionBar").prepend(`
                <select id='Scol' class='search form-group form-control' style='width: fit-content !important;'>
                    <option value='MRN'>MRN</option>
                    <option selected='true' value='Name'>Name</option>
                    <option value='Newic'>Newic</option>
                    <option value='Staffid'>Staffid</option>
                    <option value='telhp'>Handphone</option>
                    <option value='doctor'>Doctor</option>
                </select>`);
        }


        $("table#grid-command-buttons tr[data-row-id=0]").click();

    }).on("click.rs.jquery.bootgrid", function (e,c,r){

        bootgrid_last_rowid = $("#grid-command-buttons tr.justbc").data("row-id");
        let rows = $("#grid-command-buttons").bootgrid("getCurrentRows");
        bootgrid_last_row = rows[bootgrid_last_rowid];

        populate_episodelist(bootgrid_last_row);
    });

});

function calc_jq_height_onchange(jqgrid,max_height){
    $('#gview_'+jqgrid+' > div.ui-jqgrid-bdiv').css('height',max_height);
}

function stop_scroll_on(){
    $('div.paneldiv').on('mouseenter',function(){
        SmoothScrollTo('#'+$(this).parent('div.panel-collapse').attr('id'), 300,undefined,40);
        $('body').addClass('stop-scrolling');
    });

    $('div.paneldiv').on('mouseleave',function(){
        $('body').removeClass('stop-scrolling')
    });
}

function closealltab(except){
    var tab_arr = ["#episodelist_panel"];
    tab_arr.forEach(function(e,i){
        if(e != except){
            $(e).collapse('hide');
        }
    });
}

function get_age(dob){
    if(dob == null){
        return '';
    }
    const date = moment(dob, 'YYYY-MM-DD')
    const years = moment().diff(date, 'years')
    return years;
}