<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                /*color: #636b6f;*/
                color: #000000;
                /*font-family: 'Raleway', sans-serif;*/
                font-family: 'Verdana', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            table {

            }

            td, th {
                padding: 10px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @if (Auth::check())
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ url('/login') }}">Login</a>
                        <a href="{{ url('/register') }}">Register</a>
                    @endif
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    {{ config('app.name') }}
                </div>
                <div>
                    <table>
                        <thead>
                            <tr><th>&nbsp;</th><th>a</th><th>b</th><th>c</th><th>d</th><th>e</th><th>f</th><th>g</th><th>h</th><th>i</th><th>j</th></tr>
                        </thead>
                        @php
                            $coordinateStatuses = $grid->getCoordinateStatuses();

                            for ($row=1; $row<=10; $row++) {
                                print "<tr>";

                                for ($col=0; $col<=10; $col++) {
                                    if ($col === 0) {
                                        print "<th>" . $row . "</th>";
                                        continue;
                                    }

                                    $key = $col . ':' . $row;
                                    $coordinateStatus = $coordinateStatuses[$key];
                                    print "<td>" . $coordinateStatus . "</td>";
                                }

                                print "</tr>";
                            }
                        @endphp
                    </table>
                </div>
                <div>
                    <form action="/command" method="post">
                        {{ csrf_field() }}
                        <label>Command&nbsp;<input name="command" /></label>
                        <input type="submit" />
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
