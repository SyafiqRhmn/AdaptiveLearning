@extends('layouts.main')
@section('container')

<div class="wrapper d-flex align-items-stretch">
  @include('partials.sidebar')
  <div id="content" class="p-md-3">
    @include('partials.navbar_guru')


<div class="container my-5">
    <div class="row">
        <div class="col">

            <form method="POST" action="{{ route('soal-jawaban.update', $soalJawaban->id) }}">
                @method('PUT')
                @csrf
                <div class="form-group">
                    <label for="testable_type">Jenis Test</label>
                    <select class="form-control" id="testable_type" name="testable_type" onchange="toggleTestableId()">
                        <option disabled selected>(Pilih Test)</option>
                        <option value="pre-test" {{ old('testable_type', $soalJawaban->testable_id) === 'pre-test' ? 'selected' : '' }}>Pre Test</option>
                        <option value="post-test" {{ old('testable_type', $soalJawaban->testable_id) === 'post-test' ? 'selected' : '' }}>Post Test</option>
                        <option value="course-test" {{ old('testable_type', $soalJawaban->testable_id) === 'course-test' ? 'selected':''}}>Course Test</option>
                    </select>
                    @error('testable_type')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group" id="div_testable_pre" {{ old('testable_type', $soalJawaban->testable_id) !== 'posttest' && old('testable_type', $soalJawaban->testable_type) !== 'coursetest' ? 'hidden' : '' }}>
                    <label for="testable_id">Nama Test (Pre-Test)</label>
                    <select class="form-control" id="testable_id" name="testable_id">
                        <option disabled selected>(Pilih Pre-Test)</option>
                        @foreach ($preTests as $preTest)
                            <option value="{{ $preTest->id }}" {{ intval(old('testable_id', $soalJawaban->testable_id)) === $preTest->id ? 'selected' : '' }}>{{ $preTest->name }}</option>
                        @endforeach
                    </select>
                    @error('testable_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group div_testable_post" id="div_testable_post" {{ old('testable_type', $soalJawaban->testable_type) !== 'pretest' && old('testable_type', $soalJawaban->testable_type) !== 'coursetest' ? 'hidden' : '' }}>
                    <label for="testable_id">Nama Test (Post-Test)</label>
                    <select class="form-control" id="testable_id" name="testable_id">
                        <option disabled selected>(Pilih Post-Test)</option>
                        @foreach ($postTests as $postTest)
                            <option value="{{ $postTest->id }}" {{ intval(old('testable_id', $soalJawaban->testable_id)) === $postTest->id ? 'selected' : '' }}>{{ $postTest->name }}</option>
                        @endforeach
                    </select>
                    @error('testable_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group div_testable_course" id="div_testable_course" {{ old('testable_type', $soalJawaban->testable_type) !== 'pretest' && old('testable_type', $soalJawaban->testable_type) !== 'posttest' ? 'hidden' : '' }}>
                    <label for="testable_id">Nama Test (Course-Test)</label>
                    <select class="form-control" id="testable_id" name="testable_id">
                        <option disabled selected>(Pilih Course-Test)</option>
                        @foreach ($courseTests as $courseTest)
                            <option value="{{ $courseTest->id }}" {{ intval(old('testable_id', $soalJawaban->testable_id)) === $courseTest->id ? 'selected' : '' }}>{{ $courseTest->name }}</option>
                        @endforeach
                    </select>
                    @error('testable_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="question">Soal</label>
                    <textarea class="{{ $errors->has('question') ? ' is-invalid' : '' }}" name="question" id="editor1" required>{{ old('question', $soalJawaban->question) }}</textarea>
                    @if ($errors->has('question'))
                        <div class="invalid-feedback">
                            {{ $errors->first('question') }}
                        </div>
                    @endif
                </div>

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-6 ps-0">
                            <div class="form-group">
                                <label for="jawaban1">Jawaban 1</label>
                                <input type="hidden" value="{{ isset($soalJawaban->answers[0]->id) === true ? $soalJawaban->answers[0]->id : '' }}" name="jawaban_id1">
                                    &emsp;&emsp;&nbsp;<input class="form-check-input{{ $errors->has('benar_salah1') ? ' is-invalid' : '' }}" type="radio" name="benar_salah1" id="answer_benar1" value="benar" {{ old('benar_salah1', isset($soalJawaban->answers[0]->benar_salah) ? $soalJawaban->answers[0]->benar_salah : null) === 'benar' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="answer_benar1">
                                    Benar
                                    </label>
                                    &nbsp;<input class="{{ $errors->has('benar_salah1') ? ' is-invalid' : '' }}" type="radio" name="benar_salah1" id="answer_salah1" value="salah" {{ old('benar_salah1', isset($soalJawaban->answers[0]->benar_salah) ? $soalJawaban->answers[0]->benar_salah : null) === 'salah' ? 'checked' : '' }}>
                                    <label for="answer_salah1">
                                        Salah
                                    </label>
                                @error('benar_salah1')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <textarea class="{{ $errors->has('jawaban1') ? ' is-invalid' : '' }}" name="jawaban1" id="editor2" required>{{ old('jawaban1', isset($soalJawaban->answers[0]->jawaban) ? $soalJawaban->answers[0]->jawaban : null) }}</textarea>
                                @if ($errors->has('jawaban1'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('jawaban1') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-6 pe-0">
                            <div class="form-group">
                                <label for="jawaban2">Jawaban 2</label>
                                <input type="hidden" value="{{ isset($soalJawaban->answers[1]->id) === true ? $soalJawaban->answers[1]->id : '' }}" name="jawaban_id2">
                                    &emsp;&emsp;&nbsp;<input class="form-check-input{{ $errors->has('benar_salah2') ? ' is-invalid' : '' }}" type="radio" name="benar_salah2" id="answer_benar2" value="benar" {{ old('benar_salah2', isset($soalJawaban->answers[1]->benar_salah) ? $soalJawaban->answers[1]->benar_salah : null) === 'benar' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="answer_benar2">
                                    Benar
                                    </label>
                                    &nbsp;<input class="{{ $errors->has('benar_salah2') ? ' is-invalid' : '' }}" type="radio" name="benar_salah2" id="answer_salah2" value="salah" {{ old('benar_salah2', isset($soalJawaban->answers[1]->benar_salah) ? $soalJawaban->answers[1]->benar_salah : null) === 'salah' ? 'checked' : '' }}>
                                    <label for="answer_salah2">
                                        Salah
                                    </label>
                                @error('benar_salah2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <textarea class="{{ $errors->has('jawaban2') ? ' is-invalid' : '' }}" name="jawaban2" id="editor3" required>{{ old('jawaban2', isset($soalJawaban->answers[1]->jawaban) ? $soalJawaban->answers[1]->jawaban : null) }}</textarea>
                                @if ($errors->has('jawaban2'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('jawaban2') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 ps-0">
                            <div class="form-group">
                                <label for="jawaban3">Jawaban 3</label>
                                <input type="hidden" value="{{ isset($soalJawaban->answers[2]->id) === true ? $soalJawaban->answers[2]->id : '' }}" name="jawaban_id3">
                                    &emsp;&emsp;&nbsp;<input class="form-check-input{{ $errors->has('benar_salah3') ? ' is-invalid' : '' }}" type="radio" name="benar_salah3" id="answer_benar3" value="benar" {{ old('benar_salah3', isset($soalJawaban->answers[2]->benar_salah) ? $soalJawaban->answers[2]->benar_salah : null) === 'benar' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="answer_benar3">
                                    Benar
                                    </label>
                                    &nbsp;<input class="{{ $errors->has('benar_salah3') ? ' is-invalid' : '' }}" type="radio" name="benar_salah3" id="answer_salah3" value="salah" {{ old('benar_salah3', isset($soalJawaban->answers[2]->benar_salah) ? $soalJawaban->answers[2]->benar_salah : null) === 'salah' ? 'checked' : '' }}>
                                    <label for="answer_salah3">
                                        Salah
                                    </label>
                                @error('benar_salah3')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <textarea class="{{ $errors->has('jawaban3') ? ' is-invalid' : '' }}" name="jawaban3" id="editor4" required>{{ old('jawaban3', isset($soalJawaban->answers[2]->jawaban) ? $soalJawaban->answers[2]->jawaban : null) }}</textarea>
                                @if ($errors->has('jawaban3'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('jawaban3') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-6 pe-0">
                            <div class="form-group">
                                <label for="jawaban4">Jawaban 4</label>
                                <input type="hidden" value="{{ isset($soalJawaban->answers[3]->id) === true ? $soalJawaban->answers[3]->id : '' }}" name="jawaban_id4">
                                    &emsp;&emsp;&nbsp;<input class="form-check-input{{ $errors->has('benar_salah4') ? ' is-invalid' : '' }}" type="radio" name="benar_salah4" id="answer_benar4" value="benar" {{ old('benar_salah4', isset($soalJawaban->answers[3]->benar_salah) ? $soalJawaban->answers[3]->benar_salah : null) === 'benar' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="answer_benar4">
                                    Benar
                                    </label>
                                    &nbsp;<input class="{{ $errors->has('benar_salah4') ? ' is-invalid' : '' }}" type="radio" name="benar_salah4" id="answer_salah4" value="salah" {{ old('benar_salah4', isset($soalJawaban->answers[3]->benar_salah) ? $soalJawaban->answers[3]->benar_salah : null) === 'salah' ? 'checked' : '' }}>
                                    <label for="answer_salah4">
                                        Salah
                                    </label>
                                @error('benar_salah4')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <textarea class="{{ $errors->has('jawaban4') ? ' is-invalid' : '' }}" name="jawaban4" id="editor5" required>{{ old('jawaban4', isset($soalJawaban->answers[3]->jawaban) ? $soalJawaban->answers[3]->jawaban : null) }}</textarea>
                                @if ($errors->has('jawaban4'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('jawaban4') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('soal-jawaban.index') }}" class="btn btn-danger"><i class="bi bi-back"></i> Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-stickies"></i> Update</button>
            </form>

        </div>
    </div>
</div>


</div>
</div>

<script>
    const testableType = document.querySelector('#testable_type');
    const divTestablePre = document.querySelector('#div_testable_pre');
    const divTestablePost = document.querySelector('.div_testable_post');
    const divTestableCourse = document.querySelector('.div_testable_course');

    const toggleTestableId = () => {
        if (testableType.value === 'post-test') {
            divTestablePre.setAttribute('hidden', 'hidden');
            divTestablePost.removeAttribute('hidden');
            divTestableCourse.setAttribute('hidden','hidden');
        } else if (testableType.value === 'pre-test') {
            divTestablePost.setAttribute('hidden', 'hidden');
            divTestablePre.removeAttribute('hidden');
            divTestableCourse.setAttribute('hidden','hidden');
        } else if (testableType.value === 'course-test'){
            divTestablePre.setAttribute('hidden', 'hidden');
            divTestableCourse.removeAttribute('hidden');
            divTestablePost.setAttribute('hidden','hidden');
        } else {
            alert('Terjadi kesalahan!');
        }
    };
</script>
<script>
    CKEDITOR.replace('editor1');
    CKEDITOR.replace('editor2');
    CKEDITOR.replace('editor3');
    CKEDITOR.replace('editor4');
    CKEDITOR.replace('editor5');
</script>
@endsection