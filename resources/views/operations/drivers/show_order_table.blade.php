<!--  Last Order -->
<h1>Last Order </h1>
<table class="table table-hover text-nowrap">
    <thead>
        <tr>
            <th>ID</th>
            <th>By</th>
            <th>From</th>
            <th>Date</th>
            <th>Delivery Fee</th>
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
                <td>{{ $lastOrder->created_at }}</td>
                <td>{{ $lastOrder->delivery_fee }}</td>

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
            <th>Date</th>
            <th>Delivery Fee</th>

        </tr>
    </thead>
    <tbody>
        @forelse ($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->userName() }}</td>
                <td>{{ $order->restaurantName() }}</td>
                <td>{{ $lastOrder->created_at }}</td>
                <td>{{ $lastOrder->delivery_fee }}</td>

            </tr>
        @empty
        @endforelse


    </tbody>
</table>
{{ $orders->links() }}
