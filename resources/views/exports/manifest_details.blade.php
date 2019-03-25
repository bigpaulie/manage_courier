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

    @foreach($data['manifest_details'] as $md)

        <tr>
            <td>{{$md['unique_name']}}</td>
            <td class="text-semibold text-dark">

                {{$md['sender_name']}}
            </td>
            <td class="text-semibold text-dark">
                {{$md['recipient_name']}}</td>
            <td class="text-capitalize">
                {{$md['source']}}
            </td>
            <td class="text-center text-capitalize">

                {{$md['destination']}}

            </td>
            <td class="text-center">{{$md['weight']}}</td>

            <td class="text-center">{{$md['no_of_boxes']}}</td>
            <td></td>
        </tr>
    @endforeach
</table>

</body>
</html>