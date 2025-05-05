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
				<li><a href="index.php">Home</a></li>
				<?php
					session_start();
					
					if (isset($_SESSION["student_id"])) {
						echo "<li><a href=\"profile.php\">Profile</a></li>
                            <li id=\"selected_page\"><a href=\"user_list.php\">User List</a></li>";
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
                <?php
                    if ($_SESSION["account_type"] !== 0) {
                        echo "<h1 id=\"errorMsg\">PERMISSION DENIED!</h1>
                        <p>Return Home: <a href=\"index.php\">HOME</a></p>";
                    }
                    else {
                        require_once ('connection.php');
                        $conn = new mysqli($server_name, $username,  $password, $database_name);

                        if ($conn->connect_error) {
                            die("Error: Couldn't connect." . $conn -> connect_error);
                        }
                        if (isset($_SESSION["student_id"])) {
                            $student_id = $_SESSION['student_id'];
    
                            $sql = "SELECT * FROM users_info LEFT JOIN users_program ON users_info.student_id = users_program.student_id LEFT JOIN users_permissions ON users_info.student_id = users_permissions.student_id";
        
                            $statement = $conn -> prepare($sql);
                            $statement -> execute();
                            $result = $statement -> get_result();
                            $statement -> close();

							echo "<style>
									#usersList {
										background-color: #e8e8e8;
									}
									
									#usersList table {
										margin: 10px;
									}
									
									#usersList table,th,tr,td {
										border: 2px solid black;
										border-collapse: collapse;
										text-align: center;
									}
								</style>";
        
                            echo "<div id='usersList'><h2>Users List</h2>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Student ID</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Student Email</th>
                                            <th>Program</th>
                                            <th>Account Type</th>
                                        </tr>
                                    </thead><tbody>";
                            while ($row = $result->fetch_assoc()) {
                                    echo "<tr>"
                                            ."<td>" .$row['student_id'] ."</td>"
                                            ."<td>" .$row['first_name']. "</a></td>"
                                            ."<td>" .$row['last_name']. "</a></td>"
                                            ."<td>" .$row['student_email']. "</a></td>"
                                            ."<td>" . $row['program'] . "</td>"
                                            ."<td>" .$row['account_type']. "</td>"
                                        ."</tr>";
                                }
                            echo "</tbody></table></div>";
                        } else {
                            header("Location: login.php");
                            $conn -> close();
                            exit();
                        }
                    
                        $conn -> close();
                    }
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