# Zoho Books Integration
 
A Laravel + InertiaJS (React) application that connects to the Zoho Books API and displays a summary of your **Invoices** and **Bills**. This is a read-only integration — no data is written back to Zoho.
 
---
 
## Tech Stack
 
| Layer | Technology |
|---|---|
| Backend | Laravel 13, PHP 8.3 |
| Frontend | InertiaJS + React + TypeScript |
| Database | MySQL |
| Auth | Zoho OAuth 2.0 (India region) |
| HTTP Client | Laravel HTTP Facade |
 
---
 
## Features
 
- Connect your Zoho Books account via OAuth 2.0
- View Invoices list with status, amount, and due date
- View Bills list with vendor, status, and amount
- Auto token refresh — access token is silently renewed when expired
- Disconnect / re-connect Zoho account at any time
 
---
 
## Requirements
 
Before you begin, make sure you have:
 
- PHP 8.3+ with the following extensions: `pdo_mysql`, `mbstring`, `openssl`, `curl`
- Composer
- MySQL 8+
- Node.js 20+ and NPM
- A Zoho Books account (India region — `zoho.in`)
 
---
 
## Step 1 — Create a Zoho API Client
 
You need a Zoho OAuth client to get your `Client ID` and `Client Secret`.
 
1. Go to [https://api-console.zoho.in](https://api-console.zoho.in) and sign in with your Zoho account.
 
2. Click **"Add Client"** and choose **"Server-based Applications"**.
 
3. Fill in the details:
   - **Client Name** — anything, e.g. `Zoho Books Integration`
   - **Homepage URL** — `http://localhost:8000`
   - **Authorized Redirect URIs** — `http://localhost:8000/zoho/callback`
 
4. Click **Create**. You will see your **Client ID** and **Client Secret** — copy both.
 
5. Find your **Organization ID**:
   - Log in to [https://books.zoho.in](https://books.zoho.in)
   - Go to **Settings → Organization Profile**
   - Copy the **Organization ID** shown at the bottom of the page
 
---
 
## Step 2 — Clone and Install
 
```bash
git clone https://github.com/dsingh-dev/zoho-book-integration.git
cd zoho-book-integration
```
 
Install PHP dependencies:
 
```bash
composer install
```
 
Install frontend dependencies:
 
```bash
npm install
```
 
---
 
## Step 3 — Environment Setup
 
Copy the example environment file and generate your app key:
 
```bash
cp .env.example .env
php artisan key:generate
```
 
Open `.env` and fill in your database and Zoho credentials:
 
```env
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=zoho_integration
DB_USERNAME=root
DB_PASSWORD=
 
# Zoho OAuth (India region)
ZOHO_CLIENT_ID=your_client_id_here
ZOHO_CLIENT_SECRET=your_client_secret_here
ZOHO_REDIRECT_URI=http://localhost:8000/zoho/callback
ZOHO_ORGANIZATION_ID=your_organization_id_here
ZOHO_BASE_URL=https://www.zohoapis.in/books/v3
```
 
---
 
## Step 4 — Database Setup

Then run the migrations:
 
```bash
php artisan migrate
```
 
 > **Note:** If your database is not created then this command want's you to create it first. just press `y` and enter.
---
 
## Step 5 — Build Frontend Assets
 
For development (with hot reload):
 
```bash
npm run dev
```
 
For production build:
 
```bash
npm run build
```
 
---
 
## Step 6 — Run the Application
 
```bash
php artisan serve
```
 
Visit [http://localhost:8000](http://localhost:8000) in your browser.
 
---
 
## Connecting Your Zoho Account
 
Once the app is running:
 
1. Log in or register an account on the app.
2. You will be redirected to the dashboard with a **"Add Zoho Account"** button.
3. Click it — you will be redirected to Zoho's login page.
4. Authorize the app. Zoho will redirect you back to `/zoho/callback`.
5. Your tokens are saved. You can now view your **Invoices** and **Bills**.
 
> **Important:** When authorizing on Zoho, make sure you are logged in with the account that has access to the Zoho Books organization whose `ZOHO_ORGANIZATION_ID` you set in `.env`.
 
---
 
## License
 
MIT
