<?php

// Lecture du fichier config.json pour récupérer les paramètres de la simulation
$config = json_decode(file_get_contents('config.json'), true);

// Dimensions de la grille et paramètres initiaux récupérés depuis le fichier config.json
$height = $config['height'];
$width = $config['width'];
$initialFirePositions = $config['initialFirePositions'];
$probability = $config['probability'];

// Initialisation de la grille (0 = vide, 1 = en feu, 2 = brûlé)
$grid = array_fill(0, $height, array_fill(0, $width, 0));

// Définition des positions initiales du feu dans la grille
foreach ($initialFirePositions as $position) {
    $grid[$position[0]][$position[1]] = 1;
}

// Fonction pour simuler une étape de la propagation du feu
function simulate_step($grid, $probability) {
    $newGrid = $grid;
    $height = count($grid);
    $width = count($grid[0]);

    for ($i = 0; $i < $height; $i++) {
        for ($j = 0; $j < $width; $j++) {
            if ($grid[$i][$j] == 1) {
                // Brûler la cellule actuelle
                $newGrid[$i][$j] = 2;

                // Propager le feu aux cellules adjacentes
                $adjacent = [
                    [$i - 1, $j], // top
                    [$i + 1, $j], // bottom
                    [$i, $j - 1], // left
                    [$i, $j + 1]  // right
                ];

                foreach ($adjacent as $pos) {
                    $x = $pos[0];
                    $y = $pos[1];

                    // Vérifier si la position est dans les limites de la grille et si la cellule n'est pas déjà brûlée
                    if ($x >= 0 && $x < $height && $y >= 0 && $y < $width && $grid[$x][$y] == 0) {
                        // Propager le feu en fonction de la probabilité
                        if (rand(0, 100) / 100 <= $probability) {
                            $newGrid[$x][$y] = 1;
                        }
                    }
                }
            }
        }
    }

    return $newGrid;
}

// Simuler plusieurs étapes et renvoyer les états de la grille
$steps = [];
while (true) {
    $steps[] = $grid;

    // Vérifier s'il y a encore du feu
    $fireExists = false;
    foreach ($grid as $row) {
        if (in_array(1, $row)) {
            $fireExists = true;
            break;
        }
    }

    if (!$fireExists) {
        break;
    }

    // Simuler l'étape suivante
    $grid = simulate_step($grid, $probability);
}

// Sortie du résultat au format JSON
header('Content-Type: application/json');
echo json_encode($steps);
