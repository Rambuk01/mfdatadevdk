# Helper Commands

## Docker (Local Development)

```bash
# Start local environment
docker compose up -d

# Stop local environment
docker compose down

# View logs
docker compose logs -f web

# Install WordPress (first time only)
docker compose exec web bash docker/setup-wordpress.sh

# Activate headless theme (first time only)
docker compose exec web cp -r /var/www/html/cms-theme /var/www/html/cms/wp-content/themes/mfdd-headless

# Shell into web container
docker compose exec web bash

# Rebuild container (after changing Dockerfile)
docker compose up -d --build

# Reset everything (removes DB data)
docker compose down -v
```

## Local URLs

```
http://localhost:8000/           # Frontend
http://localhost:8000/cms/wp-admin  # WordPress admin
http://localhost:8000/cms/wp-json/wp/v2/posts    # REST API: posts
http://localhost:8000/cms/wp-json/wp/v2/project  # REST API: projects
http://localhost:8000/cms/wp-json/wp/v2/pages    # REST API: pages
```

## Git

```bash
# Check status
git status

# Stage and commit
git add -A
git commit -m "description of change"

# Push to GitHub (triggers deploy)
git push

# Pull latest
git pull

# View recent commits
git log --oneline -10

# See what changed in last commit
git diff HEAD~1

# Create a feature branch
git checkout -b feature/my-feature

# Merge branch back to main
git checkout main
git merge feature/my-feature
git push
```

## GitHub CLI

```bash
# Check auth status
gh auth status

# View repo in browser
gh repo view --web

# Create a pull request
gh pr create --title "Title" --body "Description"

# List open PRs
gh pr list

# View GitHub Actions runs
gh run list

# Watch a running deploy
gh run watch
```

## Composer (PHP Dependencies)

```bash
# Install dependencies
composer install

# Add a new package
composer require vendor/package

# Update all packages
composer update

# Autoload after adding new classes
composer dump-autoload
```

## Cache Management

```bash
# Clear Twig template cache
rm -rf cache/twig/*

# Clear API response cache
rm -rf cache/api/*

# Clear all caches
rm -rf cache/*/*
```

## Useful Debugging

```bash
# Test if a route works
curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/blog

# View full HTML output of a page
curl -s http://localhost:8000/

# Check WordPress REST API response
curl -s http://localhost:8000/cms/wp-json/wp/v2/posts | python3 -m json.tool

# Check PHP syntax of a file
php -l index.php

# Run PHP built-in server (without Docker)
php -S localhost:8000
```

## Deploy to Production

```bash
# Deploy happens automatically on push to main
git push origin main

# Manually check deploy status
gh run list --limit 5

# SSH into simply.com (if needed)
ssh user@your-server.simply.com
```

## SSH Key Setup (for GitHub Actions deploy)

```bash
# Generate deploy key
ssh-keygen -t ed25519 -f ~/.ssh/simply_deploy -C "github-actions-deploy"

# Copy public key (add to simply.com)
cat ~/.ssh/simply_deploy.pub

# Copy private key (add to GitHub Secrets as SSH_PRIVATE_KEY)
cat ~/.ssh/simply_deploy

# GitHub Secrets to configure:
# SSH_PRIVATE_KEY  - private key content
# SSH_HOST         - simply.com server hostname
# SSH_USER         - SSH username
# SERVER_PATH      - e.g. /home/username/public_html
```
