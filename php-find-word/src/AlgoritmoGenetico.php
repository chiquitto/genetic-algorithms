<?php


namespace Chiquitto\FindWord;


class AlgoritmoGenetico
{
    public static $maxTamPopulacao;
    public static $tamCromossomo;
    public static $maxGeracoes;
    /**
     * @var Populacao
     */
    private $populacao;
    /**
     * @var Populacao
     */
    private $novaPopulacao;
    /**
     * @var int
     */
    private $geracao;

    public function run($entrada)
    {
        static::$tamCromossomo = strlen($entrada);
        $this->geracao = 0;

        $this->dumpInicio($entrada);

        $this->populacao = Populacao::inicializar();
        $this->populacao->avaliar($entrada);

        while ($this->criterioParada()) {
            $this->novaPopulacao = new Populacao();
            $this->crossover();
            $this->novaPopulacao->mutacao();
            $this->novaPopulacao->avaliar($entrada);

            $this->populacao = $this->novaPopulacao;
            $this->dump();

            $this->geracao++;
        };

    }

    private function criterioParada()
    {
        return ($this->geracao < static::$maxGeracoes)
            && ($this->populacao->melhorIndividuo()->getAptidao() < (static::$tamCromossomo + 1));
    }

    private function dumpInicio($entrada)
    {
        echo "Iniciando ...\n";
        echo "Missão: Encontrar a string informada pelo usuario.\n";
        echo "String: {$entrada}\n";
        echo "Comprimento do problema: ", static::$tamCromossomo, "\n";
        echo "Tamanho do problema: ", pow(static::$tamCromossomo, strlen(Util::letras())), "\n";
        echo "\n\n";
    }

    private function dump()
    {
        printf("# %4d: %s (Aptidao %3d/%3d)\n",
            $this->geracao,
            $this->populacao->melhorIndividuo()->getCromossomo(),
            $this->populacao->melhorIndividuo()->getAptidao(),
            (static::$tamCromossomo + 1)
        );
    }

    private function crossover()
    {
        while ($this->novaPopulacao->calcularTamanho() < static::$maxTamPopulacao) {
            $res = $this->crossoverIndividuos();
            $this->novaPopulacao->addIndividuo($res[0]);
            $this->novaPopulacao->addIndividuo($res[1]);
        }
    }

    private function crossoverIndividuos()
    {
        $pai = $this->populacao->roletaViciada();
        $mae = $this->populacao->roletaViciada();

        $string1 = $pai->getCromossomo();
        $string2 = $mae->getCromossomo();

        $pontoCorte = mt_rand(0, static::$tamCromossomo);

        $crossover1 = substr($string1, 0, $pontoCorte) . substr($string2, $pontoCorte);
        $crossover2 = substr($string2, 0, $pontoCorte) . substr($string1, $pontoCorte);

        return [new Individuo($crossover1), new Individuo($crossover2)];
    }
}