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

namespace Slim\View;

use Psr\Http\Message\ResponseInterface;
use InvalidArgumentException;

/**
 * A Mustache view class for Slim 3 Framework
 * @author    Andrews Lince <andrews.lince@gmail.com>
 * @version   1.0.0
 * @link      https://github.com/andrewslince/slim3-mustache
 * @package   Slim/View
 * @copyright 2016 Andrews Lince
 */
class Mustache
{
    /**
     * Mustache settings
     * @since 1.0.0
     * @var   array
     */
    protected $options = array();

    /**
     * Instance of Mustache Template Engine
     * @since 1.0.0
     * @var   Mustache_Engine
     */
    protected $templateEngine = null;

    /**
     * Constructor method
     * @author Andrews Lince <andrews.lince@gmail.com>
     * @since  1.0.0
     * @param  array $options
     * @return void
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;

        $this->templateEngine = $this->getTemplateEngine($options);
    }

    /**
     * Render a mustache template
     * @author Andrews Lince <andrews.lince@gmail.com>
     * @since  1.0.0
     * @param  ResponseInterface $response
     * @param  string            $template
     * @param  array             $data
     * @return ResponseInterface
     */
    public function render(
        ResponseInterface $response,
        $template,
        array $data = []
    ) {

        // render output
        $response->getBody()->write(
            $this->templateEngine->render($template, $data)
        );

        return $response;
    }

    /**
     * Returns the instance from Mustache Template Engine
     * @author Andrews Lince <andrews.lince@gmail.com>
     * @since  1.0.0
     * @param  array $options
     * - template
     *   - extension
     *   - charset
     *   - paths[]
     * @throws InvalidArgumentException
     * @return Mustache_Engine
     */
    public function getTemplateEngine(array $options = [])
    {
        if (is_null($this->templateEngine)) {

            if (!isset($options['template']['paths'])) {
                throw new InvalidArgumentException(
                    'Template paths was not informed'
                );
            }

            if (!is_array($options['template']['paths'])) {
                throw new InvalidArgumentException(
                    'Template paths must be an array'
                );
            }

            $loaders = [];
            foreach ($options['template']['paths'] as $templatePath) {
                $loaders[] = new \Mustache_Loader_FilesystemLoader(
                    $templatePath,
                    $options['template']
                );
            }

            // defines the mustache loaders
            $options['loader'] = new \Mustache_Loader_CascadingLoader($loaders);

            $this->templateEngine = new \Mustache_Engine($options);
        }

        return $this->templateEngine;
    }
}
