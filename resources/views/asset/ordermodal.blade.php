@if($replacements)
    @foreach($replacements as $replacement)
        @isset($replacement->lank_till_zervicepoint)
            <a class="btn btn-secondary" target="_blank" href="{{$replacement->lank_till_zervicepoint}}?kund={{$kund}}&deviceUser={{$user}}&replacingAsset={{$oldasset}}&tillval_bildskarmsTyp={{iconv("utf-8","ascii//TRANSLIT",$tillval_bildskarmsTyp)}}&tillval_bildskarmsAntal={{$tillval_bildskarmsAntal}}&tillval_4Gmodem={{$tillval_4Gmodem}}">{{$replacement->pretty_shortname}}
            {{mb_strcasecmp($currentarticle, $replacement->shortname)==0?"(nuvarande)":""}}</a>
            <br><br>
        @endisset
    @endforeach
    <button type="button" class="btn btn-secondary" onClick="dontreplace()">Ersätt ej</button>
    <br><br>
@else
    Någonting gick fel!
@endif

<script type="text/javascript">

    function dontreplace() {
        if (confirm("Är du säker på att denna tillgång inte ska ersättas alls?") == true) {
            $.ajax({
                type: "POST",
                url: '/asset/dontreplace',
                data: {assetname: "{{$oldasset}}", _token: '{{csrf_token()}}'},
                success: function (data) {
                    $('#order-replacement').modal('hide');
                    $("#spec").load("/asset/listajax?kund={{$kund}}");
                },
                error: function(data, textStatus, errorThrown) {
                    console.log(data);
                },
            });
        }
    }

</script>
