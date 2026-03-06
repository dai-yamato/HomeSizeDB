<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Home;
use App\Models\Floor;
use App\Models\Room;
use App\Models\Location;
use App\Models\Measurement;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create a Test User
        $user = User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Create a Home
        $home = Home::create(['name' => 'マイホーム（代官山）']);
        $home2 = Home::create(['name' => '実家（長野）']);

        // 3. Link User to Home
        $user->homes()->attach($home->id, ['role' => 'owner']);
        $user->homes()->attach($home2->id, ['role' => 'viewer']);

        // 4. Create Floors
        $f1 = Floor::create(['home_id' => $home->id, 'name' => '1F']);
        $f2 = Floor::create(['home_id' => $home->id, 'name' => '2F']);

        // 5. Create Rooms for 1F
        $living = Room::create(['home_id' => $home->id, 'floor_id' => $f1->id, 'name' => 'リビング']);
        $kitchen = Room::create(['home_id' => $home->id, 'floor_id' => $f1->id, 'name' => 'キッチン']);
        $washroom = Room::create(['home_id' => $home->id, 'floor_id' => $f1->id, 'name' => '洗面所']);

        // 6. Create Rooms for 2F
        $bedroom = Room::create(['home_id' => $home->id, 'floor_id' => $f2->id, 'name' => '主寝室']);
        $closet = Room::create(['home_id' => $home->id, 'floor_id' => $f2->id, 'name' => 'ウォークインクローゼット']);

        // 7. Create Locations
        $loc1 = Location::create(['home_id' => $home->id, 'room_id' => $living->id, 'name' => 'テレビ台']);
        $loc2 = Location::create(['home_id' => $home->id, 'room_id' => $living->id, 'name' => 'ソファ']);
        $loc3 = Location::create(['home_id' => $home->id, 'room_id' => $kitchen->id, 'name' => '冷蔵庫']);
        $loc4 = Location::create(['home_id' => $home->id, 'room_id' => $closet->id, 'name' => '左側枕棚']);

        // 8. Create Measurements
        Measurement::create(['home_id' => $home->id, 'location_id' => $loc1->id, 'label' => '幅', 'value' => 1800]);
        Measurement::create(['home_id' => $home->id, 'location_id' => $loc1->id, 'label' => '奥行', 'value' => 450]);
        Measurement::create(['home_id' => $home->id, 'location_id' => $loc3->id, 'label' => '有効幅', 'value' => 700]);
        Measurement::create(['home_id' => $home->id, 'location_id' => $loc4->id, 'label' => '高さ', 'value' => 1800]);
        Measurement::create(['home_id' => $home->id, 'location_id' => $loc4->id, 'label' => '奥行', 'value' => 400]);

        $this->command->info('Demo data seeded. Login with: test@example.com / password');
    }
}
