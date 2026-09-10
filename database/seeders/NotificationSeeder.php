<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NotificationSeeder extends Seeder
{
    private const TYPE = 'Database\\Seeders\\NotificationSeeder';

    public function run(): void
    {
        $now = now();
        $notifications = [
            [
                'title' => 'New Application',
                'message' => 'Aarav Sharma applied for Senior Laravel Architect.',
            ],
            [
                'title' => 'Task Submitted',
                'message' => 'Aarav Sharma submitted the queue pipeline assessment.',
            ],
            [
                'title' => 'Interview Reminder',
                'message' => 'Upcoming interview with Aditya Rao tomorrow at 11:30 AM IST.',
            ],
        ];

        foreach (User::query()->pluck('id') as $userId) {
            DB::table('notifications')
                ->where('type', self::TYPE)
                ->where('notifiable_type', User::class)
                ->where('notifiable_id', $userId)
                ->delete();

            DB::table('notifications')->insert(array_map(
                fn (array $notification): array => [
                    'id' => (string) Str::uuid(),
                    'type' => self::TYPE,
                    'notifiable_type' => User::class,
                    'notifiable_id' => $userId,
                    'data' => json_encode($notification),
                    'read_at' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                $notifications
            ));
        }
    }
}
