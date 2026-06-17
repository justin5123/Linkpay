@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <strong>{{ $post->user->prenom }}</strong>
                    <a href="{{ route('user.follow', $post->user) }}" class="btn btn-sm btn-secondary float-end">
                        {{ Auth::user()->isFollowing($post->user) ? 'Ne plus suivre' : 'Suivre' }}
                    </a>
                </div>
                <div class="card-body">
                    <p>{{ $post->contenu }}</p>
                    @if($post->media)
                        <img src="{{ asset('storage/' . $post->media) }}" class="img-fluid">
                    @endif
                </div>
                <div class="card-footer">
                    <!-- Likes, commentaires, partages similaires à la timeline -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection