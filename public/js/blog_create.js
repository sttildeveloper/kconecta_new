document.addEventListener('DOMContentLoaded', () => {
    const editorArea = document.getElementById('editorArea');
    const addTextBtn = document.getElementById('addText');
    const addImageBtn = document.getElementById('addImage');
    const addImageGalleryBtn = document.getElementById('addImageGallery');
    const bgColorPicker = document.getElementById('bgColorPicker');
    const downloadHtmlBtn = document.getElementById('downloadHtmlBtn');
    const deleteElementBtn = document.getElementById('deleteElementBtn');

    const textControlsGroup = document.querySelector('.text-controls');
    const imageControlsGroup = document.querySelector('.image-controls');
    const galleryControlsGroup = document.querySelector('.gallery-controls');

    const undoBtn = document.getElementById('undoBtn');
    const redoBtn = document.getElementById('redoBtn');
    const fontFamilySelect = document.getElementById('fontFamilySelect');
    const fontSizeSelect = document.getElementById('fontSizeSelect');
    const boldBtn = document.getElementById('boldBtn');
    const italicBtn = document.getElementById('italicBtn');
    const underlineBtn = document.getElementById('underlineBtn');
    const textColorPicker = document.getElementById('textColorPicker');
    const alignLeftBtn = document.getElementById('alignLeftBtn');
    const centerTextBtn = document.getElementById('centerTextBtn');
    const alignRightBtn = document.getElementById('alignRightBtn');
    const justifyBtn = document.getElementById('justifyBtn');
    const bulletListBtn = document.getElementById('bulletListBtn');
    const numberedListBtn = document.getElementById('numberedListBtn');
    const indentBtn = document.getElementById('indentBtn');
    const outdentBtn = document.getElementById('outdentBtn');

    const imageWidthInput = document.getElementById('imageWidthInput');
    const imageFullWidthBtn = document.getElementById('imageFullWidthBtn');
    const imageHeightInput = document.getElementById('imageHeightInput');
    const imageObjectFitCoverBtn = document.getElementById('imageObjectFitCoverBtn');
    const imageAlignLeftBtn = document.getElementById('imageAlignLeftBtn');
    const imageAlignCenterBtn = document.getElementById('imageAlignCenterBtn');
    const imageAlignRightBtn = document.getElementById('imageAlignRightBtn');
    const imageNoWrapBtn = document.getElementById('imageNoWrapBtn');

    const galleryLayoutRowBtn = document.getElementById('galleryLayoutRowBtn');
    const galleryLayoutColBtn = document.getElementById('galleryLayoutColBtn');
    const galleryGapInput = document.getElementById('galleryGapInput');
    const addImageToGalleryBtn = document.getElementById('addImageToGalleryBtn');


    let selectedElement = null;
    let savedRange = null;

    addTextBtn.addEventListener('click', () => {
        const paragraph = document.createElement('p');
        paragraph.textContent = 'Texto...';
        editorArea.appendChild(paragraph);
        paragraph.focus();
        // Al añadir, seleccionamos el párrafo y actualizamos el estado
        selectElement(paragraph);
    });

    addImageBtn.addEventListener('click', () => {
        const imageUrl = prompt('Introduce la URL de la imagen (ej: https://via.placeholder.com/200x150/cccccc/ffffff?text=Imagen):');
        if (imageUrl) {
            const img = document.createElement('img');
            img.src = imageUrl;
            img.alt = 'Imagen añadida por el usuario';
            img.style.width = '200px';
            img.style.height = 'auto';
            img.style.objectFit = 'initial';
            img.classList.add('image-no-wrap');
            editorArea.appendChild(img);
            // Al añadir, seleccionamos la imagen y actualizamos el estado
            selectElement(img);
        }
    });

    addImageGalleryBtn.addEventListener('click', () => {
        const galleryDiv = document.createElement('div');
        galleryDiv.classList.add('image-gallery');
        galleryDiv.classList.add('layout-row');
        galleryDiv.style.gap = '10px';

        editorArea.appendChild(galleryDiv);
        // Al añadir, seleccionamos la galería y actualizamos el estado
        selectElement(galleryDiv);
    });

    addImageToGalleryBtn.addEventListener('click', () => {
        if (selectedElement && selectedElement.classList.contains('image-gallery')) {
            const imageUrl = prompt('Introduce la URL de la imagen para añadir a la galería:');
            if (imageUrl) {
                const img = document.createElement('img');
                img.src = imageUrl;
                img.alt = 'Imagen de galería';
                img.style.width = '150px';
                img.style.height = '100px';
                img.style.objectFit = 'cover';
                selectedElement.appendChild(img);
            }
        } else {
            console.log('Por favor, selecciona una galería de imágenes para añadir una imagen.');
        }
    });

    bgColorPicker.addEventListener('input', (event) => {
        document.body.style.backgroundColor = event.target.value;
    });

    // Removido el listener de click principal del editorArea
    // La lógica de selección ahora se maneja principalmente por 'selectionchange'

    function selectElement(element) {
        // Deseleccionar el elemento anterior si existe y es diferente
        if (selectedElement && selectedElement !== element) {
            selectedElement.classList.remove('selected');
        }

        // Si el elemento es null, significa que no hay nada relevante seleccionado
        if (!element) {
            selectedElement = null;
        } else {
            selectedElement = element;
            selectedElement.classList.add('selected');

            // Actualizar el estado de los controles según el tipo de elemento
            if (selectedElement.tagName === 'P') {
                textColorPicker.value = rgbToHex(window.getComputedStyle(selectedElement).color);

                const currentFontSizePx = parseInt(window.getComputedStyle(selectedElement).fontSize);
                let fontSizeValue = '4';
                if (currentFontSizePx >= 48) fontSizeValue = '7';
                else if (currentFontSizePx >= 36) fontSizeValue = '7';
                else if (currentFontSizePx >= 30) fontSizeValue = '6';
                else if (currentFontSizePx >= 24) fontSizeValue = '6';
                else if (currentFontSizePx >= 20) fontSizeValue = '5';
                else if (currentFontSizePx >= 18) fontSizeValue = '5';
                else if (currentFontSizePx >= 16) fontSizeValue = '4';
                else if (currentFontSizePx >= 14) fontSizeValue = '4';
                else if (currentFontSizePx >= 12) fontSizeValue = '3';
                else if (currentFontSizePx >= 10) fontSizeValue = '2';
                else if (currentFontSizePx >= 8) fontSizeValue = '1';
                else if (currentFontSizePx >= 6) fontSizeValue = '1';
                fontSizeSelect.value = fontSizeValue;

                const currentFontFamily = window.getComputedStyle(selectedElement).fontFamily.split(',')[0].replace(/['"]/g, '');
                let foundFontOption = false;
                for (let i = 0; i < fontFamilySelect.options.length; i++) {
                    if (fontFamilySelect.options[i].value.toLowerCase() === currentFontFamily.toLowerCase()) {
                        fontFamilySelect.value = fontFamilySelect.options[i].value;
                        foundFontOption = true;
                        break;
                    }
                }
                if (!foundFontOption) {
                    fontFamilySelect.value = 'Sans-serif';
                }
                const currentTextAlign = selectedElement.style.textAlign;
                selectedElement.classList.remove('align-left', 'align-center', 'align-right', 'align-justify');
                if (currentTextAlign) {
                    selectedElement.classList.add(`align-${currentTextAlign}`);
                }

            } else if (selectedElement.tagName === 'IMG') {
                const currentWidth = parseInt(selectedElement.style.width) || selectedElement.width;
                if (currentWidth === editorArea.offsetWidth) {
                     imageWidthInput.value = '';
                     imageFullWidthBtn.classList.add('active');
                } else {
                    imageWidthInput.value = currentWidth || 200;
                    imageFullWidthBtn.classList.remove('active');
                }

                imageHeightInput.value = parseInt(selectedElement.style.height) || selectedElement.height || '150';
                if (selectedElement.style.objectFit === 'cover') {
                    imageObjectFitCoverBtn.classList.add('active');
                } else {
                    imageObjectFitCoverBtn.classList.remove('active');
                }

                const imageAlignClasses = ['image-align-left', 'image-align-center', 'image-align-right', 'image-no-wrap'];
                let currentImageAlignClass = '';
                for (const cls of imageAlignClasses) {
                    if (selectedElement.classList.contains(cls)) {
                        currentImageAlignClass = cls;
                        break;
                    }
                }
                if (!currentImageAlignClass) {
                    selectedElement.classList.add('image-no-wrap');
                }
            } else if (selectedElement.classList.contains('image-gallery')) {
                galleryGapInput.value = parseInt(selectedElement.style.gap) || 10;
                if (selectedElement.classList.contains('layout-row')) {
                    galleryLayoutRowBtn.classList.add('active');
                    galleryLayoutColBtn.classList.remove('active');
                } else if (selectedElement.classList.contains('layout-column')) {
                    galleryLayoutColBtn.classList.add('active');
                    galleryLayoutRowBtn.classList.remove('active');
                }
            }
        }
        updateControlStates();
    }

    function updateControlStates() {
        const isTextSelected = selectedElement && selectedElement.tagName === 'P';
        const isImageSelected = selectedElement && selectedElement.tagName === 'IMG';
        const isGallerySelected = selectedElement && selectedElement.classList.contains('image-gallery');

        Array.from(textControlsGroup.querySelectorAll('button, select, input[type="color"]')).forEach(control => {
            control.disabled = !isTextSelected;
        });

        Array.from(imageControlsGroup.querySelectorAll('button, input[type="number"]')).forEach(control => {
            control.disabled = !isImageSelected;
        });

        Array.from(galleryControlsGroup.querySelectorAll('button, input[type="number"]')).forEach(control => {
            control.disabled = !isGallerySelected;
        });
        addImageToGalleryBtn.disabled = !isGallerySelected;

        deleteElementBtn.disabled = !selectedElement;
    }

    deleteElementBtn.addEventListener('click', () => {
        if (selectedElement) {
            selectedElement.remove();
            selectedElement = null;
            updateControlStates();
        } else {
            console.log('Por favor, selecciona un elemento para eliminar.');
        }
    });

    textColorPicker.addEventListener('input', (event) => {
        restoreSelection();
        document.execCommand('foreColor', false, event.target.value);
    });

    undoBtn.addEventListener('click', () => {
        restoreSelection();
        document.execCommand('undo', false, null);
    });
    redoBtn.addEventListener('click', () => {
        restoreSelection();
        document.execCommand('redo', false, null);
    });

    boldBtn.addEventListener('click', () => {
        restoreSelection();
        document.execCommand('bold', false, null);
    });

    italicBtn.addEventListener('click', () => {
        restoreSelection();
        document.execCommand('italic', false, null);
    });

    underlineBtn.addEventListener('click', () => {
        restoreSelection();
        document.execCommand('underline', false, null);
    });

    fontFamilySelect.addEventListener('change', (event) => {
        restoreSelection();
        document.execCommand('fontName', false, event.target.value);
    });

    fontSizeSelect.addEventListener('change', (event) => {
        restoreSelection();
        document.execCommand('fontSize', false, event.target.value);
    });

    alignLeftBtn.addEventListener('click', () => {
        restoreSelection();
        document.execCommand('justifyLeft', false, null);
    });
    centerTextBtn.addEventListener('click', () => {
        restoreSelection();
        document.execCommand('justifyCenter', false, null);
    });
    alignRightBtn.addEventListener('click', () => {
        restoreSelection();
        document.execCommand('justifyRight', false, null);
    });
    justifyBtn.addEventListener('click', () => {
        restoreSelection();
        document.execCommand('justifyFull', false, null);
    });

    bulletListBtn.addEventListener('click', () => {
        restoreSelection();
        document.execCommand('insertUnorderedList', false, null);
    });
    numberedListBtn.addEventListener('click', () => {
        restoreSelection();
        document.execCommand('insertOrderedList', false, null);
    });

    indentBtn.addEventListener('click', () => {
        restoreSelection();
        document.execCommand('indent', false, null);
    });
    outdentBtn.addEventListener('click', () => {
        restoreSelection();
        document.execCommand('outdent', false, null);
    });

    imageWidthInput.addEventListener('input', (event) => {
        if (selectedElement && selectedElement.tagName === 'IMG') {
            selectedElement.style.width = event.target.value + 'px';
            imageFullWidthBtn.classList.remove('active');
        }
    });

    imageFullWidthBtn.addEventListener('click', () => {
        if (selectedElement && selectedElement.tagName === 'IMG') {
            selectedElement.style.width = '100%';
            imageWidthInput.value = '';
            imageFullWidthBtn.classList.add('active');
        }
    });

    imageHeightInput.addEventListener('input', (event) => {
        if (selectedElement && selectedElement.tagName === 'IMG') {
            selectedElement.style.height = event.target.value + 'px';
        }
    });

    imageObjectFitCoverBtn.addEventListener('click', () => {
        if (selectedElement && selectedElement.tagName === 'IMG') {
            if (selectedElement.style.objectFit === 'cover') {
                selectedElement.style.objectFit = 'initial';
                imageObjectFitCoverBtn.classList.remove('active');
            } else {
                selectedElement.style.objectFit = 'cover';
                imageObjectFitCoverBtn.classList.add('active');
            }
        }
    });

    function clearImageAlignClasses(img) {
        img.classList.remove('image-align-left', 'image-align-center', 'image-align-right', 'image-no-wrap');
    }

    imageAlignLeftBtn.addEventListener('click', () => {
        if (selectedElement && selectedElement.tagName === 'IMG') {
            clearImageAlignClasses(selectedElement);
            selectedElement.classList.add('image-align-left');
        }
    });

    imageAlignCenterBtn.addEventListener('click', () => {
        if (selectedElement && selectedElement.tagName === 'IMG') {
            clearImageAlignClasses(selectedElement);
            selectedElement.classList.add('image-align-center');
        }
    });

    imageAlignRightBtn.addEventListener('click', () => {
        if (selectedElement && selectedElement.tagName === 'IMG') {
            clearImageAlignClasses(selectedElement);
            selectedElement.classList.add('image-align-right');
        }
    });

    imageNoWrapBtn.addEventListener('click', () => {
        if (selectedElement && selectedElement.tagName === 'IMG') {
            clearImageAlignClasses(selectedElement);
            selectedElement.classList.add('image-no-wrap');
        }
    });

    galleryLayoutRowBtn.addEventListener('click', () => {
        if (selectedElement && selectedElement.classList.contains('image-gallery')) {
            selectedElement.classList.remove('layout-column');
            selectedElement.classList.add('layout-row');
        }
    });

    galleryLayoutColBtn.addEventListener('click', () => {
        if (selectedElement && selectedElement.classList.contains('image-gallery')) {
            selectedElement.classList.remove('layout-row');
            selectedElement.classList.add('layout-column');
        }
    });

    galleryGapInput.addEventListener('input', (event) => {
        if (selectedElement && selectedElement.classList.contains('image-gallery')) {
            selectedElement.style.gap = event.target.value + 'px';
        }
    });

    downloadHtmlBtn.addEventListener('click', () => {
        const editorContentClone = editorArea.cloneNode(true);

        editorContentClone.querySelectorAll('.selected').forEach(el => {
            el.classList.remove('selected');
        });

        const editorContent = editorContentClone.innerHTML;
        openModal(document.getElementById("modal-create-article"));

        fetch('/css/app/blog_create.css')
            .then(response => response.text())
            .then(cssContent => {
                const fullHtml = `<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Página Web Exportada</title>
    <style>
        ${cssContent}

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: ${document.body.style.backgroundColor || '#f4f4f4'};
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            color: #333;
        }
        .exported-content {
            background-color: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 900px;
            box-sizing: border-box;
            line-height: 1.7;
        }
        .exported-content img {
            max-width: 100%;
            height: auto;
            display: inline-block;
            vertical-align: top;
            margin: 5px 0;
            border-radius: 6px;
        }
        .exported-content .align-left { text-align: left; }
        .exported-content .align-center { text-align: center; }
        .exported-content .align-right { text-align: right; }
        .exported-content .align-justify { text-align: justify; }

        .exported-content .image-align-left { float: left; margin-right: 15px; margin-bottom: 10px; }
        .exported-content .image-align-center { display: block; margin-left: auto; margin-right: auto; float: none; clear: both; margin-bottom: 10px; }
        .exported-content .image-align-right { float: right; margin-left: 15px; margin-bottom: 10px; }
        .exported-content .image-no-wrap { display: block; margin-left: auto; margin-right: auto; float: none; clear: both; margin-bottom: 10px; }

        .exported-content .image-gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding: 10px;
            border: 1px dashed #ccc;
            border-radius: 8px;
            margin: 15px 0;
            justify-content: center;
            align-items: flex-start;
        }
        .exported-content .image-gallery.layout-row { flex-direction: row; }
        .exported-content .image-gallery.layout-column { flex-direction: column; align-items: center; }
    </style>
</head>
<body>
    <div class="exported-content">
        ${editorContent}
    </div>
</body>
</html>`;

                // const blob = new Blob([fullHtml], { type: 'text/html' });
                // const url = URL.createObjectURL(blob);

                // const a = document.createElement('a');
                // a.href = url;
                // a.download = 'mi_pagina_web.html';
                // document.body.appendChild(a);
                // a.click();
                // document.body.removeChild(a);
                // URL.revokeObjectURL(url);
            })
            .catch(error => console.error('Error al cargar el CSS para exportar:', error));
    });

    // Usar el evento 'selectionchange' en el documento para una captura más robusta de la selección
    document.addEventListener('selectionchange', () => {
        const selection = window.getSelection();
        let newSelectedElement = null;

        if (selection.rangeCount > 0) {
            const range = selection.getRangeAt(0);
            // Solo guardar el rango si la selección está dentro del editorArea
            if (editorArea.contains(range.commonAncestorContainer)) {
                savedRange = range;

                // Determinar el elemento principal basado en la selección
                const commonAncestor = range.commonAncestorContainer;
                // Si el ancestro común es un nodo de texto, buscamos su padre
                const targetNode = commonAncestor.nodeType === Node.TEXT_NODE ? commonAncestor.parentNode : commonAncestor;

                // Buscar el elemento principal (P, IMG, .image-gallery)
                if (targetNode.tagName === 'P' && editorArea.contains(targetNode)) {
                    newSelectedElement = targetNode;
                } else if (targetNode.tagName === 'IMG' && editorArea.contains(targetNode)) {
                    newSelectedElement = targetNode;
                } else if (targetNode.classList.contains('image-gallery') && editorArea.contains(targetNode)) {
                    newSelectedElement = targetNode;
                } else {
                    // Si el targetNode no es uno de los principales, busca el más cercano dentro del editorArea
                    const closestParagraph = targetNode.closest('p');
                    const closestImage = targetNode.closest('img');
                    const closestGallery = targetNode.closest('.image-gallery');

                    if (closestParagraph && editorArea.contains(closestParagraph)) {
                        newSelectedElement = closestParagraph;
                    } else if (closestImage && editorArea.contains(closestImage)) {
                        newSelectedElement = closestImage;
                    } else if (closestGallery && editorArea.contains(closestGallery)) {
                        newSelectedElement = closestGallery;
                    }
                }
            } else {
                savedRange = null; // La selección está fuera del editorArea
            }
        } else {
            savedRange = null; // No hay selección
        }

        // Si el elemento seleccionado ha cambiado, actualizamos la UI
        if (newSelectedElement !== selectedElement) {
            if (selectedElement) {
                selectedElement.classList.remove('selected');
            }
            selectElement(newSelectedElement); // Llama a selectElement para actualizar selectedElement y los controles
        } else if (!newSelectedElement && selectedElement) {
            // Caso donde se deselecciona todo o la selección sale del editor
            selectedElement.classList.remove('selected');
            selectedElement = null;
            updateControlStates(); // Asegurarse de deshabilitar los controles
        } else if (newSelectedElement && newSelectedElement === selectedElement) {
            // Si el mismo elemento sigue seleccionado, solo actualiza los controles (ej. si cambias el color de texto)
            updateControlStates();
        }
    });


    function restoreSelection() {
        if (savedRange) {
            const selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(savedRange);
            editorArea.focus();
        } else {
            editorArea.focus();
        }
    }

    function rgbToHex(rgb) {
        const parts = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
        if (!parts) return '#000000';

        const r = parseInt(parts[1], 10);
        const g = parseInt(parts[2], 10);
        const b = parseInt(parts[3], 10);

        return "#" + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1).toUpperCase();
    }

    // Inicializar el estado de los controles al cargar la página (todos deshabilitados al inicio)
    updateControlStates();
});
