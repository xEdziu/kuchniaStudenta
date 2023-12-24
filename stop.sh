#! /bin/bash

# Stop the Symfony server
cd backend
symfony server:stop

# Get the PID of the React app
REACT_PID=$(lsof -t -i:3000)

# Stop the React app
if [ -n "$REACT_PID" ]; then
    kill $REACT_PID
    echo "React app stopped."
else
    echo "React app is not running."
fi
