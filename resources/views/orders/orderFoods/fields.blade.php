

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
                      {!! Form::open(['route' => ['orders.remove-extra', $orderFoods->id], 'method' => 'HEAD']) !!}
                      {{-- <div class="row"> --}}
                            <input type="hidden" name="food_order_id" value="{{$extra->pivot->food_order_id}}">
                            <input type="hidden" name="extra_id" value="{{$extra->pivot->extra_id}}">
                            <button type="submit" class="btn btn-outline-dark mb-2">
                                {{$extra->name}} <a><i class="fa fa-trash text-danger"></i></a>
                            </button>
                      {{-- </div> --}}
                      {!! Form::close() !!}
                      @endforeach
                      {!! Form::open(['route' => ['orders.add-extra', $orderFoods->id], 'method' => 'HEAD']) !!}
                      <div class="row">
                        <div class="col col-md-6">
                          <select name="extraId" id="extra" class="select2 form-control">
                            @foreach ($orderFoods->food->extras as $extra)
                            @if (!$orderFoods->foodOrderExtra->pluck('extra_id')->contains($extra->id))
                              <option value="{{ $extra->id }}">{{ $extra->name}}</option>
                            @endif
                            @endforeach
                          </select>
                        </div>
                        <div class="col col-md-6">
                          <button id="addExtra" class="btn btn-primary">Add Extra <i class="fa fa-plus"></i></button>
                        </div>
                      </div>
                      {!! Form::close() !!}
                    </td>
                  <td>Otto</td>
                </tr>
                @endforeach
            </tbody>
          </table>
    </div>
</div>


</div>
