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

          <form action="{{ route('qu-dosen.index') }}" method="GET" class="mb-4">
            <div class="form-group">
              <label for="kriteria">Pilih Gaya Belajar Kuisioner:</label>
              <select name="kriteria" id="kriteria" class="form-control">
                <option value="">-- Pilih Gaya Belajar --</option>
                <option value="V" {{ (isset($kriteria) && $kriteria == 'V') ? 'selected' : '' }}>Visual</option>
                <option value="A" {{ (isset($kriteria) && $kriteria == 'A') ? 'selected' : '' }}>Auditori</option>
                <option value="K" {{ (isset($kriteria) && $kriteria == 'K') ? 'selected' : '' }}>Kinestetik</option>
              </select>
            </div>
            <button type="submit" class="btn btn-primary">Tampilkan Pertanyaan</button>
          </form>
        </div>
      </div>

      @if(isset($kriteria) && $kriteriaExists)
       
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
                  <td>{{ number_format($colTotal, 2) }}</td>
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

          <form action="{{ route('qu-dosen.destroy') }}" class="d-inline" method="POST">
            @csrf
            <input type="hidden" name="kriteria" value="{{ $kriteria }}">
            <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah anda yakin ingin menghapus kuisioner?')">
              <i class="bi bi-trash"></i> RESET
            </button>
          </form>


    @elseif (isset($kriteria)) <!-- Jika kuisioner ada tetapi kriteria tidak ada -->
          <div class="row">
            <div class="col-md-6">
              <h2>Pertanyaan Kuisioner</h2>
              <table class="table table-striped table-hover my-4 mt-2">
                <thead class="bg-dark text-light">
                  <tr>
                    <th scope="col" class="align-middle text-center">#</th>
                    <th scope="col" class="align-middle">Gaya Belajar</th>
                  </tr>
                </thead>
                <tbody>
                  @php $counter = 1; @endphp
                  @foreach($kuisioners as $kuisioner)
                    <tr>
                      <td>{{ $kriteria }}{{ $counter++ }}</td>
                      <td>{{ $kuisioner->pertanyaan }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

            <div class="col-md-6">
              <h2>Definisi Numerik</h2>
              <table class="table table-striped table-hover my-4 mt-2">
                <thead class="bg-dark text-light">
                  <tr>
                    <th scope="col" class="align-middle text-center">Numerik</th>
                    <th scope="col" class="align-middle">Definisi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ([1 => 'Sama penting', 3 => 'Sedikit lebih penting', 5 => 'Lebih Penting', 7 => 'Sangat penting', 9 => 'Mutlak penting', 2 => 'Nilai tengah antara 1 dan 3', 4 => 'Nilai tengah antara 3 dan 5', 6 => 'Nilai tengah antara 5 dan 7', 8 => 'Nilai tengah antara 7 dan 9'] as $num => $definition)
                    <tr>
                      <td scope="col" class="text-center">{{ $num }}</td>
                      <td>{{ $definition }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>

      <div class="row">
        <div class="col">
          <form method="POST" action="{{ route('qu-dosen.matrix') }}" class="p-1">
            @csrf
            <table class="table table-striped table-hover my-4 mt-2">
              <thead class="bg-dark text-light">
                <tr>
                  <th scope="col" class="align-middle text-center" rowspan="2">No</th>
                  <th scope="col" class="align-middle" rowspan="2">Pertanyaan</th>
                  <th scope="col" class="text-center" colspan="9">Nilai</th>
                </tr>
                <tr class="text-center">
                  @for ($i = 1; $i <= 9; $i++)
                    <th scope="col">{{ $i }}</th>
                  @endfor
                </tr>
              </thead>
              <tbody>
                @for ($i = 1; $i <= $poinCount; $i++)
                  @for ($j = $i + 1; $j <= $poinCount; $j++)
                    <tr>
                      <td>#</td>
                      <td>
                        Membandingkan {{ $kriteria }} {{ $i }} dengan {{ $kriteria }} {{ $j }}: 
                        Seberapa penting {{ $kriteria }} {{ $i }} dibandingkan dengan {{ $kriteria }} {{ $j }}?
                      </td>
                      @for ($value = 1; $value <= 9; $value++)
                        <td class="text-center">
                          <label><input type="radio" name="comparison_{{ $kriteria }}_{{ $i }}_{{ $j }}" value="{{ $value }}"></label>
                        </td>
                      @endfor
                    </tr>
                  @endfor
                @endfor
              </tbody>
            </table>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
          </form>
        </div>
        @else
   
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
