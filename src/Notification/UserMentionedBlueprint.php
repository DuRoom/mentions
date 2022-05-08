<?php

/*
 * This file is part of DuRoom.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace DuRoom\Mentions\Notification;

use DuRoom\Notification\Blueprint\BlueprintInterface;
use DuRoom\Notification\MailableInterface;
use DuRoom\Post\Post;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserMentionedBlueprint implements BlueprintInterface, MailableInterface
{
    /**
     * @var Post
     */
    public $post;

    /**
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubject()
    {
        return $this->post;
    }

    /**
     * {@inheritdoc}
     */
    public function getFromUser()
    {
        return $this->post->user;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getEmailView()
    {
        return ['text' => 'duroom-mentions::emails.userMentioned'];
    }

    /**
     * {@inheritdoc}
     */
    public function getEmailSubject(TranslatorInterface $translator)
    {
        return $translator->trans('duroom-mentions.email.user_mentioned.subject', [
            '{mentioner_display_name}' => $this->post->user->display_name,
            '{title}' => $this->post->discussion->title
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function getType()
    {
        return 'userMentioned';
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubjectModel()
    {
        return Post::class;
    }
}
