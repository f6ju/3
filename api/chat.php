<?php
/**
 * ============================================
 * CHAT API - Kommuniserer med OpenAI
 * ============================================
 * 
 * Denne filen håndterer kommunikasjon med OpenAI API.
 * Elevene trenger vanligvis ikke å endre denne filen.
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Håndter preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Kun tillat POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Kun POST-forespørsler er tillatt']);
    exit;
}

// Last inn konfigurasjon
require_once __DIR__ . '/../config.php';

// Hent input fra request
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['message']) || empty(trim($input['message']))) {
    http_response_code(400);
    echo json_encode(['error' => 'Ingen melding mottatt']);
    exit;
}

$userMessage = trim($input['message']);

// Hent samtalehistorikk hvis sendt med
$conversationHistory = $input['history'] ?? [];

// Bygg meldingsarray for OpenAI
$messages = [];

// Legg til system prompt først
$messages[] = [
    'role' => 'system',
    'content' => SYSTEM_PROMPT
];

// Legg til samtalehistorikk
foreach ($conversationHistory as $msg) {
    if (isset($msg['role']) && isset($msg['content'])) {
        $messages[] = [
            'role' => $msg['role'],
            'content' => $msg['content']
        ];
    }
}

// Legg til ny brukermelding
$messages[] = [
    'role' => 'user',
    'content' => $userMessage
];

// Kall OpenAI API
try {
    $response = callOpenAI($messages);
    echo json_encode([
        'success' => true,
        'response' => $response,
        'agent' => AGENT_NAME
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Kunne ikke få svar fra AI: ' . $e->getMessage()
    ]);
}

/**
 * Kaller OpenAI API og returnerer svaret
 */
function callOpenAI($messages) {
    $data = [
        'model' => OPENAI_MODEL,
        'messages' => $messages,
        'max_tokens' => OPENAI_MAX_TOKENS,
        'temperature' => OPENAI_TEMPERATURE
    ];
    
    $ch = curl_init();
    
    curl_setopt_array($ch, [
        CURLOPT_URL => 'https://api.openai.com/v1/chat/completions',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . OPENAI_API_KEY
        ],
        CURLOPT_TIMEOUT => 120,
        CURLOPT_CONNECTTIMEOUT => 30
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    $errno = curl_errno($ch);
    
    curl_close($ch);
    
    if ($errno) {
        throw new Exception('Tilkoblingsfeil (' . $errno . '): ' . $error);
    }
    
    if (!$response) {
        throw new Exception('Ingen respons fra OpenAI API');
    }
    
    if ($httpCode !== 200) {
        $errorData = json_decode($response, true);
        $errorMsg = $errorData['error']['message'] ?? 'Ukjent feil (HTTP ' . $httpCode . ')';
        throw new Exception($errorMsg);
    }
    
    $result = json_decode($response, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Kunne ikke parse JSON-respons: ' . json_last_error_msg());
    }
    
    if (!isset($result['choices'][0]['message']['content'])) {
        throw new Exception('Ugyldig respons-struktur fra OpenAI');
    }
    
    return $result['choices'][0]['message']['content'];
}
