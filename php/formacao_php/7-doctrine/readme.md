#Doctrine - ORM

O ORM (Object Relational Mapping) é o componente do Doctrine responsável por mapear instâncias de uma classe no código orientado a objetos para uma tabela/relacionamento no Banco de Dados.

O mapeamento é feito por um [**gerenciador de entidades**](https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/tutorials/getting-started.html#obtaining-the-entitymanager) (_entityManager_) por meio de [**anotações**](https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/basic-mapping.html) (_annotations_). Existem outros meios além das anotações, mas aqui usaremos apenas este.

## 1. Instalação do Doctrine
Para adicionar o Doctrine como uma dependência ao seu projeto...

## 2. Linha de comando
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

### 2.2. Comandos mais utilizados
Os comandos mais utilizados (provavelmente) serão:
- `$ pdoc orm:info`: procura por entidades mapeadas e indica se há algum problema
- `$ pdoc orm:mapping:describe Batatinha`: apresenta informações da classe mapeada passada como argumento (Batatinha)
- `$ pdoc orm:schema-tool:create`: processa o _schema_ e gera a Base de Dados

## 3. Gerenciador de Entidades
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

## 4. Persistindo registros no Banco (CRUD)
Os arquivos teste usados para ilustrar os conceitos discutidos nesta seção podem ser encontrados em `commands/`.

### 4.1. `store()`
Para criar um registro no Banco precisamos seguir alguns passos simples:
1. Instânciar um objeto
2. Monitorar este objeto com o GE
3. Realizar a persistência

A implementação teste dessa funcionalidade foi feita no _commit_ [f46e96b](https://github.com/brnocesar/alura/commit/f46e96b29059f3c5dc4dfbc625176595446b1b28).

### 4.2. `index()` e `show()`
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

## 5. Relacionamentos
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

## 6. _Migrations_
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

Após gerar a "verdadeira" _migration_ podemos executa-la com o comando abaixo, que na verdade executa todas que ainda não foram rodadas (ou não, executa tudão):
```sh
$ dmic migrations:migrate
```

A criação da _migration_ que mapeou todas as classes implementadas até agora e sua execução esta no _commit_ [06c16f](https://github.com/brnocesar/alura/commit/06c16fc73e40edb6c074475ee4f582b7979aa22d).
