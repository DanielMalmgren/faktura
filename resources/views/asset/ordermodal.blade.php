@if($articles)
    @foreach($articles as $article)
        <a class="btn btn-secondary" href="{{env("ORDER_BASEURL")}}/{{$article->shortname}}?kund={{$kund}}&user={{$user}}&oldasset={{$oldasset}}&tillval={{$tillval}}&antaltillval={{$antaltillval}}">{{$article->pretty_shortname}}
        {{mb_strcasecmp($currentarticle, $article->shortname)==0?"(nuvarande)":""}}</a>
        <br><br>
    @endforeach
@else
    Någonting gick fel!
@endif
