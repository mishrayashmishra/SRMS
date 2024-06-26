<?php
session_start();
error_reporting(0);
include('includes/config.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Result Management System</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
    <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen">
    <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen">
    <link rel="stylesheet" href="css/prism/prism.css" media="screen">
    <link rel="stylesheet" href="css/main.css" media="screen">
    <script src="js/modernizr/modernizr.min.js"></script>
</head>

<body>
    <div class="main-wrapper">
        <div class="content-wrapper">
            <div class="content-container">


                <!-- /.left-sidebar -->

                <div class="main-page">
                    <div class="container-fluid">
                        <div class="row page-title-div">
                            <div class="col-md-12">
                                <h2 class="title" align="center">Result Management System</h2>
                            </div>
                        </div>
                        <!-- /.row -->

                        <!-- /.row -->
                    </div>
                    <!-- /.container-fluid -->

                    <section class="section">
                        <div class="container-fluid">

                            <div class="row">



                                <div class="col-md-8 col-md-offset-2">
                                    <div class="panel">
                                        <div class="panel-heading" id="resultprint">
                                            <div class="panel-title">
                                                <?php
                                                // code Student Data
                                                $rollid = $_POST['rollid'];
                                                $classid = $_POST['class'];
                                                $_SESSION['rollid'] = $rollid;
                                                $_SESSION['classid'] = $classid;
                                                $qery = "SELECT   tblstudents.StudentName,tblstudents.RollId,tblstudents.RegDate,tblstudents.StudentId,tblstudents.Status,tblclasses.ClassName,tblclasses.Section from tblstudents join tblclasses on tblclasses.id=tblstudents.ClassId where tblstudents.RollId=:rollid and tblstudents.ClassId=:classid ";
                                                $stmt = $dbh->prepare($qery);
                                                $stmt->bindParam(':rollid', $rollid, PDO::PARAM_STR);
                                                $stmt->bindParam(':classid', $classid, PDO::PARAM_STR);
                                                $stmt->execute();
                                                $resultss = $stmt->fetchAll(PDO::FETCH_OBJ);
                                                $cnt = 1;
                                                if ($stmt->rowCount() > 0) {
                                                    foreach ($resultss as $row) {   ?>
                                                        <p><b>Student Name :</b> <?php $printname = htmlentities($row->StudentName); echo htmlentities($row->StudentName); ?></p>
                                                        <p><b>Student Roll Id :</b> <?php echo htmlentities($row->RollId); ?>
                                                        <p><b>Student Class:</b> <?php echo htmlentities($row->ClassName); ?>(<?php echo htmlentities($row->Section); ?>)
                                                        <?php }

                                                        ?>
                                            </div>
                                            <div class="panel-body p-20">







                                                <table class="table table-hover table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Subject</th>
                                                            <th>Marks</th>
                                                        </tr>
                                                    </thead>




                                                    <tbody>
                                                        <?php
                                                        // Code for result

                                                        $query = "select t.StudentName,t.RollId,t.ClassId,t.marks,SubjectId,tblsubjects.SubjectName from (select sts.StudentName,sts.RollId,sts.ClassId,tr.marks,SubjectId from tblstudents as sts join  tblresult as tr on tr.StudentId=sts.StudentId) as t join tblsubjects on tblsubjects.id=t.SubjectId where (t.RollId=:rollid and t.ClassId=:classid)";
                                                        $query = $dbh->prepare($query);
                                                        $query->bindParam(':rollid', $rollid, PDO::PARAM_STR);
                                                        $query->bindParam(':classid', $classid, PDO::PARAM_STR);
                                                        $query->execute();
                                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                        $cnt = 1;
                                                        if ($countrow = $query->rowCount() > 0) {

                                                            foreach ($results as $result) {

                                                        ?>

                                                                <tr>
                                                                    <th scope="row"><?php echo htmlentities($cnt); ?></th>
                                                                    <td><?php echo htmlentities($result->SubjectName); ?></td>
                                                                    <td><?php echo htmlentities($totalmarks = $result->marks); ?></td>
                                                                </tr>
                                                            <?php
                                                                $totlcount += $totalmarks;
                                                                $cnt++;
                                                            }
                                                            ?>
                                                            <tr>
                                                                <th scope="row" colspan="2">Total Marks</th>
                                                                <td><b><?php echo htmlentities($totlcount); ?></b> out of <b><?php echo htmlentities($outof = ($cnt - 1) * 100); ?></b></td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row" colspan="2">Percntage</th>
                                                                <td><b><?php echo  htmlentities($totlcount * (100) / $outof); ?> %</b></td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row" colspan="2">Acedemic Profile</th>
                                                                <td>
                                                                    <?php
                                                                    $percentageprofile =  htmlentities($totlcount * (100) / $outof);

                                                                    if ($percentageprofile < 35) {
                                                                        echo '<span style="color: red;"><b>FAIL</b></span>';
                                                                    } else {
                                                                        echo '<span style="color: green;"><b>PASS</b></span>';
                                                                    }
                                                                    ?>
                                                                    </b></td>
                                                            </tr>

                                                        <?php } else { ?>
                                                            <div class="alert alert-warning left-icon-alert" role="alert">
                                                                <strong>Notice!</strong> Your result not declare yet
                                                            <?php }
                                                            ?>
                                                            </div>
                                                        <?php
                                                    } else { ?>

                                                            <div class="alert alert-danger left-icon-alert" role="alert">
                                                                strong>Oh snap!</strong>
                                                            <?php
                                                            echo htmlentities("Invalid Roll Id");
                                                        }
                                                            ?>
                                                            </div>



                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                        <!-- /.panel -->
                                    </div>
                                    <!-- /.col-md-6 -->

                                    <div class="form-group">

                                        <div class="w-full">
                                            <a href="index.php">Back to Home</a>
                                            <?php
                                            if ($countrow = $query->rowCount() > 0) { ?>
                                                <a onclick="downlaodresult()" class="btn btn-primary" style="background: blue;float: right;">downlaodresult</a>
                                            <?php } ?>
                                        </div>
                                    </div>

                                </div>
                                <!-- /.row -->

                            </div>
                            <!-- /.container-fluid -->
                    </section>
                    <!-- /.section -->

                </div>
                <!-- /.main-page -->


            </div>
            <!-- /.content-container -->
        </div>
        <!-- /.content-wrapper -->

    </div>
    <!-- /.main-wrapper -->

    <!-- ========== COMMON JS FILES ========== -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        function downlaodresult() {
            var element = document.getElementById('resultprint');

            var opt = {
                margin: 1,
                filename: '<?php echo $printname; ?>',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'in',
                    format: 'letter',
                    orientation: 'portrait'
                }
            };

            // New Promise-based usage:
            html2pdf().set(opt).from(element).save();
        }
    </script>

    <!-- ========== ADD custom.js FILE BELOW WITH CHANGES ========== -->

</body>

</html>