

<div style="flex: 50%;max-width: 100%;padding: 0 4px;" class="column">

<div class="row">
    <div class="col col-md-12" style="width: 100%; border-collapse: collapse; overflow-x:auto;">
        <table class="table">
            <thead>
              <tr>
                <th scope="col">name</th>
                <th scope="col">price</th>
                <th scope="col">quantity</th>
                <th scope="col">extras</th>
                <th scope="col">actions</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($orderFoods as $orderFoods)
                <tr>
                  <th scope="row">{{ $orderFoods->food->name}}</th>
                  <td>{{$orderFoods->price}}</td>
                  <td>{{$orderFoods->quantity}}</td>
                  <td>
                      @foreach ($orderFoods->extras as $extra)
                      <div class="row">
                            <button type="button" class="btn btn-outline-dark mb-2">
                                {{$extra->name}} <a><i class="fa fa-trash text-danger"></i></a>
                            </button>
                      </div>
                      @endforeach
                      {{-- @if (count($orderFoods->extras) !=0) --}}
                      <div class="row">
                        <div class="col col-md-6">
                          {{-- {{}} --}}
                            {!! Form::select('user_id', $orderFoods->food->extras->pluck('name','id'),null, ['class' => 'select2 form-control']) !!}
                        </div>
                        <div class="col col-md-6">
                          <button class="btn btn-primary">Add Extra <i class="fa fa-plus"></i></button>
                        </div>
                      </div>
                      {{-- @endif --}}
                    </td>
                  <td>Otto</td>
                </tr>
                @endforeach
            </tbody>
          </table>
    </div>
</div>


</div>



