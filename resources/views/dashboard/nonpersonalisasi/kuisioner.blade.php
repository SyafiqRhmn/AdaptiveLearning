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
              <!-- <p class="card-text">Harap melakukan pengisian kuisioner terlebih dahulu</p> -->
              <h3>Matriks Keputusan</h3>
              <table class="table table-striped table-hover my-4 mt-2">
                  <thead class="bg-dark text-light">
                      <tr>
                          <th>#</th>
                          <th>1</th>
                          <th>2</th>
                          <th>3</th>
                          <th>4</th>
                          <th>5</th>
                      </tr>
                  </thead>
                  <tbody>
                      @foreach (['V', 'A', 'K'] as $kriteria)
                          <tr>
                              <td>{{ $kriteria }}</td>
                              @foreach ($matrix[$kriteria] as $value)
                                  <td>{{ $value }}</td>
                              @endforeach
                              @for ($i = count($matrix[$kriteria]); $i < 5; $i++)
                                  <td></td> <!-- Menambahkan sel kosong jika kurang dari 5 -->
                              @endfor
                          </tr>
                      @endforeach
                  </tbody>
              </table>

              <h3>Matriks Keputusan yang Ternomalisasi</h3>
              <table class="table table-striped table-hover my-4 mt-2">
                <thead class="bg-dark text-light">
                    <tr>
                        <th>#</th>
                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
                        <th>4</th>
                        <th>5</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>V</td>
                        @foreach ($normalized_matrix['V'] as $value)
                            <td>{{ number_format($value, 4) }}</td>
                        @endforeach
                        @for ($i = count($normalized_matrix['V']); $i < 5; $i++)
                            <td></td> <!-- Menambahkan sel kosong jika kurang dari 5 -->
                        @endfor
                    </tr>
                    <tr>
                        <td>A</td>
                        @foreach ($normalized_matrix['A'] as $value)
                            <td>{{ number_format($value, 4) }}</td>
                        @endforeach
                        @for ($i = count($normalized_matrix['A']); $i < 5; $i++)
                            <td></td> <!-- Menambahkan sel kosong jika kurang dari 5 -->
                        @endfor
                    </tr>
                    <tr>
                        <td>K</td>
                        @foreach ($normalized_matrix['K'] as $value)
                            <td>{{ number_format($value, 4) }}</td>
                        @endforeach
                        @for ($i = count($normalized_matrix['K']); $i < 5; $i++)
                            <td></td> <!-- Menambahkan sel kosong jika kurang dari 5 -->
                        @endfor
                    </tr>
                </tbody>
            </table>

            <h3>Matriks Keputusan yang Ternomalisasi Terbobot</h3>
              <table class="table table-striped table-hover my-4 mt-2">
                <thead class="bg-dark text-light">
                    <tr>
                        <th>#</th>
                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
                        <th>4</th>
                        <th>5</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>V</td>
                        @foreach ($bobot['V'] as $value)
                            <td>{{ number_format($value, 4) }}</td>
                        @endforeach
                        @for ($i = count($bobot['V']); $i < 5; $i++)
                            <td></td> <!-- Menambahkan sel kosong jika kurang dari 5 -->
                        @endfor
                    </tr>
                    <tr>
                        <td>A</td>
                        @foreach ($bobot['A'] as $value)
                            <td>{{ number_format($value, 4) }}</td>
                        @endforeach
                        @for ($i = count($bobot['A']); $i < 5; $i++)
                            <td></td> <!-- Menambahkan sel kosong jika kurang dari 5 -->
                        @endfor
                    </tr>
                    <tr>
                        <td>K</td>
                        @foreach ($bobot['K'] as $value)
                            <td>{{ number_format($value, 4) }}</td>
                        @endforeach
                        @for ($i = count($bobot['K']); $i < 5; $i++)
                            <td></td> <!-- Menambahkan sel kosong jika kurang dari 5 -->
                        @endfor
                    </tr>
                </tbody>
            </table>

            <h3>Matriks Solusi Ideal Positif dan Negatif</h3>
            <table class="table table-striped table-hover my-4 mt-2">
                <thead class="bg-dark text-light">
                    <tr>
                        <th>#</th>
                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
                        <th>4</th>
                        <th>5</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Positif</td>
                        @foreach ($matrix_ideal as $data)
                            <td>{{ number_format($data['max'], 4) }}</td>
                        @endforeach
                        @for ($i = count($matrix_ideal); $i < 5; $i++)
                            <td></td> <!-- Menambahkan sel kosong jika kurang dari 5 -->
                        @endfor
                    </tr>
                    <tr>
                        <td>Negatif</td>
                        @foreach ($matrix_ideal as $data)
                            <td>{{ number_format($data['min'], 4) }}</td>
                        @endforeach
                        @for ($i = count($matrix_ideal); $i < 5; $i++)
                            <td></td> <!-- Menambahkan sel kosong jika kurang dari 5 -->
                        @endfor
                    </tr>
                </tbody>
            </table>

            <h3>Jarak Ideal Positif dan Negatif</h3>
            <table class="table table-striped table-hover my-4 mt-2">
                <thead class="bg-dark text-light">
                <tr>
                    <th>Kriteria</th>
                    <th>Jarak Ideal Positif (D+)</th>
                    <th>Jarak Ideal Negatif (D-)</th>
                </tr>
            </thead>
            <tbody>
        <tr>
            <td>V</td>
            <td>{{ number_format($ideal_positif['V'], 5) }}</td>
            <td>{{ number_format($ideal_negatif['V'], 5) }}</td>
        </tr>
        <tr>
            <td>A</td>
            <td>{{ number_format($ideal_positif['A'], 5) }}</td>
            <td>{{ number_format($ideal_negatif['A'], 5) }}</td>
        </tr>
        <tr>
            <td>K</td>
            <td>{{ number_format($ideal_positif['K'], 5) }}</td>
            <td>{{ number_format($ideal_negatif['K'], 5) }}</td>
        </tr>
    </tbody>
            </table>

            <h3>Nilai Preferensi Setiap Kriteria</h3>
            <table class="table table-striped table-hover my-4 mt-2">
              <thead class="bg-dark text-light">
                  <tr>
                      <th>Kriteria</th>
                      <th>Preferensi</th>
                      <th>Ranking</th>
                  </tr>
              </thead>
              <tbody>
                  <tr>
                      <td>V</td>
                      <td>{{ number_format($preferensi['V'], 5) }}</td>
                      <td>{{ $ranking['V'] }}</td>
                  </tr>
                  <tr>
                      <td>A</td>
                      <td>{{ number_format($preferensi['A'], 5) }}</td>
                      <td>{{ $ranking['A'] }}</td>
                  </tr>
                  <tr>
                      <td>K</td>
                      <td>{{ number_format($preferensi['K'], 5) }}</td>
                      <td>{{ $ranking['K'] }}</td>
                  </tr>
              </tbody>
          </table>

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