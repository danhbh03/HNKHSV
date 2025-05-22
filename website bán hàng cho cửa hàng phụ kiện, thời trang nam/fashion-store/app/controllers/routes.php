<?php
declare(strict_types=1);

use Slim\App;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Container\ContainerInterface;
require_once __DIR__ . '/../models/userModel.php';
require_once __DIR__ . '/../models/productModel.php';
require_once __DIR__ . '/../models/RabbitMQService.php';
require_once __DIR__ . '/../models/RedisService.php';
require_once __DIR__ . '/./ShopControler.php';
require_once __DIR__ . '/./AuthController.php';
use App\Models\UserModel;
use App\Models\CachingAndSession\RedisService;
use App\Models\ProductModel;
use App\Controllers\ShopController;
use App\Controllers\AuthController;
use function PHPUnit\Framework\equalToIgnoringCase;

return function (App $app) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $app->get('/', function (RequestInterface $request, ResponseInterface $response, $args) use ($app) {
        return $response->withHeader('Location', '/index')->withStatus(302);
    });
    $app->get('/shop-details', function (RequestInterface $request, ResponseInterface $response, $args) use ($app) {
        $container = $app->getContainer();
        $view = $container->get('view');
        return $view->render($response, 'shop-details.php');
    });
    $app->get('/shopping-cart', function (RequestInterface $request, ResponseInterface $response, $args) use ($app) {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }
        $redis = new RedisService();
        if ($redis->get('user_token_' . $userId) === null) {
            return $response->withHeader('Location', '/logout')->withStatus(302);
        }
        $container = $app->getContainer();
        $view = $container->get('view');
        $db = $container->get('db');
        $stmt = $db->prepare('SELECT fullname FROM customer WHERE id =:id;');
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        $name = $stmt->fetchColumn() ?? null;
        if ($name === null) {
            return $response->withHeader('Location', '/logout')->withStatus(302);
        }
        $db = $app->getContainer()->get('db');
        $stmt = $db->prepare('SELECT mf.*, op.quantity, o.id AS order_id, o.order_date 
                            FROM orders o
                            JOIN order_product op ON o.id = op.order_id
                            JOIN mens_fashion mf ON op.product_id = mf.Id
                            WHERE o.customer_id = :user_id AND o.cur_status = 0;');
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $cart = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $date = $_SESSION['date']??(new \DateTime())->format('Y-m-d');
        $cartItem = [];
        foreach ($cart as $item) {
            if ($item['order_date'] == $date) {
                $cartItem[] = $item;
            }
        }
        $cartPrice = 0;
        foreach ($cart as $item) {
            $cartPrice += ($item['Price'] ?? 0) * ($item['quantity'] ?? 0);
        }
        $container = $app->getContainer();
        $view = $container->get('view');
        return $view->render($response, 'shopping-cart.php'
            , [
                'fullname' => $name,
                'cart' => $cartItem,
                'cartPrice' => $cartPrice,
            ]);
    });
    $app->get('/logout', function (RequestInterface $request, ResponseInterface $response, $args) use ($app) {
        $_SESSION = [];
        if (session_status() !== PHP_SESSION_NONE) {
            session_destroy();
        }

        // Xóa cookie session
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        return $response->withHeader('Location', '/login')->withStatus(302);
    });
    $app->get('/login', function (RequestInterface $request, ResponseInterface $response, $args) use ($app) {
        $container = $app->getContainer();
        $view = $container->get('view');
        return $view->render($response, 'login.php');
    });
    $app->get('/signup', function (RequestInterface $request, ResponseInterface $response, $args) use ($app) {
        $container = $app->getContainer();
        $view = $container->get('view');
        return $view->render($response, 'signup.php');
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
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }
        $redis = new RedisService();
        if ($redis->get('user_token_' . $userId) === null) {
            return $response->withHeader('Location', '/logout')->withStatus(302);
        }
        $container = $app->getContainer();
        $view = $container->get('view');
        return $view->render($response, 'contact.php');
    });
    $app->get('/index', function (RequestInterface $request, ResponseInterface $response, $args) use ($app) {
        $db = $app->getContainer()->get('db');
        $productModel = new ProductModel($db);
        $BestSeller = $productModel->getProductBySaleType(2);
        $HotSale = $productModel->getProductBySaleType(1);
        $NewArrival = $productModel->getProductBySaleType(0);
        $userId = $_SESSION['user_id'] ?? null;
        $redis = new RedisService();
        $name = null;
        $cart = null;
        $cartPrice = 0;
        if ($userId) {
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
            if ($cart!== null){
                foreach ($cart as $item) {
                    $cartPrice += ($item['Price'] ?? 0) * ($item['quantity'] ?? 0);
                }
            }    
        }
        $itemsPerPage = 8;
        $container = $app->getContainer();
        $view = $container->get('view');
        return $view->render($response, 'index.php', [
            'fullname' => $name,
            'BestSeller' => $BestSeller,
            'HotSale' => $HotSale,
            'NewArrival' => $NewArrival,
            'itemsPerPage' => $itemsPerPage,
            'cart' => $cart,
            'cartPrice' => $cartPrice,
        ]);
    });
    $app->post('/login', function (RequestInterface $request, ResponseInterface $response, $args) use ($app) {
        $container = $app->getContainer();
        $view = $container->get('view');
        $db = $container->get('db');
        $redis = new RedisService();
        $data = $request->getParsedBody();
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';
        $userModel = new UserModel($db);
        $user = $userModel->findByUsername($username);

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user'] = $user;
            $_SESSION['user_id'] = $user['id'];

            $oldToken = $user['token'] ?? null;
            $newToken = bin2hex(random_bytes(16));
            $redis = new RedisService();

            if ($oldToken === null) {
                $_SESSION['user_token'] = $newToken;
                $userModel->updateToken($user['id'], $newToken);
                $redis->set('user_token_' . $user['id'], $newToken, 3600);

                return $response->withHeader('Location', '/shop')->withStatus(302);
            } else {
                $cachedToken = $redis->get('user_token_' . $user['id']);
                if ($cachedToken === null) {
                    $_SESSION['user_token'] = $newToken;
                    $userModel->updateToken($user['id'], $newToken);
                    $userModel->updateToken($user['id'], $newToken);
                    $redis->set('user_token_' . $user['id'], $newToken, 3600);
                    $_SESSION['user_token'] = $newToken;
                    return $response->withHeader('Location', '/shop')->withStatus(302);
                }                
                if ($cachedToken !== $oldToken) {
                    $response->getBody()->write("Tài khoản đang được sử dụng ở nơi khác.");
                    return $response;
                } else {
                    $userModel->updateToken($user['id'], $newToken);
                    $redis->set('user_token_' . $user['id'], $newToken, 3600);
                    $_SESSION['user_token'] = $newToken;
                    return $response->withHeader('Location', '/shop')->withStatus(302);
                }
            }
        } else {
            return $view->render($response, 'login.php', ['error' => 'Invalid username or password.']);
        }
    });
    $app->get('/shop', function ($request, $response, $args) use ($app) {
        $container = $app->getContainer();
        $controller = new ShopController($container);
        return $controller->index($request, $response, $args);
    });
    $app->get('/shop/{id}', function ($request, $response, $args) use ($app) {
        $container = $app->getContainer();
        $controller = new ShopController($container);
        return $controller->addToCart($request, $response, $args);
    });
    // $app->post('/shop/{id}', function ($request, $response, $args) use ($app) {
    //     $container = $app->getContainer();
    //     $controller = new ShopController($container);
    //     return $controller->addToCart($request, $response, $args);
    // }); 
    
    $app->post("/shopping-cart/update", function ($request,$response,$args) use ($app){
        $container = $app->getContainer();
        $ShopController = new ShopController($container);
        if (isset($_POST['remove_product_id'])) {
            return $ShopController->removeProduct($request,$response,$args);
        }
        return $ShopController->updateCart($request,$response,$args);
    });
    $app->post('/signup', function ($request,$response, $args) use ($app) {
        $container = $app->getContainer();
        $view = $container->get('view');
        $db = $container->get('db');
        $authController = new AuthController($view, $db);
        return $authController->signup($request, $response, $args);
    });
    $app->post('/vnpay_create_payment', function ($request, $response, $args) use ($app) {
        $container = $app->getContainer();
        $view = $container->get('view');
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }
        $db = $container->get('db');
        $shopController = new ShopController($container);
        if($shopController->checkCartEmpty($request,$response,$args) == true){
            return $response->withHeader('Location', '/shopping-cart',['error' => 'Empty Cart.'])->withStatus(404);
        }
        return $shopController->vnpay_create_payment($request, $response, $args);
    });

};
?>
