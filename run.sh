#!/bin/bash
cd backend
# Start the Symfony server
symfony server:start -d


# Grep the port number from the server status
server_status=$(symfony server:status)
port=$(echo $server_status | grep -oE ':[0-9]+' | awk -F: '{print $2}' | head -n 1)

#grep the server ip
serv_ip=$(ifconfig eth0 | grep -oP 'inet \K[\d.]+')

#echo the port
echo "Symfony server started on: $serv_ip:$port"

# Start React app
cd ../frontend
# Replace the REACT_APP_SYMFONY_PORT line in the .env file
sed -i "s/^REACT_APP_SYMFONY_PORT=.*/REACT_APP_SYMFONY=$serv_ip:$port/" .env

npm start &