@extends('layouts.main')
@section('container')

<div class="wrapper d-flex align-items-stretch">
  @include('partials.sidebar')
  <div id="content" class="p-md-3">
    @include('partials.navbar_guru')


    <div class="container my-4">
      <div class="row">
        <div class="col">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Pengisian Kuisioner</h5>
              <!-- <p class="card-text">Harap melakukan pengisian kuisioner terlebih dahulu</p> -->
              <table class="table table-bordered">
              <thead>
                  <tr>
                      <th scope="col" class="align-middle text-center" rowspan="2">No</th>
                      <th scope="col" class="align-middle" rowspan="2">Pertanyaan</th>
                      <th scope="col" class="text-center" colspan="4">Nilai</th>
                  </tr>
                  <tr class="text-center">
                    <th scope="col">1</th>
                    <th scope="col">2</th>
                    <th scope="col">3</th>
                    <th scope="col">4</th>
                  </tr>
              </thead>
              <tbody>
                @foreach ($kuisioner as $kuisioner)
                  <form>
                      <tr>
                        <td class="text-center" scope="row">
                        {{ $no++ }}
                        </td>
                        <td>
                        {{ $kuisioner->pertanyaan }}
                        </td>
                        <td class="text-center">
                              <label><input type="radio" id='regular' name="optradio"></label>
                          
                        </td>
                        <td class="text-center">
                              <label><input type="radio" id='regular' name="optradio"></label>
                          
                        </td>
                        <td class="text-center">
                              <label><input type="radio" id='regular' name="optradio"></label>
                        </td>
                        <td class="text-center">
                              <label><input type="radio" id='regular' name="optradio"></label>
                        </td>
                      </tr>
                  </form>
                  @endforeach
                </tbody>
              </table>
            </div>              
          </div>
        </div>
    </div>
  </div>
</div>

@endsection