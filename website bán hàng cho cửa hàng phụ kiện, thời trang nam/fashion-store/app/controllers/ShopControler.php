<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\PhpRenderer;
use App\Models\ProductModel;
use App\Models\CachingAndSession\RedisService;
use PDO;

class ShopController
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $db = $this->container->get('db');
        $view = $this->container->get('view');
        $productModel = new ProductModel($db);

        $queryParams = $request->getQueryParams();
        $sort = $queryParams['sort'] ?? null;
        $filters = [
            'filter' => $queryParams['filter'] ?? '',
            'priceFilter' => $queryParams['priceFilter'] ?? null,
            'search' => trim($queryParams['search'] ?? '')
        ];

        $page = max(1, (int) ($queryParams['page'] ?? 1));
        $itemsPerPage = 12;
        $start = ($page - 1) * $itemsPerPage;

        $mens_fashion = $productModel->getFilteredProducts($filters, $sort);
        $totalItems = count($mens_fashion);
        $totalPages = ceil($totalItems / $itemsPerPage);

        $categoryCounts = [];
        foreach ($mens_fashion as $product) {
            $category = $product['Product_type'];
            $categoryCounts[$category] = ($categoryCounts[$category] ?? 0) + 1;
        }

        $userId = $_SESSION['user_id'] ?? null;
        $name = null;
        $cart = [];

        if ($userId) {
            $stmt = $db->prepare('SELECT fullname FROM customer WHERE id =:id;');
            $stmt->bindParam(':id', $userId);
            $stmt->execute();
            $name = $stmt->fetchColumn() ?? null;

            $redis = new RedisService();
            if ($redis->get('user_token_' . $userId) !== null) {
                $stmt = $db->prepare('SELECT mf.Id AS product_id
                                      FROM orders o
                                      JOIN order_product op ON o.id = op.order_id
                                      JOIN mens_fashion mf ON op.product_id = mf.Id
                                      WHERE o.customer_id = :user_id;');
                $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $stmt->execute();
                $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $cart = array_map(function ($item) use ($db) {
                    $stmt = $db->prepare('SELECT * FROM mens_fashion WHERE Id = :id');
                    $stmt->bindParam(':id', $item['product_id']);
                    $stmt->execute();
                    return $stmt->fetch(PDO::FETCH_ASSOC);
                }, $cartItems);
            }
        }

        $cartPrice = array_sum(array_column($cart, 'Price'));

        return $view->render($response, 'shop.php', [
            'fullname' => $name,
            'page' => $page,
            'categoryCounts' => $categoryCounts,
            'start' => $start,
            'end' => $start + $itemsPerPage,
            'mens_fashion' => $mens_fashion,
            'totalItems' => $totalItems,
            'totalPages' => $totalPages,
            'cart' => $cart,
            'cartPrice' => $cartPrice,
        ]);
    }
}
