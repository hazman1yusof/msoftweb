@extends('layouts.main')

@section('content')

<div class="ui container content">
    <div class="ui message">
        <div class="header">
        Change Your Password
        </div>
        <ul>
            <li>Type your new password and retype it again, click update to confirm password changes</li>
            <li>If you forgot your password, contact admin to let them reset your password</li>
            <li>Your password are encrypted inside database, admin cant see your password, admin can only reset the value</li>
            <li>Password need to be more than 5 character long</li>
        </ul>
    </div>

    <div class="ui centered grid">
        <div class="column" style="max-width: 550px;">
            <form class="ui form" method="POST" action="/settings/change_password/{{Auth::id()}}" id="form_edit">
                <input type="hidden" name="_method" value="PUT">
                {{ csrf_field() }}
                <div class="ui attached tall stacked teal segment">
                    <div class="field">
                        <label>New Password</label>
                        <input placeholder="Password" type="password" name="password">
                    </div>
                    <div class="field">
                        <label>Re-Type Password</label>
                        <input placeholder="Retype Password" type="password" name="retype_password">
                    </div>
                </div>

                <button class="ui fluid button teal" type="submit" style="margin-top: 10px">Update</button>
                <div class="ui error message"></div>
            </form>
        </div>
        
    </div>
    @if($errors->any())
    <div class="ui centered grid">
        <div class="ui error message">
            <div class="header">{{$errors->first()}}</div>
        </div>
    </div>
    @endif
</div>

@endsection


@section('js')
    <script src="{{ asset('js/change_password.js') }}"></script>
@endsection


