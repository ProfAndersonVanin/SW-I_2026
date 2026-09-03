# Aula 1 — Modelagem do Banco de Dados e Estrutura do Projeto
### Guia de desenvolvimento em laboratório — Sistema CRUD de Produtos com Login (PHP, MySQL, HTML e CSS)

## Antes de começar

- [ ] Computador com acesso para instalar programas
- [ ] Nenhum conhecimento prévio de PHP é necessário para esta aula

## Objetivo da aula

Preparar toda a base do sistema antes de escrever qualquer tela: entender o projeto como um todo, montar o ambiente de trabalho e modelar as tabelas do banco de dados que serão usadas nas próximas aulas.

## Visão geral

Vamos construir, ao longo de 10 aulas, um sistema com tela de login e um CRUD de produtos (Cadastrar, Listar, Atualizar e Remover), sem frameworks, com PHP simples embutido no HTML. O nome da pasta do projeto será **`projeto_php`** — vale anotar esse nome, porque ele vai aparecer em vários caminhos ao longo do curso.

---

## Passo a passo detalhado

### Passo 1 — Instalação e configuração do ambiente

1. Baixar e instalar o XAMPP (ou WAMP/MAMP, conforme o sistema operacional)
2. Iniciar os módulos **Apache** e **MySQL** pelo painel de controle do XAMPP
3. Testar o funcionamento acessando `http://localhost` no navegador
4. Localizar a pasta `htdocs` (é onde o projeto será criado)

> ✅ **Teste agora:** com Apache e MySQL "verdes" (ligados) no painel do XAMPP, acesse `http://localhost`. Você deve ver a página inicial do XAMPP, não um erro de "não foi possível acessar este site".

### Passo 2 — Estrutura de pastas do projeto

Dentro de `htdocs`, criar a pasta `projeto_php` com a seguinte organização:

```
htdocs/
└── projeto_php/
    ├── css/
    │   └── style.css
    ├── conexao.php
    ├── cabecalho.php
    ├── rodape.php
    ├── index.php
    ├── login.php
    ├── logout.php
    └── produtos/
        ├── listar.php
        ├── cadastrar.php
        ├── atualizar.php
        └── excluir.php
```

Por enquanto, basta criar as pastas e deixar os arquivos vazios — eles serão preenchidos nas próximas aulas.

> ⚠️ **Atenção:** o nome `projeto_php` vai aparecer literalmente em vários lugares do código nas próximas aulas (na URL de teste, em alguns redirecionamentos). Se você usar outro nome de pasta, terá que adaptar esses trechos.

### Passo 3 — Criação do banco de dados

1. Acessar o phpMyAdmin em `http://localhost/phpmyadmin`
2. Clicar em **"Novo"** no menu lateral
3. Nomear o banco como `sistema_produtos`
4. Selecionar o cotejamento `utf8mb4_general_ci`
5. Confirmar a criação

> ✅ **Teste agora:** o banco `sistema_produtos` deve aparecer na lista de bancos, no menu lateral esquerdo do phpMyAdmin.

### Passo 4 — Modelagem da tabela `usuarios`

Esta tabela guarda os dados de quem pode acessar o sistema (usada na tela de login, na Aula 3).

| Campo | Tipo | Descrição | Chave | Obrigatório |
|---|---|---|---|---|
| `id` | `int(11)` (auto_increment) | Identificador único | Primária | Sim |
| `nome` | `varchar(100)` | Nome do usuário | — | Sim |
| `email` | `varchar(100)` | E-mail de acesso (login) | — | Sim |
| `senha` | `varchar(255)` | Senha de acesso | — | Sim |

Na aba **SQL** do phpMyAdmin, execute:

```sql
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
```

### Passo 5 — Modelagem da tabela `produtos`

Esta é a tabela principal do CRUD, que será construída a partir da Aula 5.

| Campo | Tipo | Descrição | Chave | Obrigatório |
|---|---|---|---|---|
| `id` | `int(11)` (auto_increment) | Identificador único | Primária | Sim |
| `nome` | `varchar(100)` | Nome do produto | — | Sim |
| `descricao` | `varchar(255)` | Descrição do produto | — | Sim |
| `preco` | `decimal(10,2)` | Preço unitário | — | Sim |
| `quantidade` | `int(11)` | Quantidade em estoque | — | Sim |

```sql
CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `quantidade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
```

> ⚠️ **Atenção:** repare que `descricao` está como `NOT NULL` — diferente do que pode parecer, isso não obriga o campo a ter um texto longo, só exige que não seja `NULL`. Uma string vazia (`''`) já satisfaz essa regra, então o formulário de cadastro (Aula 6) pode enviar descrição em branco sem erro.

### Passo 6 — Inserindo um usuário de teste

Para já deixar pronto para a Aula 3 (tela de login), inserir manualmente um usuário de teste:

```sql
INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`) VALUES
(1, 'Administrador', 'admin@sistema.com', '123456');
```

> ⚠️ **Atenção:** nesta fase inicial, a senha é gravada em texto puro (sem criptografia), apenas para simplificar o aprendizado. Esse ponto é retomado como sugestão de evolução futura na Aula 10.

> ✅ **Teste agora:** na aba "Procurar" da tabela `usuarios` no phpMyAdmin, confirme que a linha com `admin@sistema.com` e senha `123456` aparece corretamente.

---

## Erros comuns nesta aula

| O que você vê | Causa provável | Como resolver |
|---|---|---|
| `http://localhost` não abre | Apache não está rodando | Verifique se o Apache está "verde" no painel do XAMPP; se a porta 80 estiver ocupada, troque a porta nas configurações do Apache |
| phpMyAdmin dá erro de conexão | MySQL não está rodando | Verifique se o MySQL está "verde" no painel do XAMPP |
| Erro ao criar tabela: "already exists" | A tabela já foi criada antes (execução duplicada do script) | Apague a tabela existente antes de rodar o `CREATE TABLE` novamente, ou pule esse passo |
| Erro "Duplicate entry '1' for key PRIMARY" ao inserir o usuário | O `INSERT` já foi executado antes | Confira na aba "Procurar" se o usuário já existe; se sim, não precisa inserir de novo |

## Checklist final antes de seguir para a Aula 2

- [ ] XAMPP instalado, com Apache e MySQL funcionando
- [ ] Pasta `projeto_php` criada dentro de `htdocs`, com a estrutura de pastas do Passo 2
- [ ] Banco `sistema_produtos` criado no phpMyAdmin
- [ ] Tabela `usuarios` criada
- [ ] Tabela `produtos` criada
- [ ] Usuário de teste (`admin@sistema.com` / `123456`) inserido na tabela `usuarios`

## O que vem a seguir

Com o ambiente pronto e o banco modelado, a Aula 2 vai conectar o PHP ao banco de dados e montar a primeira página do sistema, com uma estrutura de HTML reaproveitável entre todas as páginas.
