<?php
$pageTitle = 'Modifica profilo';
include VIEWS_PATH . '/layouts/header.php';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/">Home</a></li>
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/profile">Profilo</a></li>
        <li class="breadcrumb-item active">Modifica profilo</li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Modifica profilo</h5>
            </div>
            <div class="card-body">
                <form action="<?= BASE_URL ?>/profile/update" method="POST">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="<?= $user['first_name'] ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="form-label">Cognome</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value="<?= $user['last_name'] ?>">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" value="<?= $user['email'] ?>" disabled>
                        <div class="form-text">L'email non può essere modificata.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Telefono</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?= $user['phone'] ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Indirizzo</label>
                        <input type="text" class="form-control" id="address" name="address" value="<?= $user['address'] ?>">
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="city" class="form-label">Città</label>
                            <input type="text" class="form-control" id="city" name="city" value="<?= $user['city'] ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="postal_code" class="form-label">CAP</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code" value="<?= $user['postal_code'] ?>">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="country" class="form-label">Paese</label>
                        <input type="text" class="form-control" id="country" name="country" value="<?= $user['country'] ?: 'Italia' ?>">
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="<?= BASE_URL ?>/profile" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Torna al profilo
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Salva modifiche
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>