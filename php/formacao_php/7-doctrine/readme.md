#Doctrine - ORM

O ORM (Object Relational Mapping) é o componente do Doctrine responsável por mapear instâncias de uma classe no código orientado a objetos para uma tabela/relacionamento no Banco de Dados.

O mapeamento é feito por um [**gerenciador de entidades**](https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/tutorials/getting-started.html#obtaining-the-entitymanager) (_entityManager_) por meio de [**anotações**](https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/basic-mapping.html) (_annotations_). Existem outros meios além das anotações, mas aqui usaremos apenas este.

## Linha de comando do Doctrine
Considerando que você e a pasta vendor estão na raiz do projeto, usando o comando abaixo é possível acessar a lista de comandos do Doctrine:
```sh
$ php vendor/bin/doctrine
```

Por conveniência, a primeira coisa a fazer é adicionar um alias para este comando no seu terminal. Para isso, adicione o código abaixo ao final do arquivo `.bashrc` que fica na _home_.
```sh
$ cd
$ nano .bashrc

alias pdoc="php vendor/bin/doctrine"
```

### Mais utilizados
Os comandos mais utilizados (provavelmente) serão:
- `pdoc orm:info`
- `pdoc orm:mapping:describe Class`
- `pdoc orm:schema-tool:create`