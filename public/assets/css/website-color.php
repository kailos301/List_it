<?php
header("Content-Type:text/css");

// get the colors from query parameter
$primaryColor = $_GET['primary_color'];
$secondaryColor = $_GET['secondary_color'];
$breadcrumbOverlayColor = $_GET['breadcrumb_overlay_color'];

// check, whether color has '#' or not, will return 0 or 1
function checkColorCode($color)
{
  return preg_match('/^#[a-f0-9]{6}/i', $color);
}

// if, primary color value does not contain '#', then add '#' before color value
if (isset($primaryColor) && (checkColorCode($primaryColor) == 0)) {
  $primaryColor = '#' . $primaryColor;
}

// if, secondary color value does not contain '#', then add '#' before color value
if (isset($secondaryColor) && (checkColorCode($secondaryColor) == 0)) {
  $secondaryColor = '#' . $secondaryColor;
}

// if, breadcrumb overlay color value does not contain '#', then add '#' before color value
if (isset($breadcrumbOverlayColor) && (checkColorCode($breadcrumbOverlayColor) == 0)) {
  $breadcrumbOverlayColor = '#' . $breadcrumbOverlayColor;
}

// then add color to style
?>

:root {
--primary-color: <?php echo htmlspecialchars($secondaryColor); ?>;
--secondary-color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.lds-ellipsis span {
background: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.header-area-one .header-top-bar {
background-color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.header-area-two .header-navigation .header-right-nav .cart-button .cart-btn span#product-count,
.header-area-one .header-top-bar .top-right ul li .cart-btn span#product-count {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.header-navigation .main-menu ul li>a {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.header-navigation .main-menu ul li:hover>a {
color: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.header-navigation .main-menu ul>li.menu-item-has-children>a:after {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.header-navigation .main-menu ul li:hover.menu-item-has-children>a:after {
color: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.header-navigation .main-menu ul li .sub-menu li a {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.header-navigation .main-menu ul li .sub-menu li:hover>a {
background-color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.header-area-one .header-navigation .header-right-nav ul.social-link li a {
background-color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.hero-slider-one .slick-arrow {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

h1, h2, h3, h4, h5, h6 {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.main-btn:hover {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.process-item-one .count-box .icon i {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.process-item-one .count-box {
background-color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.features-item-one .icon i {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.bg-with-overlay:after {
background-color: <?php echo htmlspecialchars($primaryColor) . 'DE'; ?>;
}

.counter-item-one .icon {
background: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.counter-item-one .icon i {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.pricing-item .pricing-info .pricing-body .price-option span.span-btn {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.pricing-item-one .pricing-info .pricing-body span.delivary {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.pricing-item .pricing-info .pricing-body ul.info-list li:before {
background-color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.testimonial-item-one .testimonial-content .quote i {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.blog-post-item-one .post-thumbnail .cat-btn {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.blog-post-item-one .entry-content .btn-link {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.newsletter-wrapper-one:after {
background: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.newsletter-wrapper-one .newsletter-form .newsletter-btn {
background: <?php echo htmlspecialchars($primaryColor); ?>;
}

.footer-area-one:after {
background-color: <?php echo htmlspecialchars($primaryColor) . 'F2'; ?>;
}

.back-to-top {
background: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.back-to-top:hover, .back-to-top:focus {
background: <?php echo htmlspecialchars($primaryColor); ?>;
}

.breadcrumbs-area:after {
background-color: <?php echo htmlspecialchars($breadcrumbOverlayColor) . 'CF'; ?>;
}


.equipments-search-filter .search-filter-form {
background-color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.pricing-item-three .pricing-img span.discount {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.equipement-sidebar-info .booking-form .price-info {
background-color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.equipement-sidebar-info .booking-form .price-info .price-tag h4 span {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.equipement-sidebar-info .booking-form .pricing-body .price-option span.span-btn {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.checkout-area-section .coupon .btn, .equipment-details-section .pricing-body .extra-option .btn {
background-color: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.equipment-gallery-slider .slick-arrow {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.description-wrapper .voucher-btn {
color: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.description-wrapper .description-tabs .nav-link {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.product-item-two .product-img .product-overlay {
background-color: <?php echo htmlspecialchars($primaryColor) . 'CC'; ?>;
}

.product-item-two .product-img .product-overlay .product-meta a {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.products-gallery-wrap .products-thumb-slider .slick-arrow,
.products-gallery-wrap .products-big-slider .slick-arrow {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.products-gallery-wrap .products-thumb-slider .slick-arrow:hover,
.products-gallery-wrap .products-big-slider .slick-arrow:hover {
background-color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.products-details-wrapper .product-info .product-tags a {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.cart-area-section .total-item-info li {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.cart-area-section .cart-table thead tr th {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.cart-area-section .cart-middle .cart-btn {
background-color: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.cart-area-section .cart-middle .cart-btn:hover {
background-color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.sidebar-widget-area .widget.search-widget .search-btn {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.sidebar-widget-area .widget.categories-widget ul.widget-link li a {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.faq-wrapper-one .card .card-header {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.header-area-two .header-top-bar .top-left span {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.header-area-two .header-top-bar:after {
background-color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.header-area-two .header-navigation .header-right-nav .cart-button .cart-btn {
background-color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.header-area-two .header-navigation .header-right-nav .user-info a {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.hero-wrapper-two .hero-search-wrapper {
background-color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.process-item-two .count-box .icon i {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.dark-blue {
background-color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.features-item-two.active-item .icon, .features-item-two:hover .icon {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.counter-item-two .icon {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.counter-item-two .icon:after {
background-color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.pricing-item-two .pricing-info .price-info {
background-color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.pricing-item-two .pricing-info .pricing-body .price-option span.span-btn.active-btn {
background-color: <?php echo htmlspecialchars($secondaryColor) . 'AC'; ?>;
}

.pricing-item-two .pricing-info .pricing-body span.delivary {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.pricing-item-two .pricing-info .pricing-bottom {
background-color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.main-btn-primary {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.blog-post-item-two .post-thumbnail .category {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.blog-post-item-two .post-thumbnail .category::after {
background-color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.newsletter-wrapper-two:after {
background-color: <?php echo htmlspecialchars($secondaryColor) . 'E6'; ?>;
}
