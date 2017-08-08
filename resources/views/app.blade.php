<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Arrow Assist</title>

        <link href="{{ url('css/app.css') }}" rel="stylesheet">       
    </head>
    <body>
        <div id="app">
            <dashboard></dashboard>
        </div>

        <script src="{{ url('js/app.js') }}"></script>
    </body>
</html>
