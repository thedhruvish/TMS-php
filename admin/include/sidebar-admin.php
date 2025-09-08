<?php

$data = $DB->read("users", [
  "where" => [
    "id" => ["=" => $_SESSION['user_id']]
  ]
]);

$user = mysqli_fetch_assoc($data);
?>

<!--  BEGIN NAVBAR  -->
<div class="header-container container-xxl">
  <header class="header navbar navbar-expand-sm expand-header">

    <ul class="navbar-item theme-brand flex-row  text-center">
      <li class="nav-item theme-logo">
        <a href="index.php">
          <img src="../../src/assets/img/logo2.svg" class="navbar-logo" alt="logo">
        </a>
      </li>
      <li class="nav-item theme-text">
        <a href="index.php" class="nav-link"> TMS </a>
      </li>
    </ul>

    <ul class="navbar-item flex-row ms-lg-auto ms-0 action-area">
      <li class="nav-item theme-toggle-item">
        <a href="javascript:void(0);" class="nav-link theme-toggle">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="feather feather-moon dark-mode">
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
          </svg>
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="feather feather-sun light-mode">
            <circle cx="12" cy="12" r="5"></circle>
            <line x1="12" y1="1" x2="12" y2="3"></line>
            <line x1="12" y1="21" x2="12" y2="23"></line>
            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
            <line x1="1" y1="12" x2="3" y2="12"></line>
            <line x1="21" y1="12" x2="23" y2="12"></line>
            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
          </svg>
        </a>
      </li>

      <li class="nav-item dropdown user-profile-dropdown  order-lg-0 order-1">
        <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown"
          data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <div class="avatar-container">
            <div class="avatar avatar-sm avatar-indicators avatar-online">
              <img alt="avatar" src="<?php echo "../images/profile/" . $user['profile_picture']; ?>"
                class="rounded-circle">
            </div>
          </div>
        </a>

        <div class="dropdown-menu position-absolute" aria-labelledby="userProfileDropdown">
          <div class="user-profile-section">
            <div class="media mx-auto">
              <div class="emoji me-2">
                &#x1F44B;
              </div>
              <div class="media-body">
                <h5><?php echo $user['email'] ?></h5>
              </div>
            </div>
          </div>
          <div class="dropdown-item">
            <a href="user-add.php?id=<?php echo $user['id']; ?>">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-user">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
              </svg> <span>Profile</span>
            </a>
          </div>
          <div class="dropdown-item">
            <a href="logout.php">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-log-out">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
              </svg>
              <span>Log Out</span>
            </a>
          </div>
        </div>

      </li>
    </ul>
  </header>
</div>
<!--  END NAVBAR  -->


<!-- BEGIN MAIN CONTAINER  -->
<div class="main-container" id="container">

  <div class="overlay"></div>
  <div class="cs-overlay"></div>
  <div class="search-overlay"></div>

  <!--  BEGIN SIDEBAR  -->
  <div class="sidebar-wrapper sidebar-theme">

    <nav id="sidebar">

      <div class="navbar-nav theme-brand flex-row  text-center">
        <div class="nav-logo">
          <div class="nav-item theme-logo">
            <a href="./index.php">
              <img src="../../src/assets/img/logo.svg" class="navbar-logo" alt="logo">
            </a>
          </div>
          <div class="nav-item theme-text">
            <a href="./index.php" class="nav-link"> TMS </a>
          </div>
        </div>
        <div class="nav-item sidebar-toggle">
          <div class="btn-toggle sidebarCollapse">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="feather feather-chevrons-left">
              <polyline points="11 17 6 12 11 7"></polyline>
              <polyline points="18 17 13 12 18 7"></polyline>
            </svg>
          </div>
        </div>
      </div>
      <div class="shadow-bottom"></div>
      <ul class="list-unstyled menu-categories" id="accordionExample">
        <!-- dashboard navbar -->
        <li class="menu">
          <a href="dashboard.php" aria-expanded="false" class="dropdown-toggle">
            <div class="">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-home">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
              </svg>

              <span>Dashboard</span>
            </div>
          </a>
        </li>

        <!-- products navbar -->
        <li class="menu">
          <a href="#products" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
            <div class="">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-shopping-bag">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                <line x1="3" y1="6" x2="21" y2="6" />
                <path d="M16 10a4 4 0 0 1-8 0" />
              </svg>
              <span>Products</span>
            </div>
            <div>
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-chevron-right">
                <polyline points="9 18 15 12 9 6"></polyline>
              </svg>
            </div>
          </a>
          <ul class="collapse submenu list-unstyled " id="products" data-bs-parent="#accordionExample">
            <li class="active">
              <a href="./products.php"> Products </a>
            </li>
            <li>
              <a href="./products-add.php"> Add Products </a>
            </li>
          </ul>
        </li>

        <!-- cetegory navbar -->
        <li class="menu">
          <a href="#cetegory" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
            <div class="">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-calendar">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                <line x1="16" y1="2" x2="16" y2="6" />
                <line x1="8" y1="2" x2="8" y2="6" />
                <line x1="3" y1="10" x2="21" y2="10" />
              </svg>
              <span>Cetegory</span>
            </div>
            <div>
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-chevron-right">
                <polyline points="9 18 15 12 9 6"></polyline>
              </svg>
            </div>
          </a>
          <ul class="collapse submenu list-unstyled " id="cetegory" data-bs-parent="#accordionExample">
            <li class="active">
              <a href="./cetegory.php"> Cetegory </a>
            </li>
            <li>
              <a href="./cetegory-add.php"> Add Cetegory </a>
            </li>
          </ul>
        </li>

        <!-- customer navbar -->
        <li class="menu">
          <a href="#customer" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
            <div class="">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-users">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                <circle cx="9" cy="7" r="4" />
                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
              </svg>
              <span>Customer</span>
            </div>
            <div>
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-chevron-right">
                <polyline points="9 18 15 12 9 6"></polyline>
              </svg>
            </div>
          </a>
          <ul class="collapse submenu list-unstyled " id="customer" data-bs-parent="#accordionExample">
            <li class="active">
              <a href="./customer.php"> Customer </a>
            </li>
            <li>
              <a href="./customer-add.php"> Add Customer </a>
            </li>
          </ul>
        </li>

        <!-- stock -->
        <li class="menu">
          <a href="#Stoack" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
            <div class="">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-layers">
                <polygon points="12 2 2 7 12 12 22 7 12 2" />
                <polyline points="2 17 12 22 22 17" />
                <polyline points="2 12 12 17 22 12" />
              </svg>
              <span>Stock</span>
            </div>
            <div>
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-chevron-right">
                <polyline points="9 18 15 12 9 6"></polyline>
              </svg>
            </div>
          </a>
          <ul class="collapse submenu list-unstyled " id="Stoack" data-bs-parent="#accordionExample">
            <li class="active">
              <a href="./stoack.php"> Stock </a>
            </li>
            <li>
              <a href="./Stoack-add.php"> Add Stock </a>
            </li>
          </ul>
        </li>

        <!-- invoice navbar -->
        <li class="menu">
          <a href="#invoice" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
            <div class="">
              <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 2h16a2 2 0 0 1 2 2v16l-4-2-4 2-4-2-4 2V4a2 2 0 0 1 2-2z" />
                <line x1="8" y1="9" x2="16" y2="9" />
                <line x1="8" y1="13" x2="16" y2="13" />
                <line x1="8" y1="17" x2="12" y2="17" />
              </svg>

              <span>Invoice</span>
            </div>
            <div>
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-chevron-right">
                <polyline points="9 18 15 12 9 6"></polyline>
              </svg>
            </div>
          </a>
          <ul class="collapse submenu list-unstyled " id="invoice" data-bs-parent="#accordionExample">
            <li class="active">
              <a href="./invoice.php"> Invoice </a>
            </li>
            <li>
              <a href="./invoice-add.php"> Add Invoice </a>
            </li>
          </ul>
        </li>

        <!-- payment -->
        <li class="menu">
          <a href="#payment" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
            <div class="">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-dollar-sign">
                <line x1="12" y1="1" x2="12" y2="23" />
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
              </svg>
              <span>Payment</span>
            </div>
            <div>
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-chevron-right">
                <polyline points="9 18 15 12 9 6"></polyline>
              </svg>
            </div>
          </a>
          <ul class="collapse submenu list-unstyled " id="payment" data-bs-parent="#accordionExample">
            <li class="active">
              <a href="./payment.php"> Payment </a>
            </li>
            <li>
              <a href="./payment-add.php"> Add Payment </a>
            </li>
          </ul>
        </li>

        <!-- User -->
        <li class="menu">
          <a href="#user" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
            <div class="">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-user">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                <circle cx="12" cy="7" r="4" />
              </svg>
              <span>Users</span>
            </div>
            <div>
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-chevron-right">
                <polyline points="9 18 15 12 9 6"></polyline>
              </svg>
            </div>
          </a>
          <ul class="collapse submenu list-unstyled " id="user" data-bs-parent="#accordionExample">
            <li class="active">
              <a href="./user.php"> Users </a>
            </li>
            <li>
              <a href="./user-add.php"> Add Users </a>
            </li>
          </ul>
        </li>
        <!-- Inquiry  navbar -->
        <li class="menu">
          <a href="#inquiry" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
            <div class="">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-file">
                <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z" />
                <polyline points="13 2 13 9 20 9" />
              </svg>
              <span>Inquiry</span>
            </div>
            <div>
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-chevron-right">
                <polyline points="9 18 15 12 9 6"></polyline>
              </svg>
            </div>
          </a>
          <ul class="collapse submenu list-unstyled " id="inquiry" data-bs-parent="#accordionExample">
            <li class="active">
              <a href="./inquiry.php"> Inquiry </a>
            </li>
            <li>
              <a href="./inquiry-add.php"> Add Inquiry </a>
            </li>
          </ul>
        </li>

        <!-- Attendance -->
        <li class="menu">
          <a href="#Attendant" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
            <div class="">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-clipboard">
                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
                <rect x="8" y="2" width="8" height="4" rx="1" ry="1" />
              </svg>
              <span>Attendance</span>
            </div>
            <div>
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-chevron-right">
                <polyline points="9 18 15 12 9 6"></polyline>
              </svg>
            </div>
          </a>
          <ul class="collapse submenu list-unstyled " id="Attendant" data-bs-parent="#accordionExample">
            <li class="active">
              <a href="./attendant.php"> Attendance </a>
            </li>
            <li>
              <a href="./view-attendant.php"> View Attendance </a>
            </li>
          </ul>
        </li>

        <!-- weblink -->
        <li class="menu">
          <a href="weblink.php" aria-expanded="false" class="dropdown-toggle">
            <div class="">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-globe">
                <circle cx="12" cy="12" r="10" />
                <line x1="2" y1="12" x2="22" y2="12" />
                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
              </svg>
              <span>Weblink</span>
            </div>
          </a>
        </li>

        <!-- log -->
        <li class="menu">
          <a href=" log.php" aria-expanded="false" class="dropdown-toggle">
            <div class="">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-clock">
                <circle cx="12" cy="12" r="10" />
                <polyline points="12 6 12 12 16 14" />
              </svg>
              <span>Log</span>
            </div>
          </a>
        </li>

        <li class="menu menu-heading">
          <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="feather feather-minus">
              <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg><span>APPLICATIONS</span></div>
        </li>

      </ul>

    </nav>

  </div>
  <!--  END SIDEBAR  -->

  <!--  BEGIN CONTENT AREA  -->
  <div id="content" class="main-content">
    <div class="layout-px-spacing">

      <div class="middle-content container-xxl p-0">

        <!--  BEGIN BREADCRUMBS  -->
        <div class="secondary-nav ">
          <div class="breadcrumbs-container" data-page-heading="Analytics">
            <header class="header navbar navbar-expand-sm">
              <a href="javascript:void(0);" class="btn-toggle sidebarCollapse" data-placement="bottom">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="feather feather-menu">
                  <line x1="3" y1="12" x2="21" y2="12"></line>
                  <line x1="3" y1="6" x2="21" y2="6"></line>
                  <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
              </a>
            </header>
          </div>
        </div>
        <!--  END BREADCRUMBS  -->