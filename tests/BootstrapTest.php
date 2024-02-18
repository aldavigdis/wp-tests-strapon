<?php

declare(strict_types = 1);

namespace Aldavigdis\WpTestsStrapon\Tests;

use Aldavigdis\WpTestsStrapon\TestHelper;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\TestDox;

#[TestDox( 'The Foo class' )]
final class BootstrapTest extends TestCase {
	#[TestDox( 'echoes out a hello message' )]
	public function testTrue(): void {
		TestHelper::sayHello();
		$this->expectOutputString( 'Hello!' );
	}
}
