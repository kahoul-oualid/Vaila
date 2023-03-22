<?php require_once('header.php'); ?>

<div id="spinner">
    <div class="lds-ripple"><div></div><div></div>
    </div>
</div>

<div class="wrap2 cf">
 <div class="full_up">
  <div class="cart_one" >
  <?php if(!isset($_SESSION['cart_p_id'])): ?>
            <style>
                @media only screen and (min-width: 972px) {
                 .full_up {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    }

}
            </style>
            <?php echo '<h2 class="text-center">Cart is Empty!!</h2></br>'; ?>
            <?php echo '<h4 class="text-center">Add products to the cart in order to view it here.</h4>'; ?>
            <?php echo '<img class="text-center" src="assets/img/empty_cart.png"> </br>'; ?>
        <?php else: ?>
    <h1 class="projTitle">Responsive Table<span>-Less</span> Shopping Cart</h1>

        <div class="heading cf">
            <h1>مشترياتي</h1>
            <a href="index.php" class="continue">تابع التسوق</a>
        </div>
    <div class="cart">
        <ul class="cartWrap">
        
        <form class="form_up" action="" method="post"> 
                    <?php $csrf->echoInputField(); ?>
                    <?php
                        $table_total_price = 0;

                        $i=0;
                        foreach($_SESSION['cart_p_id'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_p_id[$i] = $value;
                        }

                        $i=0;
                        foreach($_SESSION['cart_size_id'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_size_id[$i] = $value;
                        }

                        $i=0;
                        foreach($_SESSION['cart_size_name'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_size_name[$i] = $value;
                        }

                        $i=0;
                        foreach($_SESSION['cart_color_id'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_color_id[$i] = $value;
                        }

                        $i=0;
                        foreach($_SESSION['cart_color_name'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_color_name[$i] = $value;
                        }

                        $i=0;
                        foreach($_SESSION['cart_p_qty'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_p_qty[$i] = $value;
                        }

                        $i=0;
                        foreach($_SESSION['cart_p_current_price'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_p_current_price[$i] = $value;
                        }

                        $i=0;
                        foreach($_SESSION['cart_p_name'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_p_name[$i] = $value;
                        }

                        $i=0;
                        foreach($_SESSION['cart_p_featured_photo'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_p_featured_photo[$i] = $value;
                        }
                        ?>

            <?php for($i=1;$i<=count($arr_cart_p_id);$i++): ?>                                

            <li class="items odd">
                
            <div class="infoWrap"> 
            <div class="cartSection">
                <img src="assets/uploads/<?php echo $arr_cart_p_featured_photo[$i]; ?>" alt="" class="itemImg">
                <p class="itemNumber"># <?php echo $i; ?> </p>
                <h3><?php echo $arr_cart_p_name[$i]; ?>
                <span>
                <h5 class="itemNumber"><?php echo $arr_cart_size_name[$i]; ?> </h5>
                <h5 class="itemNumber"><?php echo $arr_cart_color_name[$i]; ?> </h5>
                </span>
                </h3>
            
                <input type="hidden" name="product_id[]" value="<?php echo $arr_cart_p_id[$i]; ?>">
                <input type="hidden" name="product_name[]" value="<?php echo $arr_cart_p_name[$i]; ?>">
                
                <div class="quantity buttons_added">
                <input type="button" value="-" class="minus"><input type="button" value="+" class="plus"><input type="number" step="1" min="1" max="" name="quantity[]" value="<?php echo $arr_cart_p_qty[$i]; ?>" title="Qty" class="input-text qty text" size="4" pattern="" inputmode="">
                <script data-require="jquery@3.1.1" data-semver="3.1.1" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
                
                <p> x <?php echo $arr_cart_p_current_price[$i]; ?> DA</p>
                <p class="stockStatus"> In Stock</p>
                </div>
            </div>  

                <?php
                $row_total_price = $arr_cart_p_current_price[$i]*$arr_cart_p_qty[$i];
                $table_total_price = $table_total_price + $row_total_price;
                ?>                                
                
                <div class="prodTotal cartSection">
                <p><?php echo $row_total_price; ?> DA</p>
                </div>

                <div class="cartSection removeWrap">
                <button  class="remove tbr-trash" value="id=<?php echo $arr_cart_p_id[$i]; ?>&size=<?php echo $arr_cart_size_id[$i]; ?>&color=<?php echo $arr_cart_color_id[$i]; ?>">x</button>
                </div>

            </div>
            </li>

            <?php endfor; ?>

        <div class="special"><div class="specialContent">Free gift with purchase!, gift wrap, etc!!</div></div>
        
        </form>

        </ul>
    </div>
    
    <div class="promoCode"><label for="promo">Have A Promo Code?</label><input type="text" name="promo" placholder="Enter Code" />
        <a href="#" class="btn"></a>
    </div>
  </div>

  <div class="tbr-space">
  </div>

    <div class="subtotal cf">
        <?php if(isset($_SESSION['customer'])): ?>
        <?php
        $statement = $pdo->prepare("SELECT * FROM tbl_shipping_cost WHERE country_id=?");
        $statement->execute(array($_SESSION['customer']['cust_country']));
        $total = $statement->rowCount();
        if($total) {
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $shipping_cost = $row['amount'];
            }
        } else {
            $statement = $pdo->prepare("SELECT * FROM tbl_shipping_cost_all WHERE sca_id=1");
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $shipping_cost = $row['amount'];
            }
        }

        $statement = $pdo->prepare("SELECT * FROM tbl_country WHERE country_id=?");
        $statement->execute(array($_SESSION['customer']['cust_country']));
        $total = $statement->rowCount();
        if($total) {
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $country_name = $row['country_name'];
            }
        }                        
        ?>
        <?php $final_total = $table_total_price+$shipping_cost; ?>

        <ul class="totalRow_up">
        <li class="totalRow"><span class="value"><?php echo $table_total_price; ?> DA</span><span class="label">المجموع</span></li>
        <li class="totalRow"><span class="value"><?php echo $shipping_cost; ?> DA</span><span class="label"> <?php echo $country_name; ?> :التوصيل لمدينة</span></li>
        <li class="totalRow final"><span class="value"><?php echo $final_total; ?> DA</span><span class="label">الإجمالي</span></li>

        <li class="totalRow buttn">

        <form action="payment/bank/init.php" method="post" id="bank_form">
        <input type="hidden" name="amount" value="<?php echo $final_total; ?>">
        <button type="submit" class="btn continue" name="form3">إدفع </button>
        </form>

        </li>
        </ul>

        <div class="tbr-space">
        </div>

        <ul class="totalRow_up">
        <li class="totalRow"><span class="value"><?php echo $_SESSION['customer']['cust_name']; ?></span><span class="label"><?php echo LANG_VALUE_102; ?></span></li>
        <li class="totalRow"><span class="value"><?php echo $_SESSION['customer']['cust_phone']; ?></span><span class="label"><?php echo LANG_VALUE_104; ?></span></li>
        <li class="totalRow"><span class="value"><?php echo $country_name; ?></span><span class="label"><?php echo LANG_VALUE_106; ?></span></li>
        <li class="totalRow"><span class="value"><?php echo nl2br($_SESSION['customer']['cust_address']); ?></span><span class="label"><?php echo LANG_VALUE_105; ?></span></li>
        <li class="totalRow buttn"><a href="customer-profile-update.php"  class="btn continue">تعديل الملف الشخصي</a></li>
        </ul>

        <?php else: ?>
        <ul class="totalRow_up">
        <li class="totalRow"><span class="value"><?php echo $table_total_price; ?> DA</span><span class="label">المجموع</span></li>
        <li class="totalRow"><span class="value">0.00 DA</span><span class="label">التوصيل</span></li>
        <li class="totalRow final"><span class="value"><?php echo $table_total_price; ?> DA</span><span class="label">الإجمالي</span></li>
        <li class="totalRow buttn"><a href="login.php?ShowDiv=true"  class="btn continue">سجل</a></li>
        </ul>
   
        <?php endif;?>
        
    </div>
    
    <?php endif; ?>
 </div>
</div>


<?php require_once('footer.php'); ?>
