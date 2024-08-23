  <?php
    require_once('../database/db.php');
    $db=new DB();
    $conn=$db->connect();
    session_start();
    $output=[];
    date_default_timezone_set('Asia/Colombo');
    $currentDate=date('Y-m-d');
    // $currentDate=date('Y-m-d H:i:s');


    $output=[]; 
    // $datalist=array();
    $productNameList = array();
    $productQtyList = array();

    if(isset($_POST['selling_summery_outlet_id']))
    {
        $selling_summery_outlet_id = htmlspecialchars($_POST['selling_summery_outlet_id']);

        $query="SELECT itemId, SUM(toid.qty) FROM tbl_order_item_details toid INNER JOIN tbl_order tor ON toid.order_id=tor.id WHERE tor.outlet_id='$selling_summery_outlet_id' GROUP BY toid.itemId ORDER BY SUM(toid.qty) DESC";
        $GetAvailableQuantityProductssql=$conn->query($query);
        while ($GAPCrow=$GetAvailableQuantityProductssql->fetch_array()) {

          $ProductId=$GAPCrow[0];
          $AllProductSaleQty=$GAPCrow[1];
        

            $getProductDetails = $conn->query("SELECT * FROM tbl_item WHERE itemId='$ProductId'");
            if($GPD = $getProductDetails->fetch_array()){

                $ItemName=$GPD[2];
                $ItemCode=$GPD[1];


              
                    array_push($productNameList,$ItemName.' ('.$ItemCode.')');
                    array_push($productQtyList,$AllProductSaleQty);
          

            }

               


        }

          // array_push($datalist);



      
    }


    $output['result']=true;
    // $output['data']=$datalist;
    

    $output['productName'] = $productNameList;
    $output['productQtySum'] = $productQtyList;









   


    echo json_encode($output);
    
    
    