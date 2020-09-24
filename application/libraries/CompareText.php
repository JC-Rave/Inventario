<?php
/**
 * Compara texto sin tener en cuenta la diferencia entre las mayúsculas
 * y minúsculas, ni la diferencia entre letras acenuadas o no.
 *
 * @example Por ejemplo, si queremos comparar la palabra Valencia con
 * València, en una comparación normal nos dirá que son palabras
 * diferentes incluso utilizando la función 'strcasecmp' de PHP:
 *      strcasecmp('Valencia', 'Valéncia'); // Devuelve -94
 * pero con 
 * estra el método CompareText::icmp(s1, s2) de esta clase obtendriamos
 * que son iguales.
 */
class CompareText {
    /**
     * Array asociativo que contiene las equivalencias numéricas para letras similares.
     * @var array
     */
    private $alpha;

    /**
     * Devuelve una nueva instancia de esta clase.
     */
    public function __construct() {
        /* Utilizamos las ventajas de los arrays asociativos de PHP para
         * obtener un valor numérico igual para letras "textualmente iguales" */
        $this->alpha = [
            'F' => 65, 'f' => 65,
            'G' => 66, 'g' => 66,
            'H' => 67, 'h' => 67,
            'J' => 68, 'j' => 68,
            'K' => 69, 'k' => 69,
            'L' => 70, 'l' => 70,
            'M' => 71, 'm' => 71,
            'Q' => 72, 'q' => 72,
            'R' => 73, 'r' => 73,
            'T' => 74, 't' => 74,
            'V' => 75, 'v' => 75,
            'W' => 76, 'w' => 76,
            'X' => 77, 'x' => 77,
            'A' => 78, 'a' => 78,
            'Á' => 78, 'á' => 78, 'À' => 78, 'à' => 78, 'Â' => 78, 'â' => 78,
                'Ã' => 78, 'ã' => 78, 'Ä' => 78, 'ä' => 78, 'Å' => 78, 'å' => 78,
                'Æ' => 78, 'æ' => 78,
            'E' => 79, 'e' => 79, 'É' => 79, 'é' => 79, 'È' => 79, 'è' => 79,
                'Ê' => 79, 'ê' => 79, 'Ë' => 79, 'ë' => 79,
            'I' => 80, 'i' => 80, 'Í' => 80, 'í' => 80, 'Ì' => 80, 'ì' => 80,
                'Î' => 80, 'î' => 80, 'Ï' => 80, 'ï' => 80,
            'O' => 81, 'o' => 81, 'Ó' => 81, 'ó' => 81, 'Ò' => 81, 'ò' => 81,
                'Ô' => 81, 'ô' => 81, 'Õ' => 81, 'õ' => 81, 'Ö' => 81, 'ö' => 81,
                'Ø' => 81, 'ø' => 81, 'Œ' => 81, 'œ' => 81,
            'U' => 82, 'u' => 82, 'Ú' => 82, 'ú' => 82, 'Ù' => 82, 'ù' => 82,
                'Ü' => 82, 'ü' => 82, 'Û' => 82, 'û' => 82,
            'C' => 83, 'c' => 83, 'Ç' => 83, 'ç' => 83,
            'N' => 84, 'n' => 84, 'Ñ' => 84, 'ñ' => 84,
            'S' => 85, 's' => 85, 'Š' => 85, 'š' => 85,
            'Z' => 86, 'z' => 86, 'Ž' => 86, 'ž' => 86,
            'Y' => 87, 'y' => 87, 'Ÿ' => 87, 'ÿ' => 87, 'Ý' => 87, 'ý' => 87,
            'D' => 88, 'd' => 88, 'Ð' => 88, 'ð' => 88,
            'P' => 89, 'p' => 89, 'Þ' => 89, 'þ' => 89,
            'B' => 90, 'b' => 90, 'ß' => 90
        ];
    }

    /**
     * Devuelve la posición de una letra en un criterio relativo de comparación
     * que ignora diferencias entre mayúsculas y minúsculas y sus signos.
     * @param string $c Cadena conteniendo un único carácter.
     * @return int
     */
    public function ipos($c) {
        // Obsérvese que si la letra no existe en el array se devuelve su
        // código UTF-8 a través de la función mb_ord($c)
        return $this->alpha[$c] ?? mb_ord($c);
    }

    /**
     * Compara dos cadenas sin tener en cuenta la diferencia entre
     * mayúsculas y minúsculas ni los signos.
     * Devuelve 0 si so iguales.
     * -1 si la primera es menor que la segunda.
     * 1 si la primera es mayor que la segunda.
     * @param string $s1 Primera cadena que se comparará.
     * @param string $s2 Segunda cadena que se comparará.
     * @return int
     */
    public function icmp($s1, $s2) {
        if (mb_strlen($s1) > mb_strlen($s2)) {
            return 1;
        } else if (mb_strlen($s1) < mb_strlen($s2)) {
            return -1;
        } else {
            for ($i = 0; $i < mb_strlen($s1); $i++) {
                $j = $this->ipos(mb_substr($s1, $i, 1)); // Buscamos la posición de la letra de la primera cadena
                $k = $this->ipos(mb_substr($s2, $i, 1)); // Buscamos la posición de la letra de la segunda cadena
                if ($j > $k) {
                    return 1;
                } else if ($j < $k) {
                    return -1;
                }
            }
        }

        return 0;
    }
}
?>