@extends('admin.layouts.admin')

@section('title', trans('jirai::admin.settings.title'))

@section('content')
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('jirai.admin.tags.store') }}" method="POST">

                @include('jirai::admin/tag/._form')

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ trans('messages.actions.save') }}
                </button>
            </form>
        </div>
    </div>
@include('jirai::delete-modal')
@endsection
