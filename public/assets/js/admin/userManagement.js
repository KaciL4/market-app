document.addEventListener('DOMContentLoaded', function () {

    // Search
    document.getElementById('searchBtn').addEventListener('click', function () {
        const query = document.getElementById('searchInput').value.trim();
        window.location.href = BASE_URL + '/admin/user_management?search=' + encodeURIComponent(query);
         window.location.href = window.APP_BASE_URL + '/admin/user_management?search=' + encodeURIComponent(query);
    });

    // Enter key
    document.getElementById('searchInput').addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            document.getElementById('searchBtn').click();
        }
    });

    // Delete modal
    document.getElementById('deleteModal').addEventListener('show.bs.modal', function (event) {
        const button   = event.relatedTarget;
        const userId   = button.getAttribute('data-user-id');
        const username = button.getAttribute('data-username');

        document.getElementById('modalUsername').textContent = username;
        document.getElementById('deleteForm').action =
            window.APP_BASE_URL + '/admin/user_management/delete/' + userId;
    });

});
