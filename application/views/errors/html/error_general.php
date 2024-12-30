<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <title>Hata</title>
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
            max-width: 600px;
            margin: 0 auto;
        }
        h1 {
            color: #d9534f;
            margin-top: 0;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1><?php echo $heading; ?></h1>
        <?php echo $message; ?>
    </div>
</body>
</html>