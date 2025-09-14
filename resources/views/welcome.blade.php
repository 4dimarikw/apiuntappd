<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>intro</title>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
            <style>
                html, body {
                    height: 100%;
                    width: 100%;
                    margin: 0;
                    padding: 0;
                    overflow: hidden;
                }
                body {
                    height: 100vh;
                    width: 100vw;
                }
                img {
                    display: block;
                    width: 100vw;
                    height: 100vh;
                    object-fit: cover;      /* Ключевая строка! */
                    object-position: center center;
                    margin: 0;
                    padding: 0;
                    border: none;
                    max-width: 100vw;
                    max-height: 100vh;
                    background: #000;      /* цвет фона (если нужно) */
                }
            </style>


    </head>
    <body>
        <img src="intro.webp" alt="Полноэкранное изображение" class="fullscreen-image">
    </body>
</html>
