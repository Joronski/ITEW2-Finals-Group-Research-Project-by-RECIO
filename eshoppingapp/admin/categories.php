<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LevelUp | Admin</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.3/examples/dashboard/">

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    <!-- Internal CSS Integration -->
    <style>
      .fa-trash-alt,
      .fa-pencil-alt {
        color: #fff;
      }

      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>

    <!-- Custom styles for this template -->
    <link href="./css/dashboard.css" rel="stylesheet">
  </head>
  <body>
    <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
      <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">LevelUp Admin</a>

      <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
          <?php
          if (isset($_SESSION['admin_id'])) {
          ?>
            <a class="nav-link" href="../admin/admin-logout.php">Sign out</a>

            <?php
          } else {
            $uriAr = explode("/", $_SERVER['REQUEST_URI']);
            $page = end($uriAr);
            if ($page === "login.php") {
            ?>

              <a class="nav-link" href="../admin/register.php">Register</a>

            <?php
            } else {
            ?>
              <a class="nav-link" href="../admin/login.php">Login</a>
          <?php
            }
          }
          ?>
        </li>
      </ul>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <?php include "./templates/sidebar.php"; ?>
        <div class="row">
          <div class="col-10">
            <h2>Manage Category</h2>
          </div>
          <div class="col-2">
            <a href="#" data-toggle="modal" data-target="#add_category_modal" class="btn btn-warning btn-sm">Add Product Category</a>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-striped table-sm">
            <thead>
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="category_list">
            </tbody>
          </table>
        </div>
        </main>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="add_category_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Update Category</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="add-category-form" enctype="multipart/form-data">
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <label>Category Name</label>
                    <input type="text" name="cat_title" class="form-control" placeholder="Enter Brand Name">
                  </div>
                </div>
                <input type="hidden" name="add_category" value="1">
                <div class="col-12">
                  <button type="button" class="btn btn-primary add-category">Add Category</button>
                </div>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal -->

    <!--Edit category Modal -->
    <div class="modal fade" id="edit_category_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Category</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="edit-category-form" enctype="multipart/form-data">
              <div class="row">
                <div class="col-12">
                  <input type="hidden" name="cat_id">
                  <div class="form-group">
                    <label>Category Name</label>
                    <input type="text" name="e_cat_title" class="form-control" placeholder="Enter Brand Name">
                  </div>
                </div>
                <input type="hidden" name="edit_category" value="1">
                <div class="col-12">
                  <button type="button" class="btn btn-primary edit-category-btn">Update Category</button>
                </div>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap JS and jQuery Integration -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>

    <!-- Integrated External JS -->
    <script src="./js/dashboard.js"></script>
    <script type="text/javascript" src="./js/categories.js"></script>
  </body>
</html>