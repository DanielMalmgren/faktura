@if($articles)
    @foreach($articles as $article)
        <a class="btn btn-secondary" target="_blank" href="{{$article->description}}?kund={{$kund}}&deviceUser={{$user}}&replacingAsset={{$oldasset}}&tillval_bildskarmsTyp={{iconv("utf-8","ascii//TRANSLIT",$tillval_bildskarmsTyp)}}&tillval_bildskarmsAntal={{$tillval_bildskarmsAntal}}&tillval_4Gmodem={{$tillval_4Gmodem}}">{{$article->pretty_shortname}}
        {{mb_strcasecmp($currentarticle, $article->shortname)==0?"(nuvarande)":""}}</a>
        <br><br>
    @endforeach
@else
    Någonting gick fel!
@endif
