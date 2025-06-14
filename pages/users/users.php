<?php
require __DIR__ . '/../../auth.php';
// Aktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

include(__DIR__ . "/../../config/config.php");

// Ambil invoice_id terakhir dari DB
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

// Ambil data customer dari tabel customers
$customers = [];
$result_customers = $mysqli->query("SELECT id, name FROM customers ORDER BY name ASC");
if ($result_customers) {
    while ($row = $result_customers->fetch_assoc()) {
        $customers[] = $row;
    }
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
                        <li class="nav-item">
                            <a class="nav-link" href="./../invoices/invoices.php">
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
                                <span class="nav-link-title">
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
                        <li class="nav-item active">
                            <a class="nav-link" href="users.php">
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
                            <div class="page-pretitle">
                                Overview
                            </div>
                            <h2 class="page-title">
                                System User
                            </h2>
                        </div>
                        <!-- Page title actions -->
                        <div class="col-auto ms-auto d-print-none">
                            <div class="btn-list">
                                <a href="#" class="btn btn-primary btn-5 d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-report">
                                    <!-- Download SVG icon from http://tabler.io/icons/icon/plus -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-plus">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                        <path d="M16 19h6" />
                                        <path d="M19 16v6" />
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                                    </svg>
                                    Add New User
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

            <div class="page-wrapper">
                <div class="container-xl">
                    <!-- Page title -->
                    <div class="page-header d-print-none">
                        <div class="row align-items-center">
                            <div class="col">
                                <h2 class="page-title"> System Users</h2>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">List of system users</h3>
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
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $perPage = isset($_GET['per_page']) ? (int) $_GET['per_page'] : 10;
                                    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                                    $offset = ($page - 1) * $perPage;
                                    $totalQuery = "SELECT COUNT(DISTINCT c.id) AS total FROM customers c";
                                    $totalResult = mysqli_query($mysqli, $totalQuery);
                                    $totalData = mysqli_fetch_assoc($totalResult)['total'];
                                    $totalPages = ceil($totalData / $perPage);

                                    $no = 1;
                                    $sql = "SELECT *
                                            FROM users
                                            ORDER BY users.name ASC
                                            LIMIT $perPage OFFSET $offset";
                                    $result = mysqli_query($mysqli, $sql);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $row['name']; ?></td>
                                            <td><?= $row['username']; ?></td>
                                            <td><?= $row['email']; ?></td>
                                            <td><?= $row['phone']; ?></td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-purple btn-edit-invoice"
                                                    data-id="<?= $row['id']; ?>"
                                                    data-name="<?= htmlspecialchars($row['name']); ?>"
                                                    data-username="<?= htmlspecialchars($row['username']); ?>"
                                                    data-email="<?= htmlspecialchars($row['email']); ?>"
                                                    data-phone="<?= $row['phone']; ?>"
                                                    data-password="<?= htmlspecialchars($row['password']); ?>"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modal-edit-invoice">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                        <path d="M16 5l3 3" />
                                                    </svg>
                                                </a>
                                                <a href="user_delete.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-danger btn-delete-invoice" title="Edit Invoice">
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

    <!-- BEGIN PAGE MODALS -->
    <!-- MODAL ADD USER -->
    <form action="store_user.php" method="POST">
        <div class="modal modal-blur fade" id="modal-report" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone" pattern="[0-9]+" title="Hanya boleh angka" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" required>
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

    <!-- MODAL EDIT USER -->
    <div class="modal modal-blur fade" id="modal-edit-invoice" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="update_user.php" method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" id="username" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" class="form-control" name="phone" id="phone" pattern="[0-9]+" title="Hanya boleh angka" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</a>
                    <button type="submit" class="btn btn-primary ms-auto">Update</button>
                </div>
            </form>
        </div>
    </div>
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
            window.tabler_chart = window.tabler_chart || {};
            window.ApexCharts && (window.tabler_chart["chart-visitors"] = new ApexCharts(document.getElementById('chart-visitors'), {
                chart: {
                    type: "line",
                    fontFamily: 'inherit',
                    height: 96,
                    sparkline: {
                        enabled: true
                    },
                    animations: {
                        enabled: false
                    },
                },
                stroke: {
                    width: [2, 1],
                    dashArray: [0, 3],
                    lineCap: "round",
                    curve: "smooth",
                },
                series: [{
                    name: "Visitors",
                    data: [7687, 7543, 7545, 7543, 7635, 8140, 7810, 8315, 8379, 8441, 8485, 8227, 8906, 8561, 8333, 8551, 9305, 9647, 9359, 9840, 9805, 8612, 8970, 8097, 8070, 9829, 10545, 10754, 10270, 9282]
                }, {
                    name: "Visitors last month",
                    data: [8630, 9389, 8427, 9669, 8736, 8261, 8037, 8922, 9758, 8592, 8976, 9459, 8125, 8528, 8027, 8256, 8670, 9384, 9813, 8425, 8162, 8024, 8897, 9284, 8972, 8776, 8121, 9476, 8281, 9065]
                }],
                tooltip: {
                    theme: 'dark'
                },
                grid: {
                    strokeDashArray: 4,
                },
                xaxis: {
                    labels: {
                        padding: 0,
                    },
                    tooltip: {
                        enabled: false
                    },
                    type: 'datetime',
                },
                yaxis: {
                    labels: {
                        padding: 4
                    },
                },
                labels: [
                    '2020-06-20', '2020-06-21', '2020-06-22', '2020-06-23', '2020-06-24', '2020-06-25', '2020-06-26', '2020-06-27', '2020-06-28', '2020-06-29', '2020-06-30', '2020-07-01', '2020-07-02', '2020-07-03', '2020-07-04', '2020-07-05', '2020-07-06', '2020-07-07', '2020-07-08', '2020-07-09', '2020-07-10', '2020-07-11', '2020-07-12', '2020-07-13', '2020-07-14', '2020-07-15', '2020-07-16', '2020-07-17', '2020-07-18', '2020-07-19'
                ],
                colors: [
                    'color-mix(in srgb, transparent, var(--tblr-primary) 100%)',
                    'color-mix(in srgb, transparent, var(--tblr-gray-400) 100%)'
                ],
                legend: {
                    show: false,
                },
            })).render();
        });
    </script>



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            window.tabler_chart = window.tabler_chart || {};
            window.ApexCharts && (window.tabler_chart["chart-active-users-3"] = new ApexCharts(document.getElementById('chart-active-users-3'), {
                chart: {
                    type: "radialBar",
                    fontFamily: 'inherit',
                    height: 192,
                    sparkline: {
                        enabled: true
                    },
                    animations: {
                        enabled: false
                    },
                },
                plotOptions: {
                    radialBar: {
                        startAngle: -120,
                        endAngle: 120,
                        hollow: {
                            margin: 16,
                            size: "50%"
                        },
                        dataLabels: {
                            show: true,
                            value: {
                                offsetY: -8,
                                fontSize: '24px',
                            }
                        },
                    },
                },
                series: [78],
                labels: [""],
                tooltip: {
                    theme: 'dark'
                },
                grid: {
                    strokeDashArray: 4,
                },
                colors: [
                    'color-mix(in srgb, transparent, var(--tblr-primary) 100%)'
                ],
                legend: {
                    show: false,
                },
            })).render();
        });
    </script>



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            window.tabler_chart = window.tabler_chart || {};
            window.ApexCharts && (window.tabler_chart["chart-revenue-bg"] = new ApexCharts(document.getElementById('chart-revenue-bg'), {
                chart: {
                    type: "area",
                    fontFamily: 'inherit',
                    height: 40,
                    sparkline: {
                        enabled: true
                    },
                    animations: {
                        enabled: false
                    },
                },
                dataLabels: {
                    enabled: false,
                },
                fill: {
                    colors: [
                        'color-mix(in srgb, transparent, var(--tblr-primary) 16%)',
                        'color-mix(in srgb, transparent, var(--tblr-primary) 16%)',
                    ],
                    type: 'solid'
                },
                stroke: {
                    width: 2,
                    lineCap: "round",
                    curve: "smooth",
                },
                series: [{
                    name: "Profits",
                    data: [37, 35, 44, 28, 36, 24, 65, 31, 37, 39, 62, 51, 35, 41, 35, 27, 93, 53, 61, 27, 54, 43, 19, 46, 39, 62, 51, 35, 41, 67]
                }],
                tooltip: {
                    theme: 'dark'
                },
                grid: {
                    strokeDashArray: 4,
                },
                xaxis: {
                    labels: {
                        padding: 0,
                    },
                    tooltip: {
                        enabled: false
                    },
                    axisBorder: {
                        show: false,
                    },
                    type: 'datetime',
                },
                yaxis: {
                    labels: {
                        padding: 4
                    },
                },
                labels: [
                    '2020-06-20', '2020-06-21', '2020-06-22', '2020-06-23', '2020-06-24', '2020-06-25', '2020-06-26', '2020-06-27', '2020-06-28', '2020-06-29', '2020-06-30', '2020-07-01', '2020-07-02', '2020-07-03', '2020-07-04', '2020-07-05', '2020-07-06', '2020-07-07', '2020-07-08', '2020-07-09', '2020-07-10', '2020-07-11', '2020-07-12', '2020-07-13', '2020-07-14', '2020-07-15', '2020-07-16', '2020-07-17', '2020-07-18', '2020-07-19'
                ],
                colors: [
                    'color-mix(in srgb, transparent, var(--tblr-primary) 100%)'
                ],
                legend: {
                    show: false,
                },
            })).render();
        });
    </script>



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            window.tabler_chart = window.tabler_chart || {};
            window.ApexCharts && (window.tabler_chart["chart-new-clients"] = new ApexCharts(document.getElementById('chart-new-clients'), {
                chart: {
                    type: "line",
                    fontFamily: 'inherit',
                    height: 40,
                    sparkline: {
                        enabled: true
                    },
                    animations: {
                        enabled: false
                    },
                },
                stroke: {
                    width: [2, 1],
                    dashArray: [0, 3],
                    lineCap: "round",
                    curve: "smooth",
                },
                series: [{
                    name: "May",
                    data: [37, 35, 44, 28, 36, 24, 65, 31, 37, 39, 62, 51, 35, 41, 35, 27, 93, 53, 61, 27, 54, 43, 4, 46, 39, 62, 51, 35, 41, 67]
                }, {
                    name: "April",
                    data: [93, 54, 51, 24, 35, 35, 31, 67, 19, 43, 28, 36, 62, 61, 27, 39, 35, 41, 27, 35, 51, 46, 62, 37, 44, 53, 41, 65, 39, 37]
                }],
                tooltip: {
                    theme: 'dark'
                },
                grid: {
                    strokeDashArray: 4,
                },
                xaxis: {
                    labels: {
                        padding: 0,
                    },
                    tooltip: {
                        enabled: false
                    },
                    type: 'datetime',
                },
                yaxis: {
                    labels: {
                        padding: 4
                    },
                },
                labels: [
                    '2020-06-20', '2020-06-21', '2020-06-22', '2020-06-23', '2020-06-24', '2020-06-25', '2020-06-26', '2020-06-27', '2020-06-28', '2020-06-29', '2020-06-30', '2020-07-01', '2020-07-02', '2020-07-03', '2020-07-04', '2020-07-05', '2020-07-06', '2020-07-07', '2020-07-08', '2020-07-09', '2020-07-10', '2020-07-11', '2020-07-12', '2020-07-13', '2020-07-14', '2020-07-15', '2020-07-16', '2020-07-17', '2020-07-18', '2020-07-19'
                ],
                colors: [
                    'color-mix(in srgb, transparent, var(--tblr-primary) 100%)',
                    'color-mix(in srgb, transparent, var(--tblr-gray-600) 100%)'
                ],
                legend: {
                    show: false,
                },
            })).render();
        });
    </script>



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            window.tabler_chart = window.tabler_chart || {};
            window.ApexCharts && (window.tabler_chart["chart-active-users"] = new ApexCharts(document.getElementById('chart-active-users'), {
                chart: {
                    type: "bar",
                    fontFamily: 'inherit',
                    height: 40,
                    sparkline: {
                        enabled: true
                    },
                    animations: {
                        enabled: false
                    },
                },
                plotOptions: {
                    bar: {
                        columnWidth: '50%',
                    }
                },
                dataLabels: {
                    enabled: false,
                },
                series: [{
                    name: "Profits",
                    data: [37, 35, 44, 28, 36, 24, 65, 31, 37, 39, 62, 51, 35, 41, 35, 27, 93, 53, 61, 27, 54, 43, 19, 46, 39, 62, 51, 35, 41, 67]
                }],
                tooltip: {
                    theme: 'dark'
                },
                grid: {
                    strokeDashArray: 4,
                },
                xaxis: {
                    labels: {
                        padding: 0,
                    },
                    tooltip: {
                        enabled: false
                    },
                    axisBorder: {
                        show: false,
                    },
                    type: 'datetime',
                },
                yaxis: {
                    labels: {
                        padding: 4
                    },
                },
                labels: [
                    '2020-06-20', '2020-06-21', '2020-06-22', '2020-06-23', '2020-06-24', '2020-06-25', '2020-06-26', '2020-06-27', '2020-06-28', '2020-06-29', '2020-06-30', '2020-07-01', '2020-07-02', '2020-07-03', '2020-07-04', '2020-07-05', '2020-07-06', '2020-07-07', '2020-07-08', '2020-07-09', '2020-07-10', '2020-07-11', '2020-07-12', '2020-07-13', '2020-07-14', '2020-07-15', '2020-07-16', '2020-07-17', '2020-07-18', '2020-07-19'
                ],
                colors: [
                    'color-mix(in srgb, transparent, var(--tblr-primary) 100%)'
                ],
                legend: {
                    show: false,
                },
            })).render();
        });
    </script>



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            window.tabler_chart = window.tabler_chart || {};
            window.ApexCharts && (window.tabler_chart["chart-mentions"] = new ApexCharts(document.getElementById('chart-mentions'), {
                chart: {
                    type: "bar",
                    fontFamily: 'inherit',
                    height: 240,
                    parentHeightOffset: 0,
                    toolbar: {
                        show: false,
                    },
                    animations: {
                        enabled: false
                    },
                    stacked: true,
                },
                plotOptions: {
                    bar: {
                        columnWidth: '50%',
                    }
                },
                dataLabels: {
                    enabled: false,
                },
                series: [{
                    name: "Web",
                    data: [1, 0, 0, 0, 0, 1, 1, 0, 0, 0, 2, 12, 5, 8, 22, 6, 8, 6, 4, 1, 8, 24, 29, 51, 40, 47, 23, 26, 50, 26, 41, 22, 46, 47, 81, 46, 6]
                }, {
                    name: "Social",
                    data: [2, 5, 4, 3, 3, 1, 4, 7, 5, 1, 2, 5, 3, 2, 6, 7, 7, 1, 5, 5, 2, 12, 4, 6, 18, 3, 5, 2, 13, 15, 20, 47, 18, 15, 11, 10, 0]
                }, {
                    name: "Other",
                    data: [2, 9, 1, 7, 8, 3, 6, 5, 5, 4, 6, 4, 1, 9, 3, 6, 7, 5, 2, 8, 4, 9, 1, 2, 6, 7, 5, 1, 8, 3, 2, 3, 4, 9, 7, 1, 6]
                }],
                tooltip: {
                    theme: 'dark'
                },
                grid: {
                    padding: {
                        top: -20,
                        right: 0,
                        left: -4,
                        bottom: -4
                    },
                    strokeDashArray: 4,
                    xaxis: {
                        lines: {
                            show: true
                        }
                    },
                },
                xaxis: {
                    labels: {
                        padding: 0,
                    },
                    tooltip: {
                        enabled: false
                    },
                    axisBorder: {
                        show: false,
                    },
                    type: 'datetime',
                },
                yaxis: {
                    labels: {
                        padding: 4
                    },
                },
                labels: [
                    '2020-06-20', '2020-06-21', '2020-06-22', '2020-06-23', '2020-06-24', '2020-06-25', '2020-06-26', '2020-06-27', '2020-06-28', '2020-06-29', '2020-06-30', '2020-07-01', '2020-07-02', '2020-07-03', '2020-07-04', '2020-07-05', '2020-07-06', '2020-07-07', '2020-07-08', '2020-07-09', '2020-07-10', '2020-07-11', '2020-07-12', '2020-07-13', '2020-07-14', '2020-07-15', '2020-07-16', '2020-07-17', '2020-07-18', '2020-07-19', '2020-07-20', '2020-07-21', '2020-07-22', '2020-07-23', '2020-07-24', '2020-07-25', '2020-07-26'
                ],
                colors: [
                    'color-mix(in srgb, transparent, var(--tblr-primary) 100%)',
                    'color-mix(in srgb, transparent, var(--tblr-primary) 80%)',
                    'color-mix(in srgb, transparent, var(--tblr-green) 80%)'
                ],
                legend: {
                    show: false,
                },
            })).render();
        });
    </script>



    <script>
        window.tabler_map_vector = window.tabler_map_vector || {};
        document.addEventListener("DOMContentLoaded", function() {
            const map = window.tabler_map_vector["map-world"] = new jsVectorMap({
                selector: '#map-world',
                map: 'world',
                backgroundColor: 'transparent',
                regionStyle: {
                    initial: {
                        fill: 'var(--tblr-bg-surface-secondary)',
                        stroke: 'var(--tblr-border-color)',
                        strokeWidth: 2,
                    }
                },
                zoomOnScroll: false,
                zoomButtons: false,
                series: {
                    regions: [{
                        attribute: "fill",
                        scale: {
                            scale1: 'color-mix(in srgb, transparent, var(--tblr-primary) 10%)',
                            scale2: 'color-mix(in srgb, transparent, var(--tblr-primary) 20%)',
                            scale3: 'color-mix(in srgb, transparent, var(--tblr-primary) 30%)',
                            scale4: 'color-mix(in srgb, transparent, var(--tblr-primary) 40%)',
                            scale5: 'color-mix(in srgb, transparent, var(--tblr-primary) 50%)',
                            scale6: 'color-mix(in srgb, transparent, var(--tblr-primary) 60%)',
                            scale7: 'color-mix(in srgb, transparent, var(--tblr-primary) 70%)',
                            scale8: 'color-mix(in srgb, transparent, var(--tblr-primary) 80%)',
                            scale9: 'color-mix(in srgb, transparent, var(--tblr-primary) 90%)',
                            scale10: 'color-mix(in srgb, transparent, var(--tblr-primary) 100%)',
                        },
                        values: {
                            "AF": "scale2",
                            "AL": "scale2",
                            "DZ": "scale4",
                            "AO": "scale3",
                            "AG": "scale1",
                            "AR": "scale5",
                            "AM": "scale1",
                            "AU": "scale7",
                            "AT": "scale5",
                            "AZ": "scale3",
                            "BS": "scale1",
                            "BH": "scale2",
                            "BD": "scale4",
                            "BB": "scale1",
                            "BY": "scale3",
                            "BE": "scale5",
                            "BZ": "scale1",
                            "BJ": "scale1",
                            "BT": "scale1",
                            "BO": "scale2",
                            "BA": "scale2",
                            "BW": "scale2",
                            "BR": "scale8",
                            "BN": "scale2",
                            "BG": "scale2",
                            "BF": "scale1",
                            "BI": "scale1",
                            "KH": "scale2",
                            "CM": "scale2",
                            "CA": "scale7",
                            "CV": "scale1",
                            "CF": "scale1",
                            "TD": "scale1",
                            "CL": "scale4",
                            "CN": "scale9",
                            "CO": "scale5",
                            "KM": "scale1",
                            "CD": "scale2",
                            "CG": "scale2",
                            "CR": "scale2",
                            "CI": "scale2",
                            "HR": "scale3",
                            "CY": "scale2",
                            "CZ": "scale4",
                            "DK": "scale5",
                            "DJ": "scale1",
                            "DM": "scale1",
                            "DO": "scale3",
                            "EC": "scale3",
                            "EG": "scale5",
                            "SV": "scale2",
                            "GQ": "scale2",
                            "ER": "scale1",
                            "EE": "scale2",
                            "ET": "scale2",
                            "FJ": "scale1",
                            "FI": "scale5",
                            "FR": "scale8",
                            "GA": "scale2",
                            "GM": "scale1",
                            "GE": "scale2",
                            "DE": "scale8",
                            "GH": "scale2",
                            "GR": "scale5",
                            "GD": "scale1",
                            "GT": "scale2",
                            "GN": "scale1",
                            "GW": "scale1",
                            "GY": "scale1",
                            "HT": "scale1",
                            "HN": "scale2",
                            "HK": "scale5",
                            "HU": "scale4",
                            "IS": "scale2",
                            "IN": "scale7",
                            "ID": "scale6",
                            "IR": "scale5",
                            "IQ": "scale3",
                            "IE": "scale5",
                            "IL": "scale5",
                            "IT": "scale8",
                            "JM": "scale2",
                            "JP": "scale9",
                            "JO": "scale2",
                            "KZ": "scale4",
                            "KE": "scale2",
                            "KI": "scale1",
                            "KR": "scale6",
                            "KW": "scale4",
                            "KG": "scale1",
                            "LA": "scale1",
                            "LV": "scale2",
                            "LB": "scale2",
                            "LS": "scale1",
                            "LR": "scale1",
                            "LY": "scale3",
                            "LT": "scale2",
                            "LU": "scale3",
                            "MK": "scale1",
                            "MG": "scale1",
                            "MW": "scale1",
                            "MY": "scale5",
                            "MV": "scale1",
                            "ML": "scale1",
                            "MT": "scale1",
                            "MR": "scale1",
                            "MU": "scale1",
                            "MX": "scale7",
                            "MD": "scale1",
                            "MN": "scale1",
                            "ME": "scale1",
                            "MA": "scale3",
                            "MZ": "scale2",
                            "MM": "scale2",
                            "NA": "scale2",
                            "NP": "scale2",
                            "NL": "scale6",
                            "NZ": "scale4",
                            "NI": "scale1",
                            "NE": "scale1",
                            "NG": "scale5",
                            "NO": "scale5",
                            "OM": "scale3",
                            "PK": "scale4",
                            "PA": "scale2",
                            "PG": "scale1",
                            "PY": "scale2",
                            "PE": "scale4",
                            "PH": "scale4",
                            "PL": "scale10",
                            "PT": "scale5",
                            "QA": "scale4",
                            "RO": "scale4",
                            "RU": "scale7",
                            "RW": "scale1",
                            "WS": "scale1",
                            "ST": "scale1",
                            "SA": "scale5",
                            "SN": "scale2",
                            "RS": "scale2",
                            "SC": "scale1",
                            "SL": "scale1",
                            "SG": "scale5",
                            "SK": "scale3",
                            "SI": "scale2",
                            "SB": "scale1",
                            "ZA": "scale5",
                            "ES": "scale7",
                            "LK": "scale2",
                            "KN": "scale1",
                            "LC": "scale1",
                            "VC": "scale1",
                            "SD": "scale3",
                            "SR": "scale1",
                            "SZ": "scale1",
                            "SE": "scale5",
                            "CH": "scale6",
                            "SY": "scale3",
                            "TW": "scale5",
                            "TJ": "scale1",
                            "TZ": "scale2",
                            "TH": "scale5",
                            "TL": "scale1",
                            "TG": "scale1",
                            "TO": "scale1",
                            "TT": "scale2",
                            "TN": "scale2",
                            "TR": "scale6",
                            "TM": "scale1",
                            "UG": "scale2",
                            "UA": "scale4",
                            "AE": "scale5",
                            "GB": "scale8",
                            "US": "scale10",
                            "UY": "scale2",
                            "UZ": "scale2",
                            "VU": "scale1",
                            "VE": "scale5",
                            "VN": "scale4",
                            "YE": "scale2",
                            "ZM": "scale2",
                            "ZW": "scale1"
                        },
                    }]
                }
            });
            window.addEventListener("resize", () => {
                map.updateSize();
            });
        });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {

            window.tabler_chart = window.tabler_chart || {};


            window.ApexCharts && (window.tabler_chart["sparkline-activity"] = new ApexCharts(document.getElementById('sparkline-activity'), {
                chart: {
                    type: "radialBar",
                    fontFamily: 'inherit',
                    height: 40,

                    width: 40,

                    animations: {
                        enabled: false
                    },
                    sparkline: {
                        enabled: true
                    },
                },
                tooltip: {
                    enabled: false,
                },

                plotOptions: {
                    radialBar: {
                        hollow: {
                            margin: 0,
                            size: '75%'
                        },
                        track: {
                            margin: 0
                        },
                        dataLabels: {
                            show: false
                        }
                    }
                },





                colors: ['var(--tblr-primary)'],
                series: [35],

            })).render();
        });
    </script>



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            window.tabler_chart = window.tabler_chart || {};
            window.ApexCharts && (window.tabler_chart["chart-development-activity"] = new ApexCharts(document.getElementById('chart-development-activity'), {
                chart: {
                    type: "area",
                    fontFamily: 'inherit',
                    height: 192,
                    sparkline: {
                        enabled: true
                    },
                    animations: {
                        enabled: false
                    },
                },
                dataLabels: {
                    enabled: false,
                },
                fill: {
                    colors: [
                        'color-mix(in srgb, transparent, var(--tblr-primary) 16%)',
                        'color-mix(in srgb, transparent, var(--tblr-primary) 16%)',
                    ],
                    type: 'solid'
                },
                stroke: {
                    width: 2,
                    lineCap: "round",
                    curve: "smooth",
                },
                series: [{
                    name: "Purchases",
                    data: [3, 5, 4, 6, 7, 5, 6, 8, 24, 7, 12, 5, 6, 3, 8, 4, 14, 30, 17, 19, 15, 14, 25, 32, 40, 55, 60, 48, 52, 70]
                }],
                tooltip: {
                    theme: 'dark'
                },
                grid: {
                    strokeDashArray: 4,
                },
                xaxis: {
                    labels: {
                        padding: 0,
                    },
                    tooltip: {
                        enabled: false
                    },
                    axisBorder: {
                        show: false,
                    },
                    type: 'datetime',
                },
                yaxis: {
                    labels: {
                        padding: 4
                    },
                },
                labels: [
                    '2020-06-20', '2020-06-21', '2020-06-22', '2020-06-23', '2020-06-24', '2020-06-25', '2020-06-26', '2020-06-27', '2020-06-28', '2020-06-29', '2020-06-30', '2020-07-01', '2020-07-02', '2020-07-03', '2020-07-04', '2020-07-05', '2020-07-06', '2020-07-07', '2020-07-08', '2020-07-09', '2020-07-10', '2020-07-11', '2020-07-12', '2020-07-13', '2020-07-14', '2020-07-15', '2020-07-16', '2020-07-17', '2020-07-18', '2020-07-19'
                ],
                colors: [
                    'color-mix(in srgb, transparent, var(--tblr-primary) 100%)'
                ],
                legend: {
                    show: false,
                },
                point: {
                    show: false
                },
            })).render();
        });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {

            window.tabler_chart = window.tabler_chart || {};


            window.ApexCharts && (window.tabler_chart["sparkline-bounce-rate-1"] = new ApexCharts(document.getElementById('sparkline-bounce-rate-1'), {
                chart: {
                    type: "line",
                    fontFamily: 'inherit',
                    height: 24,

                    animations: {
                        enabled: false
                    },
                    sparkline: {
                        enabled: true
                    },
                },
                tooltip: {
                    enabled: false,
                },



                stroke: {
                    width: 2,
                    lineCap: "round",
                },



                series: [{
                    color: 'var(--tblr-primary)',
                    data: [17, 24, 20, 10, 5, 1, 4, 18, 13]
                }],

            })).render();
        });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {

            window.tabler_chart = window.tabler_chart || {};


            window.ApexCharts && (window.tabler_chart["sparkline-bounce-rate-2"] = new ApexCharts(document.getElementById('sparkline-bounce-rate-2'), {
                chart: {
                    type: "line",
                    fontFamily: 'inherit',
                    height: 24,

                    animations: {
                        enabled: false
                    },
                    sparkline: {
                        enabled: true
                    },
                },
                tooltip: {
                    enabled: false,
                },



                stroke: {
                    width: 2,
                    lineCap: "round",
                },



                series: [{
                    color: 'var(--tblr-primary)',
                    data: [13, 11, 19, 22, 12, 7, 14, 3, 21]
                }],

            })).render();
        });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {

            window.tabler_chart = window.tabler_chart || {};


            window.ApexCharts && (window.tabler_chart["sparkline-bounce-rate-3"] = new ApexCharts(document.getElementById('sparkline-bounce-rate-3'), {
                chart: {
                    type: "line",
                    fontFamily: 'inherit',
                    height: 24,

                    animations: {
                        enabled: false
                    },
                    sparkline: {
                        enabled: true
                    },
                },
                tooltip: {
                    enabled: false,
                },



                stroke: {
                    width: 2,
                    lineCap: "round",
                },



                series: [{
                    color: 'var(--tblr-primary)',
                    data: [10, 13, 10, 4, 17, 3, 23, 22, 19]
                }],

            })).render();
        });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {

            window.tabler_chart = window.tabler_chart || {};


            window.ApexCharts && (window.tabler_chart["sparkline-bounce-rate-4"] = new ApexCharts(document.getElementById('sparkline-bounce-rate-4'), {
                chart: {
                    type: "line",
                    fontFamily: 'inherit',
                    height: 24,

                    animations: {
                        enabled: false
                    },
                    sparkline: {
                        enabled: true
                    },
                },
                tooltip: {
                    enabled: false,
                },



                stroke: {
                    width: 2,
                    lineCap: "round",
                },



                series: [{
                    color: 'var(--tblr-primary)',
                    data: [6, 15, 13, 13, 5, 7, 17, 20, 19]
                }],

            })).render();
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            window.tabler_chart = window.tabler_chart || {};
            window.ApexCharts && (window.tabler_chart["sparkline-bounce-rate-5"] = new ApexCharts(document.getElementById('sparkline-bounce-rate-5'), {
                chart: {
                    type: "line",
                    fontFamily: 'inherit',
                    height: 24,

                    animations: {
                        enabled: false
                    },
                    sparkline: {
                        enabled: true
                    },
                },
                tooltip: {
                    enabled: false,
                },
                stroke: {
                    width: 2,
                    lineCap: "round",
                },

                series: [{
                    color: 'var(--tblr-primary)',
                    data: [2, 11, 15, 14, 21, 20, 8, 23, 18, 14]
                }],

            })).render();
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            window.tabler_chart = window.tabler_chart || {};


            window.ApexCharts && (window.tabler_chart["sparkline-bounce-rate-6"] = new ApexCharts(document.getElementById('sparkline-bounce-rate-6'), {
                chart: {
                    type: "line",
                    fontFamily: 'inherit',
                    height: 24,

                    animations: {
                        enabled: false
                    },
                    sparkline: {
                        enabled: true
                    },
                },
                tooltip: {
                    enabled: false,
                },

                stroke: {
                    width: 2,
                    lineCap: "round",
                },

                series: [{
                    color: 'var(--tblr-primary)',
                    data: [22, 12, 7, 14, 3, 21, 8, 23, 18, 14]
                }],
            })).render();
        });
    </script>

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
        })
    </script>
    <script>
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
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const editButtons = document.querySelectorAll('.btn-edit-invoice');

            editButtons.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    // Ambil data dari atribut data-*
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const username = this.getAttribute('data-username');
                    const email = this.getAttribute('data-email');
                    const phone = this.getAttribute('data-phone');
                    const password = this.getAttribute('data-password');

                    // Set ke input di dalam modal
                    document.getElementById('edit-id').value = id;
                    document.getElementById('name').value = name;
                    document.getElementById('username').value = username;
                    document.getElementById('email').value = email;
                    document.getElementById('phone').value = phone;
                    document.getElementById('password').value = password;
                });
            });
        });

        // Script untuk tombol hapus dengan konfirmasi
        const deleteButtons = document.querySelectorAll('.btn-delete-invoice');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                const confirmed = confirm("Apakah Anda yakin ingin menghapus invoice ini?");
                if (!confirmed) {
                    e.preventDefault(); // Batalkan aksi jika tidak yakin
                }
            });
        });
    </script>
    <!-- END PAGE SCRIPTS -->
</body>

</html>