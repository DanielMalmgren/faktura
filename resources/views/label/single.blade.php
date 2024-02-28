Serienummer: {{$serial}}
<br><br>
{!! QrCode::size(150)->generate($link); !!}
<br><br>
@isset($name)
    Namn: {{ $name }}
@endisset
