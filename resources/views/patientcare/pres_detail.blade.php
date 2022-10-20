@extends('layouts.main')

@section('title')
Dashboard &raquo; Document Prescription | Apps Prescription
@endsection

@section('style')
.ui.table td {
    padding: 22px;
}
.ui.table tr:hover {
    background: #f0f0f0;
    box-shadow: 1px 2px 5px #949494;
    cursor:pointer;
}
@endsection

@section('js')
    <script src="{{ asset('js/prescription.js') }}"></script>
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
                    <div>
                        <form class="ui form ui grid">
                                           
                        <div class="four wide column">
                        <h4 class="card-title">
                        MRN: 8663
                        </h4>   
                        </div>
                         <div class="four wide column">
                        <h4 class="card-title">
                        NAME: NOR LIZAH BINTI YACOB
                        </h4>   
                        </div>
                        </form>
                    </div>
                    <hr/>

                    
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection