@extends('layouts.user')

@section('title', 'Publication')

@section('content')
<div class="max-w-3xl mx-auto py-6">
    <a href="{{ route('social.timeline') }}" class="text-emerald-600 hover:underline mb-4 inline-block">← Retour au fil</a>
    @include('social.partials.post-card', ['post' => $post])
</div>
@endsection