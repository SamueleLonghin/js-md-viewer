<?php
global $macroargomento, $argomento, $topics, $BASE_PATH, $preview, $language, $markdownContent;

require "head.php";
$cookieConsent = isset($_COOKIE['cookie_consent']) && $_COOKIE['cookie_consent'] == 'accepted';
$isLessonMode = isset($_GET['lesson']);
?>


<body class="<?= $isLessonMode ? 'lesson-mode-view' : 'classic-mode-view' ?>">
    <button id="sidebar-toggle" class="btn btn-outline-secondary"> ☰ </button>

    <div>
        <div class="position-fixed overflow-x-auto p-3 overflow-y-auto" id="sidebar">
            <div class="flex-column flex-nowrap vh-100 ">
                <div class="file-section">

                    <a href="/corsi" class=" btn btn-outline-secondary btn-back">
                        <i class="bi bi-arrow-left"></i>
                        <span class="">Torna ai Corsi</span>
                    </a>

                    <h2>Documenti</h2>
                    <div id="fileList">
                        <?php
                        if ($macroargomento) {
                            $macroArgomentoLink = urlencode($macroargomento);

                            foreach ($topics[$macroargomento]['chapters'] as $label => $data) {
                                if (!isset($data['visibility']) || $data['visibility'] == 'visible') {
                                    $link = urlencode($label);
                                    echo "<a href='/{$macroArgomentoLink}/$link' class='text-reset file-link'> {$label} </a>";
                                }
                            }
                        } else {
                            foreach ($topics as $topic => $val) {
                                $label = $val['label'];
                                $link = urlencode($topic);
                                echo "<a href='$link' class='text-reset file-link'> {$label} </a>";
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="chapters-section <?= !$argomento ? 'd-none' : '' ?>">
                    <h3>Capitoli</h3>
                    <div id="chapterList"></div>
                </div>
            </div>



        </div>
        <div id="content">
            <div id="output" class="bordered">
                <?php
                if ($cookieConsent) { ?>
                    <div class="placeholder-content-loading"></div>
                <?php } else { ?>
                    <div class="placeholder-cookie-request">
                        <div class='cookie-consent-container'>
                            <p>
                                Questo sito utilizza cookie per raccogliere dati statistici.
                            </p>
                            <p>
                                Accetta il trattamento dei dati per visualizzare il contenuto.
                            </p>
                            <button id='accept-cookies'>Accetta</button>
                            <a href="<?= $BASE_PATH ?>public/privacy-policy.html" style="margin-left: 10px;">Privacy
                                Policy</a>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <?php require "footer.php" ?>
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/marked/14.1.2/marked.min.js"></script>
    <script src="<?= $BASE_PATH ?>public/prism.js"></script>

    <script src="<?= $BASE_PATH ?>public/sidebar.js"></script>
    <script src="<?= $BASE_PATH ?>public/md-render.js"></script>

    <div id="raw-content" class="d-none"><?= $markdownContent ?></div>

    <?php if ($cookieConsent) {
        ?>
        <script>
            preview = <?= json_encode($preview) ?>;
            language = <?= json_encode($language) ?>;
            content = <?= json_encode($markdownContent) ?>;

            displayMarkdownContent(content)
        </script>
    <?php } ?>
</body>

</html>