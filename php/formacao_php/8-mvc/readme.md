# Arquitetura MVC com PHP

#### Índice
1. <a href='#1'>Configurando o ambiente</a>
2. <a href='#2'>Ponto único de entrada</a>
3. <a href='#3'>_Controllers_</a>
4. <a href='#4'>Isolando HTML</a>
5. <a href='#5'>HTTP, Formulários e Validação</a>
6. <a href='#'></a>
7. <a href='#'></a>
8. <a href='#'></a>
9. <a href='#'></a>

## 1. Configurando o ambiente<a name='1'></a>
O primeiro passo após baixar este projeto no ponto inicial (_commit_ [9eee948](https://github.com/brnocesar/alura/commit/9eee94837035508897476438d073c382d20cafb3)) é mandar o _composer_ instalar as dependências, isso é feito com o comando:
```sh
$ composer install
```

Após isso vá até o arquivo `php.ini` e, se ainda não estiver, descomente a linha que tenha algo com "pdo_sqlite" ou apenas "sqlite". Para encontrar o diretório deste arquivo no Linux rode o eguinte comando:
```sh
$ php -i | grep "php.ini"
```

Após isso já podemos levantar o servidor e ver o que temos, para isso rode o comando abaixo no terminal e depois acesso o endereço `localhost:8000` no navegador:
```sh
$ php -S localhost:8000 -t public
```

Note que o diretório `/public` foi passado como o _target_ pois ele será o ponto de entrada de nossa aplicação, ou seja, é o único diretório que será acessível pela Web.

## 2. Ponto único de entrada<a name='2'></a>
Quando acessamos um endereço pela URL e não informamos a rota que queremos acessar através de um domínio, o `localhost:8000` por exemplo, o PHP vai buscar pelo arquivo `index.php` no ponto de entrada da aplicação. O mesmo ocorre quando digitamos alguma coisa na rota sem expecificarmos uma extensão, digamos `localhost:8000/batatinha`, nesse caso o PHP vai procurar o arquivo `index.php` no diretório `/batatinha` a partir do ponto de entrada da aplicação.

Podemos usar a variável global `$_SERVER` para acessar dados do servidor, dentre eles a rota que esta sendo acessada, e dessa forma, implementar uma tratativa no arquivo `index.php` para avaliar o que retornar de acordo com a rota. Isso garante que nossa aplicação terá um único ponto de entrada, garantindo maior controle.

## 3. _Controllers_<a name='3'></a>
Como o PHP oferece o recurso de Orientação a Objetos (OO) podemos criar classes que **controlem** cada uma das rotas da aplicação, essas classes são os _controllers_. Toda implementação até este ponto pode ser encontrada no _commit_ [84708bc](https://github.com/brnocesar/alura/commit/84708bc6a100c54e224f0b65def056fd0af44702).

Agora que temos um _controller_ para a rota de "listar cursos", podemos adicionar "todo" HTML necessário lá e trocar o `require listar-cursos.php` pelo "instânciamneto" (?) dessa classe e chamar o método que processa a requisição quando a rota `/listar-cursos` for acessada. Lembrando de fazer o _require_ do _autoloader_ no arquivo `index.php` para que a aplicação saiba em que diretório o _controller_ está.  
Em seguida fazemos o mesmo para a página de "criar um novo curso".
O progresso até aqui pode ser visto no _commit_ [461dfe4](https://github.com/brnocesar/alura/commit/461dfe488ece6fac52a56a311cbcee9a4e7428ae)

## 3.1. Interfaces
Como os dois _controllers_ criados até o momento são bem parecidos, possuem uma função com mesmo nome e assinatura, faz sentido implementar uma interface com essas informações. Assim teremos uma espécie de "contrato" que os _controllers_ "assinaram" definindo o que cada um deles precisa implementar de forma obrigatória, trazendo um pouco mais de segurança para nosso código (_commit_ [7c7c772](https://github.com/brnocesar/alura/commit/7c7c7728902ac612e0f99e29ef51803e16b6aea3)).

## 4. Isolando HTML<a name='4'></a>
Por questão de organização e para facilitar a manutenção do nosso código podemos (devemos) separar o HTML do código referente às regras de negócio.

Para isso, criamos os devidos diretórios na pasta `public` e arquivos específicos para cada uma das páginas. Após isso basta mover o código HTML dos _controllers_ para seus respectivos arquivos e dar um `require` nos _controllers_ (_commit_ [9d8bafa](https://github.com/brnocesar/alura/commit/9d8bafa37ef3d74c2f9cc92e2e0f00902c15d9b4)).

Agora que separamos o HTML podemos observar que estes arquivos possuem código em comum, então faz sentido separá-los em arquivos menores (_commit_ [448084d](https://github.com/brnocesar/alura/commit/448084d7ed5c2198745f27a37a7307f28b9e69f5)).

## 5. HTTP, Formulários e Validação<a name='5'></a>
Para que seja possível adicionar novos cursos precisamos modificar a _view_ do formulário (`view/cursos/novo-curso.php`). Adicionamos a rota que queremos enviar a requisição no atributo `action` da tag HTML `<form>` e definimos o verbo da requisição como `POST`.  
Agora precisamos criar um _controller_ para esta rota. Este _controller_ deverá pegar os dados enviados pela página do formulário, criar o modelo Curos e persistir no Banco de Dados o "novo curso". Além disso devemos definir um caso para esta rota no arquivo `public/index.php` (_commit_ [8301da3](https://github.com/brnocesar/alura/commit/8301da34af0e52c5665d1f58e2506acba8604fb6)).  
Podemos também definir filtros para os dados vindos na requisição (_commit_ [c80005b](https://github.com/brnocesar/alura/commit/c80005b1fc0213d5b579f545281ecbe8c472c5c5)).

### 5.1. Redirecionamento
Podemos indicar redirecionamentos para nossa aplicação através de cabeçalhos HTTP (_commit_ [52b3bd5](https://github.com/brnocesar/alura/commit/52b3bd5c382511ce37c039503c221ba1731fc0e9)).

### 5.2. Rotas
Vamos separar as rotas da aplicação em arquivo próprio e implementar uma lógica no arquivo `public/index.php` para que, ele apenas faça a intermediação entre o arquivo de rotas e os _controller_ (_commit_ [8c1de9f7](https://github.com/brnocesar/alura/commit/8c1de9f722866c5d355943c1d1e3616d2fcd4bdf)).