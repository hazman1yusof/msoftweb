<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Diet Ordering</title>
  <!-- <link href="asset/bootstrap-4.3.1/css/bootstrap.min.css" rel="stylesheet" type="text/css"> -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <!-- <script language="javascript" type="text/javascript" src="asset/jquery-1.12.4.min.js"></script> -->
  <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
  <!-- <script language="javascript" type="text/javascript" src="asset/bootstrap-4.3.1/js/bootstrap.min.js"></script> -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <!-- <script language="javascript" type="text/javascript" src="asset/jquery.csv.min.js"></script> -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-csv/1.0.21/jquery.csv.min.js" integrity="sha512-Y8iWYJDo6HiTo5xtml1g4QqHtl/PO1w+dmUpQfQSOTqKNsMhExfyPN2ncNAe9JuJUSKzwK/b6oaNPop4MXzkwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <!-- <script language="javascript" type="text/JavaScript" src="asset/jquery.print.js"></script> -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.2/jQuery.print.min.js" integrity="sha512-t3XNbzH2GEXeT9juLjifw/5ejswnjWWMMDxsdCg4+MmvrM+MwqGhxlWeFJ53xN/SBHPDnW0gXYvBx/afZZfGMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style type="text/css">
    @import url('https://fonts.googleapis.com/css2?family=Patua+One&display=swap');
    h4{
      font-family: 'Patua One', cursive;
      text-align: center;
      color: #676767;
    }
    .ward{
      background-color: #828282 !important;
      color: white !important;
    }
    body{
      font-size: 10px;
    }
    th.rotate {
      height:80px;
      white-space: nowrap;
      position:relative;
    }

    th.rotate > div {
      transform: rotate(90deg);
      position:absolute;
      left:0;
      right:0;
      top: 10px;
      margin:auto;
      
    }

    table.table thead tr th,table.table tbody tr td{
      border-bottom: 1px solid grey;
    }

    table.table tbody tr td,
    table.table thead tr th,
    table.table thead {
      border-left: 1px solid #dee2e6;
      border-right: 1px solid #dee2e6;
    }
    div.vertical
    {
      position: absolute;
      left: -64px;
      bottom: 84px;
      text-align: left;
      width: 150px;
      transform: rotate(-90deg);
      -webkit-transform: rotate(-90deg); /* Safari/Chrome */
      -moz-transform: rotate(-90deg); /* Firefox */
      -o-transform: rotate(-90deg); /* Opera */
      -ms-transform: rotate(-90deg); /* IE 9 */
    }

    th.vertical
    {
      position: relative;
      height: 130px;
      line-height: 14px;
      padding-bottom: 70px;
      text-align: left;

    }
  </style>
  <script type="text/javascript">

    var ini_detail=[
      @foreach($dietorder as $key=>$diet) 
        {
          no:`{{$key}}`,
          bedno:`{{$diet->bed}}`,
          ward:`{{$diet->ward}}`,
          name:`{{$diet->Name}}`,
          mrn:`{{$diet->mrn}}`,
          age:`{{$diet->age}}`,
          diagnosis:`{{$diet->diagfinal}}`,
          lodger:`{{$diet->lodgervalue}}`,
          nbm:`{{$diet->nbm}}`,
          rtf:`{{$diet->rtf}}`,
          oral:`{{$diet->oral}}`,
          rof:`{{$diet->rof}}`,
          tpn:`{{$diet->tpn}}`,
          regulara:`{{$diet->regular_a}}`,
          regularb:`{{$diet->regular_b}}`,
          soft:`{{$diet->soft}}`,
          vegetarian:`{{$diet->vegetarian_c}}`,
          western:`{{$diet->western_d}}`,
          hiprotein:`{{$diet->highprotein}}`,
          hicalorie:`{{$diet->highcalorie}}`,
          hifiber:`{{$diet->highfiber}}`,
          diabetic:`{{$diet->diabetic}}`,
          loprotein:`{{$diet->lowprotein}}`,
          lofat:`{{$diet->lowfat}}`,
          losalt:`0`,
          kcal12:`{{$diet->red1200kcal}}`,
          kcal15:`{{$diet->red1500kcal}}`,
          month12:`{{$diet->paed6to12mth}}`,
          yr3:`{{$diet->paed1to3yr}}`,
          yr9:`{{$diet->paed4to9yr}}`,
          yr10:`{{$diet->paedgt10yr}}`,
          remarks:`{{$diet->remark}}`,
          remarks_kitchen:`{{$diet->remarkkitchen}}`,
          diet_time:`{{$diet->lastupdate}}`,
        },
      @endforeach 
    ];

    $(function(){
      $("#print_button").click(function(){
        $("#plot_table").print({
            prepend : `<br/><br/><h4>`+$('#diet_time').text()+`</h4><span style="font-size:13px; font-weight: bold;">Total no. of oral: `+oral_num+`</span><span style="float:right;font-size:13px;font-weight:bold;">Print date: `+getcurr_date()+`</span>
            `
        });
      });
      // window.history.pushState({}, "Title", "www.google.com");
      //history.replaceState('data to be passed', 'Title of the page', '/test');

      fetchdata();

      function tick(item){
        if(item=='1'){
          // return `<img src="asset/tick.png" alt="yes" width="15" height="15">`;
          return `<i class="fa fa-check" aria-hidden="true"></i>`
        }else{
          return `&nbsp&nbsp&nbsp`;
        }
      }

      function parseINIString(data){
          var regex = {
              section: /^\s*\[\s*([^\]]*)\s*\]\s*$/,
              param: /^\s*([^=]+?)\s*=\s*(.*?)\s*$/,
              comment: /^\s*;.*$/
          };
          var value = {};
          var lines = data.split(/[\r\n]+/);
          var section = null;
          lines.forEach(function(line){
              if(regex.comment.test(line)){
                  return;
              }else if(regex.param.test(line)){
                  var match = line.match(regex.param);
                  if(section){
                      value[section][match[1]] = match[2];
                  }else{
                      value[match[1]] = match[2];
                  }
              }else if(regex.section.test(line)){
                  var match = line.match(regex.section);
                  value[match[1]] = {};
                  section = match[1];
              }else if(line.length == 0 && section){
                  section = null;
              };
          });
          return value;
      }

      function getcurr_date(){
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();
        var HH = String(today.getHours()).padStart(2, '0');; // => 9
        var m = String(today.getMinutes()).padStart(2, '0');; // =>  30
        var ss = String(today.getSeconds()).padStart(2, '0');; // => 51

        today = dd + '/' + mm + '/' + yyyy + ' '+ HH +':'+m+':'+ss;
        return today;

      }

      function remarks_nbsp(remark){
        
        var remark2 = remark.replace(/ /g, '\u00a0');
      }

      function fetchdata(){
        console.log(ini_detail);
        var oral_num = 0;

        $("#plot_table tbody").html('');
        var ward_array = [];
        var num_array = [];

        $('#diet_time').text(ini_detail[0].diet_time);

        ini_detail.forEach(function(element,index){
          let wardname = (element.ward !=undefined)?element.ward.replace(/\s+/g,"_"):'undefined';
          if(!ward_array.includes(wardname)){
            ward_array.push(wardname);
            num_array[wardname] = 0;
            $("#plot_table tbody").append(`
              <tr id='`+wardname+`0' class="ward">
                <th colspan="31" >`+element.ward+`<span style='float:right'>TOTAL PATIENT : <span id='span`+wardname+`'>9</span>&nbsp;&nbsp;&nbsp</span></th>
              </tr>
            `);
          }
        });

        ini_detail.forEach(function(element,index){
          let wardname = (element.ward !=undefined)?element.ward.replace(/\s+/g,"_"):'undefined';
          //increase number of oral
          if(element.oral=='1')oral_num++;
          let old_tr = wardname+(num_array[wardname]);
          let new_tr = wardname+(num_array[wardname]+1);

          if(wardname == wardname)
          $("#plot_table tbody tr#"+old_tr).after(`
            <tr id=`+new_tr+`>
              <td>`+(num_array[wardname]+1)+`</td>
              <td>`+element.bedno+`</td>
              <td>`+element.name+`</td>
              <td>`+element.mrn+`</td>
              <td>`+element.age+`</td>
              <td>`+element.diagnosis+`</td>
              <td>`+element.lodger+`</td>
              <td>`+tick(element.nbm)+`</td>
              <td>`+tick(element.rtf)+`</td>
              <td>`+tick(element.oral)+`</td>
              <td>`+tick(element.rof)+`</td>
              <td>`+tick(element.tpn)+`</td>
              <td>`+tick(element.regulara)+`</td>
              <td>`+tick(element.regularb)+`</td>
              <td>`+tick(element.soft)+`</td>
              <td>`+tick(element.vegetarian)+`</td>
              <td>`+tick(element.western)+`</td>
              <td>`+tick(element.hiprotein)+`</td>
              <td>`+tick(element.hicalorie)+`</td>
              <td>`+tick(element.hifiber)+`</td>
              <td>`+tick(element.diabetic)+`</td>
              <td>`+tick(element.loprotein)+`</td>
              <td>`+tick(element.lofat)+`</td>
              <td>`+tick(element.kcal12)+`</td>
              <td>`+tick(element.kcal15)+`</td>
              <td>`+tick(element.yr3)+`</td>
              <td>`+tick(element.yr9)+`</td>
              <td>`+tick(element.yr10)+`</td>
              <td style="white-space: pre-wrap;">`+element.remarks+`</td>
              <td style="white-space: pre-wrap;">`+element.remarks_kitchen+`</td>
              <td>`+tick(element.discharge)+`</td>
            </tr>
          `);

          num_array[wardname]++;
        });

        ward_array.forEach(function(e,i){
          $('#span'+e).text(num_array[e]);
        });
        $('#oral_num').text(oral_num);
      }

    });
  </script>
</head>
<body>
  <div class="container mt-5">
    
    <span style="font-size:13px; font-weight: bold;">Total no. of oral: <span id="oral_num"></span> </span>
    <button class="btn btn-light rounded-circle mb-2 mr-2 float-right" id="print_button">
      <!-- <img src="asset/print.png" alt="print" width="18" height="18"/> -->
      <i class="fa fa-print" aria-hidden="true"></i>
    </button>
    <h4 id="diet_time"></h4>
    <table id="plot_table" class="table table-sm table-striped table-hover table-responsive shadow">
      <thead>
        <tr>
          <th rowspan="2" style="width:2%" >N<br/>O</th>
          <th rowspan="2" style="width:3%" class="vertical" ><div class="vertical">BEDROOM NO</div></th>
          <th rowspan="2" style="width:15%" >NAME</th>
          <th rowspan="2" style="width:5%" >MRN</th>
          <th rowspan="2" style="width:2%" >AGE</th>
          <th rowspan="2" style="width:10%" >DIAGNOSIS</th>
          <th rowspan="2" style="width:2%" class="vertical"><div class="vertical">LODGER</div></th>
          <th colspan="5" style="width:6%font-size: 8px" >MODE OF FEEDING</th>
          <th rowspan="2" style="width:2%;z-index: 0"  class="vertical"><div class="vertical">REGULAR (A)</div></th>
          <th rowspan="2" style="width:2%"  class="vertical"><div class="vertical">REGULAR (B)</div></th>
          <th rowspan="2" style="width:2%"  class="vertical"><div class="vertical">SOFT</div></th>
          <th rowspan="2" style="width:2%"  class="vertical"><div class="vertical">VEGETARIAN (C)</div></th>
          <th rowspan="2" style="width:2%"  class="vertical"><div class="vertical">WESTERN (D)</div></th>
          <th rowspan="2" style="width:2%"  class="vertical"><div class="vertical">HIGH PROTEIN</div></th>
          <th rowspan="2" style="width:2%"  class="vertical"><div class="vertical">HIGH CALORIE</div></th> 
          <th rowspan="2" style="width:2%"  class="vertical"><div class="vertical">HIGH FIBER</div></th> 
          <th rowspan="2" style="width:2%"  class="vertical"><div class="vertical">DIABETIC</div></th> 
          <th rowspan="2" style="width:2%"  class="vertical"><div class="vertical">LOW PROTEIN</div></th> 
          <th rowspan="2" style="width:2%"  class="vertical"><div class="vertical">LOW FAT</div></th> 
          <th colspan="2" style="width:4%;font-size: 8px"  >WEIGHT REDUCTION</th> 
          <th colspan="3" style="width:6%;font-size: 8px"  >PAEDIATRICS</th> 
          <th rowspan="2" style="width:15%"  >REMARKS</th> 
          <th rowspan="2" style="width:15%"  >REMARKS KITCHEN</th> 
          <th rowspan="2" style="width:2%"  class="vertical"><div class="vertical">DISCHARGE</div></th> 
        </tr>
        <tr>
          <th class="vertical"><div class="vertical">NBM</div></th>
          <th class="vertical"><div class="vertical">RTF</div></th>
          <th class="vertical"><div class="vertical">ORAL</div></th>
          <th class="vertical"><div class="vertical">ROF</div></th>
          <th class="vertical"><div class="vertical">TPN</div></th>
          <th class="vertical"><div class="vertical">1200 KCAL</div></th>
          <th class="vertical"><div class="vertical">1500 KCAL</div></th>
          <th class="vertical"><div class="vertical">1-3 YEARS</div></th>
          <th class="vertical"><div class="vertical">4-6 YEARS</div></th>
          <th class="vertical"><div class="vertical">7-10 YEARS</div></th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>

  </div>
</body>
</html>