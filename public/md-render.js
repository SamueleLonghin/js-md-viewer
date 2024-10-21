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
        chapterLink.classList.add('text-reset', 'chapter-link', 'header-' + header.tagName.toLowerCase());
        chapterLink.textContent = header.textContent;
        header.id = encodeURIComponent(header.textContent)
        chapterLink.href = "#" + header.id;


        chapterList.appendChild(chapterLink);
    });

    // Evidenziazione dei blocchi di codice
    output.appendChild(container);
    container.querySelectorAll('pre code').forEach((block) => {
        console.log(preview)
        // Prendo l'elemento radice            
        base = block.parentElement;
        base.classList.add("language")

        if (preview && block.classList.contains('language-html') && !block.innerHTML.includes("style")) {


            // Creo l'anteprima
            const previewContainer = document.createElement('div');
            previewContainer.classList.add('render-html');
            previewContainer.innerHTML = block.textContent;


            // Creazione della griglia per visualizzare codice e anteprima
            const flexContainer = document.createElement('div');
            flexContainer.classList.add('code-preview-flex');

            // Sostituisci il blocco originale con il flexContainer
            base.replaceWith(flexContainer)
            // Agiungo entrambi i figli ovvero quello che prima era il blocco e l'anteprima
            flexContainer.appendChild(base)
            flexContainer.appendChild(previewContainer);




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
