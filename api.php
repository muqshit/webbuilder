<?php
header("Content-Type: application/json");
include("config.php");

$data = json_decode(file_get_contents("php://input"), true);
$action = $data["action"] ?? "";

if ($action === "generate") {

    $messages = $data["messages"] ?? [
        ["role"=>"system","content"=>"Return JSON: html, css, js"]
    ];

    $payload = [
        "model" => "openai/gpt-4o-mini",
        "messages" => $messages
    ];

    $ch = curl_init("https://openrouter.ai/api/v1/chat/completions");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer " . OPENROUTER_API_KEY,
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

    $response = curl_exec($ch);
    curl_close($ch);

    echo $response;
    exit;
}

if ($action === "deploy") {

    $html = $data["html"];
    $css = $data["css"];
    $js = $data["js"];

    $full = "<!DOCTYPE html>
<html><head><style>$css</style></head>
<body>$html<script>$js</script></body></html>";

    $deploy = [
        "name" => "ai-site-" . time(),
        "files" => [
            ["file"=>"index.html","data"=>$full]
        ]
    ];

    $ch = curl_init("https://api.vercel.com/v13/deployments");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer " . VERCEL_TOKEN,
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($deploy));

    $response = curl_exec($ch);
    curl_close($ch);

    echo $response;
    exit;
}
?>
