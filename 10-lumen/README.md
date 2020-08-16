# Lumen

O Lumen é um _micro-framework_ PHP. É como se fosse um Laravel "enxuto", com o básico para fe

## Criando o projeto

Acesse a [documentação oficial](https://lumen.laravel.com/docs) do Lumen e verifique se sua máquina satisfaz os requisitos (PHP por necessidade e Composer por comodidade) e logo após basta seguir para o comando que cria um projeto:

```terminal
composer create-project --prefer-dist laravel/lumen nome_do_projeto
```

Para facilitar os testes e o desenvolvimento da aplicação podemos ainda utilizar um cliente HTTP para fazer as requisições. Duas boas opções são o [Postman](https://www.postman.com/) e [Insomnia](https://insomnia.rest/).

## Principais diferenças para o Laravel

Logo de cara podemos notar algumas diferenças em relação ao Laravel, dentre elas temos:

- rotas: todas as definições são feitas no único arquivo `/routes/web.php`. Não tem mais o _provider_ de rotas por exemplo
- a configuração do Banco é feita apenas no `.env`, na verdade (acho que) todas as configurações são feitas apenas nesse arquivo
- ainda temos a interface por linha de comandos **_artisan_**, mas agora com menos funcionalidades, por exemplo:
  - não é mais possível criar _controllers_ e _models_ pela linha de comando
  - o servidor embutido já era, temos que usar o do PHP:
    - `php -S localhost:8000 -t public`
- alguns recursos vem até vem configurados por padrão, mas precisam ser habilitados. É o caso do Eloquent, que é necessário descomentar uma linha no arquivo `bootstrap/app.php` (_commit_ [d97821a](https://github.com/brnocesar/alura/commit/d97821adaa15a10f25ed4d04691b256113aa713b)):

    ```php
    ...
    // $app->withEloquent();
    ...
    ```
