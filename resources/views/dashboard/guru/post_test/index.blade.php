@extends('layouts.main')
@section('container')

<div class="wrapper d-flex align-items-stretch">
  @include('partials.sidebar')
  <div id="content" class="p-md-3">
    @include('partials.navbar_guru')


    <div class="container my-4">
        <div class="row">
            <div class="col">
              <a href="{{ route('post-test.create') }}" class="mt-4 btn btn-primary">Tambah Post Test</a>
              @if (session('success'))
                  <div class="alert alert-success mt-3">
                      {{ session('success') }}
                  </div>
              @endif
                <table class="table table-striped table-hover my-4 mt-2">
                    <thead class="bg-dark text-light">
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nama Post Test</th>
                        <th scope="col">Kelas</th>
                        <th scope="col">action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($postTests as $postTest)    
                        <tr>
                          <th>{{ $loop->iteration + ($postTests->perPage() * ($postTests->currentPage() - 1)) }}</th>
                          <td>{{ $postTest->name }}</td>
                          <td>{{ $postTest->classroom->name }}</td>
                          <td>
                            <a title="lihat data" href="{{ route('post-test.show', $postTest->id) }}" class="btn btn-sm btn-primary"><i class="bi bi-eye"></i></a>
                            <a title="edit data" href="{{ route('post-test.edit', $postTest->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('post-test.destroy', $postTest->id) }}" class="d-inline" method="POST">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah anda yakin ingin menghapus?')"><i class="bi bi-trash"></i></button>
                            </form>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-end">
                    {{ $postTests->links() }}
                </div>
            </div>
        </div>
    </div>


  </div>
</div>

@endsection