@extends('layouts.main')

@section('title')
Chat | Chat
@endsection

@section('css')
@endsection

@section('style')
  .greentop{
    background: #00868b !important;
  }
  .greentop > .header,.greentop > .meta{
    color: white !important;
  }
  .container_sem{
    background-image: url({{ asset('img/mybg.jpg') }}) !important;
    background-position: center !important;
    background-repeat: no-repeat !important;
    background-size: cover !important;
    height:100vh;
  }
  .my-card{
    box-shadow: 0 4px 8px 0 rgb(0 0 0 / 20%), 0 6px 20px 0 rgb(0 0 0 / 19%);
    padding: 0px !important;
  }
  .item:hover{
    background: #8bc34a2e;
  }
@endsection

@section('js')
    <script src="{{ asset('js/chat.js') }}"></script>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
              <div class="card-body">
                <div class="ui centered grid">
                    <div class="fifteen wide mobile twelve wide tablet eight wide computer column my-card">
                        <div class="ui fluid card">
                          <div class="greentop content">
                            <div class="header">List of Doctor</div>
                            <div class="meta">
                              <span>Select from doctor's list below:</span>
                            </div>
                          </div>
                          <div class="content cont-desc">

                            <div class="description">
                              <div class="ui middle aligned divided list">

                                @foreach ($doctors as $doctor)
                                 <div class="item">
                                    <div class="right floated content">
                                      <a href="https://wa.me/{{$doctor->telhp}}"><img class="ui circular image" src="{{asset('img/whtps.png')}}" width="50" style="margin: 4px;"></a>
                                    </div>
                                    <img class="ui circular image" src="{{asset('img/doctor.png')}}" width="50" style="margin: 5px;">
                                    <div class="content">
                                      <div class="header" style="color: rgba(0,0,0,.87);">{{$doctor->username}}</div>
                                      {{$doctor->name}}
                                      
                                    </div>
                                  </div>
                                @endforeach

                              </div>
                            </div>

                          </div>
                        </div>
                    </div>
              </div>
            </div>
        </div>
    </div>
</div>
@endsection