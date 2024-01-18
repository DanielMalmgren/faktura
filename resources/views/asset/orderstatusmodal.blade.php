
Order-id: {{$order->OrderId}}<br>
Orderdatum: {{substr($order->OrderDate, 0, 10)}}<br>
Orderstatus: {{$order->Status}}<br><br>

@foreach($order->Parameters as $parameter)
    @if(($parameter->Value || $parameter->Text) && !in_array($parameter->Name, $hiddenparameters))
        {{$parameter->Name.': '.($parameter->Text?$parameter->Text:$parameter->Value)}}<br>
        @if($parameter->Name == 'orderSlappt')
            @php
                $orderSlappt = true;
            @endphp
        @endif
    @endif

@endforeach

<br>

@empty($orderSlappt)
    <button type="button" class="btn btn-secondary" onClick="cancelorder()">Annulera beställning</button>
@endempty

<script type="text/javascript">

    function cancelorder() {
        if (confirm("Är du säker på att du vill avbryta ordern?") == true) {
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
    }

</script>
