# Craft Waitlist

> **Please Note**
> This plugin is under active development. Some primary advertised features are not yet implemented.

A client recently requested that customers be able to sign up for notifications after a product had sold out. We decided to use the opportunity to extract the functionality into a plugin.

Essentially, the plugin exposes a single endpoint that accepts an AJAX `POST` payload with an email address and product ID. The "members" created via that public controller are fed into an element index, and can be moved between three pre-defined statuses (still in flux).

Ultimately, another element action will allow members to be selected in bulk and delivered a customized notification.

The plugin will not automatically send notifications, as control is inherently limited—updating or restoring inventory should be done independently, and never directly or inadvertently trigger emails.

The form can be as simple as this, assuming it's submitted via AJAX:

```twig
<form method="POST" class="waitlist">
  <input type="hidden" name="action" value="waitlist/members/add">
  <input type="hidden" name="subjectId" value="{{ product.id }}">
  {{ getCsrfInput() }}

  <input type="text" name="email">
  <input type="submit" value="Sign up for notifications">
</form>
```

A basic but configurable honeypot is planned to reduce spam submissions.

Of course, you could do this just as easily with a basic Mailchimp list. This is intended as a strictly marketing tool, and not as a means to build a contact list. Emails are apt to get purged after notifications are sent.

:deciduous_tree:
