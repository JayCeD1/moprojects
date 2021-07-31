@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <h1>Editing Answer for question: <strong>{{$question->title}}</strong></h1>
                        </div>
                        <hr>
                        <form action="{{route('questions.answers.update',[$question,$answer])}}" method="post">
                            @csrf
                            @method('patch')
                            <div class="form-group">
                                <textarea name="body" rows="7" class="form-control @error('body') is-invalid @enderror">{{old('body',$answer->body)}}</textarea>
                                @error('body')
                                <div class="invalid-feedback">
                                    <strong>{{$message}}</strong>
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-lg btn-outline-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

