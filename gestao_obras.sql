-- Tabela para armazenar os materiais
CREATE TABLE `materiais` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(255) NOT NULL,
  `unidade` VARCHAR(20) NOT NULL COMMENT 'Ex: un, m², kg, L',
  `preco_unitario` DECIMAL(10, 2) NOT NULL,
  `quantidade` DECIMAL(10, 2) NOT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'Pendente' COMMENT 'Pendente, Comprado, Usado',
  `data_cadastro` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela para armazenar os serviços de mão de obra
CREATE TABLE `mao_de_obra` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `descricao_servico` VARCHAR(255) NOT NULL,
  `custo` DECIMAL(10, 2) NOT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'Pendente' COMMENT 'Pendente, Aprovado, Pago',
  `data_cadastro` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `usuarios` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `telefone` VARCHAR(20) NULL,
  `senha` VARCHAR(255) NOT NULL,
  `data_cadastro` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);