<!DOCTYPE html>
<html>

<head>
    <title>Manage Icons</title>
    <?php include('templates/head.php'); ?>
</head>

<body>
<?php include('templates/settings-nav.php'); ?>
<section class="pt-5 pb-5 bg-light">
      <div class=" pt-5 pb-5" >
        <div class="container">
          <div class="row justify-content-center ">
            <div class="col-12 col-md-8 col-lg-6 text-center">
              <h1 class="display-8 mb-3 ">Upload</h1>
              <form method="POST" action="/icon-upload" enctype="multipart/form-data">
              <div class="input-group mb-3">
                
                <input type="file" class="form-control" placeholder="File"
                  aria-label="icon" name="upload-icon" aria-describedby="basic-addon2">
                <button class="btn btn-primary" type="submit">Upload</button>
                
              </div>
              </form>
              <p class="text-h4 mb-5">
                <small>Images will be converted to a 64x64 pixel PNG.</small>
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="pt-5 pb-5">
      <div class="container">
        <h2 class="text-center font-weight-light mb-5">Available icons</h2>
      </div>
      <div class="cards  text-dark">
        <div class="cards-overlay">
          <div class="container">
            <div class="row d-flex">
            <?php
            require_once('classes/icons.php');
            $icons = new ICONS;
            foreach($icons->list_all_icons() as $icon){
            ?>
              <div class=" col-sm-3 col-md-2 ">
                <div class="card h-100 border-0">
                  <div class="card-img-top">
                    <img src="/img/logos/<?php echo $icon; ?>"
                      class="img-fluid mx-auto d-block" alt="Card image cap">
                  </div>
                  <div class="card-body text-center">
                  <?php echo $icon; ?>
                  </div>
                </div>
              </div>
            <?php
            $i++;
            }
            ?>
              
            </div>
          </div>
        </div>
      </div>
    </section>

    <?php include('templates/footer.php'); ?>
    <?php include('templates/javascript_common.php'); ?>

</body>
</html>