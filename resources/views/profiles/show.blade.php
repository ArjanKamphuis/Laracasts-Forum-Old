@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="pb-2 mt-4 mb-2 border-bottom">
                <h1>
                    {{ $profileUser->name }}
                    <small>Since {{ $profileUser->created_at->diffForHumans() }}</small>
                </h1>
            </div>

            @foreach ($threads as $thread)
                <div class="card mb-3">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <a href="#">{{ $thread->creator->name }}</a> posted: {{ $thread->title }}</a>
                            </div>
                            <div class="col-auto">
                                <span>{{ $thread->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">{{ $thread->body }}</div>
                </div>
            @endforeach

            {{ $threads->links() }}
        </div>
    </div>
</div>
@endsection