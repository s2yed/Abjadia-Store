# Deploying Abjadia Store to Koyeb

This guide explains how to deploy the Abjadia Store Docker image to Koyeb.

## Prerequisites

-   Docker image pushed to Docker Hub: `s2yed/abjadia-store:latest`
-   Koyeb account (free tier available)

## Deployment Steps

### 1. Login to Koyeb

Go to [https://app.koyeb.com](https://app.koyeb.com) and sign in.

### 2. Create a New Service

1. Click **"Create Service"**
2. Select **"Docker"** as the deployment method
3. Enter the Docker image: `s2yed/abjadia-store:latest`

### 3. Configure the Service

#### Basic Settings

-   **Service name**: `abjadia-store`
-   **Region**: Choose the closest region to your users
-   **Instance type**: `nano` (free tier) or higher

#### Environment Variables

Add the following environment variables:

```
APP_NAME=Abjadia Store
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.koyeb.app

DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/database.sqlite

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

SEED_DATABASE=true
```

#### Port Configuration

-   **Port**: `80`
-   **Protocol**: HTTP

#### Health Check (Optional but Recommended)

-   **Path**: `/`
-   **Port**: `80`
-   **Initial delay**: `30` seconds

### 4. Deploy

Click **"Deploy"** and wait for the deployment to complete (usually 2-3 minutes).

### 5. Access Your Application

Once deployed, Koyeb will provide you with a URL like:

```
https://abjadia-store-your-org.koyeb.app
```

## Important Notes

### Database Persistence

⚠️ **Warning**: Koyeb's free tier does not persist data between deployments. For production use, consider:

1. **Using External Database**: Configure MySQL/PostgreSQL from a provider like:

    - PlanetScale (MySQL)
    - Supabase (PostgreSQL)
    - Railway (PostgreSQL/MySQL)

2. **Using Koyeb Volumes** (Paid feature):
    - Mount a persistent volume to `/var/www/html/database`
    - Mount a persistent volume to `/var/www/html/storage`

### Updating the Application

To update your application:

1. Build and push a new Docker image:

    ```bash
    docker build -t s2yed/abjadia-store:latest .
    docker push s2yed/abjadia-store:latest
    ```

2. In Koyeb dashboard:
    - Go to your service
    - Click **"Redeploy"**
    - Koyeb will pull the latest image

### Custom Domain

To use a custom domain:

1. Go to your service settings
2. Click **"Domains"**
3. Add your custom domain
4. Update your DNS records as instructed

### Scaling

Koyeb supports automatic scaling:

-   **Horizontal scaling**: Add more instances
-   **Vertical scaling**: Upgrade instance type

## Troubleshooting

### Application Not Starting

Check the logs in Koyeb dashboard:

1. Go to your service
2. Click **"Logs"**
3. Look for error messages

Common issues:

-   Missing environment variables
-   Database connection errors
-   Port configuration mismatch

### Database Issues

If you see database errors:

1. Ensure `SEED_DATABASE=true` is set for first deployment
2. Check that SQLite path is correct
3. Consider using an external database for production

### Performance Issues

If the app is slow:

1. Upgrade to a larger instance type
2. Enable caching (Redis)
3. Use a CDN for static assets

## Production Recommendations

For a production deployment:

1. **Use External Database**:

    ```
    DB_CONNECTION=mysql
    DB_HOST=your-db-host.com
    DB_PORT=3306
    DB_DATABASE=abjadia_store
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```

2. **Enable Redis** (if available):

    ```
    CACHE_DRIVER=redis
    SESSION_DRIVER=redis
    REDIS_HOST=your-redis-host
    REDIS_PASSWORD=your-redis-password
    REDIS_PORT=6379
    ```

3. **Configure Email**:

    ```
    MAIL_MAILER=smtp
    MAIL_HOST=smtp.mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME=your_username
    MAIL_PASSWORD=your_password
    ```

4. **Set Strong APP_KEY**: The image generates one automatically, but you can set your own:
    ```bash
    php artisan key:generate --show
    ```

## Cost Estimation

-   **Free Tier**: 1 nano instance, limited resources
-   **Starter**: ~$5/month for small instance
-   **Production**: ~$20-50/month with database and scaling

## Support

For issues specific to Koyeb deployment:

-   [Koyeb Documentation](https://www.koyeb.com/docs)
-   [Koyeb Community](https://community.koyeb.com)

For application issues:

-   Check application logs
-   Review Laravel logs in `storage/logs/`

---

**Happy Deploying! 🚀**
