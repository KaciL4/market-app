        // Search button → redirect with ?search= query
    document.getElementById('searchBtn').addEventListener('click', function () {
        const query = document.getElementById('searchInput').value.trim();
        window.location.href = '<?= APP_BASE_URL ?>/admin/user_management?search=' + encodeURIComponent(query);
    });

    // Allow pressing Enter to search
    document.getElementById('searchInput').addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            document.getElementById('searchBtn').click();
        }
    });
    document.getElementById('deleteModal').addEventListener('show.bs.modal', function (event) {
        const button   = event.relatedTarget;
        const userId   = button.getAttribute('data-user-id');
        const username = button.getAttribute('data-username');

        document.getElementById('modalUsername').textContent = username;
        document.getElementById('deleteForm').action =
            '/admin/user_management/delete/' + userId;
    });
