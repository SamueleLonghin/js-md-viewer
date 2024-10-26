// Mostra/Nasconde la barra laterale per schermi piccoli
const sidebar = document.getElementById('sidebar');
const sidebarToggle = document.getElementById('sidebar-toggle');
sidebarToggle.addEventListener('click', toggleSidebar);



function toggleSidebar() {
    sidebar.classList.toggle('visible');
}

displayMarkdownContent(content)

const chaptersLinks = document.getElementsByClassName('chapter-link');

for (var i = 0; i < chaptersLinks.length; i++) {
    chaptersLinks[i].addEventListener('click', toggleSidebar, false);
}
