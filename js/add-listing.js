document.addEventListener("DOMContentLoaded", () => {
    const imageInput = document.getElementById("imageUpload");
    const previewContainer = document.getElementById("imagePreviewContainer");
    const popup = document.getElementById("imagePopup");
    const popupImage = document.getElementById("popupImage");
    const closePopup = document.getElementById("closePopup");

    let selectedImages = [];

    imageInput.addEventListener("change", (event) => {
        selectedImages = Array.from(event.target.files);
        renderPreviews();
    });

    function renderPreviews() {
        previewContainer.innerHTML = "";

        selectedImages.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const wrapper = document.createElement("div");
                wrapper.classList.add("image-preview");

                wrapper.innerHTML = `
                    <img src="${e.target.result}" alt="Preview" onclick="openPopup('${e.target.result}')">
                    <button type="button" class="remove-btn" data-index="${index}">&times;</button>
                `;

                previewContainer.appendChild(wrapper);
            };
            reader.readAsDataURL(file);
        });

        // Rebuild file list for input
        const dataTransfer = new DataTransfer();
        selectedImages.forEach(file => dataTransfer.items.add(file));
        imageInput.files = dataTransfer.files;
    }

    previewContainer.addEventListener("click", (e) => {
        if (e.target.classList.contains("remove-btn")) {
            const index = parseInt(e.target.getAttribute("data-index"));
            selectedImages.splice(index, 1);
            renderPreviews();
        }
    });

    function openPopup(src) {
        popupImage.src = src;
        popup.style.display = "flex";
    }

    closePopup.addEventListener("click", () => {
        popup.style.display = "none";
    });
});
