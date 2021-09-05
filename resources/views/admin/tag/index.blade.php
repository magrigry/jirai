@extends('admin.layouts.admin')

@section('title', trans('jirai::admin.settings.title'))

@section('content')
    <div class="card shadow mb-4">
        <div class="card-body">
            <a class="btn btn-primary" href="{{ route('jirai.admin.tags.create') }}">{{ trans('jirai::messages.new') }}</a>
            <div class="mt-5">
                @foreach($tags as $tag)
                    <a href="{{ route('jirai.admin.tags.edit', $tag) }}" class="btn {{ $tag->color }} btn-sm rounded-pill bold"> {{ $tag->name }} </a>
                @endforeach
            </div>
        </div>
    </div>
    @include('jirai::delete-modal')
@endsection
