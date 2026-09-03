# Aula 10 — Revisão Geral, Testes e Entrega do Sistema
### Guia de desenvolvimento em laboratório — Sistema CRUD de Produtos com Login (PHP, MySQL, HTML e CSS)

## Antes de começar

Esta é a última aula, e ela não introduz código novo — o objetivo é testar tudo o que você construiu nas Aulas 1 a 9, de ponta a ponta, como se fosse a primeira vez usando o sistema.

- [ ] Todas as páginas do CRUD funcionando individualmente (Aulas 5 a 8)
- [ ] Mensagens de sessão e validações reforçadas (Aula 9)

## Objetivo da aula

Consolidar todo o aprendizado do curso testando o sistema completo, do login ao CRUD, revisando o código com um checklist e discutindo os próximos passos possíveis para quem quiser continuar evoluindo o projeto.

---

## Passo a passo detalhado

### Passo 1 — Preparar um ambiente de teste limpo

Se possível, esvazie a tabela `produtos` no phpMyAdmin (ou anote quantos produtos existem agora, para comparar depois):

```sql
-- Rode isto no phpMyAdmin, aba SQL, se quiser começar do zero:
TRUNCATE TABLE produtos;
```

> ⚠️ **Atenção:** `TRUNCATE TABLE` apaga todos os produtos e reinicia a contagem de id. Só use se realmente não precisar guardar os dados atuais.

### Passo 2 — Teste guiado do fluxo completo

Siga esta sequência exata, sem pular etapas:

> ✅ Abra uma aba anônima/privada do navegador e acesse `http://localhost/projeto_php/produtos/listar.php`. Você deve ser redirecionado para o login.

> ✅ Faça login com `admin@sistema.com` / `123456`. Você deve cair direto em `produtos/listar.php`.

> ✅ Clique em "Cadastrar novo produto", deixe o campo Nome vazio e tente salvar. Deve aparecer a mensagem de erro.

> ✅ Cadastre um produto válido (ex: nome "Teste Final", preço 10.00, quantidade 5). Deve voltar para a listagem com mensagem de sucesso.

> ✅ Clique em Editar nesse produto, altere a quantidade para 20 e salve. Deve mostrar a mensagem de sucesso e a quantidade atualizada.

> ✅ Clique em Excluir, confira o nome na confirmação, clique em Cancelar e confirme que o produto continua na lista.

> ✅ Clique em Excluir novamente, confirme com "Sim, excluir". O produto deve sumir da listagem.

> ✅ Clique em "Sair" no menu. Tente acessar `produtos/listar.php` de novo pela URL — deve voltar ao login.

### Passo 3 — Checklist de revisão do sistema

| Arquivo | O que verificar | OK? |
|---|---|---|
| `conexao.php` | Conecta corretamente ao banco `sistema_produtos` | [ ] |
| `cabecalho.php` | `$base_url` e os links do menu funcionam em qualquer pasta | [ ] |
| `login.php` | Valida e-mail/senha e grava a sessão corretamente | [ ] |
| `logout.php` | Encerra a sessão corretamente | [ ] |
| `produtos/verifica_login.php` | Bloqueia acesso de quem não está logado | [ ] |
| `produtos/listar.php` | Exibe produtos e mensagens da sessão | [ ] |
| `produtos/cadastrar.php` | Valida campos (com `trim`) e insere corretamente | [ ] |
| `produtos/atualizar.php` | Preenche o formulário e salva alterações | [ ] |
| `produtos/excluir.php` | Confirma antes de excluir e remove corretamente | [ ] |

### Passo 4 — Revisão do código

Abra cada arquivo do projeto e confira estes pontos de organização:

- Todos os includes usam o padrão `require __DIR__ . '/...'` de forma consistente (nenhum `include` "sozinho", sem `__DIR__`)
- Nomes de variáveis claros e consistentes (ex: sempre `$conexao`, nunca misturado com `$conn` ou `$con`)
- Nenhum código de teste esquecido (ex: `var_dump` ou `echo` de depuração usados durante as Aulas 7 e 8)
- Comentários simples nos trechos mais importantes, como o INSERT, o UPDATE e o DELETE

### Passo 5 — Ajustes finais de CSS

Revisar o `css/style.css` como um todo. Se quiser, adicione um ajuste simples de responsividade ao final do arquivo:

```css
/* Aula 10 - ajuste de responsividade básica */
@media (max-width: 600px) {
    header nav a {
        display: block;
        margin: 5px 0;
    }

    table, thead, tbody, th, td, tr {
        font-size: 14px;
    }
}
```

> ✅ **Teste agora:** reduza a largura da janela do navegador (ou use o modo mobile do DevTools) e confirme que o menu e a tabela continuam legíveis.

### Passo 6 — Discussão: próximos passos

Reflita sobre o que mudaria se o sistema continuasse evoluindo — sem necessariamente implementar agora:

- Guardar a senha com hash (`password_hash` / `password_verify`) em vez de texto puro
- Reorganizar o código usando Programação Orientada a Objetos (POO)
- Usar PDO com prepared statements em vez de `mysqli`
- Adotar um framework (ex: Laravel) para projetos maiores
- Adicionar paginação na listagem de produtos, para bancos com muitos registros

---

## Se algo não funcionar: por onde procurar

Em vez de uma tabela de erros específicos, aqui vai um roteiro geral de investigação para qualquer problema:

1. Releia a mensagem de erro do PHP com calma — ela quase sempre aponta o nome do arquivo e a linha exata do problema
2. Confira se o arquivo com problema começa com os `require` certos (`verifica_login.php` e `conexao.php`, nos caminhos corretos com `__DIR__`)
3. Confira se os `name` dos campos do formulário batem exatamente com os nomes usados em `$_POST['...']` ou `$_GET['...']`
4. Compare o arquivo com o código completo apresentado na aula correspondente (Aulas 5 a 9)
5. Se nada disso resolver, anote a mensagem de erro exata e o arquivo/linha, para levar essa dúvida pontual para a próxima aula

## Checklist final de entrega do sistema

- [ ] Testei o fluxo completo do Passo 2, do início ao fim, sem pular etapas
- [ ] Preenchi o checklist de revisão do sistema (Passo 3)
- [ ] Revisei o código de todos os arquivos (Passo 4)
- [ ] Apliquei os ajustes finais de CSS (Passo 5)
- [ ] Refleti sobre os próximos passos possíveis do sistema (Passo 6)
- [ ] Anotei qualquer dúvida ou comportamento estranho que não consegui resolver sozinho(a)

## Fechamento do curso

Com isso, você tem um sistema CRUD completo e funcional: login com sessão, proteção de páginas internas (`produtos/verifica_login.php`) e as quatro operações de um CRUD (Create, Read, Update, Delete), construído do zero com PHP, MySQL, HTML e CSS, sem frameworks, usando o padrão `require __DIR__` para includes robustos independentes de onde o script é chamado. Esse projeto serve como base sólida para explorar, no futuro, conceitos mais avançados como POO, segurança de senhas e frameworks.
