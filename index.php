<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>Index on SYSCX</title>
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
				<?php
					session_start();
					
					if (isset($_SESSION["student_id"])) {
						echo "<li><a href=\"profile.php\">Profile</a></li>";
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

					if (isset($_SESSION["student_id"])) {
						$student_id = $_SESSION['student_id'];

						$sql = "SELECT * FROM users_posts ORDER BY post_date DESC LIMIT 10";
	
						$statement = $conn -> prepare($sql);
						$statement -> execute();
						$result = $statement -> get_result();
						$statement -> close();
	
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

						if (isset($_POST["submit"])) {
							$new_post = $_POST["new_post"];
	
							$sql = "INSERT INTO users_posts (student_id, new_post) VALUES (?, ?)";
	
							$statement = $conn -> prepare($sql);
							$statement -> bind_param('is', $student_id, $new_post);
							$statement -> execute();
							$statement -> close();

							echo "<strong>Student post created successfully!!!</strong><br />";
						}
					} else {
						header("Location: login.php");
						$conn -> close();
						exit();
					}
				
					$conn -> close();
				?>
			</section>
		</main>

		<div id="user_info">
			<?php
				require_once ('connection.php');
				$conn = new mysqli($server_name, $username,  $password, $database_name);

				if ($conn->connect_error) {
					die("Error: Couldn't connect." . $conn -> connect_error);
				}

				if (isset($_SESSION["student_id"])) {
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