<?php
    session_start();
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "seproject";
    $usname = $_POST['username'];
    $psword = $_POST['password'];
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "select * from member 
            where username='".$usname."' 
            and password = '".$psword."'
            limit 1
            ";
    $result = mysqli_query($conn,$sql);

    if(mysqli_num_rows($result)==1){
        $_SESSION['id'] = $usname;
        header('location:../index.php');
    }
    else{
        
        echo "You're not a member";
        // sleep(5);
        header('refresh:2;url=../login.html');
        exit();
        
    }
?>