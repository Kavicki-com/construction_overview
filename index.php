<?php 
// 1. VERIFICA SE O USUÁRIO ESTÁ LOGADO
require 'api/verificar_sessao.php'; 
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Custos de Obra</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- 2. NOVO CABEÇALHO COM INFORMAÇÕES DO USUÁRIO -->
    <header>
        <h1><i class="fa-solid fa-hard-hat"></i> Gestão de Custos da Obra</h1>
        <div class="user-info">
            <span>Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></span>
            <a href="api/logout.php" class="btn-logout" title="Sair"><i class="fa-solid fa-sign-out-alt"></i></a>
        </div>
    </header>

    <main class="tab-container">
        <!-- Botões de Navegação -->
        <div class="tab-buttons">
            <button class="tab-button active" data-tab="financeiro"><i class="fa-solid fa-dollar-sign"></i> Financeiro</button>
            <button class="tab-button" data-tab="materiais"><i class="fa-solid fa-boxes-stacked"></i> Materiais</button>
            <button class="tab-button" data-tab="mao_de_obra"><i class="fa-solid fa-person-digging"></i> Mão de Obra</button>
        </div>

        <!-- Aba Financeiro -->
        <div id="financeiro" class="tab-content active">
            <div class="card">
                <div class="card-header">
                    <h2><i class="fa-solid fa-chart-pie"></i> Resumo Financeiro da Obra</h2>
                </div>
                <div id="summary-container" class="summary-grid">
                    <div class="summary-card">
                        <div class="summary-card-header">
                            <i class="fa-solid fa-hard-hat icon-blue"></i>
                            <h3>Total da Obra</h3>
                        </div>
                        <p class="summary-value" id="total-obra-pago">R$ 0,00</p>
                        <p class="summary-total">de <span id="total-obra-orcado">R$ 0,00</span></p>
                        <div class="progress-bar"><div class="progress-bar-fill" id="progress-obra"></div></div>
                    </div>
                    <div class="summary-card">
                        <div class="summary-card-header">
                            <i class="fa-solid fa-boxes-stacked icon-orange"></i>
                            <h3>Total de Materiais</h3>
                        </div>
                        <p class="summary-value" id="total-materiais-pago">R$ 0,00</p>
                        <p class="summary-total">de <span id="total-materiais-orcado">R$ 0,00</span></p>
                        <div class="progress-bar"><div class="progress-bar-fill" id="progress-materiais"></div></div>
                    </div>
                    <div class="summary-card">
                        <div class="summary-card-header">
                            <i class="fa-solid fa-person-digging icon-green"></i>
                            <h3>Total de Mão de Obra</h3>
                        </div>
                        <p class="summary-value" id="total-servicos-pago">R$ 0,00</p>
                        <p class="summary-total">de <span id="total-servicos-orcado">R$ 0,00</span></p>
                        <div class="progress-bar"><div class="progress-bar-fill" id="progress-servicos"></div></div>
                    </div>
                </div>
            </div>
            <div class="card">
                 <div class="card-header">
                    <h2><i class="fa-solid fa-rectangle-list"></i> Detalhamento de Pendências</h2>
                </div>
                <div class="pendentes-grid">
                    <div class="pendentes-coluna">
                        <h3><i class="fa-solid fa-boxes-stacked"></i> Materiais Pendentes</h3>
                        <div class="table-container" id="lista-materiais-pendentes"></div>
                    </div>
                     <div class="pendentes-coluna">
                        <h3><i class="fa-solid fa-person-digging"></i> Mão de obra Pendente</h3>
                        <div class="table-container" id="lista-servicos-pendentes"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aba Materiais -->
        <div id="materiais" class="tab-content">
            <div class="card">
                <div class="card-header">
                    <h2><i class="fa-solid fa-list-alt"></i> Lista de Materiais</h2>
                    <button id="btn-abrir-modal-material" class="btn-add"><i class="fa-solid fa-plus-circle"></i> Adicionar Material</button>
                </div>
                <div class="table-container" id="lista-completa-materiais"></div>
            </div>
        </div>

        <!-- Aba Mão de Obra -->
        <div id="mao_de_obra" class="tab-content">
            <div class="card">
                <div class="card-header">
                    <h2><i class="fa-solid fa-list-alt"></i> Lista de Serviços</h2>
                    <button id="btn-abrir-modal-servico" class="btn-add"><i class="fa-solid fa-plus-circle"></i> Adicionar Serviço</button>
                </div>
                <div class="table-container" id="lista-completa-servicos"></div>
            </div>
        </div>
    </main>

    <!-- Modais de Cadastro -->
    <div id="modal-material" class="modal">
        <div class="modal-content">
            <span class="close-button" data-modal="modal-material">&times;</span>
            <h3><i class="fa-solid fa-boxes-stacked"></i> Cadastrar Novo Material</h3>
            <form id="form-material">
                <input type="text" name="nome_material" placeholder="Nome do Material" required>
                <input type="text" name="unidade_material" placeholder="Unidade (ex: un, m², kg)" required>
                <input type="number" step="0.01" name="preco_material" placeholder="Preço Unitário R$" required>
                <input type="number" step="0.01" name="quantidade_material" placeholder="Quantidade" required>
                <button type="submit"><i class="fa-solid fa-check-circle"></i> Salvar Material</button>
            </form>
            <div id="material-feedback" class="feedback"></div>
        </div>
    </div>
    <div id="modal-servico" class="modal">
        <div class="modal-content">
            <span class="close-button" data-modal="modal-servico">&times;</span>
            <h3><i class="fa-solid fa-person-digging"></i> Cadastrar Novo Serviço</h3>
            <form id="form-servico">
                <input type="text" name="descricao_servico" placeholder="Nome do Serviço (ex: Eletricista, Pedreiro)" required>
                <!-- Adicionado textarea para detalhes -->
                <textarea name="detalhes_servico" placeholder="Detalhes do serviço"></textarea>
                <input type="number" step="0.01" name="custo_servico" placeholder="Custo do Serviço R$" required>
                <button type="submit"><i class="fa-solid fa-check-circle"></i> Salvar Serviço</button>
            </form>
            <div id="servico-feedback" class="feedback"></div>
        </div>
    </div>

    <!-- Modal Genérico para Edição -->
    <div id="modal-edicao" class="modal">
        <div class="modal-content">
            <span class="close-button" data-modal="modal-edicao">&times;</span>
            <h3 id="modal-edicao-titulo">Editar Item</h3>
            <form id="form-edicao">
                <!-- Campos do formulário serão injetados aqui via JS -->
            </form>
            <div id="edicao-feedback" class="feedback"></div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
