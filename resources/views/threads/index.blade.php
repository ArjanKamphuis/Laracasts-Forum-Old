@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @forelse ($threads as $thread)
                <div class="card mb-3">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <span class="h4"><a href="{{ $thread->path() }}">{{ $thread->title }}</a></span>
                            </div>
                            <div class="col-auto">
                                <a href="{{ $thread->path() }}"><strong>{{ $thread->replies_count }} {{ str_plural('reply', $thread->replies_count) }}</strong></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">{{ $thread->body }}</div>
                </div>
            @empty
                <p class="text-center">There are no relevant results at this time.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
