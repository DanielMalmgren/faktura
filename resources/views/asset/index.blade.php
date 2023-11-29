@extends('layouts.app')

@section('content')

<script type="text/javascript">

    function updateSpec() {
        var kund = $('#kund').val();
        $("#spec").load("/asset/listajax?kund=" + kund);
    }
</script>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    Visa tillgångar
                </div>

                <div class="card-body">

                    <div class="form-row">
                        <div class="col">
                            <label for="kund">Kund</label>
                            <select class="custom-select w-100" id="kund" name="kund" required="" onchange="updateSpec()">
                                @if(!$valdkund)
                                    <option selected disabled>Välj kund</option>
                                @endif
                                @foreach($kunder as $kund)
                                    <option {{$valdkund==$kund->debiteurennummer?'selected':''}} value="{{$kund->unid}}">{{$kund->naam}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <br>

                    <div id="spec"></div>

                    @if($valdkund)
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
