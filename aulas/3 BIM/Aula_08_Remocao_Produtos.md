# Aula 8 — Remoção de Produtos (DELETE)
### Guia de desenvolvimento em laboratório — Sistema CRUD de Produtos com Login (PHP, MySQL, HTML e CSS)

## Antes de começar

- [ ] `produtos/atualizar.php` funcionando: editar um produto altera os dados corretamente (Aula 7)
- [ ] `produtos/listar.php` com link "Excluir" em cada linha da tabela
- [ ] Pelo menos 3 produtos cadastrados — você vai precisar "sacrificar" um deles para testar a exclusão

> ⚠️ **Atenção:** como esta aula apaga dados de verdade, cadastre um produto de teste específico para excluir (ex: "Produto Teste Exclusão") em vez de usar um produto que você queira manter.

## Objetivo da aula

Preencher o arquivo `produtos/excluir.php` (hoje vazio), completando o CRUD com a exclusão de produtos — com uma tela de confirmação antes de remover o registro definitivamente do banco.

## Visão geral: por que pedir confirmação antes de excluir

Diferente do UPDATE, a exclusão é definitiva — não existe "desfazer" depois de rodar um DELETE no banco. Por isso, antes de excluir de verdade, vamos mostrar uma tela perguntando "Tem certeza?", e só executar o DELETE se a resposta for sim.

| Situação | O que a página faz | Método HTTP |
|---|---|---|
| Você clica em "Excluir" na listagem | Mostra o nome do produto e pede confirmação | GET (`?id=...`) |
| Você confirma a exclusão | Executa o DELETE e volta para a listagem | POST |

---

## Passo a passo detalhado

### Passo 1 — Protegendo a página

```php
<?php
require __DIR__ . '/verifica_login.php';
require __DIR__ . '/../conexao.php';
?>
```

> ✅ **Teste agora:** acesse `http://localhost/projeto_php/produtos/excluir.php` diretamente, sem estar logado. Você deve ser redirecionado para o login.

### Passo 2 — Buscar o produto que será excluído

```php
<?php
require __DIR__ . '/verifica_login.php';
require __DIR__ . '/../conexao.php';

$id = $_GET['id'];
$sql = "SELECT * FROM produtos WHERE id = '$id'";
$resultado = mysqli_query($conexao, $sql);
$produto = mysqli_fetch_assoc($resultado);
?>
```

Essa é a mesma lógica de busca usada em `atualizar.php` na Aula 7 — a diferença vem no que fazemos com esses dados a seguir.

### Passo 3 — Montar a tela de confirmação

```php
<?php require __DIR__ . '/../cabecalho.php'; ?>

<main>
    <h2>Excluir Produto</h2>
    <p>Tem certeza que deseja excluir o produto
       <strong><?php echo $produto['nome']; ?></strong>?</p>

    <form action="excluir.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">
        <button type="submit">Sim, excluir</button>
        <a href="listar.php">Cancelar</a>
    </form>
</main>

<?php require __DIR__ . '/../rodape.php'; ?>
```

> ⚠️ **Atenção:** a exclusão em si não acontece pelo link "Excluir" da listagem — esse link só abre esta tela de confirmação (GET). A exclusão de verdade só acontece quando o formulário desta tela é enviado (POST). É por isso que existe um `<form>` aqui, e não um link direto para apagar.

> ✅ **Teste agora:** clique em "Excluir" em um produto na listagem. Você deve ver a tela de confirmação com o nome correto do produto. Clique em "Cancelar" e confirme que volta para a listagem sem nada ter sido apagado.

### Passo 4 — Implementar o DELETE (quando a exclusão é confirmada)

Volte ao topo do arquivo e envolva a lógica do Passo 2 dentro de um `if`/`else`:

```php
<?php
require __DIR__ . '/verifica_login.php';
require __DIR__ . '/../conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    $sql = "DELETE FROM produtos WHERE id = '$id'";
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

A mesma estrutura da Aula 7: GET mostra a tela (`else`), POST executa a ação (`if`). A diferença é o comando `DELETE FROM`, que remove a linha inteira, em vez de `UPDATE`.

> ✅ **Teste agora:** no produto de teste separado no início da aula, clique em Excluir e depois em "Sim, excluir". Você deve voltar para a listagem, e esse produto não deve mais aparecer.

---

## Erros comuns nesta aula

| O que você vê | Causa provável | Como resolver |
|---|---|---|
| Nome do produto em branco na confirmação | SELECT não encontrou o produto (id errado) | Volte à listagem e clique em Excluir novamente |
| "Sim, excluir" não remove o produto | Faltou o campo `hidden` com o `id`, ou `name` errado | Confira o `<input type="hidden" name="id">` dentro do `<form>` |
| Produto errado foi excluído | O `id` usado no DELETE não é o do produto mostrado | Confira se o `value` do hidden usa `$produto['id']` |
| Cancelar também exclui o produto | O link Cancelar virou um botão dentro do `<form>` | Cancelar deve ser um `<a href="listar.php">`, fora do `<form>` |
| Erro ao abrir a página pela listagem | Faltou algum `require __DIR__` no topo do arquivo | Compare com `listar.php` e `atualizar.php` |

## Arquivo final — `produtos/excluir.php`

```php
<?php
require __DIR__ . '/verifica_login.php';
require __DIR__ . '/../conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    $sql = "DELETE FROM produtos WHERE id = '$id'";
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
    <h2>Excluir Produto</h2>
    <p>Tem certeza que deseja excluir o produto
       <strong><?php echo $produto['nome']; ?></strong>?</p>

    <form action="excluir.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">
        <button type="submit">Sim, excluir</button>
        <a href="listar.php">Cancelar</a>
    </form>
</main>

<?php require __DIR__ . '/../rodape.php'; ?>
```

## Checklist final antes de seguir para a Aula 9

- [ ] `produtos/excluir.php` criado com a lógica de if/else (POST exclui, GET busca e confirma)
- [ ] Tela de confirmação mostra corretamente o nome do produto
- [ ] Link Cancelar volta para a listagem sem excluir nada
- [ ] Botão "Sim, excluir" remove o produto e volta para a listagem
- [ ] Testei o fluxo completo pelo menos duas vezes: excluir, cancelar e excluir de novo

## O que vem a seguir

Com Cadastrar, Listar, Atualizar e Remover funcionando, o CRUD está funcionalmente completo. As Aulas 9 e 10 não adicionam telas novas — a Aula 9 revisa e reforça o código já existente, e a Aula 10 faz um teste guiado de ponta a ponta.
