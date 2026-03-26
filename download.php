<?php
$data = json_decode(file_get_contents("php://input"), true);

$zip = new ZipArchive();
$file = "site.zip";

if ($zip->open($file, ZipArchive::CREATE) === TRUE) {

    $zip->addFromString("index.html", $data["html"]);
    $zip->addFromString("style.css", $data["css"]);
    $zip->addFromString("script.js", $data["js"]);

    $zip->close();

    header("Content-Type: application/zip");
    header("Content-Disposition: attachment; filename=site.zip");
    readfile($file);
    unlink($file);
}
?>
