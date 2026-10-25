# Lista FTP — InfinityFree (`htdocs/`)

Envie **pastas inteiras** mantendo a estrutura. Arquivos **prioritários** (menu + banco) estão marcados com ★.

## ★ Prioridade imediata (menu Produtos + MySQL)

| Arquivo |
|---------|
| `topo.php` |
| `catalogo.php` |
| `functions.php` |
| `bootstrap.php` |
| `.user.ini` |
| `js/jquery-1.10.2.js` |
| `js/bootstrap.js` |
| `js/shortcut.js` |
| `conexao/config.php` |
| `conexao/credentials.php` *(criar no servidor; não vai no git)* |
| `includes/mysql-shim.php` |
| `includes/media.php` |

## Raiz (`htdocs/`)

- `index.php`
- `catalogo.php`
- `topo.php`
- `topo_sem_menu.php`
- `functions.php`
- `Config.inc.php`
- `bootstrap.php`
- `.user.ini`
- `.htaccess`
- `processa.php`
- `carrinho.php`, `carrinho_novo.php`, `carrinho_inserir_dados.php`
- `busca.php`, `busca_boleto.php`
- `cadastro_boleto.php`, `gerenciar_boleto.php`
- `alterar_boleto.php`, `alterar_boleto_db.php`, `alterar_foto.php`
- `orcamento.php`, `orcamento_gerenciar.php`, `orcamento_finalizar.php`, `orcamento_remover_db.php`, `orcamento_visualizar.php`
- `relatorio.php`, `relatorio_vendas.php`, `relatorio_compras.php`, `relatorio_estoque.php`
- `compras.php`, `calculadora.php`, `inserir_dados.php`
- `navbar-fixed-top.css` *(se existir)*

## `conexao/`

- `config.php`
- `credentials.php` *(só no servidor)*
- `bancos.sql` *(importar no phpMyAdmin, não precisa ficar público)*

## `includes/`

- `mysql-shim.php`
- `media.php`

## `Classes/`

- `Conexao.class.php`
- `Produto.class.php`
- `Cliente.class.php`
- `ContasPagar.class.php`

## `produto/`

- `index.php`, `adicionar.php`, `editar.php`, `apagar.php`, `busca.php`

## `cliente/`

- `index.php`, `adicionar.php`, `editar.php`, `apagar.php`

## `financeiro/`

- `index.php`, `adicionar.php`

## `css/` (pasta completa)

- `bootstrap.css`, `bootstrap.min.css`, `bootstrap-select.css`
- `lightbox.css`, `sb-admin.css`, `colorbox.css`
- `estilo_orcamento.css`, `bootstrapOLD.css` *(opcional)*

## `js/` (pasta completa)

- `jquery-1.10.2.js`, `bootstrap.js` ★
- `bootstrap-select.js`, `lightbox.js`, `function.js`
- `jquery.colorbox.js`, `jquery.colorbox-min.js`
- `shortcut.js` ★
- Subpastas: `jquery/`, `tablesorter/`, `flot/`, `morris/` *(conforme páginas que usar)*

## `images/`

- `delete.svg`, `foto.svg`, `cesta.svg`, `no-foto.svg`
- `images/catalogo/` *(fras-le-2.jpg, mobensani.jpg, vox.jpg, valclei.jpg, etc.)*
- Demais PNG do projeto original *(delete.png, editar.png, … se tiver)*

## `fotos/`

- Pasta vazia com permissão de escrita **777** ou **755** (upload de fotos de produto)

## `greybox/`, `tooltip/`

- Enviar se usar popups antigos em alguma tela

## Não enviar

- `.git/`, `.env`, `docker/`, `Dockerfile`, `docker-compose.yml`
- `scripts/`, `*.md`, `LAB-DOCKER.md`, `DEPLOY-INFINITYFREE.md`
- `conexao/credentials.php` no mirror automático — subir **manual**

## Depois do upload

1. phpMyAdmin → banco `if0_43271238_vendas` → importar `bancos.sql`
2. Testar: `https://SEU-DOMINIO/catalogo.php`
3. F12 → Network: `jquery-1.10.2.js` e `bootstrap.js` com status **200**
