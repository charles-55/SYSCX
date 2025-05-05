<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>Update SYSCX profile</title>
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
				<?php
					session_start();
					
					if (isset($_SESSION["student_id"])) {
						echo "<li id=\"selected_page\"><a href=\"profile.php\">Profile</a></li>";
						if ($_SESSION["account_type"] === 0) { echo "<li><a href=\"user_list.php\">User List</a></li>"; }
						echo"<li><a href=\"logout.php\">Log out</a></li>";
					} else {
						echo "<li><a href=\"register.php\">Register</a></li>
							<li><a href=\"login.php\">Log in</a></li>";
					}
				?>
			</ul>
		</nav>

		<main>
			<section>
				<h2>Update Profile information</h2>
				<!-- https://ramisabouni.com/sysc4504/process_profile.php -->
				<form method="POST" action="#">
					<fieldset>
						<legend><span>Personal information</span></legend>
						<table>
							<tr>
								<td>
									<label>First Name:</label>
									<input type="text" id="first_name" name="first_name" placeholder="ex: John" />
								</td>
								<td>
									<label>Last Name:</label>
									<input type="text" id="last_name" name="last_name" placeholder="ex: Snow" />
								</td>
								<td>
									<label>DOB:</label>
									<input type="date" id="dob" name="DOB" />
								</td>
							</tr>
						</table>
					</fieldset>

					<fieldset>
						<legend><span>Address</span></legend>
						<table>
							<tr>
								<td>
									<label>Street Number:</label>
									<input type="number" id="street_number" name="street_number" />
								</td>
								<td>
									<label>Street Name:</label>
									<input type="text" id="street_name" name="street_name" />
								</td>
							</tr>
							<tr>
								<td>
									<label>City:</label>
									<input type="text" id="city" name="city" />
								</td>
								<td>
									<label>Province:</label>
									<input type="text" id="province" name="province" />
								</td>
								<td>
									<label>Postal Code:</label>
									<input type="text" id="postal_code" name="postal_code" />
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
									<input type="email" id="student_email" name="student_email" />
								</td>
							</tr>
							<tr>
								<td>
									<label>Program:</label>
									<select id="program" name="program">
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
								<td><label>Choose your Avatar:</label></td>
							</tr>
							<tr>
								<td id="avatar_selection">
									<input type="radio" name="avatar" value="1" />
									<img src="images/img_avatar1.png" alt="User Image" />
									<input type="radio" name="avatar" value="2" />
									<img src="images/img_avatar2.png" alt="User Image" />
									<input type="radio" name="avatar" value="3" />
									<img src="images/img_avatar3.png" alt="User Image" />
									<input type="radio" name="avatar" value="4" />
									<img src="images/img_avatar4.png" alt="User Image" />
									<input type="radio" name="avatar" value="5" />
									<img src="images/img_avatar5.png" alt="User Image" />
								</td>
							</tr>
							<tr>
								<td>
									<input type="submit" name="profile" value="Submit" />
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
						if (isset($_SESSION["student_id"])) {
							$student_id = $_SESSION['student_id'];

							$sql = "SELECT * FROM users_info WHERE student_id = ?";
							$statement = $conn -> prepare($sql);
							$statement -> bind_param('i', $student_id);
							$statement -> execute();
							$result = $statement -> get_result();
							$statement -> close();
							$row = $result -> fetch_assoc();

							$first_name = $row["first_name"];
							$last_name = $row["last_name"];
							$dob = $row["dob"];
							$student_email = $row["student_email"];

							$sql = "SELECT * FROM users_address WHERE student_id = ?";
							$statement = $conn -> prepare($sql);
							$statement -> bind_param('i', $student_id);
							$statement -> execute();
							$result = $statement -> get_result();
							$statement -> close();
							$row = $result -> fetch_assoc();

							$street_number = $row["street_number"];
							$street_name = $row["street_name"];
							$city = $row["city"];
							$province = $row["province"];
							$postal_code = $row["postal_code"];

							$sql = "SELECT * FROM users_program WHERE student_id = ?";
							$statement = $conn -> prepare($sql);
							$statement -> bind_param('i', $student_id);
							$statement -> execute();
							$result = $statement -> get_result();
							$statement -> close();
							$row = $result -> fetch_assoc();

							$program = $row["program"];

							$sql = "SELECT * FROM users_avatar WHERE student_id = ?";
							$statement = $conn -> prepare($sql);
							$statement -> bind_param('i', $student_id);
							$statement -> execute();
							$result = $statement -> get_result();
							$statement -> close();
							$row = $result -> fetch_assoc();

							$avatar = $row["avatar"];
						} else {
							header("Location: login.php");
							$conn -> close();
							exit();
						}

						if (isset($_POST["profile"])) {
							$first_name = $_POST["first_name"];
							$last_name = $_POST["last_name"];
							$dob = $_POST["DOB"];

							$street_number = $_POST["street_number"];
							$street_name = $_POST["street_name"];
							$city = $_POST["city"];
							$province = $_POST["province"];
							$postal_code = $_POST["postal_code"];

							$student_email = $_POST["student_email"];
							$program = $_POST["program"];
							$avatar = $_POST["avatar"];

							$sql1 = "UPDATE users_info SET first_name = ?, last_name = ?, dob = ?, student_email = ? WHERE student_id = ?";

							$sql2 = "UPDATE users_address SET street_number = ?, street_name = ?, city = ?, province = ?, postal_code = ? WHERE student_id = ?";
							
							$sql3 = "UPDATE users_avatar SET avatar = ? WHERE student_id = ?";

							$sql4 = "UPDATE users_program SET program = ? WHERE student_id = ?";

							$statement1 = $conn -> prepare($sql1);
							$statement2 = $conn -> prepare($sql2);
							$statement3 = $conn -> prepare($sql3);
							$statement4 = $conn -> prepare($sql4);

							$statement1 -> bind_param('ssssi', $first_name, $last_name, $dob, $student_email, $student_id);
							$statement2 -> bind_param('issssi', $street_number, $street_name, $city, $province, $postal_code, $student_id);
							$statement3 -> bind_param('ii', $avatar, $student_id);
							$statement4 -> bind_param('si', $program, $student_id);

							$statement1 -> execute();
							$statement2 -> execute();
							$statement3 -> execute();
							$statement4 -> execute();

							$statement1 -> close();
							$statement2 -> close();
							$statement3 -> close();
							$statement4 -> close();

							echo "<strong>Student record updated successfully!!!</strong><br />";
						}
						
						$conn -> close();
					}
					
					echo "<script>
							document.addEventListener('DOMContentLoaded', function(e) {
								const first_name = document.getElementById('first_name');
								const last_name = document.getElementById('last_name');
								const dob = document.getElementById('dob');

								first_name.value = '$first_name';
								last_name.value = '$last_name';
								dob.value = '$dob';

								const street_number = document.getElementById('street_number');
								const street_name = document.getElementById('street_name');
								const city = document.getElementById('city');
								const provice = document.getElementById('province');
								const postal_code = document.getElementById('postal_code');

								street_number.value = '$street_number';
								street_name.value = '$street_name';
								city.value = '$city';
								province.value = '$province';
								postal_code.value = '$postal_code';

								
								const student_email = document.getElementById('student_email');
								const program = document.getElementById('program');

								student_email.value = '$student_email';
								program.value = '$program';

								var radios = document.querySelectorAll(\"input[name='avatar']\");

								radios.forEach(radio => {
									if (radio.value === '$avatar') {
										radio.checked = true;
									}
								});

								console.log('first_name.value');
							});
						</script>";
				?>
			</section>
		</main>

		<div id="user_info">
			<?php
				if (isset($_SESSION["student_id"])) {
					echo "
						<p>" . $first_name . " " . $last_name . "</p><br />

						<p><img src=\"images/img_avatar" . $avatar . ".png\" alt=\"User Image\" /></p><br />
			
						<p>Email:</p>
						<p><a href=\"mailto:" . $student_email ."\">" . $student_email . "</a></p><br />
			
						<p>Program: </p>
						<p>" . $program . "</p>
					";
				}
			?>
		</div>
	</div>
</body>

</html>