@csrf

<div class="form-row">

    <div class="form-group col-md-6">
        <label for="type">Type</label>
        <select class="custom-select @error('type') is-invalid @enderror" id="type" name="type" required>
            @foreach($types as $typeId => $typeTrad)
                <option
                    value="{{ $typeId }}"
                    @if( old('type', isset($issue) ? $issue->type : '') == $typeId) selected @endif
                    @if( isset($preSelectedType) && $preSelectedType == $typeId) selected @endif>
                    {{ $typeTrad }}
                </option>
            @endforeach
        </select>

        @error('type')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="form-group col-md-6">
        <label for="tags">tags</label>
        <select class="custom-select @error('tags') is-invalid @enderror" id="tags" name="tags[]" multiple>
            @foreach($tags as $tag)
                <option
                    value="{{ $tag->id }}"
                    @if(in_array($tag->id, old('tags', isset($issue) ? $issue->jiraiTags()->pluck('jirai_tags.id')->toArray() : []))) selected @endif
                >
                    {{ $tag->name }}
                </option>
            @endforeach
        </select>

        @error('tags')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="form-group col-md-12">
        <label for="message">{{ trans('jirai::messages.field_title') }}</label>
        <input
            type="text" id="title" name="title" required maxlength="100"
            class="form-control @error('title') is-invalid @enderror"
            @if(isset($issue)) value="{{ $issue->title }}" @endif>

        @error('title')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    @error('message')
    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
    @enderror

    <div class="form-group col-md-12">
        <textarea name="message" data-initialValue="@if(isset($issue)) {{ $issue->message }}@endif" id="markdownEditor"></textarea>
    </div>

</div>

@include('jirai::editor')
