<?php

use App\Helpers\ViewHelper;

global $translator;
$currentLocale = $translator->getLocale();

$user = $data['user'] ?? [];
$cart = $data['cart'] ?? [];
$subtotal = $data['subtotal'] ?? 0;
$taxAmount = $data['taxAmount'] ?? 0.0;
$totalPrice = $data['totalPrice'] ?? 0.0;

ViewHelper::loadHeader(trans('checkout.title'));
$isLoggedIn = !empty($user);
?>

<div class="container py-5">
    <div class="row g-5">
        <div class="col-lg-8">
            <h4 class="mb-3 fw-bold"><?= hs(trans('checkout.information')) ?></h4>

            <?php if (!$isLoggedIn): ?>
                <div class="card border-0 shadow-sm bg-body-tertiary p-5 text-center rounded-4">
                    <h3 class="fw-bold"><?= hs(trans('checkout.login_required')) ?></h3>
                    <p class="text-secondary"><?= hs(trans('checkout.login_required_text')) ?></p>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                        <a href="<?= APP_BASE_URL ?>/auth/login?lang=<?= hs($currentLocale) ?>" class="btn btn-primary btn-lg px-5 rounded-pill">
                            <?= hs(trans('nav.sign_in')) ?>
                        </a>
                        <a href="<?= APP_BASE_URL ?>/auth/register?lang=<?= hs($currentLocale) ?>" class="btn btn-outline-primary btn-lg px-5 rounded-pill">
                            <?= hs(trans('auth.create_account')) ?>
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <form action="<?= APP_BASE_URL ?>/cart/process?lang=<?= hs($currentLocale) ?>" method="POST">
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label"><?= hs(trans('checkout.first_name')) ?></label>
                            <input type="text" class="form-control" name="first_name" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label"><?= hs(trans('checkout.last_name')) ?></label>
                            <input type="text" class="form-control" name="last_name" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label"><?= hs(trans('auth.username')) ?></label>
                            <input type="text" class="form-control" name="username"
                                value="<?= $isLoggedIn ? hs($user['username']) : '' ?>" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label"><?= hs(trans('checkout.email_address')) ?></label>
                            <input type="email" class="form-control" name="email"
                                value="<?= $isLoggedIn ? hs($user['email']) : '' ?>" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label"><?= hs(trans('checkout.shipping_address')) ?></label>
                            <textarea class="form-control" name="address" rows="3" required></textarea>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h4 class="mb-3 fw-bold"><?= hs(trans('checkout.payment')) ?></h4>

                    <div class="my-3">
                        <div class="form-check">
                            <input id="credit" name="paymentMethod" type="radio" class="form-check-input" checked required>
                            <label class="form-check-label" for="credit"><?= hs(trans('checkout.credit_card')) ?></label>
                        </div>

                        <div class="form-check">
                            <input id="debit" name="paymentMethod" type="radio" class="form-check-input" required>
                            <label class="form-check-label" for="debit"><?= hs(trans('checkout.debit_card')) ?></label>
                        </div>
                    </div>

                    <div class="row gy-3">
                        <div class="col-md-6">
                            <label class="form-label"><?= hs(trans('checkout.name_on_card')) ?></label>
                            <input type="text" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label"><?= hs(trans('checkout.card_number')) ?></label>
                            <input type="text" class="form-control" placeholder="0000 0000 0000 0000" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label"><?= hs(trans('checkout.expiration')) ?></label>
                            <input type="text" class="form-control" placeholder="MM/YY" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label"><?= hs(trans('checkout.cvv')) ?></label>
                            <input type="text" class="form-control" placeholder="123" required>
                        </div>
                    </div>

                    <button class="w-100 btn btn-primary btn-lg mt-5 rounded-pill" type="submit">
                        <?= hs(trans('checkout.place_order')) ?>
                    </button>
                </form>
            <?php endif; ?>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 100px;">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-primary"><?= hs(trans('checkout.order_summary')) ?></span>
                    <span class="badge bg-primary rounded-pill"><?= count($cart) ?></span>
                </h4>

                <ul class="list-group mb-3 list-group-flush">
                    <?php foreach ($cart as $item): ?>
                        <li class="list-group-item d-flex justify-content-between lh-sm px-0 border-0">
                            <div>
                                <h6 class="my-0"><?= htmlspecialchars($item['name']) ?></h6>
                                <small class="text-muted"><?= hs(trans('cart.quantity')) ?>: <?= $item['quantity'] ?></small>
                            </div>
                            <span class="text-muted">$<?= number_format($item['price'] * $item['quantity'], 2) ?></span>
                        </li>
                    <?php endforeach; ?>

                    <hr class="my-2">

                    <li class="list-group-item d-flex justify-content-between px-0 border-0">
                        <span><?= hs(trans('checkout.subtotal')) ?></span>
                        <span>$<?= number_format($subtotal, 2) ?></span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between px-0 border-0">
                        <span><?= hs(trans('checkout.tax')) ?></span>
                        <span>$<?= number_format($taxAmount, 2) ?></span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between px-0 pt-3 border-0">
                        <strong class="fs-5"><?= hs(trans('checkout.total_cad')) ?></strong>
                        <strong class="fs-5 text-primary">$<?= number_format($totalPrice, 2) ?></strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php ViewHelper::loadFooter(); ?>