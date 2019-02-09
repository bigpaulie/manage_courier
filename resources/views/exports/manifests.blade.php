<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<body>

    <table class="table table-no-more table-bordered table-striped mb-none">

        <tr>
            <td>Id</td>
            @if(Auth::user()->user_type == 'admin')
                <th>Created By</th>
            @endif
            <td>Vendor Name</td>
            <td>Amount</td>
            <td>Manifest Contents</td>
            <td>Created</td>



        </tr>

        <tbody>
        @foreach($manifests as $manifest)
            <tr>
                <td>{{$manifest->unique_name}}</td>
                @if(Auth::user()->user_type == 'admin')
                    <td>{{$manifest->store->name}} ({{$manifest->store->profile->company_name}})</td>
                @endif
                <td>{{$manifest->vendor->name}}</td>
                <td>{{$manifest->amount}}</td>
                <td>
                    <ul>
                        <li>
                            Items-<strong>{{$manifest->manifest_items->where('item_type','item')->count()}}</strong>
                        </li>
                        <li>
                            Bluk-<strong>{{$manifest->manifest_items->where('item_type','bulk')->count()}}</strong>
                        </li>
                    </ul>

                </td>
                <td data-title="Created">{{date('d-M-Y',strtotime($manifest->created_at))}}</td>

            </tr>

        @endforeach

        </tbody>
    </table>

</body>
</html>