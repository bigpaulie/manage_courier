<!doctype html>
<html class="fixed">
<head>

    <!-- Basic -->
    <meta charset="UTF-8">

    <title>Manage Courier</title>
    <meta name="keywords" content="Manage Courier" />
    <meta name="description" content="Manage Courier">
    <meta name="author" content="okler.net">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <!-- Web Fonts  -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

    <!-- Vendor CSS -->


{!! Html::style("/assets/vendor/bootstrap/css/bootstrap.css") !!}
{!! Html::style("/assets/vendor/font-awesome/css/font-awesome.css") !!}
{!! Html::style("/assets/vendor/magnific-popup/magnific-popup.css") !!}
{!! Html::style("/assets/vendor/bootstrap-datepicker/css/datepicker3.css") !!}


<!-- Theme CSS -->

{!! Html::style("/assets/stylesheets/theme.css") !!}

<!-- Skin CSS -->
{!! Html::style("/assets/stylesheets/skins/default.css") !!}

<!-- Theme Custom CSS -->
{!! Html::style("/assets/stylesheets/theme-custom.css") !!}
    <style>
        button.delete-row {
            background: none;
            border: none;
            float: right;
            color: red;
        }
        .unread{
            font-weight: bold;
            cursor: pointer;
        }
    </style>

@yield('styles')

<!-- Head Libs -->

    {!! Html::script("/assets/vendor/modernizr/modernizr.js") !!}

</head>
<body >
<section class="body">

    <!-- start: header -->
        @include('_includes.admin.header')
    <!-- end: header -->

    <div class="inner-wrapper">
        <!-- start: sidebar -->
             @include('_includes.admin.left_side')
        <!-- end: sidebar -->

        <section role="main" class="content-body" id="app">
            @yield('content')
        </section>
    </div>



    <!-- Vendor -->
{!! Html::script("/assets/vendor/jquery/jquery.js") !!}
{!! Html::script("/assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js") !!}
{!! Html::script("/assets/vendor/bootstrap/js/bootstrap.js") !!}
{!! Html::script("/assets/vendor/nanoscroller/nanoscroller.js") !!}
{!! Html::script("/assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js") !!}
{!! Html::script("/assets/vendor/magnific-popup/magnific-popup.js") !!}
{!! Html::script("/assets/vendor/jquery-placeholder/jquery.placeholder.js") !!}


<!-- Specific Page Vendor -->

    <!-- Theme Base, Components and Settings -->
{!! Html::script("/assets/javascripts/theme.js") !!}

<!-- Theme Custom -->

{!! Html::script("/assets/javascripts/theme.custom.js") !!}

<!-- Theme Initialization Files -->

{!! Html::script("/assets/javascripts/theme.init.js") !!}
    <script src="{{ asset('/js/app.js') }}"></script>
 @yield('scripts')

</section>
</body>
</html>