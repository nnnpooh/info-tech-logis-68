<!DOCTYPE html>
<html>

<head>
    <title>Sensor</title>
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.lime.css">
    <script>
        // This function will refresh the image every second
        function refreshImage() {
            const img = document.getElementById('sensor-img');
            const baseUrl = 'https://pmX-ctXXX-n8n.iecmu.com/webhook/light';
            // Add a timestamp to bypass browser cache
            img.src = baseUrl + '?t=' + new Date().getTime();
        }

        // Turn LED ON (Uncomment for control)
        // -----------------------------------
        // function turnLedOn() {
        //     fetch('https://pmX-ctXXX-n8n.iecmu.com/webhook/led?led=on', {
        //             method: 'GET'
        //         })
        //         .then(response => {
        //             alert('LED turned ON');
        //         });
        // }

        // Turn LED OFF (Uncomment for control)
        // -----------------------------------
        // function turnLedOff() {
        //     fetch('https://pmX-ctXXX-n8n.iecmu.com/webhook/led?led=off', {
        //             method: 'GET'
        //         })
        //         .then(response => {
        //             alert('LED turned OFF');
        //         });
        // }

        // Start refreshing after the page loads
        window.onload = function() {
            refreshImage(); // Initial load
            setInterval(refreshImage, 2000); // Repeat

            // Uncomment for control
            // -----------------------------------
            // document.getElementById('btn-on').addEventListener('click', turnLedOn);
            // document.getElementById('btn-off').addEventListener('click', turnLedOff);

        };
    </script>
    </script>


</head>

<body>
    <main class="container">
        <h1>Sensor Value</h1>

        <article>
            <h2>Light Sensor</h2>
            <img id="sensor-img" alt="Sensor Value">
        </article>

        <article>
            <h2>Control</h2>
            <div class="grid">
                <button id="btn-on">ON</button>
                <button id="btn-off">OFF</button>
            </div>
        </article>
        </div>


    </main>
</body>

</html>