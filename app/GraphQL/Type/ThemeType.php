<?php

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use App\Models\Theme;
use Folklore\GraphQL\Support\Facades\GraphQL;
use Folklore\GraphQL\Support\Type as GraphQLType;

class ThemeType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Theme',
        'description' => 'An Anime Theme',
        'model' => Theme::class
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Entry Id'
            ],
            'anime_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Anime id'
            ],
            'song_name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Song Name'
            ],
            'isNSFW' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'The video contains risqué imagery and is not safe for work'
            ],
            'isSpoiler' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'The video contains spoilers'
            ],
            'theme' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Theme Type OP/ED'
            ],
            'ver_major' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Version Major'
            ],
            'ver_minor' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Version Minor'
            ],
            'episodes' => [
                'type' => Type::string(),
                'description' => 'Episodes with this theme'
            ],
            'notes' => [
                'type' => Type::string(),
                'description' => 'Theme Notes'
            ],

            'videos' => [
                'type' => Type::listOf(GraphQL::type('Video')),
                'description' => 'Theme Videos'
            ],
            'artist' => [
                'type' => GraphQL::type('Artist'),
                'description' => 'Theme Artist'
            ]
        ];
    }
}