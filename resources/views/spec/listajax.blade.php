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
                <td>{{$asset->leasing}} kr</td>
                <td>{{$asset->license}} kr</td>
                <td>{{$asset->support}} kr</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td></td>
            <td></td>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
</table>

<script type="text/javascript">
    new DataTable('#assettable', {
        language: {
            url: '/DataTables/i18n/sv-SE.json',
        },
        stateSave: true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'excel', 'colvis', 'pageLength'
        ],
        columnDefs: [
            {
                targets: [ 1 ],
                visible: false
            }
        ],
        footerCallback: function (row, data, start, end, display) {
            let api = this.api();
        
            function intVal(i) {
                if(typeof i === 'string') {
                    var siffror = i.match(/\d+/);
                    return parseInt(siffror[0], 10);
                }
                if(typeof i === 'number') {
                    return i;
                }
            };

            total = api.column(2, { search: 'applied' }).data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
            api.column(2).footer().innerHTML = total + ' kr';

            total = api.column(3, { search: 'applied' }).data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
            api.column(3).footer().innerHTML = total + ' kr';

            total = api.column(4, { search: 'applied' }).data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
            api.column(4).footer().innerHTML = total + ' kr';
        }
    });
</script>
