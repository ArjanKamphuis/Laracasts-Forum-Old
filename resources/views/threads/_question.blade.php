<div class="card mb-3" v-if="editing">
    <div class="card-header">
        <input type="text" class="form-control" v-model="form.title">
    </div>
    <div class="card-body">
        <div class="form-group">
            <textarea class="form-control" rows="10" v-model="form.body"></textarea>
        </div>
        
    </div>
    <div class="card-footer">
        <div class="level">
            <button class="btn btn-sm btn-primary" @click="update">Update</button>
            <button class="btn btn-sm btn-link" @click="resetForm">Cancel</button>
            @can ('update', $thread)
                <form action="{{ $thread->path() }}" method="POST" class="ml-auto">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this thread?')">Delete Thread</button>
                </form>
            @endcan
        </div>
    </div>
</div>

<div class="card mb-3" v-else>
    <div class="card-header">
        <div class="level">
            <img src="{{ $thread->creator->avatar_path }}" alt="{{ $thread->creator->name }}" width="25" height="25" class="mr-2">
            <div class="flex">
                <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a> posted: <span v-text="title">
            </div>
        </div>
    </div>
    <div class="card-body" v-text="body"></div>
    <div class="card-footer" v-if="authorize('owns', thread)">
        <button class="btn btn-info btn-sm" @click="editing = true">Edit</button>
    </div>
</div>
