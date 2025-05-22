<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\PhpRenderer;
use App\Models\ProductModel;
use App\Models\CachingAndSession\RedisService;
use PDO;
use DateTime;
use DateInterval;


class ShopController
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }
    public function show($request, $response, $args)
    {
        $id = $args['id'];
        $db = $this->container->get('db');
        $productModel = new ProductModel($db);
        $product = $productModel->getProductById($id);
        if (!$product) {
            return $response->withStatus(404)->write('Product not found');
        }
        $view = $this->container->get('view');
        return $view->render($response, 'product-details.php', ['product' => $product]);
    }

    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $userId = $_SESSION['user_id'] ?? null;
        $redis = new RedisService();
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
        $name = null;
        $cart = [];
        $cartPrice = 0;
        if ($userId!== null) {
            $stmt = $db->prepare('SELECT fullname FROM customer WHERE id =:id;');
            $stmt->bindParam(':id', $userId);
            $stmt->execute();
            $name = $stmt->fetchColumn() ?? null;
            $redis = new RedisService();
            if ($redis->get('user_token_' . $userId) !== null) {
                $stmt = $db->prepare('SELECT mf.*, op.quantity, o.id AS order_id
                                    FROM orders o
                                    JOIN order_product op ON o.id = op.order_id
                                    JOIN mens_fashion mf ON op.product_id = mf.Id
                                    WHERE o.customer_id = :user_id AND o.cur_status = 0;');
                $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $stmt->execute();
                $cart = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            foreach ($cart as $item) {
                $cartPrice += ($item['Price'] ?? 0) * ($item['quantity'] ?? 0);
            }
        }
        
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
    public function addToCart($request, $response, $args) {
        $db = $this->container->get('db');
        $productId = $args['id'];
        $userId = $_SESSION['user_id'] ?? null;
        $date = (new \DateTime())->format('Y-m-d');
        if (!$userId) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }
        $redis = new RedisService();
        if ($redis->get('user_token_' . $userId) === null) {
            return $response->withHeader('Location', '/logout')->withStatus(302);
        }
        $stmt = $db->prepare('SELECT COUNT(*) FROM orders WHERE customer_id = :user_id AND order_date = :order_date');
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':order_date', $date);
        $stmt->execute();
        $orderCount = $stmt->fetchColumn();
        if ($orderCount > 0) {
            $stmt = $db->prepare('SELECT id FROM orders WHERE customer_id = :user_id AND order_date = :order_date');
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':order_date', $date);
            $stmt->execute();
            $orderId = array($stmt->fetchColumn());
            foreach ($orderId as $id) {
                $stmt = $db->prepare('SELECT cur_status FROM orders WHERE id = :order_id');
                $stmt->bindParam(':order_id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $curStatus = $stmt->fetchColumn();
                if ($curStatus == 0) {
                    $stmt = $db->prepare('SELECT quantity FROM order_product WHERE order_id = :order_id AND product_id = :product_id');
                    $stmt->bindParam(':order_id', $id, PDO::PARAM_INT);
                    $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
                    $stmt->execute();
                    $quantity = $stmt->fetchColumn();
                    if ($quantity > 0) {
                        $stmt = $db->prepare('UPDATE order_product SET quantity = quantity + 1 WHERE order_id = :order_id AND product_id = :product_id');
                        $stmt->bindParam(':order_id', $id, PDO::PARAM_INT);
                        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
                        $stmt->execute();
                    } else {
                        $stmt = $db->prepare('INSERT INTO order_product (order_id, product_id) VALUES (:order_id, :product_id)');
                        $stmt->bindParam(':order_id', $id, PDO::PARAM_INT);
                        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
                        $stmt->execute();
                    }
                } else {
                    $stmt = $db->prepare('INSERT INTO orders (customer_id, order_date) VALUES (:user_id, :order_date)');
                    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                    $stmt->bindParam(':order_date', $date);
                    $stmt->execute();
                    $orderId = $db->lastInsertId();
                    $stmt = $db->prepare('INSERT INTO order_product (order_id, product_id) VALUES (:order_id, :product_id)');
                    $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
                    $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
                    $stmt->execute();
                    $stmt = $db->prepare('UPDATE orders SET cur_status = 0 WHERE id = :order_id');
                    $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }
        } else {
            $stmt = $db->prepare('INSERT INTO orders (customer_id, order_date) VALUES (:user_id, :order_date)');
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':order_date', $date);
            $stmt->execute();
            $orderId = $db->lastInsertId();
            $stmt = $db->prepare('INSERT INTO order_product (order_id, product_id) VALUES (:order_id, :product_id)');
            $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
            $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
            $stmt->execute();
            $stmt = $db->prepare('UPDATE orders SET cur_status = 0 WHERE id = :order_id');
            $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
            $stmt->execute();
        }
        return $response->withHeader('Location', '/shop')->withStatus(302);
    }
    public function updateCart($request, $response, $args) { 
        $db = $this->container->get('db');
        $userId = $_SESSION['user_id'] ?? null;
        $date = $_SESSION['order_date'] ?? (new \DateTime())->format('Y-m-d');
    
        if (!$userId) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }
    
        $redis = new RedisService();
        if ($redis->get('user_token_' . $userId) === null) {
            return $response->withHeader('Location', '/logout')->withStatus(302);
        }
    
        $data = $request->getParsedBody();
        $quantities = $data['quantity'] ?? [];
        
        $stmt = $db->prepare("
            SELECT id FROM orders 
            WHERE customer_id = :user_id AND order_date = :order_date AND cur_status = 0 
            LIMIT 1
        ");
        $stmt->execute([
            ':user_id' => $userId,
            ':order_date' => $date
        ]);
        $order = $stmt->fetch();
    
        if (!$order) {
            return $response->withHeader('Location', '/shopping-cart')->withStatus(404);
        }
    
        $orderId = $order['id'];
    
        foreach ($quantities as $productId => $quantity) {
            $quantity = max(1, (int)$quantity);
    
            $stmt = $db->prepare("
                UPDATE order_product 
                SET quantity = :quantity 
                WHERE order_id = :order_id AND product_id = :product_id
            ");
            $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
            $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
            $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
            $stmt->execute();
        }
    
        return $response->withHeader('Location', '/shopping-cart')->withStatus(302);
    }
    public function removeProduct($request, $response, $args) {
        $db = $this->container->get('db');
        $userId = $_SESSION['user_id'] ?? null;
        $productId = $_POST['remove_product_id'] ?? null;
        $date = $_SESSION['order_date'] ?? (new \DateTime())->format('Y-m-d');
    
        if (!$productId) {
            return $response->withHeader('Location', '/shopping-cart')->withStatus(404);
        }
    
        if (!$userId) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }
    
        $redis = new RedisService();
        if ($redis->get('user_token_' . $userId) === null) {
            return $response->withHeader('Location', '/logout')->withStatus(302);
        }
    
        // Lấy order_id hiện tại (status = 0)
        $stmt = $db->prepare('SELECT id FROM orders WHERE customer_id = :user_id AND order_date= :order_date AND cur_status = 0 LIMIT 1');
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':order_date', $date);
        $stmt->execute();
        $orderId = $stmt->fetch();
    
        if ($orderId) {
            $stmt = $db->prepare('DELETE FROM order_product WHERE order_id = :order_id AND product_id = :product_id');
            $stmt->bindParam(':order_id', $orderId['id']);
            $stmt->bindParam(':product_id', $productId);
            $stmt->execute();
        }
    
        return $response->withHeader('Location', '/shopping-cart')->withStatus(302);
    }
    public function checkout($request, $response, $args) {
        $db = $this->container->get('db');
        $userId = $_SESSION['user_id'] ?? null;
        $date = (new \DateTime())->format('Y-m-d');
    
        if (!$userId) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }
    
        $redis = new RedisService();
        if ($redis->get('user_token_' . $userId) === null) {
            return $response->withHeader('Location', '/logout')->withStatus(302);
        }
    
        // Lấy order_id hiện tại (status = 0)
        $stmt = $db->prepare('SELECT id FROM orders WHERE customer_id = :user_id AND order_date= :order_date AND cur_status = 0 LIMIT 1');
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':order_date', $date);
        $stmt->execute();
        $orderId = $stmt->fetch();
    
        if ($orderId) {
            // Cập nhật trạng thái đơn hàng thành đã thanh toán
            $stmt = $db->prepare('UPDATE orders SET cur_status = 1 WHERE id = :order_id');
            $stmt->bindParam(':order_id', $orderId['id']);
            $stmt->execute();
        }
    
        return $response->withHeader('Location', '/shop')->withStatus(302);
    }
    public function vnpay_create_payment($request, $response, $args) {
        $db = $this->container->get('db');
        $userId = $_SESSION['user_id'] ?? null;
        $date = (new DateTime())->format('Y-m-d');

        if (!$userId) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }

        $redis = new RedisService();
        if ($redis->get('user_token_' . $userId) === null) {
            return $response->withHeader('Location', '/logout')->withStatus(302);
        }

        $stmt = $db->prepare('
            SELECT id FROM orders 
            WHERE customer_id = :user_id AND order_date = :order_date AND cur_status = 0 
            LIMIT 1
        ');
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':order_date', $date);
        $stmt->execute();
        $order = $stmt->fetch();
        
        if (!$order) {
            return $response->withHeader('Location', '/cart')->withStatus(302);
        }

        $order_id = $order['id'];

        $stmt = $db->prepare('
            SELECT SUM(op.quantity * mf.Price) AS total_amount
            FROM orders o
            JOIN order_product op ON o.id = op.order_id
            JOIN mens_fashion mf ON op.product_id = mf.Id
            WHERE o.customer_id = :user_id AND o.cur_status = 0
        ');
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $amount = (int)($stmt->fetchColumn() ?? 0)*100;

        $stmt = $db->prepare('SELECT fullname FROM customer WHERE id = :user_id');
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $fullName = $stmt->fetchColumn() ?? null;

        
        $vnp_IpAddr = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];
        date_default_timezone_set('Asia/Ho_Chi_Minh'); // Đặt múi giờ về GMT+7

        $expire = new DateTime(); // Thời gian hiện tại
        $expire->add(new DateInterval('PT15M')); // Cộng thêm 15 phút (ví dụ timeout 15 phút)
        $vnp_ExpireDate = $expire->format('YmdHis'); // Định dạng thời gian theo yêu cầu của VNPAY
        require_once(__DIR__ . '/../config/vnpay_create_payment.php');
        vnpay_create_payment($amount, $order_id, $order_id, $vnp_ExpireDate, $fullName, $vnp_IpAddr);
        exit;
    }
    public function checkCartEmpty($request, $response, $args)
    {
        // Trong ShopController
        $db = $this->container->get('db');
        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) return true;

        $stmt = $db->prepare('SELECT COUNT(*) FROM orders o JOIN order_product op ON o.id = op.order_id WHERE o.customer_id = :user_id AND o.cur_status = 0');
        $stmt->execute([':user_id' => $userId]);
        $count = $stmt->fetchColumn();

        return $count == 0;
    }
    public function return($request, $response, $args) {
        $vnpay_response = $_GET;
        $vnp_SecureHash = hash_hmac('sha512', implode('', [
            $vnpay_response['vnp_TxnRef'],
            $vnpay_response['vnp_Amount'],
            $vnpay_response['vnp_Command'],
            $vnpay_response['vnp_CurrCode'],
            $vnpay_response['vnp_Lang'],
            $vnpay_response['vnp_ReturnUrl'],
            $vnpay_response['vnp_NotifyUrl'],
        ]), 'YOUR_SECRET_KEY');
        if ($vnpay_response['vnp_SecureHash'] === $vnp_SecureHash) {
            // Xử lý đơn hàng thành công
            $db = $this->container->get('db');
            $stmt = $db->prepare('UPDATE orders SET cur_status = 1 WHERE id = :order_id');
            $stmt->bindParam(':order_id', $vnpay_response['vnp_TxnRef']);
            $stmt->execute();
            return $response->withHeader('Location', '/shopping-cart')->withStatus(302);
        } else {
            // Xử lý đơn hàng thất bại
            return $response->withHeader('Location', '/shopping-cart')->withStatus(302);
        }
    }
}
