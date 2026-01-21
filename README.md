# RootQuest CTF Platform

A full-featured Capture The Flag (CTF) platform built with Laravel, designed for hosting cybersecurity competitions with team support, scoring systems, and comprehensive admin controls.

## Features

### For Players
- **Challenge System** - Solve challenges across multiple categories (Web, Crypto, Pwn, Forensics, Misc)
- **Team Support** - Create or join teams (max 4 members) or compete individually
- **Hints System** - Unlock hints for challenges by spending points
- **Leaderboards** - Track rankings for both users and teams
- **File Downloads** - Download challenge files when available
- **Profile Management** - View your stats, solve history, and team info

### For Admins
- **Dashboard** - Overview of platform statistics
- **Challenge Management** - Create, edit, and delete challenges with file uploads
- **Category Management** - Organize challenges into categories
- **Hint Management** - Add hints with point costs to challenges
- **Team Management** - Create and manage teams
- **User Management** - Manage users and assign admin roles
- **Activity Logs** - Full audit trail of all user and admin actions

## Requirements

- PHP 8.2+
- Composer
- Node.js & npm
- MySQL/MariaDB (or SQLite for development)

## Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd rootquest
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Environment Setup

Copy the example environment file and configure it:

```bash
cp .env.example .env
```

Update the following in your `.env` file:

```env
APP_NAME="RootQuest"
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rootquest
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Run Database Migrations

```bash
php artisan migrate
```

### 7. Seed the Database (Optional)

To populate the database with demo data including challenges, teams, and users:

```bash
php artisan db:seed
```

This creates:
- Admin user: `admin@ctf.local` (password: `password`)
- 4 teams with 2-4 members each
- 5 independent users
- 5 categories with 13 challenges
- Hints for each challenge

### 8. Build Frontend Assets

```bash
npm run build
```

For development with hot reloading:

```bash
npm run dev
```

### 9. Start the Server

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # Admin panel controllers
│   │   ├── ChallengeController.php
│   │   ├── HomeController.php
│   │   ├── ProfileController.php
│   │   └── ScoreboardController.php
│   └── Middleware/
│       └── EnsureIsAdmin.php
├── Models/
│   ├── User.php
│   ├── Team.php
│   ├── Category.php
│   ├── Challenge.php
│   ├── Hint.php
│   ├── SolvedChallenge.php
│   └── UserHint.php
├── Services/
│   ├── ChallengeService.php
│   ├── ScoreboardService.php
│   ├── TeamService.php
│   └── FileUploadService.php
├── DTOs/
│   ├── SubmissionResult.php
│   └── HintUnlockResult.php
└── Repositories/
    └── ...

resources/
├── views/
│   ├── layouts/            # App and guest layouts
│   ├── components/         # Blade components
│   ├── auth/               # Authentication views
│   ├── admin/              # Admin panel views
│   └── ...
└── css/
    └── app.css             # Tailwind CSS

database/
├── migrations/             # Database schema
└── seeders/                # Demo data seeders
```

## Database Schema

### Core Tables

| Table | Description |
|-------|-------------|
| `users` | User accounts with team association, score, and admin flag |
| `teams` | Teams with unique names (max 4 members) |
| `categories` | Challenge categories |
| `challenges` | Challenges with points, flags, and optional files |
| `hints` | Hints with point costs |
| `solved_challenges` | Tracks user solutions (pivot table) |
| `user_hints` | Tracks unlocked hints (pivot table) |
| `activity_log` | Audit trail of all actions |

### Relationships

```
User ─┬─> Team (belongs to, optional)
      ├─> SolvedChallenges (many-to-many)
      └─> UnlockedHints (many-to-many)

Team ──> Users (has many members)

Category ──> Challenges (has many)

Challenge ─┬─> Category (belongs to)
           ├─> Hints (has many)
           └─> Solvers (many-to-many with users)

Hint ─┬─> Challenge (belongs to)
      └─> UnlockedBy (many-to-many with users)
```

## How It Works

### Scoring System

1. **Earning Points** - Solve challenges to earn their point value
2. **Spending Points** - Unlock hints by spending points from your score
3. **Team Scoring** - Team scores are the sum of all non-admin member scores

### Team Mechanics

- Teams can have 1-4 members
- When any team member solves a challenge, it counts as solved for the entire team
- Team rankings are based on cumulative team score
- Users can be independent (no team) or join an existing team

### Flag Submission

- Flags are case-sensitive
- Submissions are rate-limited (10 per minute)
- Correct submissions award points and log the solve
- Incorrect submissions are logged for admin review

### Hint System

- Each challenge can have multiple hints
- Hints have individual point costs
- Once unlocked, hints remain accessible
- Points are permanently deducted on unlock

## Admin Panel

Access the admin panel at `/admin` (requires admin role).

### Creating Challenges

1. Navigate to Admin > Challenges
2. Click "Create Challenge"
3. Fill in:
   - Title and description
   - Category
   - Flag (the answer)
   - Points value
   - Optional file upload
4. Add hints after creation

### Managing Users

1. Navigate to Admin > Users
2. Toggle admin status for users
3. View user details and activity

### Viewing Logs

1. Navigate to Admin > Logs
2. View all platform activity including:
   - Flag submissions (correct and incorrect)
   - Hint unlocks
   - File downloads
   - Admin actions

## Configuration

### Environment Variables

| Variable | Description | Default |
|----------|-------------|---------|
| `APP_NAME` | Application name | RootQuest |
| `DB_CONNECTION` | Database driver | mysql |
| `FILESYSTEM_DISK` | File storage disk | local |

### Rate Limiting

Flag submissions are limited to 10 per minute per user. Configure in `app/Providers/AppServiceProvider.php`.

## Technology Stack

- **Backend**: Laravel 11
- **Frontend**: Blade templates with Tailwind CSS
- **Database**: MySQL/MariaDB/SQLite
- **Authentication**: Laravel Breeze
- **Activity Logging**: Spatie Laravel Activity Log
- **Build Tool**: Vite

## Development

### Running Tests

```bash
php artisan test
```

### Code Formatting

```bash
./vendor/bin/pint
```

### Cache Management

```bash
# Clear all caches
php artisan optimize:clear

# Cache for production
php artisan optimize
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests
5. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
