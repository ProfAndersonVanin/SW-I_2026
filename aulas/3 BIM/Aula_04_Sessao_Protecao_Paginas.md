# Aula 4 — Sessão de Usuário e Proteção das Páginas
### Guia de desenvolvimento em laboratório — Sistema CRUD de Produtos com Login (PHP, MySQL, HTML e CSS)

## Antes de começar

- [ ] Tela de login funcionando e validando e-mail/senha no banco de dados (Aula 3)
- [ ] `cabecalho.php` e `rodape.php` reaproveitados via `require __DIR__` (Aula 2)

## Objetivo da aula

Transformar o login validado na Aula 3 em uma sessão de verdade, garantindo que só usuários logados consigam acessar as páginas dentro de `produtos/`, e criar a página de logout.

## Visão geral: por que `verifica_login.php` fica dentro de `produtos/`

Diferente do que se poderia imaginar, o arquivo que verifica a sessão não fica na raiz do projeto, e sim **dentro da pasta `produtos/`** — porque, neste projeto, são justamente as páginas de produtos que precisam de proteção (`index.php` e `login.php` continuam públicas).

| Arquivo | Função | Onde fica / é incluído |
|---|---|---|
| `produtos/verifica_login.php` | Verifica se existe usuário na sessão; se não, redireciona para o login | Dentro de `produtos/` — incluído no topo de `listar.php`, `cadastrar.php`, `atualizar.php` e `excluir.php` |
| `logout.php` | Encerra a sessão do usuário | Na raiz do projeto — acessado pelo link "Sair" do menu |

---

## Passo a passo detalhado

### Passo 1 — Iniciando a sessão (`session_start`)

Toda página que for usar sessão precisa chamar `session_start()` antes de qualquer outra coisa, inclusive antes do `require` do cabeçalho.

```php
<?php
session_start();
require __DIR__ . '/conexao.php';
?>
```

`session_start()` precisa ser a primeira linha do arquivo (antes de qualquer HTML ou espaço em branco), senão o PHP retorna um erro.

### Passo 2 — Gravando o usuário logado na sessão (`login.php`)

No `login.php`, quando a consulta encontrar o usuário (`mysqli_num_rows == 1`), gravar os dados dele em `$_SESSION` em vez de apenas montar uma mensagem de sucesso.

```php
<?php
session_start();
require __DIR__ . '/conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha'";
    $resultado = mysqli_query($conexao, $sql);

    if (mysqli_num_rows($resultado) == 1) {
        $usuario = mysqli_fetch_assoc($resultado);
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        header('Location: /projeto_php/produtos/listar.php');
        exit;
    } else {
        $mensagem = 'E-mail ou senha inválidos.';
    }
}
?>

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

`mysqli_fetch_assoc` pega os dados do usuário encontrado, e `header('Location: ...')` redireciona automaticamente para a listagem de produtos assim que o login der certo. O `exit;` logo depois é importante para garantir que o restante do código pare de executar.

> ⚠️ **Atenção:** repare que esse `Location` usa o caminho absoluto `/projeto_php/produtos/listar.php` (começando com `/`), e não um caminho relativo como `produtos/listar.php`. Isso é proposital: `login.php` está na raiz do projeto, mas o destino (`produtos/listar.php`) está em outra pasta — usar o caminho absoluto evita qualquer ambiguidade sobre a partir de onde o navegador deve calcular o redirecionamento. O mesmo vale para o `action="/projeto_php/login.php"` do formulário.

### Passo 3 — Criando o `produtos/verifica_login.php`

```php
<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: /projeto_php/login.php');
    exit;
}
?>
```

`isset($_SESSION['usuario_id'])`: se essa variável não existir, significa que ninguém fez login, então a página redireciona de volta para o login. Aqui também é usado o caminho absoluto `/projeto_php/login.php`, pelo mesmo motivo do Passo 2: o arquivo que inclui `verifica_login.php` pode estar em `produtos/`, então um caminho relativo como `login.php` (sem barra) apontaria, por engano, para dentro da própria pasta `produtos/`.

### Passo 4 — Protegendo as páginas dentro de `produtos/`

Incluir o `verifica_login.php` como a primeira linha de toda página que só pode ser acessada por quem está logado. Por exemplo, em `produtos/listar.php`:

```php
<?php
require __DIR__ . '/verifica_login.php';
require __DIR__ . '/../cabecalho.php';
?>

<main>
    <p>Bem-vindo(a), <?php echo $_SESSION['usuario_nome']; ?>!</p>
    <!-- conteúdo da página -->
</main>

<?php
require __DIR__ . '/../rodape.php';
?>
```

Como `verifica_login.php` está na mesma pasta que `listar.php` (`produtos/`), o `require` usa `__DIR__ . '/verifica_login.php'`, sem precisar subir pasta nenhuma. Já `cabecalho.php` e `rodape.php` estão na raiz, uma pasta acima — por isso `__DIR__ . '/../cabecalho.php'` e `__DIR__ . '/../rodape.php'`.

> ✅ **Teste agora:** tente acessar `http://localhost/projeto_php/produtos/listar.php` diretamente, sem estar logado. Você deve ser redirecionado automaticamente para `login.php`.

> ✅ **Teste agora:** faça login com o usuário de teste e confirme que agora o acesso é permitido, que o nome do usuário logado aparece na página, e que o CSS e os links do menu (definidos com `$base_url` em `cabecalho.php`) aparecem corretamente também nessa página.

### Passo 5 — Criando o logout (`logout.php`)

```php
<?php
    session_start();
    session_destroy();
    header("Location: login.php");
    exit;
?>
```

Diferente dos Passos 2 e 3, aqui o `Location: login.php` pode ser relativo, porque `logout.php` e `login.php` estão os dois na raiz do projeto — não há troca de pasta nesse redirecionamento.

### Passo 6 — Conferindo o link de logout no menu

O `cabecalho.php` já reaproveita a variável `$base_url` (criada na Aula 2) para montar o link de logout junto com os demais itens do menu:

```php
<nav>
    <a href="<?php echo $base_url; ?>index.php">Início</a>
    <a href="<?php echo $base_url; ?>produtos/listar.php">Produtos</a>
    <a href="<?php echo $base_url; ?>login.php">Login</a>
    <a href="<?php echo $base_url; ?>logout.php">Sair</a>
</nav>
```

Como `$base_url` já vale `/projeto_php/`, esse link funciona igual em qualquer página do sistema, esteja ela na raiz ou dentro de `produtos/` — o mesmo raciocínio dos caminhos absolutos usados nos Passos 2 e 3, só que aplicado dentro do HTML em vez de dentro de um `header()`.

> ✅ **Teste agora:** clique em "Sair" estando logado. Você deve voltar para o login, e uma nova tentativa de acessar `produtos/listar.php` deve te redirecionar de volta para lá.

---

## Erros comuns nesta aula

| O que você vê | Causa provável | Como resolver |
|---|---|---|
| "Headers already sent" ao carregar login.php | `session_start()` não é a primeira linha do arquivo (tem espaço ou HTML antes) | Confira se não há nenhum espaço em branco ou linha antes de `<?php session_start();` |
| Login funciona mas sempre volta para o login (loop) | `$_SESSION['usuario_id']` não foi gravado corretamente no login.php | Confira se as linhas `$_SESSION['usuario_id'] = ...` estão dentro do `if (mysqli_num_rows($resultado) == 1)` |
| CSS ou menu quebrados dentro de `produtos/` | `cabecalho.php` usando caminho relativo em vez de `$base_url` | Confira se todos os `href` e o `<link>` do cabeçalho usam `<?php echo $base_url; ?>` |
| Acesso a `produtos/listar.php` não redireciona quando deslogado | Faltou o `require __DIR__ . '/verifica_login.php';` no topo do arquivo, ou ele não é a primeira linha | Confira a ordem dos `require` em `listar.php` |

## Checklist final antes de seguir para a Aula 5

- [ ] `session_start()` adicionado no `login.php`, gravando `usuario_id` e `usuario_nome` na sessão após o login
- [ ] `produtos/verifica_login.php` criado
- [ ] `verifica_login.php` incluído no topo de `produtos/listar.php`, usando `require __DIR__`
- [ ] Testei o acesso direto à página interna sem login, confirmando o redirecionamento
- [ ] CSS e menu funcionam corretamente também dentro de `produtos/`
- [ ] `logout.php` criado e testado

## O que vem a seguir

Com login, sessão e proteção prontos — usando caminhos absolutos (`/projeto_php/...`) sempre que o redirecionamento cruza de uma pasta para outra, e caminhos relativos quando origem e destino estão na mesma pasta — a Aula 5 vai começar o CRUD de produtos propriamente dito, com a listagem de produtos vindos do banco de dados.
