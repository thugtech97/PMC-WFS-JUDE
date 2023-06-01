<!DOCTYPE HTML>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />

</head>
<title>WFS Approval Notification</title>

<body>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f0f0f0;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    p {
        margin: 10px 0;
        padding: 0;
        font-weight: normal;
    }

    p {
        font-size: 13px;
    }
</style>

<!-- BODY-->
<div style="max-width: 700px; width: 100%; background: #fff;margin: 30px auto;">

    <div style="padding:30px 60px;">
        <div style="text-align: center;padding: 20px 0;">
            {{-- <img src="{{ Setting::get_company_logo_storage_path() }}" alt="company logo" width="175" /> --}}

            <img src="{{ asset('/assets/logo_default.jpg') }}" alt="company logo" width="175" />
        </div>

        <p style="margin-top: 30px;"><strong>Dear {{ strtoupper($receiver->username) }},</strong></p>

        <p>Good day!</p>

        <p>Please be informed that you have a new task in the workflow that needs your approval. Please see the details below:</p>

        <h4> Transaction Details:</h4>
        
        <p> Transaction ID: {{ $transaction->transid }} </p>
        <p> Transaction Type: {{ str_replace("OREM", "",$transaction->details) }} </p>
        <p> Total Amount: {{ $transaction->totalamount }} </p>
        <p> Requesting Department: {{ $transaction->department }} </p>

        <p>Thank you and hoping for your prompt action on this matter!</p>
        <br>

        <a href="{{ env('APP_URL') }}/transactions"><strong>View Transaction</strong></a>
        <br><br>

        <p>Regards,</p>
        {{-- <p>{{ $setting->company_name }}</p> --}}

        <p> Work Flow System </p>

    </div>

</div>

</body>

</html>
