<?php

namespace App\GraphQL\Resolver;

use GraphQL\Type\Definition\ResolveInfo;
use Overblog\GraphQLBundle\Definition\Argument;

class PostsResolver
{
    public function getPosts($value, Argument $args, \ArrayObject $context, ResolveInfo $info)
    {
//        dump($value, $args, $context, $info);
        $selectedFields = $info->getFieldSelection(2);
        $selectedFieldsPosts = $selectedFields['posts'];

//        dump($selectedFields);

        [
            'fields' => $postFields,
            'relations' => $postRelations
        ] = static::getLevelInfo($selectedFieldsPosts);

        $perPage = $args->offsetGet('perPage');
        $page = $args->offsetGet('page');
        $postsBeforePage = ($page - 1) * $perPage;
        $postsCurrentPageStart = $postsBeforePage + 1;
        $postsTotalCount = 227; // 100
        $pagesCount = ceil($postsTotalCount / $perPage);
        $posts = [];

        // генерирует посты текущей страницы
        for ($i = $postsCurrentPageStart; $i <= $postsBeforePage + $perPage && $i <= $postsTotalCount; ++$i) {
            $posts[] = static::fetchPost($i, $postFields, $postRelations, $selectedFieldsPosts);
        }

        $response = [
            'posts' => $posts,
        ];

        if (isset($selectedFields['pageInfo'])) {
            $selectedFieldsPageInfo = $selectedFields['pageInfo'];

            $pageInfo = [];

            if (isset($selectedFieldsPageInfo['page'])) {
                $pageInfo['page'] = $page;
            }
            if (isset($selectedFieldsPageInfo['pagesCount'])) {
                $pageInfo['pagesCount'] = $pagesCount;
            }
            if (isset($selectedFieldsPageInfo['perPage'])) {
                $pageInfo['perPage'] = $perPage;
            }
            if (isset($selectedFieldsPageInfo['totalItems'])) {
                $pageInfo['totalItems'] = $postsTotalCount;
            }
            if (isset($selectedFieldsPageInfo['hasNextPage'])) {
                // или $page + 1 <= $pagesCount
                $pageInfo['hasNextPage'] = $pagesCount - $page >= 1;
            }

            $response['pageInfo'] = $pageInfo;
        }

        return $response;
    }

    // Получает пост
    private function fetchPost($i, $postFields, $postRelations, $selectedFields)
    {
        $post = [
            'id' => $i,
            'comments' => [],
        ];

        if (\in_array('title', $postFields, true)) {
            $post['title'] = 'Title ' . $i;
        }
        if (\in_array('text', $postFields, true)) {
            $post['text'] = 'Text ' . $i;
        }
        if (\in_array('comments', $postRelations, true)) {
            [
                'fields' => $commentsFields
            ] = static::getLevelInfo($selectedFields['comments']);

            $post['comments'] = static::fetchComments($commentsFields);
        }
        if (\in_array('pageInfo', $postRelations, true)) {
            $post['pageInfo'] = [
                'hasNextPage' => false,
                'endCursor' => 20,
            ];
        }

        return $post;
    }

    private function fetchComments($fields)
    {
        $comments = [];
        $randCount = random_int(1, 10);

        for ($i = 1; $i <= $randCount; ++$i) {
            $comment = [];

            if (\in_array('text', $fields, true)) {
                $comment['text'] = 'Comment text';
            }
            if (\in_array('text', $fields, true)) {
                $author = ['Вася', 'Васько', 'Василий', 'Василько', 'Васьковый'][random_int(0, 4)];
                $comment['author'] = $author;
            }

            $comments[] = $comment;
        }

        return $comments;
    }

    // какие поля и отношения  есть на уровне
    private static function getLevelInfo($level)
    {
        $fields = [];
        $relations = [];

        foreach ($level as $field => $trueOrArr) {
            if (\is_array($trueOrArr)) { // or $trueOrArr === true
                $relations[] = $field;

                continue;
            }

            $fields[] = $field;
        }

        return [
            'fields' => $fields,
            'relations' => $relations,
        ];
    }
}

// параметры ресольвера:
//array:4 [▼
//  0 => null
//  1 => Argument {#851 ▼
//        -arguments: []
//  }
//  2 => ArrayObject {#619 ▼
//    flag::STD_PROP_LIST: false
//    flag::ARRAY_AS_PROPS: false
//    iteratorClass: "ArrayIterator"
//    storage: array:1 [▶]
//  }
//  3 => ResolveInfo {#856 ▼
//        +fieldName: "car"
//        +fieldNodes: ArrayObject {#853 ▶}
//        +returnType: StringType {#408 ▶}
//        +parentType: QueryType {#397 ▶}
//        +path: array:1 [▶]
//        +schema: ExtensibleSchema {#372 ▶}
//        +fragments: []
//        +rootValue: null
//        +operation: OperationDefinitionNode {#779 ▶}
//        +variableValues: []
//  }
//]
