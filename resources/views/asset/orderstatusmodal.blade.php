
Order-id: {{$order->OrderId}}<br>
Orderdatum: {{$order->OrderDate}}<br>
Orderstatus {{$order->OrderState}}<br><br>

@foreach($order->Parameters as $parameter)
    @if($parameter->Value || $parameter->Text)
        {{$parameter->Name.': '.$parameter->Value.($parameter->Text?' ('.$parameter->Text.')':'')}}<br>
    @endif
@endforeach

<br>

<button type="button" class="btn btn-secondary" onClick="cancelorder()">Annulera beställning</button>

<script type="text/javascript">

    function cancelorder() {
        $.ajax({
            type: "DELETE",
            url: '/asset/order',
            data: {orderid: "{{$order->OrderId}}", assetname: "{{$assetname}}", _token: '{{csrf_token()}}'},
            success: function (data) {
                $('#order-status').modal('hide');
                $("#spec").load("/asset/listajax?kund={{$kund}}");
            },
            error: function(data, textStatus, errorThrown) {
                console.log(data);
            },
        });
    }

</script>
