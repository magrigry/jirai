@extends('layouts.app')

@section('title', '')

@section('content')

    <div class="container content">
        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ route('jirai.messages.update', $message) }}" method="POST" >

                    @method('PUT')
                    @csrf

                    @error('message')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                    <div class="form-group col-md-12">
                        <textarea name="message" data-initialValue="{{ $message->message }}" id="markdownEditor"></textarea>
                    </div>

                    <input type="hidden" name="jirai_issue_id" value="{{ $message->jirai_issue_id }}">

                    <input type="hidden" name="message" value="{{ $message->message }}">

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ trans('messages.actions.save') }}
                    </button>

                    <a href="{{ route('jirai.messages.destroy', $message) }}" class="btn btn-danger" data-confirm="delete">
                        <i class="fas fa-trash"></i> {{ trans('messages.actions.delete') }}
                    </a>
                </form>
            </div>
        </div>
    </div>

    @include('jirai::delete-modal')
    @include('jirai::editor')

@endsection
