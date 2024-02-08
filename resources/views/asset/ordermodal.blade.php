@if($replacements)
    @foreach($replacements as $replacement)
        @isset($replacement->zervicepoint_tjanste_id)
            <div style="border-bottom-color:rgb(222,226,230);border-bottom-style:solid;border-bottom-width:1px">
                <br>
                <div class="bredvid">
                    <img src="data:{{$replacement->imageContentType}};base64,{{$replacement->imageContent}}" height="150">
                    <H3>{{$replacement->DisplayName}}</H3><br>
                </div>
                {{$replacement->ShortDescription}}<br>
                {!!$replacement->Description!!}<br>
                <a class="btn btn-secondary btn-lg" target="_blank" href="{{env("ZP_BASEURL")."/Store/Service/".$replacement->zervicepoint_tjanste_id}}?kund={{$kund}}&deviceUser={{$user}}&replacingAsset={{$oldasset}}&tillval_bildskarmsTyp={{iconv("utf-8","ascii//TRANSLIT",$tillval_bildskarmsTyp)}}&tillval_bildskarmsAntal={{$tillval_bildskarmsAntal}}&tillval_4Gmodem={{$tillval_4Gmodem}}">{{$replacement->pretty_shortname}}</a>
                <br><br>
            </div>
        @endisset
    @endforeach
    <br>
    <div class="bredvid">
        <img src="/images/dontreplace.png">
        <H3>Ersätt ej</H3>
    </div>
    Om {{$oldasset}} inte ska ersättas alls, gör detta val.<br><br>
    <button type="button" class="btn btn-secondary btn-lg" onClick="dontreplace()">Ersätt ej</button>
    <br><br>
@else
    Någonting gick fel!
@endif

<script type="text/javascript">

    function dontreplace() {
        if (confirm("Är du säker på att denna tillgång inte ska ersättas alls?") == true) {

            $('#order-replacement').modal('hide');

            $('#spinnerModal').modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });

            $.ajax({
                type: "POST",
                url: '/asset/dontreplace',
                data: {assetname: "{{$oldasset}}", kund: "{{$kund}}", _token: '{{csrf_token()}}'},
                success: function (data) {
                    setTimeout(function() {
                        $("#spec").load("/asset/listajax?kund={{$kund}}");
                        setTimeout(function() {
                            $('.modal-backdrop').remove(); // Ta bort alla modal-backdrops
                            $('body').removeClass('modal-open'); // Återställ body till normalt 
                            $('#spinnerModal').modal('hide');
                        }, 1000);
                    }, 13000);
                },
                error: function(data, textStatus, errorThrown) {
                    console.log(data);
                },
            });
        }
    }

</script>
