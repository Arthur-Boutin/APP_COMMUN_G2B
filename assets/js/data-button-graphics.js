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

        if (!toggleButton || !chartContainer) {
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

    // Configuration des toggles pour TOUS les graphiques
    setupChartToggle('toggleTemperatureChartBtn', 'temperatureChartContainer', 'le graphique de Température');
    setupChartToggle('toggleHumidityChartBtn', 'humidityChartContainer', 'le graphique d\'Humidité');
    setupChartToggle('toggleLightChartBtn', 'lightChartContainer', 'le graphique de Luminosité');
    setupChartToggle('toggleDistanceChartBtn', 'distanceChartContainer', 'le graphique de Distance');
    setupChartToggle('toggleSoundChartBtn', 'soundChartContainer', 'le graphique de Son');
    setupChartToggle('toggleBuzzerChartBtn', 'buzzerChartContainer', 'le graphique du Buzzer');


    // Intégration de Chart.js avec des données de la base de données
    // Assurez-vous que le chemin vers votre script PHP est correct.
    fetch('../controllers/get_sensor_data.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`Erreur HTTP! Statut: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            // Initialisation des graphiques avec les données de la base de données

            // Graphique de Température
            const tempCtx = document.getElementById('temperatureChart');
            if (tempCtx && data.temperature && data.temperature.labels && data.temperature.data) {
                new Chart(tempCtx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: data.temperature.labels,
                        datasets: [{
                            label: 'Température (°C)',
                            data: data.temperature.data,
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
            if (humidityCtx && data.humidity && data.humidity.labels && data.humidity.data) {
                new Chart(humidityCtx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: data.humidity.labels,
                        datasets: [{
                            label: 'Humidité (%)',
                            data: data.humidity.data,
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
            if (lightCtx && data.light && data.light.labels && data.light.data) {
                new Chart(lightCtx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: data.light.labels,
                        datasets: [{
                            label: 'Luminosité (Lux)',
                            data: data.light.data,
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
            if (distanceCtx && data.distance && data.distance.labels && data.distance.data) {
                new Chart(distanceCtx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: data.distance.labels,
                        datasets: [{
                            label: 'Distance (m)',
                            data: data.distance.data,
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
            if (soundCtx && data.sound && data.sound.labels && data.sound.data) {
                new Chart(soundCtx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: data.sound.labels,
                        datasets: [{
                            label: 'Son (dB)',
                            data: data.sound.data,
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
        })
        .catch(error => {
            console.error('Erreur lors du chargement des données des capteurs :', error);
        });
});
