@extends('layouts.app')

@section('content')

<script type="text/javascript">

    function label() {
        serial = $('#serial').val();
        window.location.href = '/label/' + serial;
    }
</script>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">

            <div class="card">
                <div class="card-header">
                    Skapa etikett
                </div>

                <div class="card-body">

                        <label for="serial">Serienummer</label>
                        <div class="mb-3">
                            <input id="serial" name="serial" required minlength="5" maxlength="50" class="form-control" value="{{old('serial')}}"  onchange="label()">
                        </div>

                        <button type="button" class="btn btn-sm btn-outline-secondary" onClick="label()">
                            <img src="/images/Refresh.png">
                        </button>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
