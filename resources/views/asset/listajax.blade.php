<div class="modal fade" id="order-replacement" tabindex="-1" role="dialog" aria-labelledby="module-management-label" aria-hidden="true">
    <div class="modal-dialog" style="max-width:800px" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="module-management-label">Välj utbyte</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Stäng">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="ordermodalcontent" class="modal-body">
                Hämtar lista med möjliga utbyten...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">@lang('Stäng')</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="order-status" tabindex="-1" role="dialog" aria-labelledby="module-management-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="module-management-label">Info om beställning</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Stäng">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="orderstatusmodalcontent" class="modal-body">
                Hämtar beställningsinformation...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">@lang('Stäng')</button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="spinnerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                Lägger order, vänligen vänta<br><br>
                <div class="spinner-border" role="status">
                    <span class="sr-only">Laddar...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<table id="assettable" class="table table-bordered table-sm">
    <thead>
        <tr>
            <th></th>
            <th>Datornamn</th>
            <th>Sammanfattning</th>
            <th>Beskrivning</th>
            <th>Användare</th>
            <th>Anteckningar</th>
            <th>Kundnr</th>
            <th>Artikel</th>
            <th>Fullst. artikel</th>
            <th>Senast inloggad</th>
            <th>Senast scannad</th>
            <th>Utbytesdatum</th>
            @if($order_access)
                <th>Utbyte</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach($assets as $asset)
            <tr>
                <td class="{{$asset->subassets?'dt-control':''}}"></td>
                <td>{{$asset->namn}}</td>
                <td>{{$asset->sammanfattning}}</td>
                <td>{{$asset->beskrivning}}</td>
                <td>{{$asset->personnamn}}</td>
                <td>{{$asset->anteckningar}}</td>
                <td>{{$asset->kundnummer}}</td>
                <td>{{str_replace('_', ' ', explode("+", $asset->artikelnummer)[0])}}</td>
                <td>{{$asset->artikelnummer}}</td>
                <td>{{$asset->senast_inloggad}}</td>
                <td>{{$asset->senast_scannad}}</td>
                <td>{{$asset->utbytesdatum}}</td>
                @if($order_access)
                    <td>
                        @if($asset->kan_bytas && $asset->valt_utbyte != '')
                            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#order-status" data-orderid="{{$asset->ordernummer_utbyte}}" data-assetname="{{$asset->namn}}">
                                {{str_replace('_', ' ', $asset->valt_utbyte)}}
                            </button>
                        @elseif($asset->kan_bytas)
                            @if(((new DateTime())->add(new DateInterval('P2W'))) > (new DateTime($asset->utbytesdatum)))
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#order-replacement" data-assetname="{{$asset->namn}}" data-user="{{$asset->personanvandarnamn}}" data-article="{{$asset->artikelnummer}}">
                                    Välj
                                </button>
                            @elseif(((new DateTime())->add(new DateInterval('P3M'))) > (new DateTime($asset->utbytesdatum)))
                                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#order-replacement" data-assetname="{{$asset->namn}}" data-user="{{$asset->personanvandarnamn}}" data-article="{{$asset->artikelnummer}}">
                                    Välj
                                </button>
                            @endif
                        @endif
                    </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>

<script type="text/javascript">

    $('#order-status').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var orderid = button.data('orderid') // Extract info from data-* attributes
        var assetname = button.data('assetname') // Extract info from data-* attributes
        $("#orderstatusmodalcontent").load("/asset/orderstatusmodal?kund={{$kund}}&orderid=" + orderid + "&assetname=" + assetname);
    })

    $('#order-replacement').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var assetname = button.data('assetname') // Extract info from data-* attributes
        var artikelnummer = encodeURIComponent(button.data('article')) // Extract info from data-* attributes
        var user = button.data('user') // Extract info from data-* attributes

        var modal = $(this)
        modal.find('.modal-title').text('Välj utbyte för ' + assetname)
        $("#ordermodalcontent").text("Hämtar lista med möjliga utbyten...")
        $("#ordermodalcontent").load("/asset/ordermodal?kund={{$kund}}&artikelnummer=" + artikelnummer + "&user=" + user + "&oldasset=" + assetname);
    })

    var table = new DataTable('#assettable', {
        language: {
            url: '/DataTables/i18n/sv-SE.json',
        },
        stateSave: true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'excel', 'colvis', 'pageLength',
            {
                extend: 'colvis',
                columns: ':not(.noVis)'
            }
        ],
        columnDefs: [
            {
                targets: [ 0 ],
                orderable: false,
                className: 'noVis'
            },
            {
                targets: [ 1 ],
                data: 'name'
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
                targets: [ 6 ],
                visible: false
            },
            {
                targets: [ 7 ],
                visible: false
            },
            {
                targets: [ 8 ],
                visible: false
            },
            {
                targets: [ 9 ],
                visible: false
            },
        ],
        lengthMenu: [
            [10, 25, 50, 100,  -1],
            [10, 25, 50, 100, 'Alla']
        ],
    });

    function format(row) {
        var div = $('<div>Läser in...</div>');

        $.ajax({
            url: '/asset/subassets',
            data: {
                name: row.data().name
            },
            dataType: 'json',
            success: function(result) {
                var rows = [];
                result.forEach(parseresult);
                function parseresult(item) {

                    var tds = '';
                    if(table.column(0).visible()) { tds += '<td style="border: 0px"></td>'};
                    if(table.column(1).visible()) { tds += '<td>'+item.name+'</td>'};
                    if(table.column(2).visible()) { tds += '<td>'+item.summary+'</td>'};
                    if(table.column(3).visible()) { tds += '<td>'+item.beskrivning+'</td>'};
                    if(table.column(4).visible()) { tds += '<td></td>'};
                    if(table.column(5).visible()) { tds += '<td>'+(item.anteckningar==null?"":item.anteckningar)+'</td>'};
                    if(table.column(6).visible()) { tds += '<td></td>'};
                    if(table.column(7).visible()) { tds += '<td>'+item.artikelnummer+'</td>'};
                    if(table.column(8).visible()) { tds += '<td>'+item.artikelnummer+'</td>'};
                    if(table.column(9).visible()) { tds += '<td></td>'};
                    if(table.column(10).visible()) { tds += '<td></td>'};
                    if(table.column(11).visible()) { tds += '<td>'+(item.utbytesdatum==null?"":item.utbytesdatum.substring(0, 10))+'</td>'};

                    var subrow = $('<tr>').append(tds)[0]
                    rows.push(subrow);
                }
                row.child(rows);
            }
        });
    
        return div;
    }

    table.on('click', 'td.dt-control', function (e) {
        let tr = e.target.closest('tr');
        let row = table.row(tr);
    
        if (row.child.isShown()) {
            row.child.hide();
        } else {
            row.child(format(row)).show();
        }
    });

    table.on( 'column-visibility.dt', function ( e, settings, column, state ) {
        //console.log('Column '+ column +' has changed to '+ (state ? 'visible' : 'hidden'));
        {{-- TODO: Trigger redraw of child rows here --}}
    });
</script>
