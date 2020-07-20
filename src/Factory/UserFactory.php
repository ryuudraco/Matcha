<?php 

namespace Src\Factory;
use Slim\Http\Request;
use Slim\Http\Response;
use Src\Utils\DB;
use Src\DAO\UserDAO;
use Src\Utils\Crypt;

class UserFactory {

    public function test() {
        $noUsersGenerated = 10;

        $hobbies = [
            '3D printing',
            'amateur radio',
            'scrapbook',
            'Amateur radio',
            'Acting',
            'Baton twirling',
            'Board games',
            'Book restoration',
            'Cabaret',
            'Calligraphy',
            'Candle making',
            'Computer programming',
            'Coffee roasting',
            'Cooking',
            'Coloring',
            'Cosplaying',
            'Couponing',
            'Creative writing',
            'Crocheting',
            'Cryptography',
            'Dance',
            'Digital arts',
            'Drama',
            'Drawing',
            'Do it yourself',
            'Electronics',
            'Embroidery',
            'Fashion',
            'Flower arranging',
            'Foreign language learning',
            'Gaming',
            'tabletop games',
            'role-playing games',
            'Gambling',
            'Genealogy',
            'Glassblowing',
            'Gunsmithing',
            'Homebrewing',
            'Ice skating',
            'Jewelry making',
            'Jigsaw puzzles',
            'Juggling',
            'Knapping',
            'Knitting',
            'Kabaddi',
            'Knife making',
            'Lacemaking',
            'Lapidary',
            'Leather crafting',
            'Lego building',
            'Lockpicking',
            'Machining',
            'Macrame',
            'Metalworking',
            'Magic',
            'Model building',
            'Listening to music',
            'Origami',
            'Painting',
            'Playing musical instruments',
            'Pet',
            'Poi',
            'Pottery',
            'Puzzles',
            'Quilting',
            'Reading',
            'Scrapbooking',
            'Sculpting',
            'Sewing',
            'Singing',
            'Sketching',
            'Soapmaking',
            'Sports',
            'Stand-up comedy',
            'Sudoku',
            'Table tennis',
            'Taxidermy',
            'Video gaming',
            'Watching movies',
            'Web surfing',
            'Whittling',
            'Wood carving',
            'Woodworking',
            'Worldbuilding',
            'Writing',
            'Yoga',
            'Yo-yoing',
            'Air sports',
            'Archery',
            'Astronomy',
            'Backpacking',
            'BASE jumping',
            'Baseball',
            'Basketball',
            'Beekeeping',
            'Bird watching',
            'Blacksmithing',
            'Board sports',
            'Bodybuilding',
            'Brazilian jiu-jitsu',
            'Community',
            'Cycling',
            'Dowsing',
            'Driving',
            'Fishing',
            'Flag Football',
            'Flying',
            'Flying disc',
            'Foraging',
            'Gardening',
            'Geocaching',
            'Ghost hunting',
            'Graffiti',
            'Handball',
            'Hiking',
            'Hooping',
            'Horseback riding',
            'Hunting',
            'Inline skating',
            'Jogging',
            'Kayaking',
            'Kite flying',
            'Kitesurfing',
            'LARPing',
            'Letterboxing',
            'Metal detecting',
            'Motor sports',
            'Mountain biking',
            'Mountaineering',
            'Mushroom hunting',
            'Mycology',
            'Netball',
            'Nordic skating',
            'Orienteering',
            'Paintball',
            'Parkour',
            'Photography',
            'Polo',
            'Rafting',
            'Rappelling',
            'Rock climbing',
            'Roller skating',
            'Rugby',
            'Running',
            'Sailing',
            'Sand art',
            'Scouting',
            'Scuba diving',
            'Sculling',
            'Rowing',
            'Shooting',
            'Shopping',
            'Skateboarding',
            'Skiing',
            'Skimboarding',
            'Skydiving',
            'Slacklining',
            'Snowboarding',
            'Stone skipping',
            'Surfing',
            'Swimming',
            'Taekwondo',
            'Tai chi',
            'Urban exploration',
            'Vacation',
            'Vehicle restoration',
            'Water sports'
        ];

        for($i = 0; $i <= $noUsersGenerated; $i++) {

            $json = file_get_contents('https://randomuser.me/api/');
            $arr = json_decode($json);
            $obj = $arr->results[0];


            //for the sake of nice data from 2 hobbies up to 5..
            $noHobbies = rand(2,5);
            $randhob = array_rand($hobbies, $noHobbies);

            $hobbyString = '';
            foreach($randhob as $index) {
                $hobbyString .= $hobbies[$index] . ', ';
            }

            $gender_id = 0;
            if($obj->gender == 'male') {
                $gender_id = 1;
            } else if($obj->gender == 'female') {
                $gender_id = 0;
            } else {
                $gender_id = 2;
            }

            $password = Crypt::hash($obj->login->password);

            DB::execute('
                INSERT INTO users (
                    username, 
                    password, 
                    email, 
                    first_name, 
                    last_name, 
                    created_at, 
                    updated_at,
                    city,
                    province,
                    country,
                    postal_code,
                    age,
                    gender_id,
                    interests,
                    ip_address
                ) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', 
                [
                    $obj->login->username,
                    $password,
                    $obj->email,
                    $obj->name->first,
                    $obj->name->last,
                    date('Y-m-d H:i:s'),
                    date('Y-m-d H:i:s'),
                    $obj->location->city,
                    $obj->location->state,
                    $obj->location->country,
                    $obj->location->postcode,
                    $obj->dob->age,
                    $gender_id,
                    rtrim($hobbyString, ', '),
                    '127.0.0.1'
                ]
            );

            echo "User number: " . $i . " generated: " . $obj->name->first . '<br />';
                    
        }

    }
}