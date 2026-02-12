<?php
require_once 'auth.php';
require_once 'config.php';

$error = '';

// Håndter innlogging
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (login($username, $password)) {
        header('Location: index.php');
        exit;
    } else {
        $error = 'Feil brukernavn eller passord';
    }
}

// Hvis allerede innlogget, redirect til chat
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logg inn - <?= htmlspecialchars(AGENT_NAME) ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles -->
    <link rel="stylesheet" href="css/style.css">
    
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            background: var(--bg-main);
        }
        
        .login-card {
            background: var(--bg-card);
            border-radius: var(--radius);
            padding: 2.5rem;
            width: 100%;
            max-width: 400px;
            box-shadow: var(--shadow-lg);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-icon {
            width: 64px;
            height: 64px;
            background: var(--user-bubble);
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin: 0 auto 1rem;
            color: white;
        }
        
        .login-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }
        
        .login-subtitle {
            color: var(--text-secondary);
            font-size: 0.95rem;
        }
        
        .login-form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .form-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-secondary);
        }
        
        .form-input {
            background: var(--bg-input);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 0.875rem 1rem;
            color: var(--text-primary);
            font-size: 1rem;
            font-family: inherit;
            transition: var(--transition);
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-bg);
        }
        
        .form-input::placeholder {
            color: var(--text-muted);
        }
        
        .login-button {
            background: var(--user-bubble);
            border: none;
            border-radius: var(--radius-sm);
            padding: 1rem;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 0.5rem;
        }
        
        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }
        
        .login-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            padding: 0.75rem 1rem;
            border-radius: var(--radius-sm);
            font-size: 0.875rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-icon">
                    <i class="fas fa-robot"></i>
                </div>
                <h1 class="login-title"><?= htmlspecialchars(AGENT_NAME) ?></h1>
                <p class="login-subtitle">Logg inn for å starte chatten</p>
            </div>
            
            <?php if ($error): ?>
                <div class="login-error">
                    <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <form class="login-form" method="POST">
                <div class="form-group">
                    <label class="form-label" for="username">Brukernavn</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        class="form-input" 
                        placeholder="Skriv brukernavn..."
                        required
                        autofocus
                    >
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="password">Passord</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input" 
                        placeholder="Skriv passord..."
                        required
                    >
                </div>
                
                <button type="submit" class="login-button">
                    <i class="fas fa-sign-in-alt"></i> Logg inn
                </button>
            </form>
        </div>
    </div>
</body>
</html>
