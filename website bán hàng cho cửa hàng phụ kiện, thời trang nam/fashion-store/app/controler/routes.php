<?php
declare(strict_types=1);

use Slim\App;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Container\ContainerInterface;
require_once __DIR__ . '/../models/productModel.php';
use function PHPUnit\Framework\equalToIgnoringCase;

return function (App $app) {
    $app->get('/hello/{name}', function (RequestInterface $request, ResponseInterface $response, $args) {
        $name = $args['name'];
        $response->getBody()->write("Hello, $name");
        return $response;
    });
    $app->get('/shop-details', function (RequestInterface $request, ResponseInterface $response, $args) use ($app) {
        $container = $app->getContainer();
        $view = $container->get('view');
        return $view->render($response, 'shop-details.php');
    });
    $app->get('/shopping-cart', function (RequestInterface $request, ResponseInterface $response, $args) use ($app) {
        $container = $app->getContainer();
        $view = $container->get('view');
        return $view->render($response, 'shopping-cart.php');
    });
    $app->get('/checkout', function (RequestInterface $request, ResponseInterface $response, $args) use ($app) {
        $container = $app->getContainer();
        $view = $container->get('view');
        return $view->render($response, 'checkout.php');
    });
    $app->get('/login', function (RequestInterface $request, ResponseInterface $response, $args) use ($app) {
        $container = $app->getContainer();
        $view = $container->get('view');
        return $view->render($response, 'login.php');
    });
    $app->get('/register', function (RequestInterface $request, ResponseInterface $response, $args) use ($app) {
        $container = $app->getContainer();
        $view = $container->get('view');
        return $view->render($response, 'register.php');
    });
    $app->get('/blog-details', function (RequestInterface $request, ResponseInterface $response, $args) use ($app) {
        $container = $app->getContainer();
        $view = $container->get('view');
        return $view->render($response, 'blog-details.php');
    });
    $app->get('/blog', function (RequestInterface $request, ResponseInterface $response, $args) use ($app) {
        $container = $app->getContainer();
        $view = $container->get('view');
        return $view->render($response, 'blog.php');
    });
    $app->get('/contact', function (RequestInterface $request, ResponseInterface $response, $args) use ($app) {
        $container = $app->getContainer();
        $view = $container->get('view');
        return $view->render($response, 'contact.php');
    });
    $app->get('/index', function (RequestInterface $request, ResponseInterface $response, $args) use ($app) {
        $db = $app->getContainer()->get('db');
        $productModel = new ProductModel($db);
        $queryParams = $request->getQueryParams();
        $BestSeller = $productModel->getProductBySaleType(2);
        $HotSale = $productModel->getProductBySaleType(1);
        $NewArrival = $productModel->getProductBySaleType(0);
        $itemsPerPage = 8;
        $container = $app->getContainer();
        $view = $container->get('view');
        return $view->render($response, 'index.php', [
            'BestSeller' => $BestSeller,
            'HotSale' => $HotSale,
            'NewArrival' => $NewArrival,
            'itemsPerPage' => $itemsPerPage,
        ]);
    });
    $app->get('/shop', function (RequestInterface $request, ResponseInterface $response, $args) use ($app) {
        $db = $app->getContainer()->get('db');
        $productModel = new ProductModel($db);
    
        $queryParams = $request->getQueryParams();
        $sort = $queryParams['sort'] ?? null;
        $filters = [
            'filter' => $queryParams['filter'] ?? '',
            'priceFilter' => $queryParams['priceFilter'] ?? null,
            'search' => trim($queryParams['search'] ?? '')
        ];
    
        $page = (int) ($queryParams['page'] ?? 1);
        if ($page < 1) $page = 1;
    
        $mens_fashion = $productModel->getFilteredProducts($filters, $sort);
        $itemsPerPage = 12;
        $start = ($page - 1) * $itemsPerPage;
        $end = $start + $itemsPerPage;
        $totalItems = count($mens_fashion);
        $totalPages = ceil($totalItems / $itemsPerPage);
    
        $categoryCounts = [];
        foreach ($mens_fashion as $product) {
            $category = $product['Product_type'];
            $categoryCounts[$category] = ($categoryCounts[$category] ?? 0) + 1;
        }
    
        $cart = $_SESSION['cart'] ?? [];
        $cartPrice = 0;
        foreach ($cart as $item) {
            $cartPrice += $item['Price'];
        }
    
        return $this->get('view')->render($response, 'shop.php', [
            'page' => $page,
            'categoryCounts' => $categoryCounts,
            'start' => $start,
            'end' => $end,
            'mens_fashion' => $mens_fashion,
            'totalItems' => $totalItems,
            'totalPages' => $totalPages,
            'cart' => $cart,
            'cartPrice' => $cartPrice,
        ]);
    });          
};