<?php include 'init.php';?>

<div class="container">
    <h1 class="text-center"><?php echo str_replace('-',' ', $_GET['pagename']) ?></h1>
    <div class="row">
        <?php
        foreach(getItems('Cat_ID', $_GET['pageid']) as $item){
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

<style>
/* Start Categories Page */
.item-box {
    position: relative;
    color: var(--deg-color-5);
    background: var(--deg-color-4);
}
.item-box .price-tag {
    position: absolute;
    left: 5px;
    top: 5px;
    background: var(--deg-color-3);
    padding: 5px 10px;
    border-radius: 20px 5px;
    color: var(--text-dark);
    text-shadow: 4px -4px 5px #000;
}
.item-box .card-body .card-text {
    display: -webkit-box;
    -webkit-line-clamp: 2;  /* lines You Want To Show And Hide The Rest */
    -webkit-box-orient: vertical;
    overflow: hidden;
}
/* End Categories Page */
/* --------------------------------*/
</style>

<?php   include $tpl . 'footer.php'; ?>