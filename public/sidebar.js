// Mostra/Nasconde la barra laterale per schermi piccoli
const sidebar = document.getElementById('sidebar');
const sidebarToggle = document.getElementById('sidebar-toggle');
sidebarToggle.addEventListener('click', toggleSidebar);


function toggleSidebar() {
    sidebar.classList.toggle('visible');
}



const lessonToggler = document.getElementById('btn-lesson-mode');
if (lessonToggler) {
    const body = document.getElementsByTagName('body');

    lessonToggler.addEventListener('click', () => {
        body.classList.toggle('lesson-view');
    });
}