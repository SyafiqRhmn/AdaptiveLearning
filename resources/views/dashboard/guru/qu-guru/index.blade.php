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
                
                <form action="{{ route('qu-guru.index') }}" method="GET">
                
                <div class="form-group mb-4">
                    <label for="kriteria">Pilih Gaya Belajar Kuisioner:</label>
                    <select name="kriteria" id="kriteria" class="form-control">
                        <option value="">-- Pilih Gaya Belajar --</option>
                        <option value="V" {{ (isset($kriteria) && $kriteria == 'V') ? 'selected' : '' }}>Visual</option>
                        <option value="A" {{ (isset($kriteria) && $kriteria == 'A') ? 'selected' : '' }}>Auditori</option>
                        <option value="K" {{ (isset($kriteria) && $kriteria == 'K') ? 'selected' : '' }}>Kinestetik</option>
                    </select>
                </div>                
                <div>
                    <button type="submit" class="btn btn-primary">Tampilkan Pertanyaan</button>
                </div>
                </form>
                @if(isset($kriteria))
            </div>  
        </div>
        <div class="row">
            <div class="col">
                <table class="table table-striped table-hover my-4 mt-2">
                <thead class="bg-dark text-light">
                    <tr>
                        <th scope="col" class="align-middle text-center">#</th>
                        <th scope="col" class="align-middle">Gaya Belajar</th>
                    </tr>
                </thead>
                @foreach($kuisioners as $kuisioner)
                <tbody>
                    <tr>
                        <td>{{ $kriteria }}{{ $counter++ }}</td>
                        <td>{{ $kuisioner->pertanyaan }}</td>
                    </tr>
                </tbody>
                @endforeach
                </table>
            </div>
            <div class="col">
                <table class="table table-striped table-hover my-4 mt-2">
                <thead class="bg-dark text-light">
                    <tr>
                        <th scope="col" class="align-middle text-center">Numerik</th>
                        <th scope="col" class="align-middle">Definisi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td scope="col" class="text-center">1</td>
                        <td>Sama penting</td>
                    </tr>
                    <tr>
                        <td class="text-center">3</td>
                        <td>Sedikit lebih penting</td>
                    </tr>
                    <tr>
                        <td class="text-center">5</td>
                        <td>Lebih Penting</td>
                    </tr>
                    <tr>
                        <td class="text-center">7</td>
                        <td>Sangat penting</td>
                    </tr>
                    <tr>
                        <td class="text-center">9</td>
                        <td>Mutlak penting</td>
                    </tr>
                    <tr>
                        <td class="text-center">2</td>
                        <td>Nilai tengah antara 1 dan 3</td>
                    </tr>
                    <tr>
                        <td class="text-center">4</td>
                        <td>Nilai tengah antara 3 dan 5</td>
                    </tr>
                    <tr>
                        <td class="text-center">6</td>
                        <td>Nilai tengah antara 5 dan 7</td>
                    </tr>
                    <tr>
                        <td class="text-center">8</td>
                        <td>Nilai tengah antara 7 dan 9</td>
                    </tr>
                </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col">
            <form method="POST" action="{{ route('qu-guru.matrix') }}" class="p-1">
                @csrf
              <table class="table table-striped table-hover my-4 mt-2">
              <thead class="bg-dark text-light">
                <tr>
                    <th scope="col" class="align-middle text-center" rowspan="2">No</th>
                    <th scope="col" class="align-middle" rowspan="2">Pertanyaan</th>
                    <th scope="col" class="text-center" colspan="9">Nilai</th>
                </tr>
                <tr class="text-center">
                    <th scope="col">1</th>
                    <th scope="col">2</th>
                    <th scope="col">3</th>
                    <th scope="col">4</th>
                    <th scope="col">5</th>
                    <th scope="col">6</th>
                    <th scope="col">7</th>
                    <th scope="col">8</th>
                    <th scope="col">9</th>
                </tr>
              </thead>
              @for ($i = 1; $i <= $poinCount; $i++)
                @for ($j = $i + 1; $j <= $poinCount; $j++) <!-- Membandingkan V1 dengan V2, dst. -->
              <tbody>
                <tr>
                    <td>#</td>
                    <td>
                        Membandingkan {{ $kriteria }}  {{ $i }} dengan {{ $kriteria }}  {{ $j }}: 
                        Seberapa penting {{ $kriteria }}  {{ $i }} dibandingkan dengan {{ $kriteria }}  {{ $j }}?
                    </td>
                    <td class="text-center">
                        <label><input type="radio" name="comparison_{{ $kriteria }}_{{ $i }}_{{ $j }}" value="1"></label>
                    </td>
                    <td class="text-center">
                        <label><input type="radio" name="comparison_{{ $kriteria }}_{{ $i }}_{{ $j }}" value="2"></label>
                    </td>
                    <td class="text-center">
                        <label><input type="radio" name="comparison_{{ $kriteria }}_{{ $i }}_{{ $j }}" value="3"></label>
                    </td>
                    <td class="text-center">
                        <label><input type="radio" name="comparison_{{ $kriteria }}_{{ $i }}_{{ $j }}" value="4"></label>
                    </td>
                    <td class="text-center">
                        <label><input type="radio" name="comparison_{{ $kriteria }}_{{ $i }}_{{ $j }}" value="5"></label>
                    </td>
                    <td class="text-center">
                        <label><input type="radio" name="comparison_{{ $kriteria }}_{{ $i }}_{{ $j }}" value="6"></label>
                    </td>
                    <td class="text-center">
                        <label><input type="radio" name="comparison_{{ $kriteria }}_{{ $i }}_{{ $j }}" value="7"></label>
                    </td>
                    <td class="text-center">
                        <label><input type="radio" name="comparison_{{ $kriteria }}_{{ $i }}_{{ $j }}" value="8"></label>
                    </td>
                    <td class="text-center">
                        <label><input type="radio" name="comparison_{{ $kriteria }}_{{ $i }}_{{ $j }}" value="9"></label>
                    </td>
                </tr>
              </tbody>
                @endfor
            @endfor
              </table>
              <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                </form>
                @else
                    <!-- <p>Silakan pilih kriteria untuk menampilkan pertanyaan.</p> -->
                @endif
              <div class="d-flex justify-content-end">
         
            </div>
        </div>
    </div>
  </div>
</div>

@endsection
