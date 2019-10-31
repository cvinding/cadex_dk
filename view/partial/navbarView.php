<?php
namespace VIEW\PARTIAL;

class NavbarView extends \VIEW\PARTIAL\BASE\PartialView {

    private $currentPage;

    private $navLinks = [
        "Hjem" => ["url" => ""],
        "Produkter" => ["url" => "products"],
        "Nyheder" => ["url" => "news", "session" => ["LOGIN/STATUS" => true]],
        "Log ind" => ["url" => "login", "session" => ["LOGIN/STATUS" => false], "right" => true],
        "Log ud" => ["url" => "logout", "session" => ["LOGIN/STATUS" => true], "right" => true]
    ];

    public function __construct(\Request $request) {
        $this->currentPage = $request->page;
    }

    public function build() {
        return $this->createNavbar($this->createNavItems($this->navLinks));
    }

    private function createNavbar(string $navItems) {
        return \HELPER\Renderer::render("ui-elements/navbar.php", ["navItems" => $navItems]);
    }

    private function createNavItems(array $navLinks) {

        $sessions = [];
        $rightLinks = [];

        $navItems = '<ul class="navbar-nav mr-auto">';

        foreach($navLinks as $propName => $properties) {

            $dontShow = false;

            if(isset($properties["right"]) && $properties["right"] === true) {
                $rightLinks[$propName] = $properties;
                continue;
            }

            if(isset($properties["session"])) {

                foreach($properties["session"] as $name => $expectedValue) {
                    
                    if(!isset($sessions[$name])) {
                        $sessions[$name] = \SESSION\Session::get($name);
                    }

                    if($sessions[$name] !== $expectedValue) {
                        $dontShow = true;
                        break;
                    }

                }

                if($dontShow) {
                    continue;
                }
            }
            
            $navItems .= ($this->currentPage === $properties["url"]) ? '<li class="nav-item active">' : '<li class="nav-item">';
                $navItems .= '<a class="nav-link" href="/' . $properties["url"] . '">' . $propName . '</a>';
            $navItems .= '</li>';

        }

        $navItems .= '</ul>';

        $navItems .= '<ul class="navbar-nav mr-2">';

        foreach($rightLinks as $propName => $properties) {

            $dontShow = false;

            if(isset($properties["session"])) {

                foreach($properties["session"] as $name => $expectedValue) {
                    
                    if(!isset($sessions[$name])) {
                        $sessions[$name] = \SESSION\Session::get($name);
                    }

                    if($sessions[$name] !== $expectedValue) {
                        $dontShow = true;
                        break;
                    }

                }

                if($dontShow) {
                    continue;
                }
            }

            $navItems .= ($this->currentPage === $properties["url"]) ? '<li class="nav-item active">' : '<li class="nav-item">';
                $navItems .= '<a class="nav-link" href="/' . $properties["url"] . '">' . $propName . '</a>';
            $navItems .= '</li>';
        }

        $navItems .= '</ul>';

        return $navItems;
    }

}