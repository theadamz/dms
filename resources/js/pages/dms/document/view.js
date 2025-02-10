import {
    loadingProcess
} from "../../../general";

function initOtherElements() {
    //
}

function initActions() {
    // file card events
    $('.file-card-info').off('click');
    $('.file-card-info').on('click', previewFile);

    document.getElementById('refresh').addEventListener('click', function () {
        loadingProcess();
        window.location.reload();
    })
}

function previewFile(e) {
    const id = $(this).data('id');

    // show loading
    $('.loading-preview-file').removeClass('d-none');
    $('#filePreviewContainer').addClass('d-none');

    // load content
    $('#filePreview').load(`${_baseURL}/documents/${_id}/files/${id}/preview`, () => {

        // hide loading
        $('.loading-preview-file').addClass('d-none');
        $('#filePreviewContainer').removeClass('d-none');

    });
}

document.addEventListener("DOMContentLoaded", function () {
    initOtherElements();
    initActions();
});
