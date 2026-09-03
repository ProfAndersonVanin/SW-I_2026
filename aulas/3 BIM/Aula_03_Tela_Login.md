# Aula 3 — Tela de Login (Formulário e Validação)
### Guia de desenvolvimento em laboratório — Sistema CRUD de Produtos com Login (PHP, MySQL, HTML e CSS)

## Antes de começar

- [ ] Página inicial (`index.php`) funcionando, com cabeçalho, rodapé e conexão com o banco (Aula 2)
- [ ] Tabela `usuarios` criada, com o usuário de teste `admin@sistema.com` / `123456` (Aula 1)

## Objetivo da aula

Criar a porta de entrada do sistema: um formulário de login que recebe e-mail e senha, consulta o banco de dados e valida se o usuário pode ou não entrar. Nesta aula, o login ainda **não** vai gravar sessão — isso fica para a Aula 4.

---

## Passo a passo detalhado

### Passo 1 — Criando o formulário de login (`login.php`)

Reaproveitando o cabeçalho e o rodapé já criados na Aula 2:

```php
<?php require __DIR__ . '/cabecalho.php'; ?>

<main>
    <h2>Login</h2>
    <form action="/projeto_php/login.php" method="POST">
        <label>E-mail:</label>
        <input type="text" name="email"><br>

        <label>Senha:</label>
        <input type="password" name="senha"><br>

        <button type="submit">Entrar</button>
    </form>
</main>

<?php require __DIR__ . '/rodape.php'; ?>
```

O campo de senha usa `type="password"` (oculta o que é digitado). O `action="/projeto_php/login.php"` usa caminho absoluto — já adiantando o padrão que o projeto vai seguir sempre que um caminho precisa funcionar de forma confiável, independente de outros detalhes da URL atual.

> ✅ **Teste agora:** acesse `http://localhost/projeto_php/login.php`. Você deve ver o formulário de login (ainda sem funcionar de verdade).

### Passo 2 — Recebendo os dados enviados pelo formulário

No topo do próprio `login.php`, antes do HTML, incluir a conexão e capturar os dados enviados quando o botão Entrar for clicado.

```php
<?php
require __DIR__ . '/conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
}
?>
```

`$_POST['email']` e `$_POST['senha']` pegam exatamente o que foi digitado nos campos com `name="email"` e `name="senha"` do formulário.

### Passo 3 — Consultando o banco para validar o login

```php
<?php
require __DIR__ . '/conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha'";
    $resultado = mysqli_query($conexao, $sql);

    if (mysqli_num_rows($resultado) == 1) {
        $mensagem = 'Login realizado com sucesso!';
    } else {
        $mensagem = 'E-mail ou senha inválidos.';
    }
}
?>
```

`mysqli_num_rows($resultado)` conta quantas linhas a consulta encontrou: se for exatamente 1, existe um usuário com aquele e-mail e senha.

### Passo 4 — Exibindo a mensagem de erro ou sucesso na tela

```php
<?php require __DIR__ . '/cabecalho.php'; ?>

<main>
    <h2>Login</h2>

    <?php if (isset($mensagem)) { ?>
        <p><?php echo $mensagem; ?></p>
    <?php } ?>

    <form action="/projeto_php/login.php" method="POST">
        <label>E-mail:</label>
        <input type="text" name="email"><br>

        <label>Senha:</label>
        <input type="password" name="senha"><br>

        <button type="submit">Entrar</button>
    </form>
</main>

<?php require __DIR__ . '/rodape.php'; ?>
```

> ✅ **Teste agora:** acesse o login e teste com `admin@sistema.com` / `123456` — deve aparecer "Login realizado com sucesso!". Teste também com uma senha errada — deve aparecer "E-mail ou senha inválidos.".

### Passo 5 — Estilizando a tela de login

Adicionar ao final do `css/style.css`:

```css
/* Aula 03 - formulario de login */
form {
    max-width: 320px;
    background-color: white;
    padding: 20px;
    border-radius: 6px;
}

form label {
    display: block;
    margin-top: 10px;
    font-weight: bold;
}

form input {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    box-sizing: border-box;
}

form button {
    margin-top: 15px;
    padding: 8px 16px;
    background-color: #1f4e79;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
```

> ✅ **Teste agora:** o formulário de login deve aparecer dentro de um cartão branco com cantos arredondados, e o botão "Entrar" deve estar azul.

---

## Erros comuns nesta aula

| O que você vê | Causa provável | Como resolver |
|---|---|---|
| "E-mail ou senha inválidos" mesmo com os dados certos | Digitou os dados errados, ou o usuário de teste não foi inserido no banco (Aula 1) | Confira na tabela `usuarios` do phpMyAdmin se o registro existe exatamente como `admin@sistema.com` / `123456` |
| Nada acontece ao clicar em Entrar | Faltou `method="POST"` no `<form>`, ou o `action` está com a URL errada | Confira o `<form action="/projeto_php/login.php" method="POST">` |
| Erro de conexão com o banco | `conexao.php` não incluído, ou banco fora do ar | Confira se `require __DIR__ . '/conexao.php';` está no topo do arquivo |
| Mensagem aparece duplicada ou não aparece | Bloco `if (isset($mensagem))` fora do lugar certo no HTML | Confira se esse bloco está entre o `<h2>Login</h2>` e o `<form>` |

## Checklist final antes de seguir para a Aula 4

- [ ] `login.php` criado, reaproveitando cabeçalho e rodapé
- [ ] Dados capturados via `$_POST` e validados com `SELECT` no banco
- [ ] Mensagem de sucesso ou erro exibida corretamente
- [ ] Testei com dados corretos e também com dados incorretos
- [ ] Estilização do formulário aplicada no `style.css`

## O que vem a seguir

Por enquanto, o login apenas exibe uma mensagem — ele não "lembra" que o usuário está logado ao navegar para outra página. A Aula 4 vai transformar esse acesso validado em uma sessão de verdade, protegendo as páginas internas do sistema.
