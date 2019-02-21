<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<body>

<table class="table table-no-more table-bordered table-striped mb-none">

    <tr>
        <td colspan="7">Manifest No. {{$data['manifest']['unique_name']}}</td>
    </tr>
    <tr>
        <?php $manifest = $data['manifest']; ?>
        <td colspan="7">Vendor {{$manifest->vendor->name}}</td>
    </tr>
    <tr>
        <td>Id</td>
        <td>Sender Name</td>
        <td>Recipient Name</td>
        <td>Source</td>
        <td>Destination</td>
        <td>Weight</td>
        <td>No. of Boxes</td>
        <td>Amount</td>
    </tr>

    @foreach($data['manifest_couriers'] as $item_courier)

        <tr>
            <td>{{$item_courier->unique_name}}</td>
            <td class="text-semibold text-dark">

                {{$item_courier->s_name}} ({{$item_courier->s_company}})
            </td>
            <td class="text-semibold text-dark">
                {{$item_courier->r_name}} ({{$item_courier->r_company}})</td>
            <td class="text-capitalize">
                {{$item_courier->s_state}}, {{$item_courier->sender_country->name}}
            </td>
            <td class="text-center text-capitalize">

                {{$item_courier->r_state}}, {{$item_courier->receiver_country->name}}

            </td>
            <td class="text-center">@if(isset($item_courier->shippment)){{$item_courier->shippment->weight}}@endif</td>

            <td class="text-center">{{$item_courier->no_of_boxes}}</td>
            <td></td>
        </tr>
    @endforeach
</table>

</body>
</html>