@extends('layouts.main')
@section('container')

<div class="wrapper d-flex align-items-stretch">
  @include('partials.sidebar')
  <div id="content" class="p-md-3">
    @include('partials.navbar_guru')
                
    
    <div class="container my-4">
        <div class="row">
          <div class="col">
            <!-- <a href="{{ route('qu-pelajar.create') }}" class="mt-4 btn btn-primary">Tambah quesioner</a> -->
              @if (session('success'))
                  <div class="alert alert-success mt-3">
                      {{ session('success') }}
                  </div>
              @endif
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
              <tbody>
                <tr>
                  <td>1</td>
                  <td>Membandingkan V1 dengan V2: Seberapa penting V1 dibandingkan dengan V2? </td>
                  <td class="text-center">
                      <label><input type="radio" name="coba" value="1"></label>
                  </td>
                  <td class="text-center">
                      <label><input type="radio" name="coba" value="2"></label>
                  </td>
                  <td class="text-center">
                      <label><input type="radio" name="coba" value="3"></label>
                  </td>
                  <td class="text-center">
                      <label><input type="radio" name="coba" value="4"></label>
                  </td>
                  <td class="text-center">
                      <label><input type="radio" name="coba" value="1"></label>
                  </td>
                  <td class="text-center">
                      <label><input type="radio" name="coba" value="2"></label>
                  </td>
                  <td class="text-center">
                      <label><input type="radio" name="coba" value="3"></label>
                  </td>
                  <td class="text-center">
                      <label><input type="radio" name="coba" value="4"></label>
                  </td>
                  <td class="text-center">
                      <label><input type="radio" name="coba" value="4"></label>
                  </td>
                </tr>
                             
              </tbody>
              </table>
              <div class="d-flex justify-content-end">
         
              </div>
          </div>
        </div>
    </div>


  </div>
</div>

@endsection
