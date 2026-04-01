<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — AdminPro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Manrope:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --accent: #5b5ef4;
            --accent-hover: #4a4dd6;
            --bg-panel: #0c0f1d;
            --text: #111827;
            --muted: #6b7280;
            --border: #e5e7eb;
            --danger: #ef4444;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; -webkit-font-smoothing: antialiased; }

        body {
            font-family: 'Manrope', sans-serif;
            display: flex;
            min-height: 100vh;
        }

        /* ─── LEFT PANEL ─── */
        .panel-brand {
            width: 45%;
            background: var(--bg-panel);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px 52px;
            position: relative;
            overflow: hidden;
        }

        .panel-brand::before {
            content: '';
            position: absolute;
            top: -100px; left: -100px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(91,94,244,0.22) 0%, transparent 65%);
        }

        .panel-brand::after {
            content: '';
            position: absolute;
            bottom: -80px; right: -80px;
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(139,92,246,0.18) 0%, transparent 65%);
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 13px;
            position: relative;
            z-index: 1;
        }

        .brand-icon {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, #5b5ef4, #818cf8);
            border-radius: 13px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; color: white;
            box-shadow: 0 4px 16px rgba(91,94,244,0.45);
        }

        .brand-name {
            font-family: 'Outfit', sans-serif;
            font-size: 22px;
            font-weight: 700;
            color: white;
            letter-spacing: -0.4px;
        }

        .brand-name em { color: #a5b4fc; font-style: normal; }

        .brand-content {
            position: relative;
            z-index: 1;
        }

        .brand-content h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 36px;
            font-weight: 800;
            color: white;
            line-height: 1.2;
            letter-spacing: -0.8px;
            margin-bottom: 18px;
        }

        .brand-content h2 em {
            color: #a5b4fc;
            font-style: normal;
        }

        .brand-content p {
            font-size: 14.5px;
            color: rgba(255,255,255,0.45);
            line-height: 1.65;
            max-width: 340px;
        }

        .brand-features {
            display: flex;
            flex-direction: column;
            gap: 13px;
            margin-top: 36px;
        }

        .feat-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .feat-dot {
            width: 28px; height: 28px;
            border-radius: 8px;
            background: rgba(91,94,244,0.2);
            border: 1px solid rgba(91,94,244,0.3);
            display: flex; align-items: center; justify-content: center;
            color: #a5b4fc;
            font-size: 12px;
            flex-shrink: 0;
        }

        .feat-item span {
            font-size: 13.5px;
            color: rgba(255,255,255,0.55);
            font-weight: 500;
        }

        .brand-footer {
            position: relative;
            z-index: 1;
            font-size: 12px;
            color: rgba(255,255,255,0.2);
        }

        /* ─── RIGHT PANEL ─── */
        .panel-form {
            flex: 1;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
        }

        .form-box {
            width: 100%;
            max-width: 400px;
            animation: slideIn 0.4s ease;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(16px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        .form-box h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 26px;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -0.4px;
            margin-bottom: 6px;
        }

        .form-box .subtitle {
            font-size: 14px;
            color: var(--muted);
            margin-bottom: 36px;
        }

        .error-msg {
            background: rgba(239,68,68,0.08);
            border: 1px solid rgba(239,68,68,0.2);
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13.5px;
            color: #dc2626;
            font-weight: 500;
            animation: slideIn 0.3s ease;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 7px;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap i {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: 14px;
            pointer-events: none;
            transition: color 0.2s;
        }

        .input-wrap input {
            width: 100%;
            padding: 12px 14px 12px 40px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Manrope', sans-serif;
            color: var(--text);
            background: white;
            transition: all 0.2s;
            outline: none;
        }

        .input-wrap input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(91,94,244,0.1);
        }

        .input-wrap input:focus + i,
        .input-wrap input:focus ~ i { color: var(--accent); }

        .input-wrap .eye-toggle {
            position: absolute;
            right: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            cursor: pointer;
            font-size: 14px;
            padding: 4px;
            left: auto;
            pointer-events: all;
            transition: color 0.2s;
        }

        .input-wrap .eye-toggle:hover { color: var(--accent); }

        .form-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            margin-top: 4px;
        }

        .remember-wrap {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 13px;
            color: var(--muted);
            cursor: pointer;
        }

        .remember-wrap input { accent-color: var(--accent); cursor: pointer; }

        .forgot-link {
            font-size: 13px;
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
        }

        .forgot-link:hover { text-decoration: underline; }

        .btn-login {
            width: 100%;
            padding: 13px;
            background: var(--accent);
            color: white;
            font-family: 'Outfit', sans-serif;
            font-size: 15px;
            font-weight: 700;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            letter-spacing: 0.2px;
            transition: all 0.2s;
            box-shadow: 0 3px 10px rgba(91,94,244,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-login:hover {
            background: var(--accent-hover);
            transform: translateY(-1px);
            box-shadow: 0 5px 16px rgba(91,94,244,0.38);
        }

        .btn-login:active { transform: translateY(0); }

        .form-footer {
            margin-top: 24px;
            text-align: center;
            font-size: 12.5px;
            color: var(--muted);
        }

        @media (max-width: 800px) {
            .panel-brand { display: none; }
        }
    </style>
</head>
<body>

    <!-- Brand Panel -->
    <div class="panel-brand">
        <div class="brand-logo">
            <div class="brand-icon"><i class="fas fa-layer-group"></i></div>
            <div class="brand-name">Admin<em>Pro</em></div>
        </div>

        <div class="brand-content">
            <h2>Gestão simples.<br><em>Resultados reais.</em></h2>
            <p>Plataforma administrativa completa para gerenciar seus produtos, usuários e pedidos em um só lugar.</p>

            <div class="brand-features">
                <div class="feat-item">
                    <div class="feat-dot"><i class="fas fa-shield-halved"></i></div>
                    <span>Acesso seguro com sessão autenticada</span>
                </div>
                <div class="feat-item">
                    <div class="feat-dot"><i class="fas fa-chart-line"></i></div>
                    <span>Dashboard com métricas em tempo real</span>
                </div>
                <div class="feat-item">
                    <div class="feat-dot"><i class="fas fa-bolt"></i></div>
                    <span>Interface rápida e responsiva</span>
                </div>
            </div>
        </div>

        <div class="brand-footer">
            &copy; <?php echo date('Y'); ?> AdminPro &mdash; Todos os direitos reservados
        </div>
    </div>

    <!-- Form Panel -->
    <div class="panel-form">
        <div class="form-box">
            <h1>Bem-vindo de volta 👋</h1>
            <p class="subtitle">Insira suas credenciais para continuar</p>

            <?php if (isset($_GET["msgerro"])): ?>
                <div class="error-msg">
                    <i class="fas fa-circle-exclamation"></i>
                    <?php echo htmlspecialchars($_GET["msgerro"]); ?>
                </div>
            <?php endif; ?>

            <form action="index.php?tentativa=1" method="post">
                <div class="form-group">
                    <label for="username">Usuário</label>
                    <div class="input-wrap">
                        <i class="fas fa-user" style="pointer-events:none;z-index:1"></i>
                        <input type="text" id="username" name="username"
                               placeholder="Digite seu usuário" required
                               value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Senha</label>
                    <div class="input-wrap">
                        <i class="fas fa-lock" style="pointer-events:none;z-index:1"></i>
                        <input type="password" id="password" name="password"
                               placeholder="Digite sua senha" required>
                        <i class="fas fa-eye eye-toggle" id="eyeToggle"></i>
                    </div>
                </div>

                <div class="form-meta">
                    <label class="remember-wrap">
                        <input type="checkbox"> Lembrar-me
                    </label>
                    <a href="#" class="forgot-link">Esqueceu a senha?</a>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fas fa-arrow-right-to-bracket"></i>
                    Entrar no sistema
                </button>
            </form>

            <div class="form-footer">
                Problemas para acessar? Contate o administrador.
            </div>
        </div>
    </div>

    <script>
        const eye = document.getElementById('eyeToggle');
        const pwd = document.getElementById('password');
        eye.addEventListener('click', () => {
            const show = pwd.type === 'password';
            pwd.type = show ? 'text' : 'password';
            eye.className = show ? 'fas fa-eye-slash eye-toggle' : 'fas fa-eye eye-toggle';
        });
    </script>
</body>
</html>