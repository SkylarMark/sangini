<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <?php $this->load->view('import'); ?>
    <link rel="stylesheet" href="<?php echo base_url()."assets/menu.css" ?>">    
</head>
<body>
<div class="page-wrapper chiller-theme toggled">
  <a id="show-sidebar" class="btn btn-sm btn-dark" href="#">
    <i class="fas fa-bars"></i>
  </a>
  <nav id="sidebar" class="sidebar-wrapper">
    <div class="sidebar-content">
      <div class="sidebar-brand">
        <a href="<?php echo base_url().'dashboard' ?>">Sangini Dashboard</a>
        <div id="close-sidebar">
          <i class="fas fa-times"></i>
        </div>
      </div>
      <div class="sidebar-header">
        <div class="user-pic">
          <img class="img-responsive img-rounded" src="https://raw.githubusercontent.com/azouaoui-med/pro-sidebar-template/gh-pages/src/img/user.jpg"
            alt="User picture">
        </div>
        <div class="user-info">
          <span class="user-name"><?php echo ucfirst($this->session->userdata('username')) ?></span>
          <span class="user-role"><?php echo $this->session->userdata('role') == 0 ? 'Administrator' : 'User' ?> </span>
          <span class="user-status">
            <i class="fa fa-circle"></i>
            <span>Online</span>
          </span>
        </div>
      </div>

      <!-- Side Bar Menu -->
      <div class="sidebar-menu">
        <ul>
          <li class="header-menu">
            <span>General</span>
          </li>
          <li class="sidebar-dropdown">
            <a href="<?php echo base_url().'dashboard' ?>">
              <i class="fa fa-tachometer-alt"></i>
              <span>Dashboard</span>
              <!-- <span class="badge badge-pill badge-warning">New</span> -->
            </a>
            <div class="sidebar-submenu">
            </div>
          </li>
          <li class="sidebar-dropdown">
            <a href="#">
              <i class="fa fa-shopping-cart"></i>
              <span>E-commerce</span>
              <!-- <span class="badge badge-pill badge-danger">3</span> -->
            </a>
            <div class="sidebar-submenu">
              <ul>
                <li>
                  <a href="<?php echo base_url().'commerce/productlist' ?>">Products</a>
                </li>
                <li>
                  <a href="<?php echo base_url().'commerce/cart' ?>">Cart</a>
                </li>
                <li>
                  <a href="<?php echo base_url().'commerce/orders' ?>">Orders</a>
                </li>
              </ul>
            </div>
          </li>
          <!-- <li class="sidebar-dropdown">
            <a href="#">
              <i class="far fa-gem"></i>
              <span>Components</span>
            </a>
            <div class="sidebar-submenu">
              <ul>
                <li>
                  <a href="#">General</a>
                </li>
                <li>
                  <a href="#">Panels</a>
                </li>
                <li>
                  <a href="#">Tables</a>
                </li>
                <li>
                  <a href="#">Icons</a>
                </li>
                <li>
                  <a href="#">Forms</a>
                </li>
              </ul>
            </div>
          </li> -->
          <li class="sidebar-dropdown">
            <a href="#">
              <i class="fa fa-chart-line"></i>
                <span>Analytics</span>
                <span class="badge badge-pill badge-danger mr-1">UC</span>
            </a>
            <div class="sidebar-submenu">
              <ul>
                <li>
                  <a href="<?php echo base_url().'analytics' ?>">Sale Chart</a>
                </li>
                <li>
                  <a href="<?php echo base_url().'analytics/productsale' ?>">Product Sale Chart</a>
                </li>
                <li>
                  <a href="<?php echo base_url().'analytics/purchaces' ?>">Customer Purchace</a>
                </li>
              </ul>
            </div>
          </li>
          <li class="sidebar-dropdown">
            <a href="#">
              <i class="fa fa-globe"></i>
              <span>Master Table</span>
            </a>
            <div class="sidebar-submenu">
              <ul>
                <li>
                  <a href="<?php echo base_url().'master/product' ?>">Product Master</a>
                </li>
                <li>
                  <a href="<?php echo base_url().'master/cloth' ?>">Cloth Master</a>
                </li>
                <li>
                  <a href="<?php echo base_url().'master/color' ?>">Color Master</a>
                </li>
                <li>
                  <a href="<?php echo base_url().'master/size' ?>">Size Master</a>
                </li>
                <li>
                  <a href="<?php echo base_url().'master/discount' ?>">Discount Master</a>
                </li>
              </ul>
            </div>
          </li>
          <!-- <li class="header-menu">
            <span>Extra</span>
          </li>
          <li>
            <a href="#">
              <i class="fa fa-book"></i>
              <span>Documentation</span>
              <span class="badge badge-pill badge-primary">Beta</span>
            </a>
          </li>
          <li>
            <a href="#">
              <i class="fa fa-calendar"></i>
              <span>Calendar</span>
            </a>
          </li>
          <li>
            <a href="#">
              <i class="fa fa-folder"></i>
              <span>Examples</span>
            </a>
          </li>
        </ul>-->
        <li class="sidebar-dropdown">
            <a href="#">
              <i class="fas fa-file-alt"></i>
                <span>Logs</span>
                <!-- <span class="badge badge-pill badge-danger mr-1">UC</span> -->
            </a>
            <div class="sidebar-submenu">
              <ul>
                <li>
                  <a href="<?php echo base_url().'logs/orders' ?>">Order Log</a>
                </li>
                <li>
                  <a href="<?php echo base_url().'logs/login' ?>">Login Log</a>
                </li>
                <li>
                  <a href="<?php echo base_url().'logs/register' ?>">Register Log</a>
                </li>
                <li>
                  <a href="<?php echo base_url().'logs/product' ?>">Product Log</a>
                </li>
                <li>
                  <a href="<?php echo base_url().'logs/error' ?>">Error Log</a>
                </li>
              </ul>
            </div>
          </li>
        <li class="sidebar-dropdown">
            <a href="#">
            <i class="fas fa-plus"></i>
              <span>Support</span>
            </a>
            <div class="sidebar-submenu">
              <ul>
                <li>
                  <a href="<?php echo base_url().'support/request' ?>">Support Request</a>
                </li>
                <li>
                  <a href="<?php echo base_url().'support/developer' ?>">Contact Developer</a>
                </li>
              </ul>
            </div>
          </li>
      </div> 
      <!-- sidebar-menu  -->
    </div>
    <!-- sidebar-content  -->
    <div class="sidebar-footer">
      <a href="#">
        <i class="fa fa-bell"></i>
        <span class="badge badge-pill badge-warning notification">3</span>
      </a>
      <a href="#">
        <i class="fa fa-envelope"></i>
        <span class="badge badge-pill badge-success notification">7</span>
      </a>
      <a href="#">
        <i class="fa fa-cog"></i>
        <span class="badge-sonar"></span>
      </a>
      <a href="#">
        <i class="fa fa-power-off" onclick="logout();"></i>
      </a>
    </div>
  </nav>

<script>

    jQuery(function ($) {

            $(".sidebar-dropdown > a").click(function() {
                $(".sidebar-submenu").slideUp(200);
                if (
                $(this)
                .parent()
                .hasClass("active")
                ) {
                $(".sidebar-dropdown").removeClass("active");
                $(this)
                .parent()
                .removeClass("active");
                } else {
                $(".sidebar-dropdown").removeClass("active");
                $(this)
                .next(".sidebar-submenu")
                .slideDown(200);
                $(this)
                .parent()
                .addClass("active");
                }
            });

            $("#close-sidebar").click(function() {
                $(".page-wrapper").removeClass("toggled");
            });
            $("#show-sidebar").click(function() {
                $(".page-wrapper").addClass("toggled");
            });
    });
    function logout() {
        location.href = "<?php echo base_url()?>login/logout";
    }
</script>
</html>