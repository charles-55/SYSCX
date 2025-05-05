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
				<li><a href="#">Log out</a></li>
			</ul>
		</nav>

		<main>
			<section>
				<h2>Register a new profile</h2>
				<!-- https://ramisabouni.com/sysc4504/process_register.php -->
				<form method="POST" action="profile.php">
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
									<input type="submit" name="register" value="Register" />
									<input type="reset" value="Reset" />
								</td>
							</tr>
						</table>
					</fieldset>
				</form>
			</section>
		</main>

		<div id="user_info">
		</div>
	</div>
</body>

</html>