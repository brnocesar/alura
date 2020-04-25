# Doctrine - ORM

O ORM (Object Relational Mapping) é o componente do Doctrine responsável por mapear instâncias de uma classe no código orientado a objetos para uma tabela/relacionamento no Banco de Dados.

O mapeamento é feito por um [**gerenciador de entidades**](https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/tutorials/getting-started.html#obtaining-the-entitymanager) (_entityManager_) por meio de [**anotações**](https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/basic-mapping.html) (_annotations_). Existem outros meios além das anotações, mas aqui usaremos apenas este.

#### Índice
1. <a href='#1'>Instalação do Doctrine</a>
2. <a href='#2'>Linha de comando</a>
3. <a href='#3'>Gerenciador de Entidades</a>
4. <a href='#4'>Persistindo registros no Banco</a>
5. <a href='#5'>Relacionamentos (1:N)</a>
6. <a href='#6'>_Migrations_</a>
7. <a href='#7'>Atualizando o CRUD de Alunos</a>
8. <a href='#8'>Uma nova entidade e mais sobre relacionamentos (N:N)</a>
9. <a href='#9'>_Lazy Loading_ e DQL</a>
10. <a href='#10'>Repositório</a>
11. <a href='#11'>Configurando o MySQL</a> 

## 1. Instalação do Doctrine<a name='1'></a>
Para adicionar o Doctrine como uma dependência ao seu projeto...

## 2. Linha de comando<a name='2'></a>
O Doctrine possui uma interface de linha de comando que pode ser acessada em `vendor/bin/doctrine`. Em geral a pasta _vendor_ é criada na raiz do projeto (não tenho certeza se o motivo é que o composer.json está lá, mas enfim...), portanto, basta navegar até essa pasta e usar o comando abaixo para ter acesso a lista de comandos do Doctrine:
```sh
$ php vendor/bin/doctrine
```

Provavelmente na primeira vez que o comando acima for rodado, você receberá um aviso de falta um arquivo de configuração. Se isso acontecer basta seguir as instruções apresentadas no terminal.  
O arquivo `cli-config.php` deste projeto foi implementado no _commit_ [f46e96b](https://github.com/brnocesar/alura/commit/f46e96b29059f3c5dc4dfbc625176595446b1b28).

### 2.1. Alias
Por conveniência, você pode adicionar um alias para este comando no seu terminal, isso pode ser feito adicionando o código abaixo ao final do arquivo `.bashrc` que fica na _home_.
```sh
$ cd
$ nano .bashrc

alias pdoc="php vendor/bin/doctrine"
```

### 2.2. Comandos mais utilizados<a name='2.2'></a>
Os comandos mais utilizados (provavelmente) serão:
- `$ pdoc orm:info`: procura por entidades mapeadas e indica se há algum problema
- `$ pdoc orm:mapping:describe Batatinha`: apresenta informações da classe mapeada passada como argumento (Batatinha)
- `$ pdoc orm:schema-tool:create`: processa o _schema_ e gera a Base de Dados

## 3. Gerenciador de Entidades<a name='3'></a>
**Entidade** é termo usado pelo Doctrine para denominar objetos mapeados para o Banco.  
Portanto, **Gerenciador de Entidades** (GE) é a interface reponsável por mapear as instâncias de Classes no código orientado a objetos (OO) para as tabelas no Banco de Dados, de acordo com as _anotações_ existentes.

### 3.1. Fábrica
Para termos acesso ao GE precisamos primeiro criar uma "Fábrica de Gerenciadores de Entidades", que é onde definimos as configurações básicas que devem ser seguidas, como:
- onde devem ser buscadas as anotações que definem o mapeamento
- se o modo de desenvolvimento está ativo ou inativo
- quais as informações do Banco (driver, local, etc)

Neste projeto a fábrica foi criada em `src/Helper/EntityManagerFactory.php`, no _commit_ [6e79b6f](https://github.com/brnocesar/alura/commit/6e79b6f4386b39a03977a2faaa35f92becc20507).

### 3.2. Entidade
As entidades do projeto ficam em `src/Entity`. Em um primeiro momento foi criada apenas uma entidade, chamada Aluno (_commit_ [f46e96b](https://github.com/brnocesar/alura/commit/f46e96b29059f3c5dc4dfbc625176595446b1b28)). Este arquivo é a classe que representa os objetos do tipo Aluno e nela são declarados todos os seus atributos.  
Também é neste arquivo que são feitas as _anotações_ "lidas" pelo GE.

### 3.3. Criando o Banco
Como as informações de conexão ao Banco de Dados já estão definidas na fábrica de geradores, podemos gerar o Banco da aplicação utilizando a linha de comando: 
```sh
$ pdoc orm:schema-tool:create
```

## 4. Persistindo registros no Banco (CRUD)<a name='4'></a>
Os arquivos teste usados para ilustrar os conceitos discutidos nesta seção podem ser encontrados em `commands/`.

### 4.1. `store()`
Para criar um registro no Banco precisamos seguir alguns passos simples:
1. Instânciar um objeto
2. Monitorar este objeto com o GE
3. Realizar a persistência

A implementação teste dessa funcionalidade foi feita no _commit_ [f46e96b](https://github.com/brnocesar/alura/commit/f46e96b29059f3c5dc4dfbc625176595446b1b28).

### 4.2. `index()` e `show()`<a name='4.2'></a>
Quando vamos buscar registros no Banco de Dados, precisamos fazer isso atráves de um "_Repositório_".  
Um repositório permite monitorar objetos de uma classe específica e recuperá-los. Os métodos fornecidos por padrão por um repositório são:
- `findAll()`: todos os objetos da classe
- `find($id)`: apenas um registro, de acordo com a chave primária passada como argumento
- `findBy(['atributo' => 'valor', ...])`: registros de que obedecem as condições passadas no _array_ associativo
- `findOneBy([])`: mesmo funcionamento do método anterior, mas retorna apenas o primeiro registro encontrado

A implementação teste dessa funcionalidade foi feita no _commit_ [9356917](https://github.com/brnocesar/alura/commit/9356917efb31374207761cdc25ec50cafea9a8c5).

### 4.3. `update()`
Para atualizar um registro devemos primeiro recupera-lo do Banco e isso já foi abordado no tópico anterior. Naquele momento foi utilizado um repositório para classe Aluno, mas também é possível recuperar um único registro do Banco sem utilizar este artifício.

É possível usar o método `find()` no GE, passando dois parâmetros (na ordem) relativos ao objeto que queremos buscar:
<ul style="list-style-type:none">
    <li>1<sup>o</sup>: sua classe</li>
    <li>2<sup>o</sup>: sua chave primária</li>
</ul>

Uma vez que temos o objeto que será atualizado basta utilizar os devidos métodos para alterar seus atributos e após finalizada esta etapa executar o método `flush()` do GE.

Note que não é necessário usar o método `persist()` para indicar ao GE que este objeto deve ser monitorado, lembre-se que foi necessário recuperar este registro do Banco, portanto, o objeto já está sendo monitorado pelo GE.

### 4.4. `destroy()`
Da mesma forma que no tópico anterior, antes de realizar alguma ação em um objeto precisamos recuperar seu registro do Banco.

Mas note que se formos seguir o tópico anterior ao final teremos realizado duas _queries_ no Banco: um SELECT e um REMOVE.  
Para a funcionalidade de atualização faz todo sentido realizar um SELECT, pois é necessário ter acesso aos atributos deste registro para modificá-los.  
Mas na situação em que já sabemos (pela chave primária) qual registro será deletado, não há necessidade alguma de ter acesso a seus atributos. Portanto, podemos apenas obter uma referência a este registro e realizar a devida ação. Dessa forma acabamos "economizando" uma _query_ no Banco.

O processo para se obter uma referência a um registro é muito similar ao que foi feito para recuperar um único registro usando a partir do GE. Agora o método aplicado é o `getReference()`, que recebe os mesmos parâmetros que `find()`.

Em seguida usamos o método `remove()`, passando a referência ao registro, e por fim mandamos executar a ação no Banco.

A implementação teste das últimas duas funcionalidades foi feita no _commit_ [72383e7](https://github.com/brnocesar/alura/commit/72383e768354c2682883b6e8acd1af354031cbb3).

## 5. Relacionamentos<a name='5'></a>
Nesta seção vamos tratar sobre relacionamentos, que podem ser de diferentes tipos: _OneToMany_, _ManyToMany_, ...  
Para ilustrar esse conceito vamos criar outra entidade, **Telefone**, e definir que cada **aluno** pode ter mais de um número de telefone associado a si.

### 5.1. Entidade 'Telefone'
O procedimento para criar esta nova entidade é exatamente igual ao que foi feito para **Aluno**:
1. Criamos o arquivo da classe (`Telefone.php`) no diretório `src/Entity`;
2. Definimos os atributos seguindo boas práticas, o que significa que também criamos os devidos _getters_ e _setters_; e
3. Adicionamos as anotações para o GE de acordo com o que queremos para o Banco.

A implementação da entidade Telefone pode ser vista no _commit_ [bfbfc4a](https://github.com/brnocesar/alura/commit/bfbfc4aa3eef2cc46e287c0b4f73db379d666244).

Ao rodarmos o comando `$ pdoc orm:info`, podemos verificar o _status_ das entidades que existem no projeto. Se a classe Telefone e suas anotações para o GE foram implementadas corretamente, receberemos um _feedback_ positivo e nunhum erro será apresentado.

### 5.2. Relacionamento "Um para Muitos" (1:N)
Seguindo com nosso exemplo, temos que cada Aluno pode possuir vários Telefones, e portanto, cada Telefone deve pertencer a um Aluno.

Dessa forma é razoável pensar que devemos ter atributos em cada uma das classes para identificar essa relação. Ou seja, devemos ter um campo na classe Aluno capaz de indicar os telefones associados ao aluno, e na classe Telefone devemos ter um campo indicando a que aluno ele pertence.

#### 5.2.1. Classe Telefone
Continuando o raciocínio do tópico anterior, criamos um atributo chamado `$aluno` bem como seus métodos acessores, de forma padrão. O que muda aqui são:  
_(i)_ as anotações para o GE, que devem indicar o tipo de relacionamento (neste caso `ManyToOne`) e a classe a que este atributo se refere (seu _target_); e  
_(ii)_ a tipagem dos métodos acessores.

#### 5.2.1. Classe Aluno
O procedimento agora difere um pouco do que foi feito na classe Telefone. Devemos criar um atributo para os telefones do aluno e o faremos no plural por uma questão de semântica (e convenção também!), ficando então `$telefones`.  
As anotações devem indicar a relação inversa do que foi colocado na classe Telefone (neste caso `OneToMany`) e agora também devemos indicar qual atributo mapeia esta relação na classe Telefone.

Antes de escrevermos o _getter_ e o "_setter_" do atributo `$telefones` precisamos lembrar que ele vair receber mais de um telefone, portanto, devemos especificar que este atributo é uma coleção do Doctrine (na verdade não tenho certeza se a coleção precisa ser de uma biblioteca do Doctrine).  
Essa definição é feita no construtor da classe e aqui vamos usar o tipo de coleção `ArrayCollection`, que faz parte de uma biblioteca do Doctrine e se comporta de forma similar a um _array_, trazendo também alguns recursos bastante interessantes.  
Um desses recurso interessantes é o método `add()` que podemos usar no método que adiciona telefones para um aluno. Essa ação é feita pelo método `addTelefones()`, e note que o aluno é associado ao telefone no mesmo local.  
Finalizamos esta etapa escrevendo o método que recupera os telefones associados a um aluno, e note que a tipagem do retorno deve ser uma coleção.

A implementação do relacionamento entre as duas classes pode ser vista no _commit_ [769e412](https://github.com/brnocesar/alura/commit/769e412ca4280fcc2548a527ce37c1c7b7f0aa7a).

## 6. _Migrations_<a name='6'></a>
As _migrations_ servem para varsionamento do Banco de Dados.

### 6.1. Adicionando dependência
Neste projeto vamos utilizar o pacote de _migrations_ do Doctrine e para adicionar esta dependência ao projeto rodamos o comando abaixo na raiz:
```sh
$ composer require doctrine/migrations
```

Para um melhor entendimento é recomendável que você consulte a [documentação](https://www.doctrine-project.org/projects/doctrine-migrations/en/2.2/reference/introduction.html).

#### 6.1.1. Linha de comando
Na documentação é apresentado o comando para acessar a interface por linha de comando: `vendor/bin/doctrine-migrations`. E como feito no inicio deste guia, vamos adicionar um _alias_ para este comando.  

Os comandos mais utilizados (provavelmente) serão:
- `$ dmic  migrations:status`: apresenta o _status_ das _migrations_, onde são armazenadas e informações sobre a Base de Dados
- `$ dmic migrations:diff`: gera uma _migration_ comparando o Banco de Dados atual com a informação de mapeamento
- `$ `: 

### 6.2. Arquivo de configurações
Seguindo para o próximo capítulo da documentação nos é apresentado um arquivo de configuração que deve existir em nosso projeto (com intuito similar ao arquivo `cli-config.php`).  
Então criamos o arquivo `migrations.php` na raiz do projeto, colamos o conteúdo do modelo disponível na documentação e fazemos as devidas alterações (_commit_ [6d8f98e](https://github.com/brnocesar/alura/commit/6d8f98eb6c21c863c64debbb4df422c4d4446f36)).

### 6.3. Gerando _migrations_
Neste ponto do projeto ja temos uma Base de Dados que tem a tabela 'alunos'. Se rodarmos o comando abaixo:
```sh
$ dmic migrations:diff
```

será criado um arquivo de _migration_ na pasta `src/Migrations` com todas as informações relativas ao mapeamento da classe Telefone e às mudanças na classe Aluno (_commit_ [8f49127](https://github.com/brnocesar/alura/commit/8f4912773c24f8f5f62af09fa5283554da6b64e7)).  
Além disso foi criada a tabela 'doctrine_migration_versions' no Banco de Dados.

Note que este arquivo não possui nenhuma informação referente ao mapeamento inicial da classe Aluno.  
Como o objetivo é versionar nosso Banco de Dados e estamos em um estágio inicial no desenvolvimento do projeto (estamos em treinamento na verdade né, mas enfim), podemos deletar o arquivo do Banco de Dados e a _migration_ gerada a pouco, assim, podemos gerar uma migration com todas as informações de mapeamento das classes que temos em nosso projeto neste momento.

Após gerar a "verdadeira" _migration_ podemos executa-la com o comando abaixo, que na verdade executa todas que ainda não foram rodadas:
```sh
$ dmic migrations:migrate
```

A criação da _migration_ que mapeou todas as classes implementadas até agora e sua execução esta no _commit_ [06c16f](https://github.com/brnocesar/alura/commit/06c16fc73e40edb6c074475ee4f582b7979aa22d).

## 7. Atualizando o CRUD de Alunos<a name='7'></a>
### 7.1. `store()`
Os passos necessários para implementar a capacidade de associar telefones aos estudantes, são basicamente os mesmos do item 4.1.:
4. Instânciar um objeto
5. Monitorar este objeto com o GE
6. Realizar a persistência

A única diferença aqui é que devemos usar um estrutura de repetição para realizar esta ação com todos os telefones passados.

O passo 5 pode ser omitido se adicionarmos uma anotação `cascade` no atributo `$telefones` da classe Aluno. Dessa forma, sempre que indicarmos ao GE que uma entidade da classe Aluno deve ser monitorada (chamar o `persist()`) suas entidades "filhas" (da classe Telefone) também o serão, em cascata.

### 7.2. `index()` e `show()`
Por conveniência, criamos uma função para printar o nome do aluno, já que isso era feito em mais de um lugar com código muito parecido. Dessa forma, basta adicionar os telefones recuperados em apenas um lugar.

Com o intuito de facilitar a apresentação dos telefones, aplicamos a função `map()` na coleção de telefones que é recuperada. Isso vai mapear a coleção de objetos do tipo Telefone para um "contentor" do tipo _ArrayCollection_ (não tenho certeza se a palavra contentor é usada em PHP).  
Além disso, usamos _built in function_ `toArray()` para obtermos um simples _array_ com todos os números de telefone.

A implementação da atualização do CRUD foi feita no _commit_ [9e20c00](https://github.com/brnocesar/alura/commit/9e20c0007371ff208dc254c09589f48bf0de6ec7).

## 8. 'Curso' e "muitos para muitos"<a name='8'></a>
### 8.1. Criando a entidade e definindo o relacionamento
Se temos a classe Aluno, faz sentido termos uma classe Curso. Neste caso vamos ter a situação de que cada aluno pode frequentar mais de um curso e cada curso pode ter vários alunos. Este é o relacionamento do tipo _"ManyToMany"_.

O processo é praticamente idêntico ao descrito no item 5, diferindo nas anotações e outros pequenos detalhes. 

Quando temos uma relação bidirecional como essa devemos especificar qual dos lados é o _owner_ e qual é o _inversed_. Neste caso as intidades são independentes, portanto não há lado inverso e inverter os lados influencia apenas no nome da tabela. Por isso, tanto faz onde colocamos o atributo _mappedBy_ e o _inversedBy_.  
Para maiores detalhes sobre quando é importante definir os lados desse tipo de relacionamento acesse a [documentação](https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/unitofwork-associations.html#association-updates-owning-side-and-inverse-side).  
Também no que não há necessidade de usarmos o atributo _cascade_ pois necessáriamente quando formos tratar um relacionamento deste tipo o doctrine ja estará monitorando as entidades envolvidas.

Nos métodos para adicionar um aluno a curso (e vice-versa) devemos verificar se a relação já existe, para não cairmos num _loop_ infinito.

A implementação dessa entidade e definição de seu relacionamento com Aluno foi feita no _commit_ [20a9e22](https://github.com/brnocesar/alura/commit/20a9e2220b3ab148117ec96beab35fc40cf8c8cb).

### 8.2. CRUD de Cursos
Já foram escritos os CRUDS de duas entidades, portanto, _"só vai"_.

## 9. _Lazy Loading_ e DQL<a name='9'></a>
No _commit_ [43d705e](https://github.com/brnocesar/alura/commit/43d705e4c7ff47136a4ddb07c04a6acd05b50170) realizamos consultas pelos cursos de cada aluno cadastrado e no _commit_ [35a31e2](https://github.com/brnocesar/alura/commit/35a31e28399d16fabc12995927bb2847ac5b896e) avaliamos quantas _queries_ eram feitas no Banco.  
Verificamos que o Doctrine deixa para buscar os telefones e cursos de cada aluno somente quando os métodos `get...()``são chamados, isso significa que para cada aluno teremos mais duas _queries_. isso é chamado de busca preguiçosa ou _Lazy Loading_.

Uma forma de contornar esse problema é usando DQL (Doctrine Query Language), que dessa forma nos permite informar ao Doctrine que queremos recuperar o conjunto completo de uma entidade. No _commit_ [a9be242](https://github.com/brnocesar/alura/commit/a9be242cd44c59e0ecae773c4d2fd5fb621648a1) foi feita uma implementação desse tipo de consulta. 

Usando o `$entityManager` acessamos o método `createQuery()`. Note que a linguagem usado não é SQL, afinal não estamos selecionando colunas ou especificando a tabela a qual queremos consultar.  
No _commit_ [d32b860](https://github.com/brnocesar/alura/commit/d32b860a6fc60015e2de1186dbfa5ad76fde8f16) o arquivo `buscar-aluno.php` foi refatorado, tendo todas as consultas feitas através de DQL.

### 9.1. _Eager Loading_ e `JOIN`
Podemos definir que quando for feita uma consulta por uma entidade no Banco, outras entidades relacionadas a esta também sejam recuperadas em cascata. Isso é feito passando o valor `EAGER` para o parâmetro `fetch` na anotação da entidade.  Na maioria das situações não é

Se essa definição é feita no mapeado da entidade, essa busca em cascata vai ocorrer sempre. Se não for necessário que isso aconteça em todas as consultas da entidade, podemos especificar que entidades também devem ser buscadas através do `JOIN`.

No _commit_ [006d43b](https://github.com/brnocesar/alura/commit/006d43b86753761722e632efd266b46f2e0c332d) foram implementadas consultas _"eager joins"_.

## 10. Repositórios e _QueryBuilder_<a name='10'></a>
Em geral não é bom termos códigos de DQL (ou SQL) explicítos em nossas aplicações, principalmente por que isso pode tornar o código inintendível para pessoas que tenham muita familiaridade com estas linguagens. 

Voltando na <a href='#4.2'>Seção 4</a>, quando quisemos recuper registros do Banco utilizamos um repositório. Criamos um repositório para alunos através do GE e utilizamos todas as abstrações fornecidas por ele para acessar o Banco.

Podemos criar uma classe para servir como repositório de uma entidade específica e fazê-la herdar funcionalidades básicas de um repositório de entidades que nos permite utilizar essas abstrações. Essa funcionalidade em especial é o _QueryBuilder_, que é "construtor de _queries_" que permite escrever códigos que façam consultas de forma muito mais simples e legível, principalmente quando temos _queries_ dinâmicas.    
Para que o GE saiba que existe uma classe modelando o repositório de uma entidade, devemos indicar isso ao GE por meio das anotações.

Esta implementação foi feita nos _commits_ seguintes: em [dc4baf7](https://github.com/brnocesar/alura/commit/dc4baf727b66534e297d4605e9571f878e095302) foi criada a classe do "repositório para alunos", no [6787975](https://github.com/brnocesar/alura/commit/6787975a569152535fe46277c1297dad7f22be33) foram utilizadas as abstrações fornecidas pelo "construtor de _queries_" para realizar a consulta estática por todos os alunos trazendo as entidades relacionadas e no [140dad0](https://github.com/brnocesar/alura/commit/140dad0174dae7c06459bd7eba8c73216e9e4064) foi feita a implementação de uma consulta dinâmica.

## 11. Configurando o MySQL<a name='11'></a>
Até este ponto foi utilizado o SQLite como Banco de Dados, mas isso pode ser facilmente alterado para o SGDB de sua prefeR6encia. Aqui será apresentado o procedimento de configuração do MYSQL.

Após criar uma Base de Dados específica para esta aplicação, devemos alterar as informações de conexão que ficam na "Fábrica de Gerenciador de Entidades".  
Após isso basta criar o banco usando o comando usado na <a href='#4.2'>Seção 2</a>: `$ pdoc orm:schema-tool:create`.

A configuração deste Banco de Dados foi feita no _commit_ [c0b8f05](https://github.com/brnocesar/alura/commit/c0b8f0561f605f1f88c43184f5d0d66852f55a4e).