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
                        <a href="<?= APP_BASE_URL ?>/cart/add/<?= $item['item_id'] ?>"class ="btn btn-primary btn-lg flex-grow-1">
                            <i class="bi bi-cart-plus me-2"></i>
                            Add to Cart
                        </a>
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
<?php

ViewHelper::loadJsScripts();
ViewHelper::loadFooter();
?>
