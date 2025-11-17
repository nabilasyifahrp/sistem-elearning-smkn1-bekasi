document.addEventListener('DOMContentLoaded', function() {
    const links = document.querySelectorAll('.ajax-link');
    const contentArea = document.getElementById('content-area');

    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();

            links.forEach(l => l.classList.remove('active'));
            this.classList.add('active');

            fetch(this.href)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    const newContent = doc.querySelector('#content-area');
                    contentArea.innerHTML = newContent.innerHTML;
                })
                .catch(err => console.error(err));
        });
    });

    const firstActive = document.querySelector('.ajax-link.active');
    if (firstActive) firstActive.click();
});
