<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Gestão de Custos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style_login.css">
    <link rel="icon" type="image/png" href="favicon.png">
</head>
<body>
    <header>
            <img src="https://kavicki.com/wp-content/uploads/2025/07/logo-roxo.png" alt="Logo Kavicki">
            <h1>Gestão de Obras</h1>
            <p>Sua ferramenta inteligente para gerenciar sua obra.</p>
            
        </header>
    <main>
        <div class="form-container">
            <div class="form-wrapper" id="login-form">
                <form action="api/autenticar.php" method="POST" class="form-box">
                    <h2><i class="fa-solid fa-right-to-bracket"></i> Login</h2>
                    <div class="input-group">
                        <input type="email" name="email" required>
                        <label>E-mail</label>
                    </div>
                    <div class="input-group">
                        <input type="password" name="senha" required>
                        <label>Senha</label>
                    </div>
                    <button type="submit">Entrar</button>
                    <div class="bottom-link">
                        Não tem uma conta? <a href="#" id="show-register">Cadastre-se</a>
                    </div>
                    <div class="feedback"></div>
                </form>
            </div>

            <div class="form-wrapper hidden" id="register-form">
                <form action="api/registrar_usuario.php" method="POST" class="form-box">
                    <h2><i class="fa-solid fa-user-plus"></i> Cadastro</h2>
                    <div class="input-group">
                        <input type="text" name="nome" required>
                        <label>Nome Completo</label>
                    </div>
                    <div class="input-group">
                        <input type="email" name="email" required>
                        <label>E-mail</label>
                    </div>
                    <div class="input-group">
                        <input type="tel" name="telefone" required>
                        <label>Telefone</label>
                    </div>
                    <div class="input-group">
                        <input type="password" name="senha" required>
                        <label>Senha</label>
                    </div>
                    <button type="submit">Cadastrar</button>
                    <div class="bottom-link">
                        Já tem uma conta? <a href="#" id="show-login">Faça Login</a>
                    </div>
                    <div class="feedback"></div>
                </form>
            </div>
        </div>
    </main>
    <script src="script_login.js"></script>
    <footer>
        <p>&copy; 2025 Kavicki. Todos os direitos reservados.</p>
    </footer>
</body>
</html>