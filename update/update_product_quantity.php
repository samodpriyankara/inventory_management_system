<?php
    require_once('../database/db.php');
    $db=new DB();
    $conn=$db->connect();
    session_start();
    date_default_timezone_set('Asia/Colombo');
    $currentDate=date('Y-m-d H:i:s');


    $output=[];
    $dataArray = array();

    if($_POST)
    {
        $item_id = htmlspecialchars($_POST['item_id']);
        $quantity = htmlspecialchars($_POST['quantity']);


        //Get This Item Details
        $getItemDetails=$conn->query("SELECT re_price FROM tbl_item WHERE itemId='$item_id'");
        if($gidRs = $getItemDetails->fetch_array()){
            $CostPrice=$gidRs[0];
        }
        //
                $ProductQuantitysql = "UPDATE tbl_item SET stock=stock+'$quantity' WHERE itemId='$item_id' ";

                if ($conn->query($ProductQuantitysql) === TRUE) {
                //   echo "Record updated successfully";


                            $BuyingHistorysql = "INSERT INTO `tbl_item_buying_history`(`item_buying_history_id`, `item_id`, `quantity`, `cost`, `item_buying_history_datetime`) VALUES (null, '$item_id', '$quantity', '$CostPrice', '$currentDate')";
                            if ($conn->query($BuyingHistorysql) === TRUE) {
                                  
                                //get all Items 
                            $getProducts=$conn->query("SELECT itemCode,itemDescription,brand_name,price,stock FROM tbl_item ORDER BY itemCode ASC");
                            while($gpRs = $getProducts->fetch_array()){

                                $ItemCode=$gpRs[0];
                                $ItemDescription=$gpRs[1];
                                $BrandName=$gpRs[2];
                                $ItemPrice=number_format($gpRs[3],2);
                                $ItemStock=$gpRs[4];
                                
                                
                                $row='

                                        <tr>
                                            <td>'.$ItemCode.'</td>
                                            <td>'.$ItemDescription.'</td>
                                            <td>'.$BrandName.'</td>
                                            <td><font class="pull-right" style="font-weight: 600;">'.$ItemPrice.'</font></td>
                                            <td><font class="pull-right">'.$ItemStock.'</font></td>
                                        </tr>

                                    ';
                                    
                                    array_push($dataArray,$row);
                                
                                
                            }
                            
                            /////////////////
                            
                            
                            $output['result'] = true;
                            $output['msg'] = 'Successfully added quantity.';
                            $output['data'] = $dataArray;
                        
                        
                        
                        // echo 'Completed';
                        }else{  
                            // echo 'Error';   
                            
                            $output['result'] = false;
                            $output['msg'] = 'Error added please reload the page'; 
                        }



                } else {
                    $output['result'] = false;
                    $output['msg'] = 'Error added please reload the page'; 
                }



                        


        }

    $conn->close();
    echo json_encode($output);


    ?>