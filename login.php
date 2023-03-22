<?php require_once('header.php'); ?>
<!-- fetching row banner login -->
<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $banner_login = $row['banner_login'];
}
?>
<!-- login form -->
<?php
if(isset($_POST['form1'])) {
        
    if(empty($_POST['cust_email']) || empty($_POST['cust_password'])) {
        $error_message = LANG_VALUE_132.'<br>';
    } else {
        
        $cust_email = strip_tags($_POST['cust_email']);
        $cust_password = strip_tags($_POST['cust_password']);

        $statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_email=?");
        $statement->execute(array($cust_email));
        $total = $statement->rowCount();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach($result as $row) {
            $cust_status = $row['cust_status'];
            $row_password = $row['cust_password'];
        }

        if($total==0) {
            $error_message .= LANG_VALUE_133.'<br>';
        } else {
            //using MD5 form
            if( $row_password != md5($cust_password) ) {
                $error_message .= LANG_VALUE_139.'<br>';
            } else {
                if($cust_status == 0) {
                    $error_message .= LANG_VALUE_148.'<br>';
                } elseif($_GET['ShowDiv'] ??null == true ){
                    $_SESSION['customer'] = $row;
                    header("location: ".BASE_URL."cart.php");
                } else {
                    $_SESSION['customer'] = $row;
                    header("location: ".BASE_URL."customer-order.php");
                }
            }
            
        }
    }
}
?>

<!-- <div class="page-banner" style="background-color:#444;background-image: url(assets/uploads/<?php echo $banner_login; ?>);">
    <div class="inner">
        <h1><?php echo LANG_VALUE_10; ?></h1>
    </div>
</div> -->

<div class="tbr-space"></div>


<?php  if($_GET['ShowDiv'] ??null == true ){?>


    <div class="tbr-txt-flex" >
    <div class="alert alert-warning" role="alert" style="text-align: center; width:50%"> Please login as customer to checkout </div></div>


<?php }  ?>

 <h2 class="tbr-txt-two"> .أهلا بعودتك</h2>  
 <?php ?>

<h4 class="tbr-txt-three">جديد على فايلا؟
<a class="tbr-link" href="registration.php"> سجل الآن</a>
</h4>


<div class="page">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="user-content">

                    
                    <form action="" method="post">
                        <?php $csrf->echoInputField(); ?>                  
                        <div class="tbr-forms">
                           
                            <div class="col-md-6">
                                <?php
                                if($error_message != '') {
                                    echo "<div class='error' style='padding: 10px;background:#f1f1f1;margin-bottom:20px;'>".$error_message."</div>";
                                }
                                if($success_message != '') {
                                    echo "<div class='success' style='padding: 10px;background:#f1f1f1;margin-bottom:20px;'>".$success_message."</div>";
                                }
                                ?>
                                <div class="form-group">
                                    <label for=""><?php echo LANG_VALUE_94; ?></label>
                                    <input type="email" class="form-control" name="cust_email">
                                </div>
                                <div class="form-group">
                                    <label for=""><?php echo LANG_VALUE_96; ?></label>
                                    <input type="password" class="form-control" name="cust_password">
                                </div>
                                <div class="form-group">
                                    <label for=""></label>
                                    <input type="submit" class="btn tbr-btn-success" value="دخول" name="form1">
                                </div>
                                <a class="tbr-link" href="forget-password.php"> مشكلة في تسجيل الدخول؟ </a>

                            </div>
                        </div>                        
                    </form>
                </div>                
            </div>
        </div>
    </div>
</div>
