document.addEventListener('DOMContentLoaded', () => {

    const toggleViewBtn = document.getElementById('toggleViewBtn');
    const listView = document.getElementById('listView');
    const buttonView = document.getElementById('buttonView'); 
    let isListView = true; // L'état initial est la vue liste

    // Initialisation de la vue par défaut (Liste)
    listView.classList.remove('hidden');
    buttonView.classList.add('hidden');
    // graphicsView sera gérée par son propre master toggle si elle est cachée par défaut en HTML.
    // Si graphicsView est visible par défaut en HTML, la ligne ci-dessous n'est pas nécessaire.
    // graphicsView.classList.add('hidden'); // Assurez-vous qu'elle est cachée si ce n'est pas le cas en HTML

    // Initialisation du texte du bouton principal de bascule
    toggleViewBtn.textContent = 'Passer à la vue Cartes';

    toggleViewBtn.addEventListener('click', () => {
        if (isListView) {
            // Si la vue actuelle est la liste, passer aux cartes
            listView.classList.add('hidden');
            buttonView.classList.remove('hidden');
            toggleViewBtn.textContent = 'Passer à la vue Liste'; // Le bouton propose de revenir à la liste
        } else {
            // Si la vue actuelle est les cartes, passer à la liste
            listView.classList.remove('hidden');
            buttonView.classList.add('hidden');
            toggleViewBtn.textContent = 'Passer à la vue Cartes'; // Le bouton propose de passer aux cartes
        }
        isListView = !isListView; // Inverse l'état
    });


    // --- NOUVEAU: Logique pour le bouton de bascule globale des graphiques ---
    const toggleAllChartsBtn = document.getElementById('toggleAllChartsBtn');
    const chartContainers = document.querySelectorAll('#graphicsView .chart-container'); // Sélectionne tous les conteneurs de graphique dans graphicsView
    let allChartsVisible = true; // État initial de tous les graphiques

    if (toggleAllChartsBtn) {
        toggleAllChartsBtn.textContent = 'Masquer tous les Graphiques'; // Texte initial

        toggleAllChartsBtn.addEventListener('click', () => {
            allChartsVisible = !allChartsVisible; // Inverse l'état

            chartContainers.forEach(container => {
                if (allChartsVisible) {
                    container.classList.remove('hidden');
                } else {
                    container.classList.add('hidden');
                }
            });

            // Mettre à jour le texte du bouton global
            toggleAllChartsBtn.textContent = allChartsVisible ? 'Masquer tous les Graphiques' : 'Afficher tous les Graphiques';

            // Mettre à jour le texte des boutons de bascule individuels pour la cohérence
            // Il faut s'assurer que setupChartToggle gère l'état initial des boutons
            // Pour cela, nous allons re-initialiser les setupChartToggle pour mettre à jour leur texte
            // Il serait préférable de stocker les états individuellement si cette opération est coûteuse.
            // Pour l'instant, on se base sur l'état global.
            const individualToggleButtons = document.querySelectorAll('#graphicsView .toggle-container .form-btn:not(#toggleAllChartsBtn)');
            individualToggleButtons.forEach(btn => {
                const chartTitle = btn.textContent.replace(/(Masquer|Afficher) le graphique de /, '');
                if (allChartsVisible) {
                    btn.textContent = `Masquer le graphique de ${chartTitle}`;
                } else {
                    btn.textContent = `Afficher le graphique de ${chartTitle}`;
                }
            });
        });
    }

    // --- Fonction générique pour créer des toggles de graphique individuel ---
    // (Légèrement modifiée pour être réinitialisable par le toggle global si nécessaire)
    const chartToggleStates = {}; // Stocke l'état visible de chaque graphique individuel

    function setupChartToggle(buttonId, chartContainerId, chartTitle) {
        const button = document.getElementById(buttonId);
        const container = document.getElementById(chartContainerId);
        
        if (!button || !container) {
            console.warn(`Bouton ou conteneur de graphique non trouvé pour ${chartTitle}`);
            return;
        }

        // Initialiser l'état si ce n'est pas déjà fait
        if (chartToggleStates[chartContainerId] === undefined) {
             // Assumer visible par défaut si le conteneur n'a pas la classe hidden
            chartToggleStates[chartContainerId] = !container.classList.contains('hidden'); 
        }

        // Mettre à jour le texte initial du bouton basé sur son état
        button.textContent = chartToggleStates[chartContainerId] ? `Masquer le graphique de ${chartTitle}` : `Afficher le graphique de ${chartTitle}`;

        button.addEventListener('click', () => {
            chartToggleStates[chartContainerId] = !chartToggleStates[chartContainerId]; // Inverse l'état
            if (chartToggleStates[chartContainerId]) {
                container.classList.remove('hidden');
                button.textContent = `Masquer le graphique de ${chartTitle}`;
            } else {
                container.classList.add('hidden');
                button.textContent = `Afficher le graphique de ${chartTitle}`;
            }
        });
    }

    // --- Récupérer les données des graphiques depuis l'API PHP ---
    fetch('../controllers/get_sensor_data.php')
        .then(response => {
            if (!response.ok) {
                return response.json().then(errorData => {
                    throw new Error(errorData.error || `Erreur HTTP: ${response.status}`);
                });
            }
            return response.json();
        })
        .then(chartData => {
            console.log("Données reçues de l'API:", chartData);

            const chartsToCreate = [
                { id: 'temperatureChart', type: 'line', label: 'Température (°C)', data: chartData.temperature, borderColor: 'rgb(99, 255, 234)', backgroundColor: 'rgba(99, 255, 255, 0.2)', yAxisText: 'Température (°C)' },
                { id: 'humidityChart', type: 'line', label: 'Humidité (%)', data: chartData.humidity, borderColor: 'rgb(154, 54, 235)', backgroundColor: 'rgba(154, 54, 235, 0.2)', yAxisText: 'Humidité (%)' },
                { id: 'lightChart', type: 'line', label: 'Luminosité (lux)', data: chartData.light, borderColor: 'rgba(255, 206, 86, 1)', backgroundColor: 'rgba(255, 206, 86, 0.2)', yAxisText: 'Luminosité (lux)' },
                { id: 'distanceChart', type: 'line', label: 'Distance (m)', data: chartData.distance, borderColor: 'rgb(255, 102, 235)', backgroundColor: 'rgba(255, 102, 242, 0.2)', yAxisText: 'Distance (m)' },
                { id: 'soundChart', type: 'line', label: 'Son (dB)', data: chartData.sound, borderColor: 'rgb(102, 192, 75)', backgroundColor: 'rgba(102, 192, 75, 0.2)', yAxisText: 'Son (dB)' }
            ];

            chartsToCreate.forEach(chartInfo => {
                const ctx = document.getElementById(chartInfo.id);
                if (ctx && chartInfo.data && chartInfo.data.labels && chartInfo.data.labels.length > 0) {
                    new Chart(ctx.getContext('2d'), {
                        type: chartInfo.type,
                        data: {
                            labels: chartInfo.data.labels,
                            datasets: [{
                                label: chartInfo.label,
                                data: chartInfo.data.data,
                                borderColor: chartInfo.borderColor,
                                backgroundColor: chartInfo.backgroundColor,
                                tension: chartInfo.tension !== undefined ? chartInfo.tension : 0.4,
                                fill: chartInfo.fill !== undefined ? chartInfo.fill : true
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                x: {
                                    grid: { color: 'rgba(255, 255, 255, 0.1)' },
                                    ticks: { color: '#fff' },
                                    title: {
                                        display: true,
                                        text: 'Date et Heure',
                                        color: '#fff'
                                    }
                                },
                                y: {
                                    beginAtZero: chartInfo.beginAtZero !== undefined ? chartInfo.beginAtZero : false,
                                    grid: { color: 'rgba(255, 255, 255, 0.1)' },
                                    ticks: {
                                        color: '#fff',
                                        precision: (chartInfo.id === 'buzzerChart') ? 0 : undefined
                                    },
                                    title: {
                                        display: true,
                                        text: chartInfo.yAxisText,
                                        color: '#fff'
                                    }
                                }
                            },
                            plugins: { legend: { labels: { color: '#fff' } } }
                        }
                    });
                } else if (ctx) {
                    const parent = ctx.parentElement;
                    ctx.remove(); 
                    const messageDiv = document.createElement('div');
                    messageDiv.style.cssText = "color: #a8b2d1; text-align: center; padding-top: 2rem; font-size: 1rem;";
                    messageDiv.textContent = `Aucune donnée disponible pour ${chartInfo.label}.`;
                    parent.appendChild(messageDiv);
                }
            });

            // Initialiser les toggles individuels après la création des graphiques
            setupChartToggle('toggleTemperatureChartBtn', 'temperatureChartContainer', 'Température');
            setupChartToggle('toggleHumidityChartBtn', 'humidityChartContainer', 'Humidité');
            setupChartToggle('toggleLightChartBtn', 'lightChartContainer', 'Luminosité');
            setupChartToggle('toggleDistanceChartBtn', 'distanceChartContainer', 'Distance');
            setupChartToggle('toggleSoundChartBtn', 'soundChartContainer', 'Son');

        })
        .catch(error => {
            console.error('Erreur lors de la récupération des données des graphiques:', error);
            const graphicsViewSection = document.getElementById('graphicsView');
            if (graphicsViewSection) {
                graphicsViewSection.innerHTML = '<p style="color: red; text-align: center; font-size: 1.2rem; padding: 2rem;">Impossible de charger les données des graphiques. Veuillez vérifier votre connexion ou les logs du serveur.</p>';
            }
        });
});
