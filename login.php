<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <title>Login on SYSCX</title>
   <link rel="stylesheet" href="assets/css/reset.css">
   <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
	<header>
		<h1>SYSCX</h1>
		<p>Social media for SYSC students in Carleton University</p>
	</header>

	<div id="content">
		<nav>
			<ul>
				<li><a href="index.php">Home</a></li>
				<li><a href="profile.php">Profile</a></li>
				<li><a href="register.php">Register</a></li>
                <li id="selected_page"><a href="#">Log in</a></li>
			</ul>
		</nav>

		<main>
			<section>
				<h2>Login to your Profile</h2>
				<form method="POST" action="#">
					<fieldset>
						<table>
							<tr>
								<td>
									<label>Email address:</label>
									<input type="email" name="student_email" />
								</td>
							</tr>
							<tr>
								<td>
									<label>Password:</label>
									<input type="password" name="student_password" id="student_password" />
								</td>
							</tr>
							<tr><td id="errorMsg"></td></tr>
							<tr>
								<td>
									<input type="submit" name="login" value="Login" />
									<input type="reset" value="Reset" />
								</td>
							</tr>
						</table>
					</fieldset>
				</form>
		
				<?php
					require_once ('connection.php');
					$conn = new mysqli($server_name, $username, $password, $database_name);

					if ($conn->connect_error) {
						echo "Error: Couldn't connect to database.";
					} else {
						if (isset($_POST["login"])) {
                            session_start();
							$student_email = $_POST["student_email"];
							$password = $_POST["student_password"];

							$sql = "SELECT student_id, student_email FROM users_info WHERE student_email = ?";
							$statement = $conn -> prepare($sql);
							$statement -> bind_param('s', $student_email);
							$statement -> execute();
							$result = $statement -> get_result();
							$statement -> close();

							if ($result -> num_rows == 1) {
                                $student_id = $result -> fetch_assoc()["student_id"];
								$sql = "SELECT password FROM users_passwords WHERE student_id = ?";
							
								$statement = $conn -> prepare($sql);
								$statement -> bind_param('i', $student_id);
								$statement -> execute();
                                $result = $statement -> get_result();
								$statement -> close();

                                $db_password = $result -> fetch_assoc()['password'];

                                if (password_verify($password, $db_password)) {
                                    $sql = "SELECT account_type FROM users_permissions WHERE student_id = ?";
                                
                                    $statement = $conn -> prepare($sql);
                                    $statement -> bind_param('i', $student_id);
                                    $statement -> execute();
                                    $result = $statement -> get_result();
                                    $statement -> close();
    
                                    $account_type = $result -> fetch_assoc()['account_type'];

                                    $_SESSION["student_id"] = $student_id;
                                    $_SESSION["account_type"] = $account_type;
                                    header("Location: index.php");
                                    $conn -> close();
                                    exit();
                                }
							}
                            echo "<script>
                                    document.addEventListener('DOMContentLoaded', function(e) {
                                        const errorMsg = document.getElementById('errorMsg');

                                        errorMsg.innerHTML = \"Invalid email or password!\";
                                    });
                                </script>";
						}
						
						$conn -> close();
					}
				?>

				<p>Don't have an account? <a href="register.php">Register here!</a></p>
			</section>
		</main>

		<div id="user_info">
		</div>
	</div>
</body>

</html>