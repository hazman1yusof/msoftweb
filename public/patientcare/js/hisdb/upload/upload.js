$(document).ready(function () {
	$('body').show();

    var DataTable_epis = $('#episodeList').DataTable({
    
        responsive: true,
        scrollY: 500,
        paging: false,
        order: [[ 3, "desc" ]],
        columns: [
            { data: 'episno', width: "10%"},
            { data: 'epistycode', width: "20%"},
            { data: 'reg_date', width: "20%"},
            { data: 'mrn', width: "15%"},
            { data: 'upload', width: "35%"}
            
        ],
        drawCallback: function( settings ) {
        },
        initComplete: function( settings, json ) {
        }
    });

    episode_load_data();

    function episode_load_data(){
        DataTable_epis.clear().draw();
        let mrn = $("#mrn").val();
        let episno = $("#episno").val();

        var urlParam={
            action:'episode value',
            url:'util/get_value_default',
            field:['episno','epistycode','reg_date','mrn'],
            table_name:['hisdb.episode'],
            table_id:'none_',
            filterCol:['mrn','episno'],
            filterVal:[parseInt(mrn),parseInt(episno)]
        }

        $.get( urlParam.url+"?"+$.param(urlParam), function( data ) {
                    
        },'json').done(function(data) {
            if(!$.isEmptyObject(data.rows)){
                data.rows.forEach(function(obj,i){
                    obj.episno = obj.episno; 
                    obj.epistycode = obj.epistycode;
                    obj.mrn = obj.mrn;
                    obj.upload = make_upload_butt(i,obj.reg_date);
                    obj.reg_date = formatDate_mom(obj.reg_date,'YYYY-MM-DD HH:mm:ss');
                });

                DataTable_epis.rows.add(data.rows).draw();
                DataTable_epis.columns.adjust().draw();
                upload_but_on(mrn);
            }
        });
    }

    function make_upload_butt(i,trxdate){
        return `
            <form class="upload_form" method="post" data-index="`+i+`" id="upload_form_`+i+`" enctype="multipart/form-data">
                <input type="file" name="file" accept="audio/*,image/*,video/*,application/pdf" style="display: none;" id="fileinput_`+i+`" data-index="`+i+`">

                <input type="hidden" value="`+trxdate+`" name='trxdate_`+i+`' id="trxdate_`+i+`" >

                <button type="button" oper='click' class='btn btn-primary btn-xs' data-index="`+i+`"><i class='fa fa-folder-open-o fa-2x' ></i></button>

                <button type="button" oper='cancel_`+i+`' class='btn btn-danger btn-xs' style="margin-left:5px;display: none;" data-index="`+i+`" data-index="`+i+`"><i class='fa fa-times fa-2x'></i></button>

                <button type="submit" oper='submit_`+i+`' class='btn btn-success btn-xs' style="display:none;" data-index="`+i+`" data-index="`+i+`"><i class='fa fa-check fa-2x'></i></button>
                <label id="label_`+i+`"></label>
            </form>
        `;
    }

    function upload_but_on(mrn){
        let click = $("form.upload_form button[oper='click']");

        click.on("click",function(){
            let i = $(this).data('index');
            $("#fileinput_"+i).click();
        });

        $('form.upload_form input[type="file"]').on("change", function(){
            let i = $(this).data('index');
            let filename = $(this).val();

            $("form.upload_form button[oper='cancel_"+i+"']").show();
            $("form.upload_form button[oper='submit_"+i+"']").show();
            $("form.upload_form #label_"+i).text(filename);

            $("form.upload_form button[oper='cancel_"+i+"']").on("click", function(){
                let i = $(this).data('index');

                $("form.upload_form button[oper='cancel_"+i+"']").hide();
                $("form.upload_form button[oper='submit_"+i+"']").hide();
                $("form.upload_form #label_"+i).text("");
                $("#upload_form_"+i).trigger('reset');

            })
        });

        $("form.upload_form").on('submit',function(e){
            let i = $(this).data('index');
            e.preventDefault();
            var formData = new FormData($(this));
            formData.append('_token', $('#_token').val());
            formData.append('mrn', mrn);
            formData.append('trxdate', $('#trxdate_'+i).val());
            formData.append('file',$("#fileinput_"+i).prop('files')[0]);

            $.ajax({
                url: "pat_enq/form",
                type: "POST",
                data:  formData,
                contentType: false,
                cache: false,
                processData:false,
                success: function(data, textStatus, jqXHR) {
                    alert('file saved!');
                },
                error: function(data, textStatus, jqXHR) {
                    alert('error occurs!');
                },
            });

            $("form.upload_form button[oper='cancel_"+i+"']").hide();
            $("form.upload_form button[oper='submit_"+i+"']").hide();
            $("form.upload_form #label_"+i).text("");
            $("#upload_form_"+i).trigger('reset');
        });
    }

    $('#biodob').text(formatDate_mom($('#biodob').text(),'YYYY-MM-DD'));

    $("#bioage").html(getAge($('#biodob').text()));
    function getAge(dateString) {
        var today = new Date();
        var birthDate = new Date(dateString);
        var age = today.getFullYear() - birthDate.getFullYear();
        var m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        return age;
    }

});
