<?php require_once('header.php'); ?>

<?php
if(!isset($_REQUEST['id'])) {
    header('location: index.php');
    exit;
} else {
    // Check the id is valid or not
    $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=?");
    $statement->execute(array($_REQUEST['id']));
    $total = $statement->rowCount();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    if( $total == 0 ) {
        header('location: index.php');
        exit;
    }
}

foreach($result as $row) {
    $p_name = $row['p_name'];
    $p_old_price = $row['p_old_price'];
    $p_current_price = $row['p_current_price'];
    $p_qty = $row['p_qty'];
    $p_featured_photo = $row['p_featured_photo'];
    $p_description = $row['p_description'];
    $p_short_description = $row['p_short_description'];
    $p_feature = $row['p_feature'];
    $p_condition = $row['p_condition'];
    $p_return_policy = $row['p_return_policy'];
    $p_total_view = $row['p_total_view'];
    $p_is_featured = $row['p_is_featured'];
    $p_is_active = $row['p_is_active'];
    $ecat_id = $row['ecat_id'];
}

// Getting all categories name for breadcrumb
$statement = $pdo->prepare("SELECT
                        t1.ecat_id,
                        t1.ecat_name,
                        t1.mcat_id,

                        t2.mcat_id,
                        t2.mcat_name,
                        t2.tcat_id,

                        t3.tcat_id,
                        t3.tcat_name

                        FROM tbl_end_category t1
                        JOIN tbl_mid_category t2
                        ON t1.mcat_id = t2.mcat_id
                        JOIN tbl_top_category t3
                        ON t2.tcat_id = t3.tcat_id
                        WHERE t1.ecat_id=?");
$statement->execute(array($ecat_id));
$total = $statement->rowCount();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $ecat_name = $row['ecat_name'];
    $mcat_id = $row['mcat_id'];
    $mcat_name = $row['mcat_name'];
    $tcat_id = $row['tcat_id'];
    $tcat_name = $row['tcat_name'];
}


$p_total_view = $p_total_view + 1;

$statement = $pdo->prepare("UPDATE tbl_product SET p_total_view=? WHERE p_id=?");
$statement->execute(array($p_total_view,$_REQUEST['id']));


$statement = $pdo->prepare("SELECT * FROM tbl_product_size WHERE p_id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $size[] = $row['size_id'];
}

$statement = $pdo->prepare("SELECT * FROM tbl_product_color WHERE p_id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $color[] = $row['color_id'];
}


if(isset($_POST['form_review'])) {
    
    $statement = $pdo->prepare("SELECT * FROM tbl_rating WHERE p_id=? AND cust_id=?");
    $statement->execute(array($_REQUEST['id'],$_SESSION['customer']['cust_id']));
    $total = $statement->rowCount();
    
    if($total) {
        $error_message = LANG_VALUE_68; 
    } else {
        $statement = $pdo->prepare("INSERT INTO tbl_rating (p_id,cust_id,comment,rating) VALUES (?,?,?,?)");
        $statement->execute(array($_REQUEST['id'],$_SESSION['customer']['cust_id'],$_POST['comment'],$_POST['rating']));
        $success_message = LANG_VALUE_163;    
    }
    
}

// Getting the average rating for this product
$t_rating = 0;
$statement = $pdo->prepare("SELECT * FROM tbl_rating WHERE p_id=?");
$statement->execute(array($_REQUEST['id']));
$tot_rating = $statement->rowCount();
if($tot_rating == 0) {
    $avg_rating = 0;
} else {
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
    foreach ($result as $row) {
        $t_rating = $t_rating + $row['rating'];
    }
    $avg_rating = $t_rating / $tot_rating;
}
?>

<?php
if($error_message1 != '') {
    echo "<script>alert('".$error_message1."')</script>";
}
if($success_message1 != '') {
    echo "<script>alert('".$success_message1."')</script>";
    header('location: product.php?id='.$_REQUEST['id']);
}
?>



<div class="page">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
            <!--
                <div class="breadcrumb mb_30">
                    <ul>
                        <li><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li>></li>
                        <li><a href="<?php echo BASE_URL.'product-category.php?id='.$tcat_id.'&type=top-category' ?>"><?php echo $tcat_name; ?></a></li>
                        <li>></li>
                        <li><a href="<?php echo BASE_URL.'product-category.php?id='.$mcat_id.'&type=mid-category' ?>"><?php echo $mcat_name; ?></a></li>
                        <li>></li>
                        <li><a href="<?php echo BASE_URL.'product-category.php?id='.$ecat_id.'&type=end-category' ?>"><?php echo $ecat_name; ?></a></li>
                        <li>></li>
                        <li><?php echo $p_name; ?></li>
                    </ul>
                </div>
            -->
				<div class="product">
					<div class="row">
						<div class="col-md-5">
							<ul class="prod-slider">
                                
								<li>
                                    
                                    <img class="popup" src="assets/uploads/<?php echo $p_featured_photo; ?>">
                           
								</li>
                                <?php
                                $statement = $pdo->prepare("SELECT * FROM tbl_product_photo WHERE p_id=?");
                                $statement->execute(array($_REQUEST['id']));
                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($result as $row) {
                                    ?>
                                    <li>
                                        <img class="popup" src="assets/uploads/product_photos/<?php echo $row['photo']; ?>">
                                    </li>
                                    <?php
                                }
                                ?>
							</ul>
							<div id="prod-pager">
								<a data-slide-index="0" href="">
                                    <div class="prod-pager-thumb" style="background-image: url(assets/uploads/<?php echo $p_featured_photo; ?>">
                                </div>
                                </a>
                                <?php
                                $i=1;
                                $statement = $pdo->prepare("SELECT * FROM tbl_product_photo WHERE p_id=?");
                                $statement->execute(array($_REQUEST['id']));
                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($result as $row) {
                                    ?>
                                    <a data-slide-index="<?php echo $i; ?>" href="">
                                    <div class="prod-pager-thumb" style="background-image: url(assets/uploads/product_photos/<?php echo $row['photo']; ?>"></div>
                                    </a>
                                    <?php
                                    $i++;
                                }
                                ?>
							</div>
						</div>
						<div class="col-md-7">
                           
                            <div>
                            <a href="<?php echo BASE_URL.'product-category.php?id='.$ecat_id.'&type=end-category' ?>" >
                            <h2 class="price-sub__heading" ><?php echo $ecat_name; ?></h2></a>   
                            <h1 class="price-main__heading" ><?php echo $p_name; ?></h1>
                            </div>
							<div class="p-review">
								<div class="rating">
                                    <?php
                                    if($avg_rating == 0) {
                                        echo '
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>';
                                    }
                                    elseif($avg_rating == 1.5) {
                                        echo '
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        ';
                                    } 
                                    elseif($avg_rating == 2.5) {
                                        echo '
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        ';
                                    }
                                    elseif($avg_rating == 3.5) {
                                        echo '
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        ';
                                    }
                                    elseif($avg_rating == 4.5) {
                                        echo '
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                        ';
                                    }
                                    else {
                                        for($i=1;$i<=5;$i++) {
                                            ?>
                                            <?php if($i>$avg_rating): ?>
                                                <i class="fa fa-star-o"></i>
                                            <?php else: ?>
                                                <i class="fa fa-star"></i>
                                            <?php endif; ?>
                                            <?php
                                        }
                                    }                                    
                                    ?>
                                </div>
							</div>
							<div class="price-txt">
								<p>
									<?php echo $p_short_description; ?>
								</p>
							</div>




                            <form class="form-add" action="" method="post">
                            <div class="p-quantity">
                             

                                <?php if(isset($color)): ?>
                                    <div class="color-choose">
                                        <?php echo LANG_VALUE_53; ?> <br>
                                        
                                            <?php
                                            $statement = $pdo->prepare("SELECT * FROM tbl_color");
                                            $statement->execute();
                                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                            $i=0;
                                            foreach ($result as $row) {
                                                if(in_array($row['color_id'],$color)) {
                                                    $i++;
                                                    ?> 
                                                    <input type="radio" class="tbr-color" id="<?php echo $row['color_name']; ?>" name="color_id" value="<?php echo $row['color_id']; ?>" 
                                                    <?php if($i == 1){
                                                        ?>checked<?php
                                                        } ?>
                                                    />
                                                    <label for="<?php echo $row['color_name']; ?>"><span></span></label>   
                                                    <?php
                                                }
                                            }
                                            ?>
                                        
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="p-quantity">
                                    <?php if(isset($size)): ?>
                                    <div class="size-config">
                                        <?php echo LANG_VALUE_52; ?> <br>
                                        <div class="size-choose">
                                            <?php
                                            $statement = $pdo->prepare("SELECT * FROM tbl_size");
                                            $statement->execute();
                                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                            $i=0;
                                            foreach ($result as $row) {
                                                if(in_array($row['size_id'],$size)) {
                                                    $i++;
                                                    ?>
                                                    <div>
                                                    <input type="radio" class="tbr-size" id="<?php echo $row['size_name']; ?>" name="size_id" value="<?php echo $row['size_id']; ?>" 
                                                    <?php if($i == 1){
                                                        ?>checked<?php
                                                        } ?>
                                                    />    
                                                    <label for="<?php echo $row['size_name']; ?>"><span><?php echo $row['size_name']; ?></span></label> 
                                                    </div>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    

                                </div>
                                
                           
							<div class="p-price">
                                <span style="font-size:14px;"><?php echo LANG_VALUE_54; ?></span><br>
                              <div class="price-box">

                              <div class=".price-box__old">  
                                <span>
                                    <?php if($p_old_price!=''): ?>
                                        <del><?php echo $p_old_price; ?> DA</del>
                                    <?php endif; ?> 
                                </span>
                              </div>
                                <div class="price-box__main">                                
                                <span class="price-box__main-new">
                                <?php echo $p_current_price; ?> DA
                                </span>
                                <?php $discount = 100-(($p_current_price*100)/$p_old_price); ?>
                                <span class="price-box__main-discount"> <?php echo floor($discount); ?>%</span>
                                </div>
                              </div>  
                            </div>
                            <input type="hidden" name="p_current_price" value="<?php echo $p_current_price; ?>" class="tbr-price">
                            <input type="hidden" name="p_name" value="<?php echo $p_name; ?>" class="tbr-name">
                            <input type="hidden" name="p_featured_photo" value="<?php echo $p_featured_photo; ?>" class="tbr-fphoto">



							<div class="p-quantity tbr-qu">

                            <div class="quantity buttons_added price-btns">
                       
                            <input type="button" value="-" class="minus price-btn__add price-btn" style=" color: var(--orange); font-size: 25px;">  
                            <input type="number" class="input-text  price-btn__txt qty tbr-qty" step="1" min="1" max="" name="p_qty" value="1" title="Qty" size="4" pattern="[0-9]*" inputmode="numeric">                    
                            <input type="button" value="+" class="plus price-btn__remove price-btn" style=" color: var(--orange); font-size: 20px;">
                            <script data-require="jquery@3.1.1" data-semver="3.1.1" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
                            </div>

                           

                            <div class="btn-cart btn-cart1">
                                <input type="hidden" value="<?php echo $_GET['id'] ?>"  class="tbr-id">                
                                <input type="submit" value="<?php echo LANG_VALUE_154; ?>" class="tbr-add-to-cart tbr-btn-success">
							</div>

                            </div>
                            </form>

							<div class="share">
                                <?php echo LANG_VALUE_58; ?> <br>
								<div class="sharethis-inline-share-buttons" id="st-1"></div>
							</div>
						
                       
					</div>

					<div class="row">
						<div class="col-md-12">
							<!-- Nav tabs -->
							<ul class="nav nav-tabs" role="tablist">
								<li role="presentation" class="active"><a href="#description" aria-controls="description" role="tab" data-toggle="tab"><?php echo LANG_VALUE_59; ?></a></li>
								<li role="presentation"><a href="#feature" aria-controls="feature" role="tab" data-toggle="tab"><?php echo LANG_VALUE_60; ?></a></li>
                                <li role="presentation"><a href="#condition" aria-controls="condition" role="tab" data-toggle="tab"><?php echo LANG_VALUE_61; ?></a></li>
                                <li role="presentation"><a href="#return_policy" aria-controls="return_policy" role="tab" data-toggle="tab"><?php echo LANG_VALUE_62; ?></a></li>
                                <li role="presentation"><a href="#review" aria-controls="review" role="tab" data-toggle="tab"><?php echo LANG_VALUE_63; ?></a></li> 
							</ul>

							<!-- Tab panes -->
							<div class="tab-content">
								<div role="tabpanel" class="tab-pane active" id="description" style="margin-top: -30px;">
									<p>
                                        <?php
                                        if($p_description == '') {
                                            echo LANG_VALUE_70;
                                        } else {
                                            echo $p_description;
                                        }
                                        ?>
									</p>
								</div>
                                <div role="tabpanel" class="tab-pane" id="feature" style="margin-top: -30px;">
                                    <p>
                                        <?php
                                        if($p_feature == '') {
                                            echo LANG_VALUE_71;
                                        } else {
                                            echo $p_feature;
                                        }
                                        ?>
                                    </p>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="condition" style="margin-top: -30px;">
                                    <p>
                                        <?php
                                        if($p_condition == '') {
                                            echo LANG_VALUE_72;
                                        } else {
                                            echo $p_condition;
                                        }
                                        ?>
                                    </p>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="return_policy" style="margin-top: -30px;">
                                    <p>
                                        <?php
                                        if($p_return_policy == '') {
                                            echo LANG_VALUE_73;
                                        } else {
                                            echo $p_return_policy;
                                        }
                                        ?>
                                    </p>
                                </div>
								<div role="tabpanel" class="tab-pane" id="review" style="margin-top: -30px;">

                                    <div class="review-form">
                                        <?php
                                        $statement = $pdo->prepare("SELECT * 
                                                            FROM tbl_rating t1 
                                                            JOIN tbl_customer t2 
                                                            ON t1.cust_id = t2.cust_id 
                                                            WHERE t1.p_id=?");
                                        $statement->execute(array($_REQUEST['id']));
                                        $total = $statement->rowCount();
                                        ?>
                                        <h2><?php echo LANG_VALUE_63; ?> (<?php echo $total; ?>)</h2>
                                        <?php
                                        if($total) {
                                            $j=0;
                                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($result as $row) {
                                                $j++;
                                                ?>
                                                <div class="mb_10"><b><u><?php echo LANG_VALUE_64; ?> <?php echo $j; ?></u></b></div>
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <th style="width:170px;"><?php echo LANG_VALUE_75; ?></th>
                                                        <td><?php echo $row['cust_name']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th><?php echo LANG_VALUE_76; ?></th>
                                                        <td><?php echo $row['comment']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th><?php echo LANG_VALUE_78; ?></th>
                                                        <td>
                                                            <div class="rating">
                                                                <?php
                                                                for($i=1;$i<=5;$i++) {
                                                                    ?>
                                                                    <?php if($i>$row['rating']): ?>
                                                                        <i class="fa fa-star-o"></i>
                                                                    <?php else: ?>
                                                                        <i class="fa fa-star"></i>
                                                                    <?php endif; ?>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <?php
                                            }
                                        } else {
                                            echo LANG_VALUE_74;
                                        }
                                        ?>
                                        
                                        <h2><?php echo LANG_VALUE_65; ?></h2>
                                        <?php
                                        if($error_message != '') {
                                            echo "<script>alert('".$error_message."')</script>";
                                        }
                                        if($success_message != '') {
                                            echo "<script>alert('".$success_message."')</script>";
                                        }
                                        ?>
                                        <?php 
                                              $statement = $pdo->prepare("SELECT *
                                            
                                              FROM tbl_payment t1 
                                              JOIN tbl_order t2 
                                              ON t1.payment_id = t2.payment_id
                                              WHERE t2.product_id=?"
                                            
                                            );

                                            $statement->execute(array($_REQUEST['id']));
                                            $total2 = $statement->rowCount(); 
                                                                                                                        
                                        if(isset($_SESSION['customer'])): ?>
                                            <?php if($total2 != 0): 
                                            $statement = $pdo->prepare("SELECT * 
                                            FROM tbl_payment t1 
                                            JOIN tbl_order t2
                                            JOIN tbl_customer t3 
                                            ON t1.customer_id = t3.cust_id                                            
                                            AND t1.payment_id = t2.payment_id
                                            WHERE t2.product_id=?"
                                            );
                                            $statement->execute(array($_REQUEST['id']));
                                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                            $j=0;
                                            foreach ($result as $row) {
                                                $shipped = $row['shipping_status'];
                                                if($shipped == 'Completed'){ $j++; }
                                            }
                                            if($j >= 1): ?>
                                            <?php
                                            $statement = $pdo->prepare("SELECT * 
                                                                FROM tbl_rating
                                                                WHERE p_id=? AND cust_id=?");
                                            $statement->execute(array($_REQUEST['id'],$_SESSION['customer']['cust_id']));
                                            $total = $statement->rowCount();
                                            ?>
                                            <?php if($total==0): ?>
                                            <form action="" method="post">
                                            <div class="rating-section">
                                                <input type="radio" name="rating" class="rating" value="1" checked>
                                                <input type="radio" name="rating" class="rating" value="2" checked>
                                                <input type="radio" name="rating" class="rating" value="3" checked>
                                                <input type="radio" name="rating" class="rating" value="4" checked>
                                                <input type="radio" name="rating" class="rating" value="5" checked>
                                            </div>                                            
                                            <div class="form-group">
                                                <textarea name="comment" class="form-control" cols="30" rows="10" placeholder="Write your comment (optional)" style="height:100px;"></textarea>
                                            </div>
                                            <input type="submit" class="btn btn-default" name="form_review" value="<?php echo LANG_VALUE_67; ?>">
                                            </form>
                                         
                                            <?php else: ?>
                                                <span style="color:red;"><?php echo LANG_VALUE_68; ?></span>
                                            <?php endif; ?>

                                            <?php else: ?>
                                                <span style="color:red;">You Need to get it first</span>
                                            <?php endif; ?>

                                            <?php else: ?>
                                                <span style="color:red;">You Need to buy it first</span>
                                            <?php endif; ?>

                                        <?php else: ?>
                                            <p class="error">
												<?php echo LANG_VALUE_69; ?> <br>
												<a href="login.php" style="color:red;text-decoration: underline;"><?php echo LANG_VALUE_9; ?></a>
											</p>
                                        <?php endif; ?>                         
                                    </div>

								</div>
							</div>
						</div>
					</div>

				</div>

			</div>
		</div>
	</div>
</div>

<div class="product bg-gray pt_70 pb_70">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="headline">
                    <h2><?php echo LANG_VALUE_155; ?></h2>
                    <h3><?php echo LANG_VALUE_156; ?></h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">

                <div class="product-carousel">

                    <?php
                    $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE ecat_id=? AND p_id!=?");
                    $statement->execute(array($ecat_id,$_REQUEST['id']));
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($result as $row) {
                        ?>
                        <div class="item">
                            <div class="thumb">
                                <div class="photo" style="background-image:url(assets/uploads/<?php echo $row['p_featured_photo']; ?>);"></div>
                                <div class="overlay"></div>
                            </div>
                            <div class="text">
                                <h3><a href="product.php?id=<?php echo $row['p_id']; ?>"><?php echo $row['p_name']; ?></a></h3>
                                <h4>
                                    <?php echo LANG_VALUE_1; ?><?php echo $row['p_current_price']; ?> 
                                    <?php if($row['p_old_price'] != ''): ?>
                                    <del>
                                        <?php echo LANG_VALUE_1; ?><?php echo $row['p_old_price']; ?>
                                    </del>
                                    <?php endif; ?>
                                </h4>
                                <div class="rating">
                                    <?php
                                    $t_rating = 0;
                                    $statement1 = $pdo->prepare("SELECT * FROM tbl_rating WHERE p_id=?");
                                    $statement1->execute(array($row['p_id']));
                                    $tot_rating = $statement1->rowCount();
                                    if($tot_rating == 0) {
                                        $avg_rating = 0;
                                    } else {
                                        $result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($result1 as $row1) {
                                            $t_rating = $t_rating + $row1['rating'];
                                        }
                                        $avg_rating = $t_rating / $tot_rating;
                                    }
                                    ?>
                                    <?php
                                    if($avg_rating == 0) {
                                        echo '';
                                    }
                                    elseif($avg_rating == 1.5) {
                                        echo '
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        ';
                                    } 
                                    elseif($avg_rating == 2.5) {
                                        echo '
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        ';
                                    }
                                    elseif($avg_rating == 3.5) {
                                        echo '
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        ';
                                    }
                                    elseif($avg_rating == 4.5) {
                                        echo '
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                        ';
                                    }
                                    else {
                                        for($i=1;$i<=5;$i++) {
                                            ?>
                                            <?php if($i>$avg_rating): ?>
                                                <i class="fa fa-star-o"></i>
                                            <?php else: ?>
                                                <i class="fa fa-star"></i>
                                            <?php endif; ?>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                                <p><a href="product.php?id=<?php echo $row['p_id']; ?>"><?php echo LANG_VALUE_154; ?></a></p>
                            </div>
                        </div>
                        <?php
                    }
                    ?>

                </div>

            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>
