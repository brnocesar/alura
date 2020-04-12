# Arquitetura MVC com PHP

#### Índice
1. <a href='#1'>Configurando o ambiente</a>
2. <a href='#2'>Ponto único de entrada</a>
3. <a href='#3'>_Controllers_</a>
4. <a href='#4'>Isolando HTML</a>
5. <a href='#5'>HTTP, Formulários e Validação</a>
6. <a href='#6'>Finalizando o CRUD de Cursos</a>
7. <a href='#7'>Autenticação</a>
8. <a href='#8'>_Traits_</a>
9. <a href='#9'>PSRs e Boas Práticas</a>
10. <a href='#10'>WebService</a>

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
### 7.1. Página de Login
Criamos a página HTML, o controlador da _view_ e adicionamos a rota (_commit_ [800f544](https://github.com/brnocesar/alura/commit/800f544033c45bb1493c72e00a0d35f7c2146385)).

### 7.2. Criando o primeiro Usuário
O primeiro usuário será criado pela linha de comando, usando um `INSERT` direto no Banco. A interface por linha de comandos do Doctrine oferece o comando `dbal:run-sql` para executar SQL.

Para criar um registro na tabela 'usuarios' devemos passar um e-mail e a _hash_ da senha. Vamos determinar a _hash_ da senha usando a função `password_hash()` da [API de senhas do PHP](https://www.php.net/manual/pt_BR/ref.password.php) e o algoritmo `PASSWORD_ARGON2I`, para isso abra um terminal e o interpretador do PHP:
```
$ php -a
Interactive mode enabled

php > echo password_hash('123456', PASSWORD_ARGON2I);
$argon2i$v=19$m=65536,t=4,p=1$cEVMdHZ4WmJqSVdVWDk0aQ$e30Ak8uD0E3elDsaemLOyGIn06bnBzS4j2MFQ5If7nM
php > 
```

Agora podemos rodar o SQL que vai criar o registro na tabela 'usuarios', passando o e-mail de sua prefência e a _hash_ obtida no passo anterior:
```sh
$ pdoc dbal:run-sql 'INSERT INTO usuarios (email, senha) VALUES ("bruno@bruno.com", "$argon2i$v=19$m=65536,t=4,p=1$cEVMdHZ4WmJqSVdVWDk0aQ$e30Ak8uD0E3elDsaemLOyGIn06bnBzS4j2MFQ5If7nM");'
```

Se a única saída no terminal foi `int(1)` significa que ocorreu tudo certo, pois o comando `run-sql` retorna o número de linhas afetadas. Para verificar os registros da tabela 'usuarios' podemos rodar o comando:
```sh
$ pdoc dbal:run-sql "SELECT * FROM usuarios;"
array(1) {
  [0]=>
  array(3) {
    ["id"]=>
    string(1) "1"
    ["email"]=>
    string(15) "bruno@bruno.com"
    ["senha"]=>
    string(96) "$argon2i$v=19$m=65536,t=4p=1$cEVMdHZ4WmJqSVdVWDk0aQ$e30Ak8uD0E3elDsaemLOyGIn06bnBzS4j2MFQ5If7nM"
  }
}
```

Se a saída não for o que você espera, digamos, a _hash_ da senha está incompleta ou coisa do tipo, experimente trocar aspas simples por duplas e vice-versa: `'` -> `"` e `"` -> `'` .

### 7.3. Validando um usuário
Primeiro definimos a rota para onde serão enviados os parâmetros da requisição POST que a página de login fará, e em seguida criamos o _controller_ que vai processar essa requisição, de acordo com o que foi definido no arquivo de rotas.

Como queremos verificar se as credências inseridas na página de login são de um usuário registrado, vamos precisar consultar a tabela 'usuarios' e para isso será necessário um "repositório de usuários", que é inicializado no construtor da classe.

Após validar os _inputs_ vindos pela requisição realizamos uma consulta na tabela de acordo com o email. Para determinar se as credências são inválidas avaliamos duas condições: (i) o objeto `$usuario` recuperado deve ser nulo; ou (ii) o método `senhaEstaCorreta()` (da classe modelo Usuario) deve retornar `false`.

O método `senhaEstaCorreta()` da classe Usuario usa a função `password_verify()` da API de senhas do PHP. Esta função recebe dois parâmetros: a "senha pura" e uma _hash_, e retorna `true` se a "senha pura" coincidir com a _hash_ e `false` em contrário.

Passando no teste, redirecionamos para a página de "listar cursos". A implementação da validação de um usuário pode ser vista no _commit_ [6974fe7](https://github.com/brnocesar/alura/commit/6974fe755397c470bae8d8e04ede1e3c918d9fed).

### 7.4. Trabalhando com a sessão
Apesar de termos a funcionalidade de login implementada em nossa aplicação, ainda não estamos verificando se o usuário está logado para permitir que ele acesse outras páginas. É isso que faremos agora, e para isso vamos usar o conceito de "sessão", que serve para guardarmos informação entre as requisições.

Como queremos apenas identificar se um usuário fez login, podemos definir uma váriavel no _controller_ que realiza esta verificação. No caso, fazemos atribução `$_SESSION['logado'] = true` logo antes de redirecionar para a rota de listar cursos. Você pode adicionar um índice com o nome e o valor que quiser, o importante é ser coerente com essa escolha quando for avaliar se um usuário está ou não autenticado.

Mas para utilizarmos a sessão devemos informar isso ao PHP, então adicionamos `session_start()` no arquivo `public/index.php` que é nosso ponto único de entrada. Dessa forma a sessão estará inicializada para todas as rotas da aplicação. Um ponto importante a ser levado em consideração é que: a função `session_start()` deve ser chamada antes de qualquer saída ser enviada para o navegador (`echo`, `var_dump`, `print_r`...).

Se o objetivo é permitir que usuários não autenticados possam acessar apenas as rotas login, o lugar ideal para avaliar se o usuário está logado é no ponto único de entrada, também conhecido como `public/index.php`.  
Devemos verificar se a variável definida na sessão possui o devido valor e também se a rota sendo acessada é uma das "não autenticadas". No _commit_ [7b34f4b](https://github.com/brnocesar/alura/commit/7b34f4b3a1caeecb236337c041619f4f4bddb76b) esão as alterações adicionadas nesta parte.

### 7.5. _Logout_
Para implementra esta funcionalidade precisamos apenas de um _controller_ com duas linhas de código: na primeira usamos a função `session_destroy()` para informar ao PHP que a sessão do usuário não é mais válida, que deve ser destruída; e na segunda apenas redirecionamos para a página de login. E precisamos, é claro, de uma rota para este _controller_.

Para executar a ação de _logout_ vamos usar um botão que será colocado em uma _navbar_ que também está sendo adicionada agora. Além disso, também condicionamos a apresentação deste botão ao usuário estar logado. Essa funcionalidade passou a estar disponível no _commit_ [ca4ebcc](https://github.com/brnocesar/alura/commit/ca4ebcce7e188352aa4234f0d32f4aa0e35e5268).

### 7.6. _Flash messages_
Vamos adicionar mensagens de _feedback_ para o usuário de acordo com a ação realizada. No arquivo `view/inicio-html.php` adicionamos o código HTML das mensagens.

E em cada _controller_ que realizar alguma ação "digna" de ser informada adicionamos na sessão a mensagem e seu tipo. 

Note que devemos: 
- condicionar a apresentação do código HTML relativo às mensagens à existência dessas variáveis na sessão; e
- permitir que essas mensagens fiquem na sessão por apenas uma requisição, ou seja, devem ser removidas da sessão assim que forem apresentadas.

No _commit_ [10a66b0](https://github.com/brnocesar/alura/commit/10a66b08e1967ba061feee2b7f65a2158a4231a6) você pode acompanhar as alterações.

## 8. _Traits_<a name='8'></a>
De forma "beeeeeemm" resumida, _traits_ são usadas para resolver o "problema" da herança multipla em PHP, já que não é possível herdar mais de uma classe. É a herança horizontal.   
No caso das _flash messages_ podemos centralizar em um arquivo a responsabilidade definir uma variável na sessão, para usar nas _flash messages_ por exemplo.

Então criamos um arquivo chamado `FlashMessageTrait.php` na pasta Helper e definimos como uma _trait_. Criamos a função `defineMensagem()` que recebe dois parâmetros que representam o tipo e a mensagem que vamos colocar na sessão, e no seu corpo fazemos a definição das variáveis na sessão.  
Agora basta "informar o uso" desta _trait_ nos arquivos que enviam mensagem para a sessão, isso é feito adicionando a palavra chave `use` junto com o nome da _trait_ `use FlashMessageTrait;` logo no começo da classe. Dessa forma é como se o PHP adicionasse todo o conteúdo a _trait_ na classe em que está sendo usado, tornando suas funcões disponíveis nessa classe. Feito isso basta trocar o código que adiciona as mensagens na sessão (_commit_ [23d1818](https://github.com/brnocesar/alura/commit/23d1818bf3671fd6468af783f44797846523a488)).

Podemos fazer mesmo para a funcionalidade de "renderizar HTML", ao invés de termos uma classe reponsável por isso e que será herdada pelas classes que retornem _views_, teremos uma _trait_ que fará isso (_commit_ [4372eab](https://github.com/brnocesar/alura/commit/4372eab510927480326cd41fee402a10585a8d94)).

## 9. PSRs e Boas Práticas<a name='9'></a>
### 9.1. O que são as PSRs
As _PHP Standards Recommendations_ (PSRs) são padrões de desenvolvimento criados pelo Grupo de Interoperabilidade entre Frameworks do PHP (__, de sigla PHP-FIG no inglês). Esses padões são recomendações que garantem a "compatibilidade" e fácil migração de aplicações desenvolvidas com os principais _frameworks_ e pacotes do PHP. 

### 9.2. Tratando requisições e repostas HTTP como objetos
Vamos instalar dois pacotes agora para lidar com as requisições e repostas HTTP. Atualmente os _controllers_ da aplicação implementam uma interface que não segue a PSR7 (sobre mensagens HTTP), então vamos substituir esta interface por uma que esteja de acordo com os padrões. Para isso vamos instalar três pacotes: o primeiro (`psr/http-message`) possui as interfaces de requisição e resposta, o segundo (`nyholm/psr7`) contém a implementação de uma fábrica de mensagens HTTP, e o terceiro (`nyholm/psr7-server`) serve para criar requisições a partir das variáveis super globais do PHP (_commit_ [583a0c0](https://github.com/brnocesar/alura/commit/583a0c05456ba3f03516902e4bef6541359d4274)). Os comandos para instalar estes pacotes são:
```sh
$ composer require psr/http-message
$ composer require nyholm/psr7
$ composer require nyholm/psr7-server
```

Após isso modificamos a interface `InterfaceControladorRequisicao` para que o método `processaRequisicao()` passe a receber um objeto "requisição HTTP" e retorne uma "resposta HTTP". O único _controller_ modificado foi o `ListarCursos`, apenas para testes (_commit_ [becb94e](https://github.com/brnocesar/alura/commit/becb94e372d2cefc63dc71c2e5d2da1da3236d1e)).

### 9.3. Padronizando a "interface controladora de requisições"
Vamos fazer isso adequando nossa aplicação à PSR15, que trata de controladores de requisições. Começamos adicionando mais um pacote ao nosso projeto (_commit_ [b60de6a](https://github.com/brnocesar/alura/commit/b60de6a01e9867aa086d925dff2ed70dacae3d0c)):
```sh
$ composer require psr/http-server-handler
```
Com isso podemos substituir a interface controladora de requisições que fizemos por uma que segue os padrões da PSR15. Além disso devemos modificar o nome dos métodos implemtandos nos devidos _controllers_ e no ponto único de entrada da aplicação (_commit_ [55056bc](https://github.com/brnocesar/alura/commit/55056bc0c95ccf52b7a75c819c21f3ba0961c42e)). 

### 9.4. Injeção de dependência
Até o momento em todas as classes que precisamos usar um gerenciador de entidades, instânciamos ele no construtor, ou seja, isso é feito no _controller_. Mas se pudermos simplemesnte pedir que alguma classe seja passada como parâmetro ao construtor, isso deixaria de ser responsabilidade do _controller_.

As coisas ficam um pouco complicados porque nem todos os _controllers_ vão utilizar as mesmas classes. Existem formas "avançadas" (puxa vida né, entendi nem a forma simple ainda...) de resolver esse problema, mas aqui vamos utilizar um pacote que implementa uma classe chamada de "contêiner de dependências" que _"a partir do nome de uma classe, irá descobrir tudo que ela precisa, criar, colocar no construtor dela e devolver um objeto da classe já instanciada"_. Este pacote externo é o `php-di/php-di` e os contêineres de dependências são tratados pela PSR11 (_commit_ [19b0a4e](https://github.com/brnocesar/alura/commit/19b0a4ea2503d09ab804567512b260192b3f6ef5)).

Criamos um arquivo chamado `dependencies.php` na pasta `config` e instânciamos um `ContainerBuilder`, no qual adicionaremos as definições de classes mapeadas. No ponto único de entrada atribuímos o retorno deste arquivo a uma variável e a usamos para "pedir" uma instância da classe controladora. Modificamos o contrutor em `ListarCursos.php` e pronto (_commit_ [6ca1b6b](https://github.com/brnocesar/alura/commit/6ca1b6b38764c226858bbb9a36f20a3d4132c0ae)).

### 9.5. Adequando toda a aplicação
No _commit_ [47e9dd9](https://github.com/brnocesar/alura/commit/47e9dd97dec42100785c305ef7315a50da47df51) todos os _controllers_ foram adequados aos padrões das PSRs.

Apenas recapitulando, as PSRs tratadas foram:
- PSR-4: _Autoloading_
    - Não tinha o que adequar, apenas informamos no `composer.json` que este seria o padrão seguido para o _autoloading_ e isso foi feito no começo do projeto.
- PSR-7: _HTTP message interfaces_
- PSR-11: _Container interface_
- PSR-15: _HTTP Server Request Handlers_
    - Haviamos escrito uma interface com apenas a função `processaRequisicao()`, que não recebia nada como parâmetro e também não possuia retorno algum. Agora os _controllers_ implementam a interface de um pacote externo. O nome da interface, de sua função, os parâmetros recebidos e retornos, agora seguem o padrão da comunidade.

## 10. WebService<a name='10'></a>
Até o moento nossa aplicação foi acessada por um usuário que escolhia a ação a ser feita ou as informações que ele queria consultar, e seu navegador recebia todo o código HTML para renderizar e apresentar uma página agradável para o usuário.

Mas e quando uma outra aplicação precisar acessar informações em nosso Gerenciador de Cursos? Nesse caso todo o código HTML retornado vai apenas ser informação desnecessária, então faz sentido retornar a informação em um formato mais enxuto. Esse é o conceito de um _WebService_ (pelo menos é o que eu entendo hoje).

Adicionamos _controllers_ e rotas que retornam uma "lista" de cursos nos formatos:
- JSON: existe uma _built in function_ do PHP que transforma qualquer objeto para formato JSON, precisamos apenas fazer a classe modelo implementar uma interface que informe que a entidade é serializável como JSON e escrever um método retornando alguma informação que o PHP consiga transformar em JSON, como um _array_ associativo. Se a classe modelo não implementar essa interface, todos seus atributos devem ser públicos, mas nem sempre isso é desejável (_commit_ [cb54699](https://github.com/brnocesar/alura/commit/cb546996f32c2a8204aea824bd4769fdba382591)); e
- XML: o PHP não possui _built in functions_ que tranformem objetos para este formato, mas o processo é bem simples então é feito (nesse caso) diretamente no _controller_ (_commit_ [c61b8c3](https://github.com/brnocesar/alura/commit/c61b8c38406e867321d681ab5b5662f1a78d45dc)).

Ainda adicionamos o `Content-Type` no cabeçalho da resposta HTTP (_commit_ [1ced421](https://github.com/brnocesar/alura/commit/1ced421321f2bc0e6e96c8ce9777571a59473fc2)).