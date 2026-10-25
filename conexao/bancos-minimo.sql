-- Lab: estrutura + poucos registros (cole na aba SQL do phpMyAdmin)
SET NAMES utf8;
SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `clientes`;
CREATE TABLE `clientes` (
  `idCliente` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(500) NOT NULL,
  `endereco` varchar(200) NOT NULL,
  `bairro` varchar(100) NOT NULL,
  `cidade` varchar(100) NOT NULL,
  `uf` varchar(25) NOT NULL,
  `cpf_cnpj` varchar(40) NOT NULL,
  `telefone` varchar(25) NOT NULL,
  `fax` varchar(25) NOT NULL,
  PRIMARY KEY (`idCliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `clientes` (`idCliente`,`nome`,`endereco`,`bairro`,`cidade`,`uf`,`cpf_cnpj`,`telefone`,`fax`) VALUES
(1,'Cliente Teste Lab','Rua A','Centro','Campo Bom','RS','','51999999999','');

DROP TABLE IF EXISTS `compras`;
CREATE TABLE `compras` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero_nota` text NOT NULL,
  `fornecedor` text NOT NULL,
  `data` date NOT NULL,
  `valor` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `contas_pagar`;
CREATE TABLE `contas_pagar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fornecedor` text NOT NULL,
  `numero_documento` text NOT NULL,
  `data` date NOT NULL,
  `valor` text NOT NULL,
  `status` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `data` (`data`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `contas_pagar` (`fornecedor`,`numero_documento`,`data`,`valor`,`status`) VALUES
('Fornecedor Lab','DOC-001',CURDATE(),'150.00','aberto');

DROP TABLE IF EXISTS `produtos`;
CREATE TABLE `produtos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` text,
  `produto` text,
  `descricao` text,
  `estoque` double DEFAULT NULL,
  `codigo_original` text,
  `codigo_paralelo` text,
  `ncm` text,
  `preco` text,
  `promocao` text,
  `foto` text,
  `pasta` text,
  `custo` text,
  `ultimo_fornecedor` text,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `indice_pesquisa` (`codigo`,`produto`,`descricao`,`codigo_original`,`codigo_paralelo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `produtos` (`codigo`,`produto`,`descricao`,`estoque`,`preco`,`promocao`,`foto`) VALUES
('LAB-001','Filtro oleo','Filtro oleo motor teste',10,'45.00','0',''),
('LAB-002','Pastilha freio','Pastilha dianteira teste',5,'120.00','0',''),
('LAB-003','Vela ignicao','Vela NGK teste',20,'35.50','0','');

DROP TABLE IF EXISTS `orcamento`;
CREATE TABLE `orcamento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idProduto` int(11) NOT NULL,
  `idOrcamento` int(11) NOT NULL,
  `idCliente` int(11) NOT NULL,
  `preco` float NOT NULL,
  `quantidade` float NOT NULL,
  `dataHora` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `senha` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `usuarios` (`nome`,`usuario`,`senha`) VALUES ('Admin Lab','admin','admin');

DROP TABLE IF EXISTS `vendedor`;
CREATE TABLE `vendedor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `vendedor` (`nome`) VALUES ('Vendedor Lab');

SET FOREIGN_KEY_CHECKS=1;
