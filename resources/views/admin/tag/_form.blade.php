@csrf

<div class="row">

    <div class="col-md-6">
        <label for="roles">Roles</label>

        <select class="form-select @error('roles') is-invalid @enderror" id="roles" name="roles[]" multiple>
            @foreach($roles as $role)
                <option
                    value="{{ $role->id }}"
                    @if(in_array($role->id, old('roles', isset($tag) ? $tag->roles()->pluck('roles.id')->toArray() : []))) selected @endif
                >
                    {{ $role->name }}
                </option>
            @endforeach
        </select>

        @error('roles')
        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
        @enderror
    </div>

    <div class="col-md-12">
        <label for="color">CSS classes that should be added to the tag button (<a href="https://getbootstrap.com/docs/5.0/components/buttons/">see here</a>)</label>
        <input
            placeholder="btn-warning"
            type="text" id="color" name="color" required
            class="form-control @error('color') is-invalid @enderror"
            @if(isset($tag)) value="{{ $tag->color }}" @endif>

        @error('color')
        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
        @enderror
    </div>

    <div class="col-md-12">
        <label for="name">Name of the tag (must be unique)</label>
        <input
            placeholder="Bugs related to my super server"
            type="text" id="name" name="name" required
            class="form-control @error('name') is-invalid @enderror"
            @if(isset($tag)) value="{{ $tag->name }}" @endif>

        @error('name')
        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
        @enderror
    </div>

</div>
