@foreach($subassets as $subasset)
    <tr>
        <td></td>
        <td>{{$subasset->name}}</td>
        <td>{{$subasset->summary}}</td>
        <td>{{$subasset->beskrivning}}</td>
        <td>{{str_replace('_', ' ', explode("+", $subasset->artikelnummer)[0])}}</td>
        <td>{{$subasset->leasingpris}}</td>
        <td>{{substr($subasset->utbytesdatum, 0, 10)}}</td>
        <td></td>
    </tr>
@endforeach
