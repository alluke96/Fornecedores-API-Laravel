# CRUD de Fornecedores com Laravel
================================

Este projeto é uma API para gerenciar fornecedores, telefones e endereços, utilizando o framework Laravel. Ele inclui funcionalidades de CRUD (Criar, Ler, Atualizar e Deletar) e organização em tabelas relacionadas.

* * * * *
Configuração Inicial
--------------------

1.  Clone o repositório do projeto para a sua máquina local.

    -   Acesse a pasta do projeto.

2.  Instale as dependências do Composer.

    -   `composer install`

3.  Copie o arquivo de configuração do ambiente.

    -   `cp .env.example .env`

4.  Configure as variáveis de ambiente no arquivo `.env`.

    -   Atualize as informações do banco de dados (DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD).

5.  Gere a chave da aplicação.

    -   `php artisan key:generate`

Banco de Dados
--------------

1.  Execute as migrações para criar as tabelas necessárias.

    -   `php artisan migrate`

2.  Popule o banco de dados com dados iniciais (seeds).

    -   `php artisan db:seed`

Como Rodar o Projeto
--------------------

1.  Inicie o servidor embutido do Laravel.

    -   `php artisan serve`

2.  Acesse a aplicação no navegador ou em um cliente REST como o Insomnia ou Postman.

    -   URL base: `http://127.0.0.1:8000`

Documentação da API
-------------------

A documentação da API foi criada com Swagger, usando a biblioteca `l5-swagger`.

1.  Para acessar a documentação Swagger, certifique-se de que o servidor esteja rodando e acesse:

    -   `http://127.0.0.1:8000/api/documentation`

* * * * *

Estrutura do Projeto
--------------------

-   **Tabelas**:

    -   `fornecedores`: Contém `documento`, `nome` e `ativo`.

    -   `enderecos`: Contém `rua`, `numero`, `complemento`, `bairro`, `cidade`, `uf` e `cep`.

    -   `telefones`: Contém os números de telefone relacionados.

-   **Relações**:

    -   Muitos-para-muitos entre fornecedores e telefones.

    -   Muitos-para-muitos entre fornecedores e endereços.

* * * * *

Requisitos
----------

-   PHP >= 8.1

-   Composer

-   Banco de dados MySQL ou PostgreSQL

-   Laravel >= 9.x

* * * * *

Possíveis Erros
---------------

-   **Erro de tabela inexistente**: Verifique se as migrações foram executadas corretamente.

-   **Problemas no servidor**: Certifique-se de que o `php artisan serve` está rodando e que não há conflito de portas com outros servidores locais (como o Apache do XAMPP).

* * * * *

Contato
-------

Para dúvidas ou problemas, entre em contato pelo e-mail: allyson.dunke@gmail.com.