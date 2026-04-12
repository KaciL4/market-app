<?php

use App\Helpers\ViewHelper;
use App\Controllers\HomeController;
//TODO: set the page title dynamically based on the view being rendered in the controller.
$page_title = 'Home';
ViewHelper::loadHeader($page_title);
?>

<!-- <h1>Slim Framework-based MVC Application</h1>
<p>This is a simple MVC application built with Slim Framework.

</p>

<p>This app uses a simple and effective way to pass the container to the controller given the small scope of the application and the fact that this application is to be used in a classroom setting where students are not yet familiar with the Dependency Inversion Principle.</p>

<p> Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos. </p>
<p> Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos. </p> -->

<div id="carouselCaptions" class="carousel slide" data-bs-ride="carousel"> <!-- carousel-dark  -->
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#carouselCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
        <button type="button" data-bs-target="#carouselCaptions" data-bs-slide-to="3" aria-label="Slide 3"></button>
    </div>

    <div class="carousel-inner">
        <div class="carousel-item active" data-bs-interval="3000">
            <img src="https://placehold.co/600x200/orange/white?text=Item 1" class="d-block w-100" alt="">
            <div class="carousel-caption d-none d-md-block">
                <h5>First slide label</h5>
                <p>Some representative placeholder content for the first slide.</p>
            </div>
        </div>

        <div class="carousel-item" data-bs-interval="3000">
            <img src="https://placehold.co/600x200/lightblue/white?text=Item 2" class="d-block w-100" alt="">
            <div class="carousel-caption d-none d-md-block">
                <h5>Second slide label</h5>
                <p>Some representative placeholder content for the second slide.</p>
            </div>
        </div>

        <div class="carousel-item" data-bs-interval="3000">
            <img src="https://placehold.co/600x200/teal/white?text=Item 3" class="d-block w-100" alt="">
            <div class="carousel-caption d-none d-md-block">
                <h5>Third slide label</h5>
                <p>Some representative placeholder content for the third slide.</p>
            </div>
        </div>

        <div class="carousel-item" data-bs-interval="3000">
            <img src="https://placehold.co/600x200/red/white?text=Item 4" class="d-block w-100" alt="">
            <div class="carousel-caption d-none d-md-block">
                <h5>Fourth slide label</h5>
                <p>Some representative placeholder content for the Fourth slide.</p>
            </div>
        </div>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#carouselCaptions" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>

    <button class="carousel-control-next" type="button" data-bs-target="#carouselCaptions" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>
<!-- Categories Section -->
 <?php
 $defaultImage = 'https://placehold.co/300x200/adb5bd/white?text=Category';
 $categoryImage=[
    'Books' => APP_BASE_URL . '/public/assets/images/categories/book.jpg',
    'Furnitures' =>APP_BASE_URL . '/public/assets/images/categories/furnitures.jpg',
    'Clothing'=> APP_BASE_URL . '/public/assets/images/categories/clothing.jpg',
    'Electronics' => APP_BASE_URL . '/public/assets/images/categories/electronics.jpg',
    'Other' => $defaultImage,
 ];
 ?>
 <div class="container my-5">
    <h4 class="mb-4 fw-bold">Browse by Category</h4>
    <div class="row g-4">
        <?php if(!empty($categories)):?>
            <?php foreach ($categories as $category): ?>
                <?php
                    $img=$categoryImage[$category['category_name']]??$defaultImage
                ?>
                <div class="col-md-4 col-sm-6">
                     <a href="<?= APP_BASE_URL ?>/items?category=<?= $category['category_id'] ?>"
                       class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm category-card">
                           <img
                            src="<?= $img ?>"
                            class="card-img-top"
                            alt="<?= hs($category['category_name']) ?>"
                            style="height: 250px; object-fit: cover; width: 400px;">
                            <div class="card-body text-center py-2">
                                <h5 class="mb-0 fw-bold"><?= hs($category['category_name']) ?></h5>
                            </div>
                        </div>
                    </a>
                </div>
                 <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center text-muted">
                <i class="bi bi-tags fs-1 d-block mb-2"></i>
                No categories available.
            </div>
        <?php endif; ?>
    </div>


 </div>
<?php

ViewHelper::loadJsScripts();
ViewHelper::loadFooter();
?>
