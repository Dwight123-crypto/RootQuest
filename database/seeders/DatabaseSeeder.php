<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Challenge;
use App\Models\Hint;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::factory()->admin()->create([
            'name' => 'admin',
            'email' => 'admin@ctf.local',
        ]);

        // Create teams
        $teams = collect([
            'Alpha Squad',
            'Cyber Warriors',
            'Binary Breakers',
            'Hack The Planet',
        ])->map(fn ($name) => Team::create(['name' => $name]));

        // Create regular users with teams
        $teams->each(function ($team) {
            User::factory()
                ->count(rand(2, 4))
                ->withTeam($team)
                ->withScore(rand(0, 500))
                ->create();
        });

        // Create some users without teams
        User::factory()->count(5)->withScore(rand(0, 300))->create();

        // Create categories and challenges
        $categoryData = [
            'Web' => [
                ['title' => 'SQL Injection 101', 'points' => 100, 'description' => 'Find the vulnerability in the login form.'],
                ['title' => 'XSS Challenge', 'points' => 150, 'description' => 'Execute JavaScript in the victim\'s browser.'],
                ['title' => 'Cookie Monster', 'points' => 200, 'description' => 'Steal the admin\'s session cookie.'],
            ],
            'Crypto' => [
                ['title' => 'Caesar\'s Secret', 'points' => 100, 'description' => 'Decrypt the ancient cipher.'],
                ['title' => 'RSA Weak Key', 'points' => 250, 'description' => 'Factor the weak RSA modulus.'],
                ['title' => 'Hash Cracking', 'points' => 150, 'description' => 'Find the plaintext from the hash.'],
            ],
            'Pwn' => [
                ['title' => 'Buffer Overflow', 'points' => 200, 'description' => 'Exploit the vulnerable binary.'],
                ['title' => 'Format String', 'points' => 300, 'description' => 'Leak memory using format strings.'],
            ],
            'Forensics' => [
                ['title' => 'Memory Dump', 'points' => 150, 'description' => 'Analyze the memory dump to find the flag.'],
                ['title' => 'Packet Analysis', 'points' => 200, 'description' => 'Find the hidden data in the PCAP file.'],
            ],
            'Misc' => [
                ['title' => 'Welcome Challenge', 'points' => 50, 'description' => 'A warmup challenge to get you started. The flag is: FLAG{welcome_to_ctf}'],
                ['title' => 'QR Quest', 'points' => 100, 'description' => 'Decode the QR code to find the flag.'],
            ],
        ];

        foreach ($categoryData as $categoryName => $challenges) {
            $category = Category::create(['name' => $categoryName]);

            foreach ($challenges as $challengeData) {
                $challenge = Challenge::create([
                    'category_id' => $category->id,
                    'title' => $challengeData['title'],
                    'description' => $challengeData['description'],
                    'flag' => 'FLAG{' . strtolower(str_replace(' ', '_', $challengeData['title'])) . '_' . rand(1000, 9999) . '}',
                    'points' => $challengeData['points'],
                ]);

                // Add 1-2 hints per challenge
                Hint::factory()
                    ->count(rand(1, 2))
                    ->forChallenge($challenge)
                    ->create();
            }
        }
    }
}
