@extends('layouts.app')

@section('header')
    <link href="/css/vendor/jquery.atwho.min.css" rel="stylesheet">
@endsection

@section('content')
<thread-page :thread="{{ $thread }}" inline-template>
    <div class="container">
        <div class="row">
            <div class="col-md-8" v-cloak>
                @include('threads._question')
                <replies-component @removed="repliesCount--" @added="repliesCount++"></replies-component>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <p>
                            This thread was published {{ $thread->created_at->diffForHumans() }} by 
                            <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a>, 
                            and currently has <span v-text="repliesCount"></span> {{ str_plural('comment', $thread->replies_count) }}.
                        </p>

                        <subscribe-button-component :active="{{ json_encode($thread->isSubscribedTo) }}" v-if="signedIn"></subscribe-button-component>
                        <button class="btn btn-secondary" v-if="authorize('isAdmin')" @click="toggleLock" v-text="locked ? 'Unlock' : 'Lock'"></button>
                    </div>
                </div>
            </div>
        </div>    
    </div>
</thread-page>
@endsection
