<?php
	session_start();

	//connecting to database
	$dbhost='localhost';
	$dbuser='root';
	$dbpass='';
	$dbname='arsh';
	$connect=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);
	if(mysqli_connect_errno())
	{
		die('database connection failed');
	}
	unset($login, $message);

	if(isset($_POST['login']))
	{

		
		unset($sys_usr,$sys_pass,$info_usr,$info_pass,$error_message);
		
		$sys_usr=$_POST['sys_pass'];
		$sys_pass=$_POST['sys_pass'];
		$info_pass=$_POST['info_pass'];
		$info_usr=$_POST['info_usr'];

		if(isset($sys_pass,$sys_usr,$info_usr,$info_pass))
		{
			//checking the login data from the database
			$sql='SELECT * FROM data WHERE sys_username ="'.$sys_usr.'" AND sys_password = "'.$sys_pass.'" AND info_username = "'.$info_usr.'" AND info_password = "'.$info_pass.'"';
			$row=mysqli_query($connect,$sql);
			$numrows=mysqli_num_rows($row);
			echo $numrows . "\n";
			if($numrows==1)
			{
				//loging status
				echo "1";
				$login=mysqli_query($connect, "SELECT status FROM data WHERE sys_pass='$sys_usr'");//all will point to same thing
				if($login==1)
				{
					echo "2";
					$message="Already logged in from another device
							\nCant login from more than 1 device";
					$_SESSION['message'] = $message;
					header("Location: login.php");
				}
				else
				{
					echo "3";
					//login status = 1 in database
					$insert='UPDATE data SET status=1 WHERE sys_username= "'.$sys_usr.'"';
					if($connect->query($insert))
						header("Location: registration.php");
				}	
			}
			else
			{
				echo "4";
				$error_message = "Wrong";
				$user="SELECT * FROM data WHERE sys_username ='$sys_usr'";
				$check=mysqli_query($connect,$user);
				$row_count=mysqli_num_rows($check);
				
				if($row_count==1)
					$_SESSION['system_username'] = $sys_usr;
				else
					$error_message .=" System Admin username";
				
				$user="SELECT * FROM data WHERE info_username = '$info_usr'";
				$check=mysqli_query($connect,$user);
				$row_count=mysqli_num_rows($check);
				
				if($row_count==1)
					$_SESSION['infodesk_username'] = $info_usr;
				else
					$error_message .=" Infodesk username ";

				//no matter the messages should be shown
				$_SESSION['error_message'] = $error_message;
				//header("Location: login.php");
				//back to login page with error message
			}
		}

		//either no input or username or password
		else
		{
			echo "5";
			if(empty($sys_usr))
				$error_message .= "System Admin username ";
			if(empty($sys_pass))
				$error_message .= "System admin password ";
			if(empty($info_usr))
				$error_message .= " Infodesk username ";
			if(empty($indo_pass))
				$error_message .= "Infodesk password ";
			$error_message .=" Required!";

			//back to login page with showing the error message
			$_SESSION['error_message'] = $error_message;
			//header("Location: login.php");
		}
	}
?>