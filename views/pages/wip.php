<?php
$pageTitle = 'Work in Progress';
include VIEWS_PATH . '/layouts/header.php';
?>

<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="mb-4">
                <i class="fas fa-hard-hat fa-5x text-warning"></i>
            </div>
            
            <h1 class="display-4 mb-4">Ci stiamo lavorando!</h1>
            
            <div class="card mb-5">
                <div class="card-body py-5">
                    <h2>Pagina in costruzione</h2>
                    <p class="lead">Stiamo preparando qualcosa di speciale per te. Torna presto per scoprire le novità!</p>
                    <div class="progress mb-4" style="height: 25px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" role="progressbar" style="width: 75%;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">75% Completato</div>
                    </div>
                </div>
            </div>
            
            <h3 class="mb-4">Cosa stiamo preparando:</h3>
            
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <i class="fas fa-gift fa-3x text-primary mb-3"></i>
                            <h4>Nuovi prodotti esclusivi</h4>
                            <p>Stiamo selezionando prodotti unici di alta qualità per arricchire la nostra offerta.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <i class="fas fa-truck fa-3x text-primary mb-3"></i>
                            <h4>Opzioni di spedizione veloce</h4>
                            <p>Stiamo migliorando il nostro sistema di spedizione per consegne ancora più rapide.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <i class="fas fa-mobile-alt fa-3x text-primary mb-3"></i>
                            <h4>App mobile</h4>
                            <p>Stiamo sviluppando un'app per rendere lo shopping ancora più comodo.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <i class="fas fa-user-friends fa-3x text-primary mb-3"></i>
                            <h4>Programma fedeltà</h4>
                            <p>Stiamo creando un nuovo programma per premiare i clienti più fedeli.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-5">
                <h3 class="mb-4">Vuoi essere il primo a saperne di più?</h3>
                <p>Iscriviti alla nostra newsletter per ricevere aggiornamenti esclusivi!</p>
                
                <form class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" placeholder="La tua email" aria-label="La tua email">
                            <button class="btn btn-primary" type="button">Iscriviti</button>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="mt-5">
                <a href="<?= BASE_URL ?>/" class="btn btn-lg btn-outline-primary">Torna alla Home</a>
            </div>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>