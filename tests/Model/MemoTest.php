<?php

namespace CommunityDS\Deputy\Api\Tests\Model;

use CommunityDS\Deputy\Api\Tests\Adapter\MockClient;
use CommunityDS\Deputy\Api\Tests\TestCase;

class MemoTest extends TestCase
{

    public function testCreate()
    {
        $memoContent = 'content of the memo message';
        
        $memo = $this->wrapper()->createMemo();
        $memo->addAssignedUserId(MockClient::EMPLOYEE_FIRST);
        $memo->addAssignedCompanyId(MockClient::COMPANY_FIRST);
        $memo->content = $memoContent;
        $this->assertTrue($memo->isAttributeDirty('content'));
        $this->assertTrue($memo->save());
        
        $this->assertEquals(MockClient::MEMO_NEW, $memo->id);
        $this->assertFalse($memo->isAttributeDirty('content'));

        $this->assertRequestLog(
            [
                ['get' => 'resource/Memo/INFO'],
                ['post' => 'supervise/memo'],
            ]
        );
    }

    public function testCreateFail()
    {
        $memo = $this->wrapper()->createMemo();
        $this->assertFalse($memo->save());
    }
    
    public function testUpdateNotSupported()
    {
        $memo = $this->wrapper()->getMemo(MockClient::MEMO_FIRST);
        $this->assertFalse($memo->save(), 'Expected updating of Memo to NOT be available / not supported by Deputy API');

        $this->assertRequestLog(
            [
                ['get' => 'resource/Memo/INFO'],
                ['get' => 'resource/Memo/' . MockClient::MEMO_FIRST],
                ['post' => 'resource/Memo/' . MockClient::MEMO_FIRST],
            ]
        );
    }
}
