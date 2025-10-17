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
            <th>Post Date</th>
            <th>Amount</th>
            <th>Out Amount</th>
            <th>OS Amt Alloc</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($array as $row)
            <tr>
                <td>{{ $row->source }}</td>
                <td>{{ $row->trantype }}</td>
                <td>{{ $row->auditno }}</td>
                <td>{{ \Carbon\Carbon::parse($row->postdate)->format('d/m/Y') }}</td>
                <td class="text-end">{{ number_format((float)$row->amount, 2) }}</td>
                <td class="text-end">{{ number_format((float)$row->outamount, 2) }}</td>
                <td class="text-end">{{ number_format((float)$row->osamt_alloc, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection


@section('scripts')

	
@endsection