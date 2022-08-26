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
            <div class="col-12">
              <form id="search_in_elements_form" class="form-inline  h-25">
                <div class="form-group text-start">
                  <div class="input-group mb-3">
                    <button class="btn btn-success" id="search_in_elements_button">
                      <i class="fas fa-search"></i>
                    </button>
                    <input type="text" class="form-control" id="search_in_elements" >
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
                    require_once 'config-icons.php';
                    require_once 'classes/hosts.php';
                    $hosts = new HOSTS;
                    $i=0;
                    foreach($hosts->view_known_hosts() as $host){
                    ?>
                    <div class="media mt-4 pb-3 d-flex align-items-center element" id="<?php echo 'list'.$i; ?>">
                        <img class="me-3 img-fluid rounded" src="<?php echo match_icon($host_icons,$host); ?>">
                        <div class="media-body small">
                            <h5 class="mt-0 mb-1">
                              <i class="fas fa-circle <?php echo match_state_css_class($host['state']); ?>" title="<?php echo $host['state']; ?>"></i> <?php echo $host['ipv4']; ?>
                            </h5>
                            <?php if($host['state'] == 'up'){ echo up_since($host['uptime_seconds']); } ?>
                            <?php echo $host['vendor']; ?> <i><?php echo $host['hostname']; ?></i> <br>
                            <small><?php echo $host['mac']; ?> <?php echo $host['os']; ?></small>
                            <?php if (!empty($ports)){ echo '<br>'; }?>
                            <?php foreach($hosts->view_host_ports($host['id']) as $port){ ?>
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