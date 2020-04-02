<?php 
    

    session_start();

    require_once "connection.php";

    if (isset($_POST['submit'])) {

        $username = $_POST['username'];
        $password = $_POST['password'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $type = $_POST['type'];
        $status = 'กำลังศึกษา';
        $studentid = "SELECT studentID
                      FROM student
                      ORDER BY studentID DESC
                      limit 1
                      ";
            $sql = mysqli_query($conn, $studentid);
            // echo $studentid;
            $maxStudentID = mysqli_fetch_assoc($sql);
            $newStudentID = $maxStudentID["studentID"]+1;
            $strStudentID = sprintf('%09d', $newStudentID);
        $teacherid = '000000004';

        $user_check = "SELECT * FROM member WHERE username = '$username' LIMIT 1";
        $result = mysqli_query($conn, $user_check);
        $user = mysqli_fetch_assoc($result);

        if ($user['username'] === $username) {
            echo "<script>alert('Username already exists');</script>";

        } else {
            // $passwordenc = md5($password);

            $query = "INSERT INTO member (username, password, type)
                        VALUE ('$username', '$password', '$type')";
            // ECHO $query;
            $result = mysqli_query($conn, $query);

            $query2 = "INSERT INTO student (studentID, teacherID, Fname, UserName, status, Lname)
                       VALUE ('$strStudentID', '$teacherid', '$firstname', '$username', '$status', '$lastname')";
            // echo $query2;
            $result2 = mysqli_query($conn, $query2);

            if ($result) {
                $_SESSION['success'] = "Insert user successfully";
                echo "<script>alert('User registeration successfully');</script>";
                // header("Location: login.html");
            } else {
                $_SESSION['error'] = "Something went wrong";
                echo "<script>alert('Something went wrong');</script>";
                // header("Location: login.html");
            }
        }

    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register Page</title>

    <link rel="stylesheet" href="style_regist.css">

</head>
<body>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <center><h1>Register Form</h1></center>
        <label for="username">Username</label>
        <input type="text" name="username" placeholder="Enter your username" required>
        <br>
        <label for="password">Password</label>
        <input type="password" name="password" placeholder="Enter your password" required>
        <br>
        <label for="type">Type</label>
        <input type="text" name="type" placeholder="Student or Teacher" required>
        <br>
        <label for="firstname">Firstname</label>
        <input type="text" name="firstname" placeholder="Enter your firstname" required>
        <br>
        <label for="lastname">Lastname</label>
        <input type="text" name="lastname" placeholder="Enter your lastname" required>
        <br>
        <input type="submit" name="submit" value="Submit">
    
    </form>
    <br>
    <a href="login.html">Go back to login</a>
    
</body>
</html>