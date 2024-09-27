@extends('layouts.main')
@section('container')

<div class="wrapper d-flex align-items-stretch">
  @include('partials.sidebar')
  <div id="content" class="p-md-3">
    @include('partials.navbar_guru')


    <div class="container my-4">
        <div class="row">
            <div class="col">
              <h4>Setting:</h4>
              <form action="{{ route('reguler.editAkun', ['userID' => $myprofile -> id]) }}" method="post">
        @csrf
        @method('put')

        <div class="form-group">
            <label for="name">Nama:</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $myprofile->name }}">
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $myprofile->email }}">
        </div>

        <div class="form-group">
        <label for="tipe">Tipe:</label>
            <select class="form-control" id="tipe" name="tipe">
                <option value="adaptive" {{ $myprofile->tipe === 'adaptive' ? 'selected' : '' }}>adaptive</option>
                <option value="reguler" {{ $myprofile->tipe === 'reguler' ? 'selected' : '' }}>reguler</option>
            </select>
          </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
@endsection