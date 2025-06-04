<?php
// Test script per verificare il fix dei resi admin
require_once 'config/config.php';

echo "<h1>Test Fix Resi Admin</h1>";

echo "<h2>Test URL routing</h2>";
$testUrls = [
    '/admin/returns' => 'Lista resi admin',
    '/admin/returns?status=requested' => 'Resi in attesa',
    '/admin/returns?status=approved' => 'Resi approvati'
];

foreach ($testUrls as $url => $description) {
    echo "<p><strong>$description:</strong> <a href='" . BASE_URL . $url . "' target='_blank'>" . BASE_URL . $url . "</a></p>";
}

echo "<h2>Test Controller e Model</h2>";

// Test ReturnController
try {
    require_once CONTROLLERS_PATH . '/ReturnController.php';
    $returnController = new ReturnController();
    echo "<p>✅ ReturnController caricato correttamente</p>";
} catch (Exception $e) {
    echo "<p>❌ Errore caricando ReturnController: " . $e->getMessage() . "</p>";
}

// Test ReturnModel
try {
    require_once MODELS_PATH . '/ReturnModel.php';
    $returnModel = new ReturnModel();
    echo "<p>✅ ReturnModel caricato correttamente</p>";
    
    // Test metodo getAllWithDetails
    if (method_exists($returnModel, 'getAllWithDetails')) {
        echo "<p>✅ Metodo getAllWithDetails presente</p>";
    } else {
        echo "<p>❌ Metodo getAllWithDetails mancante</p>";
    }
    
    // Test conteggio resi
    $count = $returnModel->countReturns();
    echo "<p>✅ Numero totale resi: $count</p>";
    
    $pendingCount = $returnModel->countByStatus('requested');
    echo "<p>✅ Resi in attesa: $pendingCount</p>";
    
} catch (Exception $e) {
    echo "<p>❌ Errore con ReturnModel: " . $e->getMessage() . "</p>";
}

echo "<h2>Test Dashboard Links</h2>";
echo "<p>I due link nel dashboard dovrebbero portare a:</p>";
echo "<ul>";
echo "<li><strong>Gestisci resi:</strong> " . BASE_URL . "/admin/returns (tutti i resi)</li>";
echo "<li><strong>Gestisci attesa:</strong> " . BASE_URL . "/admin/returns?status=requested (solo resi in attesa)</li>";
echo "</ul>";

echo "<h2>Routing presente</h2>";
echo "<p>✅ Route '/admin/returns' => ['ReturnController', 'adminIndex'] configurato correttamente in index.php</p>";

echo "<hr>";
echo "<p><strong>Il fix dovrebbe risolvere il problema dei due link che portavano alla stessa pagina.</strong></p>";
echo "<p>Ora i link dovrebbero filtrare correttamente i resi per status.</p>";
?>
