# Laravel
O Laravel é um _framework full stack_ do PHP, ou seja, nos oferece ferramentas para desenvolver a lógica da aplicação (_back-end_) e a interface de interação do usuário (_front-end_). Ele segue a arquitetura MVC e oferece uma série de facilidades que permitem um rápido desenvolvimento. O objetivo aqui será desenvolver uma aplicação para gerenciar as séries que o usuário estiver assistindo.

#### Índice
1. <a href='#1'>Configurando o ambiente</a>
2. <a href='#2'>_Controllers_</a>
3. <a href='#3'>_Views_</a>
4. <a href='#4'></a>
5. <a href='#5'></a>
6. <a href='#6'></a>
7. <a href='#7'></a>
8. <a href='#8'></a>
9. <a href='#9'></a>
10. <a href='#10'></a>

## 1. Configurando o ambiente<a name='1'></a>
### 1.1. Criando um projeto
O primeiro passo é se certificar de que todas as ferramentas/_softwares_ necessários estão instalados: o mínimo é o PHP 7.1.3 (ou maior) e o composer (pela facilidade para criar e gerenciar o projeto; além disso, é necessário que algumas [extensões](https://laravel.com/docs/5.8) do PHP estejam habilitadas.

Isso pronto, podemos rodar o comando que cria um projeto Laravel, então vá até o diretório que pretende desenvolver o projeto e rode o comando abaixo:
```sh
$ composer create-project --prefer-dist laravel/laravel nome-projeto 5.8.*
```
isso vai criar uma pasta com nome `nome-projeto` e todos os arquivos necessários para dentro dela (_commit_ [be90199](https://github.com/brnocesar/alura/commit/be9019905600c96afa5fc8307b43b587e46b8e89)). Além disso, especificamos a versão 5.8 do Laravel.

### 1.2. Estrutura de arquivos
Entrando no diretório do projeto podemos ver suas pastas:
- `app`: contém toda lógica da aplicação
- `config`: arquivos de configuração
- `database`: é onde ficam as migrations
- `resources`: fica toda a parte visualizada pelo usuário
- `routes`: armazena as rotas da aplicação

### 1.3. A primeira rota
Vamos começar falando sobre as rotas, que são o _"mapeamento de URLs para ações no PHP"_. Ao entrarmos da pasta `routes` podemos observar alguns arquivos de rotas, cada uma específica para um tipo de aplicação. No caso de uma aplicação web podemos usar o arquivo `routes/web.php`.

Ao abrir este arquivo podemos ver a definição de uma rota, note que o verbo é GET, o primeiro parâmetro é `'/'` e o segunda uma função. Podemos assumir que o primeiro parâmetro se trata da rota, então vamos acessá-la para ver o que temos.

Para levantar um servidor de desenvolvimento no Laravel podemos usar o Artisan, que é uyma ferramenta de linha de comando que nos oferece uma série de facilidades no desenvolvimento de projetos Laravel. O comando é:
```sh
$ php artisan serve
```

Então acessamos a rota raiz do domínio `localhost:8000` (por via das dúvidas, sempre confira a porta na saida do terminal) e vemos a tela de baos vindas do Laravel.

Agora vamos criar nossa própria rota que vai apresentar um texto de nossa escolha. Definimos uma nova rota no primeiro argumento e printamos alguma coisa ao invés de retornar uma _view_. No exemplo da [minha implementação](https://github.com/brnocesar/alura/commit/dd422984a25273af237ce4700e56ad67a21d3262), ao acessar a rota `localhost:8000/ola` recebo o texto `"Olá Mundo!"` na tela.

Mas como a aplicação é para gerenciar minhas séries, vou trocar o texto inicial (e a rota) para algo mais próximo do contexto. Além disso vou apresentar o conteúdo como HTML (_commit_ [6a4eb96](https://github.com/brnocesar/alura/commit/6a4eb969212fa08dce5085bb5a7a4060af1e0cf5)). Agora ao acessar a rota `localhost:8000/series` é possível observar que existe uma lista HTML na página retornada.

## 2. _Controllers_<a name='2'></a>
Note que neste momento as rotas estão fazendo mais que sua responsabilidade, que é "levar à execução de uma ação". Como essa ação será executada é responsabilidade de outro tipo de arquivo, portanto, vamos criar um _controller_ e mover este código que foi escrito na rota.

Navegamos até a pasta `app/Http/Controllers` e criamos um arquivo chamado `SeriesController.php`. Note que o `namespace` deve reproduzir a árvore de diretórios e a nossa classe deve herdar a classe `Controller`.

Então vamos mover o código que está na rota para esta classe, fazendo as devidas modificações. Precisamos definir um método público e com nome em nossa classe para receber o código, e na rota devemos especificar o que será executado quando esta rota for acessada.  
No lugar da função na rota informamos: o caminho relativo à pasta Controllers (`SeriesController`) e o método que será executado (`listarSeries`), unidos por uma arroba (`@`) (_commit_ [b998c74](https://github.com/brnocesar/alura/commit/b998c742a14108e05ea1c8260262f10fb21726d7)).  
Feito isso basta acessar a rota novamente e conferir que está tudo certo (é para estar tudo certo, se não tiver você fez alguma coisa de errado, ou a sintaxe do Laravel mudou desde que isso foi escrito).

## 2.1. Acessando dados da requisição
Podemos [injetar uma dependência](https://github.com/brnocesar/alura/tree/master/php/formacao_php/8-mvc#9-4) no nosso método para que ele possa receber dados de uma requisição através da classe `Request`. Com isso temos acesso a várias informações interessantes como a URL da requisição e aos parâmetros passados (_commit_ [56c9087](https://github.com/brnocesar/alura/commit/56c90871bc1fef44b67b247606894d41f4d39a54)).

## 3. _Views_<a name='3'></a>
### 3.1. _View_ de listagem de séries
Vamos isolar mais as responsabilidades da nossa aplicação. Agora vamos retirar o HTML do _controller_ e colocá-lo no seu devido lugar: nas _views_.

Antes disso vamos alterar o nome do nosso método para `index()`, pois este é o método que me apresenta todas as minhas séries e assim vamos estar de acordo com o padrão do Laravel (_commit_ [f3bb924](https://github.com/brnocesar/alura/commit/f3bb924de8cebf4bf9263dc73d41c67babf8b23a)).

Feito isso, na pasta `resources/views` criamos a pasta `series` e dentro dela o arquivo de _view_ `index.php`. Note que é um arquivo PHP que vai ter a estrutura HTML, assim seremos capazes de acessar variáveis.

No método `index()` agora retornamos o método `view()` que recebe dois parâmetros: o primeiro é o caminho relativo da _view_ com `.` (pontos) no lugar de `\` (barras) e o segundo são as variáveis que a _view_ terá acesso.  
Existem duas formas como as variáveis podem ser passadas para a _view_:
- através de um _array_ associativo em que a chave é a variável acessível na _view_ e o valor a variável do _controller_;
```php
return view('series.index', ['variavelNaView' => $variavelNoController]);
```
- e a outra é usando a função `compact()` do PHP, que busca uma váriavel com o nome passado e retorna um _array_ associativo.
```php
return view('series.index', compact('series'));
```
Isso pode ser visto no _commit_ [1e20b08](https://github.com/brnocesar/alura/commit/1e20b08fea3e7294262907c223cdcab3d3132576).

### 3.2. Estilizando a _view_ com Bootstrap
Uma boa aplicação deve ser agradável aos olhos, então vamos aplicar "estilo" em nossa _view_. Por questão de praticidade vamos usar um _Framework_ de CSS, o [Bootstrap](https://getbootstrap.com/), e na sua página encontramos facilmente o _link_ para incluir essa ferramenta em nosso projeto sem precisar fazer _download_ algum, basta inserir o _link_ na _view_.

Apenas essa ação já é o suficiente para alterar os _bullets_ e a fonte da nossa página, então prosseguimos adicionando classes às _tags_ no nosso HTML, containeres, cabeçalhos...

Pensando mais a frente, também ja criamos um botão para adicionar novas séries (_commit_ [8d81910](https://github.com/brnocesar/alura/commit/8d81910a00492835e4920d77ecd17fa69f304372)).

### 3.3. _View_ para adicionar série
Vamos criar uma _view_ para adicionar novas séries a nossa lista. Devemos então: (i) criar um arquivo de _view_; (ii) criar um método que retorne essa _view_; e (iii) criar uma rota para acessar esse método. Além disso colocamos essa rota no botão da _view_ de listagem (_commit_ [8486838](https://github.com/brnocesar/alura/commit/848683863cb48ebfacfe1e4851df9507dfa9398a)).

### 3.4. Blades
Note que nas duas _view_ criadas praticamente todo HTML é repetido, por isso vamos começar usar um recurso do Laravel que permite definir um _layout_ que pode ser usado por qualquer _view_ do projeto.

Criamos um arquivo `layout.blade.php` na pasta `views` e colocamos todo HTML que é comum (_commit_ [8223c05](https://github.com/brnocesar/alura/commit/8223c05d55eeec63712ab97723711d9cf0a967e0)).

O Blade utiliza o conceito de seções, o que significa que podemos definir (rotular) seções no _layout_ que receberão diferentes conteúdos (_commit_ [b05fc7f](https://github.com/brnocesar/alura/commit/b05fc7f6be94f343389cc41c7aaee3a5bc265f8f)). Ou seja, o _layout_ contém apenas uma estrutura e nessa estrutura teremos partes que serão informadas pelo arquivo que o usar.

Agora que temos um _layout_ Blade, podemos modificar nossas _views_ para utilizarem-no. O primeiro passo é renomear as _views_ para o padrão Blade. Após isso, a primeiro coisa em cada _view_ deve ser a informação de que elas "herdam" o _layout_, e aṕos isso vamos abrindo e fechando cada seção adicionando o devido conteúdo (_commit_ [919751d](https://github.com/brnocesar/alura/commit/919751d2633321b6f097016a77f335442a115db8)).

Outra funcionalidade do Blade é permitir escrever PHP de uma forma mais amgável utilizando `@` ao invés de `tags` (_commit_ [4e0a1f0](https://github.com/brnocesar/alura/commit/4e0a1f0d4eb87fc5a7f705880e1d1a35694818c9)).

## 4. <a name='4'></a>
## 5. <a name='5'></a>
## 6. <a name='6'></a>
## 7. <a name='7'></a>
## 8. <a name='8'></a>
## 9. <a name='9'></a>
## . <a name=''></a>
## . <a name=''></a>
## . <a name=''></a>
