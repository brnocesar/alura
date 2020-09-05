<?php 

class Viagem
{
    public $origem;
    public $destino;
    private $data_ida;
    private $data_volta;
    public $classe;
    public $adultos;
    public $criancas;
    public $preco;

    public function __construct(
        $origem,
        $destino,
        $data_ida,
        $data_volta,
        $classe,
        $adultos,
        $criancas,
        $preco
    ) {
        $this->origem     = $origem;
        $this->destino    = $destino;
        $this->data_volta = $data_volta;
        $this->classe     = $classe;
        $this->adultos    = $adultos;
        $this->criancas   = $criancas;

        $this->setDataIda($data_ida)
            ->setDataVolta($data_volta)
            ->setPreco($preco);
    }

    private function validaData(string $data): string
    {
        // 2020-09-05
        if ( !preg_match("/^20[0-9]{2}\-[0-1][0-9]\-[0-3][0-9]$/", $data) ) {

            throw new Exception("Data inválida. Formato incorreto.");
        }

        $partes = explode("-", $data);
        $ano = $partes[0];
        $mes = $partes[1];
        $dia = $partes[2];
        if ( !checkdate($mes, $dia, $ano) ) {

            throw new Exception("Data inválida. Não existe.");
        }

        if ( strtotime($data) < strtotime(date("Y-m-d")) ) {

            throw new Exception("Data inválida. Anterior ao dia atual.");
        }

        return $data;
    }

    private function formataData($data)
    {
        return date("d/m/Y", strtotime($data));
    }

    public function showDataIda(): string
    {
        return $this->formataData($this->data_ida);
    }

    public function getDataIda(): string
    {
        return $this->data_ida;
    }

    private function setDataIda($data_ida): self
    {
        $this->data_ida = $this->validaData($data_ida);
        return $this;
    }

    public function showDataVolta(): string
    {
        return $this->formataData($this->data_ida);
    }

    public function getDataVolta(): string
    {
        return $this->data_ida;
    }

    private function setDataVolta($data_volta): self
    {
        $this->data_volta = $this->validaData($data_volta);

        if ( strtotime($this->data_volta) < strtotime($this->data_ida) ) {
            throw new Exception("Data inválida. Anterior ao dia da ida.");
        }
        return $this;
    }

    public function getPreco()
    {
        return $this->preco;
    }

    public function showPreco()
    {
        return "R$ " . number_format($this->preco, 2, ",", ".");
    }

    private function setPreco($preco)
    {
        // "2,00"; "200,00"; "200.000,00"; "20.000.000,00";
        if (preg_match("/^[0-9]{1,3}([.][0-9]{3})*[,][0-9]{2}$/", $preco)) {

            $this->preco = $this->convertePreco($preco);
            return $this;
        }
        throw new Exception("Data inválida. Formato incorreto.");
    }

    private function convertePreco($preco): float
    {
        return floatval(
            str_replace(",", ".", str_replace(".", "", $preco))
        );
    }
}