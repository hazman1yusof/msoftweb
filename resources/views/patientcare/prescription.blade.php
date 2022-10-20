@extends('layouts.main')

@section('title')
Dashboard &raquo; Document Prescription | Apps Prescription
@endsection

@section('style')
.ui.table td {
    padding: 12px;
    font-size: 13px;
}
.ui.table tr:hover {
    background: #f0f0f0;
    box-shadow: 1px 2px 5px #949494;
    cursor:pointer;
}
.ui.table th:nth-child(even){
    position: relative;
    color: #d93025 !important;
}
.ui.table th:nth-child(odd){
    position: relative;
    color: #1a73e8 !important;
}
.ui.table tr a{
    color: #2f6677;
}
.ui.table tr:nth-child(4n-3) a{
    color: #8acdb2 !important;
}
.alnright { text-align: right !important}
.bordermerah{
    background-color: #d93025;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    bottom: 0;
    content: '';
    display: block;
    height: 3px;
    left: 0;
    margin: 0 8px;
    position: absolute;
    right: 0;
}
.borderbiru{
    background-color: #1a73e8;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    bottom: 0;
    content: '';
    display: block;
    height: 3px;
    left: 0;
    margin: 0 8px;
    position: absolute;
    right: 0;
}
@endsection

@section('js')
    <script src="{{ asset('js/prescription.js') }}"></script>
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css">
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <h3 class="card-title">
                    Prescription
                </h3>
                <hr />
                @if(session('notification'))
                    <div
                        class="alert alert-{{ session('status') }} alert-dismissible fade show"
                        role="alert"
                    >
                        {{ session('notification') }}
                        <button
                            type="button"
                            class="close"
                            data-dismiss="alert"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <div>
                    <form class="ui form ui grid">
                                       
                    <div class="four wide column">
                    <h4 class="card-title">
                    MRN: {{Auth::user()->mrn}}
                    </h4>   
                    </div>
                     <div class="four wide column">
                    <h4 class="card-title">
                    Name: {{Auth::user()->name}}
                    </h4>   
                    </div>
                    </form>
                </div>
                <hr/>
                
                <div class="table-responsive">
                    <table class="ui basic table">
                        <thead>
                            <tr>
                                <th scope="col" width="20px"></th>
                                <th scope="col">Description<div class="bordermerah"></div></th>
                                <th scope="col">Doctor<div class="borderbiru"></div></th>
                                <th scope="col">Dose<div class="bordermerah"></div></th>
                                <th scope="col">Freq<div class="borderbiru"></div></th>
                                <th scope="col">Instruction<div class="bordermerah"></div></th>
                                <th scope="col">Remark<div class="borderbiru"></div></th>
                                <th scope="col">Quantity<div class="bordermerah"></div></th>
                                <th scope="col" width="9%">Date<div class="borderbiru"></div></th>
                                <th scope="col">Action<div class="bordermerah"></div></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($table_prescription) == 0)
                                <tr>
                                    <td colspan="8" align="center"><b>Tidak ada data ...!</b></td>
                                </tr>
                            @endif
                            @foreach($table_prescription as $item)
                                <tr data-id="{{ @$item->id }}">
                                    <td>
                                        <div class="pretty p-icon p-round p-pulse">
                                            <input type="checkbox" id="cb_{{ @$item->id }}" data-id="{{ @$item->id }}" />
                                            <div class="state p-primary">
                                                <i class="huge check icon" style="display: none;"></i>
                                                <label></label>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ @$item->chg_desc }}</td>
                                    <td>{{ @$item->admdoctor }}</td>
                                    <td>{{ @$item->dos_desc }}</td>
                                    <td>{{ @$item->fre_desc }}</td>
                                    <td>{{ @$item->ins_desc }}</td>
                                    <td>{{ @$item->remarks }}</td>
                                    <td class='alnright'>{{ @$item->quantity }}</td>
                                    <td>@if(!empty($item->trxdate)){{\Carbon\Carbon::parse($item->trxdate)->format('d-m-Y')}}@endif</td>
                                    <td>
                                        <a
                                            href="https://medicsoft.com.my/patientcare/resources/views/print_detail.php?id={{ @$item->id }}"
                                            class="btn btn-sm btn-primary text-white"
                                            target="_blank"
                                        >
                                            <i class="paperclip icon"></i> Attachment
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="10" style="padding: 0; border-bottom: none;  border-top: none">
                                        <div class="ui blue card" style="width: auto;display: none; margin: 20px;"  id="card_{{ @$item->id }}" data-id="{{ @$item->id }}">
                                          <div class="content">
                                            <div class="header">
                                                [ DETAIL PRESCRIPTION ]
                                            </div>
                                            <div class="meta" style="margin: 30px 0 10px 0">
                                              <span>2 days ago</span>
                                            </div>
                                            <p>Epis Type: {{ @$item->fre_code }}</p>
                                            <p>Adm Date/Time: {{ @$item->trxdate }}</p>
                                            <p>Sex/Race/DOB: -</p>
                                            <p>PS. No.: -</p>
                                            <p>War/Bed: -</p>
                                            <p>TRX Date: {{ @$item->trxdate }}</p>
                                            <p>Charge Code: {{ @$item->chg_code }}</p>
                                            <p>Description: {{ @$item->chg_desc }}</p>
                                            <p>Dose Code: {{ @$item->dos_code }}</p>
                                            <p>InstCode: {{ @$item->ins_code }}</p>
                                            <p>DoseDescription: {{ @$item->dos_desc }}</p>
                                            <p>FreqDescription: {{ @$item->fre_desc }}</p>
                                            <p>InstDescription: {{ @$item->ins_desc }}</p>
                                            <p>Duration: {{ @$item->dru_desc }}</p>
                                            <p>Remark: {{ @$item->remarks }}</p>
                                            <p>Duration2: {{ @$item->dru_desc }}</p>
                                            <p>Quantity: {{ @$item->quantity }}</p>
                                            <p>Doctor: {{ @$item->admdoctor }}</p>
                                            <a
                                                href="https://medicsoft.com.my/patientcare/resources/views/print_detail.php?id={{ @$item->id }}"
                                                class="btn btn-sm btn-primary text-white"
                                                target="_blank"
                                            >
                                                <i class="fa fa-bars"></i> [ PRINT PDF ]
                                            </a>
                                          </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @if ($table_prescription->lastPage() > 1)
                    <div class="ui pagination menu">
                        <a href="{{ $table_prescription->previousPageUrl() }}" class="{{ ($table_prescription->currentPage() == 1) ? ' disabled' : '' }} item">
                            Previous
                        </a>
                        @for ($i = 1; $i <= $table_prescription->lastPage(); $i++)
                            <a href="{{ $table_prescription->url($i) }}" class="{{ ($table_prescription->currentPage() == $i) ? ' active' : '' }} item">
                                {{ $i }}
                            </a>
                        @endfor
                        <a href="{{ $table_prescription->nextPageUrl() }}" class="{{ ($table_prescription->currentPage() == $table_prescription->lastPage()) ? ' disabled' : '' }} item">
                            Next
                        </a>
                    </div>
                @endif
                </div>
              </div>
            </div>
        </div>
    </div>
</div>
@endsection