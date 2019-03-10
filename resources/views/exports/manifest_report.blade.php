<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<body>

<table class="table table-no-more table-bordered table-striped mb-none">

    <tr>
        <th class="">Payment Date</th>
        <th class="">Vendor Name</th>
        <th>Total Amount(Dr.)</th>
        <th class="">Total Paid Amount(Cr.)</th>

    </tr>

    <tbody>
    @foreach($manifests['manifest_payment_data'] as $mp)
    <tr>
        <td >

            @if (isset($mp['expense_date'])){{$mp['expense_date']}} @endif
            @if (isset($mp['payment_date'])){{$mp['payment_date']}} @endif

        </td>
        <td>{{$mp['vendor']['name']}} </td>
        <td>
            @if (isset($mp['unique_name'])){{$mp['amount']}} @endif
            </td>
        <td>
            @if (isset($mp['expense_of'])){{$mp['amount']}} @endif
            </td>

    </tr>

    @endforeach

    <tr >
        <td></td>
        <td></td>

        <td>
           Total (Dr.) {{$manifests['total_amount']}}
        </td>

        <td>
            Total (Cr.) {{$manifests['total_paid_amount']}}

        </td>
    </tr>

    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td>Total Remaining : {{$manifests['total_remaining']}}</td>
    </tr>
    </tbody>
</table>

</body>
</html>