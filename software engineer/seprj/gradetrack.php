<?php 
    session_start();
    $servername = "localhost";
    $username = "root";
    $dbname = "seproject";

    // Create connection
    $conn = new mysqli($servername, $username, "", $dbname);

    $id = $_SESSION['id'];
    // echo $username;
    // if ($conn->connect_error) {
    //     header('location:login.html');
    // }
    

    
    $studentID = $_SESSION["studentID"];
    $fname = $_SESSION["fname"];
    $lname = $_SESSION["lname"];
    // echo $studentID;

    $sql = "SELECT ROW_NUMBER() OVER(ORDER BY CourseNo ASC) AS No, subject.CourseNo, subject.CourseTitle, subject.Credit, gradetrackingsystem.grade
            FROM gradetrackingsystem
            INNER JOIN subject on gradetrackingsystem.subjectID = subject.CourseNo
            WHERE gradetrackingsystem.studentID = '".$studentID."'
            ";
    $result = mysqli_query($conn, $sql);
    // echo $sql;
    $rows = array();
    while($r = mysqli_fetch_assoc($result)) {
        $rows[] = $r;
    }
    // echo $_POST["boundary"];
    // echo gettype($_POST["boundary"]);
    // print json_encode($rows);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="main.css">
        <title>Document</title> 
        
        <script>
            var std_id = <?php echo "'$studentID'" ?> ;
            var std_count = [30,10,40];
            let fname = <?php echo "'$fname'" ?>;
            let lname = <?php echo "'$lname'" ?>;
            let bound = 1; 

            
                // console.log("true");
            
            
            let subjectData = <?php print json_encode($rows); ?>;
            // console.log(subjectData);
            
            window.onload = () => {
                loadStdData(std_id, fname, lname);
                loadTableData(subjectData,bound);
                
            };

            function loadStdData(std_id, fname, lname){
                document.getElementById('stdID').innerHTML = 'รหัส: ' + std_id;
                document.getElementById('stdName').innerHTML = 'ขื่อ-นามสกุล: ' + fname + " " + lname;
                
            }

            function loadTableData(subjectData,bound){
                const tableBody = document.getElementById('tableData');
                const sumBody = document.getElementById('sumData');
                let dataHtml = '';
                var subCount = 0;
                var ca =0;
                var ce = 0;
                var grade = 0;

                for(let subjectrow of subjectData){
                    dataHtml += `<tr><td>${subjectrow.No}</td><td>${subjectrow.CourseNo}</td><td>${subjectrow.CourseTitle}</td><td>${subjectrow.Credit}</td><td>${subjectrow.grade}</td></tr>`;
                    ca+= parseInt(subjectrow.Credit);
                    ce+= parseInt(checkWithdraw(subjectrow.grade,subjectrow.Credit));
                    grade += convertGrade(subjectrow.grade)*parseInt(subjectrow.Credit);
                    subCount ++;
                    // console.log(ca,"ca",ce,"ce",subjectrow.grade);
                    // console.log(convertGrade(subjectrow.grade),subjectrow.grade,'grade');
                    // console.log(parseInt(subjectrow.Credit),subjectrow.Credit,'credit');
                }
                // console.log(grade);
                tableBody.innerHTML = dataHtml;
                sumBody.innerHTML = `<tr><td>1/25562</td><td>${ca}</td><td>${ce}</td><td>${grade/ce}</td><td>${grade/ce}</td></tr>` ;
                loadVenn(grade/ce,bound);
            }

            function loadVenn(gpa,bound){
                if(gpa <= 1.5)
                    document.getElementsByClassName('ven')[0].style.backgroundImage= "url('assets/redven.png')";
                else if(gpa <=1.5+bound)
                    document.getElementsByClassName('ven')[0].style.backgroundImage= "url('assets/orangeven.png')";
                else
                    document.getElementsByClassName('ven')[0].style.backgroundImage= "url('assets/greenven.png')";
            }

            function checkWithdraw(grade,credit){
                if(grade == 'W')
                    return 0;
                else
                    return credit;
            }

            function convertGrade(grade){
                if(grade == 'A')
                    return 4;
                else if(grade == 'B+')
                    return 3.5;
                else if(grade == 'B')
                    return 3;
                else if(grade == 'C+')
                    return 2.5;
                else if(grade == 'C')
                    return 2;
                else if(grade == 'D+')
                    return 1.5;
                else if(grade == 'D')
                    return 1;
                else if(grade == 'F')
                    return 0;
                else if(grade == 'W')
                    return 0;
            }
        </script> 
    </head>

    <body>
        <header>
            <div class="container">
                <img class="logo" src="https://upload.wikimedia.org/wikipedia/th/7/7b/Chiang_Mai_University_Logo.png">
                <nav>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a class ="active" href="#">Grade tracking</a></li>
                        <li><a href="coresubtrack.html">Core subject tracking</a></li>
                        <li><a href="login.html">logout</a></li>
                    </ul>
                </nav>
            </div>
            <div class="main_header">
                <h1>ระบบวิเคราะห์ปัจจัย</h1>
                <h2>การไม่สำเร็จการศึกษา</h2>
            </div>
        </header>
        <div class="gradename">
            <div class="name">
                <ul id="stdID"></ul>
                <ul id="stdName"></ul>
            </div>
        </div>
        <div class="subjectx">
            <h1>ภาคการศึกษาที่ 1/2562</h1>
        </div>
        <table class="flex">
            <td>
                <table class="tgrade">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Course No</th>
                            <th>Course Title</th>
                            <th>Credit</th>
                            <th>Grade</th>
                        </tr>
                    </thead>
                    <tbody id='tableData'>
                    </tbody>
                </table>
        
                <br>
            
                <table class="tgrade">
                    <thead>
                        <tr>
                            <th>Record</th>
                            <th>CA</th>
                            <th>CE</th>
                            <th>GPA</th>
                            <th>GPA cumulative</th>
                        </tr>
                    </thead>
                    <tbody id='sumData'>
                    </tbody>
                </table>
            </td>
            <td>
                <!-- <form style="position:relative; top:-100px; text-align:center;" action=" <#?php echo $_SERVER['PHP_SELF']; ?>" method="post"> 
                    <input type="text" name="boundary" placeholder="กำหนดขอบเขต" required></input>
                    <input type="submit" name="submit" value="submit"></input>
                </form> -->
                <p style="position:relative; top :-100px;text-align:center;">ระดับความเสี่ยง <br> Red <= 1.5, Orange <= 2.5, Green > 2.5</p>
                <div class="ven">
                    <!-- <span></span><img src="assets/redven.png" alt=""></span> -->
                    <span class="redven"></span>
                    <span class="orangeven"></span>
                    <span class="greenven"></span>
                </div>
                <br>
                <img class="tire" src="assets/tire.jpg">
            </td>
        </table>
    </body>
</html>
