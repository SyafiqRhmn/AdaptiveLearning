@extends('layouts.main')
@section('container')

<div class="container my-4">
    <div class="row">
        <div class="col">
          <a href="{{ route('user.create') }}" class="mt-4 btn btn-primary">Tambah data pengguna</a>
          @if (session('success'))
              <div class="alert alert-success mt-3">
                  {{ session('success') }}
              </div>
          @endif
            <table class="table table-striped table-hover my-4 mt-2">
                <thead class="bg-dark text-light">
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nama</th>
                    <th scope="col">NIM</th>
                    <th scope="col">Email</th>
                    <th scope="col">Program Studi</th>
                    <th scope="col">Kelas</th>
                    <th scope="col">role</th>
                    <th scope="col">tipe</th>
                    <th scope="col">action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($users as $user)    
                    <tr>
                      <th>{{ $loop->iteration + ($users->perPage() * ($users->currentPage() - 1)) }}</th>
                      <td>{{ $user->name }}</td>
                      <td>{{ $user->nim }}</td>
                      <td>{{ $user->email }}</td>
                      <td>{{ $user->program_studi }}</td>
                      <td>{{ $user->kelas }}</td>
                      <td>{{ $user->role }}</td>
                      <td>{{ $user->tipe }}</td>
                      <td>
                        <a title="edit data" href="{{ route('user.edit', $user->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('user.destroy', $user->id) }}" class="d-inline" method="POST">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah anda yakin ingin menghapus?')"><i class="bi bi-trash"></i></button>
                        </form>
                        <a title="Reset Password" onclick="return confirm('Apakah anda yakin ingin me-reset password?')" href="{{ route('reset.password', ['id' => $user->id]) }}" class="btn btn-sm btn-warning"><i class="bi bi-key"></i></a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-end">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

@endsection