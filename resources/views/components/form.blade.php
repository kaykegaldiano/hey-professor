@props([
    'action',
    'method' => null,
])

<form action="{{ $action }}" method="post" {{ $attributes }}>
    @csrf

    @if ($method === 'put')
        @method('PUT')
    @endif

    @if($method === 'patch')
        @method('PATCH')
    @endif

    @if($method === 'delete')
        @method('DELETE')
    @endif

    {{ $slot }}

</form>
