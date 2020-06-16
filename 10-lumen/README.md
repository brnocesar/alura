# Lumen

(...) o que é o lumen

**Índice**<a name='topo'></a>

1. <a href='#1'>Configurando o ambiente</a>  
1.1. Criando o projeto  
1.2. Diferenças para o Laravel

## 1. Configurando o ambiente

### 1.1. Criando o projeto

Acesse a [documentação oficial](https://lumen.laravel.com/docs) do Lumen e verifique se sua máquina satisfaz os requisitos (PHP por necessidade e Composer por comodidade) e logo após basta seguir para o comando que cria um projeto:

```terminal
composer create-project --prefer-dist laravel/lumen nome_do_projeto
```

Para facilitar os testes e o desenvolvimento da aplicação podemos ainda utilizar um cliente HTTP para fazer as requisições. Duas boas opções são o [Postman](https://www.postman.com/) e [Insomnia](https://insomnia.rest/).

### 1.2. Principais diferenças para o Laravel

Logo de cara podemos notar algumas diferenças em relação ao Laravel, dentre elas temos:

- pasta `/route`: possui apenas um arquivo chamado `web.php`
- grupos de rotas: são definidos dentro do arquivo de rotas (mencionado no item acima) e não mais no _provider_ de rotas `app/Providers/RouteServiceProvider.php`
- servidor embutido: não existe no Lumen, deve ser usado o do própio PHP
  - `php -S localhost:8000 -t public`
- a configuração do Banco é feita apenas no `.env`
- ainda temos a interface por linha de comandos **_artisan_**, mas agora com menos funcionalidades. Não é mais possível, por exemplo, criar _controllers_ e _models_ pela linha de comando.
- mas uma das principais diferenças é que o Lumen não vem com o Eloquent habilitado por padrão. Para fazê-lo, basta descomentar uma linha no arquivo `bootstrap/app.php` (_commit_ [d97821a](https://github.com/brnocesar/alura/commit/d97821adaa15a10f25ed4d04691b256113aa713b)):

```php
...
// $app->withEloquent();
...
```
