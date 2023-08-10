@csrf

<div class="row">

    <div class="col-md-6">
        <label for="type">Type</label>
        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
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
        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="tags">Tags</label>
        <select class="form-select @error('tags') is-invalid @enderror" id="tags" name="tags[]" multiple>
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
        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
        @enderror
    </div>

    <div class="col-md-12">
        <label for="title">{{ trans('jirai::messages.field_title') }}</label>
        <input
            type="text" id="title" name="title" required maxlength="100"
            class="form-control @error('title') is-invalid @enderror"
            @if(isset($issue)) value="{{ $issue->title }}" @endif>

        @error('title')
        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
        @enderror
    </div>

    @error('message')
    <div class="col-md-12">
        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
    </div>
    @enderror

    <div class="col-md-12 mt-4">
        <textarea name="message" data-initialValue="@if(isset($issue)) {{ $issue->message }}@endif" id="markdownEditor"></textarea>
    </div>

</div>

@include('jirai::editor')
