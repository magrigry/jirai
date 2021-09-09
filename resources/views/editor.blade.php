@push('scripts')

    <script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>
@endpush

@push('styles')

    <link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css">
@endpush

@push('footer-scripts')
<script type="text/javascript">
    var easyMDE = new EasyMDE({
        element: document.getElementById('markdownEditor'),
        forceSync: true,
        previewImagesInEditor: true,
        placeholder: "Message",
        spellChecker: false,
        nativeSpellcheck: true,
        autosave: {
            enabled: true,
            uniqueId: "{{ \Illuminate\Support\Facades\Request::path() }}",
            delay: 500,
            submit_delay: 5000,
            timeFormat: {
                locale: 'fr-FR',
                format: {
                    year: 'numeric',
                    month: 'long',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit',
                },
            },
            text: "Autosaved: "
        },
        @can('jirai.post.attachments')
        uploadImage: true,
        imageUploadFunction: function (file, success, failure) {
            const formData = new FormData();
            formData.append('file', file, file.name);
            axios.post('{{ route('jirai.attachments') }}', formData, {}).then(function (response) {
                success(response.data.location);
            }).catch(function (error) {
                failure(error);
            });
        },
        @endcan
    });

    if (document.getElementById('markdownEditor').hasAttribute("data-initialValue")) {
        easyMDE.value(document.getElementById('markdownEditor').getAttribute("data-initialValue"))
    }

</script>
@endpush
