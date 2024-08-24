@extends('layouts.authdtl_mobile')

@section('title', 'User Profile')

@section('css')
<style>\
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
</style>
@endsection

@section('content')
<div id="main_container" class="ui container">
    <div class="ui raised segments" id="main_segment">
      <div class="ui secondary segment" id="main_title">
        <h3>User Profile</h3>
      </div>
      <div class="ui attached segment" id="main_segment" style="overflow:auto;">
        <form class="ui form" id="formdata">
          <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
          <div class="field info">
            <label>Username</label>
            <input type="text" name="username" id="username" value="{{$user->username}}" readonly>
          </div>
          <div class="two fields">
              <div class="field info">
                <label>Department</label>
                <input type="text" name="dept" id="dept" value="{{$user->dept}}" readonly>
              </div>
              <div class="field info">
                <label>Group</label>
                <input type="text" name="groupid" id="groupid" value="{{$user->groupid}}" readonly>
              </div>
          </div>
          <div class="field">
            <label>Full Name</label>
            <input type="text" name="name" id="name" value="{{$user->name}}">
          </div>
          <div class="field">
            <label>Designation</label>
            <input type="text" name="designation" id="designation" value="{{$user->designation}}">
          </div>
          <div class="field" style="position: relative;">
            <label>Password</label>
            <input type="password" name="password" id="password" value="{{$user->password}}">
            <i class="eye icon" id="show_pw" style="position: absolute;
                right: 10px;
                top: 37px;
                cursor: pointer;"></i>
            <i class="eye slash icon" id="hide_pw" style="position: absolute;
                right: 10px;
                top: 37px;
                cursor: pointer;
                display: none;"></i>
          </div>
        </form>
      </div>
        <div class="ui two bottom attached buttons">
            <div class="ui negative button" id="cancel">Cancel</div>
            <div class="ui positive button" id="save">Save</div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function(){
        $('#show_pw').click(function(){
            $('#password').attr('type','text');
            $('#show_pw').hide();
            $('#hide_pw').show();
        });

        $('#hide_pw').click(function(){
            $('#password').attr('type','password');
            $('#hide_pw').hide();
            $('#show_pw').show();
        });

        $("form#formdata").form({
            inline: true,
            fields: {
              name : ['empty'],
              password : ['empty']
            }
        });

        $('div#save').click(function(){
            $('form#formdata').form('validate form');

            if(!$('form#formdata').form('is valid')){
                return false;
            }

            var obj={};
            obj.idno_array = [$('#idno').val()];
            obj.oper = 'save_profile';
            obj._token = $('#_token').val();
            obj.name = $('#name').val();
            obj.password = $('#password').val();
            obj.designation = $('#designation').val();
            
            $.post( './user_maintenance/form', obj , function( data ) {

            },'json').fail(function(data) {

            }).done(function(data){
                $.toast({
                  message: 'Profile Updated',
                  class : 'inverted green',   //cycle through all colors
                  showProgress: 'bottom'
                });
            });
        });

        $('div#cancel').click(function(){

        });
    });
</script>
@endsection