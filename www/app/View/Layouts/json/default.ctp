<?php
header("Pragma: no-cache");
header("Accept Range: bytes");
header('ETag: "ac000000029d8a-61d9-4a565919dbc5a"');
header("Cache-Control: no-store, no-cache, max-age=0, must-revalidate");
header('Content-Type: application/json; charset=utf-8');
header("Keep-Alive:timeout=50, max=98000");
//header("X-JSON: ".$content_for_layout);
echo $content_for_layout;