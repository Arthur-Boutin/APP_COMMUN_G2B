    document.addEventListener('DOMContentLoaded', () => {

        // Logique de bascule entre les vues Liste et Cartes (conserve le comportement existant)
        const toggleViewBtn = document.getElementById('toggleViewBtn');
        const listView = document.getElementById('listView');
        const buttonView = document.getElementById('buttonView'); 
        let isListView = true; // L'état initial est la vue liste

        // Initialisation du texte du bouton selon la vue par défaut
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

        // Fonction générique pour créer des toggles de graphique
        function setupChartToggle(buttonId, chartContainerId, chartTitle) {
            const toggleButton = document.getElementById(buttonId);
            const chartContainer = document.getElementById(chartContainerId);

            if (!toggleButton) {
                return;
            }
            if (!chartContainer) {
                return;
            }

            let isChartVisible = true; // Par défaut, les graphiques sont visibles

            // Initialisation du texte du bouton
            toggleButton.textContent = `Masquer ${chartTitle}`;

            toggleButton.addEventListener('click', () => {
                if (isChartVisible) {
                    chartContainer.classList.add('hidden');
                    toggleButton.textContent = `Afficher ${chartTitle}`;
                } else {
                    chartContainer.classList.remove('hidden');
                    toggleButton.textContent = `Masquer ${chartTitle}`;
                }
                isChartVisible = !isChartVisible;
            });
        }

        // Configuration des toggles pour chaque graphique
        setupChartToggle('toggleTemperatureChartBtn', 'temperatureChartContainer', 'le graphique de Température');
        setupChartToggle('toggleHumidityChartBtn', 'humidityChartContainer', 'le graphique d\'Humidité');
        setupChartToggle('toggleLightChartBtn', 'lightChartContainer', 'le graphique de Luminosité');
        setupChartToggle('toggleDistanceChartBtn', 'distanceChartContainer', 'le graphique de Distance');
        setupChartToggle('toggleSoundChartBtn', 'soundChartContainer', 'le graphique de Son');
        setupChartToggle('toggleBuzzerChartBtn', 'buzzerChartContainer', 'le graphique du Buzzer');


        // Intégration de Chart.js
        // Pour la démonstration, nous utilisons des données fictives.
        // En production, vous devrez récupérer ces données via des requêtes AJAX à votre backend PHP.

        // Données fictives pour les graphiques
        const commonLabels = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00'];

        // Graphique de Température
        const tempCtx = document.getElementById('temperatureChart');
        if (tempCtx) {
            new Chart(tempCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: commonLabels,
                    datasets: [{
                        label: 'Température (°C)',
                        data: [22.5, 23.1, 22.8, 23.5, 24.0, 23.8, 24.2],
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { grid: { color: 'rgba(255, 255, 255, 0.1)' }, ticks: { color: '#fff' } },
                        y: { beginAtZero: false, grid: { color: 'rgba(255, 255, 255, 0.1)' }, ticks: { color: '#fff' } }
                    },
                    plugins: { legend: { labels: { color: '#fff' } } }
                }
            });
        }


        // Graphique d'Humidité
        const humidityCtx = document.getElementById('humidityChart');
        if (humidityCtx) {
            new Chart(humidityCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: commonLabels,
                    datasets: [{
                        label: 'Humidité (%)',
                        data: [60, 62, 58, 65, 63, 67, 60],
                        borderColor: 'rgba(153, 102, 255, 1)',
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { grid: { color: 'rgba(255, 255, 255, 0.1)' }, ticks: { color: '#fff' } },
                        y: { beginAtZero: false, grid: { color: 'rgba(255, 255, 255, 0.1)' }, ticks: { color: '#fff' } }
                    },
                    plugins: { legend: { labels: { color: '#fff' } } }
                }
            });
        }

        // Graphique de Luminosité
        const lightCtx = document.getElementById('lightChart');
        if (lightCtx) {
            new Chart(lightCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: commonLabels,
                    datasets: [{
                        label: 'Luminosité (Lux)',
                        data: [400, 450, 500, 480, 550, 520, 600], // Exemple en Lux
                        borderColor: 'rgba(255, 206, 86, 1)',
                        backgroundColor: 'rgba(255, 206, 86, 0.2)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { grid: { color: 'rgba(255, 255, 255, 0.1)' }, ticks: { color: '#fff' } },
                        y: { beginAtZero: false, grid: { color: 'rgba(255, 255, 255, 0.1)' }, ticks: { color: '#fff' } }
                    },
                    plugins: { legend: { labels: { color: '#fff' } } }
                }
            });
        }

        // Graphique de Distance
        const distanceCtx = document.getElementById('distanceChart');
        if (distanceCtx) {
            new Chart(distanceCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: commonLabels,
                    datasets: [{
                        label: 'Distance (cm)',
                        data: [15, 12, 10, 8, 11, 13, 9], // Exemple en cm
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { grid: { color: 'rgba(255, 255, 255, 0.1)' }, ticks: { color: '#fff' } },
                        y: { beginAtZero: false, grid: { color: 'rgba(255, 255, 255, 0.1)' }, ticks: { color: '#fff' } }
                    },
                    plugins: { legend: { labels: { color: '#fff' } } }
                }
            });
        }

        // Graphique de Son
        const soundCtx = document.getElementById('soundChart');
        if (soundCtx) {
            new Chart(soundCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: commonLabels,
                    datasets: [{
                        label: 'Son (dB)',
                        data: [50, 55, 60, 58, 62, 65, 53], // Exemple en dB
                        borderColor: 'rgba(75, 192, 192, 1)', // Peut utiliser une autre couleur si souhaité, ex: 'rgba(54, 162, 235, 1)'
                        backgroundColor: 'rgba(75, 192, 192, 0.2)', // Ex: 'rgba(54, 162, 235, 0.2)'
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { grid: { color: 'rgba(255, 255, 255, 0.1)' }, ticks: { color: '#fff' } },
                        y: { beginAtZero: false, grid: { color: 'rgba(255, 255, 255, 0.1)' }, ticks: { color: '#fff' } }
                    },
                    plugins: { legend: { labels: { color: '#fff' } } }
                }
            });
        }
    });