@csrf

<div class="form-row">

    <div class="form-group col-md-12">
        <label for="message">{{ trans('jirai::messages.field_description') }}</label>
        <input
            type="text" id="title" name="description" required
            class="form-control @error('description') is-invalid @enderror"
            @if(isset($changelog)) value="{{ $changelog->description }}" @endif>

        @error('description')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    @error('message')
    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
    @enderror

    <div class="form-group col-md-12">
        <textarea name="message" data-initialValue="@if(isset($changelog)) {{ $changelog->message }}@endif" id="markdownEditor"></textarea>
    </div>

    <div class="form-group col-md-12">
        <label>{{ trans('issues_to_close') }}</label>
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
