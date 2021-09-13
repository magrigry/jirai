@auth
@push('footer-scripts')
    <script type="text/javascript" defer>

        let modal = document.getElementById('jirai-search-modal')

        let processing = false;
        let search = document.getElementById('jirai-search-input').value;

        document.getElementById('jirai-search-input').addEventListener('input', function () {
            search = document.getElementById('jirai-search-input').value;
            processAutoComplete();
        })

        let issueUrl = function (id) {
            return '{{ route('jirai.issues.show', '%id%') }}'.replace('%id%', id);
        }

        let processAutoComplete = function () {

            if (processing || search === '') {
                return;
            }

            processing = true;

            setTimeout(function () {
                document.getElementById('jirai-search-result').innerHTML = '<small style="color: grey">{{ trans('jirai::messages.no_result') }}</small>';

                axios.post('{{ route("jirai.issues.fetch") }}', {
                    search: search
                }).then(function (response) {
                    response.data.forEach(item => {
                        document.getElementById('jirai-search-result').innerHTML = '<small style="color: grey">{{ trans('jirai::messages.no_result') }}</small>';
                        let li = document.createElement("a");
                        li.innerHTML = `
                            <div>
                                <span style='color: grey'>#${item.id}</span> ${item.title}
                            </div>`;
                        li.className = 'list-group-item list-group-item-action ';
                        li.className += item.closed == 0 ? 'list-group-item-success' : 'list-group-item-danger'
                        li.href = issueUrl(item.id)
                        document.getElementById('jirai-search-result').appendChild(li);
                    })
                }).then(function () {
                    processing = false;
                }).catch(function (error) {
                    document.getElementById('jirai-search-result').innerHTML = '<strong style="color: red">{{ trans('jirai::messages.search_error') }}</strong>';
                })
            }, 1000)
        }

        window.addEventListener("keydown", function (e) {
            if (e.keyCode === 114 || (e.ctrlKey && e.keyCode === 70)) {
                e.preventDefault();
                $('#jirai-search-modal').modal('show');
            }

            if(e.key === "Escape") {
                $('#jirai-search-modal').modal('hide');
            }
        });
    </script>
@endpush

<div class="modal" id="jirai-search-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ trans('jirai::messages.search_placeholder') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="text" id="jirai-search-input" class="form-control mb-4" placeholder="{{ trans('jirai::messages.search_placeholder') }}">
                <ul id="jirai-search-result" class="list-group">

                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('messages.actions.close') }}</button>
            </div>
        </div>
    </div>
</div>
@endauth
