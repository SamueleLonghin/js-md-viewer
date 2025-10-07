const state = {
    config: null,
    topics: {},
    resourcesFolder: './',
    basePath: '',
    preview: false,
    language: 'language-none',
    enableLatex: false,
    currentTopicKey: null,
    currentChapterKey: null,
    pendingSection: null,
};

const dom = {
    output: document.getElementById('output'),
    fileList: document.getElementById('fileList'),
    chaptersSection: document.getElementById('chapters-section'),
    chapterList: document.getElementById('chapterList'),
    topicHeader: document.getElementById('topic-header'),
    topicTitle: document.getElementById('topic-title'),
    initialPlaceholder: document.getElementById('initial-placeholder'),
};

function normalizeBasePath(pathname) {
    if (!pathname) {
        return '';
    }
    let cleaned = pathname.trim();
    if (!cleaned.startsWith('/')) {
        cleaned = `/${cleaned}`;
    }
    cleaned = cleaned.replace(/\/+$/, '');
    if (cleaned === '/') {
        return '';
    }
    return cleaned;
}

function ensureTrailingSlash(path) {
    if (!path) {
        return '';
    }
    return path.endsWith('/') ? path : `${path}/`;
}

function combinePath(...segments) {
    return segments.reduce((acc, segment, index) => {
        if (!segment) {
            return acc;
        }
        let value = String(segment).replace(/\\/g, '/');
        if (index === 0) {
            if (!value.endsWith('/')) {
                value += '/';
            }
            return value;
        }
        value = value.replace(/^\/+/, '').replace(/\/+$/, '');
        if (!acc.endsWith('/')) {
            acc += '/';
        }
        return acc + value;
    }, '');
}

function computePublicPath(relativePath) {
    const prefix = normalizeBasePath(state.basePath);
    const clean = (relativePath || '').replace(/^\.\/+/, '').replace(/^\/+/, '');
    if (!clean) {
        return prefix || '/';
    }
    if (!prefix) {
        return `/${clean}`;
    }
    return `${prefix}/${clean}`;
}

async function fetchJson(path) {
    try {
        const response = await fetch(path);
        if (!response.ok) {
            throw new Error(`Richiesta fallita (${response.status})`);
        }
        return await response.json();
    } catch (error) {
        console.error(`Impossibile leggere ${path}`, error);
        return null;
    }
}

async function fetchText(path) {
    const response = await fetch(path);
    if (!response.ok) {
        throw new Error(`Richiesta fallita (${response.status})`);
    }
    return response.text();
}

function updateFileList(items) {
    dom.fileList.innerHTML = '';
    items.forEach((item) => {
        const link = document.createElement('a');
        link.classList.add('text-reset', 'file-link');
        link.href = item.href;
        link.textContent = item.label;
        dom.fileList.appendChild(link);
    });
}

function toggleChaptersSection(visible) {
    if (!dom.chaptersSection) {
        return;
    }
    dom.chaptersSection.classList.toggle('d-none', !visible);
    if (!visible) {
        dom.chapterList.innerHTML = '';
    }
}

function updateTopicHeader(title) {
    if (!dom.topicHeader) {
        return;
    }
    const shouldShow = Boolean(title);
    dom.topicHeader.classList.toggle('d-none', !shouldShow);
    if (shouldShow) {
        dom.topicTitle.textContent = title;
    } else {
        dom.topicTitle.textContent = '';
    }
}

function renderMessage(message) {
    dom.output.innerHTML = '';
    const container = document.createElement('div');
    container.classList.add('p-4', 'text-center');
    container.textContent = message;
    dom.output.appendChild(container);
}

function renderError(message) {
    renderMessage(message || 'Si è verificato un errore imprevisto.');
}

function renderLoading() {
    dom.output.innerHTML = '';
    const placeholder = document.createElement('div');
    placeholder.classList.add('placeholder-content-loading');
    dom.output.appendChild(placeholder);
}

function resolveMediaPath(folderPath, mediaPath) {
    if (!mediaPath) {
        return '';
    }
    if (/^https?:\/\//i.test(mediaPath)) {
        return mediaPath;
    }
    const resourceFolder = combinePath(state.resourcesFolder, folderPath || '');
    const publicBase = computePublicPath(resourceFolder);
    if (mediaPath.includes('%%BASE_FOLDER%%')) {
        return mediaPath.replace(/%%BASE_FOLDER%%/g, publicBase);
    }
    const relative = combinePath(resourceFolder, mediaPath).replace(/^\.\/+/, '');
    return computePublicPath(relative);
}

function buildHash(segments = [], query) {
    const filtered = segments.filter((segment) => segment !== undefined && segment !== null && segment !== '');
    const path = filtered.join('/');
    const base = filtered.length ? `#/${path}` : '#/';
    if (query) {
        return `${base}?${query}`;
    }
    return base;
}

function getVisibleTopics() {
    return Object.entries(state.topics || {}).filter(([_, topic]) => {
        return !topic.visibility || topic.visibility === 'visible';
    });
}

function getVisibleChapters(topic) {
    return Object.entries(topic.chapters || {}).filter(([_, chapter]) => {
        return !chapter.visibility || chapter.visibility === 'visible';
    });
}

function renderTopicGrid() {
    state.currentTopicKey = null;
    state.currentChapterKey = null;
    updateTopicHeader(null);
    toggleChaptersSection(false);

    const items = getVisibleTopics().map(([key, topic]) => ({
        label: topic.label || key,
        href: buildHash([encodeURIComponent(key)]),
    }));
    updateFileList(items);

    dom.output.innerHTML = '';
    const container = document.createElement('div');
    container.classList.add('container', 'mt-5');

    const title = document.createElement('h1');
    title.classList.add('text-center', 'mb-4');
    title.textContent = 'Seleziona un Modulo';
    container.appendChild(title);

    const row = document.createElement('div');
    row.classList.add('row');

    getVisibleTopics().forEach(([key, topic]) => {
        const col = document.createElement('div');
        col.classList.add('col-6', 'col-sm-4', 'col-md-4', 'mb-4');

        const card = document.createElement('div');
        card.classList.add('card', 'h-100');

        if (topic.cover_image) {
            const img = document.createElement('img');
            img.classList.add('card-img-top');
            img.alt = topic.label || key;
            img.src = resolveMediaPath(topic.folder, topic.cover_image);
            card.appendChild(img);
        }

        const body = document.createElement('div');
        body.classList.add('card-body');

        const cardTitle = document.createElement('h5');
        cardTitle.classList.add('card-title');
        cardTitle.textContent = topic.label || key;

        const description = document.createElement('p');
        description.classList.add('card-text');
        description.textContent = topic.description || '';

        const link = document.createElement('a');
        link.classList.add('stretched-link');
        link.href = buildHash([encodeURIComponent(key)]);

        body.append(cardTitle, description, link);
        card.appendChild(body);
        col.appendChild(card);
        row.appendChild(col);
    });

    container.appendChild(row);
    dom.output.appendChild(container);
    document.title = state.config?.title || 'Dispensa';
}

async function ensureTopicChapters(topicKey, topic) {
    if (topic.chapters) {
        return;
    }
    const chaptersPath = combinePath(state.resourcesFolder, topic.folder || '', 'chapters.json');
    const chapters = await fetchJson(chaptersPath);
    if (chapters) {
        topic.chapters = chapters;
    } else {
        topic.chapters = {};
    }
}

function renderChapterCards(topicKey, topic) {
    state.currentTopicKey = topicKey;
    state.currentChapterKey = null;
    updateTopicHeader(topic.label || topicKey);
    toggleChaptersSection(false);

    const items = getVisibleChapters(topic).map(([chapterKey]) => ({
        label: chapterKey,
        href: buildHash([encodeURIComponent(topicKey), encodeURIComponent(chapterKey)]),
    }));
    updateFileList(items);

    dom.output.innerHTML = '';
    const container = document.createElement('div');
    container.classList.add('container');

    const row = document.createElement('div');
    row.classList.add('row');

    getVisibleChapters(topic).forEach(([chapterKey, chapter]) => {
        const col = document.createElement('div');
        col.classList.add('col-12', 'col-sm-6', 'col-md-4', 'col-lg-3', 'mb-4');

        const card = document.createElement('div');
        card.classList.add('card', 'h-100');

        if (chapter.cover_image) {
            const img = document.createElement('img');
            img.classList.add('card-img-top');
            img.alt = chapterKey;
            img.src = resolveMediaPath(topic.folder, chapter.cover_image);
            card.appendChild(img);
        }

        const body = document.createElement('div');
        body.classList.add('card-body');

        const cardTitle = document.createElement('h5');
        cardTitle.classList.add('card-title');
        cardTitle.textContent = chapterKey;

        const description = document.createElement('p');
        description.classList.add('card-text');
        description.textContent = chapter.description || '';

        const link = document.createElement('a');
        link.classList.add('stretched-link');
        link.href = buildHash([encodeURIComponent(topicKey), encodeURIComponent(chapterKey)]);

        body.append(cardTitle, description, link);
        card.appendChild(body);
        col.appendChild(card);
        row.appendChild(col);
    });

    container.appendChild(row);
    dom.output.appendChild(container);
    document.title = `${topic.label || topicKey} – ${state.config?.title || 'Dispensa'}`;
}

function applyPlaceholders(markdown, topic) {
    const resourceFolder = combinePath(state.resourcesFolder, topic.folder || '');
    const publicFolder = computePublicPath(resourceFolder);
    return markdown.replace(/%%BASE_FOLDER%%/g, publicFolder);
}

function updateGlobalRenderingOptions(topic, chapter) {
    state.preview = chapter.preview ?? topic.preview ?? state.config?.preview ?? false;
    state.language = chapter.language ?? topic.language ?? state.config?.language ?? 'language-none';
    state.enableLatex = chapter.enableLatex ?? topic.enableLatex ?? state.config?.enableLatex ?? false;

    window.preview = state.preview;
    window.language = state.language;
}

function focusSection(sectionId) {
    if (!sectionId) {
        return;
    }
    const target = document.getElementById(sectionId);
    if (target) {
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

function renderCookiePlaceholder() {
    dom.output.innerHTML = '';
    const wrapper = document.createElement('div');
    wrapper.classList.add('placeholder-cookie-request');

    const container = document.createElement('div');
    container.classList.add('cookie-consent-container');

    const message = document.createElement('p');
    message.textContent = 'Questo sito utilizza cookie per raccogliere dati statistici.';

    const message2 = document.createElement('p');
    message2.textContent = 'Accetta il trattamento dei dati per visualizzare il contenuto.';

    const button = document.createElement('button');
    button.id = 'accept-cookies-inline';
    button.textContent = 'Accetta';
    button.classList.add('btn', 'btn-primary');
    button.addEventListener('click', () => {
        window.Analytics?.setConsent();
    });

    const privacyLink = document.createElement('a');
    privacyLink.href = 'public/privacy-policy.html';
    privacyLink.textContent = 'Privacy Policy';
    privacyLink.classList.add('ms-2');

    container.append(message, message2, button, privacyLink);
    wrapper.appendChild(container);
    dom.output.appendChild(wrapper);
    if (window.Analytics) {
        window.Analytics.showBanner();
    }
}

async function renderChapterContent(topicKey, chapterKey) {
    const topic = state.topics[topicKey];
    const chapter = topic.chapters?.[chapterKey];

    state.currentTopicKey = topicKey;
    state.currentChapterKey = chapterKey;

    updateTopicHeader(topic.label || topicKey);
    toggleChaptersSection(true);

    document.title = `${chapterKey} – ${topic.label || topicKey} – ${state.config?.title || 'Dispensa'}`;

    const items = getVisibleChapters(topic).map(([key]) => ({
        label: key,
        href: buildHash([encodeURIComponent(topicKey), encodeURIComponent(key)]),
    }));
    updateFileList(items);

    if (!window.Analytics || !window.Analytics.hasConsent()) {
        toggleChaptersSection(false);
        renderCookiePlaceholder();
        return;
    }

    renderLoading();

    try {
        if (!chapter.file) {
            throw new Error('Percorso del file non configurato.');
        }
        window.buildChapterLink = (sectionId) => {
            const segments = [];
            if (state.currentTopicKey) {
                segments.push(encodeURIComponent(state.currentTopicKey));
            }
            if (state.currentChapterKey) {
                segments.push(encodeURIComponent(state.currentChapterKey));
            }
            const hash = buildHash(segments, sectionId ? `section=${sectionId}` : '');
            return `${window.location.origin}${window.location.pathname}${hash}`;
        };

        const markdownPath = combinePath(state.resourcesFolder, topic.folder || '', chapter.file);
        const markdown = await fetchText(markdownPath);
        const processed = applyPlaceholders(markdown, topic);

        updateGlobalRenderingOptions(topic, chapter);
        const container = displayMarkdownContent(processed);

        if (state.enableLatex && typeof renderMathInElement === 'function') {
            renderMathInElement(container);
        }

        focusSection(state.pendingSection);
    } catch (error) {
        console.error('Impossibile caricare il capitolo richiesto', error);
        toggleChaptersSection(false);
        renderError('Impossibile caricare il capitolo richiesto.');
    }
}

function parseRoute() {
    const raw = window.location.hash.slice(1);
    if (!raw || raw === '/') {
        return { topic: null, chapter: null, section: null };
    }
    const [pathPart, queryPart] = raw.split('?');
    const segments = pathPart.replace(/^\/+/, '').split('/').filter(Boolean);
    const params = new URLSearchParams(queryPart || '');
    return {
        topic: segments[0] ? decodeURIComponent(segments[0]) : null,
        chapter: segments[1] ? decodeURIComponent(segments[1]) : null,
        section: params.get('section') ? params.get('section') : null,
    };
}

async function handleRoute() {
    if (!state.config) {
        return;
    }

    const { topic, chapter, section } = parseRoute();
    state.pendingSection = section;

    if (!topic) {
        renderTopicGrid();
        return;
    }

    const topicData = state.topics[topic];
    if (!topicData || topicData.visibility === 'none') {
        renderError('Il modulo selezionato non è disponibile.');
        return;
    }

    await ensureTopicChapters(topic, topicData);

    if (!chapter) {
        renderChapterCards(topic, topicData);
        return;
    }

    const chapterData = topicData.chapters?.[chapter];
    if (!chapterData || chapterData.visibility === 'none') {
        renderError('Il capitolo selezionato non è disponibile.');
        return;
    }

    await renderChapterContent(topic, chapter);
}

async function bootstrap() {
    try {
        const config = await fetchJson('config.json');
        if (!config) {
            renderError('Impossibile caricare il file di configurazione.');
            return;
        }

        state.config = config;
        state.resourcesFolder = ensureTrailingSlash(config['resources-folder'] || './');
        state.basePath = config['base-path'] || '';
        state.preview = config.preview ?? false;
        state.language = config.language ?? 'language-none';
        state.enableLatex = config.enableLatex ?? false;

        document.title = config.title || 'Dispensa';

        const topicsPath = combinePath(state.resourcesFolder, 'topics.json');
        const topics = await fetchJson(topicsPath);
        if (!topics) {
            renderError('Impossibile caricare l\'elenco dei corsi.');
            return;
        }
        state.topics = topics;

        window.addEventListener('hashchange', () => {
            handleRoute();
        });

        document.addEventListener('cookie-consent-granted', () => {
            handleRoute();
        });

        await handleRoute();

        if (window.Analytics) {
            window.Analytics.init(config['analytics-id']);
        }
    } catch (error) {
        console.error('Errore durante l\'inizializzazione dell\'applicazione', error);
        renderError('Impossibile inizializzare l\'applicazione.');
    }
}

bootstrap();
