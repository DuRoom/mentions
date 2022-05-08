import app from 'duroom/forum/app';
import Notification from 'duroom/forum/components/Notification';
import { truncate } from 'duroom/common/utils/string';

export default class PostMentionedNotification extends Notification {
  icon() {
    return 'fas fa-reply';
  }

  href() {
    const notification = this.attrs.notification;
    const post = notification.subject();
    const content = notification.content();

    return app.route.discussion(post.discussion(), content && content.replyNumber);
  }

  content() {
    const notification = this.attrs.notification;
    const user = notification.fromUser();

    return app.translator.trans('duroom-mentions.forum.notifications.post_mentioned_text', { user, count: 1 });
  }

  excerpt() {
    return truncate(this.attrs.notification.subject().contentPlain(), 200);
  }
}
