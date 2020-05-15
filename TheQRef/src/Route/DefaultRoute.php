<?php
declare(strict_types=1);

namespace src\route;


class DefaultRoute extends Route
{
    /**
     * Regex izraz u kojem su imenovane grupe zamjenjene konkrentnim vrijednostima,
     * spremljeno da se ne treba ponovo raditi zamjena imenovanih grupa.
     */
    private string $regex;

    /**
     * Originalni predani regex izraz koji sadrži izvorne imenovane grupe.
     */
    private string $input;

    /**
     * Asocijaticno polje imenovanih grupa i njihovih vrijednosti.
     */
    private array $namedGroupMap;

    /**
     * Konstruktorska metoda.
     * @param string $input ulazni regularni izraz.
     * Posebni parametri nalaze se unutar tagova <>, te se smiju iskljucivo sastojati
     * od brojeva, malih slova engleske abecede te znaka podvlake.
     * @param array $regex mapa imena parametara i prudruzenih
     * regularnih izraza ; regularni izrazi moraju biti valjani i u
     * skladu s PHP notacijom da bi ih PCRE mogao izvoditi.
     */
    public function __construct(string $input, array $regex = [])
    {
        $this->input = $input;
        $this->regex = $input;
        $this->namedGroupMap = $regex;
        foreach ($regex as $key => $value) {
            $this->regex = (string)preg_replace("/<$key>/", $value, $this->regex);
        }
    }

    public static function parseRoutes(string $path)
    {
        $routesArray = yaml_parse(file_get_contents($path));
        foreach ($routesArray as $name => $data){
            $url = $data['url'];
            $controller = $data['controller'];
            $method = $data['method'];
            $route = new DefaultRoute($url, ['controller' => $controller, 'method' => $method]);
            Route::register($name, $route);
        }
    }

    /**
     * Nadjacana metoda.
     *
     * @param string $input
     * @return bool
     */
    public function match(string $input): bool
    {
        return preg_match("/^$this->regex$/", $input) === 1 ? true : false;
    }

    /**
     * Nadjacana metoda.
     *
     * @param array $array
     * @return string
     */
    public function generate(array $array = []): string
    {
        $result = $this->input;

        foreach ($this->namedGroupMap as $key => $value) {
            if (!isset($array[$key])) {
                $replacement = "-Missing param $key-";
            } else {
                $replacement = preg_match("/$value/", $array[$key]) ? $array[$key] : "-Value $key not valid-";
            }
            $result = preg_replace("/<$key>/", $replacement, $result);
        }
        return $result;
    }

    public function getValue($key)
    {
        return $this->namedGroupMap[$key] ?? null;
    }

}