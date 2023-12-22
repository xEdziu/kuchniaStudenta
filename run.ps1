# Navigate to backend directory
Set-Location "backend"

# Start the Symfony server
Start-Process "symfony" "server:start -d" -NoNewWindow

# Wait 5 seconds
Start-Sleep -s 5

# Get the server status
$server_status = symfony server:status

# Extract the port number
$port = $server_status -match ':[0-9]+' | ForEach-Object { $_.Split(':')[1] } | Select-Object -First 1

# Extract the server IP
$serv_ip = $server_status -match '[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+' | ForEach-Object { $_.Split(':')[0] } | Select-Object -First 1

# Echo the port
Write-Output "Symfony server started on port: "$serv_ip":"$port""

# Navigate to frontend directory
Set-Location "../frontend"

# Replace the REACT_APP_SYMFONY_PORT line in the .env file
(Get-Content .\.env) | ForEach-Object {
    $_ -replace "^REACT_APP_SYMFONY=.*", "REACT_APP_SYMFONY=${serv_ip}:${port}"
} | Set-Content .\.env

# Start the React app
Start-Process "npm" "start" -NoNewWindow