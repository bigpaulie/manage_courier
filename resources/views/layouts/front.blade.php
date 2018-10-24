<!doctype html>
<html class="fixed">
<head>

    <!-- Basic -->
    <meta charset="UTF-8">

    <meta name="keywords" content="HTML5 Admin Template" />
    <meta name="description" content="Porto Admin - Responsive HTML5 Template">
    <meta name="author" content="okler.net">

    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <!-- Web Fonts  -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">


    <link href="{{ asset('/public/assets/vendor/bootstrap/css/bootstrap.css') }}" rel="stylesheet">

    <link href="{{ asset('/public/assets/vendor/font-awesome/css/font-awesome.css') }}" rel="stylesheet">

{{--{!! Html::style("/assets/vendor/bootstrap/css/bootstrap.css") !!}--}}
    {{--{!! Html::style("/assets/vendor/font-awesome/css/font-awesome.css") !!}--}}
    {!! Html::style("/public/assets/vendor/magnific-popup/magnific-popup.css") !!}
    {!! Html::style("/public/assets/vendor/bootstrap-datepicker/css/datepicker3.css") !!}


    <!-- Theme CSS -->

    {!! Html::style("/public/assets/stylesheets/theme.css") !!}

    <!-- Skin CSS -->
    {!! Html::style("/public/assets/stylesheets/skins/default.css") !!}

    <!-- Theme Custom CSS -->
    {!! Html::style("/public/assets/stylesheets/theme-custom.css") !!}

@yield('styles')


<!-- Head Libs -->
    {!! Html::script("/public/assets/vendor/modernizr/modernizr.js") !!}

</head>
<body>
<!-- start: page -->
@yield('content')
<!-- end: page -->

<!-- Vendor -->

{!! Html::script("/public/assets/vendor/jquery/jquery.js") !!}
{!! Html::script("/public/assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js") !!}
{!! Html::script("/public/assets/vendor/bootstrap/js/bootstrap.js") !!}
{!! Html::script("/public/assets/vendor/nanoscroller/nanoscroller.js") !!}
{!! Html::script("/public/assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js") !!}
{!! Html::script("/public/assets/vendor/magnific-popup/magnific-popup.js") !!}
{!! Html::script("/public/assets/vendor/jquery-placeholder/jquery.placeholder.js") !!}


<!-- Specific Page Vendor -->

<!-- Theme Base, Components and Settings -->
{!! Html::script("/public/assets/javascripts/theme.js") !!}

<!-- Theme Custom -->

{!! Html::script("/public/assets/javascripts/theme.custom.js") !!}

<!-- Theme Initialization Files -->

{!! Html::script("/public/assets/javascripts/theme.init.js") !!}
@yield('scripts')

</body>
</html>