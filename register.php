<?php include 'inc/head.php' ?>
<div id="wrap">
<main >
    <div class="container">


        <div class="row">
            <div class="col-md-10 col-md-offset-1 col-sm-6 wow fadeInRight" data-wow-delay=".8s" style="margin-top: 100px">
                <div class="panel panel-default">
                    <div class="panel-body" >
                        <h4 class="text-center">Register For Jamb <?php echo date('Y'); ?> Mock CBT TEST.</h4>
                        <hr class="colorgraph">
                        <?php register() ?>
                        <form role="form" method="post" enctype="multipart/form-data">




                            <div class="row">



                                <div class=" col-xs-12 col-sm-6 col-md-6">


                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <label for="password">Reg No.</label>
                                                <input type="text" name="reg_no" id="reg_no" class="form-control" placeholder="Reg No" tabindex="5" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <label for="email">First Name*</label>
                                                <input type="text" name="first_name" id="email" value="<?php if(isset($_POST['register'])){ echo $_POST['first_name'];}  ?>"  class="form-control" placeholder="First name" tabindex="1" required>
                                            </div>
                                        </div>
                                    </div><br>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <label for="email">Last Name *</label>
                                                <input type="text" name="last_name" id="last_name" value="<?php if(isset($_POST['register'])){ echo $_POST['last_name'];}  ?>"  class="form-control" placeholder="last name" tabindex="1" required>
                                            </div>
                                        </div>
                                    </div>




                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" name="email" id="email" class="form-control" placeholder="email" tabindex="5" >
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <label for="phone">Phone No.</label>
                                                <input type="Number" name="phone" id="phone" class="form-control" placeholder="Phone Number" tabindex="5" >
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class=" col-xs-12 col-sm-6 col-md-6">



                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="form-group">

                                                <label for="password">First Subject.</label>

                                                <?php


                                                $query = "SELECT * FROM subjects WHERE subject_code = 'ENG'";
                                                $select_course_query = $connection->query($query);

                                                $row2 = $select_course_query ? $select_course_query->fetch_array() : null;

                                                $subject1 = $row2['name'];
                                                $subject1_id = $row2['id'];

                                                ?>

                                                <input type="text" value="<?php echo $subject1; ?>" name="" id="subject1" class="form-control" placeholder="" tabindex="5" disabled>

                                                <input type="hidden" value="<?php echo $subject1_id; ?>" name="subject1">

                                            </div>
                                        </div>
                                    </div>


                                    <?php


                                    $query = "SELECT * FROM subjects WHERE subject_code != 'ENG' ORDER BY RANDOM()";
                                    $select_course_query = $connection->query($query);

                                    ?>



                                    <div class="form-group">
                                        <label for="username">Second Subject</label>
                                        <select required class="form-control" name="subject2" id="">
                                            <?php
                                            if(!$select_course_query){
                                                die("QUERY FAILED");
                                            }

                                            while($row = $select_course_query->fetch_array()) {

                                                ?>

                                                <option value="<?php echo $row['id'] ?>"> <?php echo $row['name'] ?> </option>

                                            <?php } ?>


                                        </select>
                                    </div>
                                    <?php


                                    $query = "SELECT * FROM subjects WHERE subject_code != 'ENG' ORDER BY RANDOM()";
                                    $select_course_query = $connection->query($query);

                                    ?>

                                    <div class="form-group">
                                        <label for="subject3">Third Subject</label>
                                        <select required class="form-control" name="subject3" id="subject3">
                                            <?php
                                            if(!$select_course_query){
                                                die("QUERY FAILED");
                                            }

                                            while($row = $select_course_query->fetch_array()) {

                                                ?>

                                                <option value="<?php echo $row['id'] ?>"> <?php echo $row['name'] ?> </option>

                                            <?php } ?>


                                        </select>
                                    </div>

                                    <?php


                                    $query = "SELECT * FROM subjects WHERE subject_code != 'ENG' ORDER BY RANDOM()";
                                    $select_course_query = $connection->query($query);

                                    ?>

                                    <div class="form-group">
                                        <label for="username">Fourth Subject</label>
                                        <select required class="form-control" name="subject4" id="">
                                            <?php
                                            if(!$select_course_query){
                                                die("QUERY FAILED");
                                            }

                                            while($row = $select_course_query->fetch_array()) {

                                                ?>

                                                <option value="<?php echo $row['id'] ?>"> <?php echo $row['name'] ?> </option>

                                            <?php } ?>


                                        </select>
                                    </div>



                                    <hr class="colorgraph">
                                    <div class="row">
                                        <div class="col-md-12"><input type="submit" value="Register" name="register" class="btn btn-primary btn-block btn-lg" tabindex="7"></div>
                                    </div>
                        </form>
                    </div>



                </div>

            </div>
        </div>

    </div>

</main>
</div>
<?php include 'inc/footer.php' ?>
