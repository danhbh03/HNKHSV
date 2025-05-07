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
use App\Models\UserModel;
use App\Models\CachingAndSession\RedisService;
use App\Models\ProductModel;
use App\Controllers\ShopController;
use function PHPUnit\Framework\equalToIgnoringCase;

return function (App $app) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $app->get('/', function (RequestInterface $request, ResponseInterface $response, $args) use ($app) {
        return $response->withHeader('Location', '/index')->withStatus(302);
    });
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

        return $response->withHeader('Location', '/shop')->withStatus(302);
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
    $app->post('/login', function (RequestInterface $request, ResponseInterface $response, $args) use ($app) {
        $container = $app->getContainer();
        $view = $container->get('view');
        $db = $container->get('db');

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
            return $view->render($response, 'LoginView.php', ['error' => 'Invalid username or password.']);
        }
    });
    $app->get('/shop', function ($request, $response, $args) use ($app) {
        $container = $app->getContainer();
        $controller = new ShopController($container);
        return $controller->index($request, $response, $args);
    });    
};