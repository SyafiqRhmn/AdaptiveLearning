@extends('layouts.main')
@section('container')

<div class="wrapper d-flex align-items-stretch">
  <div id="content" class="p-md-3">


<form id="myForm" method="POST" action="">
  <input type="hidden" name="title" value="TEST">
@csrf
<div class="container-fluid my-4">
    <div class="row">
        <div class="col-9">
          <div class="card p-3">
          </div>
        </div>
        Gaya belajar mahasiswa
        <div class="col-3 card">
          <div class="container-fluid">
            <div class="row pt-4">
            </div>            
            <div class="row py-4">
              <div class="col">
               </div>
            </div>
          </div>
        </div>                     
    </div>
</div>
</form>


  </div>
</div>

@endsection