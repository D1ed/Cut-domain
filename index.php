<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Обрезка доменов из URL</title>
    <style>
        body { font-family: sans-serif; margin: 2rem; }
        textarea { width: 100%; height: 200px; }
        input[type="submit"] { margin-top: 1rem; padding: 0.5rem 1rem; }
        pre { background: #f4f4f4; padding: 1rem; white-space: pre-wrap; }
    </style>
</head>
<body>
    <h1>Обрезка доменов из URL</h1>
    <form method="post">
        <label for="urls">Вставьте список URL (по одному на строку):</label><br>
        <textarea name="urls" id="urls"><?php echo isset($_POST['urls']) ? htmlspecialchars($_POST['urls']) : ''; ?></textarea><br>
        <input type="submit" value="Обрезать до доменов">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["urls"])) {
        function getDomain($url) {
            // Убедимся, что URL имеет схему
            if (!preg_match('#^https?://#i', $url)) {
                $url = 'http://' . $url;
            }

            $parts = parse_url(trim($url));
            $scheme = isset($parts['scheme']) ? $parts['scheme'] : 'http';
            $host = isset($parts['host']) ? $parts['host'] : '';
            return $host ? $scheme . '://' . $host . '/' : '';
        }

        $lines = explode("\n", $_POST["urls"]);
        $domains = [];

        foreach ($lines as $line) {
            $cleaned = trim($line);
            if (!empty($cleaned)) {
                $domain = getDomain($cleaned);
                if ($domain) {
                    $domains[] = $domain;
                }
            }
        }

        echo "<h2>Результат:</h2><pre>" . implode("\n", $domains) . "</pre>";
    }
    ?>
</body>
</html>
