@extends('layouts.app')

@section('title', trans('jirai::messages.title'))

@section('content')
<div class="container content">
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('jirai.changelogs.store') }}" method="POST">

                @include('jirai::changelog._form')

                <button type="submit" class="btn btn-primary mt-2">
                    <i class="bi bi-check-lg"></i> {{ trans('messages.actions.save') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
