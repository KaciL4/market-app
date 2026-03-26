    // Edit modal
    document.getElementById('editCategoryModal').addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        document.getElementById('editCategoryName').value = button.getAttribute('data-name');
        document.getElementById('editCategoryForm').action =
            '<?= APP_BASE_URL ?>/admin/categories/edit/' + button.getAttribute('data-id');
    });

    // Delete modal
    document.getElementById('deleteCategoryModal').addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        document.getElementById('deleteCategoryName').textContent = button.getAttribute('data-name');
        document.getElementById('deleteCategoryForm').action =
            '<?= APP_BASE_URL ?>/admin/categories/delete/' + button.getAttribute('data-id');
    });
