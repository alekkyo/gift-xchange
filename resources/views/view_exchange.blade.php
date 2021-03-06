<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>GiftXchange</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 20px 0;
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
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }

        .m-b-sm {
            margin-bottom: 10px;
        }

        table {
            text-align: left;
            margin: 0 auto;
        }

        table td {
            padding: 5px;
        }

        table td:last-child {
            text-align: center;
        }
    </style>
</head>
<body>
<div class="">
    <div class="content">
        <div class="m-t-md m-b-md">
            Viewing new gift exchange "{{ $code }}"
        </div>
        <table>
            @foreach ($users as $user)
                <tr>
                    <td>Pick link for <b>{{ $user->name }}</b>:</td>
                    <td>
                        <a href="{{ config('app.url') }}/exchange/{{ $code }}/link/{{ $user->link }}">
                            {{ config('app.url') }}/exchange/{{ $code }}/link/{{ $user->link }}
                        </a>
                    </td>
                    <td>
                      <a href="{{ config('app.url') }}/exchange/{{ $code }}/wishlist/{{ $user->id }}">
                            {{ $user->name }}'s WishList
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
</body>
</html>
