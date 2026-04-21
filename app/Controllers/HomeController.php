<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Domain\Models\AdminModel;
use DI\Container;
use App\Domain\Models\ItemModel;
use App\Helpers\Core\PDOService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController extends BaseController
{
    //NOTE: Passing the entire container violates the Dependency Inversion Principle and creates a service locator anti-pattern.
    // However, it is a simple and effective way to pass the container to the controller given the small scope of the application and the fact that this application is to be used in a classroom setting where students are not yet familiar with the Dependency Inversion Principle.
    public function __construct(Container $container, private AdminModel $adminModel)
    {
        parent::__construct($container);
    }

    public function index(Request $request, Response $response, array $args): Response
    {
        $itemModel = new ItemModel($this->container->get(PDOService::class));
        $recentItems = $itemModel->getRecentItems();

        $data = [
            'categories' => $this->adminModel->getAllCategories(),
            'recentItems' => $recentItems
        ];

        return $this->render($response, 'homeView.php', $data);
    }

    public function error(Request $request, Response $response, array $args): Response
    {

        return $this->render($response, 'errorView.php');
    }
}
