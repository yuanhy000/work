<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>Project</title>
    <link rel="stylesheet" href="./css/app.css">
    <style>
        @media (min-width: 0) and (max-width: 768px) {
            html {
                font-size: 13px;
            }

            button {
                padding: 2px 10px;
            }

        }

    </style>
</head>
<body>
<div id="app">
    <app></app>
</div>
<script src="./js/app.js"></script>
</body>
</html>
