@extends('layouts.app')

@section('title', trans('jirai::messages.title'))

@section('content')
    <div class="container content">
        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ route('jirai.changelogs.update', $changelog) }}" method="POST">
                    @method('PUT')

                    @include('jirai::changelog._form')

                    <button type="submit" class="btn btn-primary mt-2">
                        <i class="bi bi-check-lg"></i> {{ trans('messages.actions.save') }}
                    </button>
                    <a href="{{ route('jirai.changelogs.destroy', $changelog) }}" class="btn btn-danger" data-confirm="delete">
                        <i class="bi bi-trash"></i> {{ trans('messages.actions.delete') }}
                    </a>
                </form>
            </div>
        </div>
    </div>
    @include('jirai::delete-modal')
@endsection
