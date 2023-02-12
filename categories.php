<?php include 'init.php';?>

<div class="container">
    <h1 class="text-center"><?php echo str_replace('-',' ', $_GET['pagename']) ?></h1>
    <div class="row">
        <?php
        foreach(getItems($_GET['pageid']) as $item){
            echo '<div class="col-sm-6 col-md-3 mt-3">';
                echo '<div class="card item-box">';
                    echo '<span class="price-tag">'. $item['Price'] .'</span>';
                    echo '<img src="NonUser.png" alt="non image user" class="img-thumbnail" >';
                    echo '<div class="card-body">
                            <h5 class="card-title">'. $item['Name'] .'</h5>
                            <p class="card-text">'. $item['Description'] .'</p>
                        </div>';
                echo '</div>';
            echo '</div>';
        }
    ?>
    </div>
</div>

<?php   include $tpl . 'footer.php'; ?>