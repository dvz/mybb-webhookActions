# Webhook Actions

Triggers configurable actions supplemented with webhook data.

**Requirements:**
- PHP >= 7.1

### Configuration
Webhook Receivers and Actions can be configured in the `inc/plugins/webhookActions/receivers.json.php` file returning a JSON string.
If the `.php` file is not present, the plugin will attempt to load JSON content from `inc/plugins/webhookActions/receivers.json`. When choosing to use this method, you should make sure this file is never accessible publicly.

The JSON contains the names of webhook Receivers, their input types (`content_type`), keys (`key`), and Action details (`actions`) that should be triggered when a webhook is received:

```json
{
  "RECEIVER-NAME": {
    "content_type": "form",
    "key": "ACCESS-KEY",
    "actions": [
      {
        "name": "ACTION-NAME",
        "options": {},
      }
    ]
  }
}
```

Defined receivers can be accessed through the following webhook URL:
```
misc.php?action=webhook_actions&receiver=RECEIVER-NAME&key=ACCESS-KEY
```

Action files are PHP classes located in `inc/plugins/webhookActions/Action/` that extend  `\webhookActions\Action`, and receive configured options with webhook data.
Depending on Action implementation, some options support [`sprintf()` formatting](https://www.php.net/manual/en/function.sprintf.php) with webhook data:
```
["String: %s, integer: %d", "WEBHOOK-DATA1", "WEBHOOK-DATA2", ...]
```

**Supported `content_type` options:**
- `form`: HTTP POST key-value pairs

### Example
Example configuration to create a forum thread using  webhook data:
```json
{
  "announcement_published": {
    "content_type": "form",
    "key": "example-key",
    "actions": [
      {
        "name": "PostThread",
        "options": {
          "userId": 1,
          "forumId": 2,
          "subject": ["Discuss: %s", "post_title"],
          "message": ["Please discuss [url=%s]%s[/url] here.", "post_url", "post_title"]
        }
      }
    ]
  }
}
```
URL: `misc.php?action=webhook_actions&receiver=announcement_published&key=example-key`

