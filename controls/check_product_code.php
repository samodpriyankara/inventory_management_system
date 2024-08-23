<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>


<?php

	require_once('../database/db.php');
    $db=new DB();
    $conn=$db->connect();
    session_start();	

if(isset($_POST['product_code']))
{
	$product_code = 'product_code';
	$product_code = $_POST['product_code'];

	if ($product_code) {
    	

    	$sql = "SELECT `itemCode` FROM `tbl_item_details` WHERE `itemCode` = ?";

		$stmt = mysqli_prepare($conn, $sql);
		mysqli_stmt_bind_param($stmt,"s", $product_code);
		$result = mysqli_stmt_execute($stmt);
		mysqli_stmt_store_result($stmt);
		mysqli_stmt_bind_result($stmt, $product_code1);
		mysqli_stmt_fetch($stmt);

		if(mysqli_stmt_num_rows($stmt) <1)
		{
			echo '<span style="color:green;text-align:center;">Product Code is valid</span>';
			?>
				<script>
					document.getElementById("btn-submit").disabled = false;
    			</script>

			<?php

		}

		else
		{
			echo '<span style="color:red;text-align:center;">Product Code already taken</span>';
			?>
				<script>
					document.getElementById('btn-submit').disabled = true;
				</script>
			<?php
		}
	}

	else 
	{
    	echo("Not a valid Product Code");

    	
		?>
			<script>
				document.getElementById('btn-submit').disabled = true;
			</script>
		<?php

	}
}

?>