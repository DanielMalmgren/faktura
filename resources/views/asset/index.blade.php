@extends('layouts.app')

@section('content')

<script type="text/javascript">

    function updateSpec() {
        $("#spec").html("Läser in...");

        $.ajax({
            url: '/asset/listajax',
            data: {
                kund: $('#kund').val()
            },
            dataType: 'html',
            error: function(result) {
                $("#spec").html("Någonting gick fel!");
            },
            success: function(result) {
                $("#spec").html(result);
            }
        });
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

                    <div class="form-row" style="position:relative">
                        <div class="col" style="max-width:95% !important">
                            <label for="kund">Kund</label>
                            <select class="custom-select" id="kund" name="kund" required="" onchange="updateSpec()">
                                @if(!$valdkund)
                                    <option selected disabled>Välj kund</option>
                                @endif
                                @isset($kommuner)
                                    @foreach($kommuner as $kommunid => $kommun)
                                        <option value="{{$kommunid}}">{{$kommun}}</option>
                                    @endforeach
                                @endisset
                                @foreach($kunder as $kund)
                                    <option {{$valdkund==$kund->debiteurennummer?'selected':''}} value="{{$kund->unid}}">{{$kund->naam}}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onClick="updateSpec()" style="height:36px;position:absolute;bottom:0;right:0">
                            <img src="/images/Refresh.png">
                        </button>
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
