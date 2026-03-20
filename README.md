# 🚀 Secure Drop DevOps

A production-ready, containerized Secure Drop API built with Laravel 11, Docker, Traefik, and a full CI/CD pipeline.

This service allows users to securely share sensitive data (e.g., passwords, API keys) via a unique link that is **encrypted at rest** and **permanently deleted after a single read**.

---

## 🌐 Live Demo

- **Application:** http://3.90.33.65  
- **API Docs:** http://3.90.33.65/docs  

---

## 🔐 Core Features

- 🔒 AES-256 encryption using Laravel Crypt
- 🔑 UUID-based secret identifiers (non-sequential)
- 🔥 Burn-after-read (one-time access)
- ⏳ Optional TTL (time-to-live)
- 📦 Fully containerized (Docker multi-stage build)
- 🌐 Reverse proxy with Traefik
- ❤️ Health checks for service readiness
- 📄 Auto-generated API documentation (Scribe)
- ⚙️ CI/CD pipeline with GitHub Actions
- ☁️ Automated deployment to AWS EC2

---

## 🏗️ Architecture


Client → Traefik → Nginx → Laravel (PHP-FPM) → MySQL


---

## 🚀 Quick Start (Local Development)

### 1. Clone the repository

```bash
git clone https://github.com/NihiGabriel/jr-devops.git
cd secure-drop
2. Start containers
docker compose up -d --build
3. Run setup commands
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
4. Access services

App: http://secure-drop.localhost

API Docs: http://secure-drop.localhost/docs

Traefik Dashboard: http://localhost:8080

📂 Project Structure
app/
├── Services/
├── Repositories/
├── Interfaces/

docker/
├── nginx/
├── traefik/

.github/workflows/
├── main.yml
🧠 Architectural Decisions
1. Service-Repository Pattern

To enforce strict separation of concerns:

SecretRepository → Handles database interactions

SecretService → Handles business logic (encryption, burn-after-read)

SecretController → Handles HTTP requests/responses

2. Multi-File Docker Strategy

docker-compose.yml → Base services (Nginx, App, MySQL, Traefik)

docker-compose.override.yml → Local development (bind mounts, build context)

docker-compose.prod.yml → Production (pre-built images, restart policies)

3. Reverse Proxy (Traefik)

Dynamic routing via Docker labels

Centralized traffic management

Decouples infrastructure from application

4. Health Checks

Ensures services are ready before receiving traffic

Improves reliability and fault tolerance

🔒 Security Features

Encryption at rest using Laravel Crypt

UUID v4 prevents ID enumeration

Burn-after-read ensures data is not reusable

Containers run as non-root user (UID 1000)

Multi-stage Docker builds reduce attack surface

Docker image scanning via CI pipeline

🔄 CI/CD Pipeline

Implemented using GitHub Actions:

Code linting (Laravel Pint)

Automated tests (PHPUnit)

Docker image build with caching

Security scanning (Trivy)

Push to Docker Hub

Automated deployment to AWS EC2 via SSH

☁️ Deployment

The application is deployed on AWS EC2 using Docker Compose.

Deployment Flow
GitHub Push → Build → Scan → Push Image → SSH → Deploy
📖 API Endpoints
Create Secret
POST /api/v1/secrets
{
  "secret": "my-password",
  "ttl": 10
}
Retrieve Secret
GET /api/v1/secrets/{uuid}

Returns decrypted secret

Immediately deletes it (burn-after-read)

📄 API Documentation

Auto-generated using Scribe:

/docs
👨‍💻 Author

Gabriel Nihi
DevOps & Backend Engineer

....