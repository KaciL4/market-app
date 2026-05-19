<?php

use App\Helpers\ViewHelper;

$title = $data['title'] ?? 'Edit Item';
$item = $data['item'] ?? [];
$categories = $data['categories'] ?? [];

ViewHelper::loadItemHeader($title);
?>

<div class="container">
    <div class="row justify-content-center mb-5">
        <div class="col-lg-8 text-center">
            <h1 class="display-5 fw-bold text-white mb-3">Upload Item for Sale</h1>
            <p class="lead text-secondary mb-4">Browse high-quality pre-owned products from our community.</p>
        </div>
    </div>
</div>

<?php
ViewHelper::loadFooter();
?>
