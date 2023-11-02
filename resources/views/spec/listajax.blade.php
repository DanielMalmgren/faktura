<table id="assettable" class="table table-bordered table-sm">
    <thead>
        <tr>
            <th>Datornamn</th>
            <th>Serienummer</th>
            <th>Leasing</th>
            <th>Licens</th>
            <th>Support</th>
        </tr>
    </thead>
    <tbody>
        @foreach($assets as $asset)
            <tr>
                <td>{{$asset->asset_name}}</td>
                <td>{{$asset->asset_serial}}</td>
                <td>{{$asset->leasing}}</td>
                <td>{{$asset->license}}</td>
                <td>{{$asset->support}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
