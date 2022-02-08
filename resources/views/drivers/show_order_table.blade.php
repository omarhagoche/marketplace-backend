<!--  Last Order -->
<h1>Last Order </h1>
<table class="table table-hover text-nowrap">
    <thead>
        <tr>
            <th>ID</th>
            <th>By</th>
            <th>From</th>
            {{-- <th>Delivery Address</th> --}}
            {{-- <th>Totle Items</th> --}}
        </tr>
    </thead>
    <tbody>
        @if ($lastOrder)

            <tr>
                <td>{{ $lastOrder->id }}</td>
                <td>{{ $lastOrder->userName() }}</td>
                <td>{{ $lastOrder->restaurantName() }}</td>
                {{-- <td>{{ $lastOrder->deliveryAddress }}</td> --}}
                {{-- <td>{{ $lastOrder->foodOrder }}</td> --}}
            </tr>
        @endif
    </tbody>
</table>

<!--  Order History -->
<h1> Orders </h1>
<table class="table table-hover text-nowrap">
    <thead>
        <tr>
            <th>ID</th>
            <th>By</th>
            <th>From</th>

        </tr>
    </thead>
    <tbody>
        @forelse ($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->userName() }}</td>
                <td>{{ $order->restaurantName() }}</td>
            </tr>
        @empty

            <H3> no orders</H3>
        @endforelse


    </tbody>
</table>
