<?php

/**
 * Address Abbreviation Helper
 *
 * @category  Helper
 * @package   Helper
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Helper_AddressAbbreviation extends Mage_Core_Helper_Abstract
{
    protected $street;
    protected $number;
    protected $complement;
    protected $abbreviationList;
    protected $abbreviationSize;
    protected $abbreviationLimit = 40;
    protected $abbreviationStreetPercentage = 90;
    protected $abbreviationComplementPercentage = 10;

    /**
     * @param $street
     * @return $this
     */
    public function setStreet($street)
    {
        $this->street = preg_replace('/[^a-zA-Z0-9 ]/', '', $street);

        return $this;
    }

    /**
     * @param $number
     * @return $this
     */
    public function setNumber($number)
    {
        $this->number = preg_replace("/[^0-9]/", "", $number);

        return $this;
    }

    /**
     * @param $complement
     * @return $this
     */
    public function setComplement($complement)
    {
        $this->complement = preg_replace('/[^a-zA-Z0-9 ]/', '', $complement);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return mixed
     */
    public function getComplement()
    {
        return $this->complement;
    }

    /**
     * @return $this
     */
    protected function setAbbreviation()
    {
        $this->abbreviationList = array_merge($this->getAbbreviationAddress(), $this->getAbbreviationOthers());

        return $this;
    }

    /**
     * @return array
     */
    protected function getAbbreviationAddress()
    {
        $address = array(
            'ACESSO' => 'ACS',
            'ADRO' => 'AD',
            'AEROPORTO' => 'AER',
            'ALAMEDA' => 'AL',
            'ALTO' => 'AT',
            'ANDAR' => 'AND',
            'ANEXO' => 'ANX',
            'APARTAMENTO' => 'APT',
            'ATALHO' => 'ATL',
            'ATERRO' => 'ATER',
            'AUTODROMO' => 'ATD',
            'AVENIDA' => 'AV',
            'AZINHAGA' => 'AZ',
            'BAIA' => 'BAIA',
            'BAIRRO' => 'BR',
            'BAIXA' => 'BX',
            'BALNEARIO' => 'BAL',
            'BECO' => 'BC',
            'BELVEDERE' => 'BLV',
            'BLOCO' => 'BLC',
            'BOSQUE' => 'BQ',
            'BOULEVARD' => 'BV',
            'CAIS' => 'C',
            'CAIXA POSTAL' => 'CP',
            'CALCADA' => 'CC',
            'CALCADINHA' => 'CCNH',
            'CAMINHO' => 'CAM',
            'CAMPO' => 'CPO',
            'CANAL' => 'CAN',
            'CARTODROMO' => 'CTD',
            'CASA' => 'CSA',
            'CASAL' => 'CSL',
            'CAVE' => 'CV',
            'CHACARA' => 'CH',
            'CHAPADAO' => 'CHP',
            'CIDADE' => 'CD',
            'COBERTURA' => 'COB',
            'COLONIA' => 'COL',
            'CONDOMINIO' => 'COND',
            'CONJUNTO' => 'CNJ',
            'CORREDOR' => 'COR',
            'CORREGO' => 'CRG',
            'DESCIDA' => 'DSC',
            'DESVIO' => 'DSV',
            'DIREITO' => 'DTO',
            'DISTRITO' => 'DT',
            'EDIFICIO' => 'EDF',
            'ENTRADA' => 'ENT',
            'ENTREPOSTO' => 'ETP',
            'ENTRONCAMENTO' => 'ENT',
            'ESCADARIA' => 'ESD',
            'ESCADAS' => 'ESC',
            'ESCADINHA' => 'ESC',
            'ESCADINHAS' => 'ESCNH',
            'ESPLANADA' => 'ESP',
            'ESQUERDO' => 'ESQ',
            'ESTACAO' => 'EST',
            'ESTADIO' => 'ETD',
            'ESTANCIA' => 'ETN',
            'ESTRADA' => 'ESTR',
            'FAVELA' => 'FAV',
            'FAZENDA' => 'FAZ',
            'FEIRA' => 'FRA',
            'FERROVIA' => 'FER',
            'FONTE' => 'FNT',
            'FORTALEZA' => 'FZA',
            'FORTE' => 'FTE',
            'FREGUESIA' => 'FRG',
            'FRENTE' => 'FTE',
            'FUNDOS' => 'FDS',
            'GALERIA' => 'GAL',
            'GALPAO' => 'GLP',
            'GRANJA' => 'GR',
            'GRUPO' => 'GRP',
            'HABITACAO' => 'HAB',
            'HIPODROMO' => 'HPD',
            'ILHA' => 'IA',
            'JARDIM' => 'JRD',
            'JUNTO' => 'JTO',
            'LADEIRA' => 'LAD',
            'LAGO' => 'LAG',
            'LAGOA' => 'LGA',
            'LARGO' => 'LG',
            'LIMITE' => 'LIM',
            'LINHA DE TRANSMISSAO' => 'LINHA',
            'LOJA' => 'LOJ',
            'LOJAS' => 'LJS',
            'LOTE' => 'LTE',
            'LOTEAMENTO' => 'LOTEAM',
            'MANGUE' => 'MANG',
            'MARGEM' => 'MGM',
            'MERCADO' => 'MER',
            'MONTE' => 'MT',
            'MORRO' => 'MRO',
            'PARADA' => 'PDA',
            'PARQUE' => 'PQ',
            'PARTE' => 'PTE',
            'PASSAGEM' => 'PAS',
            'PASSEIO' => 'PSO',
            'PATIO' => 'PTO',
            'PLANALTO' => 'PL',
            'PLATAFORMA' => 'PLT',
            'PONTE' => 'PTE',
            'PORTAO' => 'PTO',
            'PORTARIA' => 'PTR',
            'PORTO' => 'PRT',
            'POSTO' => 'POS',
            'PRACA' => 'PC',
            'PRACETA' => 'PCT',
            'PRAIA' => 'PR',
            'PROLONGAMENTO' => 'PROLNG',
            'PROXIMO' => 'PRX',
            'QUADRA' => 'QDR',
            'QUILOMETRO' => 'KM',
            'QUINTA' => 'QTA',
            'RAMPA' => 'RMP',
            'REDE ELETRICA' => 'REDE',
            'RETA' => 'RTA',
            'RIO' => 'RIO',
            'RODOVIA' => 'RDV',
            'ROTUNDA' => 'ROT',
            'RUA' => 'R',
            'RUELA' => 'RE',
            'SALA' => 'SAL',
            'SALAS' => 'SLS',
            'SERRA' => 'SERRA',
            'SERTAO' => 'SER',
            'SERVIDAO' => 'SVD',
            'SETOR' => 'ST',
            'SHOPPING' => 'SHOP',
            'SITIO' => 'SIT',
            'SOBRADO' => 'SOB',
            'SOBRELOJA' => 'SLJ',
            'SUBIDA' => 'SUB',
            'SUBSOLO' => 'SSL',
            'SUPERQUADRA' => 'SQD',
            'TERMINAL' => 'TRM',
            'TERRENO' => 'TER',
            'TERREO' => 'TER',
            'TORRE' => 'TR',
            'TRANSVERSAL' => 'TRANSV',
            'TRAVESSA' => 'TV',
            'TREVIO' => 'TRV',
            'URBANIZACAO' => 'URB',
            'VALE' => 'VAL',
            'VARGEM' => 'VRG',
            'VARIANTE' => 'VTE',
            'VELODROMO' => 'VLD',
            'VIA' => 'VIA',
            'VIADUTO' => 'VD',
            'VIELA' => 'VEL',
            'VILA' => 'VIL',
            'VIVENDA' => 'VV',
            'ZONA' => 'ZN',
        );

        return $address;
    }

    /**
     * @return array
     */
    protected function getAbbreviationOthers()
    {
        $others = array(
            'ACADEMICO' => 'ACD',
            'ADVOGADO' => 'ADV',
            'ALFERES' => 'ALF',
            'ALMIRANTE' => 'ALM',
            'ARCEBISPO' => 'ACB',
            'ARQUITETO' => 'ARQ',
            'ASSOCIACAO' => 'ASS',
            'BARONESA' => 'BEZ',
            'BARAO' => 'BR',
            'BOMBEIRO' => 'BOM',
            'BRIGADEIRO' => 'BRIG',
            'CABO' => 'CB',
            'CAPITAO' => 'CAP',
            'COMANDANTE' => 'CMDT',
            'COMENDADOR' => 'COMEND',
            'CONSELHEIRO' => 'CONS',
            'CORONEL' => 'COR',
            'CONSUL' => 'COL',
            'DEPUTADO' => 'DEP',
            'DESEMBARGADOR' => 'DES',
            'DOM' => 'D',
            'DONA' => 'D',
            'DOUTOR' => 'DR',
            'DOUTORA' => 'DR',
            'DUQUE' => 'DQ',
            'DUQUESA' => 'DQA',
            'EMBAIXADOR' => 'EMB',
            'ENGENHEIRA' => 'ENG',
            'ENGENHEIRO' => 'ENG',
            'EXPEDICIONARIO' => 'EXP',
            'FILHO' => 'FO',
            'FREI' => 'FR',
            'GENERAL' => 'GEN',
            'GOVERNADOR' => 'GOV',
            'INFANTE' => 'INF',
            'INSTITUTO' => 'INST',
            'JORNALISTA' => 'JOR',
            'JUNIOR' => 'JR',
            'LUGAR' => 'LUG',
            'MAESTRO' => 'MTO',
            'MAJOR' => 'MAJ',
            'MARECHAL' => 'MAL',
            'MARQUES' => 'MQ',
            'MARQUES' => 'MQ',
            'MINISTRO' => 'MIN',
            'MINISTERIO' => 'MIN',
            'MONSENHOR' => 'MNS',
            'PADRE' => 'PE',
            'PASTOR' => 'PA',
            'PREFEITO' => 'PREF',
            'PRESIDENTE' => 'PRESID',
            'PRINCESA' => 'PRINC',
            'PROFESSOR' => 'PROF',
            'PROFESSORA' => 'PROF',
            'PROJETADA' => 'PROJ',
            'REGENTE' => 'REG',
            'SANTA' => 'STA',
            'SANTO' => 'STO',
            'SARGENTO' => 'SARG',
            'SEM NUMERO' => 'SN',
            'SENADOR' => 'SEM',
            'SOCIEDADE' => 'SOC',
            'SOLDADO' => 'SOL',
            'SAO' => 'S',
            'TENENTE' => 'TEN',
            'UNIVERSIDADE' => 'UNIV',
            'VEREADOR' => 'VER',
            'VIGARIO' => 'VIG',
            'VISCONDE' => 'VISC',
        );

        return $others;
    }

    /**
     * @param $string
     * @return null|string|string[]
     */
    protected function removeAccents($string)
    {
        return preg_replace(
            array("/(á|à|ã|â|ä)/",
                "/(Á|À|Ã|Â|Ä)/",
                "/(é|è|ê|ë)/",
                "/(É|È|Ê|Ë)/",
                "/(í|ì|î|ï)/",
                "/(Í|Ì|Î|Ï)/",
                "/(ó|ò|õ|ô|ö)/",
                "/(Ó|Ò|Õ|Ô|Ö)/",
                "/(ú|ù|û|ü)/",
                "/(Ú|Ù|Û|Ü)/",
                "/(ç)/",
                "/(Ç)/",
                "/(ñ)/",
                "/(Ñ)/"
            ),
            array("a","A","e","E","i","I","o","O","u","U","c","C","n","N"),
            $string
        );
    }

    /**
     * @param $string
     * @return string
     */
    protected function abbreviationPlaces($string)
    {
        return strtr($string, $this->abbreviationList);
    }

    /**
     * @return $this
     */
    public function abbreviation()
    {
        $this->prepareAbbreviation();
        $steep = 1;

        while($this->getSize() > $this->abbreviationLimit)
        {
            switch ($steep)
            {
                case 1:
                    $this->setComplement($this->abbreviate($this->complement, $this->abbreviationList));
                    break;
                case 2:
                    $this->setStreet($this->abbreviate($this->street, $this->abbreviationList));
                    break;
                case 3:
                    $this->setComplement($this->abbreviateMore($this->complement));
                    break;
                case 4:
                    $this->setStreet($this->abbreviateMore($this->street));
                    break;
                case 5:
                    $this->setStreet($this->cutForAbbreviate($this->street, $this->abbreviationStreetPercentage, true));
                    $this->setComplement($this->cutForAbbreviate($this->complement, $this->abbreviationComplementPercentage, false));
                    break;
            }
            $steep ++;
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function prepareAbbreviation()
    {
        $this->setAbbreviation();
        $this->street = trim($this->street);
        $this->complement = trim($this->complement);
        $this->street = $this->removeAccents($this->street);
        $this->complement = $this->removeAccents($this->complement);
        $this->street = strtoupper($this->street);
        $this->complement = strtoupper($this->complement);
        $this->street = $this->abbreviationPlaces($this->street, $this->abbreviationList);
        $this->complement = $this->abbreviationPlaces($this->complement, $this->abbreviationList);

        return $this;
    }

    /**
     * @return int
     */
    protected function getSize()
    {
        $this->abbreviationSize = 0;
        $this->abbreviationSize += strlen($this->street);
        $this->abbreviationSize += strlen($this->number) + 1;
        $this->abbreviationSize += strlen($this->complement) + 1;

        return $this->abbreviationSize;
    }

    /**
     * @param $string
     * @return string
     */
    protected function abbreviate($string)
    {
        $string_array = explode(' ', $string);
        $abbreviationListValues = array_values($this->abbreviationList);

        $i = 1;
        foreach($string_array as $key => $word)
        {
            if (!in_array($word, $abbreviationListValues) && strlen($word) > 2 && !is_numeric($word))
            {
                if(in_array(reset($string_array), $abbreviationListValues) && ($i == 2  || $i == count($string_array))) {
                    $string_array[$key] = $word;
                } else {
                    if($i == 1  || $i == count($string_array)) {
                        $string_array[$key] = $word;
                    } else {
                        $string_array[$key] = substr($word, 0, 1);
                    }
                }
            }
            $i++;
        }

        return implode(' ', $string_array);
    }

    /**
     * @param $string
     * @return string
     */
    protected function abbreviateMore($string)
    {
        $string_array = explode(' ', $string);
        $stringReturn = array();
        $count = count($string_array);

        $i = 1;
        foreach($string_array as $word)
        {
            if ($i == 1) {
                $stringReturn[]= $word;
            }

            if(($i >= 2) && ($i <= $count))
            {
                if(strlen($word) > 2 && !is_numeric($word))
                {
                    $stringReturn[] = substr($word, 0, 1);
                } else {
                    $stringReturn[] = $word;
                }
            }

            $i++;
        }

        return implode(' ', $stringReturn);
    }

    /**
     * @param $string
     * @param $percentage
     * @param bool $roundUp
     * @return string
     */
    protected function cutForAbbreviate($string, $percentage, $roundUp = false)
    {
        $remainingSize = abs($this->abbreviationLimit - (strlen($this->number) + 2));
        $stringSize = (($remainingSize * $percentage) / 100);

        $stringSize = ($roundUp) ? ceil($stringSize) : floor($stringSize);

        return trim(substr($string, 0, $stringSize));
    }
}
