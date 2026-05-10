<?php
require __DIR__ . '/../vendor/autoload.php';
use GuzzleHttp\Client;

$client = new Client();

$data = null;

try {
    $response = $client->request('POST', 'https://api.igdb.com/v4/multiquery', [
        'headers' => [
            'Accept' => 'application/json',
            'Client-ID' => '7iy7i96fkbaja119twdsukgwno3hpo',
            'Authorization' => 'Bearer 1j19r2tgjap0lrlp58oq529vs3p526',
            'Content-Type' => 'text/plain'
        ],
        'body' => 'query games "Game Info" {
            fields name, cover, total_rating, total_rating_count, genres,franchise;
            where id = (65676,69721,25623,247545,287848,43668,279661,358525,  132181,19686,347668, 1070,2180,3349, 200966,21062);
            limit 50;
        };

        query covers "Game Covers" {
        fields game, image_id, url, width, height;
        where game = (65676,69721,25623,247545,287848,43668,279661,358525,  132181,19686,347668, 1070,2180,3349, 200966,21062);
        limit 50;
        };

        query franchises "Game Franchises" {
        fields  id, name, slug, games;
        where games = (65676,69721,25623,247545,287848,43668,279661,358525,  132181,19686,347668, 1070,2180,3349, 200966,21062);
        limit 50;
        };

        query genres "Game Genres" {
            fields id, name, slug;
            where id = (2,4,5,8,9,10,11,12,13,14,15,16,24,25,26,30,31,32,33,34,35,36);
            limit 50;
        };'
    ]);
    $statusCode = $response->getStatusCode();
    if ($statusCode != 200) {
        http_response_code($statusCode);
        $data = ['error' => 'Unable to fetch game data'];
    } else {
        $result = json_decode($response->getBody(), true);

        $games = $result[0]['result'] ?? [];
        $covers = $result[1]['result'] ?? [];
        $franchises = $result[2]['result'] ?? [];
        $genres = $result[3]['result'] ?? [];

        $genresMap = [];
        //pour chaque jeu on lui associe son genre
        foreach ($genres as $genre) {
            $genresMap[$genre['id']] = $genre['name'];
        }

        $coversMap = [];

        //pour chaque jeu on lui associe son image
        foreach ($covers as $cover) {

            $coversMap[$cover['game']] =
                'https://images.igdb.com/igdb/image/upload/t_1080p/' .
                $cover['image_id'] .
                '.jpg';
        }

        //pour chaque jeu on lui associe sa franchise
        $franchiseMap = [];
        foreach ($franchises as $franchise) {

            foreach ($franchise['games'] as $gameId) {

                $output = match ($franchise['name']) {
                    "Mario" => "mario",
                    "Sonic The Hedgehog" => "sonic",
                    "Resident Evil" => "re",
                    default => "mh",


                };
                $franchiseMap[$gameId] = $output;
            }
        }

        $data = [];

        //pour chaque jeu on lui associe son attaque
        foreach ($games as $game) {

            $gameGenres = 1;

            if (isset($game['genres'])) {

                foreach ($game['genres'] as $genreId) {

                    if (isset($genresMap[$genreId])) {
                        $output = match ($genresMap[$genreId]) {
                            'Shooter' => 20,
                            'Platform' => 14,
                            "Puzzle" => 5,
                            "Real Time Strategy (RTS)" => 8,
                            "Role-playing (RPG)" => 12,
                            "Strategy" => 9,
                            "Turn-based strategy (TBS)" => 10,
                            "Tactical" => 11,
                            "Hack and slash/Beat \u0027em up" => 18,
                            "Adventure" => 13,
                            default => 1,


                        };
                        $gameGenres += $output;
                    }
                }
            }
            //pour chaque jeu, on regroupe toutes ces données dans la même structure
            $data[] = [
                'name' => $game['name'],

                'total_rating' => $game['total_rating'] ?? null,

                'genres' => $gameGenres,

                'franchise' => $franchiseMap[$game['id']] ?? null,

                'cover_url' => $coversMap[$game['id']] ?? null
            ];
        }
    }
} catch (Exception $e) {
    http_response_code(500);
    $data = ['error' => 'Unable to fetch data'];
    echo $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($data); ?>