	<aside class="main-sidebar">
		<section class="sidebar">
      <div class="user-panel">
        <div class="pull-right image">
          <img src="<?php echo base_url(); ?>assets/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $this->session->userdata('name'); ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> آنلاین</a>
        </div>
      </div>
      <ul class="sidebar-menu">
        <li class="header text-green">منوی کاربری</li>
        <li class="<?php if($this->uri->segment(1) == '') echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>">
            <i class="fa fa-home text-green"></i> <span>پیشخوان</span>
          </a>
        </li>
        <li class="<?php if($this->uri->segment(1) == 'help') echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>help">
            <i class="fa fa-medkit text-green"></i> <span>راهنمای نگارش</span>
          </a>
        </li>
		<li class="<?php if($this->uri->segment(1) == 'translate') echo 'active'; ?>">
			<a href="<?php echo base_url(); ?>translate">
				<i class="fa fa-pencil text-green"></i><span> ترجمه</span>
			</a>
        </li>
        <li class="<?php if($this->uri->segment(1) == 'edit') echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>edit">
            <i class="fa fa-edit text-green"></i> <span>بازبینی</span>
          </a>
        </li>
        <li class="<?php if($this->uri->segment(1) == 'last24') echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>last24">
            <i class="fa fa-clock-o text-green"></i> <span>ترجمه‌های ۲۴ ساعت گذشته</span>
          </a>
        </li>
		<?php $num_temp = $this->db->query("SELECT count(*) AS num_temp FROM sentence_bank WHERE temporary = 1 AND translator = ?", $this->session->userdata('user_id'))->row_array()['num_temp']; ?>
        <li class="<?php if($this->uri->segment(1) == 'temporary') echo 'active'; ?>">
			<a href="<?php echo base_url(); ?>temporary">
				<i class="fa fa-floppy-o text-green"></i> 
				<span>لیست ترجمه های موقت</span>
				<?php if($num_temp > 0) echo '<small class="label pull-left bg-red">'.$num_temp.'</small>'; ?>
				</a>
		</li>

        <!-- <li class="treeview">
          <a href="<?php echo base_url(); ?>stats">
            <i class="fa fa-bar-chart text-green"></i> <span>آمار</span>
          </a> 
        </li>-->
        <li class="treeview <?php if($this->uri->segment(1) == 'settings') echo 'active'; ?>">
          <a href="#">
          <i class="fa fa-gears text-orange"></i> <span> تنظیمات</span> <i class="fa fa-angle-left pull-left"></i>
          </a>
          <ul class="treeview-menu">
            <li class=""><a href="<?php echo base_url(); ?>settings/resetpass"><i class="fa fa-key"></i> تغییر کلمه عبور</a></li>
          </ul>
        </li>
		<li class="treeview">
			<a href="<?php echo base_url(); ?>logout">
				<i class="fa fa-sign-out text-yellow"></i> <span>خروج</span>
			</a>
        </li>
        
      <?php if($this->session->userdata('user_type') == 'admin'): ?>
        <li class="header text-red">مدیریت</li>
        <li class="treeview <?php if($this->uri->segment(2) == 'users') echo 'active'; ?>">
          <a href="#">
          <i class="fa fa-user text-red"></i> <span> کاربران</span> <i class="fa fa-angle-left pull-left"></i>
          </a>
          <ul class="treeview-menu">
            <li class=""><a href="<?php echo base_url(); ?>admin/users/new"><i class="fa fa-user-plus"></i> کاربر جدید</a></li>
            <li><a href="<?php echo base_url(); ?>admin/users/list"><i class="fa fa-list"></i> نمایش لیست کاربران</a></li>
          </ul>
        </li>
        <li class="treeview <?php if($this->uri->segment(2) == 'bolten') echo 'active'; ?>"><a href="<?php echo base_url(); ?>admin/bolten"><i class="fa fa-bell text-red"></i> <span>اطلاعیه‌ها</span></a></li>
        <li <?php if($this->uri->segment(2) == 'add') echo 'active'; ?>>
			<a href="<?php echo base_url(); ?>admin/add">
				<i class="fa fa-plus text-red"></i> <span>اضافه کردن جملات جدید</span>
			</a>
		</li>
        <li class="treeview <?php if($this->uri->segment(2) == 'add2db') echo 'active'; ?>">
			<a href="<?php echo base_url(); ?>admin/add2db">
				<i class="fa fa-bank text-red"></i> <span>ثبت جملات ترجمه شده</span>
			</a> 
		</li>
        <li class="treeview <?php if($this->uri->segment(2) == 'groups') echo 'active'; ?>">
			<a href="<?php echo base_url(); ?>admin/groups">
				<i class="fa fa-group text-red"></i> <span>گروه ها</span>
			</a>
		</li>
		<?php $num_reported = $this->db->query("SELECT count(*) AS num_reported FROM sentence_bank WHERE reported_by IS NOT NULL AND removed = 0 AND sentence_group = ?", $this->session->userdata('user_group'))->row_array()['num_reported']; ?>
        <li class="treeview <?php if($this->uri->segment(2) == 'reports') echo 'active'; ?>">
			<a href="<?php echo base_url(); ?>admin/reports">
				<i class="fa fa-ban text-red"></i> <span>جملات گزارش شده</span> <?php if($num_reported > 0) echo '<small class="label pull-left bg-red">'.$num_reported.'</small>'; ?>
			</a>
		</li>
      <?php endif ?>
      

      </ul>
    </section>
	</aside>
