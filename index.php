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
				<li id="selected_page"><a href="index.php">Home</a></li>
				<li><a href="profile.php">Profile</a></li>
				<li><a href="register.php">Register</a></li>
				<li><a href="#">Log out</a></li>
			</ul>
		</nav>

		<main>
			<section>
				<h2>New Post</h2>
				<!-- https://ramisabouni.com/sysc4504/process_index.php -->
				<form method="POST" action="#">
					<fieldset id="newpost">
						<p>
							<textarea name="new_post" placeholder="What is happening?! (max 1000 characters)" maxlength="1000"></textarea>
						</p>

                        <input type="submit" name="submit" value="Post"/>
                        <input type="reset" value="Reset"/>
					</fieldset>
				</form>
				
				<?php 
					require_once ('connection.php');
					$conn = new mysqli($server_name, $username,  $password, $database_name);

					if ($conn->connect_error) {
						die("Error: Couldn't connect." . $conn -> connect_error);
					}
					session_start();

					if (isset($_SESSION["student_id"])) {
						$student_id = $_SESSION['student_id'];
					} else {
						header("Location: register.php");
						$conn -> close();
						exit();
					}

					if (isset($_POST["submit"])) {
						$new_post = $_POST["new_post"];

						$sql = "INSERT INTO users_posts (student_id, new_post) VALUES ('$student_id', '$new_post')";

						if ($conn->query($sql) === TRUE) {
							echo "<strong>Student post created successfully!!!</strong><br />";
						} else {
							echo "Error: An error occured!!!<br />" . $conn->error;
						}
					}

					$sql = "SELECT * FROM users_posts WHERE student_id = '$student_id' ORDER BY post_date DESC LIMIT 5";

					$result = $conn->query($sql);

					echo "<div id='posts_section'>";
					if ($result->num_rows > 0) {
						while($row = $result->fetch_assoc()) {
							echo "<details open>
								<summary>Post " . $row["post_id"] . "</summary>
							<p>" .  $row["new_post"]. "</p>
						</details>";
						}
					}
					echo  "</div>";
				
					$conn -> close();
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