<!-- Delete confirm modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="confirmDeleteLabel">{{ trans('messages.comments.delete-title') }}</h2>
                <button class="close" type="button" data-dismiss="modal" aria-label="{{ trans('messages.actions.close') }}">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">{{ trans('messages.comments.delete-description') }}</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">{{ trans('messages.actions.cancel') }}</button>

                <form id="confirmDeleteForm" method="POST">
                    @method('DELETE')
                    @csrf

                    <button class="btn btn-danger" type="submit">{{ trans('messages.actions.delete') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
