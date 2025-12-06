<!DOCTYPE html>
<html>
<head>
    <title>Test Agents</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .agent { border: 1px solid #ccc; margin: 10px 0; padding: 10px; }
        .success { background: #d4edda; }
        .error { background: #f8d7da; }
    </style>
</head>
<body>
    <h1>Test Agent Loading</h1>
    <div id="test-results"></div>

    <script>
        async function testAgents() {
            try {
                const response = await fetch('http://localhost:8000/api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ action: 'get_agents' })
                });

                const result = await response.json();
                console.log('API Response:', result);

                const resultsDiv = document.getElementById('test-results');

                if (result.success && result.data) {
                    resultsDiv.innerHTML = '<h2 class="success">✅ Success: ' + result.data.length + ' agents found</h2>';
                    result.data.forEach((agent, i) => {
                        resultsDiv.innerHTML += `
                            <div class="agent success">
                                <strong>${i+1}. ${agent.name}</strong><br>
                                URL: ${agent.url || 'no URL'}<br>
                                ID: ${agent.id}
                            </div>
                        `;
                    });
                } else {
                    resultsDiv.innerHTML = '<h2 class="error">❌ Failed: ' + JSON.stringify(result) + '</h2>';
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('test-results').innerHTML = '<h2 class="error">❌ Network Error: ' + error.message + '</h2>';
            }
        }

        // Test on page load
        testAgents();
    </script>
</body>
</html>