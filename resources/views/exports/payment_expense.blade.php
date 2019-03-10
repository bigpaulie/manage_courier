<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<body>

<table class="table table-no-more table-bordered table-striped mb-none">

    <tr>

        <th class="">Date</th>
        <th class="">Payment/Expense Type</th>
        <th>Payment(Cr.)</th>
        <th class="">Expense(Dr.)</th>
        <th>Total</th>
    </tr>

    <tbody>

    @foreach($payment_expense['payments_expense_data'] as $ex)
    <tr>

        <td>
            @if (isset($ex['payment_date'])){{$ex['payment_date']}} @endif
            @if (isset($ex['expense_date'])){{$ex['expense_date']}} @endif
           </td>
        <td>
            @if (isset($ex['payment_by'])){{ucfirst($ex['payment_by'])}} @endif
            @if (isset($ex['courier_id'])){{"Courier"}} @endif
            @if (isset($ex['expense_type_id'])){{$ex['expense_type']['name']}} @endif


        </td>

        <td>


            @if (isset($ex['payment_date']) && isset($ex['amount'])){{$ex['amount']}} @endif
            @if (isset($ex['courier_id'])){{$ex['pay_amount']}} @endif

        </td>

        <td>
            @if (isset($ex['expense_type_id'])){{$ex['amount']}} @endif
        </td>
        <td></td>
    </tr>
    @endforeach

    <tr>
        <td></td>
        <td></td>

        <td>
            <label><strong class="text-primary">Total Payment: {{$payment_expense['total_payment']}}</strong></label>
        </td>

        <td>
            <label><strong class="text-primary">Total Expense: {{$payment_expense['total_expense']}}</strong></label>

        </td>

        <td>
            <label><strong class="text-primary">Total: {{$payment_expense['total']}}</strong></label>

        </td>


    </tr>



    </tbody>
</table>
</body>
</html>