<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Arrow Assist</title>

        <link href="{{ url('css/app.css') }}" rel="stylesheet">  

        <script>
            const AUTH_ID = '{{ Auth::user()->id }}';
            const AUTH_FIRST = '{{ Auth::user()->first }}';
            const AUTH_LAST = '{{ Auth::user()->last }}';
            const AUTH_NAME = '{{ Auth::user()->first . ' ' . Auth::user()->last }}';
            const AUTH_PERMISSIONS = '{{ Auth::user()->permissions }}';
            const AUTH_EMAIL = '{{ Auth::user()->email }}';
            const AUTH_COMPANY = '{{ Auth::user()->company_name }}';
            const AUTH_GST_NO = '{{ Auth::user()->gst_number }}';
            const AUTH_HOURLY = '{{ Auth::user()->hourly_rate_one }}';
        </script>                    
    </head>
    <body>
        <div id="app">
            <dashboard></dashboard>
        </div>

        <script src="{{ url('js/app.js') }}"></script>    
    </body>
</html>
