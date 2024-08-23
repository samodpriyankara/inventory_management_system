<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>


<?php

	require_once('../database/db.php');
    $db=new DB();
    $conn=$db->connect();
    session_start();	

if(isset($_POST['username']))
{
	$username = 'username';
	$username = $_POST['username'];

	if ($username) {
    	

    	$sql = "SELECT `username` FROM `tbl_web_console_user_account` WHERE `username` = ?";

		$stmt = mysqli_prepare($conn, $sql);
		mysqli_stmt_bind_param($stmt,"s", $username);
		$result = mysqli_stmt_execute($stmt);
		mysqli_stmt_store_result($stmt);
		mysqli_stmt_bind_result($stmt, $username1);
		mysqli_stmt_fetch($stmt);

		if(mysqli_stmt_num_rows($stmt) <1)
		{
			echo '<span style="color:green;text-align:center;">User Name is valid</span>';
			?>
				<script>
					document.getElementById("btn-reg-web-console-user").disabled = false;
    			</script>

			<?php

		}

		else
		{
			echo '<span style="color:red;text-align:center;">User Name already taken</span>';
			?>
				<script>
					document.getElementById('btn-reg-web-console-user').disabled = true;
				</script>
			<?php
		}
	}

	else 
	{
    	echo("Not a valid User Name");

    	
		?>
			<script>
				document.getElementById('btn-reg-web-console-user').disabled = true;
			</script>
		<?php

	}
}

?>