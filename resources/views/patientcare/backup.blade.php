@extends('layouts.main')

@section('content')
    <div class="segment" style="margin-bottom: 10px">
        <a type="button" id="add" class="ui icon button" href="backup/create">Create Backup</a>
    </div>
	<table id="example" class="ui celled table" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Backup Files</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($files as $file)
            <tr>
                <td>{{$file->filename}}</td>
                <td>
                    <a type="button" class="ui button" href="backup/download?filepath={{$file->filepath}}" target="_blank">Download</a>
                    <a type="button" class="ui button" href="backup/restore?filepath={{$file->filename}}">Restore</a>
                    <a type="button" class="ui button" href="backup/delete?filepath={{$file->filepath}}" >Delete</a>
                </td>
            </tr>
            @endforeach
        </tbody>
	</table>
@endsection

@section('css')
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/DataTables/datatables.min.css') }}">
@endsection

@section('js')
	<script src="{{ asset('assets/DataTables/datatables.min.js') }}"></script>

	<script src="{{ asset('js/backup.js') }}"></script>
@endsection


