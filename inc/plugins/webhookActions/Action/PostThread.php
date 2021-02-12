<?php

namespace webhookActions\Action;

class PostThread extends \webhookActions\Action
{
    public function getRequiredOptions(): array
    {
        return [
            'forumId',
            'userId',
            'message',
            'subject',
        ];
    }

    public function execute(): bool
    {
        // ignore WordPress notifications for updated posts
        if (isset($this->data['post_date_gmt'], $this->data['post_modified_gmt'])) {
            if ($this->data['post_date_gmt'] !== $this->data['post_modified_gmt']) {
                return true;
            }
        }

        $subject = \webhookActions\getFormattedValue($this->options['subject'], $this->data);

        if (\my_strlen($subject) > 85) {
            $subject = \my_substr($subject, 0, 84) . '…';
        }

        $message = \webhookActions\getFormattedValue($this->options['message'], $this->data);

        require_once MYBB_ROOT . 'inc/datahandlers/post.php';

        $postDataHandler = new \postDataHandler('insert');
        $postDataHandler->action = 'thread';
        $postDataHandler->set_data([
            'fid' => (int)$this->options['forumId'],
            'subject' => $subject,
            'message' => $message,
            'options' => [
                'disablesmilies' => true,
            ],
            'uid' => $this->options['userId'],
        ]);
        $result = $postDataHandler->validate_thread();

        if ($result) {
            $result = (bool)$postDataHandler->insert_thread();
        }

        return $result;
    }
}
