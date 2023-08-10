@extends('admin.layouts.admin')

@section('title', trans('jirai::admin.settings.title'))

@section('content')
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('jirai.admin.tags.update', $tag) }}" method="POST">
                @method('PUT')

                @include('jirai::admin.tag._form')

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> {{ trans('messages.actions.save') }}
                </button>

                <a href="{{ route('jirai.admin.tags.destroy', $tag) }}" class="btn btn-danger" data-confirm="delete">
                    <i class="bi bi-trash"></i> {{ trans('messages.actions.delete') }}
                </a>
            </form>
        </div>
    </div>
    @include('jirai::delete-modal')
@endsection
