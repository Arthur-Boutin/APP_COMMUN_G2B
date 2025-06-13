document.addEventListener('DOMContentLoaded', function () {
    const thresholdSlider = document.getElementById("threshold-slider");
    const thresholdValue = document.getElementById("threshold-value");
    const durationSlider = document.getElementById("duration-slider");
    const durationValue = document.getElementById("buzz-duration-value");
    let duration = durationSlider.value;
    let interval;

    if (thresholdSlider && thresholdValue) {
        thresholdSlider.oninput = function () {
            const threshold = this.value;
            thresholdValue.textContent = threshold + "°C";

            fetch('../controllers/buzzer-management.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'threshold=' + encodeURIComponent(threshold)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Threshold updated successfully');
                        console.log(data.threshold);
                    } else {
                        console.error('Failed to update threshold');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    } else {
        console.error("Slider or threshold value element not found.");
    }

    if (durationSlider && durationValue) {
        durationSlider.oninput = function () {
            duration = this.value;
            durationValue.textContent = duration + " sec";
            redefineInterval();

            fetch('../controllers/buzzer-management.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'buzzDuration=' + encodeURIComponent(duration)
            })
                .then(response => response.text())
                .then(data => {
                    console.log(data)
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    } else {
        console.error("Buzz duration slider or buzz duration value element not found.");
    }

    fetch('../utils/launchBuzzer.php');
    redefineInterval();

    function redefineInterval() {
        if (interval) {
            clearInterval(interval);
        }
        setInterval(
            () => {
                fetch('../utils/launchBuzzer.php')
            }, 1000 * (5 + +duration));
    }
});
