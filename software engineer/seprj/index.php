<?php 
    session_start();
    $servername = "localhost";
    $username = "root";
    $dbname = "seproject";

    // Create connection
    $conn = new mysqli($servername, $username, "", $dbname);

    $id = $_SESSION['id'];
    $sql = "SELECT student.studentID,student.Fname, student.Lname, student.status, quitform.reason
            FROM student
            INNER JOIN member ON member.username = student.username
            LEFT JOIN quitform ON student.formID = quitform.formID
            WHERE student.username = '".$id."'
            ";
    $result = mysqli_query($conn, $sql);

    $qrow = mysqli_fetch_assoc($result);
    // echo $qrow;
    $status = $qrow["status"];
    $fname = $qrow["Fname"];
    $lname = $qrow["Lname"];
    $reason = $qrow["reason"];

    $stdID = $qrow["studentID"];
    
    $_SESSION["studentID"] = $stdID;
    $_SESSION["fname"] = $fname;
    $_SESSION["lname"] = $lname;
    // echo $_SESSION["studentID"];
    
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="main.css">
        <title>Document</title>  
        <script>
            let stat = <?php echo "'$status'" ?> ;
            let usname = <?php echo "'$id'" ?>;
            let fname = <?php echo "'$fname'" ?>;
            let lname = <?php echo "'$lname'" ?>;
            let reason = <?php echo "'$reason'" ?>;
            let stdID = <?php echo "'$stdID'" ?>;
            
            // console.log(fname);
            // console.log(stat);
            // console.log(convertName(fname,lname));
            window.onload = () =>{
                changeStat(stat);
                setName(fname, lname, stdID);
                if(stat == 'ไม่สำเร็จการศึกษาตามหลักสูตร')
                    setReason(reason);
            }

            function setName(fname, lname, stdID){
                document.getElementById("name").innerHTML = fname+" "+lname;
                document.getElementById("id").innerHTML = stdID;
            }

            function setReason(reason){
                document.getElementById("reason").innerHTML = 'สาเหตุ:'+reason;
                document.getElementById("reason").style.color = 'red';
            }

            function changeStat(stat){
                document.getElementById("status").innerHTML = stat;
                document.getElementById("status").style.color = changeColor(stat);
            }

            function changeColor(status){
                if(status =='กำลังศึกษา')
                    return 'blue';
                else if(status == 'พ้นสภาพนักศึกษา')
                    return 'red';
                else if(status == 'สำเร็จการศึกษา')
                    return 'green';
                else if(status == 'ไม่สำเร็จการศึกษาตามหลักสูตร')
                    return 'red';
            }   

            function convertName(fname, lname){
                return "'"+fname+" "+lname+"'";
            }

        

        </script>
    </head>

    <body>
        <header>
            <div class="container">
                <img class="logo" src="https://upload.wikimedia.org/wikipedia/th/7/7b/Chiang_Mai_University_Logo.png">
                <nav>
                    <ul>
                        <li><a class="active" href="#home">Home</a></li>
                        <li><a href="gradetrack.php">Grade tracking</a></li>
                        <li><a href="#subject">Core subject tracking</a></li>
                        <li><a href="login.html">logout</a></li>
                    </ul>
                </nav>
            </div>
            <div class="main_header">
                <h1>ระบบวิเคราะห์ปัจจัย</h1>
                <h2>การไม่สำเร็จการศึกษา</h2>
                
            </div>
        </header>
        <img src="https://upload.wikimedia.org/wikipedia/th/7/7b/Chiang_Mai_University_Logo.png" class="profile">
        <div class="indexname">
            <div class="name">
                <ul>
                    <li id="id"></li>
                </ul>
                <ul>
                    <li>ชื่อ-นามสกุล: </li>
                    <li id="name"></li>
                </ul>
                <ul>
                    <li>สถานะ: </li>
                    <li id="status"></li>
                </ul>
                <ul>
                    <li id="reason"></li>
                </ul>
            </div>
        </div>
    </body>
</html>
