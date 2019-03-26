<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<body>

<table class="table table-no-more table-bordered table-striped mb-none">

    <tr>
        <th class="">Payment Date</th>
        <th class="">Transaction Details</th>
        <th>Total Amount(Dr.)</th>
        <th class="">Total Paid Amount(Cr.)</th>

    </tr>

    <tbody>
    @foreach($company_payments['company_payment_data'] as $cp)
        <tr>
            <td >

                @if (isset($cp['expense_date'])){{$cp['expense_date']}} @endif
                @if (isset($cp['payment_date'])){{$cp['payment_date']}} @endif

            </td>
            <td>@if (isset($cp['payment_date'])) Bulk {{$cp['manifest']['unique_name']}} @endif
                @if (isset($cp['expense_date'])){{$cp['receiver_name']}} (By {{ $cp['payment_by'] }}) @endif </td>
            <td>
                @if (isset($cp['manifest_item_id'])){{$cp['amount']}} @endif
            </td>
            <td>
                @if (isset($cp['expense_of'])){{$cp['amount']}} @endif
            </td>

        </tr>

    @endforeach

    <tr >
        <td></td>
        <td></td>

        <td>
            Total (Dr.) {{$company_payments['total_amount']}}
        </td>

        <td>
            Total (Cr.) {{$company_payments['total_paid_amount']}}

        </td>
    </tr>

    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td>Total Remaining : {{$company_payments['total_remaining']}}</td>
    </tr>
    </tbody>
</table>

</body>
</html>