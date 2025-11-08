@extends('layouts.main')

@section('title', 'test')

@section('body')

<table class="table" style="background: white;margin: 15px;
    border: 1px solid black;">
    <thead>
        <tr>
            <th>auditno</th>
            <th>total dr</th>
            <th>total cr</th>
            <th>balance</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($array as $row)
            <tr>
                <td>{{ $row->auditno }}</td>
                <td>{{ $row->drtotal }}</td>
                <td>{{ $row->crtotal }}</td>
                <td>{{ $row->altotal }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection


@section('scripts')

	
@endsection