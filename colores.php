<?php
require 'Utils\Color.php';

$valor1 = $hex = $_POST["color1"];
$valor2 = $hex = $_POST["color2"];


try {
    $valor1_hsl = Color::hexToHsl($valor1);
} catch (Exception $e) {
}
try {
    $valor2_hsl = Color::hexToHsl($valor2);
} catch (Exception $e) {
}


header('Content-Type: application/json');


try {
    $color_complementary = Color::complementary($valor1_hsl);
    $validateComplementaryColors = validateComplementaryColors($valor1_hsl, $valor2) ? 'Son complementarios' : 'No son complementarios';
} catch (Exception $e) {
}
try {
    //Guardamos los datos en un array
    $datos = array(
        'valor1' => $valor1,
        'valor2' => $valor2,
        'valor1_hsl' => $valor1_hsl,
        'valor2_hsl' => $valor2_hsl,
        'complementary_color1' => $color_complementary,
        'validateComplementaryColors ' => $validateComplementaryColors,
        'tetrad' => Color::tetrad($valor1_hsl),
        'ValidateTetrad' => validateTetrad($valor1_hsl, $valor2_hsl) ? 'Forma parte de la tetrada' : 'No forma parte de la tetrada',
        'triad' => Color::triad($valor1_hsl),
        'ValidateTriad' => validateTriad($valor1_hsl, $valor2_hsl) ? 'Forma parte de la triada' : 'No forma parte de la triada',
        'adyacent' => Color::adjacent($valor1_hsl),
        'ValidateAdyacent' => validateAdyacent($valor1_hsl, $valor2_hsl) ? 'Son adyacentes' : 'No son adyacentes',
        'valorDeCombinacion' => validateCombination($valor1_hsl, $valor2_hsl, $valor1, $valor2)
    );
    //Devolvemos el array pasado a JSON como objeto
    echo json_encode($datos, JSON_FORCE_OBJECT);
} catch (Exception $e) {
}

function validateCombination($valor1_hsl, $valor2_hsl, $valor1, $valor2) {
    $value_combination = 0;
    $tetrad = validateTetrad($valor1_hsl, $valor2_hsl);
    $triad = validateTriad($valor1_hsl, $valor2_hsl);
    $adyacent = validateAdyacent($valor1_hsl, $valor2_hsl);
    $complements = validateComplementaryColors($valor1_hsl,$valor2);
    $neutralColors = neutralColors ($valor1, $valor2);

    if ($valor1 === $valor2 || $neutralColors === 1) {
        return 1;
    } else {
        $value_combination = ($tetrad + $triad + $adyacent + $neutralColors + $complements) * 5 /100;
    }

    return $value_combination;
}

function neutralColors ($valor1, $valor2) {
    $validate = 0;

    $neutral_colors = array(
        '#3c3c3c','#515151','#666666','##8c8c8c','##b5b5b5','##3e4144','##45484a','#474b4e',
        '#535b61','#65727c','#a7a7a7','#bdbdbd','#c9c9c9','#d3d3d3','#dcdcdc','#666f88',
        '#788199','#8990a2','#a3a8b7','#b5bac9','#5e5e5e','#6f6f6f','#828282','#aaaaaa',
        '#c6c6c6','#7a7a7a','#969696','#9c9c9c','#bababa','#c8c8c8','#171718','#1f2124',
        '#393d42','#6a6e73','#9fa3a9','#ffffff','#000000'
        );

    if (in_array($valor1, $neutral_colors) || in_array($valor2, $neutral_colors)) {
        $validate = 1;
    }

    return $validate;
}

function validateTetrad($valor1_hsl, $valor2_hsl) {
    $validate = 0;

    try {
        $tetrad = Color::tetrad($valor1_hsl);
        for ($i = 0 ; $i < sizeof($tetrad); $i++) {
          if ($tetrad[$i]['H'] === $valor2_hsl['H'] && $tetrad[$i]['S'] === $valor2_hsl['S'] && $tetrad[$i]['L'] === $valor2_hsl['L']) {
                $validate = 1;
            }
        }

    } catch (Exception $e) {
        return $e->getMessage();
    }

    return $validate;
}

function validateTriad($valor1_hsl, $valor2_hsl) {
    $validate = 0;

    try {
        $triad = Color::triad($valor1_hsl);
        for ($i = 0 ; $i < sizeof($triad); $i++) {
            if ($triad[$i]['H'] === $valor2_hsl['H'] && $triad[$i]['S'] === $valor2_hsl['S'] && $triad[$i]['L'] === $valor2_hsl['L']) {
                $validate = 1;
            }
        }

    } catch (Exception $e) {
        return $e->getMessage();
    }

    return $validate;
}

function validateAdyacent($valor1_hsl, $valor2_hsl) {
    $validate = 0;

    try {
        $adjacent = Color::adjacent($valor1_hsl);
        for ($i = 0 ; $i < sizeof($adjacent); $i++) {
            if ($adjacent[$i]['H'] === $valor2_hsl['H'] && $adjacent[$i]['S'] === $valor2_hsl['S'] && $adjacent[$i]['L'] === $valor2_hsl['L']) {
                $validate = 1;
            }
        }

    } catch (Exception $e) {
        return $e->getMessage();
    }

    return $validate;
}

/**
 * Returns the complimentary color
 * @return int
 * @throws Exception
 */
function validateComplementaryColors($valor1_hsl, $valor2_hsl) {
    $complementary = 0;

    $color_complementary = Color::complementary($valor1_hsl);

    if ($color_complementary === $valor2_hsl) {
        $complementary = 1;
    }

    return $complementary;
}





?>


