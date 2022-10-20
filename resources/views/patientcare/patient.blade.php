@extends('layouts.main')

@section('content')
    <div id="hiddenload" style="display: none">
    <div class="segment" style="margin-bottom: 10px">
        <button type="button" id="add" class="ui button">Add</button>
        <button type="button" id="edit" class="ui button">Edit</button>
        <button type="button" id="delete" class="ui button">Deactivate</button>
    </div>
	<table id="example" class="ui celled table" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>Patient Name</th>
                <th>Status</th>
                <th>Type</th>
                <th>Email</th>
                <th>Company</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>
            @foreach($patients as $patient)
            <tr>
                <td>{{$patient->id}}</td>
                <td>{{$patient->username}}</td>
                <td>{{$patient->status}}</td>
                <td>{{$patient->type}}</td>
                <td>{{$patient->email}}</td>
                <td>{{$patient->company}}</td>
                <td>{{$patient->note}}</td>
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
    </div>

    <div class="ui modal" id="add_modal">
        <div class="header">
            Create New Patient
        </div>
        <div class="content">
            <form class="ui form" method="POST" action="/patient" id="form">
                <div class="ui error message"></div>
                {{csrf_field()}}

                <div class="two fields">
                    <div class="field">
                        <label>Username</label>
                        <input type="text" name="username" placeholder="Log In Name">
                    </div>

                    <div class="field">
                        <label>Patient Name</label>
                        <input type="text" name="contact " placeholder="Contact Name">
                    </div>
                </div>

                <div class="field">
                    <label>Password</label>
                    <input type="text" name="password" placeholder="Password" data-content="By default password is the same as username, users are expected to change their password after login">
                </div>

                <div class="field">
                    <label>Email</label>
                    <input type="text" name="email" placeholder="Email">
                </div>

                <div class="fields">
                    <div class="twelve wide field">
                        <label>Billing Address</label>
                        <textarea placeholder="Address" rows="4" name="address"></textarea>
                    </div>

                    <div class="four wide field">
                        <label>Postcode</label>
                        <input type="text" name="postcode" placeholder="Postcode">
                    </div>
                </div>

                <div class="two fields">

                    <div class="field">
                        <label>City</label>
                        <input type="text" name="city" placeholder="City">
                    </div>

                    <div class="field">
                        <label>Province</label>
                        <select class="ui fluid dropdown" name="province" id="province">
                            <option value="1" selected="selected">Wp Kuala Lumpur</option>
                            <option value="2">Johor</option>
                            <option value="3">Kedah</option>
                            <option value="4">Kelantan</option>
                            <option value="5">Melaka</option>
                            <option value="6">Negeri Sembilan</option>
                            <option value="7">Pahang</option>
                            <option value="8">Penang</option>
                            <option value="9">Perak</option>
                            <option value="10">Perlis</option>
                            <option value="11">Sabah</option>
                            <option value="12">Sarawak</option>
                            <option value="13">Selangor</option>
                            <option value="14">Terengganu</option>
                            <option value="15">Wp Labuan</option>
                            <option value="16">Wp Putrajaya</option>
                        </select>
                    </div>

                </div>

                <div class="two fields">
                    <div class="field">
                        <label>Mobile Number</label>
                        <div class="ui left labeled input">
                          <div class="ui basic label">+60</div>
                          <input type="text" name="mobile_nm" placeholder="Phone Number">
                        </div>
                    </div>

                    <div class="field">
                        <label>Company</label>
                        <input type="text" name="company" placeholder="Company">
                    </div>
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
            Edit patient
        </div>
        <div class="content">
            <form class="ui form" method="POST" action="/patient/" id="form_edit">
                <div class="ui error message"></div>
                <input type="hidden" name="_method" value="PUT">
                {{csrf_field()}}
                
                <div class="two fields">
                    <div class="field">
                        <label>Patient Name</label>
                        <input type="text" name="username" placeholder="Log In Name">
                    </div>

                    <div class="field">
                        <label>Contact Name</label>
                        <input type="text" name="contact " placeholder="Contact Name">
                    </div>
                </div>

                <div class="field">
                    <label>Password</label>
                    <input type="text" name="password" placeholder="Password" data-content="Password are hash inside the database, old password cant be retrive, it can only be edit to a new one, leave password blank if you dont want to edit old password">
                </div>

                <div class="field">
                    <label>Email</label>
                    <input type="text" name="email" placeholder="Email">
                </div>

                <div class="fields">
                    <div class="twelve wide field">
                        <label>Billing Address</label>
                        <textarea placeholder="Address" rows="4" name="address"></textarea>
                    </div>

                    <div class="four wide field">
                        <label>Postcode</label>
                        <input type="text" name="postcode" placeholder="Postcode">
                    </div>
                </div>

                <div class="two fields">

                    <div class="field">
                        <label>City</label>
                        <input type="text" name="city" placeholder="City">
                    </div>

                    <div class="field">
                        <label>Province</label>
                        <select class="ui fluid dropdown" name="province" id="province">
                            <option value="1" selected="selected">Wp Kuala Lumpur</option>
                            <option value="2">Johor</option>
                            <option value="3">Kedah</option>
                            <option value="4">Kelantan</option>
                            <option value="5">Melaka</option>
                            <option value="6">Negeri Sembilan</option>
                            <option value="7">Pahang</option>
                            <option value="8">Penang</option>
                            <option value="9">Perak</option>
                            <option value="10">Perlis</option>
                            <option value="11">Sabah</option>
                            <option value="12">Sarawak</option>
                            <option value="13">Selangor</option>
                            <option value="14">Terengganu</option>
                            <option value="15">Wp Labuan</option>
                            <option value="16">Wp Putrajaya</option>
                        </select>
                    </div>

                </div>

                <div class="two fields">
                    <div class="field">
                        <label>Mobile Number</label>
                        <div class="ui left labeled input">
                          <div class="ui basic label">+60</div>
                          <input type="text" name="mobile_nm" placeholder="Phone Number">
                        </div>
                    </div>

                    <div class="field">
                        <label>Company</label>
                        <input type="text" name="company" placeholder="Company">
                    </div>
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

    <form method="POST" action="/patient/" id="form_delete" >
        <input type="hidden" name="_method" value="DELETE">
        {{csrf_field()}}
    </form>

@endsection

@section('css')
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/DataTables/datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.13/semantic.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.semanticui.min.css">
@endsection

@section('js')
	<script src="{{ asset('assets/DataTables/datatables.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.semanticui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.13/semantic.min.js"></script>
	<script src="{{ asset('js/patient.js') }}"></script>
@endsection


