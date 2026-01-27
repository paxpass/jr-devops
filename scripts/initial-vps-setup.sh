#!/bin/bash
set -e

echo " Initial VPS Setup..."

sudo apt-get update
sudo apt-get upgrade -y

echo " Installing Docker..."
curl -fsSL https://get.docker.com -o get-docker.sh
sh get-docker.sh
sudo usermod -aG docker $USER

echo " Installing Docker Compose..."
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

echo " Creating Traefik network..."
docker network create traefik-public || true

echo " Setup complete!"
echo "Next: Deploy Traefik, then your application"