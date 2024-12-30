<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <title>PHP Hatası</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background: #f5f5f5;
        }
        .error-container {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: 0 auto;
        }
        h1 {
            color: #d9534f;
            margin-top: 0;
        }
        .error-line {
            background: #f8d7da;
            padding: 10px;
            margin: 10px 0;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>PHP Hatası</h1>
        <div class="error-line">
            <p><strong>Severity:</strong> <?php echo $severity; ?></p>
            <p><strong>Message:</strong> <?php echo $message; ?></p>
            <p><strong>Filename:</strong> <?php echo $filepath; ?></p>
            <p><strong>Line Number:</strong> <?php echo $line; ?></p>
        </div>
    </div>
</body>
</html>