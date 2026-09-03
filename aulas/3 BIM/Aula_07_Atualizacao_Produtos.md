# Aula 7 — Atualização de Produtos (UPDATE)
### Guia de desenvolvimento em laboratório — Sistema CRUD de Produtos com Login (PHP, MySQL, HTML e CSS)

## Antes de começar

Siga cada passo na ordem, testando sempre que houver um bloco **"Teste agora"**. Se um teste falhar, pare e revise o passo antes de continuar.

- [ ] Login funcionando em `http://localhost/projeto_php/login.php` (`admin@sistema.com` / `123456`)
- [ ] `produtos/listar.php` exibindo produtos cadastrados, com link "Editar" em cada linha
- [ ] `produtos/cadastrar.php` funcionando, com pelo menos 2 produtos cadastrados para usar como teste

## Objetivo da aula

Preencher o arquivo `produtos/atualizar.php` (hoje vazio) para editar um produto existente: buscar seus dados atuais, exibi-los em um formulário já preenchido e gravar as alterações no banco quando confirmado.

## Visão geral: por que essa página funciona em duas etapas

Diferente do cadastro (que só recebe dados e insere), a atualização precisa primeiro mostrar o que já existe. O mesmo arquivo `atualizar.php` vai se comportar de dois jeitos diferentes:

| Etapa | O que acontece | Método HTTP |
|---|---|---|
| 1 | Você clica em "Editar" na listagem | GET (`?id=...`) |
| 2 | A página busca o produto pelo id e preenche o formulário | SELECT |
| 3 | Você altera os dados e clica em "Salvar alterações" | POST |
| 4 | A página grava as alterações no banco | UPDATE |

Você reconhece qual das duas situações está acontecendo checando `$_SERVER['REQUEST_METHOD']` — um detalhe que o PHP preenche sozinho, dizendo se a página foi aberta por um link (GET) ou por um formulário enviado (POST).

---

## Passo a passo detalhado

### Passo 1 — Abrir o arquivo e escrever a proteção da página

```php
<?php
require __DIR__ . '/verifica_login.php';
require __DIR__ . '/../conexao.php';
?>
```

> ✅ **Teste agora:** acesse `http://localhost/projeto_php/produtos/atualizar.php` diretamente, sem estar logado. Você deve ser redirecionado para o login.

### Passo 2 — Capturar o id do produto

Quando você clica em "Editar" na listagem, o link enviado é algo como `atualizar.php?id=3`. Esse número chega no PHP através de `$_GET`.

```php
<?php
require __DIR__ . '/verifica_login.php';
require __DIR__ . '/../conexao.php';

$id = $_GET['id'];
?>
```

> ⚠️ **Atenção:** se você acessar `atualizar.php` diretamente pela URL, sem `?id=3` no final, essa linha vai gerar um aviso (`Undefined array key "id"`). Por enquanto isso é esperado — sempre teste clicando no link Editar da listagem, nunca digitando a URL direto.

### Passo 3 — Buscar os dados do produto no banco

```php
<?php
$sql = "SELECT * FROM produtos WHERE id = '$id'";
$resultado = mysqli_query($conexao, $sql);
$produto = mysqli_fetch_assoc($resultado);
?>
```

`mysqli_fetch_assoc($resultado)` pega a única linha que o SELECT encontrou (já que `id` é único) e transforma em um array associativo — por isso dá para acessar `$produto['nome']`, `$produto['preco']`, e assim por diante.

> ✅ **Teste agora:** para confirmar que os dados chegaram certos, adicione temporariamente `var_dump($produto);` logo depois do código acima, salve e acesse a página clicando em Editar em algum produto. Você deve ver um array preenchido. Depois de conferir, **apague essa linha** de `var_dump` — ela era só para teste.

### Passo 4 — Montar o formulário já preenchido

```php
<?php require __DIR__ . '/../cabecalho.php'; ?>

<main>
    <h2>Atualizar Produto</h2>
    <form action="atualizar.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">

        <label>Nome:</label>
        <input type="text" name="nome" value="<?php echo $produto['nome']; ?>"><br>

        <label>Descrição:</label>
        <input type="text" name="descricao" value="<?php echo $produto['descricao']; ?>"><br>

        <label>Preço:</label>
        <input type="text" name="preco" value="<?php echo $produto['preco']; ?>"><br>

        <label>Quantidade:</label>
        <input type="text" name="quantidade" value="<?php echo $produto['quantidade']; ?>"><br>

        <button type="submit">Salvar alterações</button>
    </form>
</main>

<?php require __DIR__ . '/../rodape.php'; ?>
```

> ⚠️ **Atenção:** repare no `input type="hidden" name="id"`. Ele não aparece visualmente na tela, mas viaja junto com o formulário quando você clica em Salvar. Sem ele, o PHP não saberia qual produto atualizar depois — essa é a causa mais comum de erro nesta aula.

> ✅ **Teste agora:** clique em "Editar" em um produto da listagem. Você deve ver o formulário já preenchido com os dados do produto clicado — e não campos em branco.

### Passo 5 — Implementar o UPDATE (quando o formulário é enviado)

Volte ao topo do arquivo e envolva a lógica dos Passos 2 e 3 dentro de um `if`/`else`, testando `$_SERVER['REQUEST_METHOD']`:

```php
<?php
require __DIR__ . '/verifica_login.php';
require __DIR__ . '/../conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $quantidade = $_POST['quantidade'];

    $sql = "UPDATE produtos SET
            nome = '$nome',
            descricao = '$descricao',
            preco = '$preco',
            quantidade = '$quantidade'
            WHERE id = '$id'";

    mysqli_query($conexao, $sql);

    header('Location: listar.php');
    exit;
} else {
    $id = $_GET['id'];
    $sql = "SELECT * FROM produtos WHERE id = '$id'";
    $resultado = mysqli_query($conexao, $sql);
    $produto = mysqli_fetch_assoc($resultado);
}
?>
```

Se a página foi aberta pelo link Editar (GET), cai no `else` — busca e mostra os dados. Se recebeu o formulário enviado (POST), cai no `if` — grava o UPDATE e redireciona.

> ✅ **Teste agora:** edite um produto, altere o campo Preço e clique em Salvar alterações. Você deve voltar automaticamente para a listagem, e o preço novo deve aparecer lá.

---

## Erros comuns nesta aula

| O que você vê | Causa provável | Como resolver |
|---|---|---|
| Página em branco após Salvar | Faltou `header('Location: listar.php'); exit;`, ou faltou `method="POST"` no `<form>` | Confira o `<form>` e as linhas de `header`/`exit` dentro do `if` |
| `Undefined array key "id"` ao clicar em Salvar | Campo `hidden` esquecido ou com `name` diferente de `id` | Confira o campo hidden dentro do `<form>` |
| Formulário abre com campos vazios | SELECT não encontrou o produto (id errado) | Volte à listagem e clique em Editar novamente |
| Dados não mudam na listagem | Algum `name` do `<input>` diferente do usado em `$_POST['...']` | Confira `nome`, `descricao`, `preco`, `quantidade` |
| `mysqli_fetch_assoc(): Argument #1 must be of type mysqli_result, bool given` | Erro de sintaxe SQL (aspas/vírgula faltando) | Revise o SQL com calma |

## Arquivo final — `produtos/atualizar.php`

```php
<?php
require __DIR__ . '/verifica_login.php';
require __DIR__ . '/../conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $quantidade = $_POST['quantidade'];

    $sql = "UPDATE produtos SET
            nome = '$nome',
            descricao = '$descricao',
            preco = '$preco',
            quantidade = '$quantidade'
            WHERE id = '$id'";

    mysqli_query($conexao, $sql);

    header('Location: listar.php');
    exit;
} else {
    $id = $_GET['id'];
    $sql = "SELECT * FROM produtos WHERE id = '$id'";
    $resultado = mysqli_query($conexao, $sql);
    $produto = mysqli_fetch_assoc($resultado);
}
?>

<?php require __DIR__ . '/../cabecalho.php'; ?>

<main>
    <h2>Atualizar Produto</h2>
    <form action="atualizar.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">

        <label>Nome:</label>
        <input type="text" name="nome" value="<?php echo $produto['nome']; ?>"><br>

        <label>Descrição:</label>
        <input type="text" name="descricao" value="<?php echo $produto['descricao']; ?>"><br>

        <label>Preço:</label>
        <input type="text" name="preco" value="<?php echo $produto['preco']; ?>"><br>

        <label>Quantidade:</label>
        <input type="text" name="quantidade" value="<?php echo $produto['quantidade']; ?>"><br>

        <button type="submit">Salvar alterações</button>
    </form>
</main>

<?php require __DIR__ . '/../rodape.php'; ?>
```

## Checklist final antes de seguir para a Aula 8

- [ ] `produtos/atualizar.php` criado com a lógica de if/else (POST grava, GET busca e exibe)
- [ ] Formulário aparece preenchido com os dados corretos ao clicar em Editar
- [ ] Alterar um campo e salvar leva de volta para a listagem, já atualizada
- [ ] Testei editar pelo menos dois produtos diferentes
- [ ] Removi a linha `var_dump($produto)` usada só para teste

## O que vem a seguir

Com Read, Create e Update prontos, falta só uma letra do CRUD. A Aula 8 vai preencher `produtos/excluir.php`, completando a exclusão (Delete) de produtos.
