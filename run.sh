#!/bin/bash

# Start Symfony server
cd backend
symfony server:start -d

# Start React app
cd ../frontend
npm start &