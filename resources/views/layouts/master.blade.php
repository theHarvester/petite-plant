<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <title>PetitePlant</title>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link rel="apple-touch-icon" sizes="57x57" href="/favicons/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/favicons/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/favicons/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/favicons/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/favicons/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/favicons/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/favicons/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/favicons/apple-touch-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon-180x180.png">
    <link rel="icon" type="image/png" href="/favicons/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/favicons/android-chrome-192x192.png" sizes="192x192">
    <link rel="icon" type="image/png" href="/favicons/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/png" href="/favicons/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="/favicons/manifest.json">
    <link rel="mask-icon" href="/favicons/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-TileImage" content="/favicons/mstile-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <meta name="google-site-verification" content="mInCGzp_j4FhiegcNw6SNdEI6m6JHemNg7U16qElpAY" />

    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/normalize/4.1.1/normalize.min.css">
    <link rel="stylesheet" type="text/css" href="/css/app.css">
    <link href='https://fonts.googleapis.com/css?family=Josefin+Sans|Droid+Sans|Amatic+SC|Rye' rel='stylesheet'
          type='text/css'>
    @yield('head')
</head>
<body>
<div id="bg-panel">&nbsp;</div>
<section id="services">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <h1 class="logo">PetitePlant</h1>
            </div>
        </div>
    </div>
    <div class="container" id="main-content">
        <div class="row">
            <div class="col-md-6 text-right nav-main row-pad pull-right">
                {{ link_to('/', 'Home', ['class' => (isset($page) && $page == 'home') ? 'selected' : null]) }}
                {{ link_to('/blog', 'Blog', ['class' => (isset($page) && $page == 'blog') ? 'selected' : null]) }}
                {{ link_to('/workshops', 'Workshops', ['class' => (isset($page) && $page == 'workshops') ? 'selected' : null]) }}
                {{ link_to('/gallery', 'Gallery', ['class' => (isset($page) && $page == 'gallery') ? 'selected' : null]) }}
                {{ link_to('/about', 'About', ['class' => (isset($page) && $page == 'about') ? 'selected' : null]) }}
            </div>
            <div class="col-md-6 pull-left">
                {!! isset($title) ? '<h1>' . $title . '</h1>' : '' !!}
            </div>
            {{--<hr/>--}}
        </div>
        @yield('main-content')
        {{--<section id="footer">--}}
            {{--<div class="row row-pad">--}}
                {{--<div class="col-md-4">--}}
                    {{--<h3>Contact Us</h3>--}}

                    {{--<p>Foo bar</p>--}}
                {{--</div>--}}
                {{--<div class="col-md-4">--}}
                    {{--<h3>Follow Us</h3>--}}

                    {{--<p>Foo bar</p>--}}
                {{--</div>--}}
                {{--<div class="col-md-4">--}}
                    {{--<h3>Keep In Touch</h3>--}}

                    {{--<p>Foo bar</p>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</section>--}}
    </div>
</section>
<section id="footer">
    <div class="container">
        <div class="row row-pad">
            <div class="col-md-4">
                <h3>Contact Us</h3>

                <p>Email: <a href="mailto:hello@petiteplant.com">hello@petiteplant.com</a></p>
            </div>
            <div class="col-md-4">
                <h3>Follow Us</h3>

                <div>
                    <a href="https://www.instagram.com/petiteplant">
                        <img src="/img/social/instagram.png" alt="Petiteplant on Instagram">
                    </a>
                    <a href="https://www.facebook.com/petiteplant">
                        <img src="/img/social/facebook.png" alt="Petiteplant on Facebook">
                    </a>
                    <a href="https://www.twitter.com/petiteplant">
                        <img src="/img/social/twitter.png" alt="Petiteplant on Twitter">
                    </a>
                </div>
            </div>
            <div class="col-md-4">
                <h3>Keep In Touch</h3>
                <div>
                    @include('shared/newsletter')
                </div>
            </div>
        </div>
    </div>
</section>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
        integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
        crossorigin="anonymous"></script>
@yield('js')
</body>
</html>
