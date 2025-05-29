<?php 
   $vyapari = $this->db->where('id', $vyapari_id)->get('app_butcher')->row_array();
   if(empty($vyapari))
   {
       redirect(route('dashboard'));
   }
?>
<style>
    div#basic-datatable-0_filter {
    position: absolute;
    right: 13%;
    top: 21px;
}
.vyapari_qrcodes .dt-buttons.btn-group {
    position: absolute;
    right: 15px;
    top: 20px;
}
.apbtn button {
    width: 80%;
}

@media(max-width:767px)
{
h4.page-title.vyapdet.mt-0 {
    padding-bottom: 7px;
}
}
</style>
<!--title-->
<div class="row">
  <div class="col-xl-12">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
          <h4 class="page-title mb-0">
            <i class="mdi mdi-account-circle-outline title_icon"></i> 
            <?php echo get_phrase($page_title); ?>
          </h4>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card mt-2 shadow-sm">
      <div class="card-body">
        <div class="row align-items-center">

          <!-- Profile Image -->
          <div class="col-md-2 col-12 text-center mb-3">
            <?php if (!empty($vyapari['photo'])): ?>
              <a target="_blank" href="<?= base_url('uploads/butcher_photo/' . $vyapari['photo']) . '?' . time(); ?>">
                <img src="<?= base_url('uploads/butcher_photo/' . $vyapari['photo']) . '?' . time(); ?>" class="img-fluid rounded-circle border" style="width: 100px; height: 100px; object-fit: cover;">
              </a>
            <?php else: ?>
              <img src="<?= base_url('assets/images/default-user.png'); ?>" class="img-fluid rounded-circle border" style="width: 100px; height: 100px; object-fit: cover;">
            <?php endif; ?>
          </div>

          <!-- Personal Info -->
          <div class="col-md-10 col-12">
            <div class="row">
              <div class="col-md-3 col-6 mb-2">
                <strong>Butcher ID:</strong> <?= butcher_id($vyapari['id']); ?><br>
                <strong>Name:</strong> <?= $vyapari['name']; ?><br>
                <strong>State:</strong> <?= $vyapari['state']; ?>
              </div>

              <div class="col-md-3 col-6 mb-2">
                <strong>Phone:</strong> <?= $vyapari['phone']; ?><br>
                <strong>Location:</strong> <?= $vyapari['locality']; ?><br>
                <strong>Address:</strong> <?= $vyapari['address']; ?>
              </div>

              <div class="col-md-3 col-6 mb-2">
                <strong>Aadhar No:</strong> <?= $vyapari['aadhar_no']; ?><br>
                <strong>Age:</strong> <?= $vyapari['age']; ?><br>
                <strong>Experience:</strong> <?= $vyapari['experience']; ?> years
              </div>

              <div class="col-md-3 col-6 mb-2">
                <strong>Health Certificate:</strong><br>
                <?php if (!empty($vyapari['health_certificate'])): ?>
                  <a target="_blank" class="btn btn-outline-info btn-sm mt-1" href="<?= base_url('uploads/health_certificate/' . $vyapari['health_certificate']); ?>">
                    <i class="mdi mdi-file-document-outline"></i> View
                  </a>
                <?php else: ?>
                  <span class="text-muted">Not uploaded</span>
                <?php endif; ?>
              </div>
            </div>
          </div>

        </div> <!-- end row -->

        <?php if (access('printid_button')): ?>
          <div class="mt-4 mb-2 text-right">
            <a target="_blank" class="btn btn-success btn-sm" href="<?= base_url('superadmin/manage_butcher/print/' . $vyapari['id']); ?>">
              <i class="mdi mdi-printer"></i> Print ID
            </a>
          </div>
        <?php endif; ?>

      </div> <!-- end card-body -->
    </div>
  </div>
</div>
