# Doctrine - ORM

O Doctrine ORM (Object Relational Mapping) é o componente do Doctrine responsável por mapear classes no código orientado a objetos para tabelas no Banco de Dados. Esse mapeamento é feito por um [**gerenciador de entidades**](https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/tutorials/getting-started.html#obtaining-the-entitymanager) (_entityManager_) por meio de [**anotações**](https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/basic-mapping.html) (_annotations_). Existem outros meios além das anotações, mas aqui usaremos apenas este.

1. [Testando o projeto](#1-testando-o-projeto)
2. [Instalação do Doctrine](#2-iniciando-o-projeto)
3. [Mapeando uma entidade](#3-mapeando-uma-entidade)
4. [Persistindo registros no Banco](#4-persistindo-registros-no-banco)
5. [Relacionamento _OneToMany_](#5-relacionamento-_onetomany_)
6. [_Migrations_](#6-migrations)
7. [Atualizando o CRUD de Alunos](#7-atualizando-o-crud-de-alunos)
8. [Relacionamento _ManyToMany_](#8-relacionamento-_manytomany_)
9. [_Lazy Loading_ e DQL](#9-_lazy-loading_-e-dql)
10. [Repositório](#10)
11. [Configurando o MySQL](#11)

## 1 Testando o projeto

Dentro da pasta do projeto, rode os seguintes comandos para deixar a plicação pronta para rodar:

- Instale as dependências:

```terminal
composer install
```

- Crie o Banco:

```terminal
php vendor/bin/doctrine orm:schema-tool:create
ou
php vendor/bin/doctrine migrations:migrate
```

Os _scripts_ estão disponíveis dentro da pasta `commands`, então certifique-se de navegar até a pasta ou inclui-la no _path_. Quando for possível/necessário passar argumentos, estes devem ser separados apenas por um espaço em branco e seguir a ordem descrita na tabela abaixo:

_Script_ / Recurso|Argumentos
-|-
alunos-create.php|nome do aluno (_string_, obrigatório)
alunos-search.php|nome de um aluno (_string_) ou ID do aluno (_integer_)
alunos-update.php|ID do aluno (_integer_, obrigatório), nome do aluno (_string_, obrigatório)
alunos-delete.php|ID do aluno (_integer_, obrigatório)
cursos-create.php|nome do curso (_string_, obrigatório)
matricular-aluno.php|ID do aluno (_integer_), ID do curso (_integer_)
relatorio-cursos-alunos.php| -

**ex**:

- listando todos os alunos, a partir da raiz do projeto:

```terminal
php commands/alunos-search.php
```

- atualizando o nome de um aluno, após navegar até a pasta `commands`:

```terminal
php alunos-update.php 15 "Novo Nome"
```

## 2 Iniciando o projeto

### 2.1 Instalando Doctrine

A primeira coisa a ser feita é adicionar o Doctrine como uma dependência do projeto, para isso rodamos o comando:

```terminal
composer require doctrine/orm
```

### 2.2 Fábrica de gerenciadores de entidades

Agora começamos a estruturar o projeto, adotando a conveção de que todo código relacionado a "regras de negócio" deve ficar dentro na pasta `src`. Ou seja, as classes modelos para nossas entidades, os arquivos de configuração para acesso ao Banco, a lógica e etc.

Dentro dessa pasta criamos outra chamada `Helper` e nela criamos o arquvivo com as informações necessárias para que o Doctrine se conecte ao Banco de Dados e seja capaz de gerenciar as entidades do nosso sistema. Dessa forma teremos o arquivo `src/Helper/EntityManagerFactory.php` mapeando e gerenciando as nossas entidades para o Banco (_commit_ [6e79b6f](https://github.com/brnocesar/alura/commit/6e79b6f4386b39a03977a2faaa35f92becc20507)).

O arquivo criado na verdade é uma "fabrica" (_factory_) que retorna um "gerenciador de entidades" (GE). Na _factory_ definimos as configurações básicas que devem ser seguidas, como:

- como será feito o mapeamento das entidades para o Banco, que neste caso será por meio de _annotations_, e onde devem ser buscadas essas anotações que definem o mapeamento;
- se o modo de desenvolvimento está ativo ou inativo; e
- as informações necessárias para acessar o Banco (driver, local, etc)

### 2.3 `autoload`

Vamos usar o `autoload` do composer no projeto, então no arquivo `composer.json` basta especificar  o local (em relação ao projeto) que corresponde ao `namespace` raiz.

Agora temos apenas um arquivo que precisamos dar _require_ em todos os _scripts_ desse projeto, o `vendor/autoload.php`:

```php
require_once __DIR__ . '/../vendor/autoload.php';
```

## 3 Mapeando uma entidade

Uma entidade para o Doctrine é uma classe em PHP que pode ser mapeada para uma tabela no Banco de Dados, sendo que os atributos dessa classe vão representar as colunas e/ou relacionamentos da tabela.

Vamos criar uma pasta `Entity` dentro de `src` e começar a definir um modelo para nossa primeira entidade, `Aluno`. Então no arquivo `src/Entity/Aluno.php` começamos definindo os atributos básicos de um aluno, seu nível de encapsulamento e os métodos acessores (_commit_ [f46e96b](https://github.com/brnocesar/alura/commit/f46e96b29059f3c5dc4dfbc625176595446b1b28)).

Após isso podemos especificar **o que e como** deve ser mapeado, através de _anotations_. Podemos definir o nome da tabela que vai representar essa entidade, seu identificador único, tipos de dados e valor padrão para cada um dos atributos (colunas) e por ai vai.

### 3.1 Linha de comando

O Doctrine possui uma interface de linha de comando que pode ser acessada em `vendor/bin/doctrine`. A pasta `vendor` é criada na raiz do projeto, portanto, basta rodar o comando abaixo para ter acesso a lista de comandos do Doctrine:

```terminal
php vendor/bin/doctrine
```

Provavelmente na primeira vez que o comando acima for rodado, você receberá um aviso de falta um arquivo de configuração. Se isso acontecer basta seguir as instruções apresentadas no terminal.  
O arquivo `cli-config.php` deste projeto foi implementado no _commit_ [f46e96b](https://github.com/brnocesar/alura/commit/f46e96b29059f3c5dc4dfbc625176595446b1b28).

Por conveniência, você pode adicionar um alias para este comando no seu terminal, isso pode ser feito (no Linux) adicionando o código abaixo ao final do arquivo `.bashrc` que fica na _home_.

```terminal
alias pdoc="php vendor/bin/doctrine"
```

#### 3.1.1 Comandos mais utilizados

Os comandos mais utilizados (provavelmente) serão:

- `$ pdoc orm:info`: procura por entidades mapeadas e indica se há algum problema
- `$ pdoc orm:mapping:describe Batatinha`: apresenta informações da classe mapeada passada como argumento, no caso, Batatinha
- `$ pdoc orm:schema-tool:create`: processa o _schema_ e gera a Base de Dados

## 4 Persistindo registros no Banco

Vamos criar um CRUD para a entidade `Aluno`. Os _scripts_ usados para executar as implementações do projeto estão na pasta `commands/`.

### 4.1 `create()`

Para criar um registro no Banco precisamos seguir alguns passos simples:

1. Instânciar um objeto
2. Monitorar este objeto com o GE
3. Efetivar a persistência

A implementação teste dessa funcionalidade foi feita no arquivo `commands/alunos-create.php` e está registrada no _commit_ [f46e96b](https://github.com/brnocesar/alura/commit/f46e96b29059f3c5dc4dfbc625176595446b1b28).

### 4.2 `read()`

Quando vamos buscar registros no Banco de Dados, precisamos fazer isso atráves de um "Repositório".

Da mesma forma que com um GE nós enviamos dados da aplicação para o Banco, com um repositório permite monitorar objetos de uma entidade específica e recuperá-los do Banco para nossa aplicação.

O repositório pode ser obtido do GE através do método `getRepository()` e já fornece alguns métodos por padrão:

- `findAll()`: todos os objetos da classe
- `find($id)`: apenas um registro, de acordo com a chave primária passada como argumento
- `findBy(['atributo' => 'valor'])`: registros de que obedecem as condições passadas no _array_ associativo
- `findOneBy([])`: mesmo funcionamento do método anterior, mas retorna apenas o primeiro registro encontrado

A implementação teste dessa funcionalidade feita no _commit_ [9356917](https://github.com/brnocesar/alura/commit/9356917efb31374207761cdc25ec50cafea9a8c5).

### 4.3 `update()`

Para atualizar um registro devemos primeiro recuperá-lo do Banco e isso já foi abordado no tópico anterior. Naquele momento foi utilizado um repositório para classe Aluno, mas também é possível recuperar um único registro do Banco sem utilizar este artifício.

É possível usar o método `find()` no GE se queremos **recuperar apenas um registro** do Banco, basta passar dois parâmetros relativos ao objeto que queremos, a classe e a chave primária:

```php
$entityManager->find(Aluno::class, $id)
```

Uma vez que temos o objeto que será atualizado basta utilizar os devidos métodos _setters_ para alterar seus atributos e após finalizada esta etapa executar o método `flush()` do GE.

Note que não é necessário usar o método `persist()` para indicar ao GE que este objeto deve ser monitorado, lembre-se que foi necessário recuperar este registro do Banco, portanto, o objeto já está sendo monitorado pelo GE.

### 4.4 `delete()`

Da mesma forma que no tópico anterior, antes de realizar alguma ação em um objeto precisamos recuperar seu registro do Banco. Mas perceba que se formos pelo mesmo caminho do tópico anterior, ao final teremos realizado duas _queries_ no Banco: um SELECT e um REMOVE.

Para a funcionalidade de atualização faz todo sentido realizar um SELECT, pois é necessário ter acesso aos atributos deste registro para modificá-los. Mas na situação em que já sabemos (pela chave primária) qual registro será deletado, não há necessidade alguma de ter acesso a seus atributos. Portanto, podemos apenas obter uma referência a este registro e realizar a devida ação. Dessa forma acabamos "economizando" uma _query_ no Banco.

Basta trocar o método `find()` por `getReference()`, que recebe os mesmos parâmetros que `find()`. Em seguida usamos o método `remove()`, passando a referência ao registro, e por fim mandamos executar a ação no Banco.

A implementação (inicial) das últimas duas funcionalidades foi feita no _commit_ [72383e7](https://github.com/brnocesar/alura/commit/72383e768354c2682883b6e8acd1af354031cbb3).

## 5 Relacionamento _OneToMany_

### 5.1 Entidade `Telefone`

Nesta seção vamos criar a entidade **Telefone** e definir que cada **aluno** pode ter mais de um número de telefone associado a si.

O procedimento para criar esta nova entidade é exatamente igual ao que foi feito para **Aluno**:

1. Criamos o arquivo da classe (`Telefone.php`) no diretório `src/Entity`;
2. Definimos os atributos e os métodos acessores; e
3. Adicionamos as anotações para o GE de acordo com o que queremos para o Banco.

A implementação da entidade Telefone pode ser vista no _commit_ [bfbfc4a](https://github.com/brnocesar/alura/commit/bfbfc4aa3eef2cc46e287c0b4f73db379d666244).

Ao rodarmos o comando `$ pdoc orm:info`, podemos verificar o _status_ das entidades que existem no projeto. Se a classe Telefone e suas anotações para o GE foram implementadas corretamente, receberemos um _feedback_ positivo e nunhum erro será apresentado.

### 5.2 Definindo o relacionamento

Seguindo com nosso exemplo, temos que cada Aluno pode possuir vários Telefones, e cada Telefone deve pertencer a um Aluno.

Dessa forma é razoável pensar que devemos ter atributos em cada uma das classes para identificar essa relação. Ou seja, devemos ter um campo na classe Aluno capaz de indicar os telefones associados ao aluno, e na classe Telefone devemos ter um campo indicando a que aluno ele pertence.

#### 5.2.1 Classe Telefone

Criamos um atributo chamado `$aluno` bem como seus métodos acessores, da forma padrão. O que muda aqui são:  
_(i)_ as anotações para o GE, que devem indicar o tipo de relacionamento (neste caso `ManyToOne`) e a classe a que este atributo se refere (seu _target_); e  
_(ii)_ a tipagem dos métodos acessores.

#### 5.2.2 Classe Aluno

O procedimento agora difere um pouco do que foi feito na classe Telefone. Devemos criar um atributo para os telefones do aluno e o faremos no plural por uma questão de semântica (e convenção também!), ficando então `$telefones`.

As anotações devem indicar a relação inversa do que foi colocado na classe Telefone (neste caso `OneToMany`) e agora também devemos indicar qual atributo mapeia esta relação na classe Telefone.

Antes de escrevermos os métodos acessores (_getter_ e _setter_) do atributo `$telefones` precisamos lembrar que ele pode receber mais de um telefone, portanto, devemos especificar que este atributo é uma coleção do Doctrine (na verdade acho que pode ser "qualquer" coleção, não necessáriamente uma da biblioteca do Doctrine).  
Essa definição é feita no construtor da classe e aqui vamos usar o tipo de coleção `ArrayCollection`, que faz parte de uma biblioteca do Doctrine e se comporta de forma similar a um _array_, trazendo também alguns recursos bastante interessantes.  
Um desses recursos interessantes é o método `add()` que podemos usar no método que adiciona telefones para um aluno. Essa ação é feita pelo método `addTelefones()`, e note que podemos associar o aluno ao telefone no mesmo local.  
Finalizamos esta etapa escrevendo o método que recupera os telefones associados a um aluno, e note que a tipagem do retorno deve ser uma coleção.

A implementação do relacionamento entre as duas classes pode ser vista no _commit_ [769e412](https://github.com/brnocesar/alura/commit/769e412ca4280fcc2548a527ce37c1c7b7f0aa7a).

## 6 Migrations

As _migrations_ representam o varsionamento do Banco de Dados.

### 6.1 Adicionando o pacote de _migrations_

Neste projeto vamos utilizar o pacote de _migrations_ do Doctrine e para adicionar esta dependência ao projeto rodamos o comando abaixo na raiz:

```terminal
composer require doctrine/migrations
```

Para um melhor entendimento é recomendável que você consulte a [documentação](https://www.doctrine-project.org/projects/doctrine-migrations/en/2.2/reference/introduction.html).

#### 6.1.1 Linha de comando

Na documentação é apresentado o comando para acessar a interface por linha de comando: `vendor/bin/doctrine-migrations`. E como feito no inicio deste guia, é uma boa ideia adicionar um _alias_ para este comando (no meu caso usei `dmic`).

Comandos mais utilizados (provavelmente) serão:

- `dmic  migrations:status`: apresenta o _status_ das _migrations_, onde são armazenadas e informações sobre a Base de Dados
- `dmic migrations:diff`: gera uma _migration_ comparando o Banco de Dados atual com a informação de mapeamento
- `dmic migrations:migrate`: executa todos os arquivos de _migrations_

### 6.2. Arquivo de configurações

Seguindo para o próximo capítulo da documentação nos é apresentado um arquivo de configuração que deve existir no projeto (com intuito similar ao arquivo `cli-config.php`).  
Então criamos o arquivo `migrations.php` na raiz do projeto, colamos o conteúdo do modelo disponível na documentação e fazemos as devidas alterações (_commit_ [6d8f98e](https://github.com/brnocesar/alura/commit/6d8f98eb6c21c863c64debbb4df422c4d4446f36)).

### 6.3 Gerando _migrations_

Neste ponto do projeto já temos uma Base de Dados que tem a tabela 'alunos'. Se rodarmos o comando abaixo:

```terminal
dmic migrations:diff
```

será criado um arquivo de _migration_ na pasta `src/Migrations` de nome `Version<timestamp>.php` e com todas as informações relativas ao mapeamento da classe Telefone e às mudanças na classe Aluno (_commit_ [8f49127](https://github.com/brnocesar/alura/commit/8f4912773c24f8f5f62af09fa5283554da6b64e7)). Além disso foi criada a tabela 'doctrine_migration_versions' no Banco de Dados.

Note que este arquivo não possui nenhuma informação referente ao mapeamento inicial da classe Aluno. Como o objetivo é versionar nosso Banco de Dados e o projeto está em um estágio inicial de desenvolvimento, podemos deletar o arquivo do Banco de Dados e a _migration_ gerada a pouco, assim, podemos gerar uma migration com todas as informações de mapeamento das classes que temos em nosso projeto neste momento.

Após gerar a "verdadeira" _migration_ podemos executa-la com o comando abaixo, que na verdade executa todas que ainda não foram rodadas:

```terminal
dmic migrations:migrate
```

As alterações relativas a criação da _migration_ que mapeou todas as classes implementadas até o momento e sua execução esta no _commit_ [06c16f](https://github.com/brnocesar/alura/commit/06c16fc73e40edb6c074475ee4f582b7979aa22d).

Se o objetivo for rodar o `up()` ou `down()` de uma _migration_ específica podemos usar um dos comandos abaixo:

```terminal
dmic migrations:execute --up <timestamp>
dmic migrations:execute --down <timestamp>
```

## 7 Atualizando o CRUD de Alunos

### 7.1 `store()`

Os passos necessários para implementar a capacidade de associar telefones aos estudantes, são basicamente os mesmos do item 4.1.:
4. Instânciar um objeto
5. Monitorar este objeto com o GE
6. Realizar a persistência

A única diferença aqui é que devemos usar uma estrutura de repetição para realizar esta ação com todos os telefones passados.

O passo 5 pode ser omitido se adicionarmos a anotação `cascade` com o valor `"persist"` no atributo `$telefones` da classe Aluno. Dessa forma, sempre que indicarmos ao GE que uma entidade da classe Aluno deve ser monitorada (chamar o `persist()`) suas entidades "filhas" (da classe Telefone) também o serão, em cascata.

### 7.2 `index()` e `show()`

Por conveniência, criamos uma função para printar o nome do aluno, já que isso era feito em mais de um lugar com código muito parecido. Dessa forma, basta adicionar os telefones recuperados em apenas um lugar.

Com o intuito de facilitar a apresentação dos telefones, aplicamos a função `map()` na coleção de telefones que é recuperada. Isso vai mapear a coleção de objetos do tipo Telefone para um "contentor" do tipo _ArrayCollection_ (não tenho certeza se a palavra contentor é usada em PHP).  
Além disso, usamos _built in function_ `toArray()` para obtermos um simples _array_ com todos os números de telefone.

A implementação da atualização do CRUD foi feita no _commit_ [9e20c00](https://github.com/brnocesar/alura/commit/9e20c0007371ff208dc254c09589f48bf0de6ec7).

## 8 Relacionamento _ManyToMany_

### 8.1 Entidade `Curso` e definindo o relacionamento

Se temos a classe Aluno, faz sentido termos uma classe Curso. Neste caso vamos ter a situação de que cada aluno pode frequentar mais de um curso e cada curso pode ter vários alunos. Este é o relacionamento do tipo _ManyToMany_.

O processo é praticamente idêntico ao descrito no item 5, diferindo nas anotações e outros pequenos detalhes.

Quando temos uma relação bidirecional devemos especificar qual dos lados é o _owner_ e qual é o _inversed_. Neste caso as intidades são independentes, portanto não há lado inverso e inverter os lados influencia apenas no nome da tabela. Por isso, tanto faz onde colocamos o atributo _mappedBy_ e o _inversedBy_.

Para maiores detalhes sobre quando é importante definir os lados desse tipo de relacionamento acesse a [documentação](https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/unitofwork-associations.html#association-updates-owning-side-and-inverse-side).

Também não há necessidade de usarmos o atributo _cascade_ pois necessáriamente quando formos tratar um relacionamento deste tipo o doctrine ja estará monitorando as entidades envolvidas.

Nos métodos para adicionar um aluno a um curso (e vice-versa) devemos verificar se a relação já existe, para não cairmos num _loop_ infinito.

A implementação dessa entidade e definição de seu relacionamento com Aluno foi feita no _commit_ [20a9e22](https://github.com/brnocesar/alura/commit/20a9e2220b3ab148117ec96beab35fc40cf8c8cb).

### 8.2 CRUD de Cursos

Já foram escritos os CRUDS de duas entidades, então... _"VAI FIILÃÃÃÃÃÃOOO"_.

## 9 _Lazy Loading_ e DQL

No _commit_ [43d705e](https://github.com/brnocesar/alura/commit/43d705e4c7ff47136a4ddb07c04a6acd05b50170) implementamos a listagem de todos alunos apresentando os cursos em que cada um deles está cadastrado. E no [35a31e2](https://github.com/brnocesar/alura/commit/35a31e28399d16fabc12995927bb2847ac5b896e), avaliamos quantas _queries_ estavam sendo feitas no Banco usando uma `DebugStack` para armazenar o _log_ de _queries_ realizadas. Lembrando que é possível usar a "pilha de _debug_" pois a _factory_ de GE foi definida com o _setup_ para desenvolvimento.

Verificamos que o Doctrine deixa para buscar os telefones e cursos de cada aluno somente quando os métodos acessores (_getters_) são chamados, isso significa que para cada aluno teremos mais duas _queries_. Isso é chamado de busca preguiçosa ou _Lazy Loading_.

Uma forma de contornar esse problema é usando DQL (Doctrine Query Language), assim podemos informar ao Doctrine que queremos recuperar o conjunto completo de uma entidade e não precisamos usar o _repository_ para fazer a consulta. No _commit_ [a9be242](https://github.com/brnocesar/alura/commit/a9be242cd44c59e0ecae773c4d2fd5fb621648a1) foi feita uma implementação desse tipo de consulta.

Usando o método `createQuery()` no `$entityManager` para realizar a _query_ com o DQL que escrevemos e depois acessamos o resultado da consulta com `getResult()`. Note que a linguagem usado não é SQL, afinal não estamos selecionando colunas ou especificando a tabela que queremos consultar.  
No _commit_ [d32b860](https://github.com/brnocesar/alura/commit/d32b860a6fc60015e2de1186dbfa5ad76fde8f16) o arquivo `buscar-aluno.php` (agora `aluno-search.php`) foi refatorado, tendo todas as consultas feitas através de DQL.

### 9.1 _Eager Loading_ e `JOIN`

Podemos definir que quando for feita uma consulta por uma entidade no Banco, outras entidades relacionadas a esta também sejam recuperadas em cascata. Isso é feito passando o valor `EAGER` para o parâmetro `fetch` na anotação da entidade.

Se essa definição é feita no mapeamento da entidade, a busca em cascata vai ocorrer sempre. Mas nem sempre usar o _eager loading_ será a melhor opção se estivermos pensando em performance. Nesse caso podemos especificar que entidades também devem ser buscadas através do `JOIN` na DQL, conforme for necessário.

No _commit_ [006d43b](https://github.com/brnocesar/alura/commit/006d43b86753761722e632efd266b46f2e0c332d) foram implementadas consultas _"eager joins"_.

## 10 Repositórios e _QueryBuilder_

Em geral não é bom termos códigos de DQL (ou SQL) explicítos em nossas aplicações, um motivo simples é que isso pode tornar o código mais difícil de entender por pessoas que tenham pouca familiaridade com essas linguagens/sintaxe.

Voltando na [Seção 4](#4-persistindo-registros-no-banco), quando quisemos recuper registros do Banco utilizamos um repositório. Criamos um repositório para alunos através do GE e utilizamos todas as abstrações fornecidas por ele para acessar o Banco.

Também podemos criar uma classe para servir como repositório de uma entidade específica e fazê-la herdar funcionalidades básicas de um repositório de entidades, o que nos permite utilizar essas abstrações para acesso ao Banco. Essa funcionalidade em especial é o _QueryBuilder_, que é um "construtor de _queries_" que permite escrever códigos que façam consultas de forma muito mais simples e legível, principalmente quando temos _queries_ dinâmicas.

Para que o GE saiba que existe uma classe modelando o repositório de uma entidade devemos indicar isso ao GE por meio das anotações. Basta adicionar o parâmetro `repositoryClass` com o nome completo da classe (repositório) como valor.

Esta implementação foi feita nos seguintes _commits_: em [dc4baf7](https://github.com/brnocesar/alura/commit/dc4baf727b66534e297d4605e9571f878e095302) foi criada a classe do "repositório para alunos"; no [6787975](https://github.com/brnocesar/alura/commit/6787975a569152535fe46277c1297dad7f22be33) foram utilizadas as abstrações fornecidas pelo "construtor de _queries_" para realizar a consulta estática por todos os alunos trazendo as entidades relacionadas; e no [140dad0](https://github.com/brnocesar/alura/commit/140dad0174dae7c06459bd7eba8c73216e9e4064) foi feita a implementação de uma consulta dinâmica.

## 11 Configurando o MySQL

Até este ponto foi utilizado o SQLite como Banco de Dados, mas ele pode ser facilmente alterado para o SGDB de sua preferência, como por exemplo o MySQL.

Devemos criar uma Base de Dados (no MySQL) específica para a aplicação e alterar as informações de conexão que ficam na _factory_ de GEs. Após isso, é possível seguir por duas abordagens: (i) criar o banco diretamente a partir do mapeando das entidades; ou (ii) criar as _migrations_ e executá-las.

A mudança de SQLite para MySQL foi feita no _commit_ [c0b8f05](https://github.com/brnocesar/alura/commit/c0b8f0561f605f1f88c43184f5d0d66852f55a4e).
