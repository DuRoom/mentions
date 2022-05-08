<?php

/*
 * This file is part of DuRoom.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

use DuRoom\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

return Migration::createTable(
    'mentions_posts',
    function (Blueprint $table) {
        $table->integer('post_id')->unsigned();
        $table->integer('mentions_id')->unsigned();
        $table->primary(['post_id', 'mentions_id']);
    }
);
