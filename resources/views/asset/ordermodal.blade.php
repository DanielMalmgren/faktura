@if($replacements)
    @foreach($replacements as $replacement)
        @isset($replacement->lank_till_zervicepoint)
            <a class="btn btn-secondary" target="_blank" href="{{$replacement->lank_till_zervicepoint}}?kund={{$kund}}&deviceUser={{$user}}&replacingAsset={{$oldasset}}&tillval_bildskarmsTyp={{iconv("utf-8","ascii//TRANSLIT",$tillval_bildskarmsTyp)}}&tillval_bildskarmsAntal={{$tillval_bildskarmsAntal}}&tillval_4Gmodem={{$tillval_4Gmodem}}">{{$replacement->pretty_shortname}}
            {{mb_strcasecmp($currentarticle, $replacement->shortname)==0?"(nuvarande)":""}}</a>
            <br><br>
        @endisset
    @endforeach
    <a class="btn btn-secondary" target="_blank" href="{{env('ZP_DONOTREPLACE_URL')}}?kund={{$kund}}&deviceUser={{$user}}&replacingAsset={{$oldasset}}">Ersätt ej</a>
    <br><br>
@else
    Någonting gick fel!
@endif
