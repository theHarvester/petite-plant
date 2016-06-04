<!DOCTYPE html>
<html>
<head>
    <title>PetitePlant</title>

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

    <link rel="stylesheet" type="text/css" href="/css/app.css">
    <link href='https://fonts.googleapis.com/css?family=Josefin+Sans|Droid+Sans' rel='stylesheet' type='text/css'>
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
        <div class="row row-pad">
            <div class="col-lg-12 text-right nav-main">
                {{ link_to('/', 'Home') }}
                {{ link_to('/workshops', 'Workshops') }}
                {{ link_to('/about', 'About') }}
                {{ link_to('/products', 'Products') }}
            </div>
            <hr />
        </div>
        @yield('main-content')
    </div>
</section>
<section id="footer">
    <div class="container">
        <div class="row row-pad">
            &nbsp;
        </div>
    </div>
</section>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
@yield('js')
</body>
</html>
