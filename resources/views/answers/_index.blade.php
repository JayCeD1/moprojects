<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h2>{{$answersCount . " ". Str::plural('answer',$answersCount)}}</h2>
                </div>
                <hr>
                @include('layouts._messages')
                @foreach($answers as $answer)
                    <div class="media">
                        <div class="d-flex flex-column vote-controls">
                            <a title="This answer is useful" onclick="event.preventDefault(); document.getElementById('up-vote-question-{{$answer->id}}').submit();"
                               class="vote-up {{Auth::guest() ? 'off' : ''}}">
                                <i class="fas fa-caret-up fa-2x"></i>
                            </a>
                            <form action="/answers/{{$answer->id}}/vote" id="up-vote-question-{{$answer->id}}" method="post" style="display: none;">
                                @csrf
                                <input type="hidden" name="vote" value="1">
                            </form>
                            <span class="votes-count">{{$answer->votes_count}}</span>
                            <a title="This answer is not useful" onclick="event.preventDefault(); document.getElementById('down-vote-question-{{$answer->id}}').submit();"
                               class="vote-down {{Auth::guest() ? 'off' : ''}}">
                                <i class="fas fa-caret-down fa-2x"></i>
                            </a>
                            <form action="/answers/{{$answer->id}}/vote" id="down-vote-question-{{$answer->id}}" method="post" style="display: none;">
                                @csrf
                                <input type="hidden" name="vote" value="-1">
                            </form>
                            @can('accept',$answer)
                            <a title="Mark this as best answer"
                               class="{{$answer->status}} mt-2 favorited" onclick="event.preventDefault(); document.getElementById('accept-answer-{{$answer->id}}').submit();">
                                <i class="fas fa-check fa-2x"></i>
                                <span class="favorites-count">123</span>
                            </a>
                            <form action="{{route('answers.accept',$answer->id)}}" id="accept-answer-{{$answer->id}}" method="post" style="display: none;">
                                @csrf
                            </form>
                            @else
                                @if($answer->is_best)
                                    <a title="The question owner accepted this answer as best answer"
                                       class="{{$answer->status}} mt-2 favorited">
                                        <i class="fas fa-check fa-2x"></i>
                                        <span class="favorites-count">123</span>
                                    </a>
                                @endif
                            @endcan
                        </div>
                        <div class="media-body">
                            {!! $answer->body_html !!}
                            <div class="row">
                                <div class="col-4">
                                    <div class="ml-auto">
                                        @can('update',$answer)
                                            <a href="{{route('questions.answers.edit',[$question->id,$answer->id])}}" class="btn btn-sm btn-outline-info">Edit</a>
                                        @endcan
                                        @can('delete',$answer)
                                            <form action="{{route('questions.answers.destroy',[$question->id,$answer->id])}}" class="form-delete" method="post">
                                                @method('delete')
                                                @csrf
                                                <button class="btn btn-sm btn-outline-danger" type="submit" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        @endcan
                                    </div>
                                </div>
                                <div class="col-4"></div>
                                <div class="col-4">
                                    <span class="text-muted">Answered {{$answer->created_date}}</span>
                                    <div class="media mt-2">
                                        <a href="{{$answer->user->url}}" class="pr-2">
                                            <img src="{{$answer->user->avatar}}" alt="">
                                            <div class="media-body mt-2">
                                                <a href="{{$answer->user->url}}">{{$answer->user->name}}</a>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                    <hr>
                @endforeach
            </div>
        </div>
    </div>
</div>
