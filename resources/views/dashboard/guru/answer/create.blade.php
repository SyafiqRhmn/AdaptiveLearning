@extends('layouts.main')
@section('container')

<div class="wrapper d-flex align-items-stretch">
  @include('partials.sidebar')
  <div id="content" class="p-md-3">
    @include('partials.navbar_guru')


<div class="container my-5">
    <div class="row">
        <div class="col-10">
            <form method="POST" action="{{ route('answer.store') }}" class="p-1">
                @csrf
                <div class="form-group mb-3">
                    <label for="jawaban" class="form-label">Jawaban</label>
                    <textarea class="{{ $errors->has('jawaban') ? ' is-invalid' : '' }}" name="jawaban" id="editor1" required>{{ old('jawaban') }}</textarea>
                    @error('jawaban')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="">Pilih status jawaban (benar/salah)</label>
                    <div class="form-check">
                        <input class="form-check-input{{ $errors->has('benar_salah') ? ' is-invalid' : '' }}" type="radio" name="benar_salah" id="answer_benar" value="benar" {{ old('benar_salah') === 'benar' ? 'checked' : '' }}>
                        <label class="form-check-label" for="answer_benar">
                          Benar
                        </label>
                    </div>
                      
                    <div class="form-check">
                        <input class="form-check-input{{ $errors->has('benar_salah') ? ' is-invalid' : '' }}" type="radio" name="benar_salah" id="answer_salah" value="salah" {{ old('benar_salah') === 'salah' ? 'checked' : '' }}>
                        <label class="form-check-label" for="answer_salah">
                            Salah
                        </label>
                    </div>
                    @error('benar_salah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="question_id">Pilih Soal</label>
                    <select name="question_id" id="question_id" class="form-control{{ $errors->has('question_id') ? ' is-invalid' : '' }}">
                        <option selected disabled>(jenis test - soal)</option>
                        @foreach ($questions as $question)
                            <option value="{{ $question->id }}" {{ old('question_id') === $question->id ? 'selected' : '' }}>{{ $question->testable_type.' - '.$question->question }}</option>
                        @endforeach
                    </select>
                    @error('question_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <a href="{{ route('answer.index') }}" class="btn btn-danger"><i class="bi bi-back"></i>  Back</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


</div>
</div>

<script>
    CKEDITOR.replace('editor1');
</script>

@endsection