<?php require_once('header.php'); ?>

<?php
// Check if the customer is logged in or not
if(!isset($_SESSION['customer'])) {
    header('location: '.BASE_URL.'logout.php');
    exit;
} else {
    // If customer is logged in, but admin make him inactive, then force logout this user.
    $statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_id=? AND cust_status=?");
    $statement->execute(array($_SESSION['customer']['cust_id'],0));
    $total = $statement->rowCount();
    if($total) {
        header('location: '.BASE_URL.'logout.php');
        exit;
    }
}
?>

<div class="page">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php require_once('customer-sidebar.php'); ?>
            </div>
            <div class="col-md-12">
                
        


                <div class="user-content">
                    <h3><?php echo LANG_VALUE_25; ?></h3>

                    
                    


                    <div class="">

                        <table class="tbr-table">
                        
                            

            <?php
            /* ===================== Pagination Code Starts ================== */
            $adjacents = 5;

            $statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE customer_email=? ORDER BY id DESC");
            $statement->execute(array($_SESSION['customer']['cust_email']));
            $total_pages = $statement->rowCount();

            $targetpage = BASE_URL.'customer-order.php';
            $limit = 10;
            $page = @$_GET['page'];
            if($page) 
                $start = ($page - 1) * $limit;
            else
                $start = 0;
            
            
            $statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE customer_email=? ORDER BY id DESC LIMIT $start, $limit");
            $statement->execute(array($_SESSION['customer']['cust_email']));
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
           
            
            if ($page == 0) $page = 1;
            $prev = $page - 1;
            $next = $page + 1;
            $lastpage = ceil($total_pages/$limit);
            $lpm1 = $lastpage - 1;   
            $pagination = "";
            if($lastpage > 1)
            {   
                $pagination .= "<div class=\"pagination\">";
                if ($page > 1) 
                    $pagination.= "<a href=\"$targetpage?page=$prev\">&#171; previous</a>";
                else
                    $pagination.= "<span class=\"disabled\">&#171; previous</span>";    
                if ($lastpage < 7 + ($adjacents * 2))
                {   
                    for ($counter = 1; $counter <= $lastpage; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= "<span class=\"current\">$counter</span>";
                        else
                            $pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";                 
                    }
                }
                elseif($lastpage > 5 + ($adjacents * 2))
                {
                    if($page < 1 + ($adjacents * 2))        
                    {
                        for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
                        {
                            if ($counter == $page)
                                $pagination.= "<span class=\"current\">$counter</span>";
                            else
                                $pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";                 
                        }
                        $pagination.= "...";
                        $pagination.= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
                        $pagination.= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";       
                    }
                    elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                    {
                        $pagination.= "<a href=\"$targetpage?page=1\">1</a>";
                        $pagination.= "<a href=\"$targetpage?page=2\">2</a>";
                        $pagination.= "...";
                        for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                        {
                            if ($counter == $page)
                                $pagination.= "<span class=\"current\">$counter</span>";
                            else
                                $pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";                 
                        }
                        $pagination.= "...";
                        $pagination.= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
                        $pagination.= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";       
                    }
                    else
                    {
                        $pagination.= "<a href=\"$targetpage?page=1\">1</a>";
                        $pagination.= "<a href=\"$targetpage?page=2\">2</a>";
                        $pagination.= "...";
                        for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
                        {
                            if ($counter == $page)
                                $pagination.= "<span class=\"current\">$counter</span>";
                            else
                                $pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";                 
                        }
                    }
                }
                if ($page < $counter - 1) 
                    $pagination.= "<a href=\"$targetpage?page=$next\">next &#187;</a>";
                else
                    $pagination.= "<span class=\"disabled\">next &#187;</span>";
                $pagination.= "</div>\n";       
            } 
            /* ===================== Pagination Code Ends ================== */
            ?>


                                <?php
                                $i=0;
                                $tip = $page*10-10;
                                foreach ($result as $row) {
                                    $i++;
                                    $tip++;

                                    ?>
       
<div class="body-content outer-top-bd">
	<div class="container">
		<div class="checkout-box inner-bottom-sm">
			<div class="row99">
				<div class="">
					<div class="panel-group checkout-steps" id="accordion">
<div class="panel panel-default checkout-step-01">

	<!-- panel-heading -->
	<div class="panel-heading">
    	<h4 class="unicase-checkout-title">
	        <a data-toggle="collapse" class=" <?php if($i==1){ ?>  <?php } else { ?>collapsed<?php } ?> " data-parent="#accordion" href="#collapse<?php echo $tip; ?>">
			<span># <?php echo $tip; ?></span>Purchase Reciept
	        </a>
	     </h4>
    </div>
    <!-- panel-heading -->

	<div id="collapse<?php echo $tip; ?>" class="panel-collapse collapse <?php if($i==1){ ?> in <?php } else { ?> <?php } ?> " ">

		<!-- panel-body  -->
	    <div class="panel-body">
			<div class="row88">		
				

				<div class="col-7">
                        <span id="heading">Date</span><br>
                        <span id="details"><?php echo $row['payment_date']; ?></span>
                    </div>
                    <div class="col-5 pull-right">
                        <span id="heading">Order No.</span><br>
                        <span id="details"><?php echo $row['payment_id']; ?></span>
                    </div>
                     
            </div>      
            <div class="pricing">
                <div class="row">
                    <div class="col-9">
                     
                        <span id="name">
                            
                            
                            <?php

                         $statement1 = $pdo->prepare("SELECT * FROM tbl_order WHERE payment_id=?");
                         $statement1->execute(array($row['payment_id']));
                        $result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($result1 as $row1) { 

                            ?>
                            <div class="tbr-imma" style="display: flex !important;align-items: center;justify-content: flex-start;">
                          
                            <img src="assets/uploads/<?php echo $row1['p_featured_photo']; ?> " style="width: 10vw; margin-right: 5vw;">
                            
                            
                            <div class="imma2">
                            <?php
                                                echo 'Product Name: '.$row1['product_name'];
                                                echo '<br>Size: '.$row1['size'];
                                                echo '<br>Color: '.$row1['color'];
                                                echo '<br>Quantity: '.$row1['quantity'];
                                                echo '<br>Unit Price: '.$row1['unit_price'],' DA';

                             ?>
                             </div>
                             </div>
                             <?php
                          
                                            }
                                            ?></span>  
                    </div>
                   
                </div>
                
            </div>
            <div class="total">
                <div class="row">
                    <div class="col-9"></div>
                   
                                                
                    <div class="col-3"><big><?php echo $row['paid_amount']; ?> DA</big></div>
                </div>
            </div>
            <div class="tracking">
                <div class="title">Tracking Order</div>
            </div>
            <div class="progress-track">
                <ul id="progressbar">
                <li class="step0 active " id="step1">Ordered</li>
                <?php
                                

                                if($row['payment_status']=='Pending'){
                                    ?>
                                    <li class="step0 text-center" id="step2">Waiting Transit</li>
                                    <?php
                                } else{
                                    ?>
                                    <li class="step0 active text-center" id="step2">In Transit</li>
                                    <?php
                                }

                                if($row['shipping_status']=='Pending'){
                                    ?>
                                     <li class="step0 text-right" id="step3">Waiting Delivery</li>
                                    <?php
                                } else{
                                    ?>
                                     <li class="step0 active text-right" id="step3">Dilivred</li>
                                    <?php
                                }


                            ?>
                    
                    
                   
                </ul>
            </div>
            

            <div class="footer">
                <div class="row">
                    <div class="col-2"><img class="img-fluid" src="https://i.imgur.com/YBWc55P.png"></div>
                    <div class="col-10">Want any help? Please &nbsp;<a> contact us</a></div>
                </div>
	
		<!-- panel-body  -->

	</div><!-- row -->
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
                                    <?php
                                    
                              } 

                          
                                ?>
                                  </table> 
                        <div class="pagination" style="overflow: hidden;">
                        <?php 
                            echo $pagination; 
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>