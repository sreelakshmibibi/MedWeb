{{-- resources/views/settings/menu_items/partials/menu_item_row.blade.php --}}
<tr>
    <td>{{ str_repeat('—', $level) }} {{ $item->name }}</td>
    <td>{{ $item->url }}</td>
    <td>{{ $item->icon }}</td>
    <td class="text-center">{{ $item->order_no }}</td>
    <td>{{ $item->route_name }}</td>
    <td class="text-center">{{ $item->status == 'Y' ? 'Active' : 'Inactive' }}</td>
    <td class="text-center">
        <a href="{{ route('menu_items.edit', $item->id) }}" class="btn btn-warning btn-sm me-2">Edit</a>
        <form action="{{ route('menu_items.destroy', $item->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
        </form>
    </td>
</tr>

@foreach ($item->children ?? [] as $child)
    @include('settings.menu_items.menu_item_row', ['item' => $child, 'level' => $level + 1])
@endforeach
