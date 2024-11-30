<!DOCTYPE html>
<html>
  <head>
      <title>E-Invoice</title>
  </head>
  
  <!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="mydata.js"></script> -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fomantic-ui@2.9.2/dist/semantic.min.css">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/fomantic-ui@2.9.2/dist/semantic.min.js"></script>

  <style>
  </style>
  
  <script>
    $(document).ready(function () {
      $('div.canclick').click(function(){
        $('div.canclick').removeClass('teal inverted');
        $(this).addClass('teal inverted');
        var idno = $(this).data('idno');

        if(idno != ''){
          $('#inv_iframe').attr('src',"../einvoice/table?action=einvoice_show&idno="+idno);
        }
      });
    });

  </script>

  <body style="margin: 0px;">
    <div class="ui segments" style="width: 18vw;height: 95vh;float: left; margin: 10px; position: fixed;">
      <div class="ui secondary segment">
        <h3>
        <b>Invoice List</b>
        </h3>
      </div>
      @foreach($einvoices as $key => $inv)
        <div class="ui segment canclick @if($key == 0){{'inverted teal'}}@endif" style="cursor: pointer;" data-idno='{{$inv->inv_idno}}'>
          <p>Invoice No: {{str_pad($inv->invno, 7, "0", STR_PAD_LEFT)}}</p>
        </div>
      @endforeach
    </div>

    <iframe id="inv_iframe" width="100%" height="100%" src="../einvoice/table?action=einvoice_show&idno={{$einvoices[0]->inv_idno}}" frameborder="0" style="width: 79vw;height: 100vh;float: right;"></iframe>
  </body>

</html>