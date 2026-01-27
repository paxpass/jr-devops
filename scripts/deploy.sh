#!/bin/bash
set -e

echo "Starting deployment..."

VPS_HOST="${VPS_HOST:-your-vps-ip}"
VPS_USER="${VPS_USER:-ubuntu}"
DEPLOY_PATH="/opt/secure-drop"

GREEN='\033[0;32m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE} Pulling images...${NC}"
ssh ${VPS_USER}@${VPS_HOST} << 'ENDSSH'
cd /opt/secure-drop
docker-compose -f docker-compose.yml -f docker-compose.prod.yml pull
ENDSSH

echo -e "${BLUE} Restarting services...${NC}"
ssh ${VPS_USER}@${VPS_HOST} << 'ENDSSH'
cd /opt/secure-drop
docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d --remove-orphans
ENDSSH

echo -e "${BLUE}  Running migrations...${NC}"
ssh ${VPS_USER}@${VPS_HOST} << 'ENDSSH'
cd /opt/secure-drop
docker-compose exec -T app php artisan migrate --force
ENDSSH

echo -e "${BLUE} Optimizing...${NC}"
ssh ${VPS_USER}@${VPS_HOST} << 'ENDSSH'
cd /opt/secure-drop
docker-compose exec -T app php artisan config:cache
docker-compose exec -T app php artisan route:cache
docker-compose exec -T app php artisan view:cache
ENDSSH

echo -e "${GREEN} Deployment complete!${NC}"