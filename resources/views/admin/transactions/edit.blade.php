@extends('layouts.app')

@section('content')
    <h1>Edit Transaksi #{{ $transaction->id }}</h1>

    <form action="{{ route('admin.transactions.update', $transaction->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label for="status">Status</label>
            <select name="status">
                <option value="pending" {{ $transaction->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ $transaction->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="shipped" {{ $transaction->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="completed" {{ $transaction->status == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ $transaction->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>

        <button type="submit">Update Transaksi</button>
    </form>
@endsection
