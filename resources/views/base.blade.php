<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title> {{ $title ?? "" }} / To-Do List </title>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.4/css/bulma.min.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    </head>
    <body>

        @isset($title)
            <h1 class="title is-3 has-text-centered m-3">
                {{ $title }}
            </h1>
        @endif

        <div class="content">
                
            @section("content")
                Содержимое не задано
            @show
        </div>
    </body>
</html>
