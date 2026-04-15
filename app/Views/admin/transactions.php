<?php

use App\Helpers\ViewHelper;

$title = $data['title'] ?? 'Transactions';
$transactions = $data['transactions'] ?? [];

ViewHelper::loadAdminHeader($title);
?>

<div class="mb-4 border-bottom">
    <h2>Transactions</h2>
    <p class="text-secondary mb-3">View all platform transactions</p>
</div>

<p class="text-muted small mb-2">
    Total transactions: <strong><?= count($transactions) ?></strong>
</p>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Buyer/Seller</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Purchased</th>
                    <th>Transaction Date</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($transactions as $transaction) {
                ?>
                    <tr>
                        <td><?= hs($transaction['transaction_id']) ?></td>
                        <td><?= hs($transaction['username']) ?></td>
                        <td><?= hs($transaction['listing_product']) ?></td>
                        <td><?= hs($transaction['price']) ?></td>
                        <td><?php if (htmlspecialchars($transaction['item_purchased'])) { ?>
                                <span class="text-success">
                                    <?= swarm_icon('lucide:circle-check') ?>
                                </span> <!-- tinyint(1) -->
                            <?php } else { ?>
                                <span class="text-danger">
                                    <?= swarm_icon('lucide:x-circle') ?>
                                </span> <!-- tinyint(0) -->
                            <?php } ?>
                        </td>
                        <td><?= hs($transaction['transaction_date']) ?></td>
                        <td class="py-3">
                            <!-- <?php
                                    $status = strtolower(trim($transaction['status'] ?? ''));
                                    ?> -->

                            <?php if ($status === 'available') { ?>
                                <span class="text-success fw-semibold">Available</span>
                            <?php } elseif ($status === 'pending') { ?>
                                <span class="text-warning fw-semibold">Pending</span>
                            <?php } elseif ($status === 'sold') { ?>
                                <span class="text-danger fw-semibold">Sold</span>
                            <?php } else { ?>
                                <span class="text-secondary">Unknown</span>
                            <?php } ?>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>

        </table>
    </div>
</div>

<?php
ViewHelper::loadAdminFooter();
?>
