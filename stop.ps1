# Navigate to backend directory
Set-Location -Path .\backend

# Stop the Symfony server
symfony server:stop

# Navigate to frontend directory
Set-Location -Path ..\frontend

# Get the PID of the React app
$REACT_PID = Get-Process -Name "node" | Where-Object { $_.MainWindowTitle -match "localhost:3000" } | Select-Object -ExpandProperty Id

# Stop the React app
if ($REACT_PID) {
    Stop-Process -Id $REACT_PID -Force
    Write-Output "React app stopped."
} else {
    Write-Output "React app is not running."
}