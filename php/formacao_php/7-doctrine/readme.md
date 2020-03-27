## Linha de comando do Doctrine
Considerando que você e a pasta vendor estão na raiz do projeto, usando o comando abaixo é possível acessar a lista de comandos do Doctrine:
```
$ php vendor/bin/doctrine
```

Por conveniência, a primeira coisa a fazer é adicionar um alias para este comando no seu terminal. Para isso, adicione o código abaixo ao final do arquivo `.bashrc` que fica na _home_.
```
$ cd
$ nano .bashrc

alias pdoc="php vendor/bin/doctrine"
```

### Mais utilizados
Os comandos mais utilizados (provavelmente) serão:
- `pdoc orm:info`
- `pdoc orm:mapping:describe Class`
- `pdoc orm:schema-tool:create`