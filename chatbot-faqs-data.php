<?php
require_once 'includes/config.php';
header('Content-Type: application/json');

try {
    $db = getDB();
    $faqs = $db->query("SELECT question, answer, keywords FROM chatbot_faqs WHERE active=1 ORDER BY sort_order, id")->fetchAll();
} catch (Exception $e) {
    $faqs = [];
}

echo json_encode($faqs);
