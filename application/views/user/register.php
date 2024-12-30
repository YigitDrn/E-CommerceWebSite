<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">Kayıt Ol</h3>
                </div>
                <div class="card-body">
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?php echo $this->session->flashdata('error'); ?>
                        </div>
                    <?php endif; ?>

                    <?php echo form_open('user/register', ['class' => 'needs-validation', 'novalidate' => true]); ?>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Ad Soyad</label>
                            <input type="text" 
                                   class="form-control <?php echo form_error('name') ? 'is-invalid' : ''; ?>" 
                                   id="name" 
                                   name="name" 
                                   value="<?php echo set_value('name'); ?>" 
                                   required>
                            <?php echo form_error('name', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">E-posta Adresi</label>
                            <input type="email" 
                                   class="form-control <?php echo form_error('email') ? 'is-invalid' : ''; ?>" 
                                   id="email" 
                                   name="email" 
                                   value="<?php echo set_value('email'); ?>" 
                                   required>
                            <?php echo form_error('email', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Şifre</label>
                            <input type="password" 
                                   class="form-control <?php echo form_error('password') ? 'is-invalid' : ''; ?>" 
                                   id="password" 
                                   name="password" 
                                   required>
                            <?php echo form_error('password', '<div class="invalid-feedback">', '</div>'); ?>
                            <div class="form-text">Şifreniz en az 6 karakter uzunluğunda olmalıdır.</div>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirm" class="form-label">Şifre Tekrar</label>
                            <input type="password" 
                                   class="form-control <?php echo form_error('password_confirm') ? 'is-invalid' : ''; ?>" 
                                   id="password_confirm" 
                                   name="password_confirm" 
                                   required>
                            <?php echo form_error('password_confirm', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Kayıt Ol</button>
                    <?php echo form_close(); ?>

                    <div class="mt-3 text-center">
                        <p class="mb-0">
                            Zaten hesabınız var mı? 
                            <a href="<?php echo base_url('user/login'); ?>">Giriş Yap</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Form doğrulama için Bootstrap validation
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()
</script> 