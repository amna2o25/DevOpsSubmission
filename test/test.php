
<?php
// test/LoginHelperTest.php
use PHPUnit\Framework\TestCase;
use MyApp\LoginHelper;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;

class LoginHelperTest extends TestCase
{
    protected function setUp(): void
    {
        // ensure our env is loaded in PHPUnit
        if (! getenv('FTP_HOST')) {
            putenv('FTP_HOST=http://213.171.200.34');
            putenv('FTP_USER=aidris');
            putenv('FTP_PASS=Password20*..');
        }
    }

    public function testBuildUrl(): void
    {
        $mockClient = $this->createMock(ClientInterface::class);
        $helper     = new LoginHelper($mockClient);

        $this->assertEquals(
            'http://213.171.200.34/login',
            $helper->buildUrl('/login')
        );
        $this->assertEquals(
            'http://213.171.200.34/foo/bar',
            $helper->buildUrl('foo/bar')
        );
    }

    public function testLoginReturnsTrueOn200(): void
    {
        $mockResponse = new Response(200);
        $mockClient   = $this->createMock(ClientInterface::class);

        // Expect GET + correct URL + auth
        $mockClient
            ->expects($this->once())
            ->method('request')
            ->with(
                'GET',
                'http://213.171.200.34/testpath',
                ['auth' => ['aidris', 'Password20*..']]
            )
            ->willReturn($mockResponse);

        $helper = new LoginHelper($mockClient);
        $this->assertTrue($helper->login('/testpath'));
    }

    public function testLoginReturnsFalseOnNon200(): void
    {
        $mockClient = $this->createMock(ClientInterface::class);
        $mockClient
            ->method('request')
            ->willReturn(new Response(401));

        $helper = new LoginHelper($mockClient);
        $this->assertFalse($helper->login('/anything'));
    }
}
