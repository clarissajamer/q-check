<h1>Edit Eskul</h1>

<form method="POST" action="{{ route('eskul.update', $eskul) }}">
    @csrf
    @method('PUT')
    @include('admin.eskul._form')
</form>
