<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Module\DebugModule\Listener;

use Symfony\Component\HttpFoundation\Response;
use Trident\Component\HttpKernel\Event\FilterExceptionEvent;
use Trident\Component\HttpKernel\Exception\HttpExceptionInterface;
use Trident\Component\HttpKernel\AbstractKernel;

/**
 * Debug Exception Listener
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class ExceptionListener
{
    private $kernel;
    private $request;

    /**
     * On kernel exception.
     *
     * @param FilterExceptionEvent $event
     */
    public function onException(FilterExceptionEvent $event)
    {
        $this->exception = $event->getException();
        $this->kernel    = $event->getKernel();
        $this->request   = $event->getRequest();

        $event->setResponse(new Response(
            $this->createBody($event)
        ));
    }

    /**
     * Create error page body.
     *
     * @param FilterExceptionEvent $event
     *
     * @return string
     */
    private function createBody(FilterExceptionEvent $event)
    {
        $exception = $event->getException();

        if ($exception instanceof HttpExceptionInterface && 404 === $exception->getStatusCode()) {
            $title  = 'Ooops! Page not found.';
            $method = 'onPageNotFound';
        } else {
            $title  = 'Uh oh, an error has occurred.';
            $method = 'onException';
        }

        $statusCode     = $exception->getStatusCode() ? $exception->getStatusCode() : 500;
        $tridentVersion = AbstractKernel::VERSION;

        return <<<EOF
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="robots" content="noindex,nofollow" />
        <title>{$title}</title>
        <link href='//fonts.googleapis.com/css?family=Ubuntu+Mono' rel='stylesheet' type='text/css'>
        <style type="text/css">
{$this->getCss()}
        </style>
    </head>
    <body>
        <header class="container logo">
            <img class="logo" src="data:image/png;base64,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" />
        </header>
        <div class="pre-esque">
            <div class="container pre-container">
                <div class="pre-message">
                    <span class="line"><span class="comment"># {$this->request->getMethod()} {$this->request->generateRelative()}</span></span>
                    <span class="line"></span>
                    <span class="line"><span class="comment">// ...</span></span>
                    <span class="line"><span class="keyword">public function</span> <span class="function">{$method}</span>(<span class="method">FilterExceptionEvent</span> \$event)</span>
                    <span class="line">{</span>
                    <span class="line">&nbsp;&nbsp;&nbsp;&nbsp;\$event<span class="keyword">-></span><span class="method">setResponse</span>(<span class="keyword">new</span> Response(</span>
                    <span class="line">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'{$this->exception->getMessage()}'</span>, <span class="number">{$statusCode}</span>
                    <span class="line">&nbsp;&nbsp;&nbsp;&nbsp;));</span>
                    <span class="line">}</span>
                    <span class="line"></span>
                </div>
            </div>
        </div>
        <footer class="container">
            <p class="copyright">&copy; 2014 <a target="_blank" href="http://strident.io/">Strident</a> - Trident v{$tridentVersion}</p>
        </footer>
    </body>
</html>
EOF;
    }

    /**
     * Get CSS for error pages.
     *
     * Uses normalize.css v3.0.1 by Nicolas Gallagher (http://necolas.github.io/normalize.css/)
     *
     * @return string
     */
    private function getCss()
    {
        return <<<CSS
            /*! normalize.css v3.0.1 | MIT License | git.io/normalize */
            html{font-family:sans-serif;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}body{margin:0}article,aside,details,figcaption,figure,footer,header,hgroup,main,nav,section,summary{display:block}audio,canvas,progress,video{display:inline-block;vertical-align:baseline}audio:not([controls]){display:none;height:0}[hidden],template{display:none}a{background:0 0}a:active,a:hover{outline:0}abbr[title]{border-bottom:1px dotted}b,strong{font-weight:700}dfn{font-style:italic}h1{font-size:2em;margin:.67em 0}mark{background:#ff0;color:#000}small{font-size:80%}sub,sup{font-size:75%;line-height:0;position:relative;vertical-align:baseline}sup{top:-.5em}sub{bottom:-.25em}img{border:0}svg:not(:root){overflow:hidden}figure{margin:1em 40px}hr{-moz-box-sizing:content-box;box-sizing:content-box;height:0}pre{overflow:auto}code,kbd,pre,samp{font-family:monospace,monospace;font-size:1em}button,input,optgroup,select,textarea{color:inherit;font:inherit;margin:0}button{overflow:visible}button,select{text-transform:none}button,html input[type=button],input[type=reset],input[type=submit]{-webkit-appearance:button;cursor:pointer}button[disabled],html input[disabled]{cursor:default}button::-moz-focus-inner,input::-moz-focus-inner{border:0;padding:0}input{line-height:normal}input[type=checkbox],input[type=radio]{box-sizing:border-box;padding:0}input[type=number]::-webkit-inner-spin-button,input[type=number]::-webkit-outer-spin-button{height:auto}input[type=search]{-webkit-appearance:textfield;-moz-box-sizing:content-box;-webkit-box-sizing:content-box;box-sizing:content-box}input[type=search]::-webkit-search-cancel-button,input[type=search]::-webkit-search-decoration{-webkit-appearance:none}fieldset{border:1px solid silver;margin:0 2px;padding:.35em .625em .75em}legend{border:0;padding:0}textarea{overflow:auto}optgroup{font-weight:700}table{border-collapse:collapse;border-spacing:0}td,th{padding:0}

            body {
                background-color: #272b35;
                color: #eff1f5;
            }

            a {
                color: #eff1f5;
                text-decoration: none;
            }

            a:hover {
                text-decoration: underline;
            }

            .container {
                margin: 0 auto;
                width: 960px;
            }

            header,
            footer {
                padding: 25px;
            }

            .logo img {
                display: block;
                height: 100px;
                margin: 25px auto;
                width: 100px;
            }

            .pre-esque {
                background: #343d46;
                position: relative;
            }

            .pre-esque:before {
                position: absolute;
                right: 0;
                width: 50%;
                top: 0;
                bottom: 0;
                content: ' ';
                background: #2b303b;
            }

            .pre-container {
                background: #2b303b;
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
                padding: 30px;
                position: relative;
            }

            .pre-message {
                font: 18px/30px "Ubuntu Mono", monospace;
                position: relative;
                margin-left: -75px;
            }

            .pre-message .line {
                counter-increment: linenumber;
                display: block;
            }

            .pre-message .line:before {
                color: #65737e;
                content: counter(linenumber);
                margin-right: 80px;
            }

            .pre-message .comment {
                color: #65737e;
            }

            .pre-message .function {
                color: #8fa1b3;
            }

            .pre-message .keyword {
                color: #d08770;
            }

            .pre-message .method {
                color: #b48ead;
            }

            .pre-message .number {
                color: #bf616a;
            }

            .pre-message .string {
                color: #a3be8c;
            }

            .copyright {
                color: #65737e;
                font-size: 13px;
                text-align: center;
            }
CSS;
    }
}
