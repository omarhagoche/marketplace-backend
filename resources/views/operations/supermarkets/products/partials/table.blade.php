<div style="overflow-x:auto;" class="scroll-inner">
    <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            {{-- <tr id="search-fields">
                <th colspan="1"><span>#</span></th>
                <th colspan="2"><span>name</span></th>
                <th colspan="2"><span>price</span></th>
                <th><span>discount price</span></th>
                <th><span>package count</span></th>
                <th style="width: 136px;"><span hidden>category</span>{!! Form::select('category_id', $category,null, ['id'=>'search_cat','class' => 'select2 form-control']) !!}</th> 
                <th hidden>categoryname</th>
                <th hidden>available</th>
                <th hidden>update</th>
                <th hidden>actions</th> 
            </tr> --}}
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>price</th>
                {{-- <th>package count</th> --}}
                <th>category</th>
                <th hidden>categoryname</th>
                <th>available</th>
                <th>update</th>
                <th>actions</th>
            </tr>

        </thead>

        <tbody>
            @foreach ($supermarket->foods as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>
                        <div style="visibility: hidden; font-size: 2px;">{{ $product->name }}</div>
                        <input style="min-width:193px;" type="text" value="{{ $product->name }}"
                            id="name{{ $product->id }}" class="form-control">
                    </td>
                    <td>
                        <div style="visibility: hidden; font-size: 2px;">{{ $product->price }}</div>
                        <input type="text" value="{{ $product->price }}" id="price{{ $product->id }}"
                            class="form-control">
                    </td>
                    <td hidden>
                        <div>{{ $product->category->name }}</div>
                    </td>
                    <td style="width: 25%">
                        {!! Form::select('category_id', $categories, $product->category_id, ['id' => 'category_id' . $product->id, 'class' => 'select2 form-control']) !!}

                    </td>
                    <td>
                        <div class="form-group checkbox icheck">
                            <label class="col-9 ml-2 form-check-inline">
                                {!! Form::hidden('available', 0) !!}
                                {!! Form::checkbox('available', 1, $product->available, ['id' => 'available' . $product->id]) !!}
                            </label>
                        </div>
                    </td>
                    <td style="text-align: center;">
                        <button class='btn btn-primary' onclick="editBasicInto({{ $product->id }})"
                            id="button-{{ $product->id }}">
                            <i class="fa fa-edit"></i>
                        </button>
                    </td>
                    <td>
                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                            <div class="btn-group" role="group">
                                <button id="btnGroupDrop1" type="button" class="btn btn-light dropdown-toggle"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    actions
                                </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                    <a class="dropdown-item"
                                        href="{{ route('operations.restaurant.foods.edit', [$id, $product->id]) }}"><i
                                            class="fa fa-edit"></i> {{ trans('lang.product_edit') }}</a>
                                    @can('operations.restaurant.foods.delete')
                                        {!! Form::open(['route' => ['operations.restaurant.foods.delete', $product->id, $id], 'method' => 'delete']) !!}
                                        {!! Form::button('<i class="fa fa-trash"></i>  ' . trans('lang.delete'), [
                                                'type' => 'submit',
                                                'class' => 'btn btn-link text-danger',
                                                'onclick' => "return confirm('Are you sure?')",
                                            ]) !!}
                                        {!! Form::close() !!}
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
