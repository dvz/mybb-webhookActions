<?php

namespace webhookActions;

function getReceivers(): array
{
    static $receivers;

    if ($receivers === null) {
        if (file_exists(__DIR__ . '/receivers.json.php')) {
            $content = include __DIR__ . '/receivers.json.php';
        } elseif (file_exists(__DIR__ . '/receivers.json')) {
            $content = file_get_contents(__DIR__ . '/receivers.json.php');
        } else {
            $content = null;
        }

        if ($content === null) {
            $receivers = [];
        } else {
            $jsonData = json_decode($content, true);

            if ($jsonData === false) {
                $receivers = [];
            } else {
                $receivers = $jsonData;
            }
        }
    }

    return $receivers;
}

function getReceiverByName(string $name): ?array
{
    return \webhookActions\getReceivers()[$name] ?? null;
}

function runReceiverActionsWithData(array $receiverActions, array $data): void
{
    foreach ($receiverActions as $receiverAction) {
        $fqn = '\\webhookActions\\Action\\' . $receiverAction['name'];

        if (class_exists($fqn)) {
            /** @var \webhookActions\Action $action */
            $action = new $fqn();

            if (array_diff(
                $action->getRequiredOptions(),
                array_keys($receiverAction['options'] ?? [])
            ) !== []) {
                continue;
            }

            $action->setOptions($receiverAction['options'] ?? []);
            $action->setData($data);
            $action->execute();
        }
    }
}

function getFormattedValue($pattern, array $data): ?string
{
    if (is_array($pattern)) {
        if (isset($pattern[0])) {
            $values = array_map(
                function (string $key) use ($data) {
                    return $data[$key] ?? null;
                },
                array_slice($pattern, 1)
            );
            return vsprintf($pattern[0], $values);
        } else {
            return null;
        }
    } elseif (is_string($pattern)) {
        return $pattern;
    } else {
        return null;
    }
}
