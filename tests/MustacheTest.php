<?php

/**
 * MIT License
 *
 * Copyright (c) 2016 Andrews Lince
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * copies of the Software, and to permit persons to whom the Software is
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Slim\Tests\Views;

use Slim\Views\Mustache as SlimMustache;
use Slim\Http\Response;
use Slim\Http\Headers;
use Slim\Http\Body;

/**
 * Class to test Slim Mustache wrapper
 * @author   Andrews Lince <andrews.lince@gmail.com>
 * @since    1.0.3
 * @link      https://github.com/andrewslince/slim3-mustache-view
 * @package   Slim/Tests/Views
 * @copyright 2016 - 2016 Andrews Lince
 */
class MustacheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Default \Slim\Views\Mustache object
     * @since 1.0.3
     * @var   \Slim\Views\Mustache
     */
    private $slimMustacheObject = null;

    /**
     * Slim Response object
     * @since 1.0.3
     * @var   \Slim\Http\Response
     */
    private $responseInterface = null;

    /**
     * Return a default options to create a '\Slim\Views\Mustache' object
     * @author Andrews Lince <andrews.lince@gmail.com>
     * @since  1.0.3
     * @return array
     * - template[]
     *   - extension
     *   - charset
     *   - paths[]
     */
    private function getDefaultOptions()
    {
        return [
            'template' => [
                'extension' => 'html',
                'charset' => 'utf-8',
                'paths' => [
                    realpath(__DIR__ . DIRECTORY_SEPARATOR . 'templates')
                ]
            ]
        ];
    }

    /**
     * Creates and returns a default '\Slim\Views\Mustache' object
     * @author Andrews Lince <andrews.lince@gmail.com>
     * @since  1.0.3
     * @return \Slim\Views\Mustache
     */
    private function getDefaultSlimMustacheObject()
    {
        return new SlimMustache($this->getDefaultOptions());
    }

    /**
     * Returns the body's content from response object
     * @author Andrews Lince <andrews.lince@gmail.com>
     * @since  1.0.3
     * @param  \Slim\Http\Response $reponse
     * @return string
     */
    private function getBodyContents(Response $reponse)
    {
        $reponse->getBody()->rewind();

        return trim($reponse->getBody()->getContents());
    }

    /**
     * [setUp description]
     * @author Andrews Lince <andrews.lince@gmail.com>
     * @since  1.0.3
     * @return void
     */
    public function setUp()
    {
        $this->slimMustacheObject = $this->getDefaultSlimMustacheObject();
        $this->responseInterface  = new Response(
            200,
            new Headers(),
            new Body(fopen('php://temp', 'r+'))
        );
    }

    /**
     * Call protected/private method of a class.
     * @author Andrews Lince <andrews.lince@gmail.com>
     * @since  1.0.3
     * @link   https://jtreminio.com/2013/03/unit-testing-tutorial-part-3-testing-protected-private-methods-coverage-reports-and-crap/
     * @param  object &$object    Instantiated object that we will run method on
     * @param  string $methodName Method name to call
     * @param  array  $parameters Array of parameters to pass into method
     * @return mixed Method return
     */
    public function invokeMethod(
        &$object,
        $methodName,
        array $parameters = []
    ) {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * @author Andrews Lince <andrews.lince@gmail.com>
     * @since  1.0.3
     * @return void
     */
    public function testSlimMustacheConstructor()
    {
        $this->assertInstanceOf(
            'Mustache_Engine',
            $this->invokeMethod(
                $this->slimMustacheObject,
                'getTemplateEngine',
                []
            )
        );
    }

    /**
     * @author Andrews Lince <andrews.lince@gmail.com>
     * @since  1.0.3
     * @expectedException InvalidArgumentException
     * @return void
     */
    public function testSlimMustacheConstructorWithoutTemplatePaths()
    {
        $wrongOptions = $this->getDefaultOptions();
        unset($wrongOptions['template']['paths']);

        new SlimMustache($wrongOptions);
    }

    /**
     * @author Andrews Lince <andrews.lince@gmail.com>
     * @since  1.0.3
     * @expectedException InvalidArgumentException
     * @return void
     */
    public function testSlimMustacheConstructorWithTemplatePathAsString()
    {
        $wrongOptions = $this->getDefaultOptions();
        $wrongOptions['template']['paths'] = realpath('./templates');

        new SlimMustache($wrongOptions);
    }

    /**
     * @author Andrews Lince <andrews.lince@gmail.com>
     * @since  1.0.3
     * @return void
     */
    public function testRender()
    {
        // template variable
        $name = 'Andrews';

        $newResponse = $this->slimMustacheObject->render(
            $this->responseInterface,
            'simple-view',
            [ 'name' => $name ]
        );

        $this->assertEquals(
            "Hello $name",
            $this->getBodyContents($newResponse)
        );
    }

    /**
     * @author Andrews Lince <andrews.lince@gmail.com>
     * @since  1.0.3
     * @return void
     */
    public function testRenderWithMoreThanOneTemplatePaths()
    {
        // template variable
        $name = 'Andrews';

        // add another path in constructor options
        $options = $this->getDefaultOptions();
        $options['template']['paths'][] = realpath(
            __DIR__ . DIRECTORY_SEPARATOR . 'another-templates'
        );

        // create Mustache object
        $slimMustacheObject = new SlimMustache($options);

        $newResponse = $slimMustacheObject->render(
            $this->responseInterface,
            'another-simple-view'
        );

        $this->assertEquals(
            'Another simple content',
            $this->getBodyContents($newResponse)
        );
    }

    /**
     * @author Andrews Lince <andrews.lince@gmail.com>
     * @since  1.0.3
     * @expectedException \Mustache_Exception_UnknownTemplateException
     * @return void
     */
    public function testRenderTemplateNotFound()
    {
        $this->slimMustacheObject->render(
            $this->responseInterface,
            'not-found-template'
        );
    }

    /**
     * @author Andrews Lince <andrews.lince@gmail.com>
     * @since  1.0.3
     * @return void
     */
    public function testGetTemplateScriptTag()
    {
        $tplScriptTag = $this->slimMustacheObject->getTemplateScriptTag(
            'simple-view'
        );

        $outputScriptTag = '<script type="x-tmpl-mustache">Hello {{name}}
</script>';

        $this->assertEquals($outputScriptTag, $tplScriptTag);
    }

    /**
     * @author Andrews Lince <andrews.lince@gmail.com>
     * @since  1.0.3
     * @expectedException InvalidArgumentException
     * @return void
     */
    public function testGetTemplateScriptTagWithoutTemplateName()
    {
        $this->slimMustacheObject->getTemplateScriptTag(null);
    }

    /**
     * @author Andrews Lince <andrews.lince@gmail.com>
     * @since  1.0.3
     * @expectedException InvalidArgumentException
     * @return void
     */
    public function testGetTemplateScriptTagWithInvalidOptionHtmlAttributes()
    {
        $this->slimMustacheObject->getTemplateScriptTag(
            'simple-view',
            [ 'htmlAttributes' => 'style="display:none;"' ]
        );
    }

    /**
     * @author Andrews Lince <andrews.lince@gmail.com>
     * @since  1.0.3
     * @return void
     */
    public function testGetTemplateScriptTagWithMinifyOption()
    {
        $tplScriptTag = $this->slimMustacheObject->getTemplateScriptTag(
            'simple-view',
            [ 'minify' => true ]
        );

        $this->assertEquals(
            '<script type="x-tmpl-mustache">Hello {{name}} </script>',
            $tplScriptTag
        );
    }

    /**
     * @author Andrews Lince <andrews.lince@gmail.com>
     * @since  1.0.3
     * @return void
     */
    public function testGetTemplateScriptTagWithCustomAttributeTag()
    {
        $attributeName = 'id';
        $attributeValue = 'whatever';

        $tplScriptTag = $this->slimMustacheObject->getTemplateScriptTag(
            'simple-view',
            [
                'minify' => true,
                'htmlAttributes' => [
                    $attributeName => $attributeValue
                ]
            ]
        );

        $this->assertEquals(
            "<script type=\"x-tmpl-mustache\" $attributeName=\"$attributeValue\">Hello {{name}} </script>",
            $tplScriptTag
        );
    }

    /**
     * @author Andrews Lince <andrews.lince@gmail.com>
     * @since  1.0.3
     * @return void
     */
    public function testProcessHtmlAttributesWithDefaultAttributes()
    {
        $this->assertEquals(
            'type="x-tmpl-mustache"',
            $this->invokeMethod(
                $this->slimMustacheObject,
                'processHtmlAttributes'
            )
        );
    }

    /**
     * @author Andrews Lince <andrews.lince@gmail.com>
     * @since  1.0.3
     * @return void
     */
    public function testProcessHtmlAttributesWithCustomAttributes()
    {
        $customAttributeName = 'id';
        $customAttributeValue = 'whatever';

        $this->assertEquals(
            "type=\"x-tmpl-mustache\" $customAttributeName=\"$customAttributeValue\"",
            $this->invokeMethod(
                $this->slimMustacheObject,
                'processHtmlAttributes',
                [
                    [
                        $customAttributeName => $customAttributeValue
                    ]
                ]
            )
        );
    }

    /**
     * @author Andrews Lince <andrews.lince@gmail.com>
     * @since  1.0.3
     * @return void
     */
    public function testHtmlMinify()
    {
        $htmlSource = '<div id="whatever">
            <h1>Title</h1>
            <main>
                <p>Content</p>
            </main>
        </div>';

        $htmlTarget = '<div id="whatever"><h1>Title</h1><main><p>Content</p></main></div>';

        $this->assertEquals(
            $htmlTarget,
            $this->invokeMethod(
                $this->slimMustacheObject,
                'htmlMinify',
                [ $htmlSource ]
            )
        );
    }
}
