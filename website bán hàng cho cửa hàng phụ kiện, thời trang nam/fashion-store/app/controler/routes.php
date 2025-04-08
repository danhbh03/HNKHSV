<?php
declare(strict_types=1);

use Slim\App;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Container\ContainerInterface;

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
        $stmt = $db->query("SELECT * FROM mens_fashion");
        $mens_fashion = $stmt->fetchAll();
        $itemsPerPage = 8;

        $filter = $request->getQueryParams()['filter'] ?? "";
        $container = $app->getContainer();
        $view = $container->get('view');
        return $view->render($response, 'index.php');
    });
    $app->get('/shop', function (RequestInterface $request, ResponseInterface $response, $args) use ($app) {
        $db = $app->getContainer()->get('db');
        $stmt = $db->query("SELECT * FROM mens_fashion");
        $mens_fashion = $stmt->fetchAll();       
        $cart=isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        $cartPrice = 0;
        $sort = $request->getQueryParams()['sort'] ?? null;
        $filter = $request->getQueryParams()['filter'] ?? "";
        $priceFilter = $request->getQueryParams()['priceFilter'] ?? null;
        $page = (int) ($request->getQueryParams()['page'] ?? 1);
        if ($page < 1) {
            $page = 1;
        }
        $conditions = [];
        $params = [];
        
        if (!empty($filter)&& $filter !== 'all') {
            $conditions[] = "LOWER(Product_type) LIKE LOWER(:filter)";
            $params[':filter'] = '%' . $filter . '%';
        }
        
        if (!empty($priceFilter)) {
            switch ($priceFilter) {
                case "0-100":
                    $conditions[] = "Price BETWEEN 0 AND 100000";
                    break;
                case "100-200":
                    $conditions[] = "Price > 100000 AND Price <= 200000";
                    break;
                case "200-500":
                    $conditions[] = "Price > 200000 AND Price <= 500000";
                    break;
                case "500-800":
                    $conditions[] = "Price > 500000 AND Price <= 800000";
                    break;
                case "800-1000":
                    $conditions[] = "Price > 800000 AND Price <= 1000000";
                    break;
                case "1000+":
                    $conditions[] = "Price > 1000000";
                    break;
                default:
                    break;
            }
        }
        
        
        $search = trim($request->getQueryParams()['search'] ?? '');
        if ($search !== '') {
            $conditions[] = "(Product_name LIKE :search OR CAST(Price AS CHAR) LIKE :priceSearch)";
            $params[':search'] = '%' . $search . '%';
            $params[':priceSearch'] = '%' . $search . '%';
        }
        $allowedSorts = ['low_to_high' => 'Price ASC', 'high_to_low' => 'Price DESC'];
        $orderBy = "";
        if (isset($allowedSorts[$sort])) {
            $orderBy = "ORDER BY " . $allowedSorts[$sort];
        }
        $where = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';
        $sql = "SELECT * FROM mens_fashion $where $orderBy";
        $stmt = $db->prepare($sql);
        foreach ($params as $key => &$value) {
            $stmt->bindParam($key, $value, PDO::PARAM_STR);
        }
        $stmt->execute();
        $mens_fashion = $stmt->fetchAll();

        $itemsPerPage = 12;
        $start = ($page - 1) * $itemsPerPage;
        $end = $start + $itemsPerPage;
        $totalItems = count($mens_fashion);
        $totalPages = ceil($totalItems / $itemsPerPage);
        $categoryCounts = [];
        foreach ($mens_fashion as $product) {
            $categoryName = $product['Product_type'];
            if (!isset($categoryCounts[$categoryName])) {
                $categoryCounts[$categoryName] = 0;
            }
            $categoryCounts[$categoryName]++;
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