<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto-Submit Form</title>
</head>
<body onload="document.getElementById('autoSubmitForm').submit();">

<form id="autoSubmitForm" action="{{$url['payment_url'] }}" method="GET">
    @csrf
    <input type="hidden" name="pidx" value="{{$url['pidx']}}">
    <noscript>
        <button type="submit">Click here if not redirected</button>
    </noscript>
</form>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $("#autoSubmitForm").submit();
    });
</script>
</body>
</html>
