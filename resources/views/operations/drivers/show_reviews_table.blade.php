<!--  Last Order -->
<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Rate</th>
            <th scope="col">User</th>
            <th scope="col">Review</th>
            <th scope="col">Created at</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($reviews as $item)
            <tr>
                <th scope="row">{{ $item->id }}</th>
                <td>{{ $item->rate }}</td>
                <td>{{ $item->user->name }}</td>
                <td>{{ $item->review }}</td>
                <td>{{ $item->created_at }}</td>
            </tr>
        @empty
        @endforelse


    </tbody>
</table>
