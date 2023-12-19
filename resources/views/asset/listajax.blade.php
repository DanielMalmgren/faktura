<div class="modal fade" id="order-replacement" tabindex="-1" role="dialog" aria-labelledby="module-management-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
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


<table id="assettable" class="table table-bordered table-sm">
    <thead>
        <tr>
            <th></th>
            <th>Datornamn</th>
            <th>Sammanfattning</th>
            <th>Beskrivning</th>
            <th>Artikel</th>
            <th>Leasingpris</th>
            <th>Utbytesdatum</th>
            <th>Utbyte</th>
        </tr>
    </thead>
    <tbody>
        @foreach($assets as $asset)
            <tr>
                <td class="{{$asset->subassets->count()>0?'dt-control':''}}"></td>
                <td>{{$asset->name}}</td>
                <td>{{$asset->summary}}</td>
                <td>{{$asset->beskrivning}}</td>
                <td>{{str_replace('_', ' ', explode("+", $asset->artikelnummer)[0])}}</td>
                <td>{{$asset->leasingpris}}</td>
                <td>{{substr($asset->utbytesdatum, 0, 10)}}</td>
                <td>
                    @if($asset->valt_utbyte && $asset->valt_utbyte != '')
                        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#order-status" data-orderid="{{$asset->ordernummer_utbyte}}" data-assetname="{{$asset->name}}">
                            {{str_replace('_', ' ', $asset->valt_utbyte)}}
                        </button>
                    @elseif(isset($asset->leasingmanader))
                        @if(((new DateTime())->add(new DateInterval('P2W'))) > (new DateTime($asset->utbytesdatum)))
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#order-replacement" data-assetname="{{$asset->name}}" data-user="{{$asset->person()?$asset->person()->tasloginnaam:""}}" data-article="{{$asset->artikelnummer}}">
                                Välj
                            </button>
                        @elseif(((new DateTime())->add(new DateInterval('P3M'))) > (new DateTime($asset->utbytesdatum)))
                            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#order-replacement" data-assetname="{{$asset->name}}" data-user="{{$asset->person()?$asset->person()->tasloginnaam:""}}" data-article="{{$asset->artikelnummer}}">
                                Välj
                            </button>
                        @endif
                    @endif
                </td>
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
            'copy', 'excel', 'colvis', 'pageLength'
        ],
        columnDefs: [
            {
                targets: [ 0 ],
                orderable: false
            },
            {
                targets: [ 1 ],
                data: 'name'
            },
        ],
        lengthMenu: [
            [10, 25, 50, 100,  -1],
            [10, 25, 50, 100, 'Alla']
        ],
    });

    function format(rowData) {
        var div = $('<div>Läser in...</div>');
        //var div = $('<td>1</td><td>2</td><td>3</td><td>4</td><td>5</td><td>6</td>');
    
        $.ajax({
            url: '/asset/subassets',
            data: {
                name: rowData.name
            },
            dataType: 'html',
            success: function(result) {
                //div.parent().parent().after(result);
                div.html(result);
            }
        });

        //console.log($('<tr>').append(div));
        //console.log($('<tr>').append(div)[0]);
        //console.log(div);
    
        //return $('<tr>').append(div)[0];

        return div;
    }

    table.on('click', 'td.dt-control', function (e) {
        let tr = e.target.closest('tr');
        let row = table.row(tr);
    
        if (row.child.isShown()) {
            row.child.hide();
        } else {
            row.child(format(row.data())).show();
        }
    });
</script>
