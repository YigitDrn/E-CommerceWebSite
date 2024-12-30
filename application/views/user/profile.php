<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">Profil Bilgilerim</h3>
                </div>
                <div class="card-body">
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success">
                            <?php echo $this->session->flashdata('success'); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?php echo $this->session->flashdata('error'); ?>
                        </div>
                    <?php endif; ?>

                    <?php echo form_open('user/profile', ['class' => 'needs-validation', 'novalidate' => true]); ?>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Ad Soyad</label>
                            <input type="text" 
                                   class="form-control <?php echo form_error('name') ? 'is-invalid' : ''; ?>" 
                                   id="name" 
                                   name="name" 
                                   value="<?php echo set_value('name', $user['name']); ?>" 
                                   required>
                            <?php echo form_error('name', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">E-posta Adresi</label>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   value="<?php echo $user['email']; ?>" 
                                   disabled>
                            <div class="form-text">E-posta adresi değiştirilemez.</div>
                        </div>

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Mevcut Şifre</label>
                            <input type="password" 
                                   class="form-control <?php echo form_error('current_password') ? 'is-invalid' : ''; ?>" 
                                   id="current_password" 
                                   name="current_password" 
                                   required>
                            <?php echo form_error('current_password', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">Yeni Şifre (Opsiyonel)</label>
                            <input type="password" 
                                   class="form-control <?php echo form_error('new_password') ? 'is-invalid' : ''; ?>" 
                                   id="new_password" 
                                   name="new_password">
                            <?php echo form_error('new_password', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>

                        <div class="mb-3">
                            <label for="new_password_confirm" class="form-label">Yeni Şifre Tekrar</label>
                            <input type="password" 
                                   class="form-control <?php echo form_error('new_password_confirm') ? 'is-invalid' : ''; ?>" 
                                   id="new_password_confirm" 
                                   name="new_password_confirm">
                            <?php echo form_error('new_password_confirm', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>

                        <button type="submit" class="btn btn-primary">Bilgilerimi Güncelle</button>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div> 