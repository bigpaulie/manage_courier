<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<body>

<table class="table table-no-more table-bordered table-striped mb-none">

    <tr>
        <th class="">Payment Date</th>
        <th class="">Courier Id</th>
        <th class="">Customer Name</th>
        <th class="">Phone</th>
        <th class="">Address</th>
        <th class="">City</th>
        <th>Total Amount(Dr.)</th>
        <th class="">Total Paid Amount(Cr.)</th>
        <th class="">Discount</th>
    </tr>

    <tbody>
    @foreach($walking_customer['walking_payment_data'] as $wp)
    <tr>
        <td>{{$wp['payment_date']}} </td>
        <td>{{$wp['courier']['unique_name']}}</td>
        <td>{{$wp['courier']['s_name']}}</td>
        <td>{{$wp['courier']['s_phone']}}</td>
        <td>{{$wp['courier']['s_address1']}}</td>
        <td>{{$wp['courier']['s_city']}}</td>
        <td>@if (isset($wp['total'])){{$wp['total']}} @endif</td>
        <td>
            @if (isset($wp['amount'])){{$wp['amount']}} @endif
            @if (isset($wp['pay_amount'])){{$wp['pay_amount']}} @endif

        </td>
        <td>
            @if (isset($wp['discount'])){{$wp['discount']}} @endif
            </td>



    </tr>

    @endforeach

    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>

        <td>
            <label><strong class="text-primary">Total (Dr.) {{$walking_customer['total_amount']}}</strong></label>
        </td>

        <td>
            <label><strong class="text-primary">Total (Cr.) {{$walking_customer['total_paid_amount']}}</strong></label>

        </td>

        <td>
            <label><strong class="text-primary">Total Discount {{$walking_customer['total_discount']}}</strong></label>

        </td>


    </tr>

    <tr >
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>

        <td>
           Total Remaining : {{$walking_customer['total_remaining']}}

        </td>


    </tr>





    </tbody>
</table>

</body>
</html>