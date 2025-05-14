<style>
    .dashboard_box .card-stats .card-header.card-header-icon img {
    padding: 6px;
}
</style>       
       
       <div class="dashboard_box">
            
            <div class="row">
            
                <?php
                    $prebookingCount = $dataReport['prebookingCount'];
                    $male = $dataReport['male']; //$this->db->select('id')->from('cattle_pre_booking')->where('cattle_sex','1')->get()->num_rows();
                    $female = $dataReport['female']; //$this->db->select('id')->from('cattle_pre_booking')->where('cattle_sex','2')->get()->num_rows();                    
                    $transferred = $dataReport['transferred'];
                    $doctor_count = $this->db->select(['id', 'name'])->from('users')->where('role_type', 'doctor')->get()->num_rows();
                    $slaughtering = $this->db->select('qrcode_id')->from('app_qrcode')->where('slaughtering_type','1')->get()->num_rows();
                    $sell = $this->db->select('qrcode_id')->from('app_qrcode')->where('slaughtering_type','2')->get()->num_rows();
                ?>
                
                <div class="col-lg-3 col-md-3 col-sm-6 col-6">
                    <div class="card card-stats two">
                         <a class="dashbox" href="#">
                        <div class="card-header card-header-warning card-header-icon">
                            <div class="card-icon">
                                <img src="../assets/backend/images/arrow_icon1.svg" alt="">
                            </div>
                            <p class="card-category"><?php echo get_phrase('Buffalo Reg.'); ?></p>
                            <h3 class="card-title"> 
                            <?php
                              echo $prebookingCount;
                              ?>
                  </h3>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                                <i class="fa fa-clock-o"></i> Just Updated
                            </div>
                        </div>
                       </a>
                    </div>
                </div>
                
                
                
                
                
                <div class="col-lg-3 col-md-3 col-sm-6 col-6 pl-md-1">
                    <div class="card card-stats four">
                        <a class="dashbox" href="#">
                        <div class="card-header card-header-warning card-header-icon">
                            <div class="card-icon">
                                <img src="../assets/backend/images/arrow_icon11.svg" alt="">
                                
                            </div>
                            <p class="card-category"><?php echo get_phrase('Male Buffalo'); ?></p>
                            <h3 class="card-title"> 
                            <?php
                              echo $male
            
                              ?>
                              </h3>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                                <i class="fa fa-clock-o"></i> Just Updated
                            </div>
                        </div>
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-3 col-sm-6 col-6 pl-md-1">
                    <div class="card card-stats five">
                        <a class="dashbox" href="#">
                        <div class="card-header card-header-warning card-header-icon">
                            <div class="card-icon">
                                
                                <img src="../assets/backend/images/arrow_icon12.svg">
                            </div>
                            <p class="card-category"><?php echo get_phrase('Female Buffalo'); ?></p>
                            <h3 class="card-title"> 
                            <?php
                              echo $female
            
                              ?>
                              </h3>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                                <i class="fa fa-clock-o"></i> Just Updated
                            </div>
                        </div>
                        </a>
                    </div>
                </div>
                
                
                <div class="col-lg-3 col-md-3 col-sm-6 col-6 pl-md-1">
                    <div class="card card-stats one">
                        <a class="dashbox" href="#">
                        <div class="card-header card-header-warning card-header-icon">
                            <div class="card-icon">
                               <img src="../assets/backend/images/arrow_icon13.svg" alt="" >
                            </div>
                            <p class="card-category"><?php echo get_phrase('Transferred'); ?></p>
                            <h3 class="card-title"> 
                            <?php
                              echo $transferred
            
                              ?>
                              </h3>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                                <i class="fa fa-clock-o"></i> Just Updated
                            </div>
                        </div>
                        </a>
                    </div>
                </div>                
                
                <?php /*<div class="col-lg-4 col-md-4 col-sm-4 col-6">
                    <div class="card card-stats one gradian_bg4">
                        <a class="dashbox" href="#">
                        <div class="card-header card-header-warning card-header-icon">
                            <div class="card-icon">
                                <img src="../assets/backend/images/cattle_icon2.png" alt="">
                            </div>
                            <p class="card-category"><?php echo get_phrase('Veterinarian\'s Registered'); ?></p>
                            <h3 class="card-title"> 
                            <?php
                              echo $doctor_count
            
                              ?>
                              </h3>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                                <i class="fa fa-clock-o"></i> Just Updated
                            </div>
                        </div>
                        </a>
                    </div>
                </div>
                
                
                <div class="col-lg-4 col-md-4 col-sm-4 col-6">
                    <div class="card card-stats two gradian_bg5">
                        <a class="dashbox" href="#">
                        <div class="card-header card-header-warning card-header-icon">
                            <div class="card-icon">
                                <img src="../assets/backend/images/cattle_icon5.png" alt="">
                            </div>
                            <p class="card-category"><?php echo get_phrase('Slaughtering Buffalo'); ?></p>
                            <h3 class="card-title"> 
                            <?php
                              echo $slaughtering
            
                              ?>
                              </h3>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                                <i class="fa fa-clock-o"></i> Just Updated
                            </div>
                        </div>
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-4 col-sm-4 col-6">
                    <div class="card card-stats one gradian_bg6">
                        <a class="dashbox" href="#">
                        <div class="card-header card-header-warning card-header-icon">
                            <div class="card-icon">
                               <img src="../assets/backend/images/cattle_icon6.png" alt="">
                            </div>
                            <p class="card-category"><?php echo get_phrase('Selling Buffalo'); ?></p>
                            <h3 class="card-title"> 
                            <?php
                              echo $sell
            
                              ?>
                              </h3>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                                <i class="fa fa-clock-o"></i> Just Updated
                            </div>
                        </div>
                        </a>
                    </div>
                </div>*/?>
                
                
                
                
                
            </div>
            
        </div>