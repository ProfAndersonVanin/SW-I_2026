# Aula 9 — Validações, Mensagens e Boas Práticas Básicas
### Guia de desenvolvimento em laboratório — Sistema CRUD de Produtos com Login (PHP, MySQL, HTML e CSS)

## Antes de começar

Esta aula é diferente das anteriores: você não vai criar nenhuma tela nova, só melhorar arquivos que já existem e já funcionam. Antes de mexer em qualquer coisa, faça uma cópia de segurança da pasta `produtos/` (fora do `htdocs`) — se algo der errado, você volta para essa cópia.

- [ ] CRUD completo funcionando: listar, cadastrar, atualizar e excluir produtos (Aulas 5 a 8)
- [ ] Fiz uma cópia de segurança da pasta `produtos/` antes de começar

> ⚠️ **Atenção:** faça uma alteração de cada vez e teste logo em seguida. Se você mudar vários arquivos ao mesmo tempo e algo quebrar, fica muito mais difícil descobrir onde foi o erro.

## Objetivo da aula

Deixar o sistema mais robusto e amigável sem adicionar telas novas: padronizar as mensagens de sucesso e erro (fazendo-as sobreviver a um redirecionamento), reforçar as validações de formulário e reduzir problemas causados por caracteres especiais nos dados.

## Visão geral: o problema que vamos resolver primeiro

Repare em um comportamento estranho no sistema atual: quando você cadastra um produto com sucesso, `cadastrar.php` redireciona para `listar.php` com `header('Location: listar.php')`. Esse redirecionamento cria uma página inteiramente nova no navegador — e qualquer variável PHP comum (como `$mensagem`) criada em `cadastrar.php` desaparece nesse processo. Por isso, hoje, nenhuma mensagem de sucesso chega a aparecer na listagem.

A solução é guardar a mensagem em `$_SESSION` em vez de em uma variável comum — dados salvos na sessão sobrevivem entre páginas diferentes.

| Tipo | Quando aparece | Exemplo |
|---|---|---|
| Sucesso | Depois de cadastrar, atualizar ou excluir com êxito | "Produto cadastrado com sucesso!" |
| Erro | Quando falta preencher um campo obrigatório | "Preencha todos os campos obrigatórios." |

---

## Passo a passo detalhado

### Passo 1 — Guardar a mensagem de sucesso no cadastro

Em `produtos/cadastrar.php`, localize:

```php
if (mysqli_query($conexao, $sql)) {
    header('Location: listar.php');
    exit;
} else {
```

E adicione a linha da mensagem antes do `header`:

```php
if (mysqli_query($conexao, $sql)) {
    $_SESSION['mensagem'] = "Produto cadastrado com sucesso!";
    header('Location: listar.php');
    exit;
} else {
```

> ⚠️ **Atenção:** para usar `$_SESSION`, o PHP precisa ter chamado `session_start()` antes em algum ponto da execução dessa página. Isso já acontece automaticamente, porque `produtos/verifica_login.php` (incluído no topo de `cadastrar.php`) já chama `session_start()`. Você não precisa chamar de novo.

### Passo 2 — Repetir em `atualizar.php` e `excluir.php`

Em `produtos/atualizar.php`, logo depois do `mysqli_query` do UPDATE:

```php
mysqli_query($conexao, $sql);

$_SESSION['mensagem'] = "Produto atualizado com sucesso!";
header('Location: listar.php');
exit;
```

Em `produtos/excluir.php`, logo depois do `mysqli_query` do DELETE:

```php
mysqli_query($conexao, $sql);

$_SESSION['mensagem'] = "Produto excluído com sucesso!";
header('Location: listar.php');
exit;
```

### Passo 3 — Exibir e limpar a mensagem em `listar.php`

```php
<main>
    <h2>Produtos cadastrados</h2>

    <?php if (isset($_SESSION['mensagem'])) { ?>
        <p><?php echo $_SESSION['mensagem']; ?></p>
        <?php unset($_SESSION['mensagem']); ?>
    <?php } ?>

    <a href="cadastrar.php">Cadastrar novo produto</a>
    <!-- tabela de produtos continua igual -->
```

A linha `unset($_SESSION['mensagem']);` apaga a mensagem da sessão logo depois de mostrá-la, para que ela não continue aparecendo se você atualizar a página (F5) depois.

> ✅ **Teste agora:** cadastre um novo produto de teste. Depois do redirecionamento, você deve ver a mensagem "Produto cadastrado com sucesso!" no topo da listagem. Atualize a página (F5) — a mensagem deve desaparecer. Repita editando e excluindo um produto.

### Passo 4 — Reforçar a validação com `trim()`

Hoje, se alguém digitar só espaços em branco no campo Nome, a validação atual (`if ($nome == "")`) não percebe. A função `trim()` remove espaços do início e do fim do texto antes de validar.

Em `produtos/cadastrar.php`, troque:

```php
$nome = $_POST['nome'];
$descricao = $_POST['descricao'];
$preco = $_POST['preco'];
$quantidade = $_POST['quantidade'];
```

Por:

```php
$nome = trim($_POST['nome']);
$descricao = trim($_POST['descricao']);
$preco = trim($_POST['preco']);
$quantidade = trim($_POST['quantidade']);
```

Faça o mesmo ajuste no bloco POST de `produtos/atualizar.php`.

> ✅ **Teste agora:** no cadastro, digite só espaços no campo Nome (sem nenhuma letra) e tente salvar. Deve aparecer a mensagem de campos obrigatórios, em vez de cadastrar com nome em branco.

### Passo 5 — Proteger contra aspas nos dados (`mysqli_real_escape_string`)

Se alguém cadastrar um produto com aspas simples no nome (ex: `Caneta 'Premium'`), o comando SQL monta errado. A função `mysqli_real_escape_string` trata isso, sem precisar de POO ou bibliotecas externas.

Em `produtos/cadastrar.php`:

```php
$nome = mysqli_real_escape_string($conexao, trim($_POST['nome']));
$descricao = mysqli_real_escape_string($conexao, trim($_POST['descricao']));
```

`preco` e `quantidade` não precisam dessa proteção — são números, não têm aspas dentro. Só `nome` e `descricao`, que são texto livre.

Faça o mesmo ajuste no bloco POST de `produtos/atualizar.php`.

> ✅ **Teste agora:** cadastre um produto com nome `Caneta 'Premium'`. Antes deste passo isso provavelmente dava erro; agora deve cadastrar normalmente.

### Passo 6 — Revisão visual de todas as telas

Abra cada página abaixo e confira se o visual está consistente — mesmo cabeçalho, mesmo rodapé, mesmas cores de botão:

1. `http://localhost/projeto_php/login.php`
2. `http://localhost/projeto_php/produtos/listar.php`
3. `http://localhost/projeto_php/produtos/cadastrar.php`
4. `http://localhost/projeto_php/produtos/atualizar.php` (clicando em Editar)
5. `http://localhost/projeto_php/produtos/excluir.php` (clicando em Excluir)

---

## Erros comuns nesta aula

| O que você vê | Causa provável | Como resolver |
|---|---|---|
| Mensagem de sucesso nunca aparece | Faltou `$_SESSION['mensagem'] = ...;` antes do `header`, ou faltou o `if (isset(...))` em `listar.php` | Confira as duas pontas: quem guarda e quem exibe |
| Mensagem continua após F5 | Faltou `unset($_SESSION['mensagem']);` | Adicione logo depois do `echo` da mensagem |
| Erro de sintaxe SQL com aspas no nome | Esqueceu `mysqli_real_escape_string` | Confira `$nome` e `$descricao` antes do SQL |
| Nome com só espaços ainda é aceito | Faltou trocar `$_POST['nome']` por `trim($_POST['nome'])` na validação | Confira se a validação usa a variável já tratada |

## Checklist final antes de seguir para a Aula 10

- [ ] Mensagens de sucesso aparecem após cadastrar, atualizar e excluir
- [ ] A mensagem some depois de atualizar a página (F5) uma vez
- [ ] `trim()` aplicado na validação de `cadastrar.php` e `atualizar.php`
- [ ] `mysqli_real_escape_string` aplicado em `nome` e `descricao`
- [ ] Testei cadastrar um produto com aspas simples no nome, sem erro
- [ ] Revisei visualmente as 5 páginas do Passo 6

## O que vem a seguir

Com o sistema mais robusto e consistente, a Aula 10 fecha o curso com um teste guiado de ponta a ponta, um checklist geral de revisão e uma discussão sobre os próximos passos possíveis.
