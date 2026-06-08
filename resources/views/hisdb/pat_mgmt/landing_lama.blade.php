
@if (Auth::user()->doctor == 1)
    <div class='row' style="position: relative;margin: 0 12px 12px 12px" id="nursingED_row">
        @include('hisdb.nursingED.nursingED')
    </div>
    
    <div class='row' style="position: relative;margin: 0 12px 12px 12px" id="nursing_row">
        @include('hisdb.nursing.nursing',['page_screen' => "patmast"])
    </div>
    
    <div class='row' style="position: relative;margin: 0 12px 12px 12px">
        @include('hisdb.nursingActionPlan.nursingActionPlan')
    </div>
    
    <div class='row' style="position: relative;margin: 0 12px 12px 12px">
        @include('hisdb.nursingnote.nursingnote')
    </div>
    
    <div class='row' style="position: relative;margin: 0 12px 12px 12px" id="antenatal_row">
        @include('hisdb.antenatal.antenatal')
    </div>
    
    <div class='row' style="position: relative;margin: 0 12px 12px 12px">
        @include('hisdb.clientprogressnote.clientprogressnote')
    </div>
    
    <div class='row' style="position: relative;margin: 0 12px 12px 12px">
        @include('hisdb.clientprogressnote.clientprogressnoteref')
    </div>
    
    <div class='row' style="position: relative;margin: 0 12px 12px 12px">
        @include('hisdb.doctornote.doctornote')
    </div>
    
    <div class='row' style="position: relative;margin: 0 12px 12px 12px">
        @include('hisdb.requestfor.requestfor')
    </div>
    
    <div class='row' style="position: relative;margin: 0 12px 12px 12px">
        @include('hisdb.dieteticCareNotes.dieteticCareNotes')
    </div>
    
    <div class='row' style="position: relative;margin: 0 12px 12px 12px">
        @include('hisdb.dietorder.dietorder')
    </div>
@elseif (Auth::user()->nurse == 1)
    <div class='row' style="position: relative;margin: 0 12px 12px 12px" id="nursingED_row">
        @include('hisdb.nursingED.nursingED')
    </div>
    
    <div class='row' style="position: relative;margin: 0 12px 12px 12px">
        @include('hisdb.nursing.nursing',['page_screen' => "patmast"])
    </div>
    
    <div class='row' style="position: relative;margin: 0 12px 12px 12px">
        @include('hisdb.nursingActionPlan.nursingActionPlan')
    </div>
    
    <div class='row' style="position: relative;margin: 0 12px 12px 12px">
        @include('hisdb.nursingnote.nursingnote')
    </div>
    
    <div class='row' style="position: relative;margin: 0 12px 12px 12px">
        @include('hisdb.clientprogressnote.clientprogressnote')
    </div>
    
    <div class='row' style="position: relative;margin: 0 12px 12px 12px">
        @include('hisdb.clientprogressnote.clientprogressnoteref')
    </div>
    
    <div class='row' style="position: relative;margin: 0 12px 12px 12px">
        @include('hisdb.doctornote.doctornote')
    </div>
    
    <div class='row' style="position: relative;margin: 0 12px 12px 12px">
        @include('hisdb.requestfor.requestfor')
    </div>
    
    <div class='row' style="position: relative;margin: 0 12px 12px 12px">
        @include('hisdb.dieteticCareNotes.dieteticCareNotes')
    </div>
    
    <div class='row' style="position: relative;margin: 0 12px 12px 12px">
        @include('hisdb.dietorder.dietorder')
    </div>
@endif

@if (Auth::user()->billing == 1)
    <div class='row' style="position: relative;margin: 0 12px 12px 12px">
        @include('hisdb.ordcom.ordcom',['phase' => '1'])
    </div>
@endif

<div class='row' style="position: relative;margin: 0 12px 12px 12px">
    @include('hisdb.discharge.discharge',['type' => "IP",'type_desc' => "In Patient"])
</div>