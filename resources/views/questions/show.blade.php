@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <div class="d-flex align-items-center">
                                <h1>{{ $question->title}} </h1>
                                <div class="ml-auto">
                                    <a href="{{route('questions.index')}}" class="btn btn-outline-secondary">Back to all question</a>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="media">
                            <div class="d-flex flex-column vote-controls">
                                <a title="This question is useful" onclick="event.preventDefault(); document.getElementById('up-vote-question-{{$question->id}}').submit();"
                                   class="vote-up {{Auth::guest() ? 'off' : ''}}">
                                    <i class="fas fa-caret-up fa-2x"></i>
                                </a>
                                <form action="/questions/{{$question->id}}/vote" id="up-vote-question-{{$question->id}}" method="post" style="display: none;">
                                    @csrf
                                    <input type="hidden" name="vote" value="1">
                                </form>
                                <span class="votes-count">{{$question->votes_count}}</span>
                                <a title="This question is not useful" onclick="event.preventDefault(); document.getElementById('down-vote-question-{{$question->id}}').submit();"
                                   class="vote-down {{Auth::guest() ? 'off' : ''}}">
                                    <i class="fas fa-caret-down fa-2x"></i>
                                </a>
                                <form action="/questions/{{$question->id}}/vote" id="down-vote-question-{{$question->id}}" method="post" style="display: none;">
                                    @csrf
                                    <input type="hidden" name="vote" value="-1">
                                </form>
                                <a title="Click to mark as favorite question (Click again to undo)" class="favorite mt-2
                                    {{Auth::guest() ? 'off' : ($question->is_favorited ? 'favorited': '')}}"
                                    onclick="event.preventDefault(); document.getElementById('favorite-question-{{$question->id}}').submit();">
                                    <i class="fas fa-star fa-2x"></i>
                                    <span class="favorites-count">{{$question->favorite_count}}</span>
                                </a>
                                <form action="/questions/{{$question->id}}/favorites" id="favorite-question-{{$question->id}}" method="post" style="display: none;">
                                    @csrf
                                    @if($question->is_favorited)
                                        @method('delete')
                                    @endif
                                </form>
                            </div>
                            <div class="media-body">
                                {!! $question->body_html !!}
                                <div class="float-right">
                                    <span class="text-muted">Asked {{$question->created_date}}</span>
                                    <div class="media mt-2">
                                        <a href="{{$question->user->url}}" class="pr-2">
                                            <img src="{{$question->user->avatar}}" alt="">
                                            <div class="media-body mt-2">
                                                <a href="{{$question->user->url}}">{{$question->user->name}}</a>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('answers._index',[
            'answersCount'=>$question->answers_count,
            'answers'=>$question->answers
        ])
        @include('answers._create')
    </div>
@endsection
