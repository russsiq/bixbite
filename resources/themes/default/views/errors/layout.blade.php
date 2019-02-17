<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title')</title>
    <style>
        *, :after, :before {
            box-sizing: border-box;
        }

        html, body {
            font-family: Helvetica, Arial, sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        article, aside, footer, header, hgroup, main, nav, section {
            display: block;
        }

        .wrapper {
            padding: 24px;
            float: left;
            position: relative;
            min-height: 512px;
            height: 100%;
            width: 100%;
            background: #001123;
            background-image: linear-gradient(45deg, #7d0029, #008cba);
            text-align: center;
        }

        .nav {
            margin-bottom: 24px;
            font-size: 16px;
            line-height: 1.6;
            color: white;
            text-align: center;
            width: 100%;
        }

        .nav__link {
            color: #fafafa;
            padding: 2px 4px;
            margin: 0 8px;
            text-decoration: none;
            display: inline-block;
        }

        .content {
            padding: 48px;
            font-weight: 400;
            font-size: 18px;
            line-height: 1.6;
            left: 50%;
            top: 50%;
            position: absolute;
            -webkit-transform: translateX(-50%) translateY(-50%);
            transform: translateX(-50%) translateY(-50%);
        }

        .header {
            padding: 0;
            margin: 0 0 24px;
            line-height: 1;
            color: #fafafa;
            letter-spacing: -1px;
            font-size: 48px;
            font-weight: 600;
        }

        .paragraph {
            color: white;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <nav class="nav">
            <a href="{{ url('/') }}" class="nav__link">@lang('common.home')</a>
            <a href="{{ url('search') }}" class="nav__link">@lang('common.search')</a>
        </nav>

        <main class="content">
            <h1 class="header">@yield('title')</h1>
            <p class="paragraph">@yield('message')</p>
        </main>
    </div>
</body>

</html>
