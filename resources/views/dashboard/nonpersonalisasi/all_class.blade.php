@extends('layouts.main')
@section('container')

<div class="wrapper d-flex align-items-stretch">
  @include('partials.sidebar')
  <div id="content" class="p-md-3">
    @include('partials.navbar_guru')


    <div class="container my-4">
        <div class="row">
            <div class="col">
              <h4>Semua kelas:</h4>
              <table class="table table-striped table-hover my-4 mt-2">
                <thead class="bg-dark text-light">
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Classroom</th>
                    <th scope="col">action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($classrooms as $classroom)    
                    <tr>
                      <th>{{ $loop->iteration + ($classrooms->perPage() * ($classrooms->currentPage() - 1)) }}</th>
                      <td>{{ $classroom->name }}</td>
                      <td>
                        @if (!in_array($classroom->id, $my_classes))
                            <a title="Ikuti kelas" href="{{ route('reguler.all-class.ikuti', [$classroom->id]) }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-square"></i></a>
                        @else
                            <a style="pointer-events: none" title="Telah diikuti" href="#" class="btn btn-sm btn-success"><i class="bi bi-check-square"></i></a>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-end">
                {{ $classrooms->links() }}
            </div>
            </div>
        </div>
    </div>


  </div>
</div>

@endsection