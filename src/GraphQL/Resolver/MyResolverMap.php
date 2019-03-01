<?php

namespace App\GraphQL\Resolver;

use App\GraphQL\Mutation\CreatePostMutation;
use GraphQL\Type\Definition\ResolveInfo;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Resolver\ResolverMap;
use Overblog\GraphQLBundle\Upload\Type\GraphQLUploadType;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MyResolverMap extends ResolverMap
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    protected function map()
    {
        return [
//            'MyUpload' => [self::SCALAR_TYPE => function () { return new GraphQLUploadType(); }],
            'Query' => [
                self::RESOLVE_FIELD => function ($value, Argument $args, \ArrayObject $context, ResolveInfo $info) {
//                    dump($value, $args, $context, $info);

//                    if ($info->fieldName === 'player') {
//                        $id = $args->offsetGet('id');
//
//                        return "Player with id $id!";
//                    }
//
//                    if ($info->fieldName === 'comments') {
//                        return [
//                            [
//                                'text' => 'comment1'
//                            ],
//                            [
//                                'text' => 'comment2'
//                            ]
//                        ];
//                    }
//
//                    if ($info->fieldName === 'hello') {
//                        return 'Hello';
//                    }

                    return null;
                },
                'paginatedPosts' => [PostsResolver::class, 'getPosts'],
            ],
            'Mutation' => [
                'createPost' => [new CreatePostMutation($this->container), 'createPost'],
            ],
        ];
    }
}
