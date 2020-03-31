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
O arquivo `cli-config.php` deste projeto foi implementado no commit [f46e96b](https://github.com/brnocesar/alura/commit/f46e96b29059f3c5dc4dfbc625176595446b1b28).

### 2.1. Alias
Por conveniência, você pode adicionar um alias para este comando no seu terminal, isso pode ser feito adicionando o código abaixo ao final do arquivo `.bashrc` que fica na _home_.
```sh
$ cd
$ nano .bashrc

alias pdoc="php vendor/bin/doctrine"
```

### 2.2. Mais utilizados
Os comandos mais utilizados (provavelmente) serão:
- `pdoc orm:info`: procura por entidades mapeadas e indica se há algum problema
- `pdoc orm:mapping:describe Batatinha`: apresenta informações da classe mapeada passada como argumento (Batatinha)
- `pdoc orm:schema-tool:create`: processa o _schema_ e gera a Base de Dados

## 3. Gerenciador de Entidades
**Entidade** é termo usado pelo Doctrine para denominar objetos mapeados para o Banco.  
Portanto, **Gerenciador de Entidades** (GE) é a interface reponsável por mapear as instâncias de Classes no código orientado a objetos (OO) para as tabelas no Banco de Dados, de acordo com as _anotações_ existentes.

### 3.1. Fábrica
Para termos acesso ao GE precisamos primeiro criar uma "Fábrica de Gerenciadores de Entidades", que é onde definimos as configurações básicas que devem ser seguidas, como:
- onde devem ser buscadas as anotações que definem o mapeamento
- se o modo de desenvolvimento está ativo ou inativo
- quais as informações do Banco (driver, local, etc)

Neste projeto a fábrica foi criada em `src/Helper/EntityManagerFactory.php`, no commit [6e79b6f](https://github.com/brnocesar/alura/commit/6e79b6f4386b39a03977a2faaa35f92becc20507).

### 3.1. Entidade
As entidades do projeto ficam em `src/Entity`. Em um primeiro momento foi criada apenas uma entidade, chamada Aluno (commit [f46e96b](https://github.com/brnocesar/alura/commit/f46e96b29059f3c5dc4dfbc625176595446b1b28)). Este arquivo é a classe que representa os objetos do tipo Aluno e nela são declarados todos os seus atributos.  
Também é neste arquivo que são feitas as _anotações_ que são "lidas" pelo GE.

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

A implementação teste dessa funcionalidade foi feita no commit [f46e96b](https://github.com/brnocesar/alura/commit/f46e96b29059f3c5dc4dfbc625176595446b1b28).

### 4.2. `index()` e `show()`
Quando vamos buscar registros no Banco de Dados, precisamos fazer isso atráves de um "_Repositório_".  
Um repositório permite monitorar objetos de uma classe específica e recuperá-los. Os métodos fornecidos por padrão por um repositório são:
- `findAll()`: todos os objetos da classe
- `find($id)`: apenas um registro, de acordo com a chave primária passada como argumento
- `findBy(['atributo' => 'valor', ...])`: registros de que obedecem as condições passadas no _array_ associativo
- `findOneBy([])`: mesmo funcionamento do método anterior, mas retorna apenas o primeiro registro encontrado

A implementação teste dessa funcionalidade foi feita no commit [9356917](https://github.com/brnocesar/alura/commit/9356917efb31374207761cdc25ec50cafea9a8c5).

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

A implementação teste das últimas duas funcionalidades foi feita no commit [72383e7](https://github.com/brnocesar/alura/commit/72383e768354c2682883b6e8acd1af354031cbb3).
