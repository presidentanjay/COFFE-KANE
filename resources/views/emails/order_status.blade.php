<h1>Status Pesanan Anda</h1>

<p>Halo {{ $transaction->user->name }},</p>

<p>Pesanan dengan ID #{{ $transaction->id }} saat ini berstatus: <strong>{{ ucfirst($transaction->status) }}</strong>.</p>

<p>Terima kasih telah berbelanja di Coffee Kane!</p>
