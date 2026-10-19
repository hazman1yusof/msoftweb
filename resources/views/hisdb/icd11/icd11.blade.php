
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>WHO ICD-11 Coding Tool</title>

    <link rel="stylesheet"
          href="https://icdcdn.who.int/embeddedct/icd11ect-1.8.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fomantic-ui@2.9.2/dist/semantic.min.css">
</head>
<body>

    <!-- <h3>ICD-11 Coding Tool</h3> -->
    <div class="ui segments" style="position: relative; margin: 5px;">
        <div class="ui secondary segment bluecloudsegment" style="position: sticky; top: 0px; z-index: 3; height: 65px;">
            <div class="ui fluid right labeled action input">
                <button class="ui teal labeled icon button" type="button" style="margin-right: 5px;">
                     Code : <b id="code_b" style="margin-left: 5px;"></b>
                     <i class="eye icon"></i>
                </button>
                <input type="text" autocomplete="off" id="selected" style="background: teal; color: white;">

                <button class="ui green button " type="button" id="goback">
                     Select
                </button>
            </div>
        </div>
        <div class="ui segment">
            <input type="hidden" id="thecode">
            <input type="hidden" id="thedesc">

            <form class="ui form" autofocus>
                <label>Type to search ICD-11 code:</label>
                <input type="text" class="ctw-input" autocomplete="off" data-ctw-ino="1" id="thesearch" autofocus >
            </form>

            <div class="ctw-window" data-ctw-ino="1"></div>
        </div>
    </div>

    <script src="https://icdcdn.who.int/embeddedct/icd11ect-1.8.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/fomantic-ui@2.9.2/dist/semantic.min.js"></script>

    <script>
        // const mySettings = {
        //     // Developer test API, as used by the CodePen sample.
        //     apiServerUrl:
        //         "https://icd11restapi-developer-test.azurewebsites.net"
        // };

        // ECT.Handler.configure(mySettings);
        const mySettings = {
            apiServerUrl: "https://id.who.int",
            apiSecured: true,
            source: "mms",
            language: "en"
        };

        const myCallbacks = {
            getNewTokenFunction: async function () {
                const response = await fetch(
                    @json(route('icd11.token')),
                    {
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json'
                        }
                    }
                );

                if (!response.ok) {
                    throw new Error('Unable to obtain WHO API token');
                }

                const result = await response.json();

                return result.token;
            },

            selectedEntityFunction: function (selectedEntity) {
                const code = selectedEntity.code || '';
                const description = selectedEntity.title || '';

                // Works for either a popup window or an iframe.
                const receiver = window.opener || window.parent;

                const thecode = document.getElementById("thecode");
                const thedesc = document.getElementById("thedesc");
                const code_b = document.getElementById("code_b");
                const selected = document.getElementById("selected");

                thecode.value = code;
                thedesc.value = description;
                code_b.textContent  = code;
                selected.value = description;

            }
        };

        ECT.Handler.configure(mySettings, myCallbacks);

        $(document).ready(function () {
            $('#thesearch').focus();
            $('#goback').click(function(){
                if($('#thecode').val() == ''){
                    alert('Please search for the code first');
                    $('#thesearch').click().focus();
                }else{
                    let thecode = $('#thecode').val();
                    let thedesc = $('#thedesc').val();

                    alert('code: '+thecode+' -> '+thedesc);
                }
            });
        });
    </script>

</body>
</html>
