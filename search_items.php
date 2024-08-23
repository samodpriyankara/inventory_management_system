<?php
require 'database/db.php';
$db = new DB();
$conn = $db->connect();

if(isset($_POST['search'])) {
    $search = $_POST['search'];
    
    // SQL query to search items in tbl_item table
    $itemSql = "SELECT * FROM tbl_item WHERE itemName LIKE '%$search%'";
    $itemResult = $conn->query($itemSql);
    
    $grnIds = [];
    while($item = $itemResult->fetch_assoc()) {
        $itemId = $item['itemId'];
        // SQL query to find related GRNs for the searched item
        $grnSql = "SELECT * FROM tbl_grn_details WHERE itemId = $itemId";
        $grnResult = $conn->query($grnSql);
        
        while($grn = $grnResult->fetch_assoc()) {
            // Collect GRN IDs
            $grnIds[] = $grn['GRNDetailId'];
        }
    }
    
    // Remove duplicate GRN IDs
    $grnIds = array_unique($grnIds);
    
    // SQL query to retrieve GRN details based on GRN IDs
    if(!empty($grnIds)) {
        $grnIdsString = implode(',', $grnIds);
        $GRNsql = "SELECT * FROM tbl_grn_details tgd INNER JOIN tbl_supplier tsu ON tgd.supplier_id = tsu.supplier_id WHERE tgd.GRNDetailId IN ($grnIdsString) AND tgd.stat = '1'";
        $rs = $conn->query($GRNsql);
        while($GRNrs = $rs->fetch_array()) {
            // Output GRN details as table rows
            echo "<tr class='gradeA' onclick=\"location.href='view_grn_list?g=".base64_encode($GRNrs['GRNDetailId'])."'\" style='cursor: pointer;'>
                <td>{$GRNrs['GRNDetailId']}</td>
                <td>{$GRNrs['GRNNumber']}</td>
                <td>{$GRNrs['InvoiceNumber']}</td>
                <td>{$GRNrs['SupplierName']}</td>
                <td>{$GRNrs['GoodsReceivedDate']}</td>
            </tr>";
        }
    } else {
        // If no GRNs found for the searched item, display a message
        echo "<tr><td colspan='5'>No GRNs found for the searched item.</td></tr>";
    }
}
?>
