<?php

use App\Helpers\ViewHelper;

ViewHelper::loadAdminHeader($title);
?>

<div class="mb-4 border-bottom">
    <h2>Admin Dashboard</h2>
    <p>System overview & statistics</p>
</div>

<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stat-card border-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Users</h6>
                        <h3 class="mb-0"><?= htmlspecialchars($totalUsers) ?></h3>
                    </div>
                    <div class="text-primary fs-1">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stat-card border-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Categories</h6>
                        <h3 class="mb-0"><?= htmlspecialchars($totalCategories) ?></h3>
                    </div>
                    <div class="text-warning fs-1">
                        <i class="bi-tags-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stat-card border-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Items</h6>
                        <h3 class="mb-0"><?= htmlspecialchars($totalItems) ?></h3>
                    </div>
                    <div class="text-info fs-1">
                        <i class="bi-box-seam"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stat-card border-danger">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Transactions</h6>
                        <h3 class="mb-0"><?= htmlspecialchars($totalTransactions) ?></h3>
                    </div>
                    <div class="text-danger fs-1">
                        <i class="bi-receipt"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div>
    <canvas id="myChart"></canvas>
</div>

<!-- Attempt to put chart -->
<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  const ctx = document.getElementById('myChart');

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
      datasets: [{
        label: '# of Votes',
        data: [12, 19, 3, 5, 2, 3],
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script> -->

<?php ViewHelper::loadAdminFooter(); ?>
