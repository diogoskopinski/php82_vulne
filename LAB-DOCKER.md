# Laboratório Docker — sistema-vendas (PHP 8.2)

Ambiente isolado para estudo de segurança do projeto [bbroger/sistema-vendas](https://github.com/bbroger/sistema-vendas.git).

**Use apenas em máquina local / rede de laboratório.** Não exponha na internet.

## Stack

| Componente | Versão / nota |
|------------|----------------|
| PHP | **8.2.27** (`php:8.2.27-apache-bookworm`) — linha 8.2 anterior a abril/2026 |
| Apache | mod_rewrite habilitado |
| MySQL | 8.0 com `ft_min_word_len=2` |
| Compat | Shim [dshafik/php-mysql](https://github.com/dshafik/php-mysql) em `/usr/local/lib/php/mysql-shim.php` |

## Subir o ambiente

```bash
cp .env.example .env
docker compose up -d --build
```

Aguarde o healthcheck do MySQL (primeira subida importa `conexao/bancos.sql`).

## Acesso

- Aplicação: http://localhost:8080/ (página inicial: `catalogo.php`)
- Clientes: http://localhost:8080/cliente/
- Carrinho: http://localhost:8080/carrinho.php
- MySQL (host): `127.0.0.1:3306` — usuário `sistema` / senha `sistema_pass` (padrão do `.env.example`)

## Comandos úteis

```bash
docker compose logs -f web
docker compose logs -f db
docker compose down          # mantém volume do banco
docker compose down -v       # apaga volume do banco
```

## Credenciais padrão (`.env.example`)

- `DB_USER=sistema`
- `DB_PASSWORD=sistema_pass`
- `MYSQL_ROOT_PASSWORD=root_pass`
- `DB_NAME=catalogo`

## Alterações mínimas no código (só para rodar no Docker)

- `conexao/config.php`, `processa.php` e `Classes/Conexao.class.php` leem `DB_*` do ambiente.
- `Conexao.class.php` no upstream tinha sintaxe inválida (`getEncryptedPass(...)`); foi corrigido para o lab funcionar.

O restante do código permanece como no repositório original (incluindo padrões inseguros intencionais para estudo).

## Aviso legal

Testes de invasão e exploração de vulnerabilidades devem ser feitos **somente** em sistemas que você possui ou tem autorização explícita por escrito.
