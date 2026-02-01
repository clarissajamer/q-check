<h1>Tambah Eskul</h1>

<form method="POST" action="{{ route('eskul.store') }}">
    @csrf
    @include('admin.eskul._form')
</form>
