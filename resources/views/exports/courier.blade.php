<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<body>
<table border="1" width="100%" >
    <tr>
        <td style="color: red; vertical-align: center" colspan="2"><b>INV NO: {{$courier->unique_name}}</b></td>
    </tr>
    <tr ><td colspan="8" style="vertical-align: center;text-align: center;font-weight: bold;" >INVOICE</td></tr>
    <tr>
        <td colspan="4">
            <b>SHIPPER:</b>
        </td>
        <td colspan="4">
            <b>CONSIGNEE:</b>
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <b>{{$courier->s_name}}</b>
        </td>
        <td colspan="4">
            <b>{{$courier->r_name}}</b>
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <b>{{$courier->s_address1}}</b>
        </td>
        <td colspan="4">
            <b>{{$courier->r_address1}}</b>
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <b>{{$courier->s_state}}, {{$courier->s_city}}</b>
        </td>
        <td colspan="4">
            <b>{{$courier->r_state}}, {{$courier->r_city}}, {{$courier->r_zip_code}}</b>
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <b>{{$courier->sender_country->name}}</b>
        </td>
        <td colspan="4">
            <b>{{$courier->receiver_country->name}}</b>
        </td>
    </tr>

    <tr>
        <td colspan="4"><b>TEL: {{$courier->s_phone}}</b></td>
        <td colspan="4"><b>TEL: {{$courier->r_phone}}</b></td>
    </tr>
    <tr>
        <td colspan="8">&nbsp;</td>
    </tr>
    <tr>
        <td>DATE OF SHIPMENT :</td>
        <td>&nbsp;</td>
        <td>NA</td>
        <td colspan="5"></td>
    </tr>
    <tr>
        <td colspan="8">&nbsp;</td>
    </tr>
    <tr>
        <td>AIR WAYBILL NO :</td>
        <td>&nbsp;</td>
        <td>NA</td>
        <td colspan="5"></td>
    </tr>

    <tr>
        <td colspan="8">&nbsp;</td>
    </tr>

    <tr>
        <td>COUNTRY OF ORIGIN :</td>
        <td>&nbsp;</td>
        <td>{{$courier->sender_country->name}}</td>
        <td colspan="5"></td>
    </tr>

    <tr>
        <td colspan="8">&nbsp;</td>
    </tr>

    <tr>
        <td>FINAL DESTINATION :</td>
        <td>&nbsp;</td>
        <td>{{$courier->receiver_country->name}}</td>
        <td colspan="5"></td>
    </tr>

    <tr>
        <td colspan="8">&nbsp;</td>
    </tr>

    <tr>
        <td>NO. OF BOX : </td>
        <td>&nbsp;</td>
        <td>{{$courier->no_of_boxes}} BOXES</td>
        <td colspan="5"></td>
    </tr>

    <tr>
        <td colspan="8">&nbsp;</td>
    </tr>

    <tr>
        <td>TOTAL WEIGHT : </td>
        <td>&nbsp;</td>
        <td>{{$courier->shippment->weight}} KGS</td>
        <td colspan="5"></td>
    </tr>

    <tr>
        <td colspan="8">&nbsp;</td>
    </tr>

    <tr>
        <td>BOX NO</td>
        <td colspan="2">DESCRIPTION</td>
        <td colspan="2">DETAILS</td>
        <td>QTY</td>
        <td>VALUE</td>
        <td>TOTAL</td>
    </tr>

    <tr>
        <td colspan="8">&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    "UNSOLICITED GIFT - FROM INDIVIDUAL TO INDIVIDUAL"
</table>
</body>
</html>