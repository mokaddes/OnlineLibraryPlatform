<!DOCTYPE html>
<html>
<head>
    <title>Redirecting...</title>
</head>
<body>
Redirecting...
<form method="POST" action="https://checkout.flutterwave.com/v3/hosted/pay" id="redirectForm">

    <input type="hidden" name="public_key" value="{{ env('PUBLIC_KEY') }}"/>
    <input type="hidden" name="tx_ref" value="bitethtx-019203"/>
    <input type="hidden" name="amount" value="{{ $package->price_ngn }}"/>
    <input type="hidden" name="currency" value="NGN"/>
    <input type="hidden" name="redirect_url" value="{{ $fullUrl }}" />
    <input type="hidden" name="meta[token]" value="{{ $package->price_ngn }}"/>
    <input type="hidden" name="customer[name]" value="{{ Auth::user()->name }} {{ Auth::user()->last_name }}"/>
    <input type="hidden" name="customer[email]" value="{{ Auth::user()->email }}"/>
    <input type="hidden" name="payment_plan" value="{{ $package->plan_id }}" />

</form>

<script>
    document.getElementById('redirectForm').submit();
</script>
</body>
</html>
