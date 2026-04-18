<?php
use App\Helpers\ViewHelper;
ViewHelper::loadHeader($title);
?>
<!-- display Items  -->
 <div class="row g-4">
    <?php if(empty($items)): ?>
    <div class="col-12 text-center text-muted py-5">
        <i class="bi bi-box-seam fs-1 d-block mb-2"></i>
                No items found.
    </div>
    <?php else: ?>
        <?php foreach($items as $item): ?>
            <div class="col-md-3 col-sm-6">
                <div class="card h-100 border-0 shadow bg-dark text-white rounded-4 overflow-hidden item-card">
                    <!-- Item Image (To change later) -->
                     <img src="https://placehold.co/600x300/343a40/white?text=No+Image"
                     class="card-img-top"
                     alt="<?= hs($item['listing_product']) ?>"
                     style="height: 220px;object-fit:cover;">
                     <!-- card body of the item -->
                      <div class="card-body d-flex flex-column px-3 pt-3 pb-4">
                        <!-- item name -->
                         <h5 class="fw-bold ">
                            <?= hs($item['listing_product']) ?>
                         </h5>
                         <!-- description of item -->
                          <p>
                            <?= hs(mb_strimwidth($item['detail'],0,60,'...')) ?>
                          </p>
                          <p>
                            Seller: <?= hs($item['username']) ?>
                            </p>
                          <!-- price  of item -->
                           <div class="d-flex justify-content-between align-items-center mt-auto mb-3">
                                <h5 class="text-primary fw-bold mb-0">
                                    $<?= number_format($item['price'], 2) ?>
                                </h5>
                           </div>
                           <!-- add to cart Button -->
                            <div class="d-flex gap-2">
                                <a href="<?= APP_BASE_URL ?>/cart/add/<?= $item['item_id'] ?>"
                                    class="btn btn-primary flex-grow-1">
                                    <i class="bi bi-cart-plus me-1"></i> Add to Cart
                                </a>
                            </div>
                      </div>
                </div>
            </div>
        <?php endforeach?>
    <?php endif?>
 </div>

<?php

ViewHelper::loadJsScripts();
ViewHelper::loadFooter();
?>
