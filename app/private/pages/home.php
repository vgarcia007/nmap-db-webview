<!DOCTYPE html>
<html>

<head>
  <title>Nmap Results</title>
  <?php include('templates/head.php'); ?>
</head>

<body>
  <section class="pb-0 pt-5">
    <div class="block text-center">
      <div class="container">
        <div class="row">
          <div class="col-12 mb-3 text-start">
            <div class="dropdown">
              <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
              Order by
              </button>
              <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                <li><a class="dropdown-item" href="/">Order by IPv4</a></li>
                <li><a class="dropdown-item" href="/order-by/reg-date">Order by registration date</a></li>
                <li><a class="dropdown-item" href="/order-by/last-seen">Order by registration last seen</a></li>
              </ul>
            </div>
          </div>

          <div class="col-12">
            <form id="search_in_elements_form" class="form-inline  h-25">
              <div class="form-group text-start">
                <div class="input-group mb-3">
                  <button class="btn btn-success" id="search_in_elements_button">
                    <i class="fas fa-search"></i>
                  </button>
                  <input type="text" class="form-control" id="search_in_elements">
                </div>
              </div>
            </form>
          </div>

        </div>
      </div>
    </div>
  </section>
  <section class="pt-3 pb-5">
    <div class="container">
      <div class="row align-items-center justify-content-around">
        <div class="col-12 col-md-12" id="elements">
          <?php
          require_once 'classes/hosts.php';
          $hosts = new HOSTS;
          $i = 0;
          foreach ($hosts->view_known_hosts($order) as $host) {
            $ports = $hosts->view_host_ports($host['id']);
          ?>
            <div class="media mt-4 pb-3 d-flex align-items-center element" id="<?php echo 'list' . $i; ?>">
              <img class="me-3 img-fluid rounded" src="<?php echo $host['icon']; ?>">
              <div class="media-body small">
                <h5 class="mt-0 mb-1">
                  <i class="fas fa-circle <?php echo $host['state_css']; ?>" title="<?php echo $host['state']; ?>"></i> <?php echo $host['ipv4']; ?>
                </h5>
                <?php if ($host['hostname']) {
                  echo '<i><b>' . $host['hostname'] . '</b></i> <br>';
                } ?>
                <?php if ($host['vendor']) {
                  echo $host['vendor'] . '<br>';
                } ?>
                <small>known since <?php echo $host['reg_date']; ?> last seen: <?php echo $host['last_seen']; ?> </small>
                <?php if ($host['state'] == 'up') {
                  echo up_since($host['uptime_seconds']);
                } ?><br>
                <small><?php echo $host['mac']; ?> <?php echo $host['os']; ?></small>
                <?php if (!empty($ports)) {
                  echo '<br>';
                } ?>
                <?php foreach ($ports as $port) { ?>
                  <small><?php echo $port['portid']; ?>/<?php echo $port['protocol']; ?> </small>
                <?php } ?>
              </div>
            </div>
          <?php
            $i++;
          }
          ?>
        </div>
      </div>
    </div>
  </section>
  <?php include('templates/footer.php'); ?>
  <?php include('templates/javascript_common.php'); ?>
  <?php include('templates/javascript_search_in_elements.php'); ?>
</body>

</html>