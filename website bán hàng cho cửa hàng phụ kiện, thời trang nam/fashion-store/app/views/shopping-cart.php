<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Male_Fashion Template">
    <meta name="keywords" content="Male_Fashion, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Male-Fashion | Đơn hàng</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&display=swap"
    rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="css/magnific-popup.css" type="text/css">
    <link rel="stylesheet" href="css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Offcanvas Menu Begin -->
    <div class="offcanvas-menu-overlay"></div>
    <div class="offcanvas-menu-wrapper">
    <div class="offcanvas__option">
            <div class="offcanvas__links">
            <?php if (!empty($fullname)): ?>
                <a href="/profile" class="primary-btn">Xin chào <?= htmlspecialchars($fullname) ?></a>
                <a href="/logout" class="primary-btn">Đăng xuất</a>
            <?php else: ?>
                <a href="/login" class="primary-btn">Đăng nhập</a>
            <?php endif; ?>
            </div>
            <div class="offcanvas__top__hover">
                <span>VNĐ <i class="arrow_carrot-down"></i></span>
                <!-- <ul>
                    <li>USD</li>
                    <li>EUR</li>
                    <li>USD</li>
                </ul> -->
            </div>
        </div>
        <div class="offcanvas__nav__option">
            <a href="#" class="search-switch"><img src="/img/icon/search.png" alt=""></a>
            <a href="#"><img src="/img/icon/heart.png" alt=""></a>
            <a href="#"><img src="/img/icon/cart.png" alt=""> <span><?=htmlspecialchars(array_sum(array_column($cart, 'quantity'))) ?></span></a>
            <div class="price" id="displaytotal"><?=htmlspecialchars($cartPrice)." VNĐ" ?></div>
        </div>
        <div id="mobile-menu-wrap"></div>
        <div class="offcanvas__text">
            <p>Miễn phí vận chuyển, chấp nhận hoàn trả trong vòng 30 ngày</p>
        </div>
    </div>
    <!-- Offcanvas Menu End -->

    <!-- Header Section Begin -->
    <header class="header">
        <div class="header__top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5 col-md-6" style="display: flex; align-items: center;">
                        <div class="header__top__left">
                            <p>Miễn phí vận chuyển, chấp nhận hoàn trả trong vòng 30 ngày</p>
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-6">
                        <div class="header__top__right" style="display: flex; justify-content: flex-end; align-items: center; gap: 15px;">
                            <div class="header__top__links">
                            <?php if ($fullname!==null): ?>
                                <a href="/profile" class="primary-btn">Xin chào <?= htmlspecialchars($fullname ?? $_SESSION['user']['username']) ?></a>
                                <a href="/logout" class="primary-btn">Đăng xuất</a>
                            <?php else: ?>
                                <a href="/login" class="primary-btn">Đăng nhập</a>
                            <?php endif; ?>
                            </div>
                            <div class="header__top__hover">
                                <span>VND <i class="arrow_carrot-down"></i></span>
                                <!-- <ul>
                                    <li>USD</li>
                                    <li>EUR</li>
                                    <li>USD</li>
                                </ul> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3">
                    <div class="header__logo">
                        <a href="./index"><img src="img/logo.png" alt=""></a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <nav class="header__menu mobile-menu">
                        <ul>
                            <li><a href="./index">Trang Chủ</a></li>
                            <li class="active"><a href="./shop">Mua Hàng</a></li>
                            <!-- <li><a href="#">Pages</a>
                                <ul class="dropdown">
                                    <li><a href="./shop-details">Shop Details</a></li>
                                    <li><a href="./shopping-cart">Shopping Cart</a></li>
                                    <li><a href="./checkout">Check Out</a></li>
                                    <li><a href="./blog-details">Blog Details</a></li>
                                </ul>
                            </li>
                            <li><a href="./blog">Blog</a></li> -->
                            <li><a href="./contact">Liên Hệ</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-3 col-md-3">
                <div class="header__nav__option">
                        <a href="#" class="search-switch"><img src="/img/icon/search.png" alt=""></a>
                        <a href="#"><img src="/img/icon/heart.png" alt=""></a>
                        <a href="#"><img src="/img/icon/cart.png" alt=""></a>
                        <div class="price" id="subtotal"><?=htmlspecialchars($cartPrice)." VNĐ" ?></div>
                    </div>
                </div>
            </div>
            <div class="canvas__open"><i class="fa fa-bars"></i></div>
        </div>
    </header>
    <!-- Header Section End -->

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Đơn Hàng</h4>
                        <div class="breadcrumb__links">
                            <a href="./index">Trang Chủ</a>
                            <a href="./shop">Mua Hàng</a>
                            <span>Đơn Hàng</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Shopping Cart Section Begin -->
    <section class="shopping-cart spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="shopping__cart__table">
                        <form action="/shopping-cart/update" method="POST">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Sản Phẩm</th>
                                        <th>Số Lượng</th>
                                        <th>Tổng</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($cart as $item): ?>
                                        <tr>
                                            <td class="product__cart__item">
                                                <div class="product__cart__item__pic">
                                                    <img src="../img/product/<?php echo $item['Image']; ?>" alt="" style="width: 90px; height: 90px;">
                                                </div>
                                                <div class="product__cart__item__text">
                                                    <h6><?php echo $item['Product_name']; ?></h6>
                                                    <h5><?php echo $item['Price']." VNĐ"; ?></h5>
                                                </div>
                                            </td>
                                            <td class="quantity__item">
                                                <div class="quantity">
                                                    <div class="pro-qty-2">
                                                    <input
                                                        type="number"
                                                        class="quantity-input"
                                                        name="quantity[<?= $item['Id'] ?>]"
                                                        value="<?= $item['quantity'] ?>"
                                                        min="1"
                                                        data-price="<?= $item['Price'] ?>"
                                                        data-id="<?= $item['Id'] ?>"
                                                    >
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="cart__price" id="price-<?= $item['Id'] ?>"><?php echo $item['Price']*$item['quantity']." VNĐ"; ?></td>
                                            <td class="cart__close">
                                                <button type="submit" name="remove_product_id" value="<?= $item['Id'] ?>" class="fa fa-close" style="border: none; background: none; color: red;"></button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="continue__btn">
                                    <a href="/shop">Tiếp tục mua sắm</a>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="continue__btn update__btn">
                                    <button type="submit"><i class="fa fa-spinner"></i> Cập nhật giỏ hàng</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-4">
                    <!-- <div class="cart__discount">
                        <h6>Discount codes</h6>
                        <form action="#">
                            <input type="text" placeholder="Coupon code">
                            <button type="submit">Apply</button>
                        </form>
                    </div> -->
                    <div class="cart__total">
                        <h6>Giá đơn hàng</h6>
                        <ul>
                            <li>Tổng giá <span id="total">0 VNĐ</span></li>
                        </ul>
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?></div>
                        <form action="/vnpay_create_payment" method="POST">
                            <button type="submit" class="primary-btn" id="checkout-button">Thanh toán VNPAY</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shopping Cart Section End -->

    <!-- Footer Section Begin -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer__about">
                        <div class="footer__logo">
                            <a href="#"><img src="img/footer-logo.png" alt=""></a>
                        </div>
                        <p>Khách hàng là trung tâm trong mô hình kinh doanh độc đáo của chúng tôi, bao gồm cả thiết kế.</p>
                        <a href="#"><img src="img/payment.png" alt=""></a>
                    </div>
                </div>
                <div class="col-lg-2 offset-lg-1 col-md-3 col-sm-6">
                    <div class="footer__widget">
                        <h6>Cửa Hàng</h6>
                        <ul>
                            <li><a href="http://localhost:8080/shop?search=%C3%A1o">Áo</a></li>
                            <li><a href="http://localhost:8080/shop?search=qu%E1%BB%8B">Quần</a></li>
                            <li><a href="http://localhost:8080/shop?filter=Gi%C3%A0y">Giày</a></li>
                            <li><a href="http://localhost:8080/shop?filter=V%C3%AD">Ví</a></li>
                            <li><a href="http://localhost:8080/shop?filter=T%C3%BAi+X%C3%A1ch">Túi xách</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 offset-lg-1 col-md-3 col-sm-6">
                    <div class="footer__widget">
                        <h6>Mua Sắm</h6>
                        <ul>
                            <li><a href="http://localhost:8080/shop?search=%C3%A1o">Áo</a></li>
                            <li><a href="http://localhost:8080/shop?search=qu%E1%BB%8B">Quần</a></li>
                            <li><a href="http://localhost:8080/shop?filter=Gi%C3%A0y">Giày</a></li>
                            <li><a href="http://localhost:8080/shop?filter=V%C3%AD">Ví</a></li>
                            <li><a href="http://localhost:8080/shop?filter=T%C3%BAi+X%C3%A1ch">Túi xách</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-6">
                    <div class="footer__widget">
                        <h6>Dịch Vụ</h6>
                        <ul>
                            <li><a href="/contact">Liên Hệ với Chúng Tôi</a></li>
                            <!-- <li><a href="#">Phương Thức Thanh Toán</a></li> -->
                            <li><a href="#">Giao Hàng</a></li>
                            <li><a href="#">Trả Hàng / Đổi Hàng</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 offset-lg-1 col-md-6 col-sm-6">
                    <div class="footer__widget">
                        <h6>Tin Tức</h6>
                        <div class="footer__newslatter">
                            <p>Đăng ký để biết về sản phẩm mới, bộ sưu tập, và các ưu đãi độc quyền sớm nhất!</p>
                            <form action="#">
                                <input type="text" placeholder="Email">
                                <button type="submit"><span class="icon_mail_alt"></span></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="footer__copyright__text">
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        <p>Copyright ©
                            <script>
                                document.write(new Date().getFullYear());
                            </script>2020
                            All rights reserved | This template is made with <i class="fa fa-heart-o"
                            aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
                        </p>
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer Section End -->

    <!-- Search Begin -->
    <div class="search-model">
        <div class="h-100 d-flex align-items-center justify-content-center">
            <div class="search-close-switch">+</div>
            <form class="search-model-form">
                <input type="text" id="search-input" placeholder="Search here.....">
            </form>
        </div>
    </div>
    <!-- Search End -->

    <!-- Js Plugins -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.nice-select.min.js"></script>
    <script src="js/jquery.nicescroll.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/jquery.countdown.min.js"></script>
    <script src="js/jquery.slicknav.js"></script>
    <script src="js/mixitup.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>