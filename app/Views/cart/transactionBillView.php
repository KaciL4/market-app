<?php
use App\Helpers\ViewHelper;
ViewHelper::loadHeader('Receipt');
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="bg-success p-4 text-center text-white">
                    <i class="bi bi-check-circle-fill" style="font-size: 3rem;"></i>
                    <h2 class="fw-bold mt-2">Payment Successful</h2>
                    <p class="mb-0">Order #<?= hs($orderId) ?></p>
                    <p class="mb-0 small"><?= date('F j, Y g:i A', strtotime($transaction_date)) ?></p>
                </div>

                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Order Items</h5>

                    <?php if (count($transactions) > 1): ?>
                        <div class="alert alert-info mb-3">
                            <i class="bi bi-info-circle me-2"></i>
                            This order contains <strong><?= count($transactions) ?> items</strong>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($transactions as $transaction): ?>
                                    <tr>
                                        <td>
                                            <div>
                                                <strong><?= hs($transaction['listing_product']) ?></strong>
                                                <?php if (!empty($transaction['detail'])): ?>
                                                    <br>
                                                    <small class="text-muted"><?= hs(substr($transaction['detail'], 0, 60)) ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="text-center"><?= $transaction['item_purchased'] ?? 1 ?></td>
                                        <td class="text-end">$<?= number_format($transaction['price'], 2) ?></td>
                                        <td class="text-end">
                                            $<?= number_format($transaction['total_price'] ?? ($transaction['price'] * ($transaction['item_purchased'] ?? 1)), 2) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Subtotal:</td>
                                    <td class="text-end">$<?= number_format($subtotal, 2) ?></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Tax (15%):</td>
                                    <td class="text-end">$<?= number_format($taxAmount, 2) ?></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold fs-5">Total Paid:</td>
                                    <td class="text-end fs-5 fw-bold text-primary">$<?= number_format($totalPaid, 2) ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                        <a href="<?= APP_BASE_URL ?>/home" class="btn btn-primary rounded-pill mt-3 px-4">
                            <i class="bi bi-house me-2"></i> Return Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php ViewHelper::loadFooter(); ?>
