<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['url'])) {
    http_response_code(400);
    echo "Bad Request: Missing URL parameter.";
    exit;
}

$url = $_GET['url'];
$allowed_domains = [
    'applis.univ-nc.nc',
];

$parsed_url = parse_url($url);
if (!in_array($parsed_url['host'], $allowed_domains)) {
    http_response_code(403);
    echo "Forbidden: Access to this URL is not allowed.";
    exit;
}

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_SSL_VERIFYPEER => false,
]);
$response = curl_exec($ch);
if ($response === false) {
    http_response_code(500);
    echo "Internal Server Error: Unable to fetch the requested URL.";
} else {
    header("Content-Type: " . curl_getinfo($ch, CURLINFO_CONTENT_TYPE));
    echo $response;
}
curl_close($ch);
?>
