document.addEventListener('DOMContentLoaded', () => {

    // =================================================================
    // CONTROLE DAS ABAS
    // =================================================================
    const tabs = document.querySelectorAll('.tab-button');
    const contents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(item => item.classList.remove('active'));
            contents.forEach(content => content.classList.remove('active'));

            tab.classList.add('active');
            const activeTabId = tab.dataset.tab;
            document.getElementById(activeTabId).classList.add('active');

            if (activeTabId === 'financeiro') {
                carregarAbaFinanceiro();
            } else if (activeTabId === 'materiais') {
                carregarTodosMateriais();
            } else if (activeTabId === 'mao_de_obra') {
                carregarTodosServicos();
            }
        });
    });

    // =================================================================
    // CONTROLE DOS MODAIS
    // =================================================================
    function openModal(modalId) { document.getElementById(modalId).style.display = 'block'; }
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.style.animation = 'fadeOut 0.3s forwards';
        modal.querySelector('.modal-content').style.animation = 'slideOut 0.4s forwards';
        setTimeout(() => {
            modal.style.display = 'none';
            modal.style.animation = 'fadeIn 0.3s';
            modal.querySelector('.modal-content').style.animation = 'slideIn 0.4s';
            const feedback = modal.querySelector('.feedback');
            if (feedback) feedback.style.display = 'none';
        }, 400);
    }
    
    // Listeners para modais de CADASTRO
    document.getElementById('btn-abrir-modal-material').addEventListener('click', () => openModal('modal-material'));
    document.getElementById('btn-abrir-modal-servico').addEventListener('click', () => openModal('modal-servico'));
    document.querySelectorAll('.close-button').forEach(button => {
        button.addEventListener('click', () => closeModal(button.dataset.modal));
    });
    window.addEventListener('click', (event) => {
        if (event.target.classList.contains('modal')) closeModal(event.target.id);
    });

    // =================================================================
    // LÓGICA DE EDIÇÃO E EXCLUSÃO
    // =================================================================
    async function abrirModalEdicao(tipo, id) {
        try {
            const response = await fetch(`api/buscar_item.php?tipo=${tipo}&id=${id}`);
            const result = await response.json();

            if (!result.success) {
                alert(result.message);
                return;
            }

            const item = result.data;
            const formContainer = document.getElementById('form-edicao');
            const tituloModal = document.getElementById('modal-edicao-titulo');
            let formHTML = '';

            // Monta o formulário dinamicamente
            if (tipo === 'material') {
                tituloModal.innerHTML = '<i class="fa-solid fa-pencil"></i> Editar Material';
                formHTML = `
                    <input type="hidden" name="id" value="${item.id}">
                    <input type="hidden" name="tipo" value="material">
                    <input type="text" name="nome_material" placeholder="Nome do Material" value="${item.nome}" required>
                    <input type="text" name="unidade_material" placeholder="Unidade" value="${item.unidade}" required>
                    <input type="number" step="0.01" name="preco_material" placeholder="Preço Unitário" value="${item.preco_unitario}" required>
                    <input type="number" step="0.01" name="quantidade_material" placeholder="Quantidade" value="${item.quantidade}" required>
                `;
            } else if (tipo === 'servico') {
                tituloModal.innerHTML = '<i class="fa-solid fa-pencil"></i> Editar Serviço';
                formHTML = `
                    <input type="hidden" name="id" value="${item.id}">
                    <input type="hidden" name="tipo" value="servico">
                    <input type="text" name="descricao_servico" placeholder="Nome do Serviço" value="${item.descricao_servico}" required>
                    <textarea name="detalhes_servico" placeholder="Detalhes (opcional)...">${item.detalhes_servico || ''}</textarea>
                    <input type="number" step="0.01" name="custo_servico" placeholder="Custo" value="${item.custo}" required>
                `;
            }

            formHTML += `
                <button type="submit"><i class="fa-solid fa-check-circle"></i> Salvar Alterações</button>
                <button type="button" class="btn-delete"><i class="fa-solid fa-trash-alt"></i> Excluir Item</button>
            `;
            formContainer.innerHTML = formHTML;
            
            openModal('modal-edicao');

        } catch (error) {
            console.error("Erro ao buscar item para edição:", error);
            alert("Não foi possível carregar os dados para edição.");
        }
    }

    // Listener para o formulário de edição
    document.getElementById('form-edicao').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        
        try {
            const response = await fetch('api/editar_item.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const result = await response.json();
            const feedbackEl = document.getElementById('edicao-feedback');
            showFeedback(feedbackEl, result.message, result.success);

            if (result.success) {
                recarregarTodasAsListas();
                setTimeout(() => closeModal('modal-edicao'), 1500);
            }
        } catch (error) {
            console.error("Erro ao salvar edição:", error);
        }
    });

    // Listener para o botão de exclusão
    document.getElementById('modal-edicao').addEventListener('click', async function(e) {
        if (e.target.classList.contains('btn-delete')) {
            if (confirm('Tem certeza que deseja excluir este item? Esta ação não pode ser desfeita.')) {
                const form = document.getElementById('form-edicao');
                const id = form.querySelector('input[name="id"]').value;
                const tipo = form.querySelector('input[name="tipo"]').value;

                try {
                    const response = await fetch('api/excluir_item.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id, tipo })
                    });
                    const result = await response.json();
                     const feedbackEl = document.getElementById('edicao-feedback');
                    showFeedback(feedbackEl, result.message, result.success);
                    
                    if(result.success) {
                        recarregarTodasAsListas();
                        setTimeout(() => closeModal('modal-edicao'), 1500);
                    }
                } catch (error) {
                     console.error("Erro ao excluir item:", error);
                }
            }
        }
    });

    // =================================================================
    // FUNÇÕES AUXILIARES E DE RENDERIZAÇÃO
    // =================================================================
    function showFeedback(element, message, success = true) {
        element.textContent = message;
        element.className = 'feedback';
        element.classList.add(success ? 'success' : 'error');
        element.style.display = 'block';
    }
    
    function renderTable(container, headers, data, rowRenderer, emptyMessage) {
        container.innerHTML = '';
        if (data && data.length > 0) {
            const table = document.createElement('table');
            table.innerHTML = `
                <thead><tr>${headers.map(h => `<th>${h}</th>`).join('')}</tr></thead>
                <tbody>${data.map(item => `<tr data-id="${item.id}">${rowRenderer(item)}</tr>`).join('')}</tbody>`;
            container.appendChild(table);
        } else {
            container.innerHTML = `<p>${emptyMessage}</p>`;
        }
    }

    function formatCurrency(value) {
        return parseFloat(value).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    }

    function recarregarTodasAsListas() {
        carregarAbaFinanceiro();
        carregarTodosMateriais();
        carregarTodosServicos();
    }

    // =================================================================
    // ATUALIZAÇÃO DE STATUS
    // =================================================================
    async function atualizarStatus(tipo, id, novoStatus) {
        const url = tipo === 'material' ? 'api/atualizar_status_material.php' : 'api/atualizar_status_servico.php';
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id, status: novoStatus })
            });
            const result = await response.json();
            if (!result.success) alert(`Erro: ${result.message}`);
            
            recarregarTodasAsListas();
        } catch (error) {
            console.error('Falha ao atualizar status:', error);
            alert('Não foi possível conectar ao servidor para atualizar o status.');
        }
    }

    // =================================================================
    // CARREGAMENTO DE DADOS (API)
    // =================================================================
    function carregarAbaFinanceiro() {
        carregarResumoFinanceiro();
        carregarListasPendentes();
    }

    async function carregarResumoFinanceiro() {
        try {
            const response = await fetch('api/buscar_totais.php');
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const result = await response.json();

            if (result.success) {
                const totais = result.totais;
                const updateSummaryCard = (prefix, data) => {
                    document.getElementById(`${prefix}-pago`).textContent = formatCurrency(data.pago);
                    document.getElementById(`${prefix}-orcado`).textContent = formatCurrency(data.total);
                    const percentage = data.total > 0 ? (data.pago / data.total) * 100 : 0;
                    document.getElementById(`progress-${prefix.split('-')[1]}`).style.width = `${percentage}%`;
                };
                updateSummaryCard('total-obra', totais.obra);
                updateSummaryCard('total-materiais', totais.materiais);
                updateSummaryCard('total-servicos', totais.servicos);
            }
        } catch (error) {
            console.error("Erro ao carregar resumo financeiro:", error);
        }
    }

    async function carregarListasPendentes() {
        try {
            const response = await fetch('api/listar_pendentes.php');
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const data = await response.json();
            
            renderTable(document.getElementById('lista-materiais-pendentes'), ['Material', 'Quantidade', 'Custo Total'], data.materiais, item => `<td>${item.nome}</td><td>${item.quantidade} ${item.unidade}</td><td><b>${formatCurrency(item.quantidade * item.preco_unitario)}</b></td>`, 'Nenhum material pendente.');
            
            renderTable(document.getElementById('lista-servicos-pendentes'), ['Serviço', 'Custo'], data.servicos, item => `
                <td>
                    ${item.descricao_servico}
                    ${item.detalhes_servico ? `<br><small style="color: var(--text-muted);">${item.detalhes_servico}</small>` : ''}
                </td>
                <td><b>${formatCurrency(item.custo)}</b></td>
            `, 'Nenhum serviço pendente.');
        } catch (error) {
            console.error("Erro ao carregar listas pendentes:", error);
        }
    }

    async function carregarTodosMateriais() {
        try {
            const response = await fetch('api/listar_materiais.php');
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const data = await response.json();
            
            renderTable(document.getElementById('lista-completa-materiais'), ['Material', 'Qtd.', 'Preço Unit.', 'Custo Total', 'Status', 'Ações'], data, item => {
                const custoTotal = (parseFloat(item.quantidade) * parseFloat(item.preco_unitario));
                return `
                    <td>${item.nome}</td>
                    <td>${item.quantidade} ${item.unidade}</td>
                    <td>${formatCurrency(item.preco_unitario)}</td>
                    <td><b>${formatCurrency(custoTotal)}</b></td>
                    <td>
                        <select class="status-select" data-id="${item.id}" data-tipo="material" data-status="${item.status}" onchange="atualizarStatus('material', this.dataset.id, this.value)">
                            <option value="Pendente" ${item.status === 'Pendente' ? 'selected' : ''}>Pendente</option>
                            <option value="Comprado" ${item.status === 'Comprado' ? 'selected' : ''}>Comprado</option>
                        </select>
                    </td>
                    <td class="actions">
                        <button class="btn-action btn-edit" onclick="abrirModalEdicao('material', ${item.id})"><i class="fa-solid fa-pencil"></i></button>
                    </td>
                `;
            }, 'Nenhum material cadastrado.');
        } catch (error) {
            console.error("Erro ao carregar todos os materiais:", error);
        }
    }

    async function carregarTodosServicos() {
        try {
            const response = await fetch('api/listar_servicos.php');
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const data = await response.json();

            renderTable(document.getElementById('lista-completa-servicos'), ['Serviço', 'Custo', 'Status', 'Ações'], data, item => `
                <td>
                    ${item.descricao_servico}
                    ${item.detalhes_servico ? `<br><small style="color: var(--text-muted);">${item.detalhes_servico}</small>` : ''}
                </td>
                <td><b>${formatCurrency(item.custo)}</b></td>
                <td>
                    <select class="status-select" data-id="${item.id}" data-tipo="servico" data-status="${item.status}" onchange="atualizarStatus('servico', this.dataset.id, this.value)">
                        <option value="Pendente" ${item.status === 'Pendente' ? 'selected' : ''}>Pendente</option>
                        <option value="Pago" ${item.status === 'Pago' ? 'selected' : ''}>Pago</option>
                    </select>
                </td>
                <td class="actions">
                    <button class="btn-action btn-edit" onclick="abrirModalEdicao('servico', ${item.id})"><i class="fa-solid fa-pencil"></i></button>
                </td>
            `, 'Nenhum serviço cadastrado.');
        } catch (error) {
            console.error("Erro ao carregar todos os serviços:", error);
        }
    }

    // =================================================================
    // SUBMISSÃO DOS FORMULÁRIOS DE CADASTRO
    // =================================================================
    const formMaterial = document.getElementById('form-material');
    formMaterial.addEventListener('submit', async (e) => {
        e.preventDefault();
        const feedbackEl = document.getElementById('material-feedback');
        const formData = new FormData(formMaterial);
        
        const response = await fetch('api/cadastrar_material.php', { method: 'POST', body: formData });
        const result = await response.json();
        
        showFeedback(feedbackEl, result.message, result.success);

        if (result.success) {
            formMaterial.reset();
            recarregarTodasAsListas();
            setTimeout(() => closeModal('modal-material'), 1500);
        }
    });

    const formServico = document.getElementById('form-servico');
    formServico.addEventListener('submit', async (e) => {
        e.preventDefault();
        const feedbackEl = document.getElementById('servico-feedback');
        const formData = new FormData(formServico);

        const response = await fetch('api/cadastrar_servico.php', { method: 'POST', body: formData });
        const result = await response.json();

        showFeedback(feedbackEl, result.message, result.success);

        if (result.success) {
            formServico.reset();
            recarregarTodasAsListas();
            setTimeout(() => closeModal('modal-servico'), 1500);
        }
    });
    
    // =================================================================
    // INICIALIZAÇÃO
    // =================================================================
    window.abrirModalEdicao = abrirModalEdicao; // Torna a função global
    window.atualizarStatus = atualizarStatus; 
    recarregarTodasAsListas();
});
