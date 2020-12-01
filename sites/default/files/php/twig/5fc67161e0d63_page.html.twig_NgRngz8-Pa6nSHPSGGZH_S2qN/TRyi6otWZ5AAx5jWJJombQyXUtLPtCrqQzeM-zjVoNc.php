<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* themes/TH_base2/templates/page.html.twig */
class __TwigTemplate_67d722bce1545eee88d6c99d45be02a9e218e13f409e3aafb357469493073942 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = ["set" => 1, "if" => 18];
        $filters = ["render" => 1, "escape" => 3];
        $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['set', 'if'],
                ['render', 'escape'],
                []
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->getSourceContext());

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 1
        $context["main_menu"] = $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "primary_menu", [])));
        // line 2
        $context["secondary_menu"] = $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "secondary_menu", [])));
        // line 3
        echo "<div";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["attributes"] ?? null), "addClass", [0 => "layout-container", 1 => (((        // line 5
($context["main_menu"] ?? null) || ($context["secondary_menu"] ?? null))) ? ("with-navigation") : ("")), 2 => ((        // line 6
($context["secondary_menu"] ?? null)) ? ("with-subnav") : (""))], "method")), "html", null, true);
        // line 7
        echo ">

  <!-- ______________________ HEADER _______________________ -->

    <nav id=\"top-top-nav\" class=\"menu\">
      <div class=\"container\">
        ";
        // line 13
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "top_navigation", [])), "html", null, true);
        echo "
      </div>
    </nav><!-- /#toptopnav -->


  ";
        // line 18
        if ($this->getAttribute(($context["page"] ?? null), "header", [])) {
            // line 19
            echo "    <header id=\"header\">
      <div class=\"container\">
        <div id=\"header-region\">
          ";
            // line 22
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "header", [])), "html", null, true);
            echo "
        </div>
        <nav id=\"service-menu\" class=\"menu\">
          <div class=\"container\">
            ";
            // line 26
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "service_menu", [])), "html", null, true);
            echo "
          </div>
        </nav><!-- /#navigation -->
      </div>
    </header><!-- /#header -->
  ";
        }
        // line 32
        echo "
    <div id=\"page-hero\" class=\"hero\">
      <div class=\"container\">
        ";
        // line 35
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "hero", [])), "html", null, true);
        echo "
      </div>
    </div><!-- /#Hero -->

  <!-- ______________________ MAIN _______________________ -->

  <div id=\"main\">
    <div class=\"container\">

      <div id=\"main-top\">
        <div class=\"container\">
          ";
        // line 46
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "main_top", [])), "html", null, true);
        echo "
        </div>
      </div><!-- /#main-top -->

      <div id=\"content-wrapper\">
        <section id=\"content\">

          <div id=\"content-header\">

            ";
        // line 55
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "breadcrumb", [])), "html", null, true);
        echo "

            ";
        // line 57
        if ($this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->getAttribute(($context["page"] ?? null), "highlighted", []))) {
            // line 58
            echo "              <div id=\"highlighted\">";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "highlighted", [])), "html", null, true);
            echo "</div>
            ";
        }
        // line 60
        echo "
            ";
        // line 61
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title_prefix"] ?? null)), "html", null, true);
        echo "

            ";
        // line 63
        if (($context["title"] ?? null)) {
            // line 64
            echo "              <h1 class=\"title\">";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title"] ?? null)), "html", null, true);
            echo "</h1>
            ";
        }
        // line 66
        echo "
            ";
        // line 67
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title_suffix"] ?? null)), "html", null, true);
        echo "
            ";
        // line 68
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "help", [])), "html", null, true);
        echo "

            ";
        // line 70
        if (($context["tabs"] ?? null)) {
            // line 71
            echo "              <div class=\"tabs\">";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["tabs"] ?? null)), "html", null, true);
            echo "</div>
            ";
        }
        // line 73
        echo "
            ";
        // line 74
        if (($context["action_links"] ?? null)) {
            // line 75
            echo "              <ul class=\"action-links\">";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["action_links"] ?? null)), "html", null, true);
            echo "</ul>
            ";
        }
        // line 77
        echo "
          </div><!-- /#content-header -->

          <div id=\"content-area\">
            ";
        // line 81
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "content", [])), "html", null, true);
        echo "
          </div>

        </section><!-- /#content -->

        ";
        // line 86
        if ($this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->getAttribute(($context["page"] ?? null), "sidebar_first", []))) {
            // line 87
            echo "          <aside id=\"sidebar-first\" class=\"column sidebar first\">
            ";
            // line 88
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "sidebar_first", [])), "html", null, true);
            echo "
          </aside><!-- /#sidebar-first -->
        ";
        }
        // line 91
        echo "
        ";
        // line 92
        if ($this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->getAttribute(($context["page"] ?? null), "sidebar_second", []))) {
            // line 93
            echo "          <aside id=\"sidebar-second\" class=\"column sidebar second\">
            ";
            // line 94
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "sidebar_second", [])), "html", null, true);
            echo "
          </aside><!-- /#sidebar-second -->
        ";
        }
        // line 97
        echo "
      </div><!-- /#content-wrapper -->

      <div id=\"main-bottom\">
        <div class=\"container\">
          ";
        // line 102
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "main_bottom", [])), "html", null, true);
        echo "
        </div>
      </div><!-- /#main-bottom -->

    </div><!-- /.container -->
  </div><!-- /#main -->

  <!-- ______________________ FOOTER _______________________ -->

  ";
        // line 111
        if ($this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->getAttribute(($context["page"] ?? null), "footer", []))) {
            // line 112
            echo "    <footer id=\"footer\">
      <div class=\"container\">
        <div id=\"footer-region\">
          ";
            // line 115
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "footer", [])), "html", null, true);
            echo "
        </div>
      </div>
    </footer><!-- /#footer -->
  ";
        }
        // line 120
        echo "
  ";
        // line 121
        if ($this->getAttribute(($context["page"] ?? null), "th_code", [])) {
            // line 122
            echo "    <footer id=\"th_code\">
      <div class=\"container\">
        <div id=\"th_code-region\">
          ";
            // line 125
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "th_code", [])), "html", null, true);
            echo "
        </div>
      </div>
    </footer><!-- /#footer -->
  ";
        }
        // line 130
        echo "
</div><!-- /.layout-container -->
";
    }

    public function getTemplateName()
    {
        return "themes/TH_base2/templates/page.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  285 => 130,  277 => 125,  272 => 122,  270 => 121,  267 => 120,  259 => 115,  254 => 112,  252 => 111,  240 => 102,  233 => 97,  227 => 94,  224 => 93,  222 => 92,  219 => 91,  213 => 88,  210 => 87,  208 => 86,  200 => 81,  194 => 77,  188 => 75,  186 => 74,  183 => 73,  177 => 71,  175 => 70,  170 => 68,  166 => 67,  163 => 66,  157 => 64,  155 => 63,  150 => 61,  147 => 60,  141 => 58,  139 => 57,  134 => 55,  122 => 46,  108 => 35,  103 => 32,  94 => 26,  87 => 22,  82 => 19,  80 => 18,  72 => 13,  64 => 7,  62 => 6,  61 => 5,  59 => 3,  57 => 2,  55 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "themes/TH_base2/templates/page.html.twig", "/Users/trentonmarks/Sites/devdesktop/decoupled-drupal/themes/TH_base2/templates/page.html.twig");
    }
}
