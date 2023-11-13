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


<table id="assettable" class="table table-bordered table-sm">
    <thead>
        <tr>
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
                <td>{{$asset->name}}</td>
                <td>{{$asset->summary}}</td>
                <td>{{$asset->beskrivning}}</td>
                <td>{{str_replace('_', ' ', explode("+", $asset->artikelnummer)[0])}}</td>
                <td>{{$asset->leasingpris}}</td>
                <td>{{substr($asset->utbytesdatum, 0, 10)}}</td>
                <td>
                    @if($asset->valt_utbyte && $asset->valt_utbyte != '')
                        <a href="{{env("ORDER_BASEURL")}}/Store/Order/Details/{{$asset->ordernummer_utbyte}}">
                            {{str_replace('_', ' ', $asset->valt_utbyte)}}
                        </a>
                    @elseif(((new DateTime())->add(new DateInterval('P2W'))) > (new DateTime($asset->utbytesdatum)))
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#order-replacement" data-assetname="{{$asset->name}}" data-user="{{$asset->person()?$asset->person()->tasloginnaam:""}}" data-article="{{$asset->artikelnummer}}">
                            Beställ
                        </button>
                    @elseif(((new DateTime())->add(new DateInterval('P3M'))) > (new DateTime($asset->utbytesdatum)))
                        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#order-replacement" data-assetname="{{$asset->name}}" data-user="{{$asset->person()?$asset->person()->tasloginnaam:""}}" data-article="{{$asset->artikelnummer}}">
                            Beställ
                        </button>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<script type="text/javascript">

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

    new DataTable('#assettable', {
        language: {
            url: '/DataTables/i18n/sv-SE.json',
        },
        stateSave: true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'excel', 'colvis', 'pageLength'
        ],
    });
</script>
