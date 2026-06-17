<?php

/*
 * This file is part of Sulu.
 *
 * (c) Sulu GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Functional\Controller;

use Sulu\Bundle\TestBundle\Testing\WebsiteTestCase;
use Symfony\Component\Routing\RouterInterface;

class WebsiteRoutingTest extends WebsiteTestCase
{
    public function testWebsiteRoutesKeepGeneratedPaths(): void
    {
        $router = static::getContainer()->get('router');
        \assert($router instanceof RouterInterface);

        $this->assertSame(
            '/_api/threads/page-123/comments',
            $router->generate('sulu_comment.get_threads_comments', ['threadId' => 'page-123'])
        );
        $this->assertSame(
            '/_api/threads/page-123/comments.json',
            $router->generate('sulu_comment.get_threads_comments', ['threadId' => 'page-123', '_format' => 'json'])
        );
        $this->assertSame(
            '/_api/threads/page-123/comments',
            $router->generate('sulu_comment.post_thread_comments', ['threadId' => 'page-123'])
        );
        $this->assertSame(
            '/_api/threads/page-123/comments/1',
            $router->generate('sulu_comment.put_thread_comment', ['threadId' => 'page-123', 'commentId' => 1])
        );
        $this->assertSame(
            '/_api/threads/page-123/comments/1',
            $router->generate('sulu_comment.delete_thread_comment', ['threadId' => 'page-123', 'commentId' => 1])
        );
        $this->assertSame(
            '/_api/threads/page-123/count',
            $router->generate('sulu_comment.get_threads_count', ['threadId' => 'page-123'])
        );
    }
}
