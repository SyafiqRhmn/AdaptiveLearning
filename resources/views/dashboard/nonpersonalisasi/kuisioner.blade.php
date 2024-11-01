@extends('layouts.main')
@section('container')

<div class="wrapper d-flex align-items-stretch">
  @include('partials.sidebar')
  <div id="content" class="p-md-3">
    @include('partials.navbar_guru')


    <div class="container my-4">
      <div class="row">
        <div class="col">
        @if ($kriteriaExists)
            @php
                // Menentukan gaya belajar dengan nilai preferensi tertinggi
                $maxValue = max($preferensi);
                $highestPreference = array_search($maxValue, $preferensi); // Mendapatkan kunci tertinggi (V, A, atau K)
                $gayaBelajar = match($highestPreference) {
                    'V' => 'Visual',
                    'A' => 'Auditori',
                    'K' => 'Kinestetik',
                };
            @endphp

            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="card-title">Gaya Belajar Anda Termasuk : {{ $gayaBelajar }}</h3>

                    
                    <!-- Tombol Aksi -->
                    <a href="{{ route('reguler.my-class') }}" class="btn btn-primary">Kelas Saya</a>
                    <a href="{{ route('reguler.perhitungan') }}" class="btn btn-secondary">Lihat Perhitungan</a>
                </div>
            </div>
                @else
                  
              <h4>Pengisian Kuisioner</h4>
              <table class="table table-striped table-hover my-4 mt-2">
              <thead class="bg-dark text-light">
                  <tr>
                      <th scope="col" class="align-middle text-center" rowspan="2">No</th>
                      <th scope="col" class="align-middle" rowspan="2">Pertanyaan</th>
                      <th scope="col" class="text-center" colspan="5">Nilai</th>
                  </tr>
                  <tr class="text-center">
                    <th scope="col">1</th>
                    <th scope="col">2</th>
                    <th scope="col">3</th>
                    <th scope="col">4</th>
                    <th scope="col">5</th>
                  </tr>
              </thead>
              <tbody>
                @foreach ($kuisioner as $kuisioner)
                  <form action="{{ route('reguler.saveKuisioner') }}" method="POST">
                        @csrf
                      <tr>
                        <td class="text-center" scope="row">
                        {{ $no++ }}
                        </td>
                        <input type="hidden" name="kuisioners_id" value="{{ $kuisioner->id }}">
                        <td>
                        {{ $kuisioner->pertanyaan }}
                        </td>
                        <td class="text-center">
                            <label><input type="radio" name="answers[{{ $kuisioner->id }}]" value="1" required></label>
                        </td>
                        <td class="text-center">
                            <label><input type="radio" name="answers[{{ $kuisioner->id }}]" value="2" required></label>
                        </td>
                        <td class="text-center">
                            <label><input type="radio" name="answers[{{ $kuisioner->id }}]" value="3" required></label>
                        </td>
                        <td class="text-center">
                            <label><input type="radio" name="answers[{{ $kuisioner->id }}]" value="4" required></label>
                        </td>
                        <td class="text-center">
                            <label><input type="radio" name="answers[{{ $kuisioner->id }}]" value="5" required></label>
                        </td>
                      </tr>
                  @endforeach
                </tbody>
              </table>
              <button type="submit" class="btn pull-right btn-info">Kirim</button>
              </form>
            </div>              
            <!-- <p>Silakan pilih kriteria untuk menampilkan pertanyaan.</p> -->
            @endif
    </div>
  </div>
</div>

@endsection