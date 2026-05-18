function confirmDeleteItem(itemId, listingProduct) {
    Swal.fire({
        title: "Are you sure?",
        text: "Please confirm deleting item: " + listingProduct,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: "Deleted!",
                text: "The selected item has been deleted.",
                icon: "success",
            });

            window.location.href =
                window.APP_BASE_URL + "/items/" + itemId + "/delete";
        }
    });
}
