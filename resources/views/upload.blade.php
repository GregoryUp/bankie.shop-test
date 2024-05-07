@include('parts/header', ['title' => 'Статус'])

<div class="container">
    <h1>Статус <span class="{{ $status === 'SUCCESS' ? 'text-success' : 'text-danger' }}">{{ $status }}</span></h1>
    <p>
        {{ $message }}
    </p>
</div>

@include('parts/footer')