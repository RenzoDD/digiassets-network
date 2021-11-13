@echo off
SET NODE_ENV=development
SET PORT=3000
SET DB_HOST=localhost
SET DB_NAME=digiassets_network
SET DB_USER=root
SET DB_PASS=

cls
node src/index.js
pause