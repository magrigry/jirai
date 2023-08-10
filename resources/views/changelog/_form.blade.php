@csrf

<div class="row">

    <div class="col-md-12">
        <label for="message">{{ trans('jirai::messages.field_description') }}</label>
        <input
            type="text" id="title" name="description" required
            class="form-control @error('description') is-invalid @enderror"
            @if(isset($changelog)) value="{{ $changelog->description }}" @endif>

        @error('description')
        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
        @enderror
    </div>

    @error('message')
    <div class="col-md-12">
        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
    </div>
    @enderror

    <div class="col-md-12 mt-4">
        <textarea name="message" data-initialValue="@if(isset($changelog)) {{ $changelog->message }}@endif" id="markdownEditor"></textarea>
    </div>

    <div class="col-md-12">
        <label>{{ trans('jirai::messages.issues_to_close') }}</label>
        <select class="form-control form-select" name="issues[]" multiple>
            @foreach($issues as $issue)
                <option value="{{ $issue->id }}">
                    #{{ $issue->id }} | {{ \Azuriom\Plugin\Jirai\Models\JiraiIssue::TYPES[$issue->type] }}  | {{ $issue->title }}
                </option>
            @endforeach
        </select>
    </div>

</div>

@include('jirai::editor')
