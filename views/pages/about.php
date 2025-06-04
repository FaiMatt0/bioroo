<?php
if (!defined('VIEWS_PATH')) {
    // Load config when accessed directly
    require_once '../../config/config.php';
}

$pageTitle = 'La nostra storia';
include VIEWS_PATH . '/layouts/header.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="text-center mb-5">La nostra storia</h1>
            
            <div class="mb-5">
                <img src="<?= BASE_URL ?>/assets/images/about/company-history.jpg" alt="La nostra storia" class="img-fluid rounded mb-4">
                
                <h2>Le nostre origini</h2>
                <p>La nostra avventura è iniziata nel 2010, con una semplice idea: creare un marketplace dove i clienti potessero trovare prodotti di alta qualità a prezzi accessibili. Ciò che è iniziato come un piccolo negozio online con pochi prodotti è cresciuto fino a diventare uno dei principali marketplace in Italia.</p>
                
                <p>Il fondatore, Mario Rossi, ha sempre avuto una passione per [INSERIRE SETTORE] e ha voluto condividere questa passione con il mondo, offrendo una selezione curata di prodotti che rispettano i più alti standard di qualità.</p>
            </div>
            
            <div class="mb-5">
                <h2>La nostra missione</h2>
                <p>La nostra missione è semplice: offrire prodotti eccezionali che migliorano la vita quotidiana dei nostri clienti. Ci impegniamo a selezionare solo i migliori prodotti, garantendo qualità, valore e sostenibilità.</p>
                
                <blockquote class="blockquote border-start border-primary border-5 ps-4 my-4">
                    <p>"Non vendiamo semplicemente prodotti, offriamo esperienze che arricchiscono la vita dei nostri clienti."</p>
                    <footer class="blockquote-footer">Mario Rossi, Fondatore</footer>
                </blockquote>
                
                <p>Questo impegno verso l'eccellenza ci ha permesso di crescere costantemente negli anni, ampliando la nostra offerta e raggiungendo sempre più clienti in tutta Italia e in Europa.</p>
            </div>
            
            <div class="mb-5">
                <h2>I nostri valori</h2>
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-star text-primary me-2"></i> Qualità</h5>
                                <p class="card-text">Selezioniamo solo prodotti di alta qualità che soddisfano rigidi standard.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-leaf text-primary me-2"></i> Sostenibilità</h5>
                                <p class="card-text">Ci impegniamo a ridurre il nostro impatto ambientale attraverso pratiche sostenibili.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-users text-primary me-2"></i> Comunità</h5>
                                <p class="card-text">Sosteniamo le comunità locali e promuoviamo pratiche commerciali etiche.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-heart text-primary me-2"></i> Passione</h5>
                                <p class="card-text">Mettiamo passione in tutto ciò che facciamo, dal servizio clienti alla selezione dei prodotti.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mb-5">
                <h2>Il nostro team</h2>
                <p>Il successo del nostro marketplace è il risultato del lavoro di un team appassionato e dedicato. Ogni membro del nostro team condivide la stessa visione e lo stesso impegno verso l'eccellenza.</p>
                
                <div class="row mt-4">
                    <div class="col-md-4 mb-4 text-center">
                        <img src="<?= BASE_URL ?>/assets/images/about/team-1.jpg" alt="Mario Rossi" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                        <h5>Mario Rossi</h5>
                        <p class="text-muted">Fondatore & CEO</p>
                    </div>
                    <div class="col-md-4 mb-4 text-center">
                        <img src="<?= BASE_URL ?>/assets/images/about/team-2.jpg" alt="Laura Bianchi" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                        <h5>Laura Bianchi</h5>
                        <p class="text-muted">Direttrice Operativa</p>
                    </div>
                    <div class="col-md-4 mb-4 text-center">
                        <img src="<?= BASE_URL ?>/assets/images/about/team-3.jpg" alt="Marco Verdi" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                        <h5>Marco Verdi</h5>
                        <p class="text-muted">Responsabile Prodotti</p>
                    </div>
                </div>
            </div>
            
            <div>
                <h2>Il nostro futuro</h2>
                <p>Guardiamo al futuro con ottimismo e determinazione. Continuiamo a cercare nuovi prodotti innovativi e sostenibili da offrire ai nostri clienti, espandendo costantemente la nostra gamma per soddisfare le esigenze in evoluzione del mercato.</p>
                
                <p>Siamo grati a tutti i nostri clienti che ci hanno sostenuto nel corso degli anni e ci impegniamo a continuare a offrire loro un'esperienza di acquisto eccezionale per molti anni a venire.</p>
                
                <div class="text-center mt-4">
                    <a href="<?= BASE_URL ?>/products" class="btn btn-primary btn-lg">Esplora i nostri prodotti</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>