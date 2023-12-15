<table id="assettable" class="table table-bordered table-sm">
    <thead>
        <tr>
            <th>Datornamn</th>
            <th>Serienummer</th>
            <th>Beskrivning</th>
            <th>Typ</th>
            <th>Leasingtjänst</th>
            <th>Licenstjänst</th>
            <th>Supporttjänst</th>
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
                <td>{{$asset->asset_description}}</td>
                <td>{{$asset->asset_template}}</td>
                <td>{{str_replace("_", " ", $asset->leasingtjanst)}}</td>
                <td>{{str_replace("_", " ", $asset->licenstjanst)}}</td>
                <td>{{str_replace("_", " ", $asset->supporttjanst)}}</td>
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
            <td></td>
            <td></td>
            <td></td>
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
        lengthMenu: [
            [10, 25, 50, 100,  -1],
            [10, 25, 50, 100, 'Alla']
        ],
        columnDefs: [
            {
                targets: [ 1 ],
                visible: false
            },
            {
                targets: [ 2 ],
                visible: false
            },
            {
                targets: [ 3 ],
                visible: false
            },
            {
                targets: [ 4 ],
                visible: false
            },
            {
                targets: [ 5 ],
                visible: false
            },
            {
                targets: [ 6 ],
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

            function calcFooter(c) {
                total = api.column(c, { search: 'applied' }).data()
                        .reduce((a, b) => intVal(a) + intVal(b), 0);
                api.column(c).footer().innerHTML = total + ' kr';
            }

            calcFooter(7);
            calcFooter(8);
            calcFooter(9);
        }
    });
</script>
