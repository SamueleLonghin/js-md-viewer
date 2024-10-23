function previewCode(base) {
    // Creo l'anteprima
    const previewContainer = document.createElement('div');
    previewContainer.classList.add('render-code');

    // Creazione della griglia per visualizzare codice e anteprima
    const flexContainer = document.createElement('div');
    flexContainer.classList.add('code-preview-flex');

    // Sostituisci il blocco originale con il flexContainer
    base.replaceWith(flexContainer)

    // Agiungo entrambi i figli ovvero quello che prima era il blocco e l'anteprima
    flexContainer.appendChild(base)
    flexContainer.appendChild(previewContainer);


    return previewContainer
}


function previewHTML(base, block) {
    // Ottengo il blocco per la preview
    previewContainer = previewCode(base);

    previewCSS(base,block)

    html = block.textContent;
    html = html.replace(/[^{]+\s*{[^}]*}/g, '');

    // Inserisco il codice dentro al blocco
    previewContainer.innerHTML = html;
}

function previewCSS(base, block) {
    console.log(base)

    // Modifica il CSS per essere applicato solo all'interno del div con classe 'preview'
    // cssCode = block.textContent.replace(/([^{]+){/g, '.render-code $1 {');
    cssCode = block.textContent;
    // Modifica il CSS per essere applicato solo all'interno del div con classe 'preview'
    // Evita di applicare il prefisso `.preview` a selettori globali come 'html', 'body', o 'head'
    // cssCode = cssCode.replace(/(^|\s+)(html|body|head|@.+?)(\s*[{])/g, '$1$2$3'); // Lascia invariati questi selettori
    // cssCode = cssCode.replace(/([^{\s]+)\s*{/g, '.render-code $1 {'); // Prefissa tutti gli altri selettori con .preview
    cssCode = cssCode.replace(/([^{\s]+)\s*{/g, '.render-code $1 {');

    // Crea un tag <style> per inserire il CSS modificato nella pagina
    const styleTag = document.createElement('style');
    styleTag.textContent = cssCode;

    document.head.appendChild(styleTag);
}

function previewPython(preElement, codeElement) {

    // Crea il pulsante "Esegui"
    const runButton = document.createElement('button');
    runButton.classList.add('run-button');
    runButton.textContent = 'Esegui';


    // Crea un div per mostrare l'output
    const outputDiv = document.createElement('div');
    outputDiv.classList.add('code-output');

    // Funzione per eseguire il codice Python quando il pulsante viene cliccato
    runButton.addEventListener('click', function () {
        const code = codeElement.textContent.trim(); // Recupera il codice dal blocco <code>
        // Costruisci il payload per l'API
        const payload = {
            language: "python",
            version: "3.10.0",
            files: [
                {
                    name: "main.py",
                    content: code
                }
            ]
        };

        // Esegui la richiesta POST alla API di Piston
        fetch('https://emkc.org/api/v2/piston/execute', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload) // Converte il payload in JSON
        })
            .then(response => response.json()) // Converte la risposta in JSON
            .then(data => {
                // Mostra l'output dell'esecuzione
                if (data.run && data.run.output) {
                    outputDiv.textContent = `Output:\n${data.run.output}`;

                } else {
                    outputDiv.textContent = 'Nessun output o errore.';
                }
            })
            .catch(error => {
                // Gestisce eventuali errori
                console.error('Errore:', error);
                outputDiv.textContent = 'Si è verificato un errore durante l\'esecuzione.';
            });

    });

    // Creo il blocco per la preview
    const previewContainer = previewCode(preElement)

    // Inserisce il pulsante e il div dell'output dopo il blocco <pre>
    previewContainer.append(runButton, outputDiv);
}

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

        // Prendo l'elemento radice            
        base = block.parentElement;
        base.classList.add("language")

        console.log(block)
        if (preview) {
            if (block.classList.contains('language-html') ) {
                previewHTML(base, block);
            }
            if (block.classList.contains('language-python')) {
                previewPython(base, block);
            }
            if (block.classList.contains('language-css')) {
                previewCSS(base, block);
            }
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
