@extends('layouts.app')

@section('title', trans('admin.redirects.title-create'))

@section('content')
<div class="container content">
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('jirai.issues.store') }}" method="POST">

                @include('jirai::issue._form')

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ trans('messages.actions.save') }}
                </button>
            </form>
        </div>
    </div>
</div>
@include('jirai::delete-modal')
@endsection
