# Upgrade

## 3.0.0

### Sulu 3.0 compatibility

This version adds support for Sulu 3.0. The bundle now requires:

- PHP 8.2 or higher
- Sulu 3.0 or higher
- Symfony 6.4 or 7.1 or higher
- Doctrine ORM 2.17.3 or 3.3 or higher
- FOSRestBundle 3.2 or higher

### Removing the rest routing

The Rest Routing bundle is no longer required. Remove `type: rest` from your
admin and website route imports:

```diff
# config/routes/sulu_comment_admin.yaml
 sulu_comment_api:
-    type: rest
     resource: "@SuluCommentBundle/Resources/config/routing_api.yaml"
     prefix: /admin/api
```

```diff
# config/routes/sulu_comment_website.yaml
 sulu_comment:
-    type: rest
     resource: "@SuluCommentBundle/Resources/config/routing_website.yaml"
```

The generated paths and route names are kept unchanged.

### REST list response changed

The admin comments and threads list endpoints now return a
`PaginatedRepresentation` instead of the removed `ListRepresentation`. The
`_embedded.comments` and `_embedded.threads` arrays, `page`, `limit` and
`total` fields are unchanged. The `_links` section no longer exposes the
request route and query parameters.

### Removed website comment pagination fallback

The deprecated `page` and `pageSize` query parameters of the website comments
endpoint have been removed. Use `limit` and `offset` instead:

```diff
-/_api/threads/page-123/comments?page=2&pageSize=10
+/_api/threads/page-123/comments?limit=10&offset=10
```

The `page` and `pageSize` variables are no longer passed to the rendered
comments template. If a custom comments template uses those variables, calculate
the pagination state in the application and pass it through custom content data.

### Controller routing integration changed

The controllers no longer implement FOSRestBundle's `ClassResourceInterface`
and no longer use FOSRestBundle route annotations. If you extended these
controllers or imported them with custom FOSRest route resources, switch to
explicit Symfony route definitions.

### Removed legacy test compatibility files

The PHPUnit 9 configuration and the PHP 7.2 Prophecy bridge have been removed.
Use the default `phpunit.xml.dist` configuration with the supported PHP and
PHPUnit versions.

## dev-develop

### Rename comment_count property to commentCount

The `comment_count` property of serialized threads was renamed to `commentCount`.

### Add nested tree to comments

Comments can now be nested - therefor the database schema has changed and can be updated by:

```sql
ALTER TABLE com_comment ADD lft INT NOT NULL, ADD rgt INT NOT NULL, ADD depth INT NOT NULL, ADD idCommentsParent INT DEFAULT NULL;
ALTER TABLE com_comment ADD CONSTRAINT FK_AA6F14A324308710 FOREIGN KEY (idCommentsParent) REFERENCES com_comment (id) ON DELETE CASCADE;
CREATE INDEX IDX_AA6F14A324308710 ON com_comment (idCommentsParent);
```

Use following configuration to disable the nested comments by default:

```yaml
sulu_comment:
    nested_comments: false
``` 

### Type-Hints

We have added type-hints to the whole codebase. Therefor the function parameter and returns validation is stricter
than before.

Additionally we have remove the possibility to pass a single ID to the following functions. If you want to delete a
single entity you have to pass an array with a single id.

* CommentManagerInterface::delete
* CommentManagerInterface::deleteThreads

### Index length of threads type/entityId

In order to allow `utf8mb4` it was neccesary to down size the length of the fields `type` and `entityId` within the
Thread. The following SQL upgrades your database schema. But be sure that the types you are not longer than 64
characters.

```sql
ALTER TABLE com_threads CHANGE type type VARCHAR(64) NOT NULL, CHANGE entityId entityId VARCHAR(64) NOT NULL;
```
