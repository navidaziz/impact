 <!-- <div class="row">
 <div class="row">

     <div class="col-md-3">
         <ul class="breadcrumb">
             <li>
                 <i class="fa fa-home"></i>
                 <a href="<?php echo site_url($this->session->userdata("role_homepage_uri")); ?>"><?php echo $this->lang->line('Home'); ?></a>
             </li>
             <li><?php echo $title; ?></li>
         </ul>

     </div>



 </div>


 </div>
 </div>
 </div> -->

 <div class="row" style="padding-top: 10px;">

     <div class="col-md-12">
         <div class="box border blue" id="messenger">

             <div class="box-body">

                 <style>
                     .panel {
                         transform: translateY(-5px);
                         box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
                         transition: all 0.3s ease;
                         color: #31708F;
                         background-color: #D9ECF7;
                     }

                     .panel:hover {
                         transform: translateY(-5px);
                         box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
                         transition: all 0.3s ease;
                         color: #D9ECF7;
                         background-color: #31708F;
                     }

                     .panel.active {
                         color: #31708F;
                         background-color: #D9ECF7;
                         border-color: #31708F;
                     }

                     .panel.active .menu-text {
                         color: #fff;
                     }

                     .panel.active i {
                         color: #fff;
                     }
                 </style>
                 <h4 style="font-weight: bold;"><i class="fa fa-home"></i> Application Module list</h4>
                 <hr />
                 <div class="row">

                     <?php foreach ($menu_arr as $controller_id => $controller_data): ?>
                         <?php $cn_class = ($controller_name == $controller_data['controller_uri']) ? "active" : ""; ?>
                         <a href="<?php echo site_url(ADMIN_DIR . $controller_data['controller_uri'] . "/" . $action['action_uri']); ?>" class="text-decoration-none text-dark">
                             <div class="col-md-3 col-sm-6">
                                 <div class="panel panel-default <?= $cn_class ?>">
                                     <div class="panel-body text-center">

                                         <i class="fa <?= $controller_data['controller_icon'] ?> fa-3x mb-3"></i>
                                         <h4 class="menu-text" style=" font-weight:bold;  margin-top: 10px;"><?= $controller_data['controller_title'] ?></h4>
                                         <span class="arrow text-muted"><i class="fa fa-arrow-right"></i></span>

                                     </div>
                                 </div>
                         </a>
                 </div>
             <?php endforeach; ?>
             </div>


         </div>
     </div>
 </div>
 </div>