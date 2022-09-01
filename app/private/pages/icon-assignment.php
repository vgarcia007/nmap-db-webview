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
                    <h1 class="display-8 mb-3 ">Icon Assignment</h1>
                    <form class="row g-3" method="POST" action="/icon-assignment" >
                        <div class="col-md-6">
                            <label for="search_string" class="form-label">search_string</label>
                            <input type="text" class="form-control" id="search_string" name="search_string">
                        </div>
                        <div class="col-md-2">
                            <label for="match_in" class="form-label">match_in</label>
                            <select id="match_in" name="match_in" class="form-select">
                                <option value ="os">os</option>
                                <option value ="vendor">vendor</option>
                                <option value ="hostname">hostname</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="icon_file" class="form-label">icon_file</label>
                            <select id="icon_file" name="icon_file" class="form-select">
                            <?php
                                require_once('classes/icons.php');
                                $icons = new ICONS;
                                foreach($icons->list_all_icons() as $icon){
                                ?>
                                <option value ="<?php echo $icon; ?>"><?php echo $icon; ?></option>
                                <?php
                                $i++;
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Assign</button>
                        </div>
                        </form>
                </div>
           </div>
        </div>
    </div>
</section>
<section class="pt-5 pb-5 mt-0 mb-0 text-center">
      <div class="container">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">search_string</th>
                    <th scope="col">match_in</th>
                    <th scope="col">icon_file</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once('classes/icons.php');
                $icons = new ICONS;
                foreach($icons->list_all_assignments() as $icon){
                ?>
                <tr>
                    <td><?php echo $icon['search_string']; ?></td>
                    <td><?php echo $icon['match_in']; ?></td>
                    <td><?php echo $icon['icon_file']; ?></td>
                </tr>
                <?php
                $i++;
                }
                ?>
            </tbody>
        </table>
      </div>
</section>
    <?php include('templates/footer.php'); ?>
    <?php include('templates/javascript_common.php'); ?>

</body>
</html>