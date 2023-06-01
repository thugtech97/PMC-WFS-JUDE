<!DOCTYPE HTML>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />

</head>
<title>WFS On Hold Notification</title>

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

        <p style="margin-top: 30px;"><strong>Dear {{ strtoupper($requestor->requestor) }},</strong></p>

        <p>Good day!</p>

        <p>Please be informed that your request <strong>{{ $requestor->transid }}</strong> has been put onto on hold by  {{ $onholdby->designation }}.</p>

        <p>Thank you and hoping for your prompt action on this matter!</p>
        <br>

        <p>Regards,</p>
        <p> Work Flow System </p>

    </div>

</div>

</body>

</html>
