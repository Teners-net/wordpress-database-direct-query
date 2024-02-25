<div class="wrap">
    <h1>WP Database Direct Query</h1>

    <div id="endpoints-list">
        <h2>Available REST API Endpoints</h2>
        <ul id="endpoints"></ul>
    </div>
</div>

<script>
    fetch('/wp-json/wp-db-direct-query/v1')
        .then(response => response.json())
        .then(data => {
            const endpointsList = document.getElementById('endpoints');

            endpointsList.innerHTML = '';

            const endPoints = (endpoint, methods, namespace) => `<li>
                <strong>${endpoint}</strong> <br />
                - Methods: ${methods.join(', ')} <br />
                - Namespace: ${namespace}
            </li>`

            Object.entries(data.routes).forEach(([endpoint, data]) => {
                const methods = data.methods || [];
                const html = endPoints(endpoint, methods, data.namespace);
                endpointsList.innerHTML += html;
            });
        })
        .catch(error => console.error('Error fetching endpoints:', error));
</script>

<style>
    li {
        margin-bottom: 10px;
    }
</style>