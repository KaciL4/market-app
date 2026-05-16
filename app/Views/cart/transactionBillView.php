<?php

use App\Helpers\ViewHelper;

$transaction = $data['transaction'] ?? [];
ViewHelper::loadHeader('Receipt');
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="bg-success p-4 text-center text-white">
                    <i class="bi bi-check-circle-fill" style="font-size: 3rem;"></i>
                    <h2 class="fw-bold mt-2">Payment Successful</h2>
                    <p class="mb-0">Order #<?= hs($transaction['transaction_id']) ?></p>
                </div>
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Summary</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Item:</span>
                        <span class="fw-semibold"><?= hs($transaction['listing_product']) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Date:</span>
                        <span><?= date('M d, Y', strtotime($transaction['transaction_date'])) ?></span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fs-4 fw-bold text-primary">
                        <span>Total Paid (incl. 15% tax):</span>
                        <span>$<?= number_format($transaction['total_paid'], 2) ?></span>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button onclick="window.print()" class="btn btn-outline-secondary rounded-pill">
                            <i class="bi bi-printer me-2"></i> Print Receipt
                        </button>
                        <a href="<?= APP_BASE_URL ?>/home" class="btn btn-primary rounded-pill">Return Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php ViewHelper::loadFooter(); ?>