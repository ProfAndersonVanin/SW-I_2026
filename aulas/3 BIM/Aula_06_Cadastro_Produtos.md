# Aula 6 — Cadastro de Produtos (CREATE)
### Guia de desenvolvimento em laboratório — Sistema CRUD de Produtos com Login (PHP, MySQL, HTML e CSS)

## Antes de começar

Confirme os pré-requisitos abaixo antes de abrir o editor de código:

- [ ] Listagem de produtos (`produtos/listar.php`) funcionando, com link **"Cadastrar novo produto"** apontando para `cadastrar.php` (Aula 5)
- [ ] Login funcionando em `http://localhost/projeto_php/login.php` (usuário `admin@sistema.com` / senha `123456`)

> ⚠️ **Atenção:** se algum dos itens acima não estiver funcionando, resolva isso antes de continuar — este guia parte do princípio de que a Aula 5 já está pronta.

## Objetivo da aula

Preencher o arquivo `produtos/cadastrar.php`, que hoje existe vazio no projeto, com o formulário de cadastro e a lógica que insere um novo produto no banco de dados.

## Visão geral

`produtos/cadastrar.php` vai fazer duas coisas, dependendo de como for acessado:

| Situação | O que a página faz | Método HTTP |
|---|---|---|
| Você clica em "Cadastrar novo produto" | Mostra o formulário vazio | GET |
| Você preenche e clica em "Salvar" | Valida os dados e insere no banco | POST |

---

## Passo a passo

### Passo 1 — Protegendo a página e incluindo a conexão

Abra `produtos/cadastrar.php` (vazio) e comece com a mesma base usada em `listar.php`:

```php
<?php
require __DIR__ . '/verifica_login.php';
require __DIR__ . '/../conexao.php';
?>
```

> ✅ **Teste agora:** salve e acesse `http://localhost/projeto_php/produtos/cadastrar.php` sem estar logado. Você deve ser redirecionado para o login.

### Passo 2 — Recebendo os dados do formulário

Logo abaixo dos `require`, capturar os dados enviados quando o formulário for submetido:

```php
<?php
require __DIR__ . '/verifica_login.php';
require __DIR__ . '/../conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $quantidade = $_POST['quantidade'];
}
?>
```

Campos do formulário e para onde cada um vai:

| Campo do formulário | `name` do input | Coluna na tabela `produtos` |
|---|---|---|
| Nome | `nome` | `nome` |
| Descrição | `descricao` | `descricao` |
| Preço | `preco` | `preco` |
| Quantidade | `quantidade` | `quantidade` |

### Passo 3 — Validando campos obrigatórios

Antes de gravar no banco, verificar se nome, preço e quantidade foram preenchidos:

```php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $quantidade = $_POST['quantidade'];

    if ($nome == "" || $preco == "" || $quantidade == "") {
        $mensagem = "Preencha todos os campos obrigatórios.";
    } else {
        // aqui vai o INSERT, no próximo passo
    }
}
```

### Passo 4 — Inserindo o produto no banco (INSERT)

Dentro do `else` do passo anterior:

```php
$sql = "INSERT INTO produtos (nome, descricao, preco, quantidade)
        VALUES ('$nome', '$descricao', '$preco', '$quantidade')";

if (mysqli_query($conexao, $sql)) {
    header('Location: listar.php');
    exit;
} else {
    $mensagem = "Erro ao cadastrar produto: " . mysqli_error($conexao);
}
```

> ⚠️ **Atenção:** como `cadastrar.php` está na mesma pasta que `listar.php` (ambos dentro de `produtos/`), o `header('Location: listar.php')` usa caminho relativo — não é preciso o caminho absoluto `/projeto_php/...` usado em `login.php`, porque aqui não há troca de pasta.

### Passo 5 — Montando o arquivo completo

Juntar tudo: a lógica no topo do arquivo e o formulário HTML embaixo, reaproveitando cabeçalho e rodapé com o padrão `require __DIR__`:

```php
<?php
require __DIR__ . '/verifica_login.php';
require __DIR__ . '/../conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $quantidade = $_POST['quantidade'];

    if ($nome == "" || $preco == "" || $quantidade == "") {
        $mensagem = "Preencha todos os campos obrigatórios.";
    } else {
        $sql = "INSERT INTO produtos (nome, descricao, preco, quantidade)
                VALUES ('$nome', '$descricao', '$preco', '$quantidade')";

        if (mysqli_query($conexao, $sql)) {
            header('Location: listar.php');
            exit;
        } else {
            $mensagem = "Erro ao cadastrar produto: " . mysqli_error($conexao);
        }
    }
}
?>

<?php require __DIR__ . '/../cabecalho.php'; ?>

<main>
    <h2>Cadastrar Produto</h2>

    <?php if (isset($mensagem)) { ?>
        <p><?php echo $mensagem; ?></p>
    <?php } ?>

    <form action="cadastrar.php" method="POST">
        <label>Nome:</label>
        <input type="text" name="nome"><br>

        <label>Descrição:</label>
        <input type="text" name="descricao"><br>

        <label>Preço:</label>
        <input type="text" name="preco"><br>

        <label>Quantidade:</label>
        <input type="text" name="quantidade"><br>

        <button type="submit">Salvar</button>
    </form>
</main>

<?php require __DIR__ . '/../rodape.php'; ?>
```

> ✅ **Teste agora:** acesse a página pelo link "Cadastrar novo produto" na listagem, tente salvar com campos vazios (deve mostrar erro) e depois cadastre um produto válido (deve voltar para a listagem e o produto deve aparecer nela).

---

## Erros comuns nesta aula

| O que você vê | Causa provável | Como resolver |
|---|---|---|
| Página em branco ao salvar | Faltou `header('Location: listar.php'); exit;` dentro do `if` de sucesso | Confira se as duas linhas estão dentro do bloco `if (mysqli_query(...))` |
| "Preencha todos os campos" mesmo com tudo preenchido | Algum `name` do `<input>` está diferente do usado em `$_POST['...']` | Confira se `nome`, `preco` e `quantidade` batem exatamente |
| Produto cadastrado com campos em branco | Faltou a validação `if ($nome == "" ...)` | Confira se o bloco de validação está antes do INSERT |
| Erro de sintaxe SQL | Aspas ou vírgula faltando no comando INSERT | Compare com o código do Passo 5, vírgula por vírgula |

## Checklist final antes de seguir para a Aula 7

- [ ] `produtos/cadastrar.php` preenchido com a lógica de recebimento e validação dos dados
- [ ] `INSERT INTO produtos` executado quando os dados são válidos
- [ ] Redirecionamento para `listar.php` após o cadastro
- [ ] Mensagem de erro exibida quando falta algum campo obrigatório
- [ ] Testei o cadastro com dados válidos e inválidos

## O que vem a seguir

Com `Read` (Aula 5) e `Create` (esta aula) prontos, a Aula 7 vai preencher o arquivo `produtos/atualizar.php`, hoje vazio, com a lógica de edição de produtos existentes.
