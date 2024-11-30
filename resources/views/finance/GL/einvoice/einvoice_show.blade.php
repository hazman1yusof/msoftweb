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
        body{
/*            background: #00bcd421;*/
        }
        .column{
            padding: 5px 10px !important;
        }
    </style>
    
    <script>
    </script>

    <body>
      <div class="ui container" style="width: 90vw;margin: auto;">
          <div class="ui segments" style="margin-top: 20px;">
            <div class="ui secondary segment">
              <b>Submitted Invoice</b>
            </div>
            <div class="ui segment">
              <div class="ui grid" style="overflow-wrap: break-word;">
                  <div class="two wide column"><b>Invoice no.</b></div>
                  <div class="six wide column">{{str_pad($header->invno, 7, "0", STR_PAD_LEFT)}}</div>
                  <div class="two wide column"><b>Invoice Date</b></div>
                  <div class="six wide column">{{$header->posteddate}}</div>

                  <div class="two wide column"><b>Buyer Name</b></div>
                  <div class="fourteen wide column">{{$header->name}}</div>

                  <div class="two wide column"><b>Buyer I/C</b></div>
                  <div class="six wide column">{{$header->Newic}}</div>
                  <div class="two wide column"><b>Buyer TIN</b></div>
                  <div class="six wide column">{{$header->tinid}}</div>

                  <div class="two wide column"><b>Address</b></div>
                  <div class="six wide column">{{$header->address1}}</div>
                  <div class="two wide column"><b>Handpone</b></div>
                  @if(!empty($header->teloffice))
                  <div class="six wide column">{{$header->teloffice}}</div>
                  @else
                  <div class="six wide column">{{$header->telhp}}</div>
                  @endif

                  <div class="two wide column"><b></b></div>
                  <div class="six wide column">{{$header->address2}}</div>
                  <div class="two wide column"><b>City</b></div>
                  <div class="six wide column">{{$header->address2}}</div>

                  <div class="two wide column"><b></b></div>
                  <div class="six wide column">{{$header->address3}}</div>
                  <div class="two wide column"><b>Postcode</b></div>
                  <div class="six wide column">{{$header->postcode}}</div>

                  <div class="two wide column"><b>State Code</b></div>
                  <div class="six wide column">{{$header->statecode}}</div>
                  <div class="two wide column"><b>Total</b></div>
                  <div class="six wide column">{{$header->amount}}</div>
              </div>
            </div>
            <div class="ui segment">
              @if($header->status == 'ERROR')
                <div class="sixteen wide column"><b style="color:darkred">Submission Error</b></div>
                <div class="ui grid" style="overflow-wrap: break-word;">
                <div class="sixteen wide column"><b style="color:darkred">{{$header->message}}</b></div>

              @else
                  @if($header->status == 'REJECTED')
                    <div class="ui grid" style="overflow-wrap: break-word;">
                        <div class="sixteen wide column"><b style="color:darkred">Rejected Document</b></div>
                        <div class="two wide column"><b>Submission ID</b></div>
                        <div class="six wide column">{{$header->submissionUid}}</div>
                        <div class="two wide column"><b>Code Number</b></div>
                        <div class="six wide column">{{$header->invoiceCodeNumber}}</div>
                        <div class="two wide column"><b>Error Message</b></div>
                        <div class="six wide column">{{$header->message}}</div>
                        <div class="two wide column"><b>Error Code</b></div>
                        <div class="six wide column">{{$header->code}}</div>
                        <div class="two wide column"><b>Error Path</b></div>
                        <div class="six wide column">{{$header->propertyPath}}</div>
                    </div>
                  @else
                    <div class="ui grid" style="overflow-wrap: break-word;">
                        <div class="sixteen wide column"><b style="color:green">Accepted Document</b></div>
                        <div class="two wide column"><b>Submission ID</b></div>
                        <div class="six wide column">{{$header->submissionUid}}</div>
                        <div class="two wide column"><b>Code Number</b></div>
                        <div class="six wide column">{{$header->invoiceCodeNumber}}</div>
                        <div class="two wide column"><b>Document ID</b></div>
                        <div class="six wide column">{{$header->uuid}}</div>
                    </div>
                  @endif

              @endif
            </div>
            <div class="ui segment">
                <table class="ui striped celled table">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Description</th>
                      <th>Amount</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($detail as $key => $obj)
                    <tr>
                      <td>{{$key+1}}</td>
                      <td>{{$obj->description}}</td>
                      <td>{{number_format($obj->totamount,2)}}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
            </div>
          </div>
      </div>      
    </body>

</html>