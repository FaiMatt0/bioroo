<?php
if (!defined('VIEWS_PATH')) {
    // Load config when accessed directly
    require_once '../../config/config.php';
}

$pageTitle = 'Sostenibilità';
include VIEWS_PATH . '/layouts/header.php';
?>

<div class="sustainability-hero py-5 mb-5 text-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4">Il nostro impegno per la sostenibilità</h1>
                <p class="lead">Costruiamo un futuro migliore attraverso pratiche commerciali sostenibili ed etiche</p>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <section class="mb-5">
                <h2 class="mb-4">La nostra visione</h2>
                <p>In [Nome Marketplace], crediamo che le aziende abbiano la responsabilità di operare in modo sostenibile e di contribuire positivamente alla società e all'ambiente. La nostra visione è quella di creare un marketplace che non solo offra prodotti eccezionali, ma che lo faccia in modo responsabile e sostenibile.</p>
                
                <p>Ci impegniamo a ridurre continuamente il nostro impatto ambientale, a promuovere pratiche commerciali etiche e a sostenere le comunità in cui operiamo. Questo impegno guida ogni aspetto della nostra attività, dalla selezione dei prodotti alla gestione della catena di approvvigionamento, fino alle operazioni quotidiane.</p>
            </section>
            
            <section class="mb-5">
                <h2 class="mb-4">I nostri obiettivi di sostenibilità</h2>
                
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-leaf fa-2x text-success me-3"></i>
                                    <h5 class="card-title mb-0">Riduzione emissioni</h5>
                                </div>
                                <p class="card-text">Ci impegniamo a ridurre le nostre emissioni di CO2 del 50% entro il 2030 e a raggiungere la neutralità carbonica entro il 2040.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-recycle fa-2x text-success me-3"></i>
                                    <h5 class="card-title mb-0">Riduzione rifiuti</h5>
                                </div>
                                <p class="card-text">Entro il 2025, tutti i nostri imballaggi saranno completamente riciclabili, riutilizzabili o compostabili.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-building fa-2x text-success me-3"></i>
                                    <h5 class="card-title mb-0">Fornitori responsabili</h5>
                                </div>
                                <p class="card-text">Collaboriamo solo con fornitori che condividono i nostri valori e rispettano i nostri rigidi standard etici e ambientali.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-users fa-2x text-success me-3"></i>
                                    <h5 class="card-title mb-0">Impatto sociale</h5>
                                </div>
                                <p class="card-text">Doniamo l'1% dei nostri profitti a organizzazioni locali che lavorano per proteggere l'ambiente e sostenere le comunità vulnerabili.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <section class="mb-5">
                <h2 class="mb-4">Le nostre iniziative</h2>
                
                <div class="accordion" id="sustainabilityAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Imballaggi sostenibili
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#sustainabilityAccordion">
                            <div class="accordion-body">
                                <p>Abbiamo riprogettato tutti i nostri imballaggi per ridurre gli sprechi e l'impatto ambientale. Utilizziamo materiali riciclati e biodegradabili e abbiamo eliminato completamente la plastica monouso.</p>
                                <p>I nostri imballaggi sono progettati per essere riutilizzati o riciclati facilmente, contribuendo così a ridurre i rifiuti nelle discariche.</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Energia rinnovabile
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#sustainabilityAccordion">
                            <div class="accordion-body">
                                <p>I nostri uffici e magazzini sono alimentati al 100% da energia rinnovabile. Abbiamo installato pannelli solari sui tetti dei nostri edifici e acquistiamo l'energia rimanente da fornitori di energia rinnovabile certificati.</p>
                                <p>Questo ci ha permesso di ridurre significativamente le nostre emissioni di CO2 e di contribuire alla transizione verso un'economia a basse emissioni di carbonio.</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Programma di riforestazione
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#sustainabilityAccordion">
                            <div class="accordion-body">
                                <p>Per ogni ordine effettuato sul nostro marketplace, piantiamo un albero attraverso la nostra partnership con organizzazioni di riforestazione. Questo programma ci ha permesso di piantare più di 50.000 alberi negli ultimi due anni.</p>
                                <p>Gli alberi non solo assorbono CO2 dall'atmosfera, ma forniscono anche habitat per la fauna selvatica e sostengono le comunità locali.</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                Prodotti eco-friendly
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#sustainabilityAccordion">
                            <div class="accordion-body">
                                <p>Diamo priorità ai prodotti che hanno un impatto ambientale ridotto. I nostri criteri di selezione includono materiali sostenibili, processi di produzione efficienti dal punto di vista energetico e durata del prodotto.</p>
                                <p>Inoltre, abbiamo creato una categoria speciale "Eco-friendly" che rende più facile per i clienti trovare prodotti che rispettano l'ambiente.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <section class="mb-5">
                <h2 class="mb-4">I nostri progressi</h2>
                
                <div class="progress-container mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="mb-0">Riduzione emissioni di CO2</h5>
                        <span class="badge bg-success">35%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 35%;" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <small class="text-muted">Obiettivo: 50% entro il 2030</small>
                </div>
                
                <div class="progress-container mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="mb-0">Imballaggi sostenibili</h5>
                        <span class="badge bg-success">70%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 70%;" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <small class="text-muted">Obiettivo: 100% entro il 2025</small>
                </div>
                
                <div class="progress-container mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="mb-0">Energia rinnovabile</h5>
                        <span class="badge bg-success">100%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <small class="text-muted">Obiettivo raggiunto nel 2022</small>
                </div>
                
                <div class="progress-container">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="mb-0">Fornitori certificati</h5>
                        <span class="badge bg-success">85%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 85%;" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <small class="text-muted">Obiettivo: 100% entro il 2026</small>
                </div>
            </section>
            
            <section>
                <h2 class="mb-4">Il nostro impegno per il futuro</h2>
                <p>La sostenibilità è un percorso, non una destinazione. Continuiamo a imparare, ad adattarci e a migliorare le nostre pratiche per ridurre il nostro impatto ambientale e contribuire a costruire un futuro più sostenibile.</p>
                
                <p>Ci impegniamo a essere trasparenti sui nostri progressi e sulle sfide che affrontiamo. Ogni anno pubblichiamo un rapporto di sostenibilità che delinea i nostri obiettivi, le azioni intraprese e i risultati ottenuti.</p>
                
                <div class="text-center mt-5">
                    <a href="<?= BASE_URL ?>/sustainability/report" class="btn btn-success me-2">Scarica il rapporto di sostenibilità</a>
                    <a href="<?= BASE_URL ?>/products?category=eco-friendly" class="btn btn-outline-success">Esplora i prodotti eco-friendly</a>
                </div>
            </section>
        </div>
    </div>
</div>

<style>
    .sustainability-hero {
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('<?= BASE_URL ?>/assets/images/sustainability/hero-bg.jpg');
        background-size: cover;
        background-position: center;
        padding: 100px 0;
    }
    
    .progress {
        height: 8px;
        border-radius: 4px;
    }
</style>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>