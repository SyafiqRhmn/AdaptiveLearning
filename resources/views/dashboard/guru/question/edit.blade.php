@extends('layouts.main')
@section('container')

<div class="wrapper d-flex align-items-stretch">
  @include('partials.sidebar')
  <div id="content" class="p-md-3">
    @include('partials.navbar_guru')


<div class="container my-5">
    <div class="row">
        <div class="col-8">

            <form method="POST" action="{{ route('question.update', $question->id) }}">
                @method('PUT')
                @csrf
                <div class="form-group">
                    <label for="question">Soal</label>
                    <textarea class="{{ $errors->has('question') ? ' is-invalid' : '' }}" name="question" id="editor1" required>{{ old('question',$question->question) }}</textarea>
                    @if ($errors->has('question'))
                        <div class="invalid-feedback">
                            {{ $errors->first('question') }}
                        </div>
                    @endif
                </div>
            
                <div class="form-group">
                    <label for="testable_type">Jenis Test</label>
                    <select class="form-control" id="testable_type" name="testable_type" onchange="toggleTestableId()">
                        <option disabled selected>(Pilih Test)</option>
                        <option value="pre-test" {{ old('testable_type', $question->testable_type) === 'pre-test' ? 'selected' : '' }}>Pre Test</option>
                        <option value="post-test" {{ old('testable_type', $question->testable_type) === 'post-test' ? 'selected' : '' }}>Post Test</option>
                    </select>
                    @error('testable_type')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group" id="div_testable_pre" {{ old('testable_type', $question->testable_type) !== 'post-test' ? 'hidden' : '' }}>
                    <label for="testable_id">Test (Pre-Test)</label>
                    <select class="form-control" id="testable_id" name="testable_id">
                        <option disabled selected>(Pilih Pre-Test)</option>
                        @foreach ($preTests as $preTest)
                            <option value="{{ $preTest->id }}" {{ old('testable_id', $question->testable_id) === $preTest->id ? 'selected' : '' }}>{{ $preTest->name }}</option>
                        @endforeach
                    </select>
                    @error('testable_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group div_testable_post" id="div_testable_post" {{ old('testable_type', $question->testable_type) !== 'pre-test' ? 'hidden' : '' }}>
                    <label for="testable_id">Test (Post-Test)</label>
                    <select class="form-control" id="testable_id" name="testable_id">
                        <option disabled selected>(Pilih Post-Test)</option>
                        @foreach ($postTests as $postTest)
                            <option value="{{ $postTest->id }}" {{ old('testable_id', $question->testable_id) === $postTest->id ? 'selected' : '' }}>{{ $postTest->name }}</option>
                        @endforeach
                    </select>
                    @error('testable_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <a href="{{ route('question.index') }}" class="btn btn-danger"><i class="bi bi-back"></i> Cancel</a>
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

    const toggleTestableId = () => {
        if (testableType.value === 'post-test') {
            divTestablePre.setAttribute('hidden', 'hidden');
            divTestablePost.removeAttribute('hidden');
        } else if (testableType.value === 'pre-test') {
            divTestablePost.setAttribute('hidden', 'hidden');
            divTestablePre.removeAttribute('hidden');
        } else {
            alert('Terjadi kesalahan!');
        }
    };
</script>
<script>
    CKEDITOR.replace('editor1');
</script>
@endsection