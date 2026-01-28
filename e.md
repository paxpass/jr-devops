Complete Setup Instructions
Step 1: Create Laravel Project
composer create-project laravel/laravel secure-drop
cd secure-drop
Step 2: Copy All Files
Copy each file from the sections above into your project following the exact directory structure.

Step 3: Setup Environment
cp .env.local.example .env
php artisan key:generate
Step 4: Install Pint
composer require laravel/pint --dev
Step 5: Make Scripts Executable
chmod +x scripts/*.sh
Step 6: Start Development
docker-compose up -d
docker-compose exec app php artisan migrate
Step 7: Test Locally
curl http://localhost:8000/api/health
Step 8: Setup GitHub Secrets
VPS_HOST - Your VPS IP
VPS_USERNAME - SSH username
VPS_SSH_KEY - Private SSH key
Step 9: Deploy to Production
On your VPS:

./scripts/initial-vps-setup.sh
docker network create traefik-public
cd /opt && git clone your-repo secure-drop
cd secure-drop && cp .env.example .env
nano .env # Update with production values
docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --force
Step 10: Verify Deployment
curl https://paxform-drop.duckdns.org/api/health

