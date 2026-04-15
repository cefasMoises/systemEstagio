<?php








if (!function_exists("voidStatus")) {
    function voidStatus(string $text = "N/A")
    {

        return <<<HTML

        <span class=" flex items-center justify-center px-1 rounded h-6 w-12 text-sm bg-yellow-200 border-gray-800 border border-solid text-gray-800 " title="campo vazio...">{$text}</span>

        HTML;

    }

}