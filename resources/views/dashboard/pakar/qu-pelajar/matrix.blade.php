@extends('layouts.main')
@section('container')

<div class="wrapper d-flex align-items-stretch">
  @include('partials.sidebar')
  <div id="content" class="p-md-3">
    @include('partials.navbar_guru')

    <div class="container my-4">
        <div class="row">
          <div class="col">
              @if (session('success'))
                  <div class="alert alert-success mt-3">
                      {{ session('success') }}
                  </div>
              @endif
              <h2>Hasil Matrix</h2>
              <table class="table table-striped table-hover my-4">
                  <thead class="bg-dark text-light">
                      <tr>
                          <th>Kriteria</th>
                          @for ($i = 1; $i <= count($matrix); $i++)
                          <th>{{ $kriteria }}{{ $i }}</th>
                          @endfor
                      </tr>
                  </thead>
                  <tbody>
                      @foreach ($matrix as $i => $row)
                          <tr>
                              <th>{{ $kriteria }}{{ $i }}</th>
                              @foreach ($row as $j => $value)
                                  <td>{{ number_format($value, 2) }}</td>
                              @endforeach
                          </tr>
                      @endforeach
                  </tbody>
                  <tfoot class="bg-dark text-light">
                      <tr>
                          <th>Jumlah</th>
                          @foreach ($totalCol as $colTotal)
                              <td>{{ number_format($colTotal, 2) }}</td> <!-- Menampilkan Total Kolom di bagian bawah -->
                          @endforeach
                      </tr>
                  </tfoot>
              </table>
              <h2>Hasil Perkalian Matriks</h2>
              <table class="table table-striped table-hover my-4">
                  <thead class="bg-dark text-light">
                      <tr>
                          <th>Kriteria</th>
                          @for ($i = 1; $i <= count($resultMatrix); $i++)
                              <th>{{ $kriteria }}{{ $i }}</th>
                          @endfor
                          <th>Total</th>
                          <th>EV</th>
                      </tr>
                  </thead>
                  <tbody>
                      @foreach ($resultMatrix as $i => $row)
                          <tr>
                              <th>{{ $kriteria }}{{ $i }}</th>
                              @foreach ($row as $j => $value)
                                  <td>{{ number_format($value, 2) }}</td>
                              @endforeach
                              <td>{{ number_format($total[$i], 2) }}</td>
                              <td>{{ number_format($ev[$i], 2) }}</td>
                          </tr>
                      @endforeach
                  </tbody>
              </table>

              <h2>CI dan CR</h2>
              <table class="table table-striped table-hover my-4">
                  <thead class="bg-dark text-light">
                      <tr>
                          <th>Emakx</th>
                          <th>CI</th>
                          <th>CR</th>
                          <th>Konsistensi</th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr>
                          <td>{{ number_format($emax, 9) }}</td>
                          <td>{{ number_format($ci, 5) }}</td>
                          <td>{{ number_format($cr, 5) }}</td>
                          <td>
                              @if ($cr <= 0.1)
                                  KONSISTEN
                              @else
                                  TIDAK KONSISTEN
                              @endif
                          </td>
                      </tr>
                  </tbody>
              </table>
              <a href="{{ route('qu-dosen.index') }}" class="btn btn-danger"><i class="bi bi-back"></i> BACK</a>
              
              <form action="{{ route('qu-dosen.destroy') }}" class="d-inline" method="POST">
                    @csrf
                    
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah anda yakin ingin menghapus kuisioner?')"><i class="bi bi-trash"></i> RESET</button>
                </form>
              <div class="d-flex justify-content-end">
                 
              </div>
          </div>
      </div>
    </div>
  </div>
</div>

@endsection
