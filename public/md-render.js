// Funzione per visualizzare il contenuto Markdown
function displayMarkdownContent(content) {
    const output = document.getElementById('output');
    output.innerHTML = '';
    const htmlContent = marked.parse(content);

    const container = document.createElement('div');
    container.classList.add('markdown');
    container.innerHTML = htmlContent;

    // Genera l'elenco dei capitoli basato sugli header del markdown
    const chapterList = document.getElementById('chapterList');
    chapterList.innerHTML = '';
    container.querySelectorAll('h1, h2, h3').forEach((header, index) => {
        
        const chapterLink = document.createElement('a');
        chapterLink.classList.add('text-reset','chapter-link', 'header-' + header.tagName.toLowerCase());
        chapterLink.textContent = header.textContent;
        header.id = encodeURIComponent(header.textContent)
        chapterLink.href = "#"+header.id;
        
        
        chapterList.appendChild(chapterLink);
    });

    // Evidenziazione dei blocchi di codice
    output.appendChild(container);
    container.querySelectorAll('pre code').forEach((block) => {

        if (preview && block.classList.contains('language-html') && !block.innerHTML.includes("style")) {
            // Creazione della griglia per visualizzare codice e anteprima
            const flexContainer = document.createElement('div');
            flexContainer.classList.add('code-preview-flex');

            const codeContainer = document.createElement('div');
            codeContainer.classList.add('code-container');
            const previewContainer = document.createElement('div');
            previewContainer.classList.add('render-html');

            // Copia il contenuto del blocco di codice nel codeContainer e applica la classe language-html
            const codeBlock = document.createElement('pre');
            const codeElement = document.createElement('code');
            codeElement.classList.add(language);
            codeElement.textContent = block.textContent;
            codeBlock.appendChild(codeElement);
            codeContainer.appendChild(codeBlock);

            // Aggiungi l'anteprima nel previewContainer
            previewContainer.innerHTML = block.textContent;

            // Aggiungi entrambi i contenitori al flexContainer
            flexContainer.appendChild(codeContainer);
            flexContainer.appendChild(previewContainer);

            // Sostituisci il blocco originale con il flexContainer
            block.parentElement.replaceWith(flexContainer);
        }
        Prism.highlightElement(block);

    });

    container.querySelectorAll('code').forEach((inlineCode) => {
        if (/^<.*>$/.test(inlineCode.textContent.trim())) {
            inlineCode.classList.add(language); // Aggiungi la classe di linguaggio appropriata
        }
        Prism.highlightElement(inlineCode);
    });
}
