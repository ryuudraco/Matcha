<?php

/* layouts/master.html */
class __TwigTemplate_77d7de7e62bf4e338233227de3b2aed06debf80acc3865514aba373620862d4c extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = array(
            'body' => array($this, 'block_body'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html>
    <head>
        <meta charset=\"utf-8\"/>
        <title>";
        // line 5
        echo twig_escape_filter($this->env, ($context["ENV_APP_NAME"] ?? null), "html", null, true);
        echo "</title>
        <link href='//fonts.googleapis.com/css?family=Lato:300' rel='stylesheet' type='text/css'>
        <style>
            body {
                margin: 50px 0 0 0;
                padding: 0;
                width: 100%;
                font-family: \"Helvetica Neue\", Helvetica, Arial, sans-serif;
                text-align: center;
                color: #aaa;
                font-size: 18px;
            }

            h1 {
                color: #719e40;
                letter-spacing: -3px;
                font-family: 'Lato', sans-serif;
                font-size: 100px;
                font-weight: 200;
                margin-bottom: 0;
            }
        </style>
    </head>
    <body>
        ";
        // line 29
        $this->displayBlock('body', $context, $blocks);
        // line 30
        echo "    </body>
</html>
";
    }

    // line 29
    public function block_body($context, array $blocks = array())
    {
        echo "Empty page";
    }

    public function getTemplateName()
    {
        return "layouts/master.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  65 => 29,  59 => 30,  57 => 29,  30 => 5,  24 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "layouts/master.html", "/var/www/templates/layouts/master.html");
    }
}
