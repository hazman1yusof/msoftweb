@extends('layouts.authdtl_mobile')

@section('title', 'Purchase Order Mobile')

@section('css')
<style>
    .ui.segment, .ui.segments .segment {
    font-size: 0.85rem;
    }
    #main_container{
        padding-top: 10px;
    }
    #main_segment{
        height: 70vh;
    }
    #mygrid span{
/*      padding-left: 4px;*/
    }
    #mygrid .column{
        padding: 5px 0px;
    }
    #mygrid .column.cont1{
        padding: 5px 0px 5px 0px;
    }
    #mygrid .column.cont2{
        padding: 5px 0px 5px 0px;
    }
    #mygrid .row{
        padding: 0px;
    }
    .ui.grid {
       margin: 0rem; 
    }
    span.span_txt{
        color: #0057b1;
    display: block;
    margin-top: -5px;
    }
</style>
@endsection

@section('content')
<div id="main_container" class="ui container">
    <div class="ui segments" id="main_segment">
      <div class="ui secondary segment" id="main_title">
        <h3>Purchase Order</h3>
      </div>
      <div class="ui attached segment" id="main_segment" style="overflow:auto;">
        <h3>Header</h3>
        <div class="ui grid" id="mygrid">
            <div class="row">
                <div class="eight wide column ">
                 <b>Record No:</b><span> {{str_pad($po_hd->recno, 7, '0', STR_PAD_LEFT)}}</span>
                </div>
                <div class="eight wide column cont2">
                 <b>Request No:</b><span> {{str_pad($po_hd->purreqno, 7, '0', STR_PAD_LEFT)}}</span>
                </div>
            </div>
            <div class="row">
                <div class="eight wide column cont1">
                    <b>Request Department:</b>
                </div>
                <div class="eight wide column cont2">
                 <span>{{$po_hd->reqdept_desc}}</span>
                </div>
            </div>
            <div class="row">
                <div class="eight wide column cont1">
                    <b>Purchase Department:</b>
                </div>
                <div class="eight wide column cont2">
                 <span>{{$po_hd->prdept_desc}}</span>
                </div>
            </div>
            <div class="row">
                <div class="three wide column cont1">
                    <b>Supplier:</b>
                </div>
                <div class="twelve wide column cont2">
                 <span>{{$po_hd->suppcode_desc}}</span>
                </div>
            </div>
            <div class="row">
                <div class="three wide column cont1">
                    <b>Remarks:</b>
                </div>
                <div class="twelve wide column cont2">
                 <span>{{$po_hd->remarks}}</span>
                </div>
            </div>
            <div class="row">
                <div class="eight wide column">
                 <b>Date:</b><span> {{\Carbon\Carbon::parse($po_hd->purreqdt)->format('d-m-Y')}}</span>
                </div>
                <div class="eight wide column cont2">
                 <b>Total Amount:</b><span> {{number_format($po_hd->totamount,2)}}</span>
                </div>
            </div>
        </div>
        <div class="ui divider"></div>
        <h3>Detail</h3>
        <table class="ui selectable celled striped table">
              <tbody>
                @foreach($po_dt as $dt)
                <tr>
                  <td><b>Line No.</b> <span class="span_txt">{{$dt->lineno_}}</span></td>
                  <td><b>Item Code</b> <span class="span_txt">{{$dt->itemcode}}</span></td>
                  <td><b>Description</b> <span class="span_txt">{{$dt->itemcode_desc}}</span></td>
                  <td><b>UOM</b> <span class="span_txt">{{$dt->uomcode}} - {{$dt->uom_desc}}</span></td>
                  <td><b>PO UOM</b> <span class="span_txt">{{$dt->pouom}} - {{$dt->pouom_desc}}</span></td>
                  <td><b>Quantity Request</b> <span class="span_txt">{{$dt->qtyrequest}}</span></td>
                  <td><b>Unit Price</b> <span class="span_txt">{{number_format($dt->unitprice,2)}}</span></td>
                  <td><b>Total Amount</b> <span class="span_txt">{{number_format($dt->totamount,2)}}</span></td>
                  <td><b>remarks</b> <span class="span_txt">{{$dt->remarks}}</span></td>
                </tr>
                @endforeach
              </tbody>
            </table>
      </div>
        <div class="ui two bottom attached buttons">
            <div class="ui negative button" id="reject">Reject</div>
            <div class="ui positive button" id="post">{{$scope}}</div>
        </div>
    </div>
</div>

<div id='remark_modal' class="ui modal">
  <div class="content">
        <form class="ui form" id="formdata">
            <input type="hidden" id="oper" name="oper" value="{{$oper}}">
            <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" id="idno" name="idno" value="{{$po_hd->idno}}">
          <div class="field">
            <label>Remark</label>
            <textarea rows="5" name="remarks" id="remarks"></textarea>
          </div>
      </form>
  </div>
  <div class="actions">
    <button class="ui negative button">Cancel</button>
    <button class="ui positive button" id="submit_remark">Submit</button>
  </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function(){
        $("form#formdata").form({
            inline: true,
        fields: {
          remarks : ['maxLength[222]', 'empty']
        }
        });

        $('div#post').click(function(){
            $("form#formdata").form('remove field','remarks');

            $('#remark_modal.modal')
              .modal({
                closable: false,
                onApprove:function(){
                    $('button#submit_remark').attr('disabled');
                    return save_authdtl($('#oper').val());
                }
              }).modal('show');
        });

        $('div#reject').click(function(){
            if (confirm("Are you sure to reject this purchase request?") == true) {
                $("form#formdata").form('add rule', 'remarks', ['maxLength[222]', 'empty'])
                
              $('#remark_modal.modal')
                  .modal({
                    closable: false,
                    onApprove:function(){
                        $('button#submit_remark').attr('disabled');
                        return save_authdtl('cancel');
                    }
                  }).modal('show');
            }
        });
    });

    function save_authdtl(status){

        $('form#formdata').form('validate form');

        if(!$('form#formdata').form('is valid')){
            return false;
        }

        var obj={};
        obj.idno_array = [$('#idno').val()];
        obj.oper = status;
        obj._token = $('#_token').val();
        obj.remarks = $('#remarks').val();
        
        $.post( './purchaseRequest/form', obj , function( data ) {

        }).fail(function(data) {

        }).success(function(data){
            close_and_refresh();
        });
    }

    function close_and_refresh(){
        if (window.frameElement) {
            parent.close_and_refresh();
        }
    }
</script>
@endsection