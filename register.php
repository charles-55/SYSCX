<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <title>Register on SYSCX</title>
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
				<li id="selected_page"><a href="register.php">Register</a></li>
				<li><a href="login.php">Log in</a></li>
			</ul>
		</nav>

		<main>
			<section>
				<h2>Register a new profile</h2>
				<!-- https://ramisabouni.com/sysc4504/process_register.php -->
				<form method="POST" action="#">
					<fieldset>
						<legend><span>Personal information</span></legend>
						<table>
							<tr>
								<td>
									<label>First Name:</label>
									<input type="text" name="first_name" placeholder="ex: John" />
								</td>
								<td>
									<label>Last Name:</label>
									<input type="text" name="last_name" placeholder="ex: Snow" />
								</td>
								<td>
									<label>DOB:</label>
									<input type="date" name="DOB" />
								</td>
							</tr>
						</table>
					</fieldset>

					<fieldset>
						<legend><span>Profile Information</span></legend>
						<table>
							<tr>
								<td>
									<label>Email address:</label>
									<input type="email" name="student_email" />
								</td>
							</tr>
							<tr>
								<td>
									<label>Program:</label>
									<select name="program">
										<option>Choose Program</option>
										<option>Computer Systems Engineering</option>
										<option>Software Engineering</option>
										<option>Communications Engineering</option>
										<option>Biomedical and Electrical</option>
										<option>Electrical Engineering</option>
										<option>Special</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									<label>Password:</label>
									<input type="password" name="student_password" id="student_password" />
								</td>
							</tr>
							<tr>
								<td>
									<label>Confirm password:</label>
									<input type="password" name="student_confirm_password" id="student_confirm_password" />
								</td>
							</tr>
							<tr><td id="errorMsg"></td></tr>

							<script>
								document.addEventListener('DOMContentLoaded', function(e) {
									const student_password = document.getElementById('student_password');
									const student_confirm_password = document.getElementById('student_confirm_password');
									const errorMsg = document.getElementById('errorMsg');

									student_password.addEventListener("change", function(ev) {
										if (student_password.value !== student_confirm_password.value) {
											errorMsg.innerHTML = "Passwords do not match!";
										} else {
											errorMsg.innerHTML = "";
										}
									});

									student_confirm_password.addEventListener("change", function(ev) {
										if (student_password.value !== student_confirm_password.value) {
											errorMsg.innerHTML = "Passwords do not match!";
										} else {
											errorMsg.innerHTML = "";
										}
									});
								});
							</script>

							<tr>
								<td>
									<input type="submit" name="register" value="Register" />
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
						if (isset($_POST["register"])) {
							session_start();
							$first_name = $_POST["first_name"];
							$last_name = $_POST["last_name"];
							$dob = $_POST["DOB"];
							$password = $_POST["student_password"];
							$hashed_password = password_hash($password, PASSWORD_BCRYPT);
							
							$street_number = 0;
							$street_name = "";
							$city = "";
							$province = "";
							$postal_code = "";

							$student_email = $_POST["student_email"];
							$program = $_POST["program"];
							$avatar = 0;

							$sql = "SELECT student_email FROM users_info WHERE student_email = ?";
							$statement = $conn -> prepare($sql);
							$statement -> bind_param('s', $student_email);
							$statement -> execute();
							$result = $statement -> get_result();
							$statement -> close();

							if ($result -> num_rows == 0) {
								$sql = "INSERT INTO users_info (first_name, last_name, dob, student_email) VALUES (?, ?, ?, ?)";
							
								$statement = $conn -> prepare($sql);
								$statement -> bind_param('ssss', $first_name, $last_name, $dob, $student_email);
								$statement -> execute();
								$statement -> close();

								$sql = "SELECT LAST_INSERT_ID() AS student_id";
								$result = $conn->query($sql);
								$student_id = ($result->fetch_assoc())["student_id"];

								$sql1 = "INSERT INTO users_address (student_id, street_number, street_name, city, province, postal_code) VALUES (?, 0, NULL, NULL, NULL, NULL)";
								$sql2 = "INSERT INTO users_program (student_id, program) VALUES (?, ?)";
								$sql3 = "INSERT INTO users_avatar (student_id, avatar) VALUES (?, 0)";
								$sql4 = "INSERT INTO users_passwords (student_id, password) VALUES (?, ?)";
								$sql5 = "INSERT INTO users_permissions (student_id, account_type) VALUES (?, 1)";

								$statement1 = $conn -> prepare($sql1);
								$statement2 = $conn -> prepare($sql2);
								$statement3 = $conn -> prepare($sql3);
								$statement4 = $conn -> prepare($sql4);
								$statement5 = $conn -> prepare($sql5);

								$statement1 -> bind_param('i', $student_id);
								$statement2 -> bind_param('is', $student_id, $program);
								$statement3 -> bind_param('i', $student_id);
								$statement4 -> bind_param('is', $student_id, $hashed_password);
								$statement5 -> bind_param('i', $student_id);

								$statement1 -> execute();
								$statement2 -> execute();
								$statement3 -> execute();
								$statement4 -> execute();
								$statement5 -> execute();

								$statement1 -> close();
								$statement2 -> close();
								$statement3 -> close();
								$statement4 -> close();
								$statement5 -> close();

								$_SESSION["student_id"] = $student_id;
								header("Location: index.php");
								$conn -> close();
								exit();
							} else {
								echo "<script>
										document.addEventListener('DOMContentLoaded', function(e) {
											const errorMsg = document.getElementById('errorMsg');

											errorMsg.innerHTML = \"Email address is taken!\";
										});
									</script>";
							}
						}
						
						$conn -> close();
					}
				?>

				<p>Already have an account? <a href="login.php">Login here!</a></p>
			</section>
		</main>

		<div id="user_info">
		</div>
	</div>
</body>

</html>