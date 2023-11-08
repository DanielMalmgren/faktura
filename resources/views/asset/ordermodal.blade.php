@if($articles)
    @foreach($articles as $article)
        <a class="btn btn-secondary" href="{{env("ORDER_BASEURL")}}/{{$article->shortname}}?kund={{$kund}}&user={{$user}}">{{$article->pretty_shortname}}
        {{$currentarticle==$article->shortname?"(nuvarande)":""}}</a>
        <br><br>
    @endforeach
@else
    Någonting gick fel!
@endif
