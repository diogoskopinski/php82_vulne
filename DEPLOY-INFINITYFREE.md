# Deploy no InfinityFree (FTP)

## 1. MySQL no painel

Crie o banco em **MySQL Databases** e importe os dados (veja seção **Importar tabelas** abaixo).

## 2. `conexao/credentials.php` (obrigatório no servidor)

Copie `conexao/credentials.example.php` para `conexao/credentials.php` e preencha:

```php
<?php
return [
    'DB_HOST' => 'sql123.infinityfree.com',  // do painel — NÃO use localhost
    'DB_USER' => 'if0_XXXXXXX',
    'DB_PASSWORD' => 'sua_senha',
    'DB_NAME' => 'if0_XXXXXXX_catalogo',
];
```

Envie esse arquivo por FTP (não versionar no git).

## 3. Pastas graváveis

No FTP, crie `fotos/` com permissão de escrita (chmod 755 ou 777).

## 4. Enviar arquivos

Inclua pelo menos: `includes/mysql-shim.php`, `js/`, `css/`, `produto/`, `conexao/`, etc.

```bash
FTP_PASSWORD='...' ./scripts/deploy-infinityfree.sh
```

## 5. Menu “Produtos” não abre

- Confirme que `js/jquery-1.10.2.js` e `js/bootstrap.js` existem no servidor (200 no DevTools).
- Se a página mostra erro de MySQL antes do HTML terminar, corrija `credentials.php` (host remoto).

## 6. PHP

InfinityFree usa PHP 8.x; o projeto usa `includes/mysql-shim.php` para funções `mysql_*` legadas.

## 7. Importar tabelas (banco vazio)

### Opção A — dados completos (~5 MB)

1. phpMyAdmin → banco `if0_43271238_vendas`
2. Aba **Importar**
3. Arquivo: `conexao/bancos.sql` (do seu PC / projeto)
4. Executar

Se der erro de tamanho, use a opção B ou importe pelo Docker local e exporte em partes.

### Opção B — mínimo para testar (recomendado no começo)

1. phpMyAdmin → banco `if0_43271238_vendas`
2. Aba **SQL**
3. Abra `conexao/bancos-minimo.sql` no editor, copie **tudo**, cole e clique **Executar**

Cria as tabelas `clientes`, `produtos`, `contas_pagar`, etc. com poucos registros de exemplo.

### Opção C — linha de comando (Docker local → só se você tiver MySQL aí)

```bash
cd ~/projetos/php-8_2_vulne
docker compose up -d db
docker compose exec -T db mysql -uroot -proot_pass -e "CREATE DATABASE IF NOT EXISTS catalogo;"
docker compose exec -T db mysql -uroot -proot_pass catalogo < conexao/bancos.sql
```

No InfinityFree não há SSH; use phpMyAdmin (A ou B).
