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

use Sulu\Bundle\TestBundle\Testing\SuluTestCase;
use Symfony\Component\Routing\RouterInterface;

class AdminRoutingTest extends SuluTestCase
{
    public function testAdminRoutesKeepGeneratedPaths(): void
    {
        $router = static::getContainer()->get('router');
        \assert($router instanceof RouterInterface);

        $this->assertSame('/api/comments', $router->generate('sulu_comment.get_comments'));
        $this->assertSame('/api/comments/1', $router->generate('sulu_comment.get_comment', ['id' => 1]));
        $this->assertSame('/api/comments/1', $router->generate('sulu_comment.put_comment', ['id' => 1]));
        $this->assertSame('/api/comments/1', $router->generate('sulu_comment.delete_comment', ['id' => 1]));
        $this->assertSame('/api/comments', $router->generate('sulu_comment.delete_comments'));
        $this->assertSame('/api/comments/1', $router->generate('sulu_comment.post_comment_trigger', ['id' => 1]));

        $this->assertSame('/api/threads', $router->generate('sulu_comment.get_threads'));
        $this->assertSame('/api/threads/1', $router->generate('sulu_comment.get_thread', ['id' => 1]));
        $this->assertSame('/api/threads/1', $router->generate('sulu_comment.put_thread', ['id' => 1]));
        $this->assertSame('/api/threads/1', $router->generate('sulu_comment.delete_thread', ['id' => 1]));
        $this->assertSame('/api/threads', $router->generate('sulu_comment.delete_threads'));
    }
}
