<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* layout/header.html.twig */
class __TwigTemplate_b4d4f05c43c2f701d4e5920541ba98b0 extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "layout/header.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "layout/header.html.twig"));

        // line 1
        yield "<header>

";
        // line 4
        yield "
    <nav>
        <ul>
            <li><a href=\"#\">Accueil</a></li>
            <li><a href=\"#\">Profil</a></li>
            <li><a href=\"#\">Panel Admin</a></li>
            <li><a href=\"#\">Se connecter</a></li>
            <li><a href=\"#\">Se déconnecter</a></li>
            <li><a href=\"#\">S'inscrire</a></li>
        </ul>
    </nav>
    
</header>";
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "layout/header.html.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  52 => 4,  48 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<header>

{# FUTUR EMPLACEMENT DU LOGO #}

    <nav>
        <ul>
            <li><a href=\"#\">Accueil</a></li>
            <li><a href=\"#\">Profil</a></li>
            <li><a href=\"#\">Panel Admin</a></li>
            <li><a href=\"#\">Se connecter</a></li>
            <li><a href=\"#\">Se déconnecter</a></li>
            <li><a href=\"#\">S'inscrire</a></li>
        </ul>
    </nav>
    
</header>", "layout/header.html.twig", "C:\\wamp64\\www\\Arinfo\\symfony\\mini-twitter\\templates\\layout\\header.html.twig");
    }
}
