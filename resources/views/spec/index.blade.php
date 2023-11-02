@extends('layouts.app')

@section('content')

<script type="text/javascript">

    function updateSpec() {
        var kund = $('#kund').val();
        var period = $('#period').val();
        if(period != null) {
            $("#spec").load("/spec/listajax?kund=" + kund + "&period=" + period);
        }
    }
</script>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    Fakturaspec
                </div>

                <div class="card-body">

                    <div class="form-row">
                        <div class="col">
                            <label for="kund">Kund</label>
                            <select class="custom-select d-block w-100" id="kund" name="kund" required="" onchange="updateSpec()">
                                @if(!$valdkund)
                                    <option selected disabled>Välj kund</option>
                                @endif
                                @foreach($kunder as $kund)
                                    <option {{$valdkund==$kund?'selected':''}} value="{{$kund}}">{{$kund}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col" style="max-width:150px">
                            <label for="period">Period</label>
                            <select class="custom-select d-block w-200" id="period" name="period" onchange="updateSpec()">
                                @foreach($perioder as $period)
                                    <option {{$valdperiod==$period?'selected':''}} value="{{$period}}">{{$period}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <br>

                    <div id="spec"></div>

                    @if($valdkund && $valdperiod)
                        <script type="text/javascript">
                            updateSpec();
                        </script>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
