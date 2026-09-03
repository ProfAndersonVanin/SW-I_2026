# Aula 2 — Conexão com o Banco e Primeira Página com PHP Embutido
### Guia de desenvolvimento em laboratório — Sistema CRUD de Produtos com Login (PHP, MySQL, HTML e CSS)

## Antes de começar

- [ ] Ambiente XAMPP instalado e funcionando (Aula 1)
- [ ] Estrutura de pastas do projeto criada dentro de `projeto_php` (Aula 1)
- [ ] Banco `sistema_produtos` com as tabelas `usuarios` e `produtos` já criadas (Aula 1)

## Objetivo da aula

Fazer o PHP "conversar" com o MySQL pela primeira vez e montar o esqueleto visual do sistema, com uma estrutura de página reaproveitável e o primeiro exemplo de PHP embutido em HTML.

## Visão geral: o padrão `require __DIR__`

Ao longo de todo o curso, sempre que uma página precisar de outro arquivo PHP (conexão, cabeçalho, rodapé, verificação de login), vamos usar `require __DIR__ . '/caminho.php'` em vez de simplesmente `include 'caminho.php'`. A vantagem: `__DIR__` sempre aponta para a pasta onde o **arquivo atual** está salvo, então o caminho funciona certo não importa de onde o PHP foi chamado — diferente de um caminho "solto", que pode variar dependendo de como o servidor foi configurado.

---

## Passo a passo detalhado

### Passo 1 — Criando o arquivo de conexão (`conexao.php`)

Este arquivo será incluído em todas as páginas do sistema que precisarem falar com o banco de dados.

```php
<?php
    // conexao.php
    $host = "localhost";
    $usuario = "root";
    $senha = "";
    $banco = "sistema_produtos";
    $conexao = mysqli_connect($host, $usuario, $senha, $banco);
?>
```

No XAMPP, o usuário padrão do MySQL é `root` e a senha costuma ser vazia.

### Passo 2 — Testando a conexão

Adicione temporariamente uma verificação, só para confirmar que está tudo certo:

```php
<?php
    // conexao.php
    $host = "localhost";
    $usuario = "root";
    $senha = "";
    $banco = "sistema_produtos";
    $conexao = mysqli_connect($host, $usuario, $senha, $banco);

    if ($conexao) {
        echo "Conexão realizada com sucesso!";
    } else {
        echo "Erro ao conectar: " . mysqli_connect_error();
    }
?>
```

> ✅ **Teste agora:** salve o arquivo e acesse `http://localhost/projeto_php/conexao.php` no navegador. Você deve ver a mensagem "Conexão realizada com sucesso!" na tela.

Depois de confirmar que funciona, **comente** as linhas de teste em vez de apagá-las — é uma forma prática de deixar o teste guardado, caso precise depurar a conexão de novo no futuro:

```php
<?php
    // conexao.php
    $host = "localhost";
    $usuario = "root";
    $senha = "";
    $banco = "sistema_produtos";
    $conexao = mysqli_connect($host, $usuario, $senha, $banco);

    //var_dump($conexao);

    // if ($conexao) {
    //     echo "Conexão realizada com sucesso!";
    // } else {
    //     echo "Erro ao conectar: " . mysqli_connect_error();
    // }
?>
```

> ⚠️ **Atenção:** com essas linhas comentadas, a página `conexao.php` não mostra mais nada quando acessada diretamente — isso é esperado, porque esse arquivo não é feito para ser visitado sozinho, e sim incluído dentro de outras páginas.

### Passo 3 — Criando o `cabecalho.php` (com `$base_url`)

Em vez de repetir o mesmo HTML em toda página, vamos criar um cabeçalho e um rodapé únicos, incluídos com `require`. O cabeçalho já define uma variável `$base_url`, que vamos usar em todos os links do menu — assim, os links funcionam certo não importa se a página atual está na raiz do projeto ou dentro da pasta `produtos/`.

```php
<?php
$base_url = '/projeto_php/';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo $base_url; ?>css/style.css">
    <title>Projeto PHP - CRUD</title>
</head>
<body>
    <header>
        <h1>Sistema de Produtos</h1>
            <nav>
                <a href="<?php echo $base_url; ?>index.php">Início</a>
                <a href="<?php echo $base_url; ?>produtos/listar.php">Produtos</a>
                <a href="<?php echo $base_url; ?>login.php">Login</a>
                <a href="<?php echo $base_url; ?>logout.php">Sair</a>
            </nav>
    </header>
```

> ⚠️ **Atenção:** repare que `href="<?php echo $base_url; ?>css/style.css"` monta o caminho `/projeto_php/css/style.css` — um caminho absoluto (começa com `/`). Isso é proposital: se usássemos um caminho relativo como `href="css/style.css"`, ele funcionaria em `index.php` (que está na raiz), mas quebraria quando esse mesmo cabeçalho fosse incluído por uma página dentro de `produtos/` (a partir da Aula 4/5) — o navegador resolve links relativos com base na URL da página aberta, não em onde o `require` foi feito.

### Passo 4 — Criando o `rodape.php`

```php
    <footer>
        <p>Sistema de Produtos - Todos os direitos reservados</p>
    </footer>
</body>
</html>
```

### Passo 5 — Primeira página com PHP embutido (`index.php`)

```php
<?php require __DIR__ . '/cabecalho.php'; ?>
    <main>
        <p>Bem-vindo(a) ao sistema!</p>
        <p>Hoje é <?php echo date('d/m/Y'); ?></p>
    </main>
<?php require __DIR__ . '/rodape.php'; ?>
```

O PHP pode aparecer em qualquer parte do HTML — aqui ele é usado apenas para exibir a data atual, um exemplo simples de PHP embutido.

> ✅ **Teste agora:** acesse `http://localhost/projeto_php/index.php`. Você deve ver o cabeçalho azul com o menu, a mensagem de boas-vindas com a data de hoje, e o rodapé.

### Passo 6 — Introdução ao CSS básico

Criar `css/style.css`:

```css
body {
    font-family: Arial, sans-serif;
    margin: 0;
    background-color: #f4f4f4;
}
header {
    background-color: #1f4e79;
    color: white;
    padding: 15px 30px;
}
header nav a {
    color: white;
    margin-right: 15px;
    text-decoration: none;
}
main {
    padding: 20px 30px;
}
footer {
    text-align: center;
    padding: 10px;
    color: #666;
}
```

> ✅ **Teste agora:** atualize `http://localhost/projeto_php/index.php`. O cabeçalho deve aparecer com fundo azul e texto branco, e o rodapé centralizado.

---

## Erros comuns nesta aula

| O que você vê | Causa provável | Como resolver |
|---|---|---|
| "Erro ao conectar" no Passo 2 | Usuário/senha do MySQL errados, ou MySQL não está rodando | Confira se o MySQL está "verde" no XAMPP; confirme usuário `root` e senha vazia |
| Página em branco ao acessar `index.php` | Erro de sintaxe em `cabecalho.php` ou `rodape.php` | Ative a exibição de erros do PHP (ou confira o log do Apache) e revise o arquivo apontado |
| CSS não aparece (página sem estilo) | Caminho do `<link>` errado, ou arquivo salvo fora de `css/` | Confira se `style.css` está exatamente em `projeto_php/css/style.css` |
| Menu do cabeçalho aparece mas os links levam para página não encontrada (404) | `$base_url` com valor diferente do nome real da pasta do projeto | Confira se `$base_url` é exatamente `/projeto_php/` (com a barra no final) |

## Arquivo final — `cabecalho.php`

```php
<?php
$base_url = '/projeto_php/';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo $base_url; ?>css/style.css">
    <title>Projeto PHP - CRUD</title>
</head>
<body>
    <header>
        <h1>Sistema de Produtos</h1>
            <nav>
                <a href="<?php echo $base_url; ?>index.php">Início</a>
                <a href="<?php echo $base_url; ?>produtos/listar.php">Produtos</a>
                <a href="<?php echo $base_url; ?>login.php">Login</a>
                <a href="<?php echo $base_url; ?>logout.php">Sair</a>
            </nav>
    </header>
```

## Checklist final antes de seguir para a Aula 3

- [ ] `conexao.php` criado e testado (mensagem de sucesso confirmada e depois comentada)
- [ ] `cabecalho.php` criado com `$base_url` e o menu de navegação
- [ ] `rodape.php` criado
- [ ] `index.php` exibindo a data atual, com cabeçalho e rodapé aplicados
- [ ] `css/style.css` criado e aplicado corretamente

## O que vem a seguir

Com a página inicial funcionando, conectada ao banco e com uma base visual pronta, a Aula 3 vai usar esse mesmo modelo para criar a tela de login.
