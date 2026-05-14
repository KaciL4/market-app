<?php
use App\Helpers\ViewHelper;
ViewHelper::loadHeader($title);
?>
<div class="container my-5">
    <!-- image of item (in the left)  -->
   <div class="row g-5 align-items-center">
        <div class="col-md-6">
            <img
                src="https://placehold.co/600x400/343a40/white?text=No+Image"
                    class="img-fluid"
                    alt="<?= htmlspecialchars($item['listing_product']) ?>"
                    style="height: 500px; object-fit: cover; width: 100%;">
        </div>
   <!-- item details (in the right)-->
    <div class="col-md-6">
        <div class="d-flex flex-column">

                 <!-- item name -->
                  <h1 class="mb-3">
                    <?= hs($item['listing_product']) ?>
                  </h1>
                  <!-- item price -->
                   <h3 class="text-primary mb-5">
                        $<?= number_format($item['price'],2) ?>
                   </h3>
                   <!-- add to cart button -->
                     <div class="d-grid mb-4">
                        <form action="<?= APP_BASE_URL ?>/cart/add" method="POST">
                            <!-- Hidden field to send the product ID to the controller -->
                            <input type="hidden" name="item_id" value="<?= (int)$item['item_id'] ?>">
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-cart-plus me-2"></i>
                                Add to Cart
                            </button>
                        </form>
                    </div>
                   <hr class="border-secondary">
                   <h4 class="accordion-header">Details</h4>
                   <!-- item description -->
                    <p class="text-secondary mb-4">
                        <?= hs($item['detail']) ?>
                    </p>
                    <!-- more item info -->
                    <ul class="list-unstyled mb-4">
                <li class="mb-2"><strong>Seller:</strong> <?= hs($item['username'] ?? 'Unknown') ?></li>
                <li class="mb-2"><strong>Status:</strong> <span class="badge bg-success"><?= hs($item['status']) ?></span></li>
                <li class="mb-2"><strong>Listed on:</strong> <?= hs($item['listing_date']) ?></li>
                <li class="mb-2"><strong>Category:</strong> <?= hs($item['category_name']) ?></li>
            </ul>

        </div>
    </div>
</div>
<!-- buttom section for items recommendation -->
 <div class="mt-5">
    <h5 class="fw-bold mb-4">
        <i class="bi bi-grid me-2"></i>
        Similar Items
    </h5>
    <div class="row g-3">
        <div class="col-12 text-muted text-center py-3">
        <!-- TODO: fetch similar item by category -->
        </div>
    </div>
 </div>
 <!-- notification pop-up when add a item to cart -->
 <?php if (isset($_GET['added'])): ?>
<div class="modal fade" id="cartModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-body p-4 text-center">
        <i class="bi bi-check-circle-fill text-success mb-3" style="font-size: 3rem;"></i>
        <h4 class="fw-bold">Item added to cart</h4>
        <p class="text-muted">You have 1 new item in your cart.</p>
        <div class="d-grid gap-2 mt-4">
          <a href="<?= APP_BASE_URL ?>/cart" class="btn btn-primary rounded-pill py-2 fw-bold">View Cart</a>
          <button type="button" class="btn btn-outline-secondary rounded-pill py-2" data-bs-dismiss="modal">Continue Shopping</button>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
    new bootstrap.Modal(document.getElementById('cartModal')).show();
</script>
<?php endif; ?>
<?php

ViewHelper::loadJsScripts();
ViewHelper::loadFooter();
?>
