@extends('layouts.main')

@section('title', 'test')

@section('body')

<table class="table" style="background: white;margin: 15px;
    border: 1px solid black;">
    <thead>
        <tr>
            <th>Source</th>
            <th>Tran Type</th>
            <th>Audit No</th>
            <th>pdramt</th>
            <th>pcramt</th>
            <th>difference</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($gltran_auditno as $row)
            <tr>
                <td>{{ $row->source }}</td>
                <td>{{ $row->trantype }}</td>
                <td>{{ $row->auditno }}</td>
                <td class="text-end">{{ number_format((float)$row->pdramt, 2) }}</td>
                <td class="text-end">{{ number_format((float)$row->pcramt, 2) }}</td>
                <td class="text-end">{{ number_format((float)$row->amtdif, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection


@section('scripts')

	
@endsection