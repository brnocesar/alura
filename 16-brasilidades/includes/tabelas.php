<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active">
        <a role="tab" data-toggle="tab">Dados "armazenados"</a>
    </li>
</ul>
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="passagens">
        <div class="row table-text">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class='text-center'>CPF</th>
                        <th class='text-center'>CNPJ</th>
                        <th class='text-center'>Telefone</th>
                        <th class='text-center'>Email</th>
                        <th class='text-center'>CEP</th>
                        <th class='text-center'>Ida</th>
                        <th class='text-center'>Volta</th>
                        <th class='text-center'>Preço</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class='text-center'>
                            <?php echo $cliente->getCpf() ?? '' ?>
                        </td>
                        <td class='text-center'>
                            <?php echo $cliente->getCnpjCliente()->getCnpj() ?? '' ?>
                        </td>
                        <td class='text-center'>
                            <?php echo $cliente->getTelefone() ?? '' ?>
                        </td>
                        <td class='text-center'>
                            <?php echo $cliente->getEmail() ?? '' ?>
                        </td>
                        <td class='text-center'>
                            <?php echo $cliente->getCep() ?? '' ?>
                        </td>
                        <td class='text-center'>
                            <?php echo $viagem->getDataIda() ?? '' ?>
                        </td>
                        <td class='text-center'>
                            <?php echo $viagem->getDataVolta() ?? '' ?>
                        </td>
                        <td class='text-center'>
                            <?php echo $viagem->getPreco() ?? '' ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active">
        <a role="tab" data-toggle="tab">Dados formatados</a>
    </li>
</ul>
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="passagens">
        <div class="row table-text">
            <table class="table table-striped table-hover">
            <thead>
                    <tr>
                        <th class='text-center'>CPF</th>
                        <th class='text-center'>CNPJ</th>
                        <th class='text-center'>Telefone</th>
                        <th class='text-center'>Email</th>
                        <th class='text-center'>CEP</th>
                        <th class='text-center'>Ida</th>
                        <th class='text-center'>Volta</th>
                        <th class='text-center'>Preço</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class='text-center'>
                            <?php echo $cliente->getCpf() ?? '' ?>
                        </td>
                        <td class='text-center'>
                            <?php echo $cliente->getCnpjCliente()->showCnpj() ?? '' ?>
                        </td>
                        <td class='text-center'>
                            <?php echo $cliente->showTelefone() ?? '' ?>
                        </td>
                        <td class='text-center'>
                            <?php echo $cliente->getEmail() ?? '' ?>
                        </td>
                        <td class='text-center'>
                            <?php echo $cliente->showCep() ?? '' ?>
                        </td>
                        <td class='text-center'>
                            <?php echo $viagem->showDataIda() ?? '' ?>
                        </td>
                        <td class='text-center'>
                            <?php echo $viagem->showDataVolta() ?? '' ?>
                        </td>
                        <td class='text-center'>
                            <?php echo $viagem->showPreco() ?? '' ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>