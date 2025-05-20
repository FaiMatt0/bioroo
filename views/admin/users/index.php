<?php
$pageTitle = 'Gestione Utenti';
include VIEWS_PATH . '/layouts/header.php';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/admin">Dashboard Admin</a></li>
        <li class="breadcrumb-item active">Gestione Utenti</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Gestione Utenti</h1>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="users-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Nome</th>
                        <th>Cognome</th>
                        <th>Ruolo</th>
                        <th>Data registrazione</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td><?= $user['username'] ?></td>
                            <td><?= $user['email'] ?></td>
                            <td><?= $user['first_name'] ?: '-' ?></td>
                            <td><?= $user['last_name'] ?: '-' ?></td>
                            <td>
                                <?php if ($user['is_admin']): ?>
                                    <span class="badge bg-danger">Amministratore</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Cliente</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                                        Azioni
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a href="<?= BASE_URL ?>/admin/users/view/<?= $user['id'] ?>" class="dropdown-item">
                                                <i class="fas fa-eye me-2"></i> Visualizza
                                            </a>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#changeRoleModal<?= $user['id'] ?>">
                                                <i class="fas fa-user-tag me-2"></i> Cambia ruolo
                                            </button>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a href="<?= BASE_URL ?>/admin/users/orders/<?= $user['id'] ?>" class="dropdown-item">
                                                <i class="fas fa-shopping-cart me-2"></i> Visualizza ordini
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                
                                <!-- Modal Cambia Ruolo -->
                                <div class="modal fade" id="changeRoleModal<?= $user['id'] ?>" tabindex="-1" aria-labelledby="changeRoleModalLabel<?= $user['id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="changeRoleModalLabel<?= $user['id'] ?>">Cambia ruolo utente</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="<?= BASE_URL ?>/admin/users/change-role" method="POST">
                                                <div class="modal-body">
                                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                                    
                                                    <p>Utente: <strong><?= $user['username'] ?></strong> (<?= $user['email'] ?>)</p>
                                                    
                                                    <div class="form-check mb-3">
                                                        <input class="form-check-input" type="checkbox" id="is_admin<?= $user['id'] ?>" name="is_admin" <?= $user['is_admin'] ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="is_admin<?= $user['id'] ?>">
                                                            Amministratore
                                                        </label>
                                                        <div class="form-text">Gli amministratori hanno accesso completo al pannello di amministrazione.</div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                                                    <button type="submit" class="btn btn-primary">Salva modifiche</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inizializza DataTable per la tabella utenti
        $('#users-table').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Italian.json"
            },
            "order": [[0, "desc"]], // Ordina per ID in modo decrescente
            "pageLength": 10
        });
    });
</script>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>