#!/bin/bash
cd backend
# Start the Symfony server
symfony server:start -d


# Grep the port number from the server status
server_status=$(symfony server:status)
port=$(echo $server_status | grep -oE ':[0-9]+' | awk -F: '{print $2}' | head -n 1)

#echo the port
echo "Symfony server started on port: $port"

# Start React app
cd ../frontend
# Replace the REACT_APP_SYMFONY_PORT line in the .env file
sed -i "s/^REACT_APP_SYMFONY_PORT=.*/REACT_APP_SYMFONY_PORT=$port/" .env

npm start &