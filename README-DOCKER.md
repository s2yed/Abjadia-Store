# Abjadia Store - Docker Deployment Guide

This guide explains how to build, run, and deploy the Abjadia Store using Docker.

## 📋 Prerequisites

-   Docker installed (version 20.10 or higher)
-   Docker Compose installed (version 2.0 or higher)
-   At least 2GB of free disk space

## 🚀 Quick Start

### 1. Build the Docker Image

```bash
docker build -t abjadia-store:latest .
```

### 2. Run with Docker Compose

```bash
docker-compose up -d
```

The application will be available at: **http://localhost**

### 3. Stop the Application

```bash
docker-compose down
```

## 🔧 Configuration

### Environment Variables

Create a `.env` file or set environment variables in `docker-compose.yml`:

```env
APP_NAME="Abjadia Store"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://localhost

DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/database.sqlite

# Optional: Enable database seeding on first run
SEED_DATABASE=true
```

### Using MySQL Instead of SQLite

Uncomment the MySQL service in `docker-compose.yml` and update the environment variables:

```yaml
environment:
    - DB_CONNECTION=mysql
    - DB_HOST=mysql
    - DB_PORT=3306
    - DB_DATABASE=abjadia_store
    - DB_USERNAME=abjadia
    - DB_PASSWORD=secret
```

## 📦 Building for Production

### Build the Image

```bash
docker build -t abjadia-store:1.0.0 .
```

### Tag for Registry

```bash
# For Docker Hub
docker tag abjadia-store:1.0.0 yourusername/abjadia-store:1.0.0
docker tag abjadia-store:1.0.0 yourusername/abjadia-store:latest

# For private registry
docker tag abjadia-store:1.0.0 registry.example.com/abjadia-store:1.0.0
```

### Push to Registry

```bash
# Login to Docker Hub
docker login

# Push the image
docker push yourusername/abjadia-store:1.0.0
docker push yourusername/abjadia-store:latest
```

## 🔐 Security Best Practices

1. **Never commit `.env` file** - Use environment variables or secrets management
2. **Change default passwords** - Update MySQL passwords in production
3. **Use HTTPS** - Configure a reverse proxy (Nginx/Traefik) with SSL certificates
4. **Regular updates** - Keep base images and dependencies up to date

## 📊 Monitoring and Logs

### View Logs

```bash
# All services
docker-compose logs -f

# Specific service
docker-compose logs -f app
```

### Access Container Shell

```bash
docker-compose exec app bash
```

### Run Artisan Commands

```bash
docker-compose exec app php artisan migrate
docker-compose exec app php artisan cache:clear
```

## 🗄️ Data Persistence

Data is persisted using Docker volumes:

-   **Storage**: `./storage` - User uploads and logs
-   **Database**: `./database/database.sqlite` - SQLite database file
-   **MySQL** (if enabled): `mysql-data` volume

### Backup

```bash
# Backup SQLite database
docker-compose exec app cp /var/www/html/database/database.sqlite /var/www/html/storage/backup.sqlite

# Backup storage
tar -czf storage-backup.tar.gz storage/
```

## 🌐 Deployment Options

### Option 1: Docker Compose (Simple)

Deploy on any server with Docker installed:

```bash
# Clone repository
git clone <repository-url>
cd abjadia-store

# Start services
docker-compose up -d
```

### Option 2: Docker Swarm (Scalable)

```bash
docker stack deploy -c docker-compose.yml abjadia
```

### Option 3: Kubernetes

Create Kubernetes manifests based on the Docker image.

### Option 4: Cloud Platforms

-   **AWS ECS/Fargate**: Use the Docker image with ECS task definitions
-   **Google Cloud Run**: Deploy the container directly
-   **Azure Container Instances**: Run the image on Azure
-   **DigitalOcean App Platform**: Deploy from Docker Hub

## 🔄 Updates and Maintenance

### Update Application

```bash
# Pull latest code
git pull

# Rebuild image
docker-compose build

# Restart services
docker-compose up -d
```

### Database Migrations

```bash
docker-compose exec app php artisan migrate --force
```

## 🐛 Troubleshooting

### Permission Issues

```bash
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chmod -R 775 /var/www/html/storage
```

### Clear Cache

```bash
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan view:clear
```

### Rebuild from Scratch

```bash
docker-compose down -v
docker-compose build --no-cache
docker-compose up -d
```

## 📝 Notes

-   The first startup may take a few minutes as it runs migrations and seeds the database
-   Default admin credentials are created during seeding (check `database/seeders/`)
-   For production, always set `APP_DEBUG=false` and use strong passwords
-   Consider using a CDN for static assets in production

## 🆘 Support

For issues and questions:

-   Check the logs: `docker-compose logs -f`
-   Review Laravel logs: `storage/logs/laravel.log`
-   Ensure all environment variables are set correctly

---

**Built with ❤️ for Abjadia Store**
