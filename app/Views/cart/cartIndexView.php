<?php
namespace App\Views\cart;
use App\Helpers\ViewHelper;
ViewHelper::loadHeader('Shopping Cart');
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-12">
            <h1 class="fw-bold h2 mb-4">Shopping cart</h1>

            <div class="row g-4">
                <div class="col-lg-8">
                    <?php if (empty($cart)): ?>
                        <div class="card border-0 shadow text-center py-5">
                            <i class="bi bi-cart-x text-muted mb-3" style="font-size: 3rem;"></i>
                            <h4>Your cart is empty</h4>
                            <a href="<?= APP_BASE_URL ?>/items" class="btn btn-primary rounded-pill mt-3 px-4">Continue Shopping</a>
                        </div>
                    <?php else: ?>
                        <?php foreach ($cart as $id => $item): ?>
                            <div class="card border-0 shadow mb-3 position-relative">
                                <div class="position-absolute" style="top: 15px; right: 15px;">
                                    <form action="<?= APP_BASE_URL ?>/cart/remove" method="POST" id="remove-form-<?= (int)$id ?>">
                                        <input type="hidden" name="item_id" value="<?= (int)$id ?>">
                                        <button type="button" class="btn btn-link text-danger p-0" onclick="showRemoveModal('<?= (int)$id ?>', '<?= hs($item['name']) ?>')">
                                            <i class="bi bi-trash3 fs-5"></i>
                                        </button>
                                    </form>
                                </div>

                                <div class="card-body p-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-3">
                                            <div class="bg-light rounded overflow-hidden d-flex align-items-center justify-content-center" style="height: 120px;">
                                                <img src="<?= hs($item['image']) ?>" class="img-fluid" style="max-height: 100%;">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <h5 class="fw-bold mb-1"><?= hs($item['name']) ?></h5>

                                        </div>

                                        <div class="col-md-3 text-md-end mt-3 mt-md-0">
                                            <div class="h5 fw-bold">$<?= number_format($item['price'], 2) ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow p-4 sticky-top" style="top: 20px;">
                        <h4 class="fw-bold mb-4">Order summary</h4>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Items (<?= $itemCount ?>)</span>
                            <span>$<?= number_format($totalPrice, 2) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Shipping</span>
                            <span class="text-success fw-bold">Free</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="h4 fw-bold">Total</span>
                            <span class="h4 fw-bold">$<?= number_format($totalPrice, 2) ?></span>
                        </div>
                        <a href="<?= APP_BASE_URL ?>/cart/checkout" class="btn  btn-primary w-100 py-3 rounded-pill fw-bold <?= empty($cart) ? 'disabled' : '' ?>">
                            Checkout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="removeConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 1rem;">
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                    <i class="bi bi-exclamation-circle text-danger" style="font-size: 4rem;"></i>
                </div>
                <h4 class="fw-bold">Remove Item?</h4>
                <p class="text-muted">Are you sure you want to remove <span id="modalItemName" class="fw-bold "></span> from your cart?</p>

                <div class="d-grid gap-2 mt-4">
                    <button type="button" id="confirmDeleteBtn" class="btn btn-danger rounded-pill py-2 fw-bold">Remove Item</button>
                    <button type="button" class="btn btn-outline-secondary rounded-pill py-2" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= APP_BASE_URL ?>/public/assets/js/cart.js"></script>

<?php
ViewHelper::loadFooter();
?>
