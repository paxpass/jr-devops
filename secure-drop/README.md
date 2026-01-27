# Paxform Secure Drop 🔐

A secure, burn-on-read secret sharing service built with Laravel.

## 🌐 Live Demo

**Production URL:** https://paxform-drop.duckdns.org  
**API Docs:** https://paxform-drop.duckdns.org/docs

## ✨ Features

- 🔒 End-to-end encryption using Laravel Crypt
- 🔥 Burn-on-read: secrets are deleted after viewing
- ⏱️ Optional TTL (Time To Live) support
- 🆔 UUID-based secure, non-sequential IDs
- 🏗️ Clean Service-Repository architecture
- 🐳 Production-ready Docker setup
- 🚀 Automated CI/CD with GitHub Actions
- 🌐 Traefik reverse proxy with auto-SSL

## 🚀 Quick Start

### Local Development

```bash
# Clone repository
git clone https://github.com/your-username/secure-drop.git
cd secure-drop

# Setup environment
cp .env.local.example .env
composer install

# Start services (one command!)
make dev

# Access at http://localhost:8000
```

### Production Deployment

```bash
# On your VPS
./scripts/initial-vps-setup.sh

# Deploy application
docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d

# Run migrations
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --force
```

## 📡 API Usage

### Create a Secret

```bash
curl -X POST https://paxform-drop.duckdns.org/api/v1/secrets \
  -H "Content-Type: application/json" \
  -d '{
    "content": "My secret password",
    "ttl": 60
  }'
```

**Response:**
```json
{
  "id": "9c8f1234-5678-90ab-cdef-1234567890ab",
  "url": "https://paxform-drop.duckdns.org/api/v1/secrets/9c8f1234...",
  "expires_at": "2026-01-27T15:30:00Z"
}
```

### Retrieve Secret (Burns After Read)

```bash
curl https://paxform-drop.duckdns.org/api/v1/secrets/9c8f1234-5678-90ab-cdef-1234567890ab
```

**Response:**
```json
{
  "content": "My secret password",
  "message": "This secret has been permanently deleted"
}
```

## 🏗️ Architecture

### Service-Repository Pattern

```
Controller → Service → Repository → Model
```

- **Controller**: HTTP request handling
- **Service**: Business logic
- **Repository**: Data access layer
- **Model**: Database representation

### Tech Stack

- **Backend**: Laravel 11, PHP 8.2
- **Database**: PostgreSQL 15
- **Cache**: Redis 7
- **Reverse Proxy**: Traefik 2.10
- **Container**: Docker, Docker Compose
- **CI/CD**: GitHub Actions

## 🐳 Docker Architecture

### Multi-stage Build

Stage 1 (Builder):
- Installs dependencies
- Builds optimized autoloader

Stage 2 (Production):
- Minimal Alpine image
- Non-root user (laravel:laravel)
- Nginx + PHP-FPM + Supervisor
- Size: ~100MB

### Docker Compose Strategy

- `docker-compose.yml`: Base configuration
- `docker-compose.override.yml`: Dev overrides (auto-loaded)
- `docker-compose.prod.yml`: Production overrides

## 🔐 Security Features

- Encrypted content storage
- Non-sequential UUID IDs
- Security headers via Traefik
- Non-root container user
- Vulnerability scanning with Trivy
- HTTPS with Let's Encrypt

## 📊 CI/CD Pipeline

1. **Lint & Test**: Pint + PHPUnit
2. **Security Scan**: Trivy vulnerability check
3. **Build & Push**: Docker image to GHCR
4. **Deploy**: Automated VPS deployment
5. **Health Check**: Post-deployment verification

## 🛠️ Development Commands

```bash
make install    # Install dependencies
make dev        # Start development
make test       # Run tests
make lint       # Run code linter
make prod       # Deploy to production
make clean      # Clean containers
```

## 📝 Environment Variables

Copy `.env.example` to `.env` and configure:

- `APP_KEY`: Laravel application key
- `DB_PASSWORD`: Database password
- `REDIS_PASSWORD`: Redis password
- `APP_URL`: Your domain URL

## 🚦 Health Check

```bash
curl https://paxform-drop.duckdns.org/api/health
```

## 📄 License

MIT License

## 👨‍💻 Author

Built for Paxform DevOps Challenge