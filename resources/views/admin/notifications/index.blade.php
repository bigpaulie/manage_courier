@extends('layouts.admin')

@section('content')

    <header class="page-header">
        <h2>Manage Notifications</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="javascript:void(0);">
                        <i class="fa fa-home"></i>
                    </a>
                </li>

                <li><span>Notifications</span></li>
            </ol>

            <a class="sidebar-right-toggle" data-open="sidebar-right"></a>
        </div>
    </header>

    @if (Session::has('message'))
        <div class="alert alert-success">
            <strong> {{ Session::get('message') }}</strong>
        </div>
    @endif


    <section class="panel">
        <header class="panel-heading">

            <h2 class="panel-title">Manage Notifications</h2>
        </header>
        <div class="panel-body">
            <table class="table table-no-more table-bordered table-striped mb-none">
                <thead>
                <tr>
                    <th>Agent Name</th>
                    <th class="hidden-xs hidden-sm">Notification Type</th>
                    <th class="text-right">Message</th>
                    <th class="text-right hidden-xs hidden-sm">Created</th>
                </tr>
                </thead>
                <tbody>
                @foreach($notifications as $key=> $notification)

                    <tr id="row_noti_{{$notification->id}}" class="@if($notification->status == 'unread') unread @endif" @if($notification->status == 'unread') @click="changeStatus('{{$notification->id}}','read')" @endif>
                        <td data-title="Agent Name">{{$notification->user->name}}</td>
                        <td data-title="Notification Type" class="hidden-xs hidden-sm">{{$notification->notification_type}}</td>
                        <td data-title="Message" class="text-right">{{$notification->message}}</td>
                        <td data-title="Created" class="text-right hidden-xs hidden-sm">{{date('d-M-Y',strtotime($notification->created_at))}}</td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="pull-right">{{ $notifications->links() }}</div>
    </section>
    <!-- end: page -->


@endsection

@section('scripts')

    <script>

        jQuery(document).ready(function($) {

        });

        const oapp = new Vue({
            el:'#app',
            data:{

            },
            created(){
                //console.log(this.countries);
            },


            methods: {
                changeStatus(notification_id,status){


                    axios.post('/api/update_notification_status', {
                        notification_id: notification_id,
                        status: status
                    })
                        .then(function (response) {
                            $('#row_noti_'+notification_id).removeClass('unread');
                        })
                        .catch(function (error) {
                            //currentObj.output = error;
                        });
                }

            },

            computed: {

            }

        });


    </script>

@endsection
