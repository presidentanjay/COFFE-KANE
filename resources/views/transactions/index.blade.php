@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Transaksi</h1>

    <table border="1" cellpadding="8" cellspacing="0" style="width: 100%;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Total Harga</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $trans)
                <tr>
                    <td>{{ $trans->id }}</td>
                    <td>{{ $trans->user->name }}</td>
                    <td>Rp {{ number_format($trans->total_price, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($trans->status) }}</td>
                    <td>{{ $trans->created_at->format('d-m-Y H:i') }}</td>
                    <td>
                        <a href="{{ route('transactions.show', $trans) }}">Detail</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 10px;">
        {{ $transactions->links() }}
    </div>
</div>
@endsection
