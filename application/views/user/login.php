<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">Giriş Yap</h3>
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

                    <?php echo form_open('user/login', ['class' => 'needs-validation', 'novalidate' => true]); ?>
                        
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
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Beni Hatırla</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Giriş Yap</button>
                    <?php echo form_close(); ?>

                    <div class="mt-3 text-center">
                        <p class="mb-1">
                            <a href="<?php echo base_url('user/forgot_password'); ?>">Şifremi Unuttum</a>
                        </p>
                        <p class="mb-0">
                            Hesabınız yok mu? 
                            <a href="<?php echo base_url('user/register'); ?>">Kayıt Ol</a>
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