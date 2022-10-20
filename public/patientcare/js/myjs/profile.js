/* Write here your custom javascript codes */
var Profile = function () {

    function signing_in(usrid, pwd, company)
    {
        console.log(company);

        $.getJSON( "assets/php/entry.php?action=signing_in&usrid=" + usrid + "&pwd=" + pwd + "&comp=" + company, function(data)
        {
            console.log(data);

            $.each(data.login_check, function (index, value) 
            {
                console.log(value.isValid);

                if (value.isValid == "1")
                {
                    location.href="index.php";
                }
                else
                {
                    alert("The information you entered are not valid. Please re-enter.");
                }
            });
        });           
    }

    function get_profile()
    {
        $.getJSON( "assets/php/entry.php?action=get_profile_data", function(data)
        {

            $.each(data.user_profile, function (index, value) 
            {
                $("#profile_name").html('<a href="profile.php">' + value.e_compname + '</a>');
                $("#a_login").hide();
                //alert(value.e_compname);
            });
        });           
    }

    return {
        signing_in: function (usrid, pwd, company) {
            signing_in(usrid, pwd, company);
        },

        get_profile: function () {
            get_profile();
        },

        init_profile_form: function(){
            init_profile_form();
        },
    };

}();
