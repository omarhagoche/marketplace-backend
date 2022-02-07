

{{ $orderProducts }}


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
                @foreach ($orderProducts as $orderProduct)
                <tr>
                  <th scope="row">{{ $orderProduct->food->name}}</th>
                  <td>{{$orderProduct->price}}</td>
                  <td>{{$orderProduct->quantity}}</td>
                  <td>
                      @foreach ($orderProduct->extras as $extra)
                      <div class="row">
                            <p>
                                {{$extra->name}} <a><i class="fa fa-trash text-danger"></i></a>
                            </p>
                      </div>
                      <hr>
                      @endforeach
                      {{-- @if (count($orderProduct->extras) !=0) --}}
                      <div class="row">
                        <div class="col col-md-6">
                            <select name="" id=""></select>
                        </div>
                        <div class="col col-md-6">
                          <button></button>
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



