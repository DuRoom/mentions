import app from 'duroom/forum/app';
import { extend } from 'duroom/common/extend';
import Button from 'duroom/common/components/Button';
import CommentPost from 'duroom/forum/components/CommentPost';

import reply from './utils/reply';

export default function () {
  extend(CommentPost.prototype, 'actionItems', function (items) {
    const post = this.attrs.post;

    if (post.isHidden() || (app.session.user && !post.discussion().canReply())) return;

    items.add(
      'reply',
      <Button className="Button Button--link" onclick={() => reply(post)}>
        {app.translator.trans('duroom-mentions.forum.post.reply_link')}
      </Button>
    );
  });
}
