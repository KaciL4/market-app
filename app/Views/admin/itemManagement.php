<?php

use App\Helpers\ViewHelper;

ViewHelper::loadAdminHeader($title);
?>

<div class="mb-4 border-bottom pb-2">
    <h2>Item Management</h2>
    <p class="text-muted mb-0">Review and manage marketplace listings</p>
</div>

<div class="card p-3 mb-4">
    <form method="GET" action="<?= APP_BASE_URL ?>/admin/item_management">
        <div class="row g-3 align-items-center">
            <div class="col-md-8">
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Search items..."
                    value="<?= htmlspecialchars($search ?? '') ?>">
            </div>

            <div class="col-md-4">
                <div class="d-flex gap-2 flex-wrap justify-content-md-end">
                    <a href="<?= APP_BASE_URL ?>/admin/item_management?status=all&search=<?= urlencode($search ?? '') ?>"
                        class="btn <?= ($status ?? 'all') === 'all' ? 'btn-primary' : 'btn-outline-secondary' ?>">
                        All
                    </a>

                    <a href="<?= APP_BASE_URL ?>/admin/item_management?status=pending&search=<?= urlencode($search ?? '') ?>"
                        class="btn <?= ($status ?? '') === 'pending' ? 'btn-warning' : 'btn-outline-secondary' ?>">
                        Pending
                    </a>

                    <a href="<?= APP_BASE_URL ?>/admin/item_management?status=approved&search=<?= urlencode($search ?? '') ?>"
                        class="btn <?= ($status ?? '') === 'approved' ? 'btn-success' : 'btn-outline-secondary' ?>">
                        Approved
                    </a>

                    <a href="<?= APP_BASE_URL ?>/admin/item_management?status=rejected&search=<?= urlencode($search ?? '') ?>"
                        class="btn <?= ($status ?? '') === 'rejected' ? 'btn-danger' : 'btn-outline-secondary' ?>">
                        Rejected
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="row">
    <?php if (!empty($items)): ?>
        <?php foreach ($items as $item): ?>
            <?php
            $reviewStatus = strtolower($item['review_status'] ?? 'unknown');

            $badgeClass = 'bg-secondary';
            if ($reviewStatus === 'approved') {
                $badgeClass = 'bg-success';
            } elseif ($reviewStatus === 'pending') {
                $badgeClass = 'bg-warning text-dark';
            } elseif ($reviewStatus === 'rejected') {
                $badgeClass = 'bg-danger';
            }

            $placeholderImage = 'https://placehold.co/600x300?text=No+Image';
            ?>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <img
                        src="<?= $placeholderImage ?>"
                        class="card-img-top"
                        alt="Item image"
                        style="height: 220px; object-fit: cover;">

                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0">
                                <?= htmlspecialchars($item['listing_product']) ?>
                            </h5>
                            <span class="badge <?= $badgeClass ?>">
                                <?= htmlspecialchars($item['review_status']) ?>
                            </span>
                        </div>

                        <p class="text-muted small mb-2">
                            <?= htmlspecialchars($item['detail']) ?>
                        </p>

                        <p class="fw-bold text-primary fs-5 mb-2">
                            $<?= number_format((float)$item['price'], 2) ?>
                        </p>

                        <p class="small text-muted mb-1">
                            <strong>Seller:</strong> <?= htmlspecialchars($item['username']) ?>
                        </p>

                        <p class="small text-muted mb-3">
                            <strong>Date:</strong> <?= htmlspecialchars($item['listing_date']) ?>
                        </p>

                        <div class="mt-auto">
                            <?php if ($reviewStatus === 'pending'): ?>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-success btn-sm flex-fill" disabled>
                                        Approve
                                    </button>
                                    <button class="btn btn-danger btn-sm flex-fill" disabled>
                                        Reject
                                    </button>
                                </div>
                            <?php else: ?>
                                <button class="btn btn-outline-secondary btn-sm w-100" disabled>
                                    Review Completed
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="alert alert-info">
                No items found.
            </div>
        </div>
    <?php endif; ?>
</div>

<?php ViewHelper::loadAdminFooter(); ?>
