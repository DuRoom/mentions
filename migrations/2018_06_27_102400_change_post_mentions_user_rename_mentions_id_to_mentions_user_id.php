<?php

/*
 * This file is part of DuRoom.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

use DuRoom\Database\Migration;

return Migration::renameColumn('post_mentions_user', 'mentions_id', 'mentions_user_id');
