@extends('layouts.app')

@section('title', trans('jirai::messages.title'))

@section('content')
    <div class="container content">
        <div class="card shadow-sm mb-4">

            <div class="card-header d-flex flex-row justify-content-between">
                <span>{{ trans('jirai::messages.changelog') }}</span>
                @auth @can('jirai.changelog.post')
                    <a href="{{ route('jirai.changelogs.create') }}" class="btn btn-primary">{{ trans('jirai::messages.new') }}</a>
                @endcan @endauth
            </div>

            <div class="card-body">
                @foreach($changelogs as $changelog)
                    <div class="card m-2 p-2 text-decoration-none d-flex flex-column">
                        <a class="m-2" href="{{ route('jirai.changelogs.show', $changelog) }}">
                            <h3>{{ $changelog->description }}</h3>
                        </a>
                        <small style="color: grey">
                            #{{ $changelog->id }} @lang('messages.posts.posted', ['user' => e($changelog->user->name), 'date' => format_date($changelog->created_at, true)])
                        </small>
                    </div>
                @endforeach
                {{ $changelogs->withQueryString()->links() }}
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header d-flex justify-content-between">
                        <span>{{ trans('jirai::messages.bugs') }}</span>
                        @auth @can('jirai.issue.post')
                        <a href="{{ route('jirai.issues.create') }}?type=bugs" class="btn btn-primary">{{ trans('jirai::messages.new') }}</a>
                        @endauth @endcan
                    </div>
                    <div class="card-body">
                        @foreach($bugs as $bug)
                            <div class="card m-2 p-2 text-decoration-none">
                                <div>
                                    @if ($bug->closed)
                                        <button class="btn btn-danger btn-sm font-weight-bold">{{ trans('jirai::messages.closed') }}</button>
                                    @else
                                        <button class="btn btn-success btn-sm rounded-pill font-weight-bold">{{ trans('jirai::messages.open') }}</button>
                                    @endif

                                    @foreach($bug->jiraiTags as $tag)
                                        <button class="btn {{ $tag->color }} rounded-pill font-weight-bold btn-sm">{{ $tag->name }}</button>
                                    @endforeach
                                </div>
                                <hr>
                                <div class="row">
                                    <h5 class="col-10">
                                        <a href="{{ route('jirai.issues.show', ['issue' => $bug->id]) }}">
                                            {{ $bug->title }}
                                        </a>
                                    </h5>
                                    <a href="{{ route('jirai.issues.show', ['issue' => $bug->id]) }}">
                                        <span class="col-2">{{ $bug->messages->count() }} <i class="fas fa-comment"></i></span>
                                    </a>
                                </div>
                                <small style="color: grey">
                                    #{{ $bug->id }} @lang('messages.posts.posted', ['user' => e($bug->user->name), 'date' => format_date($bug->created_at, true)])
                                </small>
                            </div>
                        @endforeach
                        {{ $bugs->withQueryString()->links() }}
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header d-flex justify-content-between">
                        <span>{{ trans('jirai::messages.suggestions') }}</span>
                        @auth @can('jirai.issue.post')
                            <a href="{{ route('jirai.issues.create') }}?type=suggestion" class="btn btn-primary">{{ trans('jirai::messages.new') }}</a>
                        @endauth @endcan
                    </div>
                    <div class="card-body">
                        @foreach($suggestions as $suggestion)
                            <div class="card m-2 p-2 text-decoration-none">
                                <div>
                                    @if ($suggestion->closed)
                                        <button class="btn btn-danger btn-sm font-weight-bold">{{ trans('jirai::messages.closed') }}</button>
                                    @else
                                        <button class="btn btn-success btn-sm rounded-pill font-weight-bold">{{ trans('jirai::messages.open') }}</button>
                                    @endif

                                    @foreach($suggestion->jiraiTags as $tag)
                                        <button class="btn {{ $tag->color }} rounded-pill font-weight-bold btn-sm" >{{ $tag->name }}</button>
                                    @endforeach
                                </div>
                                <hr>
                                <div class="row">
                                    <h5 class="col-10">
                                        <a href="{{ route('jirai.issues.show', ['issue' => $suggestion->id]) }}">
                                            {{ $suggestion->title }}
                                        </a>
                                    </h5>
                                    <a href="{{ route('jirai.issues.show', ['issue' => $suggestion->id]) }}">
                                        <span class="col-2">{{ $suggestion->messages->count() }} <i class="fas fa-comment"></i></span>
                                    </a>
                                </div>
                                <small style="color: grey">
                                    #{{ $suggestion->id }} @lang('messages.posts.posted', ['user' => e($suggestion->user->name), 'date' => format_date($suggestion->created_at, true)])
                                </small>
                            </div>
                        @endforeach
                        {{ $suggestions->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('jirai::search')
