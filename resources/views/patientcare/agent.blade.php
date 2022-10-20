@extends('layouts.main')

@section('content')
    <div class="segment" style="margin-bottom: 10px">
        <button type="button" id="add" class="ui button">Add</button>
        <button type="button" id="edit" class="ui button">Edit</button>
        <button type="button" id="delete" class="ui button">Deactivate</button>
        <button type="button" id="detail" class="ui button" data-agent="1" style="margin-top: 10px" >Detail</button>
    </div>
	<table id="example" class="ui celled table" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>id</th>
                <th>Agent Name</th>
                <th>Status</th>
                <th>Type</th>
                <th>Email</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>
            @foreach($agents as $agent)
            <tr>
                <td>{{$agent->id}}</td>
                <td>{{$agent->username}}</td>
                <td>{{$agent->status}}</td>
                <td>{{$agent->type}}</td>
                <td>{{$agent->email}}</td>
                <td>{{$agent->note}}</td>
            </tr>
            @endforeach
        </tbody>
	</table>

    @if($errors->any())
    <div class="segment">
        <div class="ui error message">
            <ul>
            @foreach($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="ui modal" id="add_modal">
        <div class="header">
            Create New Agent
        </div>
        <div class="content">
            <form class="ui form" method="POST" action="/agent" id="form">
                <div class="ui error message"></div>
                {{csrf_field()}}
                <div class="field">
                    <label>Username</label>
                    <input type="text" name="username" placeholder="Username">
                </div>
                <div class="field">
                    <label>Password</label>
                    <input type="text" name="password" placeholder="Password" data-content="By default password is the same as username, users are expected to change their password after login">
                </div>
                <div class="field">
                    <label>Email</label>
                    <input type="text" name="email" placeholder="Email">
                </div>
                <div class="field">
                    <label>Note</label>
                    <textarea name="note" placeholder="Note"></textarea>
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui cancel button">Cancel</div>
            <button class="ui button teal" form="form" >Save</button>
        </div>
    </div>

    <div class="ui modal" id="edit_modal">
        <div class="header">
            Edit Agent
        </div>
        <div class="content">
            <form class="ui form" method="POST" action="/agent/" id="form_edit">
                <div class="ui error message"></div>
                <input type="hidden" name="_method" value="PUT">
                {{csrf_field()}}
                <div class="field">
                    <label>Username</label>
                    <input type="text" name="username" placeholder="Username" disabled>
                </div>
                <div class="field">
                    <label>Password</label>
                    <input type="text" name="password" placeholder="Password" data-content="Password are hash inside the database, old password cant be retrive, it can only be edit to a new one, leave password blank if you dont want to edit old password">
                </div>
                <div class="field">
                    <label>Email</label>
                    <input type="text" name="email" placeholder="Email">
                </div>
                <div class="field">
                    <label>Note</label>
                    <textarea name="note" placeholder="Note"></textarea>
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui cancel button">Cancel</div>
            <button class="ui button teal" form="form_edit" >Save</button>
        </div>
    </div>

    <form method="POST" action="/agent/" id="form_delete" >
        <input type="hidden" name="_method" value="DELETE">
        {{csrf_field()}}
    </form>

@endsection

@section('css')
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/DataTables/datatables.min.css') }}">
@endsection

@section('js')
	<script src="{{ asset('assets/DataTables/datatables.min.js') }}"></script>

	<script src="{{ asset('js/agent.js') }}"></script>
@endsection


