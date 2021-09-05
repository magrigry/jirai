<link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css">
<script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>


<script>
    var easyMDE = new EasyMDE({
        element: document.getElementById('markdownEditor'),
        forceSync: true,
        previewImagesInEditor: true,
        placeholder: "Message",
        uploadImage: true,
        insertTexts: {
            image: ["[](", ")"],
        },
        imageUploadFunction: function (file, onSuccess, onError) {
            var reader = new FileReader();
            reader.readAsDataURL(file);

            reader.onload = function () {
                onSuccess(reader.result)
            };

            reader.onerror = function (error) {
                console.log('Error: ', error);
            };
        }
    });

    if (document.getElementById('markdownEditor').hasAttribute("data-initialValue")) {
        easyMDE.value(document.getElementById('markdownEditor').getAttribute("data-initialValue"))
    }

    easyMDE.codemirror.on("inputRead", function(codeMirror, obj){
        console.log(codeMirror)
    });

</script>
