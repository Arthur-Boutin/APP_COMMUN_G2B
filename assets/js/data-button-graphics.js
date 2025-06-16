document.addEventListener('DOMContentLoaded', () => {

    const toggleViewBtnCard = document.getElementById('toggleViewBtnCard');
    const toggleViewBtnList = document.getElementById('toggleViewBtnList');
    const toggleViewBtnGraph = document.getElementById('toggleViewBtnGraph');
    const listView = document.getElementById('listView');
    const buttonView = document.getElementById('buttonView');
    const graphView = document.getElementById('graphicsView');

    toggleViewBtnCard.addEventListener('click', () => {
        buttonView.classList.remove('hidden');
        listView.classList.add('hidden');
        graphView.classList.add('hidden');
    })

    toggleViewBtnList.addEventListener('click', () => {
        listView.classList.remove('hidden');
        buttonView.classList.add('hidden');
        graphView.classList.add('hidden');
    })

    toggleViewBtnGraph.addEventListener('click', () => {
        graphView.classList.remove('hidden');
        listView.classList.add('hidden');
        buttonView.classList.add('hidden');
    })

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
                { id: 'temperatureChart', type: 'line', label: 'Capteur Température', data: chartData.temperature, borderColor: 'rgb(99, 255, 234)', backgroundColor: 'rgba(99, 255, 255, 0.2)', yAxisText: 'Température (°C)' },
                { id: 'humidityChart', type: 'line', label: 'Capteur Humidité', data: chartData.humidity, borderColor: 'rgb(154, 54, 235)', backgroundColor: 'rgba(154, 54, 235, 0.2)', yAxisText: 'Humidité (%)' },
                { id: 'lightChart', type: 'line', label: 'Capteur Lumière', data: chartData.light, borderColor: 'rgba(255, 206, 86, 1)', backgroundColor: 'rgba(255, 206, 86, 0.2)', yAxisText: 'Luminosité (lux)' },
                { id: 'distanceChart', type: 'line', label: 'Compteur de Personnes', data: chartData.distance, borderColor: 'rgb(255, 102, 235)', backgroundColor: 'rgba(255, 102, 242, 0.2)', yAxisText: 'Personnes' },
                { id: 'soundChart', type: 'line', label: 'Capteur Son', data: chartData.sound, borderColor: 'rgb(102, 192, 75)', backgroundColor: 'rgba(102, 192, 75, 0.2)', yAxisText: 'Son (dB)' }
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
                    messageDiv.textContent = `Aucune donnée disponible pour "${chartInfo.label}".`;
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
