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
				<li id="selected_page"><a href="profile.php">Profile</a></li>
				<li><a href="register.php">Register</a></li>
				<li><a href="#">Log out</a></li>
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
						session_start();

						if (isset($_POST["register"])) {
							$first_name = $_POST["first_name"];
							$last_name = $_POST["last_name"];
							$dob = $_POST["DOB"];
							
							$street_number = 0;
							$street_name = "";
							$city = "";
							$province = "";
							$postal_code = "";

							$student_email = $_POST["student_email"];
							$program = $_POST["program"];
							$avatar = 0;

							$sql = "INSERT INTO users_info (first_name, last_name, dob, student_email) VALUES ('$first_name', '$last_name', '$dob', '$student_email')";

							if ($conn->query($sql) === TRUE) {
								$sql = "SELECT LAST_INSERT_ID() AS student_id";
								$result = $conn->query($sql);
								$student_id = ($result->fetch_assoc())["student_id"];
								$_SESSION["student_id"] = $student_id;

								$sql1 = "INSERT INTO users_address (student_id, street_number, street_name, city, province, postal_code) VALUES ('$student_id', 0, NULL, NULL, NULL, NULL)";

								$sql2 = "INSERT INTO users_program (student_id, program) VALUES ('$student_id', '$program')";
								
								$sql3 = "INSERT INTO users_avatar (student_id, avatar) VALUES ('$student_id', 0)";

								$result1 = $conn->query($sql1);
								$result2 = $conn->query($sql2);
								$result3 = $conn->query($sql3);

								if ($result1 === $result2 && $result2 === $result3) {
									echo "<strong>Student record created successfully!!!</strong><br />";
								}
							} else {
								echo "Error: " . $sql . "<br />" . $conn->error;
							}
						} else if (isset($_POST["profile"])) {
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

							$sql1 = "UPDATE users_info SET first_name = '$first_name', last_name = '$last_name', dob = '$dob', student_email = '$student_email' WHERE student_id = '$student_id'";

							$sql2 = "UPDATE users_address SET street_number = '$street_number', street_name = '$street_name', city = '$city', province = '$province', postal_code = '$postal_code' WHERE student_id = '$student_id'";
							
							$sql3 = "UPDATE users_avatar SET avatar = '$avatar' WHERE student_id = '$student_id'";

							$sql4 = "UPDATE users_program SET program = '$program' WHERE student_id = '$student_id'";

							$result1 = $conn->query($sql1);
							$result2 = $conn->query($sql2);
							$result3 = $conn->query($sql3);
							$result4 = $conn->query($sql4);

							if ($result1 === $result2 && $result2 === $result3 && $result3 === $result4 && $result4 === TRUE) {
								echo "<strong>Student record updated successfully!!!</strong><br />";
							} else {
								echo "Error: An error occured!!!<br />" . $conn->error;
							}
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
			<p>Osamudiamen Nwoko</p><br />

			<p><img src="images/img_avatar1.png" alt="User Image" /></p><br />

			<p>Email:</p>
			<p><a href="mailto:osamudiamennwoko@cmail.carleton.ca">osamudiamennwoko@<br />cmail.carleton.ca</a></p><br />

			<p>Program: </p>
			<p>Software Engineering</p>
		</div>
	</div>
</body>

</html>