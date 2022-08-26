<!DOCTYPE html>
<html>

  <head>
    <title>Login</title>
    <?php include('templates/head.php'); ?>
  </head>

  <body>
  <?php include('templates/nav.php'); ?>

    <section class="jumbotron py-5">
      <div class="container">
        <div class="row justify-content-center text-center">
          <div class="col-md-6">
            <h1 class="jumbotron-heading">Login</h1>
            <button class="btn btn-success btn-lg mt-5 mb-5 "  data-bs-toggle="modal" data-bs-target="#exampleModal">Login</button>
            </p>
          </div>
        </div>
      </div>
    </section>

    <?php include('templates/javascript_common.php'); ?>
    <?php include('templates/modal_login.php'); ?>
    
  </body>

</html>
