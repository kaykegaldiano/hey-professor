@props([
    'action',
    'method' => null,
])

<form action="{{ $action }}" method="post">
    @csrf

    @if ($method === 'put')
        @method('PUT')
    @endif

    @if($method === 'delete')
        @method('DELETE')
    @endif

    {{ $slot }}

</form>
