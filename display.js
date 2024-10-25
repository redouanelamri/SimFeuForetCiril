document.getElementById('startSimulation').addEventListener('click', function() {
    // Lorsque l'utilisateur clique sur le bouton "startSimulation", lancer la simulation
    fetch('simulation.php') // Récupérer les étapes de la simulation depuis le fichier simulation.php
        .then(response => response.json()) // Convertir la réponse en format JSON
        .then(steps => {
            runSimulation(steps); // Lancer la simulation avec les étapes reçues
        });
});

function runSimulation(steps) {
    const canvas = document.getElementById('forestCanvas'); // Récupérer le canvas où sera affichée la simulation
    const context = canvas.getContext('2d'); // Définir le contexte 2D pour dessiner sur le canvas
    const cellSize = canvas.width / steps[0][0].length; // Calculer la taille de chaque cellule en fonction de la grille

    let currentStep = 0; // Variable pour suivre l'étape actuelle de la simulation

    // Fonction pour dessiner la grille sur le canvas
    function drawGrid(grid) {
        context.clearRect(0, 0, canvas.width, canvas.height); // Effacer le canvas avant de redessiner

        for (let i = 0; i < grid.length; i++) {
            for (let j = 0; j < grid[i].length; j++) {
                // Définir la couleur en fonction de l'état de chaque cellule
                if (grid[i][j] === 0) {
                    context.fillStyle = 'green'; // Vert pour une cellule vide (non brûlée)
                } else if (grid[i][j] === 1) {
                    context.fillStyle = 'red'; // Rouge pour une cellule en feu
                } else {
                    context.fillStyle = 'gray'; // Gris pour une cellule brûlée
                }

                // Dessiner le carré représentant la cellule
                context.fillRect(j * cellSize, i * cellSize, cellSize, cellSize);

                // Ajouter une bordure noire autour de chaque cellule
                context.strokeStyle = 'black';
                context.strokeRect(j * cellSize, i * cellSize, cellSize, cellSize);
            }
        }
    }

    // Fonction pour passer à l'étape suivante de la simulation
    function nextStep() {
        if (currentStep < steps.length) {
            drawGrid(steps[currentStep]); // Dessiner la grille pour l'étape actuelle
            currentStep++; // Incrémenter le compteur d'étapes
            setTimeout(nextStep, 500); // Attendre 500 millisecondes avant de passer à l'étape suivante
        }
    }

    nextStep(); // Commencer la simulation en appelant la première étape
}