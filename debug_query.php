<?php
try {
    $pdo = new PDO('sqlite:database/database.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "--- Checking ILPIs ---\n";
    $stmt = $pdo->query("SELECT id, nome, status FROM ilpis");
    $ilpis = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($ilpis);

    echo "\n--- Checking Leitos (Available) ---\n";
    $stmt = $pdo->query("SELECT id, ilpi_id, status FROM leitos WHERE status = 'disponivel'");
    $leitos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($leitos);

    echo "\n--- Checking Renovacoes (Valid) ---\n";
    $stmt = $pdo->query("SELECT id, ilpi_id, data_vencimento, DATE('now') as now_utc, date('now', 'localtime') as now_local FROM renovacoes");
    $renovacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($renovacoes);

    echo "\n--- Testing Query Logic ---\n";
    $sql = "SELECT i.id, i.nome 
            FROM ilpis i
            JOIN leitos l ON i.id = l.ilpi_id
            WHERE l.status = 'disponivel' AND i.status = 'ativo'
            AND EXISTS (
                SELECT 1 FROM renovacoes r 
                WHERE r.ilpi_id = i.id 
                AND r.data_vencimento >= DATE('now')
            )
            GROUP BY i.id";
    $stmt = $pdo->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Query Result:\n";
    print_r($result);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
