<!-- resources/views/payment/redirect.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting to Payment</title>
    <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
</head>
<body>
    <script type="text/javascript">
        window.onload = function() {
            snap.pay("{{ $snapToken }}", {
                onSuccess: function(result) {
                    alert("Pembayaran berhasil");
                },
                onPending: function(result) {
                    alert("Pembayaran masih pending");
                },
                onError: function(result) {
                    alert("Pembayaran gagal");
                }
            });
        };
    </script>
</body>
</html>
