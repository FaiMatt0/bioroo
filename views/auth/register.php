<?php
$pageTitle = 'Registrazione';
include VIEWS_PATH . '/layouts/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Crea un nuovo account</h4>
            </div>
            <div class="card-body">
                <?php if (isset($errors['register'])): ?>
                    <div class="alert alert-danger"><?= $errors['register'] ?></div>
                <?php endif; ?>
                
                <form action="<?= BASE_URL ?>/register" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>" id="username" name="username" value="<?= isset($_POST['username']) ? sanitize($_POST['username']) : '' ?>" required>
                            <?php if (isset($errors['username'])): ?>
                                <div class="invalid-feedback"><?= $errors['username'] ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= isset($_POST['email']) ? sanitize($_POST['email']) : '' ?>" required>
                            <?php if (isset($errors['email'])): ?>
                                <div class="invalid-feedback"><?= $errors['email'] ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" id="password" name="password" required>
                            <?php if (isset($errors['password'])): ?>
                                <div class="invalid-feedback"><?= $errors['password'] ?></div>
                            <?php endif; ?>
                            <div class="form-text">La password deve contenere almeno 8 caratteri.</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="confirm_password" class="form-label">Conferma Password</label>
                            <input type="password" class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>" id="confirm_password" name="confirm_password" required>
                            <?php if (isset($errors['confirm_password'])): ?>
                                <div class="invalid-feedback"><?= $errors['confirm_password'] ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="<?= isset($_POST['first_name']) ? sanitize($_POST['first_name']) : '' ?>">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Cognome</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value="<?= isset($_POST['last_name']) ? sanitize($_POST['last_name']) : '' ?>">
                        </div>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                        <label class="form-check-label" for="terms">Accetto i <a href="<?= BASE_URL ?>/terms">Termini e condizioni</a> e la <a href="<?= BASE_URL ?>/privacy">Privacy Policy</a></label>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Registrati</button>
                    </div>
                </form>
                
                <div class="mt-3 text-center">
                    <p>Hai già un account? <a href="<?= BASE_URL ?>/login">Accedi</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>