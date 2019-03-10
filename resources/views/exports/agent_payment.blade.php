<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<body>

<table class="table table-no-more table-bordered table-striped mb-none">

    <tr>
        <th class="">PaymentDate</th>
        <th class="">Agent Name</th>
        <th>Total Amount(Dr.)</th>
        <th class="">Total Paid Amount(Cr.)</th>
    </tr>

    <tbody>

    @foreach($agent_payment['agent_payment_data'] as $ap)
    <tr >

        <td>{{$ap['payment_date']}} </td>
        <td>{{$ap['agent']['name']}}</td>
        <td>@if (isset($ap['total'])){{$ap['total']}} @endif</td>
        <td>@if (isset($ap['amount'])){{$ap['amount']}} @endif</td>

    </tr>
    @endforeach

    <tr >
        <td>

        </td>
        <td>

        </td>

        <td>
            Total (Dr.) {{$agent_payment['total_amount']}}
        </td>

        <td>
           Total (Cr.) {{$agent_payment['total_paid_amount']}}

        </td>


    </tr>


    <tr>
        <td>

        </td>
        <td>

        </td>
        <td>

        </td>



        <td>
            Total Remaining {{$agent_payment['remaining_amount']}}

        </td>


    </tr>




    </tbody>
</table>
</body>
</html>