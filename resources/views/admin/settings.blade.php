@extends('admin.layouts.admin')

@section('title', trans('jirai::admin.settings.title'))

@section('content')
    <form action="{{ route('jirai.admin.settings') }}" method="POST">
        @csrf
        <div class="card shadow mb-4">
            <div class="card-body">

                @foreach($settings as $setting)
                    <div class="form-group">

                        @error($setting->getName())
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror

                        <label for="{{ $setting->getName() }}">{{ trans($setting->getTranslationKey()) }}</label>

                        <input class="form-control @error($setting->getName()) is-invalid @enderror"
                               id="{{ $setting->getName() }}"
                               placeholder="{{ trans($setting->getTranslationKey()) }}"
                               name="{{ trans($setting->getName()) }}"
                               value="{{ $setting->getValue() }}"
                        >
                    </div>
                @endforeach

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> {{ trans('messages.actions.save') }}
                </button>
            </div>
        </div>
    </form>
@endsection
