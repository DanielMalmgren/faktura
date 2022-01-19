<form method="post" name="question" action="{{action('OrderAOController@store')}}" accept-charset="UTF-8">
    @csrf

    <input type="hidden" id="week" name="week" value="{{$week}}">
    <input type="hidden" name="department_id" value="{{$department->id}}">

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        @foreach($weekdays as $weekdaynumber => $weekday)
            <li class="nav-item" role="presentation">
                <a class="nav-link {{$weekdaynumber==1?'active':''}}" id="{{$weekdaynumber}}-tab" data-toggle="tab" href="#tab{{$weekdaynumber}}" role="tab" aria-controls="{{$weekdaynumber}}">{{$weekday}}</a>
            </li>
        @endforeach
    </ul>
    <div class="tab-content" id="myTabContent">
        @foreach($weekdays as $weekdaynumber => $weekday)
            <div class="tab-pane fade {{$weekdaynumber==1?'show active':''}}" id="tab{{$weekdaynumber}}" role="tabpanel" aria-labelledby="{{$weekdaynumber}}-tab">
            
                <br>
            
            {{$dates[$weekdaynumber]->format('Y-m-d')}}

            <br><br>
            
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card">
                <div class="card-header">Lunch 1: {{$chosen_courses['Lunch1'][$weekdaynumber]}}</div>

                <div class="card-body">
                    <div class="form-row">
                        <div class="col-8">
                            <label>Portioner totalt</label>
                        </div>
                        <div class="col-3">
                            <input type="number" min="0" max="{{$department->Boende}}" name="whatever" class="form-control" value="{{$department->Boende}}">
                        </div>
                    </div>

                    @if($department->special_diet_needs->isNotEmpty())
                        <label>Varav specialkost</label>

                        @foreach($department->special_diet_needs as $special_diet_need)

                            <div class="form-row">
                                <div class="col-8">
                                    <label>{{$special_diet_need->special_diet->Namn}}</label>
                                </div>
                                <div class="col-3">
                                    <input type="number" min="0" max="{{$special_diet_need->Antal}}" name="whatever" class="form-control" value="{{$special_diet_need->Antal}}">
                                </div>
                            </div>

                        @endforeach
                    @endif

                </div>
            </div>
        </div>

        <div class="col-md-6">

            <div class="card">
                <div class="card-header">Lunch 2: {{$chosen_courses['Lunch2'][$weekdaynumber]}}</div>

                <div class="card-body">
                    <div class="form-row">
                        <div class="col-8">
                            <label>Portioner totalt</label>
                        </div>
                        <div class="col-3">
                            <input type="number" min="0" max="{{$department->Boende}}" name="whatever" class="form-control" value="{{$department->Boende}}">
                        </div>
                    </div>

                    @if($department->special_diet_needs->isNotEmpty())
                        <label>Varav specialkost</label>

                        @foreach($department->special_diet_needs as $special_diet_need)

                            <div class="form-row">
                                <div class="col-8">
                                    <label>{{$special_diet_need->special_diet->Namn}}</label>
                                </div>
                                <div class="col-3">
                                    <input type="number" min="0" max="{{$special_diet_need->Antal}}" name="whatever" class="form-control" value="{{$special_diet_need->Antal}}">
                                </div>
                            </div>

                        @endforeach
                    @endif

                </div>
            </div>
        </div>

    </div>

    <br>

    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card">
                <div class="card-header">Middag: {{$chosen_courses['Middag'][$weekdaynumber]}}</div>

                <div class="card-body">
                    <div class="form-row">
                        <div class="col-8">
                            <label>Portioner totalt</label>
                        </div>
                        <div class="col-3">
                            <input type="number" min="0" max="{{$department->Boende}}" name="whatever" class="form-control" value="{{$department->Boende}}">
                        </div>
                    </div>

                    @if($department->special_diet_needs->isNotEmpty())
                        <label>Varav specialkost</label>

                        @foreach($department->special_diet_needs as $special_diet_need)

                            <div class="form-row">
                                <div class="col-8">
                                    <label>{{$special_diet_need->special_diet->Namn}}</label>
                                </div>
                                <div class="col-3">
                                    <input type="number" min="0" max="{{$special_diet_need->Antal}}" name="whatever" class="form-control" value="{{$special_diet_need->Antal}}">
                                </div>
                            </div>

                        @endforeach
                    @endif

                </div>
            </div>
        </div>

        <div class="col-md-6">
            @if($weekdaynumber==4 || $weekdaynumber==6 || $weekdaynumber==7)

                <div class="card">
                    <div class="card-header">Dessert: {{$chosen_courses['Dessert'][$weekdaynumber]}}</div>

                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-8">
                                <label>Portioner totalt</label>
                            </div>
                            <div class="col-3">
                                <input type="number" min="0" max="{{$department->Boende}}" name="whatever" class="form-control" value="{{$department->Boende}}">
                            </div>
                        </div>

                        @if($department->special_diet_needs->isNotEmpty())
                            <label>Varav specialkost</label>

                            @foreach($department->special_diet_needs as $special_diet_need)

                                <div class="form-row">
                                    <div class="col-8">
                                        <label>{{$special_diet_need->special_diet->Namn}}</label>
                                    </div>
                                    <div class="col-3">
                                        <input type="number" min="0" max="{{$special_diet_need->Antal}}" name="whatever" class="form-control" value="{{$special_diet_need->Antal}}">
                                    </div>
                                </div>

                            @endforeach
                        @endif

                    </div>
                </div>
            @endif
        </div>

    </div>

</div>

            
            
            
            
            
            </div>
        @endforeach
    </div>

    <br>

    @if($almost_too_late)
        <div style="border-color:red;border-width:5px;border-style:solid;border-radius:0.3rem;padding-left:10px;font-size:1.5rem">
            Denna beställning inkommer efter brytdatum.<br>
            Det innebär att du själv måste kontakta köket och meddela dem om avvikelse!
        </div>
        <br>
    @endif

    <button {{$too_late?"disabled":""}} class="btn btn-primary btn-lg btn-block" id="submit" name="submit" type="submit">Spara</button>
</form>
