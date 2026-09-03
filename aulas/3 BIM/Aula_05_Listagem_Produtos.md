# Aula 5 — Listagem de Produtos (READ)
### Guia de desenvolvimento em laboratório — Sistema CRUD de Produtos com Login (PHP, MySQL, HTML e CSS)

## Antes de começar

- [ ] Login, sessão e proteção de páginas internas funcionando, com `produtos/verifica_login.php` (Aula 4)
- [ ] Tabela `produtos` criada no banco (ver `banco.sql`)

## Objetivo da aula

Substituir o esqueleto criado na Aula 4 por uma listagem real: exibir os produtos cadastrados no banco de dados `sistema_produtos`, na página `produtos/listar.php`.

---

## Passo a passo detalhado

### Passo 1 — Revisão rápida do estado atual

Relembrar o estado atual do arquivo `produtos/listar.php`, criado na Aula 4:

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

Hoje o comentário `<!-- conteúdo da página -->` vai virar a listagem de verdade, vinda do banco de dados.

### Passo 2 — Incluindo a conexão com o banco

Adicionar o `require` da conexão logo após o `verifica_login.php`:

```php
<?php
require __DIR__ . '/verifica_login.php';
require __DIR__ . '/../conexao.php';
?>
```

Como `listar.php` está dentro de `produtos/`, o caminho `../conexao.php` sobe uma pasta para chegar até a raiz do projeto — o mesmo raciocínio já usado para `../cabecalho.php` e `../rodape.php`.

### Passo 3 — Consultando todos os produtos (`SELECT`)

```php
<?php
require __DIR__ . '/verifica_login.php';
require __DIR__ . '/../conexao.php';

$sql = "SELECT * FROM produtos";
$resultado = mysqli_query($conexao, $sql);
?>
```

### Passo 4 — Percorrendo o resultado com `while`

Cada linha retornada pelo banco precisa ser lida uma de cada vez, usando `mysqli_fetch_assoc` dentro de um laço `while`.

| Coluna da tabela | Coluna no HTML | Origem do dado |
|---|---|---|
| `nome` | Produto | `$produto['nome']` |
| `descricao` | Descrição | `$produto['descricao']` |
| `preco` | Preço | `$produto['preco']` |
| `quantidade` | Qtd. | `$produto['quantidade']` |

A cada volta do `while`, `$produto` passa a ser um array com os dados de uma linha da tabela — e o `while` para automaticamente quando não há mais linhas.

### Passo 5 — Montando o arquivo completo (`produtos/listar.php`)

Substituir o conteúdo atual do arquivo pela versão abaixo. O link "Cadastrar novo produto" e os links de Editar/Excluir apontam para arquivos dentro da própria pasta `produtos/`, por isso continuam sendo caminhos relativos simples, sem precisar de `$base_url`:

```php
<?php
require __DIR__ . '/verifica_login.php';
require __DIR__ . '/../conexao.php';

$sql = "SELECT * FROM produtos";
$resultado = mysqli_query($conexao, $sql);
?>

<?php require __DIR__ . '/../cabecalho.php'; ?>

<main>
    <h2>Produtos cadastrados</h2>
    <a href="cadastrar.php">Cadastrar novo produto</a>

    <table>
        <tr>
            <th>Produto</th>
            <th>Descrição</th>
            <th>Preço</th>
            <th>Qtd.</th>
            <th>Ações</th>
        </tr>

        <?php while ($produto = mysqli_fetch_assoc($resultado)) { ?>
        <tr>
            <td><?php echo $produto['nome']; ?></td>
            <td><?php echo $produto['descricao']; ?></td>
            <td>R$ <?php echo $produto['preco']; ?></td>
            <td><?php echo $produto['quantidade']; ?></td>
            <td>
                <a href="atualizar.php?id=<?php echo $produto['id']; ?>">Editar</a>
                <a href="excluir.php?id=<?php echo $produto['id']; ?>">Excluir</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</main>

<?php require __DIR__ . '/../rodape.php'; ?>
```

> ⚠️ **Atenção:** `cadastrar.php`, `atualizar.php` e `excluir.php` já existem na pasta `produtos/` (criados vazios no projeto), mas ainda não têm conteúdo — eles serão preenchidos nas Aulas 6, 7 e 8.

> ✅ **Teste agora:** cadastre manualmente 2 ou 3 produtos direto no phpMyAdmin (para ter dados de teste), faça login no sistema e acesse `http://localhost/projeto_php/produtos/listar.php`. Os produtos devem aparecer na tabela.

### Passo 6 — Estilizando a tabela

Adicionar ao final do `css/style.css`:

```css
/* Aula 05 - tabela de produtos */
table {
    width: 100%;
    border-collapse: collapse;
    background-color: white;
    margin-top: 15px;
}

table th, table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

table th {
    background-color: #1f4e79;
    color: white;
}
```

> ✅ **Teste agora:** a tabela de produtos deve aparecer com cabeçalho azul, linhas com bordas visíveis e boa legibilidade.

---

## Erros comuns nesta aula

| O que você vê | Causa provável | Como resolver |
|---|---|---|
| Tabela aparece vazia, sem nenhum produto | Nenhum produto foi cadastrado ainda no banco | Cadastre alguns produtos de teste direto no phpMyAdmin |
| Erro `mysqli_fetch_assoc(): Argument #1 must be of type mysqli_result, bool given` | Erro de sintaxe no `SELECT` | Confira se o comando SQL está exatamente `SELECT * FROM produtos` |
| Links de Editar/Excluir não levam a lugar nenhum | Esperado nesta aula — os arquivos ainda estão vazios | Serão preenchidos nas Aulas 6, 7 e 8 |
| Página em branco ao acessar `listar.php` | Erro de sintaxe no bloco `while` (chaves não fechadas corretamente) | Compare com o código do Passo 5, prestando atenção às tags `<?php` e `?>` de abertura e fechamento dentro do `while` |

## Checklist final antes de seguir para a Aula 6

- [ ] `produtos/listar.php` atualizado com a conexão e a consulta `SELECT * FROM produtos`
- [ ] Resultado percorrido com `while`, exibindo os produtos em uma tabela HTML
- [ ] Links de Editar e Excluir passando o `id` do produto pela URL
- [ ] Produtos de teste cadastrados e listagem validada
- [ ] Estilização da tabela aplicada no `style.css`

## O que vem a seguir

Com a listagem funcionando, a Aula 6 vai preencher o arquivo `produtos/cadastrar.php`, que hoje está vazio, com o formulário que efetivamente insere novos produtos nessa listagem.
