<?php

namespace Caishni\SmsMisr\Tests\Unit;

use Caishni\SmsMisr\Tests\TestCase;

class SmsMisrTest extends TestCase
{
    /** @test */
    public function it_can_send_to_one_target()
    {
        $response = sms()->to('201234567891')
            ->message('hello world')
            ->language('en')
            ->send();

        $this->assertEquals('1901', $response->code);
    }

    /** @test */
    public function it_can_send_to_many_targets()
    {
        sleep(2);

        $response = sms()->to(['201234567891', '201234567892', '201234567893'])
            ->message('hello world')
            ->language('en')
            ->send();

        $this->assertEquals('1901', $response->code);
    }

    /** @test */
    public function it_throws_exception_if_message_is_not_set()
    {
        $this->expectException(\InvalidArgumentException::class);

        sms()->to('201234567891')
            ->language('en')
            ->send();
    }

    /** @test */
    public function it_throws_exception_if_language_is_not_set()
    {
        $this->expectException(\InvalidArgumentException::class);

        sms()->to('201234567891')
            ->message('hello world')
            ->send();
    }

    /** @test */
    public function it_throws_exception_if_selected_language_is_invalid()
    {
        $this->expectException(\InvalidArgumentException::class);

        sms()->to('201234567891')
            ->language('es')
            ->send();
    }

    /** @test */
    public function it_throws_exceptions_if_config_is_not_set_test()
    {
        $this->expectException(\Exception::class);

        $this->app['config']->set('sms-misr.username', null);
        $this->app['config']->set('sms-misr.password', null);
        $this->app['config']->set('sms-misr.sender', null);

        sms()->message('test');
    }
}