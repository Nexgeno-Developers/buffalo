<audio id="success_sound2" src="<?php echo base_url('uploads/sound/success.wav') ?>"></audio>
<audio id="danger_sound2" src="<?php echo base_url('uploads/sound/danger.wav') ?>"></audio>

<!-- Footer Start -->
<footer class="footer mt-4 text-center" style="display: contents;">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <b style="color:#000">For support</b> : <a href="tel:919029075525" class="txtfoo">+91 90290 75525</a> | <b style="color:#000">For query</b> : <a href="tel:919773375525" class="txtfoo"> +91 97733 75525</a> | <a href="mailto:support@makent.in" class="txtfoo">support@nexgeno.in</a>
            </div>
        </div>
    </div>
</footer>
<!-- end Footer -->



<div class="menu_bottom_fixed">
   <div class="metismenu side-nav side-nav-light">
       <div class="fiexd_width">
          <li class="side-nav-item">
          <a href="<?php echo site_url($controller.'/dashboard'); ?>" class="side-nav-link">
          <i class="mdi mdi-home"></i>
            <span> <?php echo get_phrase('dashboard'); ?> </span>
          </a>
        </li>
       </div>

       <div class="fiexd_width">
       <?php if(access('manage_vyapari')){ ?>
    <li class="side-nav-item">
      <a href="<?php echo site_url($controller.'/manage_vyapari'); ?>" class="side-nav-link">
        <i class="mdi mdi-book-open-page-variant"></i>
        <span> <?php echo get_phrase('vyapari'); ?> </span>
      </a>
    </li>  
    <?php } ?>
       </div>

       <div class="fiexd_width">
       <?php if(access('exit_verification')){ ?>
    <li class="side-nav-item">
      <a href="<?php echo site_url($controller.'/exit_verification'); ?>" class="side-nav-link">
        <i class="mdi mdi-barcode-scan"></i>
        <span> <?php echo get_phrase('security'); ?> </span>
      </a>
    </li>
    <?php } ?>
       </div>


       <div class="fiexd_width">
       <?php if(access('manage_admins')){ ?>
    <li class="side-nav-item">
      <a href="<?php echo site_url($controller.'/manage_admins'); ?>" class="side-nav-link">
        <i class="mdi mdi-account-group"></i>
        <span> <?php echo get_phrase('Users'); ?> </span>
      </a>
    </li>  
    <?php } ?>
       </div>


       <div class="fiexd_width">
       <?php if(access('manage_settings')){ ?>
  <li class="side-nav-item">
    <a href="<?php echo site_url($controller.'/cattle_prebooking'); ?>" class="side-nav-link">
      <i class="mdi mdi-database"></i>
      <span> <?php echo get_phrase('Cattle'); ?> </span>
    </a>
  </li> 
  <?php } ?> 
       </div>
       </div>
   </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
  const menu = document.querySelector('.menu_bottom_fixed');
  let lastScrollTop = 0;
  
  window.addEventListener('scroll', function() {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    
    // Show menu if scrolling down more than 5px
    if (scrollTop > lastScrollTop && scrollTop > 5) {
      menu.classList.add('visible');
    } 
    // Hide menu if scrolling up
    else if (scrollTop < lastScrollTop) {
      menu.classList.remove('visible');
    }
    
    lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
  });
  
  // Also show menu if page loads with existing scroll
  if (window.pageYOffset > 5) {
    menu.classList.add('visible');
  }
});
</script>