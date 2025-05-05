<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <title>Logout on SYSCX</title>
   <link rel="stylesheet" href="assets/css/reset.css">
   <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <?php
        session_start();
        session_unset();
    	session_destroy();
        header("Location: login.php");
        exit();
    ?>
</body>

</html>