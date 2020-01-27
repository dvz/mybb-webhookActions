<?php

namespace webhookActions\Hooks;

function misc_start(): void
{
    global $mybb;

    if ($mybb->get_input('action') === 'webhook_actions') {
        $feed = \webhookActions\getReceiverByName($mybb->get_input('receiver'));

        if ($feed !== null) {
            if ($feed['content_type'] ?? null === 'form') {
                if (isset($feed['key']) && $mybb->get_input('key') === $feed['key']) {
                    $data = $_POST;
                }
            }

            if (isset($data)) {
                \webhookActions\runReceiverActionsWithData($feed['actions'] ?? [], $data);
            }
        }

        exit;
    }
}
