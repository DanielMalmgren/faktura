<table class="table table-bordered table-sm">
    <thead>
        <tr>
            <th scope="col">Namn</th>
            <th scope="col">Specialkost</th>
            <th class="text-center" scope="col">1</th>
            <th class="text-center" scope="col">2</th>
            <th class="text-center" scope="col">3</th>
            <th class="text-center" scope="col">4</th>
            <th class="text-center" scope="col">5</th>
            <th class="text-center" scope="col">6</th>
            <th class="text-center" scope="col">7</th>
            <th class="text-center" scope="col">8</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
            <tr>
                <td>{{$order->Kund_namn}}</td>
                <td>{{$order->Specialkost}}</td>
                <td class="text-center">{{$order->Alt1}}</td>
                <td class="text-center">{{$order->Alt2}}</td>
                <td class="text-center">{{$order->Alt3}}</td>
                <td class="text-center">{{$order->Alt4}}</td>
                <td class="text-center">{{$order->Alt5}}</td>
                <td class="text-center">{{$order->Alt6}}</td>
                <td class="text-center">{{$order->Alt7}}</td>
                <td class="text-center">{{$order->Alt8}}</td>
            </tr>
            @endforeach
    </tbody>
    <thead>
        <tr>
            <td> </td>
            <td> </td>
                @for($i=1; $i <= 8; $i++)
                    <th class="text-center" style="min-width:25px">{{$ordered_amount[$i]}}</th>
                @endfor
        </tr>
    </thead>

</table>

<a href="/homecareorder/listpdf?week={{$week}}&listgrupp={{$group->listgrupp}}" class="btn btn-primary">Skriv ut</a>
