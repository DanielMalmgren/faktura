<div class="modal fade" id="order-replacement" tabindex="-1" role="dialog" aria-labelledby="module-management-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="module-management-label">Välj utbyte</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Stäng">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <a href="{{env("ORDER_BASEURL")}}/whatever">Beställ en ny liten dator</a><br>
                <a href="{{env("ORDER_BASEURL")}}/whatever">Beställ en stor dator istället</a><br>
                <a href="{{env("ORDER_BASEURL")}}/whatever">Beställ en atlantångare istället</a><br>
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
                <td>{{$asset->artikelnummer}}</td>
                <td>{{$asset->leasingpris}}</td>
                <td>{{substr($asset->utbytesdatum, 0, 10)}}</td>
                <td>
                    @if(((new DateTime())->add(new DateInterval('P3M'))) > (new DateTime($asset->utbytesdatum)))
                        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#order-replacement" data-assetname="{{$asset->name}}">
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
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this)
        modal.find('.modal-title').text('Välj utbyte för ' + assetname)
        //modal.find('.modal-body input').val(recipient)
    })

    new DataTable('#assettable', {
        language: {
            url: '/DataTables/i18n/sv-SE.json',
        },
        stateSave: true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'excel', 'colvis'
        ],
    });
</script>
