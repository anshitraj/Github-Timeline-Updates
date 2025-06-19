<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GitHub Timeline Email Subscription</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f4f6fb 60%, #e0e7ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Roboto', 'Segoe UI', Arial, sans-serif;
        }
        .container {
            background: #fff;
            padding: 2.5rem 2rem 2rem 2rem;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        .logo {
            width: 60px;
            margin-bottom: 1rem;
        }
        h2 {
            color: #22223b;
            margin-bottom: 1.5rem;
            font-size: 2rem;
            font-weight: 700;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #4a4e69;
            font-weight: 500;
        }
        input[type="email"] {
            width: 100%;
            padding: 0.7rem;
            border: 1px solid #c9c9c9;
            border-radius: 8px;
            margin-bottom: 1.2rem;
            font-size: 1rem;
            transition: border 0.2s;
            background: #f8fafc;
        }
        input:focus {
            border: 1.5px solid #4f8cff;
            outline: none;
            background: #fff;
        }
        button {
            background: #4f8cff;
            color: #fff;
            border: none;
            padding: 0.7rem 1.5rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 8px rgba(79,140,255,0.08);
        }
        button:hover {
            background: #2563eb;
            box-shadow: 0 4px 16px rgba(79,140,255,0.12);
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="https://github.githubassets.com/images/modules/logos_page/GitHub-Mark.png" alt="GitHub Logo" class="logo">
        <h2>Subscribe to GitHub Timeline</h2>
        <form action="verify.php" method="POST">
            <label for="email">Enter your email to subscribe:</label>
            <input type="email" name="email" required placeholder="you@example.com">
            <button id="submit-email">Get Verification Code</button>
        </form>
    </div>
</body>
</html>
