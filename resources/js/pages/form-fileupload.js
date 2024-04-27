

import 'dropzone/dist/dropzone.js';

// Dropzone
const dropzonePreviewNode = document.querySelector("#dropzone-preview-list");

if (dropzonePreviewNode) {
    const previewTemplate = dropzonePreviewNode.parentNode.innerHTML;
    dropzonePreviewNode.parentNode.removeChild(dropzonePreviewNode);
    const dropzone = new Dropzone(".dropzone", {
        url: this.url,
        method: "post",
        previewTemplate: previewTemplate,
        previewsContainer: "#dropzone-preview",
    });
}
