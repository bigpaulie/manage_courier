<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<body>
<table border="1" width="100%" >
    <tr>
        <td style="color: red; vertical-align: center" colspan="2">
            <b>INV NO: {{$courier->unique_name}}</b>
        </td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
        <td colspan="5"><b>INVOICE</b></td>
    </tr>
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
        <td colspan="5" style="text-align: center">"UNSOLICITED GIFT - FROM INDIVIDUAL TO INDIVIDUAL"</td>

        <td>US$</td>
        <td>US$</td>
    </tr>

    <tr>
        <td>&nbsp;</td>
        <td colspan="3">&nbsp;</td>
        <td><b>KGS</b></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>

    <?php $courier_boxes = $courier->courier_boxes; ?>
     @foreach($courier_boxes as $key=> $cb)
      <tr>
          <td style="text-align: center"><b>Box - {{$key+1}}</b></td>
          <td>&nbsp;</td>
          <td colspan="6">&nbsp;</td>
      </tr>
    <tr>
        <td><b>{{$cb->breadth}}*{{$cb->width}}*{{$cb->height}}</b></td>
        <td colspan="7">&nbsp;</td>
    </tr>
       <?php $courier_box_items = $cb->courier_box_items; ?>

        @foreach($courier_box_items as $key=> $cbi)

            <tr>
                <td></td>
                <td><b>{{$cbi->content_type->name}}</b></td>
                <td colspan="3">&nbsp;</td>
                <td>{{$cbi->qty}}</td>
                <td></td>
                <td></td>
            </tr>
        @endforeach
        <tr>
            <td colspan="8">&nbsp;</td>
        </tr>

     @endforeach

    <tr>
        <td colspan="8">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="8">THE VALUE IS DECLARED FOR THE CUSTOM PURPOSE ONLY.</td>
    </tr>
    <tr>
        <td colspan="8">&nbsp;</td>
    </tr>

    <tr>
        <td><b>FOR,</b></td>
        <td colspan="4">&nbsp;</td>
        <td><b>TOTAL US$</b></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td colspan="8">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="8">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="8"><b>AUTHORISED SIGNATORY</b></td>
    </tr>


</table>
</body>
</html>