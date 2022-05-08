<?php

/*
 * This file is part of DuRoom.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace DuRoom\Mentions\Filter;

use DuRoom\Filter\FilterInterface;
use DuRoom\Filter\FilterState;

class MentionedFilter implements FilterInterface
{
    public function getFilterKey(): string
    {
        return 'mentioned';
    }

    public function filter(FilterState $filterState, string $filterValue, bool $negate)
    {
        $mentionedId = trim($filterValue, '"');

        $filterState
            ->getQuery()
            ->join('post_mentions_user', 'posts.id', '=', 'post_mentions_user.post_id')
            ->where('post_mentions_user.mentions_user_id', $negate ? '!=' : '=', $mentionedId);
    }
}
