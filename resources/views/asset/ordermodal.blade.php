@if($replacements)
    @foreach($replacements as $replacement)
        <a class="btn btn-secondary" target="_blank" href="{{$replacement->lank_till_zervicepoint}}?kund={{$kund}}&deviceUser={{$user}}&replacingAsset={{$oldasset}}&tillval_bildskarmsTyp={{iconv("utf-8","ascii//TRANSLIT",$tillval_bildskarmsTyp)}}&tillval_bildskarmsAntal={{$tillval_bildskarmsAntal}}&tillval_4Gmodem={{$tillval_4Gmodem}}">{{$replacement->pretty_shortname}}
        {{mb_strcasecmp($currentarticle, $replacement->shortname)==0?"(nuvarande)":""}}</a>
        <br><br>
    @endforeach
@else
    Någonting gick fel!
@endif
