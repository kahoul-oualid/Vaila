<?php require_once('header.php'); ?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $banner_product_category = $row['banner_product_category'];
}
?>

<?php

$statement = $pdo->prepare("SELECT * FROM tbl_top_category");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $top[] = $row['tcat_id'];
    $top1[] = $row['tcat_name'];
}

$statement = $pdo->prepare("SELECT * FROM tbl_mid_category");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $mid[] = $row['mcat_id'];
    $mid1[] = $row['mcat_name'];
    $mid2[] = $row['tcat_id'];
}

$statement = $pdo->prepare("SELECT * FROM tbl_end_category");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $end[] = $row['ecat_id'];
    $end1[] = $row['ecat_name'];
    $end2[] = $row['mcat_id'];
}


