<?php
  session_start();
  require_once "../db.php";

  // Check if user is logged in
  if (!isset($_SESSION['admin_id'])) {
    header("location:login.php");
    exit();
  }

  // Query to fetch all admins
  $sql = "SELECT id, name, email, is_active FROM admin";
  $result = mysqli_query($con, $sql);

  // Check for query error
  if (!$result) {
    die("Query failed: " . mysqli_error($con));
  }
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

      .modal-dialog {
        max-width: 500px;
      }

      .modal-body {
        padding: 20px;
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
            <a class="nav-link" href="admin-logout.php">Sign out</a>

            <?php
          } else {
            $uriAr = explode("/", $_SERVER['REQUEST_URI']);
            $page = end($uriAr);
            if ($page === "login.php") {
            ?>

              <a class="nav-link" href="register.php">Register</a>

            <?php
            } else {
            ?>
              <a class="nav-link" href="login.php">Login</a>
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

        <h2>
          <center>Admin Details</center>
        </h2>

        <div class="table-responsive">
          <table class="table table-striped table-sm">
            <thead>
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="admin_list">
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Edit Admin Modal -->
    <div class="modal fade" id="editAdminModal" tabindex="-1" role="dialog" aria-labelledby="editAdminModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editAdminModalLabel">Edit Admin</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="editAdminForm" action="update_admin.php" method="POST">
            <div class="modal-body">
              <input type="hidden" name="admin_id" id="edit_admin_id">
              <div class="form-group">
                <label for="edit_name">Name</label>
                <input type="text" class="form-control" id="edit_name" name="name" required>
              </div>

              <div class="form-group">
                <label for="edit_email">Email</label>
                <input type="email" class="form-control" id="edit_email" name="email" required>
              </div>

              <div class="form-group">
                <label for="edit_password">Password (leave blank to keep current)</label>
                <input type="password" class="form-control" id="edit_password" name="password">
              </div>

              <div class="form-group">
                <label for="edit_status">Status</label>
                <select class="form-control" id="edit_status" name="is_active">
                  <option value="1">Active</option>
                  <option value="0">Inactive</option>
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
          </form>
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
    <script src="./js/admin.js"></script>

    <!-- Inline JavaScript for edit functionality -->
    <script type="text/javascript">
      $(document).ready(function() {
        // Initialize feather icons
        feather.replace();

        // Edit admin button click handler
        $('.edit-admin').click(function() {
          var adminId = $(this).data('id');
          var name = $(this).data('name');
          var email = $(this).data('email');
          var status = $(this).data('status');

          $('#edit_admin_id').val(adminId);
          $('#edit_name').val(name);
          $('#edit_email').val(email);
          $('#edit_status').val(status);

          $('#editAdminModal').modal('show');
        });
      });
    </script>
  </body>
</html>