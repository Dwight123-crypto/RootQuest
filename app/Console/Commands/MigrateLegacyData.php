<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Challenge;
use App\Models\Hint;
use App\Models\Team;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MigrateLegacyData extends Command
{
    protected $signature = 'migrate:legacy
                            {--connection=legacy : The database connection for the legacy database}
                            {--dry-run : Run without making changes}';

    protected $description = 'Migrate data from the legacy PHP CTF platform to Laravel';

    private bool $dryRun = false;

    public function handle(): int
    {
        $this->dryRun = $this->option('dry-run');
        $connection = $this->option('connection');

        $this->info('Starting legacy data migration...');

        if ($this->dryRun) {
            $this->warn('Running in dry-run mode. No changes will be made.');
        }

        try {
            DB::beginTransaction();

            $this->migrateTeams($connection);
            $this->migrateUsers($connection);
            $this->migrateCategories($connection);
            $this->migrateChallenges($connection);
            $this->migrateHints($connection);
            $this->migrateSolvedChallenges($connection);
            $this->migrateUserHints($connection);

            if ($this->dryRun) {
                DB::rollBack();
                $this->info('Dry run complete. No changes were made.');
            } else {
                DB::commit();
                $this->info('Migration completed successfully!');
            }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Migration failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    private function migrateTeams(string $connection): void
    {
        $this->info('Migrating teams...');

        $teams = DB::connection($connection)->table('teams')->get();

        $bar = $this->output->createProgressBar($teams->count());

        foreach ($teams as $team) {
            if (!$this->dryRun) {
                Team::create([
                    'id' => $team->id,
                    'name' => $team->name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Migrated {$teams->count()} teams.");
    }

    private function migrateUsers(string $connection): void
    {
        $this->info('Migrating users...');

        $users = DB::connection($connection)->table('users')->get();

        $bar = $this->output->createProgressBar($users->count());

        foreach ($users as $user) {
            if (!$this->dryRun) {
                User::create([
                    'id' => $user->id,
                    'name' => $user->username,
                    'email' => $user->email,
                    'password' => $user->password, // Already hashed
                    'team_id' => $user->team_id,
                    'score' => $user->score ?? 0,
                    'is_admin' => $user->is_admin ?? false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Migrated {$users->count()} users.");
    }

    private function migrateCategories(string $connection): void
    {
        $this->info('Migrating categories...');

        $categories = DB::connection($connection)->table('categories')->get();

        $bar = $this->output->createProgressBar($categories->count());

        foreach ($categories as $category) {
            if (!$this->dryRun) {
                Category::create([
                    'id' => $category->id,
                    'name' => $category->name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Migrated {$categories->count()} categories.");
    }

    private function migrateChallenges(string $connection): void
    {
        $this->info('Migrating challenges...');

        $challenges = DB::connection($connection)->table('challenges')->get();

        $bar = $this->output->createProgressBar($challenges->count());

        foreach ($challenges as $challenge) {
            $filePath = null;

            // Copy challenge files if they exist
            if (!empty($challenge->file_path) && !$this->dryRun) {
                $oldPath = base_path('../acdctf/' . $challenge->file_path);
                if (file_exists($oldPath)) {
                    $newPath = 'challenges/' . basename($challenge->file_path);
                    Storage::disk('local')->put($newPath, file_get_contents($oldPath));
                    $filePath = $newPath;
                }
            }

            if (!$this->dryRun) {
                Challenge::create([
                    'id' => $challenge->id,
                    'category_id' => $challenge->category_id,
                    'title' => $challenge->title,
                    'description' => $challenge->description,
                    'flag' => $challenge->flag,
                    'points' => $challenge->points,
                    'file_path' => $filePath,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Migrated {$challenges->count()} challenges.");
    }

    private function migrateHints(string $connection): void
    {
        $this->info('Migrating hints...');

        $hints = DB::connection($connection)->table('hints')->get();

        $bar = $this->output->createProgressBar($hints->count());

        foreach ($hints as $hint) {
            if (!$this->dryRun) {
                Hint::create([
                    'id' => $hint->id,
                    'challenge_id' => $hint->challenge_id,
                    'content' => $hint->content,
                    'cost' => $hint->cost,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Migrated {$hints->count()} hints.");
    }

    private function migrateSolvedChallenges(string $connection): void
    {
        $this->info('Migrating solved challenges...');

        $solved = DB::connection($connection)->table('solved_challenges')->get();

        $bar = $this->output->createProgressBar($solved->count());

        foreach ($solved as $record) {
            if (!$this->dryRun) {
                DB::table('solved_challenges')->insert([
                    'user_id' => $record->user_id,
                    'challenge_id' => $record->challenge_id,
                    'solved_at' => $record->solved_at ?? now(),
                ]);
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Migrated {$solved->count()} solved challenge records.");
    }

    private function migrateUserHints(string $connection): void
    {
        $this->info('Migrating user hints...');

        $userHints = DB::connection($connection)->table('user_hints')->get();

        $bar = $this->output->createProgressBar($userHints->count());

        foreach ($userHints as $record) {
            if (!$this->dryRun) {
                DB::table('user_hints')->insert([
                    'user_id' => $record->user_id,
                    'hint_id' => $record->hint_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Migrated {$userHints->count()} user hint records.");
    }
}
