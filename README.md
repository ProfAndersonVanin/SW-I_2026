# 🌐 Sistemas Web I - Prática Profissional

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![Status](https://img.shields.io/badge/Status-Em_Andamento-green?style=for-the-badge)

> **Carga Horária:** 80 horas | **Duração:** 42 Semanas  
> **Foco:** Desenvolvimento Backend, Arquitetura MVC e APIs REST.

## 📖 Sobre a Disciplina

Este repositório contém o Plano de Ensino Anual e os materiais de apoio da disciplina de **Sistemas Web I**. O curso foi desenhado para levar o aluno desde os fundamentos do protocolo HTTP e lógica *server-side* pura ("na unha") até o desenvolvimento profissional utilizando frameworks modernos, testes automatizados e arquitetura de APIs.

### 🎯 Objetivos
- Compreender o ciclo de vida Request/Response.
- Dominar a linguagem PHP e interação com Banco de Dados.
- Evoluir de código procedural para Arquitetura MVC.
- Desenvolver APIs RESTful e aplicar Testes de Software.
- **Project Based Learning:** Criação de um produto real no 4º bimestre.

---

## 🛠️ Stack Tecnológica

As seguintes ferramentas e tecnologias serão utilizadas ao longo do ano letivo:

* **Linguagem:** PHP (8.x)
* **Banco de Dados:** MySQL / MariaDB
* **Framework:** Laravel
* **Ambiente:** XAMPP, Laragon ou Docker
* **Versionamento:** Git e GitHub
* **Testes:** PHPUnit / Pest
* **API Client:** Postman ou Insomnia

---

## 📅 Cronograma Anual

### 1º Bimestre: Fundamentos da Web e Lógica Server-Side
*O objetivo é desmistificar como o servidor funciona antes de introduzir a "mágica" dos frameworks.*

| Semana | Tópico | Detalhes |
| :---: | :--- | :--- |
| **01** | **Ambiente** | Apresentação, VS Code, XAMPP/Laragon, Git. |
| **02** | **Protocolo HTTP** | Request/Response, Cliente vs. Servidor. |
| **03** | **Lógica PHP** | Variáveis, Tipos, If/Else, Loops no HTML. |
| **04** | **Dados** | Manipulação de Arrays e Strings. |
| **05** | **Interação** | Formulários HTML, GET vs POST. |
| **06** | **SQL** | Revisão de Banco de Dados Relacional e SQL básico. |
| **07** | **Conexão (PDO)** | Segurança, PDO e SQL Injection. |
| **08** | **CRUD (Read)** | Listagem de dados "Na Unha". |
| **09** | **CRUD (Create)** | Inserção de dados e Validação básica. |
| **10** | **🏆 Avaliação 1** | **Projeto Prático:** Sistema de cadastro simples sem framework. |

### 2º Bimestre: Arquitetura MVC e Framework
*Introdução ao desenvolvimento profissional e padrões de projeto.*

| Semana | Tópico | Detalhes |
| :---: | :--- | :--- |
| **11** | **Conceito MVC** | Model, View, Controller vs "Código Espaguete". |
| **12** | **Setup** | Instalação do Framework (Laravel) e Gerenciador de Dependências (Composer). |
| **13** | **Roteamento** | URLs amigáveis e verbos HTTP. |
| **14** | **Controllers** | A lógica de negócio separada da visualização. |
| **15** | **Views (Blade)** | Template Engine, Layouts, herança e exibição de dados. |
| **16** | **Sessão** | Cookies, Mantendo o estado do usuário (Login/Logout básico). |
| **17** | **Validação** | Garantindo a integridade no lado do servidor. |
| **18** | **Middleware** | Protegendo rotas (Segurança básica). |
| **19** | **Front-end** | Integração Bootstrap/Tailwind com o Framework. |
| **20** | **🏆 Avaliação 2** | **Refatoração:** Projeto do 1º bi utilizando estrutura MVC. |

### 3º Bimestre: Persistência Avançada, APIs e Testes
*Foco nas competências 1.2 e conceitos de APIs REST.*

| Semana | Tópico | Detalhes |
| :---: | :--- | :--- |
| **21** | **Migrations** | Banco de Dados Moderno (Code-first) e versionamento. |
| **22** | **ORM** | Object-Relational Mapping: Mapeando tabelas para Classes. |
| **23** | **Relacionamentos** | ORM: 1:N (Um para Muitos) e N:N (Muitos para Muitos). |
| **24** | **Intro API** | O que é JSON e códigos de status HTTP (200, 404, 500). |
| **25** | **Construindo API** | Criando endpoints para consumo externo. |
| **26** | **Consumo de APIs** | Clientes HTTP (Guzzle/Http Client) para buscar CEP ou Clima. |
| **27** | **Testes** | Por que testar? (Pirâmide de testes). |
| **28** | **Unitários** | Testando pequenas funções isoladas. |
| **29** | **Integração** | Testes Automatizados: fluxos de rotas e banco. |
| **30** | **🏆 Avaliação 3** | **Prática:** Desenvolvimento de API REST documentada com testes. |

### 4º Bimestre: Fábrica de Projetos (PBL)
*Aplicação de todas as competências para estimular a criatividade e resolução de problemas.*

* **Semana 31:** 🧠 **Brainstorming:** Formação de grupos e tema (E-commerce, Blog, etc).
* **Semana 32:** 📝 **Planejamento:** Diagrama de Banco de Dados e Wireframes.
* **Semana 33:** ⚙️ **Setup:** Repositório Git, Configuração inicial e Migrations.
* **Semana 34:** 🚀 **Sprint 1:** Desenvolvimento do Core (Autenticação e CRUD principal).
* **Semana 35:** 🔄 **Sprint 2:** Implementação das Regras de Negócio e Relacionamentos.
* **Semana 36:** 🔌 **Sprint 3:** Implementação de API ou Integração externa.
* **Semana 37:** 🎨 **Sprint 4:** Refinamento de Interface (UI/UX) e Validações.
* **Semana 38:** ✅ **Qualidade:** Escrita de Testes Automatizados para rotas críticas.
* **Semana 39:** ☁️ **Deploy:** Publicação em servidor real (Heroku, Railway, etc).
* **Semana 40:** 📄 **Documentação:** Finalização e preparação da apresentação.
* **Semana 41:** 🎉 **Feira de Projetos:** Apresentação dos sistemas.
* **Semana 42:** 🏁 Encerramento, Feedback e Recuperação.

---

## 📦 Instruções

1.  Clone este repositório.
2.  Instale as dependências: `composer install`.
3.  Configure o arquivo `.env`.
4.  Execute as migrations: `php artisan migrate`.

---

## 👨‍🏫 Docente

**[Anderson Silva Vanin]**

[![LinkedIn](https://img.shields.io/badge/LinkedIn-0077B5?style=for-the-badge&logo=linkedin&logoColor=white)](https://linkedin.com/in/anderson-vanin)
