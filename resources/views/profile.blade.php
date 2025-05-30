@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    @if(request()->routeIs('profile.edit'))
        @include('components.profile.edit')
    @else
        @include('components.profile.show')
    @endif
</div>
@endsection
