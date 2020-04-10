# Arquitetura MVC com PHP

#### Índice
1. <a href='#1'>Configurando o ambiente</a>
2. <a href='#2'>Ponto único de entrada</a>
3. <a href='#3'>_Controllers_</a>
4. <a href='#4'>Isolando HTML</a>
5. <a href='#5'>HTTP, Formulários e Validação</a>
6. <a href='#6'></a>
7. <a href='#7'></a>
8. <a href='#8'></a>
9. <a href='#9'></a>

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

## 6. Finalizando o CRUD de Cursos<a name='6'></a>
### 6.1. Remover Curso
Vamos adicionar a funcionalidade que permitirá excluir um curso que ja foi cadastrado.

Primeiro adicionamos um botão na _view_ "listar cursos" para executar esta ação: definimos o estilo do botão, a rota e passamos o identificador único do curso pela rota.

Em seguida criamos o _controller_ que irá processar essa requisição: tratamos o dado que vem no _input_, recuperamos a referência ao registro correspondente ao `id` passado, executamos a ação no Banco e redirecionamos para a _view_ "listar cursos".

Adicionamos a nova rota no arquivo de rotas `config/routes.php` e podemos testar.

Caso ocorra algum erro do tipo:
```sh
Warning: require(C:\Users\sadasd\AppData\Local\Temp\__CG__AluraCursosEntityCurso.php): failed to open stream: No such file or directory in C:\Users\rodrigo\Desktop\gerenciador-de-cursos\vendor\doctrine\common\lib\Doctrine\Common\Proxy\AbstractProxyFactory.php on line 206

Fatal error: require(): Failed opening required 'C:\Users\sadasd\AppData\Local\Temp\__CG__AluraCursosEntityCurso.php' (include_path='.;C:\php\pear') in C:\Users\rodrigo\Desktop\gerenciador-de-cursos\vendor\doctrine\common\lib\Doctrine\Common\Proxy\AbstractProxyFactory.php on line 206
```
ou
```sh
PHP Warning:  require(/tmp/__CG__AluraCursosEntityCurso.php): failed to open stream: No such file or directory in /home/bruno/repositorios/alura/php/formacao_php/8-mvc/vendor/doctrine/common/lib/Doctrine/Common/Proxy/AbstractProxyFactory.php on line 206

PHP Fatal error:  require(): Failed opening required '/tmp/__CG__AluraCursosEntityCurso.php' (include_path='.:/usr/share/php') in /home/bruno/repositorios/alura/php/formacao_php/8-mvc/vendor/doctrine/common/lib/Doctrine/Common/Proxy/AbstractProxyFactory.php on line 206

127.0.0.1:59816 [500]: GET /excluir-curso?id=8 - require(): Failed opening required '/tmp/__CG__AluraCursosEntityCurso.php' (include_path='.:/usr/share/php') in /home/bruno/repositorios/alura/php/formacao_php/8-mvc/vendor/doctrine/common/lib/Doctrine/Common/Proxy/AbstractProxyFactory.php on line 206
```
talvez seja necessário rodar o comando abaixo:
```sh
$ php vendor/bin/doctrine orm:generate-proxies
```
o motivo:  
> _"Em alguns momentos o Doctrine não utiliza a nossa própria classe (por exemplo, `Curso::class`), mas a "embrulha" em uma classe gerada por ele, chamada `Proxy`, o que permite algumas manipulações. Depois, ele envia para o banco os dados da classe `Proxy` que está "embrulhando" a nossa._  
_Quando esse tipo de erro acontece, pode ser um problema de permissão na pasta "Temp" (e nosso instrutor nunca viu esse erro acontecer em outras plataformas, apenas no Windows). Para consertarmos isso, no terminal, executaremos o comando `vendor\bin\doctrine orm:generate-proxies`. Isso fará com que o Doctrine processe as nossas entidades e gere as classes `Proxy`."_

A implementação dessa funcionalidade esta no _commit_ [f6aa236](https://github.com/brnocesar/alura/commit/f6aa23696d9d661d2c218f85941ab450a065969e).

### 6.2. Editar Curso
Vamos adicionar a funcionalidade que permitirá modificar um curso que já foi cadastrado.

Primeiro adicionamos um botão na _view_ "listar cursos" para executar esta ação: definimos o estilo do botão, a rota e passamos o identificador único do curso pela rota.

Em seguida criamos o _controller_ que irá retornar a _view_ de edição. Apenas tratamos o parâmetro `'id'` que deve vir na URL, e passando na validação, recuperamos a referência ao registro correspondente ao `id` passado. 

Podemos aproveitar a mesma _view_ usada para cadastrar um curso e assim faremos. Note que o nome do arquivo que contem todo HTML foi modificado de `public/novo-curso.php` para `public/formulario-curso.php` de modo que fique mais genérico e foram feitas as devidas alterações no código.
Além disso precisamos apresentar na _view_ a atual descrição do curso, mas apenas se existe um curso, para que não ocorram erros quando a intenção for criar um novo curso.  
As modificações feitas até este ponto podem ser encontradas no _commit_ [7a4f1de](https://github.com/brnocesar/alura/commit/7a4f1de0767279341ebc611c919776aca5f7aa19).

A persistência no Banco é feita pelo _controller_ de persistência quando clicamos no botão 'Salvar', portanto é lá que devemos avaliar se estamos criando um novo registro ou alterando um já existente.  
Quando estamos editando um registro que já existe enviamos o parâmetro `'id'` pela URL, o que não acontece quando estamos criando um novo registro. Então é isso que iremos avaliar.  
Primeiro montamos o modelo `Curso` (instânciamos um objeto dessa classe); avaliamos se recebemos o parâmetro `'id'`, se sim: setamos o `'id'` e atualizamos o registro no Banco; do contrário, criamos um novo registro.

Precisamos adicionar uma tratativa na rota do botão 'Salvar' na _view_ para o parâmetro `'id'` que será ou não enviado pela URL. A finalização do CRUD de cursos está no _commit_ [bffe435](https://github.com/brnocesar/alura/commit/bffe4356f0d5f77cfc014d25fedb20c3e440a110).

### 6.3. Isolando (ainda mais) o HTML
Até o momento, em todos os locais de nossa aplicação quando queremos "chamar" o código HTML das _views_ precisamos fazer um `require` passando todo o caminho. Isso não é bom pois existe a possibilidade acabarmos digitando errado este caminho e também, da forma como isso é feito, o HTML acaba tendo acesso a todas as variáveis definidas no _controller_ onde esse `require` é feito.

Vamos isolar ainda mais o HTML criando um _controller_ base que terá a única reposnabilidade de chamar o HTML e definir quais variávei ele terá acesso, e ele será então herdado pelos "controladores de _views_". Como esta classe terá a única reponsibilidade de retornar o código HTML necessário para renderizar uma página e será herdada pelos _controllers_ que necessitem utilizar esta função, faz sentido defini-la como uma classe abstrata, dessa forma impedimos que ela seja instânciada.

Em nosso _controller_ base teremos um método que recebe o caminho relativo para o arquivo com código HTML e um _array_ com os dados que este código precisa/pode ter acesso. Dentro do método usamos a _built in function_ `extract()` que extrai o valor de cada elemento de um vetor para uma variável com o mesmo nome da chave deste elemento. Por fim damos `require` no arquivo da _view_ usando a variável que recebe o caminho relativo (montando o caminho completo é claro). Esta implementação pode ser encontrada no _commit_ [d6a23ee](https://github.com/brnocesar/alura/commit/d6a23ee58fe16b62c3869c83d72b0c5087bebd9f).

No _commit_ [3312ed4](https://github.com/brnocesar/alura/commit/3312ed4d42579d94b3b3a22b41f147ba85de830c) modificamos o método `renderizaHtml()` para retornar o conteúdo HTML que seria exibido em formato de _string_. O PHP permite manipular o conteúdo de um arquivo que está sendo exibido (_Output Buffer_), para isso precisamos "inicializar a saída do _buffer_" antes do `require` com o método `ob_start()` (_"output buffer start"), dessa forma o PHP vai começar a guardar tudo que é exibido. 

Quando quisermos pegar o conteúdo do _buffer_ usamos a função `ob_get_contents()` que retorna o conteúdo como _string_, após isso devemos limpar o _buffer_ com `ob_clean()`. Ou podemos simplesmente usar `ob_get_clean()`, que retorna o conteúdo do _buffer_ e após isso o limpa.

Após isso ainda temos que atualizar os controladores de _views_, mandando-os imprimir na tela o conteúdo HTML retornado.

## 7. Autenticação<a name='7'></a>