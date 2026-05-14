// function for pop up confirmation message for removing a item from cart
document.addEventListener('DOMContentLoaded', function() {
    let currentFormId = null;
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    const modalElement = document.getElementById('removeConfirmModal');
    const modalItemNameSpan = document.getElementById('modalItemName');

    // initialize Bootstrap Modal
    const removeModal = modalElement ? new bootstrap.Modal(modalElement) : null;

    window.showRemoveModal = function(itemId, itemName) {
        currentFormId = 'remove-form-' + itemId;
        if (modalItemNameSpan) {
            modalItemNameSpan.innerText = itemName;
        }
        if (removeModal) {
            removeModal.show();
        }
    };

    if (confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            if (currentFormId) {
                const form = document.getElementById(currentFormId);
                if (form) {
                    form.submit();
                }
            }
        });
    }
});
