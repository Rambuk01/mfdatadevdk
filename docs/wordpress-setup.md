# WordPress Setup Guide

## Local Development (Docker)

1. Start the environment:
   ```bash
   docker compose up -d
   ```

2. Install WordPress (first time only):
   ```bash
   docker compose exec web bash docker/setup-wordpress.sh
   ```

3. Complete WordPress setup at http://localhost:8000/cms/

4. Activate the headless theme:
   - Go to **Appearance > Themes** in WP admin
   - Copy the `cms-theme/` folder contents to `cms/wp-content/themes/mfdd-headless/`
   - Activate "MF Data Dev Headless"

   In Docker, run:
   ```bash
   docker compose exec web cp -r /var/www/html/cms-theme /var/www/html/cms/wp-content/themes/mfdd-headless
   ```
   Then activate in WP admin.

5. Your site is at http://localhost:8000/

## Production (Simply.com)

### 1. Install WordPress

- Log into Simply.com control panel
- Use 1-click WordPress install OR install manually:
  - Download WordPress and upload to `/public_html/cms/`
  - Create a MySQL database via control panel
  - Configure `wp-config.php` with database credentials

### 2. Activate Headless Theme

- Upload `cms-theme/` contents to `/public_html/cms/wp-content/themes/mfdd-headless/`
- Activate in WP admin

### 3. Configure Permalinks

- Go to **Settings > Permalinks**
- Select "Post name" (`/%postname%/`)
- Save (this ensures the REST API uses slugs)

### 4. Create Content

- **Pages**: Create "About" and "Contact" pages (use those exact slugs)
- **Posts**: Create blog posts as usual
- **Projects**: Use the "Projects" menu item to add portfolio items
  - Fill in Tech Stack, Live URL, and GitHub URL fields

### 5. GitHub Actions Deployment

Add these secrets to your GitHub repo (**Settings > Secrets and variables > Actions**):

| Secret | Value |
|--------|-------|
| `SSH_PRIVATE_KEY` | Your private SSH key (full content) |
| `SSH_HOST` | Your simply.com server hostname |
| `SSH_USER` | Your SSH username |
| `SERVER_PATH` | Path to document root (e.g., `/home/username/public_html`) |

Generate an SSH key pair:
```bash
ssh-keygen -t ed25519 -f ~/.ssh/simply_deploy -C "github-actions-deploy"
```

Add the public key to simply.com (control panel > SSH keys).
Add the private key content as `SSH_PRIVATE_KEY` in GitHub Secrets.

### 6. Test the REST API

Verify the API works:
```
https://yourdomain.dk/cms/wp-json/wp/v2/posts
https://yourdomain.dk/cms/wp-json/wp/v2/project
https://yourdomain.dk/cms/wp-json/wp/v2/pages
```
