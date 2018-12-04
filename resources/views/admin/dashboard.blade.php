@extends('layouts.admin')

@section('content')

    <header class="page-header">
        <h2>Dashboard</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="javascript:void(0);">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Dashboard</span></li>
            </ol>

            <a class="sidebar-right-toggle" data-open=""></a>
        </div>
    </header>

    <!-- start: page -->
    <div class="row">

        <div class="col-md-12 col-lg-12 col-xl-4">
            <h5 class="text-semibold text-dark text-uppercase mb-md mt-lg">Couriers</h5>
            <div class="row">
                @foreach($status as $sta)
                <div class="col-md-6 col-xl-12">
                    <section class="panel">
                        <div class="panel-body bg-primary" style="background-color: {{$sta->color_code}};">
                            <div class="widget-summary">
                                <div class="widget-summary-col widget-summary-col-icon">
                                    <div class="summary-icon">
                                        <i class="fa fa fa-envelope"></i>
                                    </div>
                                </div>
                                <div class="widget-summary-col">
                                    <div class="summary">
                                        <h4 class="title">{{$sta->name}}</h4>
                                        <div class="info">
                                            <strong class="amount">
                                                @foreach($courier_status as $cs)
                                                    @if($cs->status_id == $sta->id)
                                                        {{$cs->status_count}}
                                                    @endif
                                                @endforeach
                                            </strong>
                                        </div>
                                    </div>
                                    <div class="summary-footer">
                                        <a class="text-uppercase" href="/admin/couriers">(view all)</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                @endforeach
            </div>
        </div>
    </div>



@endsection

@section('scripts')

    <script>

        jQuery(document).ready(function($) {

        });

    </script>

@endsection
