<?php
require __DIR__ . '/../../auth.php';
// error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

include(__DIR__ . "/../../config/config.php");

// get invoice_id terakhir dari DB
$result = $mysqli->query("SELECT invoice_id FROM invoices ORDER BY id DESC LIMIT 1");
$last_invoice_id = null;
if ($result && $row = $result->fetch_assoc()) {
    $last_invoice_id = $row['invoice_id'];
}

function generate_next_invoice_id($last_id)
{
    if (!$last_id) {
        return "INV0001";
    }
    $num = (int) substr($last_id, 3);
    $num++;
    return "INV" . str_pad($num, 4, '0', STR_PAD_LEFT);
}

$new_invoice_id = generate_next_invoice_id($last_invoice_id);

// get data customer dari tabel customers
$customers = [];
$result_customers = $mysqli->query("SELECT id, name FROM customers ORDER BY name ASC");
if ($result_customers) {
    while ($row = $result_customers->fetch_assoc()) {
        $customers[] = $row;
    }
}

// get data produk
$products = [];
$productResult = $mysqli->query("SELECT id, product_name, qty, product_price FROM products");
while ($row = $productResult->fetch_assoc()) {
    $products[] = $row;
}
?>

<!doctype html>
<!--
* Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
* @version 1.3.2
* @link https://tabler.io
* Copyright 2018-2025 The Tabler Authors
* Copyright 2018-2025 codecalm.net Paweł Kuna
* Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
-->
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    <title>Dashboard - Tabler - Premium and Open Source dashboard template with responsive and high quality UI.</title>

    <link rel="icon" href="./favicon-dev.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="./favicon-dev.ico" type="image/x-icon" />
    <link href="../../assets/libs/jsvectormap/dist/jsvectormap.css?1749426669" rel="stylesheet" />
    <link href="../../assets/css/tabler.css?1749426669" rel="stylesheet" />

    <!-- BEGIN PLUGINS STYLES -->
    <link href="../../assets/css/tabler-flags.css?1749426669" rel="stylesheet" />
    <link href="../../assets/css/tabler-socials.css?1749426669" rel="stylesheet" />
    <link href="../../assets/css/tabler-payments.css?1749426669" rel="stylesheet" />
    <link href="../../assets/css/tabler-vendors.css?1749426669" rel="stylesheet" />
    <link href="../../assets/css/tabler-marketing.css?1749426669" rel="stylesheet" />
    <link href="../../assets/css/tabler-themes.css?1749426669" rel="stylesheet" />
    <!-- END PLUGINS STYLES -->

    <!-- BEGIN DEMO STYLES -->
    <link href="./preview/css/demo.css?1749426669" rel="stylesheet" />
    <!-- END DEMO STYLES -->

    <!-- BEGIN CUSTOM FONT -->
    <style>
        @import url('https://rsms.me/inter/inter.css');

        #product-modal {
            z-index: 1060 !important;
        }

        .modal-backdrop.product-backdrop {
            z-index: 1059 !important;
        }
    </style>

    </style>
    <!-- END CUSTOM FONT -->
    <script type="module" integrity="sha512-I1nWw2KfQnK/t/zOlALFhLrZA1yzsCzBl7DxamXdg/QF7kq+O4sYBZLl0DFCE7vP2ixPccL/k0/oqvhyDB73zQ==" src="/.11ty/reload-client.js"></script>
</head>

<body>
    <script src="../../assets/js/tabler-theme.js"></script>
    <div class="page">
        <!--  BEGIN SIDEBAR  -->
        <aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
            <div class="container-fluid">
                <!-- BEGIN NAVBAR TOGGLER -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- END NAVBAR TOGGLER -->
                <!-- BEGIN NAVBAR LOGO -->
                <div class="navbar-brand navbar-brand-autodark">
                    <a href="." aria-label="Tabler"><svg xmlns="http://www.w3.org/2000/svg" width="110" height="32" viewBox="0 0 232 68" class="navbar-brand-image">
                            <path d="M64.6 16.2C63 9.9 58.1 5 51.8 3.4 40 1.5 28 1.5 16.2 3.4 9.9 5 5 9.9 3.4 16.2 1.5 28 1.5 40 3.4 51.8 5 58.1 9.9 63 16.2 64.6c11.8 1.9 23.8 1.9 35.6 0C58.1 63 63 58.1 64.6 51.8c1.9-11.8 1.9-23.8 0-35.6zM33.3 36.3c-2.8 4.4-6.6 8.2-11.1 11-1.5.9-3.3.9-4.8.1s-2.4-2.3-2.5-4c0-1.7.9-3.3 2.4-4.1 2.3-1.4 4.4-3.2 6.1-5.3-1.8-2.1-3.8-3.8-6.1-5.3-2.3-1.3-3-4.2-1.7-6.4s4.3-2.9 6.5-1.6c4.5 2.8 8.2 6.5 11.1 10.9 1 1.4 1 3.3.1 4.7zM49.2 46H37.8c-2.1 0-3.8-1-3.8-3s1.7-3 3.8-3h11.4c2.1 0 3.8 1 3.8 3s-1.7 3-3.8 3z" fill="#066fd1" style="fill: var(--tblr-primary, #066fd1)" />
                            <path d="M105.8 46.1c.4 0 .9.2 1.2.6s.6 1 .6 1.7c0 .9-.5 1.6-1.4 2.2s-2 .9-3.2.9c-2 0-3.7-.4-5-1.3s-2-2.6-2-5.4V31.6h-2.2c-.8 0-1.4-.3-1.9-.8s-.9-1.1-.9-1.9c0-.7.3-1.4.8-1.8s1.2-.7 1.9-.7h2.2v-3.1c0-.8.3-1.5.8-2.1s1.3-.8 2.1-.8 1.5.3 2 .8.8 1.3.8 2.1v3.1h3.4c.8 0 1.4.3 1.9.8s.8 1.2.8 1.9-.3 1.4-.8 1.8-1.2.7-1.9.7h-3.4v13c0 .7.2 1.2.5 1.5s.8.5 1.4.5c.3 0 .6-.1 1.1-.2.5-.2.8-.3 1.2-.3zm28-20.7c.8 0 1.5.3 2.1.8.5.5.8 1.2.8 2.1v20.3c0 .8-.3 1.5-.8 2.1-.5.6-1.2.8-2.1.8s-1.5-.3-2-.8-.8-1.2-.8-2.1c-.8.9-1.9 1.7-3.2 2.4-1.3.7-2.8 1-4.3 1-2.2 0-4.2-.6-6-1.7-1.8-1.1-3.2-2.7-4.2-4.7s-1.6-4.3-1.6-6.9c0-2.6.5-4.9 1.5-6.9s2.4-3.6 4.2-4.8c1.8-1.1 3.7-1.7 5.9-1.7 1.5 0 3 .3 4.3.8 1.3.6 2.5 1.3 3.4 2.1 0-.8.3-1.5.8-2.1.5-.5 1.2-.7 2-.7zm-9.7 21.3c2.1 0 3.8-.8 5.1-2.3s2-3.4 2-5.7-.7-4.2-2-5.8c-1.3-1.5-3-2.3-5.1-2.3-2 0-3.7.8-5 2.3-1.3 1.5-2 3.5-2 5.8s.6 4.2 1.9 5.7 3 2.3 5.1 2.3zm32.1-21.3c2.2 0 4.2.6 6 1.7 1.8 1.1 3.2 2.7 4.2 4.7s1.6 4.3 1.6 6.9-.5 4.9-1.5 6.9-2.4 3.6-4.2 4.8c-1.8 1.1-3.7 1.7-5.9 1.7-1.5 0-3-.3-4.3-.9s-2.5-1.4-3.4-2.3v.3c0 .8-.3 1.5-.8 2.1-.5.6-1.2.8-2.1.8s-1.5-.3-2.1-.8c-.5-.5-.8-1.2-.8-2.1V18.9c0-.8.3-1.5.8-2.1.5-.6 1.2-.8 2.1-.8s1.5.3 2.1.8c.5.6.8 1.3.8 2.1v10c.8-1 1.8-1.8 3.2-2.5 1.3-.7 2.8-1 4.3-1zm-.7 21.3c2 0 3.7-.8 5-2.3s2-3.5 2-5.8-.6-4.2-1.9-5.7-3-2.3-5.1-2.3-3.8.8-5.1 2.3-2 3.4-2 5.7.7 4.2 2 5.8c1.3 1.6 3 2.3 5.1 2.3zm23.6 1.9c0 .8-.3 1.5-.8 2.1s-1.3.8-2.1.8-1.5-.3-2-.8-.8-1.3-.8-2.1V18.9c0-.8.3-1.5.8-2.1s1.3-.8 2.1-.8 1.5.3 2 .8.8 1.3.8 2.1v29.7zm29.3-10.5c0 .8-.3 1.4-.9 1.9-.6.5-1.2.7-2 .7h-15.8c.4 1.9 1.3 3.4 2.6 4.4 1.4 1.1 2.9 1.6 4.7 1.6 1.3 0 2.3-.1 3.1-.4.7-.2 1.3-.5 1.8-.8.4-.3.7-.5.9-.6.6-.3 1.1-.4 1.6-.4.7 0 1.2.2 1.7.7s.7 1 .7 1.7c0 .9-.4 1.6-1.3 2.4-.9.7-2.1 1.4-3.6 1.9s-3 .8-4.6.8c-2.7 0-5-.6-7-1.7s-3.5-2.7-4.6-4.6-1.6-4.2-1.6-6.6c0-2.8.6-5.2 1.7-7.2s2.7-3.7 4.6-4.8 3.9-1.7 6-1.7 4.1.6 6 1.7 3.4 2.7 4.5 4.7c.9 1.9 1.5 4.1 1.5 6.3zm-12.2-7.5c-3.7 0-5.9 1.7-6.6 5.2h12.6v-.3c-.1-1.3-.8-2.5-2-3.5s-2.5-1.4-4-1.4zm30.3-5.2c1 0 1.8.3 2.4.8.7.5 1 1.2 1 1.9 0 1-.3 1.7-.8 2.2-.5.5-1.1.8-1.8.7-.5 0-1-.1-1.6-.3-.2-.1-.4-.1-.6-.2-.4-.1-.7-.1-1.1-.1-.8 0-1.6.3-2.4.8s-1.4 1.3-1.9 2.3-.7 2.3-.7 3.7v11.4c0 .8-.3 1.5-.8 2.1-.5.6-1.2.8-2.1.8s-1.5-.3-2.1-.8c-.5-.6-.8-1.3-.8-2.1V28.8c0-.8.3-1.5.8-2.1.5-.6 1.2-.8 2.1-.8s1.5.3 2.1.8c.5.6.8 1.3.8 2.1v.6c.7-1.3 1.8-2.3 3.2-3 1.3-.7 2.8-1 4.3-1z" fill-rule="evenodd" clip-rule="evenodd" fill="#4a4a4a" />
                        </svg></a>
                </div>
                <!-- END NAVBAR LOGO -->
                <div class="navbar-nav flex-row d-lg-none">
                    <div class="d-none d-lg-flex">
                        <div class="nav-item">
                            <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Enable dark mode" data-bs-toggle="tooltip"
                                data-bs-placement="bottom">
                                <!-- Download SVG icon from http://tabler.io/icons/icon/moon -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                    <path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" />
                                </svg>
                            </a>
                            <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Enable light mode" data-bs-toggle="tooltip"
                                data-bs-placement="bottom">
                                <!-- Download SVG icon from http://tabler.io/icons/icon/sun -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                    <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                    <path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link d-flex lh-1 p-0 px-2" data-bs-toggle="dropdown" aria-label="Open user menu">
                            <span class="avatar avatar-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-circle">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                    <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                    <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" />
                                </svg>
                            </span>
                            <div class="d-none d-xl-block ps-2">
                                <div><?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : '' ?></div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <a href="./../../logout.php" class="dropdown-item">Logout</a>
                        </div>
                    </div>
                </div>

                <div class="collapse navbar-collapse" id="sidebar-menu">
                    <!-- BEGIN NAVBAR MENU -->
                    <ul class="navbar-nav pt-lg-3">
                        <li class="nav-item">
                            <a class="nav-link" href="/new-labpresso/dashboard.php">
                                <span class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler.io/icons/icon/home -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                        <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                                        <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                                        <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                                    </svg></span>
                                <span class="nav-link-title">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" href="invoices.php">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <!-- Download SVG icon from http://tabler.io/icons/icon/package -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-1">
                                        <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" />
                                        <path d="M12 12l8 -4.5" />
                                        <path d="M12 12l0 9" />
                                        <path d="M12 12l-8 -4.5" />
                                        <path d="M16 5.25l-8 4.5" />
                                    </svg>
                                </span>
                                <span class="nav-link-title">Invoices</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./../products/products.php">
                                <span class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler.io/icons/icon/checkbox -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                        <path d="M9 11l3 3l8 -8" />
                                        <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9" />
                                    </svg></span>
                                Products
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./../customers/customers.php">
                                <span class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler.io/icons/icon/star -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                        <path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" />
                                    </svg></span>
                                <span class="nav-link-title">
                                    Customers
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./../users/users.php">
                                <span class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler.io/icons/icon/layout-2 -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                        <path d="M4 4m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v1a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                        <path d="M4 13m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v3a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                        <path d="M14 4m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v3a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                        <path d="M14 15m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v1a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                    </svg></span>
                                <span class="nav-link-title">
                                    System Users
                                </span>
                            </a>
                        </li>
                    </ul>
                    <!-- END NAVBAR MENU -->
                </div>
            </div>
        </aside>
        <!--  END SIDEBAR  -->

        <header class="navbar navbar-expand-md d-none d-lg-flex d-print-none">
            <div class="container-xl">
                <!-- BEGIN NAVBAR TOGGLER -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- END NAVBAR TOGGLER -->

                <!-- MODE TAMPILAN -->
                <div class="navbar-nav flex-row order-md-last">
                    <div class="d-none d-md-flex">
                        <div class="nav-item">
                            <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Enable dark mode" data-bs-toggle="tooltip"
                                data-bs-placement="bottom">
                                <!-- Download SVG icon from http://tabler.io/icons/icon/moon -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                    <path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" />
                                </svg>
                            </a>
                            <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Enable light mode" data-bs-toggle="tooltip"
                                data-bs-placement="bottom">
                                <!-- Download SVG icon from http://tabler.io/icons/icon/sun -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                    <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                    <path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link d-flex lh-1 p-0 px-2" data-bs-toggle="dropdown" aria-label="Open user menu">
                            <span class="avatar avatar-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-circle">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                    <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                    <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" />
                                </svg>
                            </span>
                            <div class="d-none d-xl-block ps-2">
                                <div><?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : '' ?></div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <a href="./../../logout.php" class="dropdown-item">Logout</a>
                        </div>
                    </div>
                </div>
                <div class="collapse navbar-collapse" id="navbar-menu">
                    <!-- BEGIN NAVBAR MENU -->
                    <ul class="navbar-nav">
                    </ul>
                    <!-- END NAVBAR MENU -->
                </div>
            </div>
        </header>
        <!-- END NAVBAR  -->

        <div class="page-wrapper">
            <!-- BEGIN PAGE HEADER -->
            <div class="page-header d-print-none" aria-label="Page header">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <!-- Page pre-title -->
                            <div class="page-pretitle">
                                Overview
                            </div>
                            <h2 class="page-title">
                                Invoice
                            </h2>
                        </div>
                        <!-- Page title actions -->
                        <div class="col-auto ms-auto d-print-none">
                            <div class="btn-list">
                                <a href="invoice_recap.php" class="btn btn-secondary btn-5 d-none d-sm-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-clipboard-text">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                                        <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                        <path d="M9 12h6" />
                                        <path d="M9 16h6" />
                                    </svg>
                                    View Invoice Recap
                                </a>
                                <a href="#" class="btn btn-primary btn-5 d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-report">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-circle-dashed-plus">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M8.56 3.69a9 9 0 0 0 -2.92 1.95" />
                                        <path d="M3.69 8.56a9 9 0 0 0 -.69 3.44" />
                                        <path d="M3.69 15.44a9 9 0 0 0 1.95 2.92" />
                                        <path d="M8.56 20.31a9 9 0 0 0 3.44 .69" />
                                        <path d="M15.44 20.31a9 9 0 0 0 2.92 -1.95" />
                                        <path d="M20.31 15.44a9 9 0 0 0 .69 -3.44" />
                                        <path d="M20.31 8.56a9 9 0 0 0 -1.95 -2.92" />
                                        <path d="M15.44 3.69a9 9 0 0 0 -3.44 -.69" />
                                        <path d="M9 12h6" />
                                        <path d="M12 9v6" />
                                    </svg>
                                    Create New Invoice
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END PAGE HEADER -->

            <!-- BEGIN PAGE BODY -->
            <?php
            if (isset($_GET['success']) && $_GET['success'] == 1) {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            Invoice berhasil disimpan!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
                // Hapus parameter success dari URL segera setelah halaman dimuat
                echo "<script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.pathname);
            }
            </script>";
            }
            ?>

            <?php
            if (isset($_GET['delete']) && $_GET['delete'] === 'success') {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    Invoice berhasil dihapus!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                // Hapus parameter delete dari URL
                echo "<script>
                    if (window.history.replaceState) {
                        window.history.replaceState(null, null, window.location.pathname);
                    }
                </script>";
            }
            ?>

            <?php
            $invoice_date = $_GET['invoice_date'] ?? '';
            $status = $_GET['status'] ?? '';
            $search = $_GET['search'] ?? '';

            $perPage = isset($_GET['per_page']) ? (int) $_GET['per_page'] : 10;
            $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
            $offset = ($page - 1) * $perPage;

            // Siapkan filter WHERE
            $where = [];
            if (!empty($invoice_date)) {
                $where[] = "DATE(i.invoice_date) = '" . mysqli_real_escape_string($mysqli, $invoice_date) . "'";
            }
            if (!empty($status)) {
                $where[] = "i.status = '" . mysqli_real_escape_string($mysqli, $status) . "'";
            }
            if (!empty($search)) {
                $search = mysqli_real_escape_string($mysqli, $search);
                $where[] = "(i.invoice_id LIKE '%$search%' OR c.name LIKE '%$search%')";
            }
            $whereSql = count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "";

            // 1. Hitung total invoice unik
            $totalSql = "SELECT COUNT(DISTINCT i.id) as total 
    FROM invoices i 
    LEFT JOIN customers c ON i.customer_id = c.id 
    $whereSql";
            $totalResult = mysqli_query($mysqli, $totalSql);
            $totalData = mysqli_fetch_assoc($totalResult)['total'];
            $totalPages = ceil($totalData / $perPage);

            // 2. Ambil id invoice unik sesuai pagination
            $idSql = "SELECT DISTINCT i.id 
    FROM invoices i 
    LEFT JOIN customers c ON i.customer_id = c.id 
    $whereSql 
    ORDER BY i.id ASC 
    LIMIT $perPage OFFSET $offset";
            $idResult = mysqli_query($mysqli, $idSql);

            $invoiceIds = [];
            while ($row = mysqli_fetch_assoc($idResult)) {
                $invoiceIds[] = $row['id'];
            }

            // 3. Ambil detail invoice dan items jika ada ID
            $invoices = [];
            if (!empty($invoiceIds)) {
                $idList = implode(',', $invoiceIds);
                $sql = "SELECT 
                i.id, i.invoice_id, i.invoice_date, i.status, i.customer_id,
                c.name AS customer_name,
                ii.product_name, ii.qty, ii.price, ii.subtotal
            FROM invoices i
            LEFT JOIN customers c ON i.customer_id = c.id
            LEFT JOIN invoice_items ii ON i.id = ii.invoice_id
            WHERE i.id IN ($idList)
            ORDER BY i.id ASC";

                $result = mysqli_query($mysqli, $sql);

                while ($row = mysqli_fetch_assoc($result)) {
                    $invoice_id = $row['id'];
                    if (!isset($invoices[$invoice_id])) {
                        $invoices[$invoice_id] = [
                            'id' => $row['id'],
                            'invoice_id' => $row['invoice_id'],
                            'invoice_date' => $row['invoice_date'],
                            'customer_id' => $row['customer_id'],
                            'customer_name' => $row['customer_name'],
                            'status' => $row['status'],
                            'items' => [],
                        ];
                    }

                    if (
                        isset($row['product_name']) &&
                        isset($row['qty']) &&
                        isset($row['price']) &&
                        isset($row['subtotal'])
                    ) {
                        $invoices[$invoice_id]['items'][] = [
                            'name' => $row['product_name'],
                            'qty' => $row['qty'],
                            'price' => $row['price'],
                            'subtotal' => $row['subtotal'],
                        ];
                    }
                }
            }
            ?>

            <div class="page-wrapper">
                <div class="container-xl">
                    <!-- Page title -->
                    <div class="page-header d-print-none">
                        <div class="row align-items-center">
                            <div class="col">
                                <h2 class="page-title">Invoices</h2>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">List of invoices</h3>
                                <!-- Filter Form -->
                                <div class="ms-auto">
                                    <form method="GET" class="d-flex flex-wrap align-items-end gap-3">
                                        <div class="form-group">
                                            <label for="invoice_date" class="form-label mb-1">Tanggal Invoice</label>
                                            <input type="date" name="invoice_date" id="invoice_date" class="form-control form-control-sm"
                                                value="<?= $_GET['invoice_date'] ?? '' ?>" placeholder="Pilih tanggal invoice">
                                        </div>

                                        <div class="form-group">
                                            <label for="status" class="form-label mb-1">Status</label>
                                            <select name="status" id="status" class="form-select form-select-sm">
                                                <option value="">Semua</option>
                                                <option value="Kasbon" <?= (isset($_GET['status']) && $_GET['status'] === 'Kasbon') ? 'selected' : '' ?>>Kasbon</option>
                                                <option value="Transfer" <?= (isset($_GET['status']) && $_GET['status'] === 'Transfer') ? 'selected' : '' ?>>Transfer</option>
                                            </select>
                                        </div>

                                        <div class="form-group d-flex gap-1">
                                            <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                                            <a href="invoices.php" class="btn btn-sm btn-secondary">Reset</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="card-body border-bottom py-3">
                                <div class="d-flex">
                                    <form method="GET" class="d-flex">
                                        <div class="text-secondary">
                                            Show
                                            <div class="mx-2 d-inline-block">
                                                <select name="per_page" class="form-control form-control-sm" onchange="this.form.submit()">
                                                    <?php
                                                    $options = [5, 10, 25, 50];
                                                    $selectedPerPage = $_GET['per_page'] ?? 10;
                                                    foreach ($options as $opt) {
                                                        $selected = ($opt == $selectedPerPage) ? 'selected' : '';
                                                        echo "<option value=\"$opt\" $selected>$opt</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            entries
                                        </div>
                                        <!-- Simpan filter sebelumnya agar tidak hilang -->
                                        <?php
                                        foreach ($_GET as $key => $value) {
                                            if (!in_array($key, ['per_page', 'page'])) {
                                                echo "<input type=\"hidden\" name=\"" . htmlspecialchars($key) . "\" value=\"" . htmlspecialchars($value) . "\">";
                                            }
                                        }
                                        ?>
                                    </form>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-selectable card-table table-vcenter text-nowrap datatable">
                                    <thead>
                                        <tr>
                                            <!-- <th class="w-1"><input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select all invoices" /></th> -->
                                            <th class="w-1">No.</th>
                                            <th>Invoice ID</th>
                                            <th>Customer</th>
                                            <th>Tanggal</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Tampilkan data ke tabel
                                        $no = 1;
                                        foreach ($invoices as $inv) {
                                        ?>
                                            <tr>
                                                <!-- <td><input class="form-check-input m-0 align-middle table-selectable-check" type="checkbox" aria-label="Select invoice" /></td> -->
                                                <td><span class="text-secondary"><?= $no++; ?></span></td>
                                                <td><?= $inv['invoice_id']; ?></td>
                                                <td><?= $inv['customer_name']; ?></td>
                                                <td><?= date('d M Y', strtotime($inv['invoice_date'])); ?></td>
                                                <td>
                                                    Rp<?= number_format(array_sum(array_column($inv['items'] ?? [], 'subtotal')), 0, ',', '.'); ?>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?= $inv['status'] == 'Transfer' ? 'green' : 'yellow'; ?> text-white">
                                                        <?= $inv['status']; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-purple btn-edit-invoice"
                                                        data-id="<?= $inv['id']; ?>"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modal-edit-invoice">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                            <path d="M16 5l3 3" />
                                                        </svg>
                                                    </a>
                                                    <a href="invoice_delete.php?id=<?= $inv['id']; ?>" class="btn btn-sm btn-danger btn-delete-invoice">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M4 7l16 0" />
                                                            <path d="M10 11l0 6" />
                                                            <path d="M14 11l0 6" />
                                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                        </svg>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer">
                                <div class="row g-2 justify-content-center justify-content-sm-between">
                                    <div class="col-auto d-flex align-items-center">
                                        <!-- <?php
                                                $startEntry = $offset + 1;
                                                $endEntry = min($offset + $perPage, $totalData);
                                                ?>
                                        <p class="m-0 text-secondary">
                                            Showing <strong><?= $startEntry; ?> to <?= $endEntry; ?></strong> of <strong><?= $totalData; ?> entries</strong>
                                        </p> -->
                                    </div>
                                    <div class="col-auto">
                                        <?php if ($totalPages > 1): ?>
                                            <ul class="pagination m-0 ms-auto">
                                                <?php if ($page > 1): ?>
                                                    <li class="page-item">
                                                        <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-caret-left">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path d="M13.883 5.007l.058 -.005h.118l.058 .005l.06 .009l.052 .01l.108 .032l.067 .027l.132 .07l.09 .065l.081 .073l.083 .094l.054 .077l.054 .096l.017 .036l.027 .067l.032 .108l.01 .053l.01 .06l.004 .057l.002 .059v12c0 .852 -.986 1.297 -1.623 .783l-.084 -.076l-6 -6a1 1 0 0 1 -.083 -1.32l.083 -.094l6 -6l.094 -.083l.077 -.054l.096 -.054l.036 -.017l.067 -.027l.108 -.032l.053 -.01l.06 -.01z" />
                                                            </svg>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>

                                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                                    <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                                                        <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
                                                    </li>
                                                <?php endfor; ?>

                                                <?php if ($page < $totalPages): ?>
                                                    <li class="page-item">
                                                        <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-caret-right">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path d="M9 6c0 -.852 .986 -1.297 1.623 -.783l.084 .076l6 6a1 1 0 0 1 .083 1.32l-.083 .094l-6 6l-.094 .083l-.077 .054l-.096 .054l-.036 .017l-.067 .027l-.108 .032l-.053 .01l-.06 .01l-.057 .004l-.059 .002l-.059 -.002l-.058 -.005l-.06 -.009l-.052 -.01l-.108 -.032l-.067 -.027l-.132 -.07l-.09 -.065l-.081 -.073l-.083 -.094l-.054 -.077l-.054 -.096l-.017 -.036l-.027 -.067l-.032 -.108l-.01 -.053l-.01 -.06l-.004 -.057l-.002 -12.059z" />
                                                            </svg>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                        <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- END PAGE BODY -->

            <!--  BEGIN FOOTER  -->
            <footer class="footer footer-transparent d-print-none">
                <div class="container-xl">
                    <div class="row text-center align-items-center flex-row-reverse">
                        <div class="col-lg-auto ms-lg-auto">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item"><a href="https://docs.tabler.io" target="_blank" class="link-secondary" rel="noopener">Documentation</a></li>
                                <li class="list-inline-item"><a href="./license.html" class="link-secondary">License</a></li>
                                <li class="list-inline-item"><a href="https://github.com/tabler/tabler" target="_blank" class="link-secondary" rel="noopener">Source code</a></li>
                                <li class="list-inline-item">
                                    <a href="https://github.com/sponsors/codecalm" target="_blank" class="link-secondary" rel="noopener">
                                        <!-- Download SVG icon from http://tabler.io/icons/icon/heart -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-pink icon-inline icon-4">
                                            <path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" />
                                        </svg>
                                        Sponsor
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item">
                                    Copyright &copy; 2025
                                    <a href="." class="link-secondary">Tabler</a>.
                                    All rights reserved.
                                </li>
                                <li class="list-inline-item">
                                    Generated 2025-06-09 06:51 +0000
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
            <!--  END FOOTER  -->
        </div>
    </div>

    <!-- MODALS -->
    <!-- MODAL ADD INVOICE -->
    <form action="store_invoice.php" method="POST">
        <div class="modal modal-blur fade" id="modal-report" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">New Invoice</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Invoice Id</label>
                            <input type="text" class="form-control" name="invoice_id" value="<?= htmlspecialchars($new_invoice_id) ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Invoice Date</label>
                            <input type="date" class="form-control" name="invoice_date">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <div class="form-selectgroup-boxes row mb-3">
                                <div class="col-lg-6">
                                    <label class="form-selectgroup-item">
                                        <input type="radio" name="report-type" value="Kasbon" class="form-selectgroup-input" checked>
                                        <span class="form-selectgroup-label d-flex align-items-center p-3">
                                            <span class="me-3">
                                                <span class="form-selectgroup-check"></span>
                                            </span>
                                            <span class="form-selectgroup-label-content">
                                                <span class="form-selectgroup-title strong mb-1">Kasbon</span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-selectgroup-item">
                                        <input type="radio" name="report-type" value="Transfer" class="form-selectgroup-input">
                                        <span class="form-selectgroup-label d-flex align-items-center p-3">
                                            <span class="me-3">
                                                <span class="form-selectgroup-check"></span>
                                            </span>
                                            <span class="form-selectgroup-label-content">
                                                <span class="form-selectgroup-title strong mb-1">Transfer</span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Customer</label>
                            <select name="customer_id" class="form-select" required>
                                <option value="" disabled selected>-- Select Customer --</option>
                                <?php foreach ($customers as $customer): ?>
                                    <option value="<?= htmlspecialchars($customer['id']) ?>">
                                        <?= htmlspecialchars($customer['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Products</label>
                            or <a href="#" class="small text-primary" id="select-product-link">Select a Product</a>
                            <div id="product-container-add">
                                <div class="row g-2 product-row mb-2">
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="product_name[]" placeholder="Product Name" required>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" class="form-control" name="product_qty[]" placeholder="Qty" min="1" required>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" class="form-control" name="product_price[]" placeholder="Price" min="0" step="0.01" required>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" class="form-control" name="product_subtotal[]" placeholder="Subtotal" readonly>
                                    </div>
                                    <div class="col-md-1 d-flex align-items-start">
                                        <button type="button" class="btn btn-sm btn-danger btn-discard-product" title="Remove product">
                                            &times;
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="add-product" class="btn btn-sm btn-outline-primary mt-2">Add Product</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary ms-auto">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- END MODAL ADD INVOICE -->

    <!-- MODAL PRODUCT LIST -->
    <div class="modal fade" id="product-modal" tabindex="1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Stock</th>
                                <th>Price</th>
                                <th>Select</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><?= htmlspecialchars($product['product_name']) ?></td>
                                    <td><?= $product['qty'] ?></td>
                                    <td><?= $product['product_price'] ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary btn-select-product"
                                            data-name="<?= htmlspecialchars($product['product_name']) ?>"
                                            data-price="<?= $product['product_price'] ?>">
                                            Select
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT INVOICE  -->
    <div class="modal modal-blur fade" id="modal-edit-invoice" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="update_invoice.php" method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="mb-3">
                        <label class="form-label">Invoice Id</label>
                        <input type="text" class="form-control" name="invoice_id" id="edit-invoice-id" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Invoice Date</label>
                        <input type="date" class="form-control" name="invoice_date" id="edit-date" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <div class="form-selectgroup-boxes row mb-3">
                            <div class="col-lg-6">
                                <label class="form-selectgroup-item">
                                    <input type="radio" name="status" value="Kasbon" class="form-selectgroup-input" id="status-kasbon">
                                    <span class="form-selectgroup-label d-flex align-items-center p-3">
                                        <span class="me-3"><span class="form-selectgroup-check"></span></span>
                                        <span class="form-selectgroup-label-content">
                                            <span class="form-selectgroup-title strong mb-1">Kasbon</span>
                                        </span>
                                    </span>
                                </label>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-selectgroup-item">
                                    <input type="radio" name="status" value="Transfer" class="form-selectgroup-input" id="status-transfer">
                                    <span class="form-selectgroup-label d-flex align-items-center p-3">
                                        <span class="me-3"><span class="form-selectgroup-check"></span></span>
                                        <span class="form-selectgroup-label-content">
                                            <span class="form-selectgroup-title strong mb-1">Transfer</span>
                                        </span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Customer</label>
                        <select name="customer_id" class="form-select" id="edit-customer" required>
                            <option value="" disabled>-- Select Customer --</option>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?= htmlspecialchars($customer['id']) ?>">
                                    <?= htmlspecialchars($customer['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Products</label>
                        or <a href="#" class="small text-primary" id="select-product-link-edit">Select a Product</a>
                        <div id="product-container-edit">
                            <div class="row g-2 product-row mb-2">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="product_name[]" placeholder="Product Name" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" class="form-control" name="product_qty[]" placeholder="Qty" min="1" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" class="form-control" name="product_price[]" placeholder="Price" min="0" step="0.01" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" class="form-control" name="product_subtotal[]" placeholder="Subtotal" readonly>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="add-product-edit" class="btn btn-sm btn-outline-primary mt-2">Add Product</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</a>
                    <button type="submit" class="btn btn-primary ms-auto">Update</button>
                </div>
            </form>
        </div>
    </div>
    <!-- END MODAL EDIT INVOICE  -->
    <!-- END PAGE MODALS -->

    <div class="settings">
        <a href="#" class="btn btn-floating btn-icon btn-primary" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSettings" aria-controls="offcanvasSettings" aria-label="Theme Builder">
            <!-- Download SVG icon from http://tabler.io/icons/icon/brush -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                <path d="M3 21v-4a4 4 0 1 1 4 4h-4" />
                <path d="M21 3a16 16 0 0 0 -12.8 10.2" />
                <path d="M21 3a16 16 0 0 1 -10.2 12.8" />
                <path d="M10.6 9a9 9 0 0 1 4.4 4.4" />
            </svg>
        </a>

        <form class="offcanvas offcanvas-start offcanvas-narrow" tabindex="-1" id="offcanvasSettings">
            <div class="offcanvas-header">
                <h2 class="offcanvas-title">Theme Builder</h2>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body d-flex flex-column">
                <div>
                    <div class="mb-4">
                        <label class="form-label">Color mode</label>
                        <p class="form-hint">Choose the color mode for your app.</p>

                        <label class="form-check">
                            <div class="form-selectgroup-item">
                                <input type="radio" name="theme" value="light" class="form-check-input" checked />
                                <div class="form-check-label">Light</div>
                            </div>
                        </label>

                        <label class="form-check">
                            <div class="form-selectgroup-item">
                                <input type="radio" name="theme" value="dark" class="form-check-input" />
                                <div class="form-check-label">Dark</div>
                            </div>
                        </label>

                    </div>

                    <div class="mb-4">
                        <label class="form-label">Color scheme</label>
                        <p class="form-hint">The perfect color mode for your app.</p>
                        <div class="row g-2">
                            <div class="col-auto">
                                <label class="form-colorinput">
                                    <input name="theme-primary" type="radio" value="blue" class="form-colorinput-input" />
                                    <span class="form-colorinput-color bg-blue"></span>
                                </label>
                            </div>

                            <div class="col-auto">
                                <label class="form-colorinput">
                                    <input name="theme-primary" type="radio" value="azure" class="form-colorinput-input" />
                                    <span class="form-colorinput-color bg-azure"></span>
                                </label>
                            </div>

                            <div class="col-auto">
                                <label class="form-colorinput">
                                    <input name="theme-primary" type="radio" value="indigo" class="form-colorinput-input" />
                                    <span class="form-colorinput-color bg-indigo"></span>
                                </label>
                            </div>

                            <div class="col-auto">
                                <label class="form-colorinput">
                                    <input name="theme-primary" type="radio" value="purple" class="form-colorinput-input" />
                                    <span class="form-colorinput-color bg-purple"></span>
                                </label>
                            </div>

                            <div class="col-auto">
                                <label class="form-colorinput">
                                    <input name="theme-primary" type="radio" value="pink" class="form-colorinput-input" />
                                    <span class="form-colorinput-color bg-pink"></span>
                                </label>
                            </div>

                            <div class="col-auto">
                                <label class="form-colorinput">
                                    <input name="theme-primary" type="radio" value="red" class="form-colorinput-input" />
                                    <span class="form-colorinput-color bg-red"></span>
                                </label>
                            </div>

                            <div class="col-auto">
                                <label class="form-colorinput">
                                    <input name="theme-primary" type="radio" value="orange" class="form-colorinput-input" />
                                    <span class="form-colorinput-color bg-orange"></span>
                                </label>
                            </div>

                            <div class="col-auto">
                                <label class="form-colorinput">
                                    <input name="theme-primary" type="radio" value="yellow" class="form-colorinput-input" />
                                    <span class="form-colorinput-color bg-yellow"></span>
                                </label>
                            </div>

                            <div class="col-auto">
                                <label class="form-colorinput">
                                    <input name="theme-primary" type="radio" value="lime" class="form-colorinput-input" />
                                    <span class="form-colorinput-color bg-lime"></span>
                                </label>
                            </div>

                            <div class="col-auto">
                                <label class="form-colorinput">
                                    <input name="theme-primary" type="radio" value="green" class="form-colorinput-input" />
                                    <span class="form-colorinput-color bg-green"></span>
                                </label>
                            </div>

                            <div class="col-auto">
                                <label class="form-colorinput">
                                    <input name="theme-primary" type="radio" value="teal" class="form-colorinput-input" />
                                    <span class="form-colorinput-color bg-teal"></span>
                                </label>
                            </div>

                            <div class="col-auto">
                                <label class="form-colorinput">
                                    <input name="theme-primary" type="radio" value="cyan" class="form-colorinput-input" />
                                    <span class="form-colorinput-color bg-cyan"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Font family</label>
                        <p class="form-hint">Choose the font family that fits your app.</p>
                        <div>
                            <label class="form-check">
                                <div class="form-selectgroup-item">
                                    <input type="radio" name="theme-font" value="sans-serif" class="form-check-input" checked />
                                    <div class="form-check-label">Sans-serif</div>
                                </div>
                            </label>

                            <label class="form-check">
                                <div class="form-selectgroup-item">
                                    <input type="radio" name="theme-font" value="serif" class="form-check-input" />
                                    <div class="form-check-label">Serif</div>
                                </div>
                            </label>

                            <label class="form-check">
                                <div class="form-selectgroup-item">
                                    <input type="radio" name="theme-font" value="monospace" class="form-check-input" />
                                    <div class="form-check-label">Monospace</div>
                                </div>
                            </label>

                            <label class="form-check">
                                <div class="form-selectgroup-item">
                                    <input type="radio" name="theme-font" value="comic" class="form-check-input" />
                                    <div class="form-check-label">Comic</div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>



    <!-- BEGIN PAGE LIBRARIES -->
    <script src="../../assets/libs/apexcharts/dist/apexcharts.min.js" defer></script>
    <script src="../../assets/libs/jsvectormap/dist/jsvectormap.min.js" defer></script>
    <script src="../../assets/libs/jsvectormap/dist/maps/world.js" defer></script>
    <script src="../../assets/libs/jsvectormap/dist/maps/world-merc.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->

    <!-- END PAGE LIBRARIES -->


    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="../../assets/js/tabler.js" defer></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <!-- BEGIN DEMO SCRIPTS -->
    <script src="./preview/js/demo.js" defer></script>
    <!-- END DEMO SCRIPTS -->

    <!-- BEGIN PAGE SCRIPTS -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var themeConfig = {
                theme: "light",
                "theme-base": "gray",
                "theme-font": "sans-serif",
                "theme-primary": "blue",
                "theme-radius": "1",
            }
            var url = new URL(window.location)
            var form = document.getElementById("offcanvasSettings")
            var resetButton = document.getElementById("reset-changes")

            var checkItems = function() {
                for (var key in themeConfig) {
                    var value = window.localStorage["tabler-" + key] || themeConfig[key]

                    if (!!value) {
                        var radios = form.querySelectorAll(`[name="${key}"]`)

                        if (!!radios) {
                            radios.forEach((radio) => {
                                radio.checked = radio.value === value
                            })
                        }
                    }
                }
            }

            form.addEventListener("change", function(event) {
                var target = event.target,
                    name = target.name,
                    value = target.value

                for (var key in themeConfig) {
                    if (name === key) {
                        document.documentElement.setAttribute("data-bs-" + key, value)
                        window.localStorage.setItem("tabler-" + key, value)
                        url.searchParams.set(key, value)
                    }
                }

                window.history.pushState({}, "", url)
            })

            resetButton.addEventListener("click", function() {
                for (var key in themeConfig) {
                    var value = themeConfig[key]
                    document.documentElement.removeAttribute("data-bs-" + key)
                    window.localStorage.removeItem("tabler-" + key)
                    url.searchParams.delete(key)
                }

                checkItems()

                window.history.pushState({}, "", url)
            })

            checkItems()
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Simpan referensi modal aktif sebelum buka product-modal
            let activeModal = null;

            function openProductModal(fromModalId, targetRow) {
                // Simpan modal asal
                activeModal = new bootstrap.Modal(document.getElementById(fromModalId));
                // Sembunyikan modal asal
                document.getElementById(fromModalId).classList.remove('show');
                document.getElementById(fromModalId).style.display = 'none';
                document.body.classList.remove('modal-open');

                // Simpan row target secara global
                window.targetRow = targetRow;

                // Tampilkan product modal
                const productModal = new bootstrap.Modal(document.getElementById('product-modal'), {
                    backdrop: 'static' // agar backdrop tetap stabil
                });
                productModal.show();
            }

            // Saat product-modal ditutup (tanpa pilih produk pun), kembalikan modal asal
            document.getElementById('product-modal').addEventListener('hidden.bs.modal', function() {
                if (activeModal) {
                    // Tampilkan kembali modal asal
                    const modalElement = activeModal._element;
                    modalElement.style.display = 'block';
                    modalElement.classList.add('show');
                    document.body.classList.add('modal-open');

                    // Reset activeModal jika perlu
                    activeModal = null;
                }
            });

            const deleteButtons = document.querySelectorAll('.btn-delete-invoice');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const confirmed = confirm("Apakah Anda yakin ingin menghapus invoice ini?");
                    if (!confirmed) {
                        e.preventDefault(); // batalkan jika tidak yakin
                    }
                });
            });

            // Fungsi hitung subtotal per baris produk
            function calculateSubtotals() {
                const rows = document.querySelectorAll('.product-row');
                rows.forEach(row => {
                    const qtyInput = row.querySelector('input[name="product_qty[]"]');
                    const priceInput = row.querySelector('input[name="product_price[]"]');
                    const subtotalInput = row.querySelector('input[name="product_subtotal[]"]');

                    const qty = parseFloat(qtyInput.value) || 0;
                    const price = parseFloat(priceInput.value) || 0;
                    subtotalInput.value = (qty * price).toFixed(2);
                });
            }

            // Event delegation untuk input qty dan price agar hitung subtotal saat diubah
            document.getElementById('product-container-add').addEventListener('input', e => {
                if (
                    e.target.name === 'product_qty[]' ||
                    e.target.name === 'product_price[]'
                ) {
                    calculateSubtotals();
                }
            });

            // Tambah row produk baru
            document.getElementById('add-product').addEventListener('click', () => {
                const container = document.getElementById('product-container-add');
                const newRow = document.querySelector('.product-row').cloneNode(true);

                // Clear input values
                newRow.querySelectorAll('input').forEach(input => input.value = '');
                // Append new row
                container.appendChild(newRow);
            });

            document.addEventListener("input", function() {
                const qtyInputs = document.querySelectorAll('input[name="product_qty[]"]');
                const priceInputs = document.querySelectorAll('input[name="product_price[]"]');
                const subtotalInputs = document.querySelectorAll('input[name="product_subtotal[]"]');

                qtyInputs.forEach((qty, index) => {
                    const price = parseFloat(priceInputs[index].value) || 0;
                    const quantity = parseInt(qty.value) || 0;
                    subtotalInputs[index].value = price * quantity;
                });
            });

            // Saat klik "Select a Product"
            document.getElementById('select-product-link').addEventListener('click', function(e) {
                e.preventDefault();
                const lastRow = document.querySelector('#product-container-add .product-row:last-child');
                openProductModal('modal-report', lastRow);
            });


            // Saat pilih produk dari modal
            document.querySelectorAll('.btn-select-product').forEach(button => {
                button.addEventListener('click', function() {
                    const productName = this.dataset.name;
                    const productPrice = this.dataset.price;

                    if (window.targetRow) {
                        window.targetRow.querySelector('input[name="product_name[]"]').value = productName;
                        window.targetRow.querySelector('input[name="product_price[]"]').value = productPrice;
                        window.targetRow.querySelector('input[name="product_qty[]"]').value = 1;
                        window.targetRow.querySelector('input[name="product_subtotal[]"]').value = productPrice;
                    }

                    // Tutup product-modal
                    bootstrap.Modal.getInstance(document.getElementById('product-modal')).hide();

                    // Tampilkan kembali modal asal
                    setTimeout(() => {
                        if (activeModal) {
                            document.getElementById(activeModal._element.id).style.display = 'block';
                            document.body.classList.add('modal-open');
                            document.getElementById(activeModal._element.id).classList.add('show');
                        }
                    }, 300);
                });
            });

            // Event delegation: hapus product row saat klik discard
            document.getElementById('product-container-add').addEventListener('click', function(e) {
                if (e.target.classList.contains('btn-discard-product')) {
                    const allRows = document.querySelectorAll('.product-row');
                    if (allRows.length > 1) {
                        e.target.closest('.product-row').remove();
                    } else {
                        alert("Minimal satu produk harus ada di invoice.");
                    }
                }
            });

            document.querySelectorAll('.btn-edit-invoice').forEach(button => {
                button.addEventListener('click', async function() {
                    const id = this.dataset.id;

                    // Tunda sedikit supaya modal muncul dulu
                    setTimeout(async () => {
                        try {
                            const response = await fetch(`get_invoice_data.php?id=${id}`);
                            const data = await response.json();

                            document.getElementById('edit-id').value = id;
                            document.getElementById('edit-invoice-id').value = data.invoice_id;
                            document.getElementById('edit-date').value = data.invoice_date;
                            document.getElementById('edit-customer').value = data.customer_id;
                            document.getElementById('status-kasbon').checked = data.status === 'Kasbon';
                            document.getElementById('status-transfer').checked = data.status === 'Transfer';

                            const productContainer = document.querySelector('#product-container-edit');
                            console.log('Container ditemukan?', productContainer);
                            productContainer.innerHTML = '';

                            data.items.forEach(item => {
                                const row = document.createElement('div');
                                row.className = 'row g-2 product-row mb-2';
                                row.innerHTML = `
                                            <div class="col-md-4">
                                            <input type="text" class="form-control" name="product_name[]" value="${item.product_name}" required>
                                            </div>
                                            <div class="col-md-2">
                                            <input type="number" class="form-control" name="product_qty[]" value="${item.qty}" required>
                                            </div>
                                            <div class="col-md-3">
                                            <input type="number" class="form-control" name="product_price[]" value="${item.price}" step="0.01" required>
                                            </div>
                                            <div class="col-md-2">
                                            <input type="number" class="form-control" name="product_subtotal[]" value="${item.subtotal}" readonly>
                                            </div>
                                            <div class="col-md-1 d-flex align-items-start">
                                            <button type="button" class="btn btn-sm btn-danger btn-discard-product" title="Remove product">&times;</button>
                                            </div>`;
                                productContainer.appendChild(row);
                                console.log('Product container setelah append:', productContainer.innerHTML);
                            });

                        } catch (err) {
                            console.error('Gagal memuat data invoice:', err);
                        }
                    }, 200); // tunda 200ms agar modal muncul dulu
                });

            });

            // Event delegation: hapus baris produk di modal edit
            document.getElementById('product-container-edit').addEventListener('click', function(e) {
                if (e.target.classList.contains('btn-discard-product')) {
                    const allRows = this.querySelectorAll('.product-row');
                    console.log("Total baris:", allRows.length);
                    if (allRows.length > 1) {
                        e.target.closest('.product-row').remove();
                    } else {
                        alert("Minimal satu produk harus ada di invoice.");
                    }
                }
            });

            // Perhitungan subtotal otomatis
            document.getElementById('product-container-edit').addEventListener('input', function(e) {
                if (e.target.name === 'product_qty[]' || e.target.name === 'product_price[]') {
                    const row = e.target.closest('.product-row');
                    const qty = parseFloat(row.querySelector('input[name="product_qty[]"]').value) || 0;
                    const price = parseFloat(row.querySelector('input[name="product_price[]"]').value) || 0;
                    const subtotalInput = row.querySelector('input[name="product_subtotal[]"]');
                    subtotalInput.value = (qty * price).toFixed(2);
                }
            });

            document.getElementById('add-product-edit').addEventListener('click', () => {
                const container = document.getElementById('product-container-edit');
                const newRow = container.querySelector('.product-row').cloneNode(true);

                newRow.querySelectorAll('input').forEach(input => {
                    if (input.name === 'product_subtotal[]') {
                        input.value = '0.00';
                    } else {
                        input.value = '';
                    }
                });

                container.appendChild(newRow);
            });

            document.getElementById('select-product-link-edit').addEventListener('click', function(e) {
                e.preventDefault();
                const lastRow = document.querySelector('#product-container-edit .product-row:last-child');
                openProductModal('modal-edit-invoice', lastRow);
            });


            document.querySelector('#product-container-edit').addEventListener('click', function(e) {
                if (e.target.classList.contains('btn-select-row-product')) {
                    window.targetRow = e.target.closest('.product-row');
                    const modal = new bootstrap.Modal(document.getElementById('product-modal'));
                    modal.show();
                }
            });

        });
    </script>
    <!-- END PAGE SCRIPTS -->
</body>

</html>