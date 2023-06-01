Dear {{ strtoupper($requestor->requestor) }},


Good day!

Please be informed that your request {{ $requestor->transid }} has been denied and cancelled by {{ $cancelledby->designation }}.

Thank you!



Regards,
{{-- {{ $setting->company_name }} --}}

Work Flow System