<?php

/*
 * This file is part of DuRoom.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace DuRoom\Mentions;

use DuRoom\Api\Controller;
use DuRoom\Api\Serializer\BasicPostSerializer;
use DuRoom\Api\Serializer\BasicUserSerializer;
use DuRoom\Api\Serializer\PostSerializer;
use DuRoom\Extend;
use DuRoom\Mentions\Notification\PostMentionedBlueprint;
use DuRoom\Mentions\Notification\UserMentionedBlueprint;
use DuRoom\Post\Event\Deleted;
use DuRoom\Post\Event\Hidden;
use DuRoom\Post\Event\Posted;
use DuRoom\Post\Event\Restored;
use DuRoom\Post\Event\Revised;
use DuRoom\Post\Filter\PostFilterer;
use DuRoom\Post\Post;
use DuRoom\User\User;

return [
    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/less/forum.less'),

    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js'),

    (new Extend\Formatter)
        ->configure(ConfigureMentions::class)
        ->render(Formatter\FormatPostMentions::class)
        ->render(Formatter\FormatUserMentions::class)
        ->unparse(Formatter\UnparsePostMentions::class)
        ->unparse(Formatter\UnparseUserMentions::class),

    (new Extend\Model(Post::class))
        ->belongsToMany('mentionedBy', Post::class, 'post_mentions_post', 'mentions_post_id', 'post_id')
        ->belongsToMany('mentionsPosts', Post::class, 'post_mentions_post', 'post_id', 'mentions_post_id')
        ->belongsToMany('mentionsUsers', User::class, 'post_mentions_user', 'post_id', 'mentions_user_id'),

    new Extend\Locales(__DIR__.'/locale'),

    (new Extend\View)
        ->namespace('duroom-mentions', __DIR__.'/views'),

    (new Extend\Notification())
        ->type(PostMentionedBlueprint::class, PostSerializer::class, ['alert'])
        ->type(UserMentionedBlueprint::class, PostSerializer::class, ['alert']),

    (new Extend\ApiSerializer(BasicPostSerializer::class))
        ->hasMany('mentionedBy', BasicPostSerializer::class)
        ->hasMany('mentionsPosts', BasicPostSerializer::class)
        ->hasMany('mentionsUsers', BasicUserSerializer::class),

    (new Extend\ApiController(Controller\ShowDiscussionController::class))
        ->addInclude(['posts.mentionedBy', 'posts.mentionedBy.user', 'posts.mentionedBy.discussion'])
        ->load([
            'posts.mentionsUsers', 'posts.mentionsPosts', 'posts.mentionsPosts.user', 'posts.mentionedBy',
            'posts.mentionedBy.mentionsPosts', 'posts.mentionedBy.mentionsPosts.user', 'posts.mentionedBy.mentionsUsers',
        ]),

    (new Extend\ApiController(Controller\ListDiscussionsController::class))
        ->load([
            'firstPost.mentionsUsers', 'firstPost.mentionsPosts', 'firstPost.mentionsPosts.user',
            'lastPost.mentionsUsers', 'lastPost.mentionsPosts', 'lastPost.mentionsPosts.user'
        ]),

    (new Extend\ApiController(Controller\ShowPostController::class))
        ->addInclude(['mentionedBy', 'mentionedBy.user', 'mentionedBy.discussion']),

    (new Extend\ApiController(Controller\ListPostsController::class))
        ->addInclude(['mentionedBy', 'mentionedBy.user', 'mentionedBy.discussion'])
        ->load([
            'mentionsUsers', 'mentionsPosts', 'mentionsPosts.user', 'mentionedBy',
            'mentionedBy.mentionsPosts', 'mentionedBy.mentionsPosts.user', 'mentionedBy.mentionsUsers',
        ]),

    (new Extend\ApiController(Controller\CreatePostController::class))
        ->addInclude(['mentionsPosts', 'mentionsPosts.mentionedBy']),

    (new Extend\ApiController(Controller\UpdatePostController::class))
        ->addInclude(['mentionsPosts', 'mentionsPosts.mentionedBy']),

    (new Extend\ApiController(Controller\AbstractSerializeController::class))
        ->prepareDataForSerialization(FilterVisiblePosts::class),

    (new Extend\Settings)
        ->serializeToForum('allowUsernameMentionFormat', 'duroom-mentions.allow_username_format', 'boolval'),

    (new Extend\Event())
        ->listen(Posted::class, Listener\UpdateMentionsMetadataWhenVisible::class)
        ->listen(Restored::class, Listener\UpdateMentionsMetadataWhenVisible::class)
        ->listen(Revised::class, Listener\UpdateMentionsMetadataWhenVisible::class)
        ->listen(Hidden::class, Listener\UpdateMentionsMetadataWhenInvisible::class)
        ->listen(Deleted::class, Listener\UpdateMentionsMetadataWhenInvisible::class),

    (new Extend\Filter(PostFilterer::class))
        ->addFilter(Filter\MentionedFilter::class),
];
