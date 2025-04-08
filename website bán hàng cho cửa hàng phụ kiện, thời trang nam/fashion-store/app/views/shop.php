<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Male_Fashion Template">
    <meta name="keywords" content="Male_Fashion, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Male-Fashion | Template</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&display=swap"
    rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="/css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="/css/magnific-popup.css" type="text/css">
    <link rel="stylesheet" href="/css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="/css/style.css" type="text/css">
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
                <a href="#">Sign in</a>
                <a href="#">FAQs</a>
            </div>
            <div class="offcanvas__top__hover">
                <span>Usd <i class="arrow_carrot-down"></i></span>
                <ul>
                    <li>USD</li>
                    <li>EUR</li>
                    <li>USD</li>
                </ul>
            </div>
        </div>
        <div class="offcanvas__nav__option">
            <a href="#" class="search-switch"><img src="/img/icon/search.png" alt=""></a>
            <a href="#"><img src="/img/icon/heart.png" alt=""></a>
            <a href="#"><img src="/img/icon/cart.png" alt=""> <span>0</span></a>
            <div class="price">$0.00</div>
        </div>
        <div id="mobile-menu-wrap"></div>
        <div class="offcanvas__text">
            <p>Free shipping, 30-day return or refund guarantee.</p>
        </div>
    </div>
    <!-- Offcanvas Menu End -->

    <!-- Header Section Begin -->
    <header class="header">
        <div class="header__top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-7">
                        <div class="header__top__left">
                            <p>Free shipping, 30-day return or refund guarantee.</p>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-5">
                        <div class="header__top__right">
                            <div class="header__top__links">
                                <a href="#">Sign in</a>
                                <a href="#">FAQs</a>
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
                        <a href="./index"><img src="/img/logo.png" alt=""></a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <nav class="header__menu mobile-menu">
                        <ul>
                            <li><a href="./index">Home</a></li>
                            <li class="active"><a href="./shop">Shop</a></li>
                            <li><a href="#">Pages</a>
                                <ul class="dropdown">
                                    <li><a href="./shop-details">Shop Details</a></li>
                                    <li><a href="./shopping-cart">Shopping Cart</a></li>
                                    <li><a href="./checkout">Check Out</a></li>
                                    <li><a href="./blog-details">Blog Details</a></li>
                                </ul>
                            </li>
                            <li><a href="./blog">Blog</a></li>
                            <li><a href="./contact">Contacts</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="header__nav__option">
                        <a href="#" class="search-switch"><img src="/img/icon/search.png" alt=""></a>
                        <a href="#"><img src="/img/icon/heart.png" alt=""></a>
                        <a href="#"><img src="/img/icon/cart.png" alt=""> <span>0</span></a>
                        <div class="price"><?=htmlspecialchars($cartPrice)." VNĐ" ?></div>
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
                        <h4>Shop</h4>
                        <div class="breadcrumb__links">
                            <a href="./index">Home</a>
                            <span>Shop</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Shop Section Begin -->
    <section class="shop spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="shop__sidebar">
                        <div class="shop__sidebar__search">
                            <form method="get" action="/shop">
                                <input type="text" id="search-input" name="search" placeholder="Tìm kiếm ở đây.....">
                                <button type="submit"><span class="icon_search"></span></button>
                            </form>
                        </div>
                        <div class="shop__sidebar__accordion">
                            <div class="accordion" id="accordionExample">
                                <div class="card">
                                    <div class="card-heading">
                                        <a data-toggle="collapse" data-target="#collapseOne">Loại trang phục</a>
                                    </div>
                                    <div id="collapseOne" class="collapse show" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="shop__sidebar__categories">
                                            <ul class="nice-scroll">
                                                <li><a href="/shop?filter=all">Mọi loại</a></li>
                                                <?php foreach ($categoryCounts as $categoryName => $count): ?>
                                                    <li>
                                                        <a href="/shop?filter=<?= urlencode($categoryName) ?>">
                                                            <?= htmlspecialchars($categoryName) ?> (<?= $count ?>)
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                                
                                            </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-heading">
                                        <a data-toggle="collapse" data-target="#collapseThree">Filter Price</a>
                                    </div>
                                    <div id="collapseThree" class="collapse show" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="shop__sidebar__price">
                                                <ul>
                                                    <li>
                                                        <form method="get" action="/shop" style="display: none;" id="form_all_price">
                                                            <?php foreach ($_GET as $key => $value): ?>
                                                                <?php if ($key !== 'priceFilter'): ?>
                                                                    <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                            <input type="hidden" name="priceFilter" value="">
                                                            <button type="submit" id="btn_all_price"></button>
                                                        </form>
                                                        <a href="#" onclick="document.getElementById('btn_all_price').click(); return false;">Mọi mức giá</a>
                                                    </li>
                                                    <li>
                                                        <form method="get" action="/shop" style="display: none;" id="form_0_100">
                                                            <?php foreach ($_GET as $key => $value): ?>
                                                                <?php if ($key !== 'priceFilter'): ?>
                                                                    <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                            <input type="hidden" name="priceFilter" value="0-100">
                                                            <button type="submit" id="btn_0_100"></button>
                                                        </form>
                                                        <a href="#" onclick="document.getElementById('btn_0_100').click(); return false;">0 VNĐ - 100,000 VNĐ</a>
                                                    </li>
                                                    <li>
                                                        <form method="get" action="/shop" style="display: none;" id="form_100_200">
                                                            <?php foreach ($_GET as $key => $value): ?>
                                                                <?php if ($key !== 'priceFilter'): ?>
                                                                    <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                            <input type="hidden" name="priceFilter" value="100-200">
                                                            <button type="submit" id="btn_100_200"></button>
                                                        </form>
                                                        <a href="#" onclick="document.getElementById('btn_100_200').click(); return false;">100,000 VNĐ - 200,000 VNĐ</a>
                                                    </li>
                                                    <li>
                                                        <form method="get" action="/shop" style="display: none;" id="form_200_500">
                                                            <?php foreach ($_GET as $key => $value): ?>
                                                                <?php if ($key !== 'priceFilter'): ?>
                                                                    <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                            <input type="hidden" name="priceFilter" value="200-500">
                                                            <button type="submit" id="btn_200_500"></button>
                                                        </form>
                                                        <a href="#" onclick="document.getElementById('btn_200_500').click(); return false;">200,000 VNĐ - 500,000 VNĐ</a>
                                                    </li>
                                                    <li>
                                                        <form method="get" action="/shop" style="display: none;" id="form_500_800">
                                                            <?php foreach ($_GET as $key => $value): ?>
                                                                <?php if ($key !== 'priceFilter'): ?>
                                                                    <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                            <input type="hidden" name="priceFilter" value="500-800">
                                                            <button type="submit" id="btn_500_800"></button>
                                                        </form>
                                                        <a href="#" onclick="document.getElementById('btn_500_800').click(); return false;">500,000 VNĐ - 800,000 VNĐ</a>
                                                    </li>
                                                    <li>
                                                        <form method="get" action="/shop" style="display: none;" id="form_800_1000">
                                                            <?php foreach ($_GET as $key => $value): ?>
                                                                <?php if ($key !== 'priceFilter'): ?>
                                                                    <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                            <input type="hidden" name="priceFilter" value="800-1000">
                                                            <button type="submit" id="btn_800_1000"></button>
                                                        </form>
                                                        <a href="#" onclick="document.getElementById('btn_800_1000').click(); return false;">800,000 VNĐ - 1,000,000 VNĐ</a>
                                                    </li>
                                                    <li>
                                                        <form method="get" action="/shop" style="display: none;" id="form_1000_plus">
                                                            <?php foreach ($_GET as $key => $value): ?>
                                                                <?php if ($key !== 'priceFilter'): ?>
                                                                    <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                            <input type="hidden" name="priceFilter" value="1000+">
                                                            <button type="submit" id="btn_1000_plus"></button>
                                                        </form>
                                                        <a href="#" onclick="document.getElementById('btn_1000_plus').click(); return false;">1,000,000 VNĐ +</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="shop__product__option">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="shop__product__option__left">
                                    <p><?= htmlspecialchars("Hiển thị ".$start."–".$end. " trên ". $totalItems ." kết quả") ?></p>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="shop__product__option__right">
                                    <p>Sắp xếp theo giá:</p>
                                    <form method="get" action="/shop">
                                        <?php foreach ($_GET as $key => $value): ?>
                                            <?php if ($key !== 'sort'): ?>
                                                <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                        <select name="sort" onchange="this.form.submit()">
                                            <option value="low_to_high" <?= (isset($_GET['sort']) && $_GET['sort'] == 'low_to_high') ? 'selected' : '' ?>>Thấp đến cao</option>
                                            <option value="high_to_low" <?= (isset($_GET['sort']) && $_GET['sort'] == 'high_to_low') ? 'selected' : '' ?>>Cao đến thấp</option>
                                        </select>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php for ($i = $start; $i < $end && $i < count($mens_fashion); $i++): ?>
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="product__item">
                                    <div class="product__item__pic set-bg" data-setbg="<?= htmlspecialchars("/img/product/".$mens_fashion[$i]['Image']) ?>">
                                        <ul class="product__hover">
                                            <li><a href="#"><img src="/img/icon/heart.png" alt=""></a></li>
                                            <li><a href="#"><img src="/img/icon/compare.png" alt=""> <span>Compare</span></a></li>
                                            <li><a href="#"><img src="/img/icon/search.png" alt=""></a></li>
                                        </ul>
                                    </div>
                                    <div class="product__item__text">
                                        <h6><?= htmlspecialchars($mens_fashion[$i]['Product_name']) ?></h6>
                                        <div class="rating">
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        </div>
                                        <a href="#" class="add-cart">+ Add To Cart</a>
                                        <h5><?= htmlspecialchars(number_format($mens_fashion[$i]['Price'], 0, '.', ',') . " VNĐ") ?></h5>
                                    </div>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="product__pagination">
                                <?php
                                    $queryParams = $_GET;
                                ?>

                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <?php
                                        $queryParams['page'] = $i;
                                        $queryString = http_build_query($queryParams);
                                    ?>
                                    <a href="?<?= htmlspecialchars($queryString) ?>" class="<?= $i == $page ? 'active' : ''; ?>"><?= $i; ?></a>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shop Section End -->

    <!-- Footer Section Begin -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer__about">
                        <div class="footer__logo">
                            <a href="#"><img src="/img/footer-logo.png" alt=""></a>
                        </div>
                        <p>The customer is at the heart of our unique business model, which includes design.</p>
                        <a href="#"><img src="/img/payment.png" alt=""></a>
                    </div>
                </div>
                <div class="col-lg-2 offset-lg-1 col-md-3 col-sm-6">
                    <div class="footer__widget">
                        <h6>Shopping</h6>
                        <ul>
                            <li><a href="#">Clothing Store</a></li>
                            <li><a href="#">Trending Shoes</a></li>
                            <li><a href="#">Accessories</a></li>
                            <li><a href="#">Sale</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-6">
                    <div class="footer__widget">
                        <h6>Shopping</h6>
                        <ul>
                            <li><a href="#">Contact Us</a></li>
                            <li><a href="#">Payment Methods</a></li>
                            <li><a href="#">Delivary</a></li>
                            <li><a href="#">Return & Exchanges</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 offset-lg-1 col-md-6 col-sm-6">
                    <div class="footer__widget">
                        <h6>NewLetter</h6>
                        <div class="footer__newslatter">
                            <p>Be the first to know about new arrivals, look books, sales & promos!</p>
                            <form action="#">
                                <input type="text" placeholder="Your email">
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
            <form class="search-model-form" method="get" action="/shop">
                <input type="text" id="search-input" name="search" placeholder="Tìm kiếm ở đây.....">
            </form>
        </div>
    </div>
    <!-- Search End -->

    <!-- Js Plugins -->
    <script src="/js/jquery-3.3.1.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/jquery.nice-select.min.js"></script>
    <script src="/js/jquery.nicescroll.min.js"></script>
    <script src="/js/jquery.magnific-popup.min.js"></script>
    <script src="/js/jquery.countdown.min.js"></script>
    <script src="/js/jquery.slicknav.js"></script>
    <script src="/js/mixitup.min.js"></script>
    <script src="/js/owl.carousel.min.js"></script>
    <script src="/js/main.js"></script>
</body>

</html>