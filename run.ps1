# Start Symfony server
Set-Location "backend"
Start-Process "symfony" "server:start -d" -NoNewWindow

# Start React app
Set-Location "../frontend"
Start-Process "npm" "start" -NoNewWindow