
var DebtorType = function () {

    
    function populate_all_companies_into_cmb()
    {
        $.getJSON( "assets/php/entry.php?action=get_all_companies", function(data)
        {
            console.log(data);

            $.each(data.companies, function (index, value) 
            {
                $("#cmb_companies").append('<option value="'+value.compcode+'">'+value.name+'</option>');

                //alert(value.c_desc);
            });
        });
    }

    return {

        init_cmb_companies: function () {
            populate_all_companies_into_cmb();
        },

    };

}();