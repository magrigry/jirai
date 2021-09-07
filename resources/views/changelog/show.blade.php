@extends('layouts.app')

@section('title', trans('admin.redirects.title-create'))

@section('content')

<div class="container">

    <div class="card mt-5">
        <div class="card-header">
            <img class="rounded float-left mr-4" src="{{ $changelog->user->getAvatar() }}" alt="{{ $changelog->user->name }}" height="55">
            <h1 class="card-title">{{ $changelog->description }}</h1>
            <div class="d-flex justify-content-between align-items-center">
                <span>@lang('messages.posts.posted', ['user' => e($changelog->user->name), 'date' => format_date($changelog->created_at, true)])</span>
                <div>
                    @can('jirai.changelog.post')
                        <a class="btn btn-danger" href="{{ route('jirai.changelogs.edit', $changelog->id) }}">{{ trans('jirai::messages.edit') }}</a>
                        <a class="btn btn-danger" href="{{ route('jirai.changelogs.destroy', $changelog->id) }}" data-confirm="delete">{{ trans('jirai::messages.delete') }}</a>
                    @endcan
                </div>
                </a>
            </div>
        </div>
        <div class="card-body user-html-content mt-5">
            {!! \Azuriom\Support\Markdown::parse($changelog->message) !!}
        </div>
    </div>

</div>
@include('jirai::delete-modal')
@endsection
